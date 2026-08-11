<?php

namespace App\Http\Controllers;

use App\Models\Firewall;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Exception;

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
                    ->orWhere('pic_pencatat', 'like', "%{$search}%")
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
        $asset = null; // Diubah dari $firewall ke $asset agar sesuai dengan blade template sebelumnya

        return view('firewallForm', compact('asset'));
    }

    public function importForm()
    {
        return view('firewallImport'); 
    }

    public function import(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|mimes:xlsx,xls,csv,txt',
        ]);

        if (!$request->hasFile('files')) {
            return back()->with('error', 'Tidak ada file yang diunggah.');
        }

        $skippedReasons = [];
        $successCount = 0;

        foreach ($request->file('files') as $file) {
            try {
                $filePath = $file->getRealPath();
                $spreadsheet = IOFactory::load($filePath);
                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray();

                // Baca data mulai dari baris index ke-5 (baris ke-6 di Excel)
                for ($i = 5; $i < count($rows); $i++) {
                    $row = $rows[$i];

                    // Cek jika baris kosong (kolom ID Aset / Merk kosong)
                    if (empty($row[1]) && empty($row[15])) {
                        continue;
                    }

                    $merk = $row[15] ?? null;
                    $model = $row[16] ?? null;
                    $serialNumber = $row[17] ?? null;
                    $segmenNumber = $row[18] ?? null;
                    $segmenTujuan = $row[19] ?? null;
                    $versiFirmware = $row[20] ?? null;

                    // Validasi kolom wajib spesifik firewall
                    if (empty($merk) || empty($model) || empty($segmenNumber) || empty($segmenTujuan) || empty($versiFirmware)) {
                        $skippedReasons[] = "File {$file->getClientOriginalName()} Baris ke-" . ($i + 1) . " dilewati: Kolom wajib spesifik (Merk, Model, Segmen Number/Tujuan, atau Versi Firmware) ada yang kosong.";
                        continue;
                    }

                    // Simpan data dari Excel ke Database
                    Firewall::create([
                        'id_aset'                     => $row[1] ?? 'AST-' . uniqid(),
                        'tanggal_mulai_aktif'         => $row[2] ?? now()->toDateString(),
                        'status_kepemilikan'          => $row[3] ?? 'Milik PLN',
                        'keterangan_status_kepemilikan'=> $row[4] ?? null,
                        'status_kondisi_aset'         => $row[5] ?? 'baik',
                        'status_operasional_aset'     => $row[6] ?? 'aktif',
                        'tingkat_kritikalitas_aset'   => $row[7] ?? 'penting',
                        'klasifikasi_keamanan_aset'   => $row[8] ?? 'internal',
                        'deskripsi_tujuan'            => $row[9] ?? null,
                        'lokasi_aset_saat_ini'        => $row[10] ?? 'Pusat',
                        'keterangan_lokasi_aset'      => $row[11] ?? null,
                        'tanggal_pemeriksaan_terakhir'=> $row[12] ?? null,
                        'pic_pencatat'                => $row[13] ?? (auth()->user()->name ?? 'Admin'),
                        'bidang_pencatat_aset'        => $row[14] ?? null,
                        'merk'                        => $merk,
                        'model'                       => $model,
                        'serial_number'               => $serialNumber,
                        'segmen_number'               => $segmenNumber,
                        'segmen_tujuan'               => $segmenTujuan,
                        'versi_firmware'              => $versiFirmware,
                        'konsumsi_daya'               => $row[21] ?? null,
                        'rack'                        => $row[22] ?? null,
                        'masa_berlaku_garansi'        => $row[23] ?? null,
                        'keterangan'                  => $row[24] ?? null,
                    ]);

                    $successCount++;
                }

            } catch (Exception $e) {
                return back()->with('error', 'Gagal memproses file ' . $file->getClientOriginalName() . ': ' . $e->getMessage());
            }
        }

        $message = "Berhasil mengimpor {$successCount} data asset firewall.";
        if (count($skippedReasons) > 0) {
            session()->flash('import_skipped_reasons', $skippedReasons);
        }

        return back()->with('success', $message);
    }

    public function store(Request $request)
    {
        $validated = $this->validateFirewall($request);

        $data = array_merge([
            'tanggal_mulai_aktif'           => now()->toDateString(),
            'status_kepemilikan'            => 'pembelian oleh PLN pusat',
            'keterangan_status_kepemilikan' => null,
            'klasifikasi_keamanan_aset'     => 'terbatas',
            'keterangan_lokasi_aset'        => null,
            'tanggal_pemeriksaan_terakhir'  => null,
            'pic_pencatat'                  => auth()->user()->name ?? 'Admin PLN',
            'bidang_pencatat_aset'          => 'DIV STI',
            'keterangan'                    => null,
        ], $validated);

        Firewall::create($data);

        return redirect()
            ->route('manage-asset') // Disesuaikan dengan route list Anda sebelumnya
            ->with('success', 'Aset Firewall berhasil ditambahkan!');
    }

    /**
     * FORM EDIT
     */
    public function edit($id)
    {
        $asset = Firewall::findOrFail($id); // Diubah dari $firewall ke $asset

        return view('firewallForm', compact('asset'));
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
            ->route('manage-asset') // Disesuaikan dengan route list Anda sebelumnya
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
            ->route('manage-asset') // Disesuaikan dengan route list Anda sebelumnya
            ->with('success', 'Data Firewall berhasil dihapus.');
    }

    /**
     * VALIDASI FIREWALL
     */
    private function validateFirewall(Request $request, $id = null)
    {
        return $request->validate([
            'id_aset' => [
                'nullable',
                'string',
                'max:255',
                $id
                    ? Rule::unique('firewalls', 'id_aset')->ignore($id)
                    : Rule::unique('firewalls', 'id_aset'),
            ],
            'status_kondisi_aset' => ['required', 'string', 'max:255'],
            'status_operasional_aset' => ['required', 'string', 'max:255'],
            'tingkat_kritikalitas_aset' => ['required', 'string', 'max:255'],
            'lokasi_aset_saat_ini' => ['required', 'string', 'max:255'],
            'merk' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'serial_number' => ['nullable', 'string', 'max:255'],
            'segmen_number' => ['required', 'string'],
            'segmen_tujuan' => ['required', 'string'],
            'versi_firmware' => ['required', 'string', 'max:255'], // Diubah dari versi_firmware_os agar cocok dengan form
            'konsumsi_daya' => ['nullable', 'numeric'], // Diubah dari integer ke numeric agar support desimal (step 0.01)
            'rack' => ['nullable', 'string', 'max:255'],
            'masa_berlaku_garansi' => ['nullable', 'date'],
            'status_kepemilikan' => ['nullable', 'string', 'max:255'],
            'ket_status_kepemilikan' => ['nullable', 'string', 'max:255'],
            'klasifikasi_keamanan_aset' => ['nullable', 'string', 'max:255'],
            'deskripsi_tujuan' => ['nullable', 'string'],
            'keterangan_lokasi_aset' => ['nullable', 'string'],
            'tanggal_pemeriksaan_terakhir' => ['nullable', 'date'],
            'tanggal_mulai_aktif' => ['nullable', 'date'],
            'pic_pencatat' => ['nullable', 'string', 'max:255'],
            'bidang_pencatat_aset' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);
    }
}