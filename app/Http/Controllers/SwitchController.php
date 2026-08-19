<?php

namespace App\Http\Controllers;

use App\Models\Switche;
use App\Models\AssetHistory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SwitchController extends Controller
{
    /**
     * LIST SWITCH
     */
    public function index(Request $request)
    {
        $query = Switche::query();

        // SEARCH
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('id_aset', 'like', "%{$search}%")
                    ->orWhere('merk', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhere('mac_address', 'like', "%{$search}%")
                    ->orWhere('lokasi_aset_saat_ini', 'like', "%{$search}%");
            });
        }

        // FILTER KONDISI
        if ($request->filled('kondisi')) {
            $query->where('status_kondisi', $request->kondisi);
        }

        // FILTER OPERASIONAL
        if ($request->filled('status_operasional')) {
            $query->where('status_operasional', $request->status_operasional);
        }

        // FILTER KRITIKALITAS
        if ($request->filled('kritikalitas')) {
            $query->where('tingkat_kritikalitas', $request->kritikalitas);
        }

        // FILTER LOKASI
        if ($request->filled('lokasi')) {
            $query->where('lokasi_aset_saat_ini', $request->lokasi);
        }

        $switches = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $lokasiList = Switche::whereNotNull('lokasi_aset_saat_ini')
            ->distinct()
            ->orderBy('lokasi_aset_saat_ini')
            ->pluck('lokasi_aset_saat_ini');

        return view('assets.switch.index', compact(
            'switches',
            'lokasiList'
        ));
    }

    public function ImportForm()
    {
        return view('assets.switch.import'); 
    }

    /**
     * PROSES SIMPAN IMPORT DATA SWITCH
     */
    /**
     * PROSES SIMPAN IMPORT DATA SWITCH (MENGGUNAKAN CSV MURNI)
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
            'description' => "Melakukan impor massal data Switch ({$successCount} data berhasil).",
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

                    // Pastikan baris memiliki jumlah kolom yang cukup (minimal sampai Firmware - index 23)
                    if (count($row) < 24) {
                        $skippedReasons[] = "Baris ke-{$rowIndex} dilewati: Format kolom tidak sesuai template resmi.";
                        continue;
                    }

                    // Melewati baris jika ID Aset (1) & Merk (15) kosong (dianggap baris kosong)
                    if (empty(trim($row[1] ?? '')) && empty(trim($row[15] ?? ''))) {
                        continue;
                    }

                    // Pemetaan Index Column CSV sesuai TEMPLATE RESMI SWITCH
                    $merk               = $row[15] ?? null;
                    $model              = $row[16] ?? null;
                    $fungsiSwitch       = $row[17] ?? null;
                    $serialNumber       = $row[18] ?? null;
                    $macAddress         = $row[19] ?? null;
                    $ipAddress          = $row[20] ?? null;
                    $jumlahKecepatanPort= $row[21] ?? null;
                    $supportPoe         = $row[22] ?? null;
                    $versiFirmware      = $row[23] ?? null;

                    // Validasi: Wajib isi Merk dan Model
                    if (empty(trim($merk)) || empty(trim($model))) {
                        $skippedReasons[] = "Baris ke-{$rowIndex} dilewati: Kolom Merk atau Model kosong.";
                        continue;
                    }

                    // Format tanggal (handling jika format dari CSV tidak sesuai)
                    $tglPerolehan = !empty(trim($row[2] ?? '')) ? date('Y-m-d', strtotime(trim($row[2]))) : now()->toDateString();
                    $tglPemeriksaan = !empty(trim($row[12] ?? '')) ? date('Y-m-d', strtotime(trim($row[12]))) : null;
                    $tglGaransi = !empty(trim($row[26] ?? '')) ? date('Y-m-d', strtotime(trim($row[26]))) : null;

                    // Simpan data dari CSV ke Database
                    Switche::create([
                        'id_aset'                      => !empty(trim($row[1] ?? '')) ? trim($row[1]) : 'SW-' . strtoupper(uniqid()),
                        'tanggal_perolehan'            => $tglPerolehan,
                        'status_kepemilikan'           => trim($row[3] ?? 'Milik PLN'),
                        'keterangan_kepemilikan'       => trim($row[4] ?? ''),
                        'status_kondisi'               => strtolower(trim($row[5] ?? 'baik')),
                        'status_operasional'           => strtolower(trim($row[6] ?? 'aktif')),
                        'tingkat_kritikalitas'         => strtolower(trim($row[7] ?? 'normal')),
                        'klasifikasi_keamanan'         => strtolower(trim($row[8] ?? 'internal')),
                        'deskripsi_fungsi_aset'        => trim($row[9] ?? ''),
                        'lokasi_aset_saat_ini'         => trim($row[10] ?? 'Pusat'),
                        'keterangan_lokasi'            => trim($row[11] ?? ''),
                        'tanggal_pemeriksaan_terakhir' => $tglPemeriksaan,
                        'pic_pencatat'                 => trim($row[13] ?? '') ?: (auth()->user()->name ?? 'Admin PLN'),
                        'bidang_pencatat_aset'         => trim($row[14] ?? ''),
                        'merk'                         => trim($merk),
                        'model'                        => trim($model),
                        'tipe_switch'                  => trim($fungsiSwitch), // Tipe Switch = Fungsi Switch
                        'serial_number'                => trim($serialNumber),
                        'mac_address'                  => trim($macAddress),
                        'ip_address'                   => trim($ipAddress),
                        'jumlah_port'                  => trim($jumlahKecepatanPort),
                        'mendukung_poe'                => trim($supportPoe),
                        'versi_firmware'               => trim($versiFirmware),
                        'konsumsi_daya'                => trim($row[24] ?? ''),
                        'rack'                         => trim($row[25] ?? ''),
                        'masa_berlaku_garansi'         => $tglGaransi,
                        'keterangan'                   => trim($row[27] ?? ''),
                        'kode_lokasi'                  => '-', // Default
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

        $message = "Berhasil mengimpor {$successCount} data asset Switch & TOR Switch.";
        if (count($skippedReasons) > 0) {
            session()->flash('import_skipped_reasons', $skippedReasons);
        }

        return redirect()
            ->route('manage-switch')
            ->with('success', $message);
    }

    public function import(Request $request)
    {
        $request->validate([
            'files.*' => 'required|mimes:xlsx,xls,csv,txt|max:10240', // Maksimal 10MB per file
        ]);

        if (!$request->hasFile('files')) {
            return back()->with('error', 'Tidak ada file yang diunggah.');
        }

        $skippedReasons = [];
        $successCount = 0;

        foreach ($request->file('files') as $file) {
            try {
                // Contoh logika pembacaan file / integrasi Maatwebsite Excel
                // Excel::import(new SwitchImport, $file);
                
                // Atau proses kustom parsing di sini sesuai kolom Excel Switch Anda:
                // (No, Nama Switch, IP Address, Serial Number, Lokasi, Model, Port Count, dll.)
                
                $successCount++;
            } catch (Exception $e) {
                $skippedReasons[] = "File " . $file->getClientOriginalName() . ": " . $e->getMessage();
            }
        }

        if (count($skippedReasons) > 0) {
            return back()->with('success', "Berhasil memproses sebagian file.")
                         ->with('import_skipped_reasons', $skippedReasons);
        }

        return redirect()->route('manage-switch')->with('success', 'Semua data Asset Switch berhasil diimport!');
    }

    public function create()
    {
        $switch = null;

        return view('assets.switch.form', compact('switch'));
    }



    public function store(Request $request)
    {
        $validated = $this->validateSwitch($request);

        /*
        |--------------------------------------------------------------------------
        | Kolom NOT NULL yang belum ada di form
        |--------------------------------------------------------------------------
        */
        AssetHistory::create([
            'asset_id' => $switch->id_aset ?? 'SWITCH',
            'user_id' => auth()->id(),
            'action' => 'TAMBAH',
            'description' => 'Menambahkan aset Switch baru: ' . $switch->merk . ' ' . $switch->model,
        ]);

        $data = array_merge([
            'tanggal_perolehan' => now()->toDateString(),
            'status_kepemilikan' => 'Milik PLN',
            'keterangan_kepemilikan' => null,
            'klasifikasi_keamanan' => 'Internal',
            'deskripsi_fungsi_aset' => null,
            'kode_lokasi' => $request->input('kode_lokasi', '-'),
            'keterangan_lokasi' => null,
            'tanggal_pemeriksaan_terakhir' => null,
            'pic_pencatat' => auth()->user()->name ?? 'Admin PLN',
            'bidang_pencatat_aset' => null,
            'keterangan' => null,
        ], $validated);

        Switche::create($data);

        return redirect()
            ->route('manage-switch')
            ->with('success', 'Aset Switch berhasil ditambahkan!');
    }


    /**
     * FORM EDIT
     */
    public function edit($id)
    {
        $switch = Switche::findOrFail($id);

        return view('assets.switch.form', compact('switch'));
    }


    /**
     * UPDATE SWITCH
     */
    public function update(Request $request, $id)
    {
        $switch = Switche::findOrFail($id);

        $validated = $this->validateSwitch(
            $request,
            $switch->id
        );

        $switch->update($validated);

        AssetHistory::create([
            'asset_id' => $switch->id ?? 'SWITCH',
            'user_id' => auth()->id(),
            'action' => 'UBAH',
            'description' => 'Memperbarui data aset Switch: ' . $switch->id_aset,
        ]);

        return redirect()
            ->route('manage-switch')
            ->with('success', 'Aset Switch berhasil diperbarui!');
    }


    /**
     * HAPUS SWITCH
     */
    public function destroy($id)
    {
        $switch = Switche::findOrFail($id);

        $switch->delete();

        AssetHistory::create([
            'asset_id' => $switch->id ?? 'SWITCH',
            'user_id' => auth()->id(),
            'action' => 'HAPUS',
            'description' => 'Menghapus aset Switch: ' . $switch->id_aset,
        ]);

        return redirect()
            ->route('manage-switch')
            ->with('success', 'Data Switch berhasil dihapus.');
    }


    /**
     * VALIDASI SWITCH
     */
    private function validateSwitch(Request $request, $id = null)
    {
        return $request->validate([
            /*
            |--------------------------------------------------------------------------
            | DATA UTAMA
            |--------------------------------------------------------------------------
            */

            'id_aset' => [
                'required',
                'string',
                'max:255',
                $id
                    ? Rule::unique('switches', 'id_aset')->ignore($id)
                    : Rule::unique('switches', 'id_aset'),
            ],

            'status_kondisi' => [
                'required',
                'in:baik,rusak,perlu_perbaikan',
            ],

            'status_operasional' => [
                'required',
                'in:aktif,tidak_aktif',
            ],

            'tingkat_kritikalitas' => [
                'required',
                'in:kritis,penting,normal',
            ],

            'lokasi_aset_saat_ini' => [
                'required',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | ATRIBUT SWITCH
            |--------------------------------------------------------------------------
            */

            'merk' => [
                'required',
                'string',
                'max:255',
            ],

            'model' => [
                'required',
                'string',
                'max:255',
            ],

            'serial_number' => [
                'required',
                'string',
                'max:255',
            ],

            'mac_address' => [
                'nullable',
                'string',
                'max:255',
            ],

            'ip_address' => [
                'nullable',
                'string',
                'max:255',
            ],

            'jumlah_port' => [
                'nullable',
                'string',
                'max:255',
            ],

            'tipe_switch' => [
                'nullable',
                'string',
                'max:255',
            ],

            'mendukung_poe' => [
                'nullable',
                'string',
                'max:255',
            ],

            'kapasitas_poe' => [
                'nullable',
                'string',
                'max:255',
            ],

            'vlan_support' => [
                'nullable',
                'string',
                'max:255',
            ],

            'versi_firmware' => [
                'nullable',
                'string',
                'max:255',
            ],

            'konsumsi_daya' => [
                'nullable',
                'string',
                'max:255',
            ],

            'rack' => [
                'nullable',
                'string',
                'max:255',
            ],

            'masa_berlaku_garansi' => [
                'nullable',
                'date',
            ],

            'kode_lokasi' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);
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