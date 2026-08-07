<?php

namespace App\Http\Controllers;

use App\Models\Router;
use Illuminate\Http\Request;

class RouterController extends Controller
{
    public function index(Request $request)
    {
        $query = Router::query();

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

        if ($request->filled('kondisi')) {
            $query->where('status_kondisi', $request->kondisi);
        }
        if ($request->filled('status_operasional')) {
            $query->where('status_operasional', $request->status_operasional);
        }
        if ($request->filled('kritikalitas')) {
            $query->where('tingkat_kritikalitas', $request->kritikalitas);
        }
        if ($request->filled('lokasi')) {
            $query->where('lokasi_aset_saat_ini', $request->lokasi);
        }

        $routers = $query->latest()->paginate(10)->withQueryString();

        $lokasiList = Router::whereNotNull('lokasi_aset_saat_ini')
                             ->distinct()
                             ->pluck('lokasi_aset_saat_ini');

        return view('manageRouter', compact('routers', 'lokasiList'));
    }

    public function create()
    {
        $router = null;
        return view('routerForm', compact('router'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_aset' => 'required|unique:routers,id_aset',
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

        Router::create($request->all());

        return redirect()->route('manage-router')->with('success', 'Aset Router berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $router = Router::findOrFail($id);
        return view('routerForm', compact('router'));
    }

    public function update(Request $request, $id)
    {
        $router = Router::findOrFail($id);

        $validated = $request->validate([
            'id_aset' => 'required|unique:routers,id_aset,' . $id,
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

        $router->update($request->all());

        return redirect()->route('manage-router')->with('success', 'Aset Router berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $router = Router::findOrFail($id);
        $router->delete();

        return redirect()->route('manage-router')->with('success', 'Data Router berhasil dihapus.');
    }
}