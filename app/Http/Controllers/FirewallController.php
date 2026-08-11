<?php

namespace App\Http\Controllers;

use App\Models\Firewall;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FirewallController extends Controller
{
    /**
     * LIST FIREWALL
     */
    public function index(Request $request)
    {
        $query = Firewall::query();

        // SEARCH
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('id_aset', 'like', "%{$search}%")
                    ->orWhere('merk', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere('segmen_number', 'like', "%{$search}%")
                    ->orWhere('segmen_tujuan', 'like', "%{$search}%")
                    ->orWhere('lokasi_aset_saat_ini', 'like', "%{$search}%")
                    ->orWhere('pic_pencatat', 'like', "%{$search}%") // Tambahan agar pencarian PIC berfungsi
                    ->orWhere('bidang_pencatat_aset', 'like', "%{$search}%");
            });
        }

        // FILTER KONDISI
        if ($request->filled('kondisi')) {
            $query->where('status_kondisi_aset', $request->kondisi);
        }

        // FILTER OPERASIONAL
        if ($request->filled('status_operasional')) {
            $query->where('status_operasional_aset', $request->status_operasional);
        }

        // FILTER KRITIKALITAS
        if ($request->filled('kritikalitas')) {
            $query->where('tingkat_kritikalitas_aset', $request->kritikalitas);
        }

        // FILTER LOKASI
        if ($request->filled('lokasi')) {
            $query->where('lokasi_aset_saat_ini', $request->lokasi);
        }

        $firewalls = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $lokasiList = Firewall::whereNotNull('lokasi_aset_saat_ini')
            ->distinct()
            ->orderBy('lokasi_aset_saat_ini')
            ->pluck('lokasi_aset_saat_ini');

        return view('manageFirewall', compact('firewalls', 'lokasiList'));
    }


    /**
     * FORM TAMBAH
     */
    public function create()
    {
        $firewall = null;

        return view('firewallForm', compact('firewall'));
    }


    /**
     * SIMPAN FIREWALL BARU
     */
    public function store(Request $request)
    {
        $validated = $this->validateFirewall($request);

        /*
        |--------------------------------------------------------------------------
        | Kolom default yang disesuaikan dengan struktur database
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

        Firewall::create($data);

        return redirect()
            ->route('manage-firewall.index')
            ->with('success', 'Aset Firewall berhasil ditambahkan!');
    }


    /**
     * FORM EDIT
     */
    public function edit($id)
    {
        $firewall = Firewall::findOrFail($id);

        return view('firewallForm', compact('firewall'));
    }


    /**
     * UPDATE FIREWALL
     */
    public function update(Request $request, $id)
    {
        $firewall = Firewall::findOrFail($id);

        $validated = $this->validateFirewall(
            $request,
            $firewall->id
        );

        $firewall->update($validated);

        return redirect()
            ->route('manage-firewall.index')
            ->with('success', 'Aset Firewall berhasil diperbarui!');
    }


    /**
     * HAPUS FIREWALL
     */
    public function destroy($id)
    {
        $firewall = Firewall::findOrFail($id);

        $firewall->delete();

        return redirect()
            ->route('manage-firewall.index')
            ->with('success', 'Data Firewall berhasil dihapus.');
    }


    /**
     * VALIDASI FIREWALL
     */
    private function validateFirewall(Request $request, $id = null)
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
                    ? Rule::unique('firewalls', 'id_aset')->ignore($id)
                    : Rule::unique('firewalls', 'id_aset'),
            ],

            'status_kondisi_aset' => [
                'required',
                'string',
                'max:255',
            ],

            'status_operasional_aset' => [
                'required',
                'string',
                'max:255',
            ],

            'tingkat_kritikalitas_aset' => [
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
            | ATRIBUT SPESIFIK FIREWALL
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

            'segmen_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'segmen_tujuan' => [
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