<?php

namespace App\Http\Controllers;

use App\Models\Router;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\AssetHistory;
use Illuminate\Validation\Rule;

class RouterController extends Controller
{
    /**
     * LIST ROUTER
     */
    public function index(Request $request)
    {
        $query = Router::query();

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

        $routers = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $lokasiList = Router::whereNotNull('lokasi_aset_saat_ini')
            ->distinct()
            ->orderBy('lokasi_aset_saat_ini')
            ->pluck('lokasi_aset_saat_ini');

        return view('manageRouter', compact(
            'routers',
            'lokasiList'
        ));
    }


    /**
     * FORM TAMBAH
     */
    public function create()
    {
        $router = null;

        return view('routerForm', compact('router'));
    }

    public function importForm()
    {
        return view('routerImport'); // Sesuaikan path view Anda jika berbeda
    }

    /**
     * PROSES SIMPAN IMPORT DATA ROUTER (MENGGUNAKAN CSV MURNI)
     */
    public function import(Request $request)
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
        $successCount = 0; // Inisialisasi awal counter sukses

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

                    // Pastikan baris memiliki jumlah kolom yang cukup (minimal sampai Firmware - index 22)
                    if (count($row) < 23) {
                        $skippedReasons[] = "Baris ke-{$rowIndex} dilewati: Format kolom tidak sesuai template resmi.";
                        continue;
                    }

                    // Melewati baris jika ID Aset (1) & Merk (15) kosong (dianggap baris kosong)
                    if (empty(trim($row[1] ?? '')) && empty(trim($row[15] ?? ''))) {
                        continue;
                    }

                    // Pemetaan Index Column CSV sesuai TEMPLATE RESMI ROUTER (Total 27 Kolom)
                    $merk               = $row[15] ?? null;
                    $model              = $row[16] ?? null;
                    $serialNumber       = $row[17] ?? null;
                    $macAddress         = $row[18] ?? null;
                    $ipAddress          = $row[19] ?? null;
                    $jumlahKecepatanPort= $row[20] ?? null;
                    $protocolDisupport  = $row[21] ?? null;
                    $versiFirmware      = $row[22] ?? null;

                    // Validasi: Wajib isi Merk dan Model
                    if (empty(trim($merk)) || empty(trim($model))) {
                        $skippedReasons[] = "Baris ke-{$rowIndex} dilewati: Kolom Merk atau Model kosong.";
                        continue;
                    }

                    // Format tanggal (handling jika format dari CSV tidak sesuai)
                    $tglPerolehan = !empty(trim($row[2] ?? '')) ? date('Y-m-d', strtotime(trim($row[2]))) : now()->toDateString();
                    $tglPemeriksaan = !empty(trim($row[12] ?? '')) ? date('Y-m-d', strtotime(trim($row[12]))) : null;
                    $tglGaransi = !empty(trim($row[25] ?? '')) ? date('Y-m-d', strtotime(trim($row[25]))) : null;

                    // Simpan data dari CSV ke Database
                    $router = Router::create([
                        'id_aset'                      => !empty(trim($row[1] ?? '')) ? trim($row[1]) : 'RT-' . strtoupper(uniqid()),
                        'tanggal_mulai_aktif'          => $tglPerolehan,
                        'status_kepemilikan'           => trim($row[3] ?? 'Milik PLN'),
                        'keterangan_status_kepemilikan'=> trim($row[4] ?? ''),
                        'status_kondisi'               => strtolower(trim($row[5] ?? 'baik')),
                        'status_operasional'           => strtolower(trim($row[6] ?? 'aktif')),
                        'tingkat_kritikalitas'         => strtolower(trim($row[7] ?? 'normal')),
                        'klasifikasi_keamanan'         => strtolower(trim($row[8] ?? 'internal')),
                        'keterangan_lokasi'            => trim($row[11] ?? ''),
                        'lokasi_aset_saat_ini'         => trim($row[10] ?? 'Pusat'),
                        'tanggal_pemeriksaan_terakhir' => $tglPemeriksaan,
                        'pic_pencatat'                 => trim($row[13] ?? '') ?: (auth()->user()->name ?? 'Admin PLN'),
                        'bidang_pencatat_aset'         => trim($row[14] ?? ''),
                        'merk'                         => trim($merk),
                        'model'                        => trim($model),
                        'serial_number'                => trim($serialNumber),
                        'mac_address'                  => trim($macAddress),
                        'ip_address'                   => trim($ipAddress),
                        'jumlah_kecepatan_jenis_port'  => trim($jumlahKecepatanPort),
                        'protocol_disupport'           => trim($protocolDisupport),
                        'versi_firmware_os'            => trim($versiFirmware),
                        'konsumsi_daya'                => !empty(trim($row[23] ?? '')) ? (int) trim($row[23]) : null,
                        'rack'                         => trim($row[24] ?? ''),
                        'masa_berlaku_garansi'         => $tglGaransi,
                        'keterangan'                   => trim($row[26] ?? ''),
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

        // CATAT RIWAYAT PERUBAHAN DI SINI (Setelah proses import selesai dan $successCount sudah ada isinya)
        if ($successCount > 0 && isset($router)) {
            AssetHistory::create([
                'asset_id'    => $router->id, // Menggunakan ID angka dari router terakhir yang diimpor
                'user_id'     => auth()->id(),
                'action'      => 'TAMBAH',
                'description' => "Melakukan impor massal data Router ({$successCount} data berhasil).",
            ]);
        }

        $message = "Berhasil mengimpor {$successCount} data Asset Router.";
        if (count($skippedReasons) > 0) {
            session()->flash('import_skipped_reasons', $skippedReasons);
        }

        return redirect()
            ->route('manage-router')
            ->with('success', $message);
    }

    public function store(Request $request)
    {
        $validated = $this->validateRouter($request);

        /*
        |--------------------------------------------------------------------------
        | Kolom default yang disesuaikan dengan struktur database baru
        |--------------------------------------------------------------------------
        */
        $data = array_merge([
            'tanggal_mulai_aktif'           => now()->toDateString(),
            'status_kepemilikan'            => 'Milik PLN',
            'keterangan_status_kepemilikan' => null,
            'klasifikasi_keamanan'          => 'Internal',
            'keterangan_lokasi'             => null,
            'tanggal_pemeriksaan_terakhir'  => null,
            'pic_pencatat'                  => auth()->user()->name ?? 'Admin PLN',
            'bidang_pencatat_aset'          => null,
            'keterangan'                    => null,
        ], $validated);

        Router::create($data);

        AssetHistory::create([
            'asset_id' => $router->id_aset ?? 'ROUTER',
            'user_id' => auth()->id(),
            'action' => 'TAMBAH',
            'description' => 'Menambahkan aset Router baru: ' . $router->merk . ' ' . $router->model,
        ]);

        return redirect()
            ->route('manage-router')
            ->with('success', 'Aset Router berhasil ditambahkan!');
    }


    /**
     * FORM EDIT
     */
    public function edit($id)
    {
        $router = Router::findOrFail($id);

        return view('routerForm', compact('router'));
    }


    /**
     * UPDATE ROUTER
     */
    public function update(Request $request, $id)
    {
        $router = Router::findOrFail($id);

        $validated = $this->validateRouter(
            $request,
            $router->id
        );

        $router->update($validated);

        AssetHistory::create([
            'asset_id' => $router->id ?? 'ROUTER',
            'user_id' => auth()->id(),
            'action' => 'UBAH',
            'description' => 'Memperbarui data aset Router: ' . $router->id_aset,
        ]);

        return redirect()
            ->route('manage-router')
            ->with('success', 'Aset Router berhasil diperbarui!');
    }


    /**
     * HAPUS ROUTER
     */
    public function destroy($id)
    {
        $router = Router::findOrFail($id);

        $router->delete();

        AssetHistory::create([
            'asset_id' => $router->id ?? 'ROUTER',
            'user_id' => auth()->id(),
            'action' => 'HAPUS',
            'description' => 'Menghapus aset Router: ' . $router->id_aset,
        ]);

        return redirect()
            ->route('manage-router')
            ->with('success', 'Data Router berhasil dihapus.');
    }


    /**
     * VALIDASI ROUTER
     */
    private function validateRouter(Request $request, $id = null)
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
                    ? Rule::unique('routers', 'id_aset')->ignore($id)
                    : Rule::unique('routers', 'id_aset'),
            ],

            'status_kondisi' => [
                'required',
                'string',
                'max:255',
            ],

            'status_operasional' => [
                'required',
                'string',
                'max:255',
            ],

            'tingkat_kritikalitas' => [
                'required',
                'string',
                'max:255',
            ],

            'lokasi_aset_saat_ini' => [
                'required',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | ATRIBUT ROUTER (Sesuai dengan migration terbaru)
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

            'jumlah_kecepatan_jenis_port' => [
                'nullable',
                'string',
                'max:255',
            ],

            'protocol_disupport' => [
                'nullable',
                'string',
                'max:255',
            ],

            'versi_firmware_os' => [
                'nullable',
                'string',
                'max:255',
            ],

            'konsumsi_daya' => [
                'nullable',
                'integer',
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