<?php

namespace App\Http\Controllers;

use App\Models\AccessPoint;
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

        return view('manageAccessPoint', compact(
            'accessPoints',
            'lokasi'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | TAMBAH
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('accessPointForm', [
            'asset' => null,
        ]);
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
        return view('accessPointForm', [
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

        return redirect()
            ->route('manage-access-point')
            ->with('success', 'Access Point berhasil dihapus.');
    }
}