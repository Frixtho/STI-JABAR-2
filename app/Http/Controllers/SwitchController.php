<?php

namespace App\Http\Controllers;

use App\Models\Switche;
use Illuminate\Http\Request;

class SwitchController extends Controller
{
    public function index(Request $request)
    {
        $query = Switche::query();

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

        $switches = $query->latest()->paginate(10)->withQueryString();

        $lokasiList = Switche::whereNotNull('lokasi_aset_saat_ini')
                              ->distinct()
                              ->pluck('lokasi_aset_saat_ini');

        return view('manageSwitch', compact('switches', 'lokasiList'));
    }

    public function create()
    {
        $switch = null;
        return view('switchForm', compact('switch'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_aset' => 'required|unique:switches,id_aset',
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

        Switche::create($request->all());

        return redirect()->route('manage-switch')->with('success', 'Aset Switch berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $switch = Switche::findOrFail($id);
        return view('switchForm', compact('switch'));
    }

    public function update(Request $request, $id)
    {
        $switch = Switche::findOrFail($id);

        $validated = $request->validate([
            'id_aset' => 'required|unique:switches,id_aset,' . $id,
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

        $switch->update($request->all());

        return redirect()->route('manage-switch')->with('success', 'Aset Switch berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $switch = Switche::findOrFail($id);
        $switch->delete();

        return redirect()->route('manage-switch')->with('success', 'Data Switch berhasil dihapus.');
    }
}