<?php

namespace App\Http\Controllers;
use PhpOffice\PhpSpreadsheet\IOFactory;
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

    public function create()
    {
        return view('accessPointForm', [
            'asset' => null,
        ]);
    }

    public function importForm()
    {
        return view('accessPointImport'); // Sesuaikan nama view blade anda
    }

    // Memproses file Excel / CSV yang di-upload
    public function importStore(Request $request)
    {
        $request->validate([
            'files' => 'required',
            'files.*' => 'required|mimes:xlsx,xls,csv,txt'
        ]);

        $skippedReasons = [];

        foreach ($request->file('files') as $file) {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Lewati baris header (Baris 0 dan 1 berdasarkan struktur excel template)
            foreach (array_slice($rows, 2) as $index => $row) {
                // Pastikan baris tidak kosong (cek kolom merk/model atau nama asset)
                if (empty($row[15]) && empty($row[16])) { 
                    continue; 
                }

                try {
                    // Mapping kolom sesuai urutan header template Excel Access Point:
                    // Kolom ke-1: Tanggal Perolehan, dst.
                    AccessPoint::create([
                        'tanggal_perolehan'         => $row[2] ?? null,
                        'status_kepemilikan'        => $row[3] ?? null,
                        'ket_status_kepemilikan'    => $row[4] ?? null,
                        'status_kondisi'            => $row[5] ?? null,
                        'status_operasional'        => $row[6] ?? null,
                        'tingkat_kritikalitas'      => $row[7] ?? null,
                        'klasifikasi_keamanan'      => $row[8] ?? null,
                        'deskripsi_tujuan'          => $row[9] ?? null,
                        'lokasi_aset'               => $row[10] ?? null,
                        'ket_lokasi_aset'           => $row[11] ?? null,
                        'tanggal_pemeriksaan'       => $row[12] ?? null,
                        'pic_pencatat'              => $row[13] ?? null,
                        'bidang_pencatat'           => $row[14] ?? null,
                        'merk'                      => $row[15] ?? null,
                        'model'                     => $row[16] ?? null,
                        'serial_number'             => $row[17] ?? null,
                        'mac_address'               => $row[18] ?? null,
                        'ip_address'                => $row[19] ?? null,
                        'nama_ssid'                 => $row[20] ?? null,
                        'frekuensi'                 => $row[21] ?? null,
                        'menggunakan_poe'           => $row[22] ?? null,
                        'standar_wifi'              => $row[23] ?? null,
                        'enkripsi_wifi'             => $row[24] ?? null,
                        'versi_firmware'            => $row[25] ?? null,
                        'konsumsi_daya'             => $row[26] ?? null,
                        'rack'                      => $row[27] ?? null,
                        'masa_berlaku_garansi'      => $row[28] ?? null,
                        'keterangan'                => $row[29] ?? null,
                    ]);
                } catch (\Exception $e) {
                    $skippedReasons[] = "Baris " . ($index + 3) . ": " . $e->getMessage();
                }
            }
        }

        if (count($skippedReasons) > 0) {
            return redirect()->back()->with('success', 'Import selesai dengan beberapa catatan.')
                             ->with('import_skipped_reasons', $skippedReasons);
        }

        return redirect()->route('manage-asset')->with('success', 'Data Access Point berhasil di-import!');
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