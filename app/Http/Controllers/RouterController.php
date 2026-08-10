<?php

namespace App\Http\Controllers;

use App\Models\Router;
use Illuminate\Http\Request;
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
                    ->orWhere('ip_address_wan', 'like', "%{$search}%")
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


    /**
     * SIMPAN ROUTER BARU
     */
    public function store(Request $request)
    {
        $validated = $this->validateRouter($request);

        /*
        |--------------------------------------------------------------------------
        | Kolom NOT NULL dari database yang belum ada di form
        |--------------------------------------------------------------------------
        | Karena kamu tidak mau bikin migration baru, kita isi default di sini.
        */

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
            | ATRIBUT ROUTER
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

            'ip_address_wan' => [
                'nullable',
                'string',
                'max:255',
            ],

            'ip_address_lan' => [
                'nullable',
                'string',
                'max:255',
            ],

            'jumlah_port_wan' => [
                'nullable',
                'string',
                'max:255',
            ],

            'jumlah_port_lan' => [
                'nullable',
                'string',
                'max:255',
            ],

            'mendukung_vpn' => [
                'nullable',
                'string',
                'max:255',
            ],

            'protokol_routing' => [
                'nullable',
                'string',
                'max:255',
            ],

            'throughput' => [
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
}