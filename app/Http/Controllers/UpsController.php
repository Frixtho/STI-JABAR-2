<?php

namespace App\Http\Controllers;

use App\Models\Ups;
use App\Models\AssetHistory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpsController extends Controller
{
    public function index(Request $request)
    {
        $query = Ups::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('id_aset', 'ILIKE', "%{$search}%")
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

        $upsList = $query->orderBy('id_aset')->paginate(15)->withQueryString();
        $lokasi = Ups::select('lokasi_aset_saat_ini')->distinct()->orderBy('lokasi_aset_saat_ini')->pluck('lokasi_aset_saat_ini');

        return view('manageUps', compact('upsList', 'lokasi'));
    }

    public function create()
    {
        $asset = null;
        return view('upsForm', compact('asset'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateInput($request);
        $validated['klasifikasi_keamanan'] = $validated['klasifikasi_keamanan'] ?? 'internal';
        $validated['tanggal_perolehan'] = $validated['tanggal_perolehan'] ?? now()->toDateString();
        $validated['pic_pencatat'] = $request->input('pic_pencatat') ?? auth()->user()->name;

        $ups = Ups::create($validated);

        AssetHistory::create([
            'asset_id' => $ups->id,
            'user_id' => auth()->id(),
            'action' => 'TAMBAH',
            'description' => 'Menambahkan UPS baru: ' . $ups->merk . ' ' . $ups->model,
        ]);

        return redirect()->route('manage-ups')->with('success', 'Aset UPS berhasil ditambahkan.');
    }

    public function edit(Ups $ups)
    {
        $asset = $ups;
        return view('upsForm', compact('asset'));
    }

    public function update(Request $request, Ups $ups)
    {
        $validated = $this->validateInput($request, $ups->id);
        $validated['klasifikasi_keamanan'] = $validated['klasifikasi_keamanan'] ?? 'internal';
        $validated['pic_pencatat'] = $request->input('pic_pencatat') ?? auth()->user()->name;

        $ups->update($validated);

        AssetHistory::create([
            'asset_id' => $ups->id,
            'user_id' => auth()->id(),
            'action' => 'UBAH',
            'description' => 'Memperbarui data UPS: ' . $ups->id_aset,
        ]);

        return redirect()->route('manage-ups')->with('success', 'Aset UPS berhasil diperbarui.');
    }

    public function destroy(Ups $ups)
    {
        $idAset = $ups->id_aset;
        $upsId = $ups->id;
        $ups->delete();

        AssetHistory::create([
            'asset_id' => $upsId,
            'user_id' => auth()->id(),
            'action' => 'HAPUS',
            'description' => 'Menghapus UPS: ' . $idAset,
        ]);

        return redirect()->route('manage-ups')->with('success', 'Data UPS berhasil dihapus.');
    }

    public function importForm()
    {
        return view('upsImport');
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
        $ups = null;

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
                    
                    if (count($row) < 26) {
                        $skippedReasons[] = "Baris ke-{$rowIndex} dilewati: Jumlah kolom kurang dari template (26 kolom).";
                        continue;
                    }
                    if (empty(trim($row[1] ?? '')) && empty(trim($row[15] ?? ''))) continue;

                    $merk = $row[15] ?? null;
                    $model = $row[16] ?? null;

                    if (empty(trim($merk)) || empty(trim($model))) {
                        $skippedReasons[] = "Baris ke-{$rowIndex} dilewati: Kolom wajib (Merk, Model) kosong.";
                        continue;
                    }

                    $parseDate = fn($val) => !empty(trim($val ?? '')) && strtotime($val) ? date('Y-m-d', strtotime($val)) : null;

                    $ups = Ups::create([
                        'id_aset'                       => !empty(trim($row[1] ?? '')) ? trim($row[1]) : 'UPS-' . strtoupper(uniqid()),
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
                        
                        // Atribut Spesifik UPS
                        'merk'                          => trim($merk),
                        'model'                         => trim($model),
                        'serial_number'                 => trim($row[17] ?? ''),
                        'tipe_kimia'                    => trim($row[18] ?? ''),
                        'ip_address'                    => trim($row[19] ?? ''),
                        'jumlah_baterai'                => !empty(trim($row[20] ?? '')) ? (int) trim($row[20]) : null,
                        'kapasitas_total'               => !empty(trim($row[21] ?? '')) ? (float) trim($row[21]) : null,
                        'spesifikasi'                   => trim($row[22] ?? ''),
                        'konsumsi_daya'                 => !empty(trim($row[23] ?? '')) ? (float) trim($row[23]) : null,
                        'masa_berlaku_garansi'          => $parseDate($row[24] ?? ''),
                        'keterangan'                    => trim($row[25] ?? ''),
                    ]);

                    $successCount++;
                }
                fclose($handle);
            } catch (\Exception $e) {
                if (isset($handle) && is_resource($handle)) fclose($handle);
                return back()->with('error', 'Gagal memproses file: ' . $e->getMessage());
            }
        }

        if ($successCount > 0 && $ups) {
            AssetHistory::create([
                'asset_id' => $ups->id,
                'user_id' => auth()->id(),
                'action' => 'TAMBAH',
                'description' => "Melakukan impor massal data UPS ({$successCount} data berhasil).",
            ]);
        }

        $message = "Berhasil mengimpor {$successCount} data UPS.";
        if (count($skippedReasons) > 0) {
            session()->flash('import_skipped_reasons', $skippedReasons);
        }

        return redirect()->route('manage-ups')->with('success', $message);
    }

    private function validateInput(Request $request, $id = null)
    {
        return $request->validate([
            'id_aset' => ['required', 'string', 'max:255', $id ? Rule::unique('ups', 'id_aset')->ignore($id) : 'unique:ups,id_aset'],
            'merk' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'tipe_kimia' => 'nullable|string|max:255',
            'ip_address' => 'nullable|string|max:255',
            'jumlah_baterai' => 'nullable|integer',
            'kapasitas_total' => 'nullable|numeric',
            'spesifikasi' => 'nullable|string',
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
            'masa_berlaku_garansi' => 'nullable|date',
            'keterangan' => 'nullable|string',
            'bidang_pencatat_aset' => 'nullable|string|max:255',
        ]);
    }
}