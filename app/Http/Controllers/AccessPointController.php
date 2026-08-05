<?php

namespace App\Http\Controllers;
use App\Models\AccessPoint;

use Illuminate\Http\Request;

class AccessPointController extends Controller
{
    public function index(Request $request)
    {
        $query = AccessPoint::query();

        // 1. Filter berdasarkan pencarian teks (search)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id_aset', 'like', "%{$search}%")
                  ->orWhere('merk', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('mac_address', 'like', "%{$search}%")
                  ->orWhere('lokasi_aset_saat_ini', 'like', "%{$search}%");
            });
        }

        // 2. Filter berdasarkan kondisi
        if ($request->filled('kondisi')) {
            $query->where('status_kondisi', $request->kondisi);
        }

        // 3. Filter berdasarkan status operasional
        if ($request->filled('status_operasional')) {
            $query->where('status_operasional', $request->status_operasional);
        }

        // 4. Filter berdasarkan kritikalitas
        if ($request->filled('kritikalitas')) {
            $query->where('tingkat_kritikalitas', $request->kritikalitas);
        }

        // 5. Filter berdasarkan lokasi
        if ($request->filled('lokasi')) {
            $query->where('lokasi_aset_saat_ini', $request->lokasi);
        }

        // Ambil data dengan pagination (misal 10 data per halaman)
        $accessPoints = $query->latest()->paginate(10)->withQueryString();

        // Ambil daftar lokasi unik untuk dropdown filter lokasi
        $lokasiList = AccessPoint::whereNotNull('lokasi_aset_saat_ini')
                                 ->distinct()
                                 ->pluck('lokasi_aset_saat_ini');

        return view('manageAccessPoint', compact('accessPoints', 'lokasiList'));
    }

    public function edit($id)
    {
        $accessPoint = AccessPoint::findOrFail($id);
        
        // Ganti path view di bawah ini sesuai dengan letak file blade form Anda (misal: 'access-points.form')
        return view('accessPointForm', compact('accessPoint'));
    }

    // Tambahkan method create, store, edit, update, destroy sesuai kebutuhan form CRUD Anda.

    public function create()
    {
        $unit = null; // Karena mode tambah, set null agar form mendeteksi mode "Tambah Unit"
        $parents = \App\Models\AccessPoint::all();
        return view('accessPointForm', compact('unit', 'parents'));
    }

    public function update(Request $request, $id)
    {
        $accessPoint = AccessPoint::findOrFail($id);
        
        $validated = $request->validate([
            'id_aset' => 'required|unique:access_points,id_aset,' . $id,
            'tanggal_perolehan' => 'required|date',
            'status_kepemilikan' => 'required',
            'status_kondisi' => 'required',
            'status_operasional' => 'required',
            'tingkat_kritikalitas' => 'required',
            'klasifikasi_keamanan' => 'required',
            'lokasi_aset_saat_ini' => 'required',
            'kode_lokasi' => 'required',
            'pic_pencatat' => 'required',
            'merk' => 'required',
            'model' => 'required',
            'serial_number' => 'required',
        ]);

        $accessPoint->update($request->all());

        return redirect()->route('manage-access-point')->with('success', 'Aset Access Point berhasil ditambahkan!');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_aset' => 'required|unique:access_points,id_aset',
            'tanggal_perolehan' => 'required|date',
            'status_kepemilikan' => 'required',
            'status_kondisi' => 'required',
            'status_operasional' => 'required',
            'tingkat_kritikalitas' => 'required',
            'klasifikasi_keamanan' => 'required',
            'lokasi_aset_saat_ini' => 'required',
            'kode_lokasi' => 'required',
            'pic_pencatat' => 'required',
            'merk' => 'required',
            'model' => 'required',
            'serial_number' => 'required',
        ]);

        AccessPoint::create($request->all());

        return redirect()->back()->with('success', 'Aset Access Point berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $accessPoint = AccessPoint::findOrFail($id);
        $accessPoint->delete();

        return redirect()->route('manage-access-point')->with('success', 'Data Access Point berhasil dihapus.');
    }
}