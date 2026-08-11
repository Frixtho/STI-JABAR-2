<?php

namespace App\Http\Controllers;

use App\Models\Switche;
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

        return view('manageSwitch', compact(
            'switches',
            'lokasiList'
        ));
    }

    public function ImportForm()
    {
        return view('switchImport'); 
    }

    public function importStore(\Illuminate\Http\Request $request)
    {
        // Validasi dan logika proses import file Excel/CSV di sini
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        // Contoh redirect setelah sukses
        return redirect()->route('manage-switch')->with('success', 'Data switch berhasil diimport.');
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

        return redirect()->route('manage-asset')->with('success', 'Semua data Asset Switch berhasil diimport!');
    }

    public function create()
    {
        $switch = null;

        return view('switchForm', compact('switch'));
    }



    public function store(Request $request)
    {
        $validated = $this->validateSwitch($request);

        /*
        |--------------------------------------------------------------------------
        | Kolom NOT NULL yang belum ada di form
        |--------------------------------------------------------------------------
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

        return view('switchForm', compact('switch'));
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
}