<?php

namespace App\Http\Controllers;

use App\Models\Router;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
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

    public function import(Request $request)
    {
        $request->validate([
            'files.*' => 'required|mimes:xlsx,xls,csv,txt'
        ]);

        $skippedReasons = [];
        $successCount = 0;

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                try {
                    $data = Excel::toArray([], $file);
                    $rows = $data[0] ?? [];

                    foreach ($rows as $index => $row) {
                        if ($index < 4 || empty($row[1])) { 
                            continue; 
                        }

                        try {
                            RouterAsset::create([
                                'tanggal_aktif'             => $row[1] ?? null,
                                'status_kepemilikan'        => $row[2] ?? null,
                                'ket_status_kepemilikan'    => $row[3] ?? null,
                                'status_kondisi'            => $row[4] ?? null,
                                'status_operasional'        => $row[5] ?? null,
                                'tingkat_kritikalitas'      => $row[6] ?? null,
                                'klasifikasi_keamanan'      => $row[7] ?? null,
                                'deskripsi_fungsi'          => $row[8] ?? null,
                                'lokasi_aset'               => $row[9] ?? null,
                                'ket_lokasi'                => $row[10] ?? null,
                                'tanggal_pemeriksaan'       => $row[11] ?? null,
                                'pic_pencatat'              => $row[12] ?? null,
                                'bidang_pencatat'           => $row[13] ?? null,
                                'merk'                      => $row[14] ?? null,
                                'model'                     => $row[15] ?? null,
                                'serial_number'             => $row[16] ?? null,
                                'mac_address'               => $row[17] ?? null,
                                'ip_address'                => $row[18] ?? null,
                                'jumlah_kecepatan_port'     => $row[19] ?? null,
                                'protocol_disupport'        => $row[20] ?? null,
                                'versi_firmware'            => $row[21] ?? null,
                                'konsumsi_daya'             => $row[22] ?? null,
                                'rack'                      => $row[23] ?? null,
                                'masa_berlaku_garansi'      => $row[24] ?? null,
                                'keterangan'                => $row[25] ?? null,
                            ]);

                            $successCount++;
                        } catch (\Exception $e) {
                            $skippedReasons[] = "File {$file->getClientOriginalName()} Baris " . ($index + 1) . ": " . $e->getMessage();
                        }
                    }
                } catch (\Exception $e) {
                    $skippedReasons[] = "Gagal membaca file {$file->getClientOriginalName()}: " . $e->getMessage();
                }
            }
        }

        return redirect()->route('manage-asset.router.import')
            ->with('success', "Berhasil mengimpor {$successCount} data Router.")
            ->with('import_skipped_reasons', $skippedReasons);
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
}