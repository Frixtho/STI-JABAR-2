<?php

namespace App\Http\Controllers;

use App\Models\Modem;
use App\Models\AssetHistory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ModemController extends Controller
{
    public function index(Request $request)
    {
        $query = Modem::query();

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

        $modems = $query->orderBy('id_aset')->paginate(15)->withQueryString();

        $lokasi = Modem::select('lokasi_aset_saat_ini')->distinct()->orderBy('lokasi_aset_saat_ini')->pluck('lokasi_aset_saat_ini');

        return view('assets.modem.index', compact('modems', 'lokasi'));
    }

    public function create()
    {
        $asset = null;
        return view('assets.modem.form', compact('asset'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_aset' => 'required|string|max:255|unique:modems,id_aset',
            'merk' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'tipe_koneksi' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'ip_address' => 'nullable|string|max:255',
            'mac_address' => 'nullable|string|max:255',
            'lokasi_aset_saat_ini' => 'required|string|max:255',
            'status_kondisi' => 'required|string|max:255',
            'status_operasional' => 'required|string|max:255',
            'tingkat_kritikalitas' => 'required|string|max:255',
            'klasifikasi_keamanan' => 'nullable|string|max:255', // Ubah jadi nullable agar aman
            'status_kepemilikan' => 'nullable|string|max:255',
            'tanggal_perolehan' => 'nullable|date',
            'versi_firmware' => 'nullable|string|max:255',
            'konsumsi_daya' => 'nullable|numeric',
        ]);

        // Berikan nilai default jika kosong untuk kolom yang wajib di database
        $validated['klasifikasi_keamanan'] = $validated['klasifikasi_keamanan'] ?? 'internal';
        $validated['tanggal_perolehan'] = $validated['tanggal_perolehan'] ?? now()->toDateString();
        $validated['pic_pencatat'] = $request->input('pic_pencatat') ?? auth()->user()->name;

        $modem = Modem::create($validated);

        AssetHistory::create([
            'asset_id' => $modem->id,
            'user_id' => auth()->id(),
            'action' => 'TAMBAH',
            'description' => 'Menambahkan aset Modem baru: ' . $modem->merk . ' ' . $modem->model,
        ]);

        return redirect()->route('manage-modem')->with('success', 'Aset Modem berhasil ditambahkan.');
    }

    public function edit(Modem $modem)
    {
        $asset = $modem;
        return view('assets.modem.form', compact('asset'));
    }

    public function update(Request $request, Modem $modem)
    {
        $validated = $request->validate([
            'id_aset' => ['required', 'string', 'max:255', Rule::unique('modems', 'id_aset')->ignore($modem->id)],
            'merk' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'tipe_koneksi' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'ip_address' => 'nullable|string|max:255',
            'mac_address' => 'nullable|string|max:255',
            'lokasi_aset_saat_ini' => 'required|string|max:255',
            'status_kondisi' => 'required|string|max:255',
            'status_operasional' => 'required|string|max:255',
            'tingkat_kritikalitas' => 'required|string|max:255',
            'klasifikasi_keamanan' => 'nullable|string|max:255',
            'status_kepemilikan' => 'nullable|string|max:255',
            'tanggal_perolehan' => 'nullable|date',
            'versi_firmware' => 'nullable|string|max:255',
            'konsumsi_daya' => 'nullable|numeric',
        ]);

        $modem->update($validated);

        AssetHistory::create([
            'asset_id' => $modem->id,
            'user_id' => auth()->id(),
            'action' => 'UBAH',
            'description' => 'Memperbarui data aset Modem: ' . $modem->id_aset,
        ]);

        return redirect()->route('manage-modem')->with('success', 'Aset Modem berhasil diperbarui.');
    }

    public function destroy(Modem $modem)
    {
        $modemIdAset = $modem->id_aset;
        $modem->delete();

        AssetHistory::create([
            'asset_id' => $modem->id,
            'user_id' => auth()->id(),
            'action' => 'HAPUS',
            'description' => 'Menghapus aset Modem: ' . $modemIdAset,
        ]);

        return redirect()->route('manage-modem')->with('success', 'Data Modem berhasil dihapus.');
    }

    public function importForm()
    {
        return view('assets.modem.import');
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
        $modem = null;

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
                        $skippedReasons[] = "Baris ke-{$rowIndex} dilewati: Jumlah kolom kurang dari standar.";
                        continue;
                    }
                    if (empty(trim($row[1] ?? '')) && empty(trim($row[15] ?? ''))) continue;

                    $merk = $row[15] ?? null;
                    $model = $row[16] ?? null;
                    $tipeKoneksi = $row[20] ?? null;

                    if (empty(trim($merk)) || empty(trim($model)) || empty(trim($tipeKoneksi))) {
                        $skippedReasons[] = "Baris ke-{$rowIndex} dilewati: Kolom wajib (Merk, Model, atau Tipe Koneksi) kosong.";
                        continue;
                    }

                    $parseDate = fn($val) => !empty(trim($val ?? '')) && strtotime($val) ? date('Y-m-d', strtotime($val)) : null;

                    $modem = Modem::create([
                        'id_aset'                       => !empty(trim($row[1] ?? '')) ? trim($row[1]) : 'MODEM-' . strtoupper(uniqid()),
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
                        'merk'                          => trim($merk),
                        'model'                         => trim($model),
                        'serial_number'                 => trim($row[17] ?? ''),
                        'mac_address'                   => trim($row[18] ?? ''),
                        'ip_address'                    => trim($row[19] ?? ''),
                        'tipe_koneksi'                  => trim($tipeKoneksi),
                        'versi_firmware'                => trim($row[21] ?? ''),
                        'konsumsi_daya'                 => !empty(trim($row[22] ?? '')) ? (float) trim($row[22]) : null,
                        'rack'                          => trim($row[23] ?? ''),
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

        if ($successCount > 0 && $modem) {
            AssetHistory::create([
                'asset_id' => $modem->id,
                'user_id' => auth()->id(),
                'action' => 'TAMBAH',
                'description' => "Melakukan impor massal data Modem ({$successCount} data berhasil).",
            ]);
        }

        $message = "Berhasil mengimpor {$successCount} data Modem.";
        if (count($skippedReasons) > 0) {
            session()->flash('import_skipped_reasons', $skippedReasons);
        }

        return redirect()->route('manage-modem')->with('success', $message);
    }
}