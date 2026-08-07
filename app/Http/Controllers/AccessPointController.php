<?php

namespace App\Http\Controllers;

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

        $query->when($request->filled('kondisi'), fn($q) =>
            $q->where('status_kondisi', $request->kondisi)
        );

        $query->when($request->filled('status_operasional'), fn($q) =>
            $q->where('status_operasional', $request->status_operasional)
        );

        $query->when($request->filled('kritikalitas'), fn($q) =>
            $q->where('tingkat_kritikalitas', $request->kritikalitas)
        );

        $query->when($request->filled('lokasi'), fn($q) =>
            $q->where('lokasi_aset_saat_ini', 'ILIKE', "%{$request->lokasi}%")
        );

        $accessPoints = $query->orderBy('id_aset')->paginate(15)->withQueryString();

        $lokasi = AccessPoint::select('lokasi_aset_saat_ini')
            ->distinct()
            ->orderBy('lokasi_aset_saat_ini')
            ->pluck('lokasi_aset_saat_ini');

        return view('manageAccessPoint', compact('accessPoints', 'lokasi'));
    }