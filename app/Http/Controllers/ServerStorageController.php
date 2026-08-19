<?php

namespace App\Http\Controllers;

use App\Models\ServerStorage;
use App\Models\AssetHistory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServerStorageController extends Controller
{
    public function index(Request $request)
    {
        $query = ServerStorage::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('id_aset', 'ILIKE', "%{$search}%")
                    ->orWhere('hostname', 'ILIKE', "%{$search}%")
                    ->orWhere('merk', 'ILIKE', "%{$search}%")
                    ->orWhere('model', 'ILIKE', "%{$search}%")
                    ->orWhere('serial_number', 'ILIKE', "%{$search}%")
                    ->orWhere('ip_address', 'ILIKE', "%{$search}%")
                    ->orWhere('lokasi_aset_saat_ini', 'ILIKE', "%{$search}%");
            });
        }

        $query->when($request->filled('kondisi'), fn ($q) => $q->where('status_kondisi', $request->kondisi));
        $query->when($request->filled('status_operasional'), fn ($q) => $q->where('status_operasional', $request->status_operasional));
        $query->when($request->filled('lokasi'), fn ($q) => $q->where('lokasi_aset_saat_ini', 'ILIKE', "%{$request->lokasi}%"));

        $servers = $query->orderBy('id_aset')->paginate(15)->withQueryString();
        $lokasi = ServerStorage::select('lokasi_aset_saat_ini')->distinct()->orderBy('lokasi_aset_saat_ini')->pluck('lokasi_aset_saat_ini');

        return view('assets.serverstorage.index', compact('servers', 'lokasi'));
    }

    public function create()
    {
        $asset = null;
        return view('assets.serverstorage.form', compact('asset'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateInput($request);
        $validated['klasifikasi_keamanan'] = $validated['klasifikasi_keamanan'] ?? 'internal';
        $validated['tanggal_perolehan'] = $validated['tanggal_perolehan'] ?? now()->toDateString();
        $validated['pic_pencatat'] = $request->input('pic_pencatat') ?? auth()->user()->name;

        $server = ServerStorage::create($validated);

        AssetHistory::create([
            'asset_id' => $server->id,
            'user_id' => auth()->id(),
            'action' => 'TAMBAH',
            'description' => 'Menambahkan Server Storage baru: ' . $server->merk . ' ' . $server->model,
        ]);

        return redirect()->route('manage-server-storage')->with('success', 'Server Storage berhasil ditambahkan.');
    }

    public function edit(ServerStorage $serverStorage)
    {
        $asset = $serverStorage;
        return view('assets.serverstorage.form', compact('asset'));
    }

    public function update(Request $request, ServerStorage $serverStorage)
    {
        $validated = $this->validateInput($request, $serverStorage->id);
        $validated['klasifikasi_keamanan'] = $validated['klasifikasi_keamanan'] ?? 'internal';
        $validated['pic_pencatat'] = $request->input('pic_pencatat') ?? auth()->user()->name;

        $serverStorage->update($validated);

        AssetHistory::create([
            'asset_id' => $serverStorage->id,
            'user_id' => auth()->id(),
            'action' => 'UBAH',
            'description' => 'Memperbarui data Server Storage: ' . $serverStorage->id_aset,
        ]);

        return redirect()->route('manage-server-storage')->with('success', 'Server Storage berhasil diperbarui.');
    }

    public function destroy(ServerStorage $serverStorage)
    {
        $idAset = $serverStorage->id_aset;
        $serverId = $serverStorage->id;
        $serverStorage->delete();

        AssetHistory::create([
            'asset_id' => $serverId,
            'user_id' => auth()->id(),
            'action' => 'HAPUS',
            'description' => 'Menghapus Server Storage: ' . $idAset,
        ]);

        return redirect()->route('manage-server-storage')->with('success', 'Data Server Storage berhasil dihapus.');
    }

    public function importForm()
    {
        return view('assets.serverstorage.import');
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'files'   => ['required', 'array'],
            'files.*' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        if (!$request->hasFile('files')) {
            return back()->with('error', 'Tidak ada file yang diunggah.');
        }

        $skippedReasons = [];
        $successCount = 0;
        $server = null;

        foreach ($request->file('files') as $file) {
            try {
                $handle = fopen($file->getRealPath(), 'r');
                $sampleLine = fgets($handle);
                rewind($handle);
                $delimiter = (substr_count($sampleLine, ';') > substr_count($sampleLine, ',')) ? ';' : ',';

                $rowIndex = 0;
                while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                    $rowIndex++;
                    if ($rowIndex <= 5) continue; // Skip header template
                    
                    if (count($row) < 31) {
                        $skippedReasons[] = "Baris ke-{$rowIndex} dilewati: Jumlah kolom kurang dari template (31 kolom).";
                        continue;
                    }
                    if (empty(trim($row[1] ?? '')) && empty(trim($row[16] ?? ''))) continue;

                    $merk = $row[16] ?? null;
                    $model = $row[17] ?? null;

                    if (empty(trim($merk)) || empty(trim($model))) {
                        $skippedReasons[] = "Baris ke-{$rowIndex} dilewati: Kolom wajib (Merk, Model) kosong.";
                        continue;
                    }

                    $parseDate = fn($val) => !empty(trim($val ?? '')) && strtotime($val) ? date('Y-m-d', strtotime($val)) : null;

                    $server = ServerStorage::create([
                        'id_aset'                       => !empty(trim($row[1] ?? '')) ? trim($row[1]) : 'STRG-' . strtoupper(uniqid()),
                        'tanggal_perolehan'             => $parseDate($row[2] ?? '') ?? now()->toDateString(),
                        'status_kepemilikan'            => trim($row[3] ?? 'Milik PLN'),
                        'keterangan_status_kepemilikan' => trim($row[4] ?? ''),
                        'status_kondisi'                => strtolower(trim($row[5] ?? 'baik')),
                        'status_operasional'            => strtolower(trim($row[6] ?? 'aktif')),
                        'tingkat_kritikalitas'          => strtolower(trim($row[7] ?? 'normal')),
                        'klasifikasi_keamanan'          => strtolower(trim($row[8] ?? 'internal')),
                        'deskripsi_tujuan'              => trim($row[9] ?? ''),
                        'lokasi_aset_saat_ini'          => trim($row[10] ?? 'Pusat'),
                        'keterangan_lokasi_aset'        => trim($row[11] ?? ''),
                        'tanggal_pemeriksaan_terakhir'  => $parseDate($row[12] ?? ''),
                        'pic_pencatat'                  => trim($row[13] ?? '') ?: auth()->user()->name,
                        'bidang_pencatat_aset'          => trim($row[14] ?? ''),
                        
                        // Atribut Spesifik (Dimulai dari index 15)
                        'hostname'                      => trim($row[15] ?? ''),
                        'merk'                          => trim($merk),
                        'model'                         => trim($model),
                        'serial_number'                 => trim($row[18] ?? ''),
                        'cpu_controller'                => trim($row[19] ?? ''),
                        'ram'                           => trim($row[20] ?? ''),
                        'disk_array'                    => trim($row[21] ?? ''),
                        'kapasitas_total_storage'       => trim($row[22] ?? ''),
                        'mac_address'                   => trim($row[23] ?? ''),
                        'ip_address'                    => trim($row[24] ?? ''),
                        'versi_firmware'                => trim($row[25] ?? ''),
                        'aplikasi'                      => trim($row[26] ?? ''),
                        'app_standar_kamsiber'          => trim($row[27] ?? ''),
                        'konsumsi_daya'                 => !empty(trim($row[28] ?? '')) ? (float) trim($row[28]) : null,
                        'rack'                          => trim($row[29] ?? ''),
                        'masa_berlaku_garansi'          => $parseDate($row[30] ?? ''),
                        'keterangan'                    => trim($row[31] ?? ''),
                    ]);

                    $successCount++;
                }
                fclose($handle);
            } catch (\Exception $e) {
                if (isset($handle) && is_resource($handle)) fclose($handle);
                return back()->with('error', 'Gagal memproses file: ' . $e->getMessage());
            }
        }

        if ($successCount > 0 && $server) {
            AssetHistory::create([
                'asset_id' => $server->id,
                'user_id' => auth()->id(),
                'action' => 'TAMBAH',
                'description' => "Melakukan impor massal data Server Storage ({$successCount} data berhasil).",
            ]);
        }

        $message = "Berhasil mengimpor {$successCount} data Server Storage.";
        if (count($skippedReasons) > 0) {
            session()->flash('import_skipped_reasons', $skippedReasons);
        }

        return redirect()->route('manage-server-storage')->with('success', $message);
    }

    private function validateInput(Request $request, $id = null)
    {
        return $request->validate([
            'id_aset' => ['required', 'string', 'max:255', $id ? Rule::unique('server_storages', 'id_aset')->ignore($id) : 'unique:server_storages,id_aset'],
            'merk' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'hostname' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'cpu_controller' => 'nullable|string|max:255',
            'ram' => 'nullable|string|max:255',
            'disk_array' => 'nullable|string|max:255',
            'kapasitas_total_storage' => 'nullable|string|max:255',
            'mac_address' => 'nullable|string|max:255',
            'ip_address' => 'nullable|string|max:255',
            'versi_firmware' => 'nullable|string|max:255',
            'aplikasi' => 'nullable|string',
            'app_standar_kamsiber' => 'nullable|string|max:255',
            'lokasi_aset_saat_ini' => 'required|string|max:255',
            'keterangan_lokasi_aset' => 'nullable|string|max:255',
            'status_kondisi' => 'required|string|max:255',
            'status_operasional' => 'required|string|max:255',
            'tingkat_kritikalitas' => 'required|string|max:255',
            'klasifikasi_keamanan' => 'nullable|string|max:255',
            'status_kepemilikan' => 'nullable|string|max:255',
            'keterangan_status_kepemilikan' => 'nullable|string|max:255',
            'deskripsi_tujuan' => 'nullable|string',
            'tanggal_perolehan' => 'nullable|date',
            'tanggal_pemeriksaan_terakhir' => 'nullable|date',
            'konsumsi_daya' => 'nullable|numeric',
            'rack' => 'nullable|string|max:255',
            'masa_berlaku_garansi' => 'nullable|date',
            'keterangan' => 'nullable|string',
            'bidang_pencatat_aset' => 'nullable|string|max:255',
        ]);
    }
}