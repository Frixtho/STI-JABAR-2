<?php

namespace App\Http\Controllers;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\AccessPoint;
use App\Models\AssetHistory;
use Illuminate\Http\Request;

class AccessPointController extends Controller
{
    public function index(Request $request)
    {
        $query = AccessPoint::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('id_aset', 'ILIKE', "%{$search}%")
                    ->orWhere('merk', 'ILIKE', "%{$search}%")
                    ->orWhere('model', 'ILIKE', "%{$search}%")
                    ->orWhere('serial_number', 'ILIKE', "%{$search}%")
                    ->orWhere('ip_address', 'ILIKE', "%{$search}%")
                    ->orWhere('mac_address', 'ILIKE', "%{$search}%")
                    ->orWhere('lokasi_aset_saat_ini', 'ILIKE', "%{$search}%");
            });
        }

        $query->when(
            $request->filled('kondisi'),
            fn ($q) => $q->where('status_kondisi', $request->kondisi)
        );

        $query->when(
            $request->filled('status_operasional'),
            fn ($q) => $q->where('status_operasional', $request->status_operasional)
        );

        $query->when(
            $request->filled('lokasi'),
            fn ($q) => $q->where(
                'lokasi_aset_saat_ini',
                'ILIKE',
                "%{$request->lokasi}%"
            )
        );

        $accessPoints = $query
            ->orderBy('id_aset')
            ->paginate(15)
            ->withQueryString();

        $lokasi = AccessPoint::select('lokasi_aset_saat_ini')
            ->distinct()
            ->orderBy('lokasi_aset_saat_ini')
            ->pluck('lokasi_aset_saat_ini');

        return view('assets.accesspoint.index', compact(
            'accessPoints',
            'lokasi'
        ));
    }

    public function create()
    {
        return view('assets.accesspoint.form', [
            'asset' => null,
        ]);
    }

    public function importForm()
    {
        return view('assets.accesspoint.import'); // Sesuaikan nama view blade anda
    }

    // Memproses file Excel / CSV yang di-upload
    /**
     * PROSES SIMPAN IMPORT DATA ACCESS POINT (MENGGUNAKAN CSV MURNI)
     */
    public function importStore(Request $request)
    {
        // 1. Validasi HANYA menerima CSV atau TXT
        $request->validate([
            'files'   => ['required', 'array'],
            'files.*' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        if (!$request->hasFile('files')) {
            return back()->with('error', 'Tidak ada file yang diunggah.');
        }

        $skippedReasons = [];
        $successCount = 0;

        AssetHistory::create([
            'asset_id' => 1,
            'user_id' => auth()->id(),
            'action' => 'TAMBAH',
            'description' => "Melakukan impor massal data Access Point ({$successCount} data berhasil).",
        ]);

        foreach ($request->file('files') as $file) {
            try {
                $handle = fopen($file->getRealPath(), 'r');

                // Deteksi Delimiter (koma atau titik koma)
                $sampleLine = fgets($handle);
                rewind($handle);
                $delimiter = (substr_count($sampleLine, ';') > substr_count($sampleLine, ',')) ? ';' : ',';

                $rowIndex = 0;

                while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                    $rowIndex++;

                    // Template resmi memiliki 5 baris header/penjelasan. Data mulai di baris ke-6.
                    if ($rowIndex <= 5) {
                        continue;
                    }

                    // Pastikan baris memiliki jumlah kolom yang cukup (minimal sampai Keterangan - index 29)
                    if (count($row) < 30) {
                        $skippedReasons[] = "Baris ke-{$rowIndex} dilewati: Format kolom tidak sesuai template resmi Access Point (kurang dari 30 kolom).";
                        continue;
                    }

                    // Melewati baris jika ID Aset (1) & Merk (15) kosong (dianggap baris kosong)
                    if (empty(trim($row[1] ?? '')) && empty(trim($row[15] ?? ''))) {
                        continue;
                    }

                    // Pemetaan Index Column CSV sesuai TEMPLATE RESMI ACCESS POINT (Total 30 Kolom)
                    $merk           = $row[15] ?? null;
                    $model          = $row[16] ?? null;
                    $serialNumber   = $row[17] ?? null;
                    $macAddress     = $row[18] ?? null;
                    $ipAddress      = $row[19] ?? null;
                    $namaSsid       = $row[20] ?? null;
                    $frekuensi      = $row[21] ?? null;
                    $menggunakanPoe = $row[22] ?? null;
                    $standarWifi    = $row[23] ?? null;
                    $enkripsiWifi   = $row[24] ?? null;
                    $versiFirmware  = $row[25] ?? null;

                    // Helper aman untuk parsing tanggal Excel/CSV
                    $parseDate = function($val) {
                        $v = trim($val ?? '');
                        if (empty($v)) return null;
                        // Coba convert format DD-MM-YYYY atau standar lainnya ke Y-m-d
                        $time = strtotime($v);
                        return $time ? date('Y-m-d', $time) : null;
                    };

                    $tglAktif       = $parseDate($row[2] ?? '') ?? now()->toDateString();
                    $tglPemeriksaan = $parseDate($row[12] ?? '');
                    $tglGaransi     = $parseDate($row[28] ?? '');

                    // Simpan data dari CSV ke Database
                    \App\Models\AccessPoint::create([
                        'id_aset'                       => !empty(trim($row[1] ?? '')) ? trim($row[1]) : 'AP-' . strtoupper(uniqid()),
                        'tanggal_perolehan'             => $tglAktif,
                        'status_kepemilikan'            => trim($row[3] ?? 'Milik PLN'),
                        'keterangan_status_kepemilikan' => trim($row[4] ?? ''),
                        'status_kondisi'                => strtolower(trim($row[5] ?? 'baik')),
                        'status_operasional'            => strtolower(trim($row[6] ?? 'aktif')),
                        'tingkat_kritikalitas'          => strtolower(trim($row[7] ?? 'normal')),
                        'klasifikasi_keamanan'          => strtolower(trim($row[8] ?? 'publik')),
                        'deskripsi_fungsi'              => trim($row[9] ?? ''),
                        'lokasi_aset_saat_ini'          => trim($row[10] ?? 'Pusat'),
                        'kode_lokasi'                   => trim($row[10] ?? '-'), // <--- DITAMBAHKAN AGAR TIDAK ERROR NOT NULL
                        'keterangan_lokasi'             => trim($row[11] ?? ''),
                        'tanggal_pemeriksaan_terakhir'  => $tglPemeriksaan,
                        'pic_pencatat'                  => trim($row[13] ?? '') ?: (auth()->user()->name ?? 'Admin PLN'),
                        'bidang_pencatat_aset'          => trim($row[14] ?? ''),
                        'merk'                          => trim($merk),
                        'model'                         => trim($model),
                        'serial_number'                 => trim($serialNumber),
                        'mac_address'                   => trim($macAddress),
                        'ip_address'                    => trim($ipAddress),
                        'nama_ssid'                     => trim($namaSsid),
                        'frekuensi'                     => trim($frekuensi),
                        'menggunakan_poe'               => trim($menggunakanPoe),
                        'standar_wifi'                  => trim($standarWifi),
                        'enkripsi_wifi'                 => trim($enkripsiWifi),
                        'versi_firmware'                => trim($versiFirmware),
                        'konsumsi_daya'                 => !empty(trim($row[26] ?? '')) ? (float) trim($row[26]) : null,
                        'rack'                          => trim($row[27] ?? ''),
                        'masa_berlaku_garansi'          => $tglGaransi,
                        'keterangan'                    => trim($row[29] ?? ''),
                    ]);

                    $successCount++;
                }

                fclose($handle);

            } catch (\Exception $e) {
                if (isset($handle) && is_resource($handle)) {
                    fclose($handle);
                }
                return back()->with('error', 'Gagal memproses file ' . $file->getClientOriginalName() . ': ' . $e->getMessage());
            }
        }

        $message = "Berhasil mengimpor {$successCount} data Asset Access Point.";
        if (count($skippedReasons) > 0) {
            session()->flash('import_skipped_reasons', $skippedReasons);
        }

        // PERBAIKAN REDIRECT (diubah ke index access point)
        return redirect()
            ->route('manage-access-point')
            ->with('success', $message);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_aset' => 'required|string|max:255|unique:access_points,id_aset',
            'merk' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255',
            'ip_address' => 'nullable|string|max:255',
            'mac_address' => 'nullable|string|max:255',
            'lokasi_aset_saat_ini' => 'required|string|max:255',
            'status_kondisi' => 'required|string|max:255',
            'status_operasional' => 'required|string|max:255',
        ]);

        /*
         * Field yang tidak ditampilkan di form tetap diisi
         * supaya kolom database yang NOT NULL tidak error.
         */
        $validated['tanggal_perolehan'] = now()->toDateString();
        $validated['status_kepemilikan'] = 'PLN';
        $validated['tingkat_kritikalitas'] = 'Sedang';
        $validated['klasifikasi_keamanan'] = 'Internal';
        $validated['kode_lokasi'] = $validated['lokasi_aset_saat_ini'];
        $validated['pic_pencatat'] = auth()->user()->name ?? 'Admin';

        AccessPoint::create($validated);

        AssetHistory::create([
            'asset_id' => $accessPoint->id_aset ?? 'ACCESS-POINT',
            'user_id' => auth()->id(),
            'action' => 'TAMBAH',
            'description' => 'Menambahkan aset Access Point baru: ' . $accessPoint->merk . ' ' . $accessPoint->model,
        ]);

        return redirect()
            ->route('manage-access-point')
            ->with('success', 'Access Point berhasil ditambahkan.');
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(AccessPoint $accessPoint)
    {
        return view('assets.accesspoint.form', [
            'asset' => $accessPoint,
        ]);
    }

    public function update(Request $request, AccessPoint $accessPoint)
    {
        $validated = $request->validate([
            'id_aset' => 'required|string|max:255|unique:access_points,id_aset,' . $accessPoint->id,
            'merk' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255',
            'ip_address' => 'nullable|string|max:255',
            'mac_address' => 'nullable|string|max:255',
            'lokasi_aset_saat_ini' => 'required|string|max:255',
            'status_kondisi' => 'required|string|max:255',
            'status_operasional' => 'required|string|max:255',
        ]);

        $validated['kode_lokasi'] = $validated['lokasi_aset_saat_ini'];

        $accessPoint->update($validated);

        // CATAT RIWAYAT PERUBAHAN (Gunakan $accessPoint->id agar berupa angka integer)
        \App\Models\AssetHistory::create([
            'asset_id' => $accessPoint->id, // <--- Perbaikan di sini (menggunakan ID angka)
            'user_id' => auth()->id(),
            'action' => 'UBAH',
            'description' => 'Memperbarui data aset Access Point: ' . $accessPoint->id_aset,
        ]);

        return redirect()
            ->route('manage-access-point')
            ->with('success', 'Access Point berhasil diperbarui.');
    }

    /*
    |--------------------------------------------------------------------------
    | HAPUS
    |--------------------------------------------------------------------------
    */

    public function destroy(AccessPoint $accessPoint)
    {
        $accessPoint->delete();

        AssetHistory::create([
            'asset_id' => $accessPoint->id ?? 'ACCESS-POINT',
            'user_id' => auth()->id(),
            'action' => 'HAPUS',
            'description' => 'Menghapus aset Access Point: ' . $accessPoint->id_aset,
        ]);

        return redirect()
            ->route('manage-access-point')
            ->with('success', 'Access Point berhasil dihapus.');
    }

    public function history(Request $request)
    {
        $query = \App\Models\AssetHistory::with('user')->latest();

        // Opsional: Fitur pencarian pada halaman riwayat perubahan
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                ->orWhere('asset_id', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $histories = $query->paginate(15)->withQueryString();

        return view('assetHistory', compact('histories'));
    }
}