<?php

namespace App\Http\Controllers;

use App\Models\ServerFisik;
use App\Models\AssetHistory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServerFisikController extends Controller
{
    public function index(Request $request)
    {
        $query = ServerFisik::query();

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
        $lokasi = ServerFisik::select('lokasi_aset_saat_ini')->distinct()->orderBy('lokasi_aset_saat_ini')->pluck('lokasi_aset_saat_ini');

        return view('assets.serverfisik.index', compact('servers', 'lokasi'));
    }

    public function create()
    {
        $asset = null;
        return view('assets.serverfisik.form', compact('asset'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateInput($request);
        $validated['klasifikasi_keamanan'] = $validated['klasifikasi_keamanan'] ?? 'internal';
        $validated['tanggal_perolehan'] = $validated['tanggal_perolehan'] ?? now()->toDateString();
        $validated['pic_pencatat'] = $request->input('pic_pencatat') ?? auth()->user()->name;

        $server = ServerFisik::create($validated);

        AssetHistory::create([
            'asset_id' => $server->id,
            'user_id' => auth()->id(),
            'action' => 'TAMBAH',
            'description' => 'Menambahkan Server Fisik baru: ' . $server->merk . ' ' . $server->model,
        ]);

        return redirect()->route('manage-server-fisik')->with('success', 'Server Fisik berhasil ditambahkan.');
    }

    public function edit(ServerFisik $serverFisik)
    {
        $asset = $serverFisik;
        return view('assets.serverfisik.form', compact('asset'));
    }

    public function update(Request $request, ServerFisik $serverFisik)
    {
        $validated = $this->validateInput($request, $serverFisik->id);
        $validated['klasifikasi_keamanan'] = $validated['klasifikasi_keamanan'] ?? 'internal';
        $validated['pic_pencatat'] = $request->input('pic_pencatat') ?? auth()->user()->name;

        $serverFisik->update($validated);

        AssetHistory::create([
            'asset_id' => $serverFisik->id,
            'user_id' => auth()->id(),
            'action' => 'UBAH',
            'description' => 'Memperbarui data Server Fisik: ' . $serverFisik->id_aset,
        ]);

        return redirect()->route('manage-server-fisik')->with('success', 'Server Fisik berhasil diperbarui.');
    }

    public function destroy(ServerFisik $serverFisik)
    {
        $idAset = $serverFisik->id_aset;
        $serverId = $serverFisik->id;
        $serverFisik->delete();

        AssetHistory::create([
            'asset_id' => $serverId,
            'user_id' => auth()->id(),
            'action' => 'HAPUS',
            'description' => 'Menghapus Server Fisik: ' . $idAset,
        ]);

        return redirect()->route('manage-server-fisik')->with('success', 'Data Server Fisik berhasil dihapus.');
    }

    public function importForm()
    {
        return view('assets.serverfisik.import');
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
                    
                    if (count($row) < 38) {
                        $skippedReasons[] = "Baris ke-{$rowIndex} dilewati: Jumlah kolom kurang dari template (38 kolom).";
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

                    $server = ServerFisik::create([
                        'id_aset'                       => !empty(trim($row[1] ?? '')) ? trim($row[1]) : 'SRVF-' . strtoupper(uniqid()),
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
                        
                        'hostname'                      => trim($row[15] ?? ''),
                        'merk'                          => trim($merk),
                        'model'                         => trim($model),
                        'serial_number'                 => trim($row[18] ?? ''),
                        'cpu'                           => trim($row[19] ?? ''),
                        'ram'                           => trim($row[20] ?? ''),
                        'disk_array'                    => trim($row[21] ?? ''),
                        'kapasitas_total_storage'       => trim($row[22] ?? ''),
                        'mac_address'                   => trim($row[23] ?? ''),
                        'ip_address'                    => trim($row[24] ?? ''),
                        'sistem_operasi'                => trim($row[25] ?? ''),
                        'konsumsi_daya'                 => !empty(trim($row[26] ?? '')) ? (float) trim($row[26]) : null,
                        'rack'                          => trim($row[27] ?? ''),
                        'masa_berlaku_garansi'          => $parseDate($row[28] ?? ''),
                        'keterangan'                    => trim($row[29] ?? ''),
                        // ROW 30 = SERVER VIRTUAL HEADER (DILEWATI)
                        'hostname_virtual'              => trim($row[31] ?? ''),
                        'jumlah_vcpu'                   => trim($row[32] ?? ''),
                        'kapasitas_vram'                => trim($row[33] ?? ''),
                        'kapasitas_storage_virtual'     => trim($row[34] ?? ''),
                        'mac_address_virtual'           => trim($row[35] ?? ''),
                        'ip_address_virtual'            => trim($row[36] ?? ''),
                        'sistem_operasi_virtual'        => trim($row[37] ?? ''),
                        'aplikasi_virtual'              => trim($row[38] ?? ''),
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
                'description' => "Melakukan impor massal data Server Fisik ({$successCount} data berhasil).",
            ]);
        }

        $message = "Berhasil mengimpor {$successCount} data Server Fisik.";
        if (count($skippedReasons) > 0) {
            session()->flash('import_skipped_reasons', $skippedReasons);
        }

        return redirect()->route('manage-server-fisik')->with('success', $message);
    }

    private function validateInput(Request $request, $id = null)
    {
        return $request->validate([
            'id_aset' => ['required', 'string', 'max:255', $id ? Rule::unique('server_fisiks', 'id_aset')->ignore($id) : 'unique:server_fisiks,id_aset'],
            'merk' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'hostname' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'cpu' => 'nullable|string|max:255',
            'ram' => 'nullable|string|max:255',
            'disk_array' => 'nullable|string|max:255',
            'kapasitas_total_storage' => 'nullable|string|max:255',
            'mac_address' => 'nullable|string|max:255',
            'ip_address' => 'nullable|string|max:255',
            'sistem_operasi' => 'nullable|string|max:255',
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
            // Virtual fields
            'hostname_virtual' => 'nullable|string',
            'jumlah_vcpu' => 'nullable|string|max:255',
            'kapasitas_vram' => 'nullable|string|max:255',
            'kapasitas_storage_virtual' => 'nullable|string|max:255',
            'mac_address_virtual' => 'nullable|string',
            'ip_address_virtual' => 'nullable|string',
            'sistem_operasi_virtual' => 'nullable|string',
            'aplikasi_virtual' => 'nullable|string',
            'bidang_pencatat_aset' => 'nullable|string|max:255',
        ]);
    }
}