<?php

namespace App\Http\Controllers;
use App\Models\AssetHistory;
use App\Models\Firewall;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
        $asset = null; 
        return view('firewallForm', compact('asset'));
    }

    /**
     * FORM IMPORT EXCEL/CSV
     */
    public function importForm()
    {
        return view('firewallImport'); 
    }

    /**
     * PROSES SIMPAN IMPORT DATA
     * (Nama function diubah menjadi importStore agar cocok dengan route 'manage-asset.firewall.import.process')
     */
    /**
     * PROSES SIMPAN IMPORT DATA (MENGGUNAKAN CSV MURNI)
     */
    /**
     * PROSES SIMPAN IMPORT DATA (TEMPLATE RESMI CSV)
     */
    /**
     * PROSES SIMPAN IMPORT DATA (KHUSUS UNTUK FILE TEST CSV 9 KOLOM)
     */
    public function importStore(Request $request)
    {
        // 1. Validasi HANYA menerima CSV atau TXT
        $request->validate([
            'files'   => ['required', 'array'],
            'files.*' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        if (!$request->hasFile('files')) {
            return back()->with('error', 'Tidak ada file yang diunggah.');
        }

        $skippedReasons = [];
        $successCount = 0;

        foreach ($request->file('files') as $file) {
            try {
                $handle = fopen($file->getRealPath(), 'r');

                // Deteksi Delimiter (koma atau titik koma)
                $sampleLine = fgets($handle);
                rewind($handle);
                $delimiter = (substr_count($sampleLine, ';') > substr_count($sampleLine, ',')) ? ';' : ',';

                $rowIndex = 0;

                while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                    $rowIndex++;

                    // Melewati baris PERTAMA saja (karena baris 1 adalah Header/Judul Kolom di file test Anda)
                    if ($rowIndex === 1) {
                        continue;
                    }

                    // Melewati baris jika seluruhnya kosong
                    if ($row === [null] || empty(array_filter($row))) {
                        continue;
                    }

                    // Pemetaan Index Column CSV sesuai file test (Total 9 Kolom)
                    // Index: 0=Kondisi, 1=Ops, 2=Kritikalitas, 3=Lokasi, 4=Merk, 5=Model, 6=Firmware, 7=SegmenNum, 8=SegmenTujuan
                    $kondisi       = $row[0] ?? 'baik';
                    $operasional   = $row[1] ?? 'aktif';
                    $kritikalitas  = $row[2] ?? 'penting';
                    $lokasi        = $row[3] ?? 'Pusat';
                    $merk          = $row[4] ?? null;
                    $model         = $row[5] ?? null;
                    $versiFirmware = $row[6] ?? null;
                    $segmenNumber  = $row[7] ?? null;
                    $segmenTujuan  = $row[8] ?? null;

                    // Validasi sederhana: pastikan Merk tidak kosong
                    if (empty(trim($merk))) {
                        $skippedReasons[] = "Baris ke-{$rowIndex} dilewati: Kolom Merk kosong.";
                        continue;
                    }

                    AssetHistory::create([
                        'asset_id' => 1,
                        'user_id' => auth()->id(),
                        'action' => 'TAMBAH',
                        'description' => "Melakukan impor massal data Firewall ({$successCount} data berhasil).",
                    ]);

                    // Simpan data dari CSV Test ke Database
                    Firewall::create([
                        'id_aset'                       => 'FW-' . strtoupper(uniqid()), // ID Otomatis
                        'tanggal_mulai_aktif'           => now()->toDateString(),
                        'status_kepemilikan'            => 'Milik PLN',
                        'status_kondisi_aset'           => strtolower(trim($kondisi)),
                        'status_operasional_aset'       => strtolower(trim($operasional)),
                        'tingkat_kritikalitas_aset'     => strtolower(trim($kritikalitas)),
                        'klasifikasi_keamanan_aset'     => 'internal',
                        'lokasi_aset_saat_ini'          => trim($lokasi),
                        'pic_pencatat'                  => auth()->user()->name ?? 'Admin PLN',
                        'merk'                          => trim($merk),
                        'model'                         => trim($model),
                        'versi_firmware_os'             => trim($versiFirmware),
                        'segmen_number'                 => trim($segmenNumber),
                        'segmen_tujuan'                 => trim($segmenTujuan),
                        
                        // Set null untuk data yang tidak ada di CSV test
                        'keterangan_status_kepemilikan' => null,
                        'deskripsi_tujuan_peran_fungsi' => null,
                        'keterangan_lokasi_aset'        => null,
                        'tanggal_pemeriksaan_terakhir'  => null,
                        'bidang_pencatat_aset'          => null,
                        'serial_number'                 => null,
                        'konsumsi_daya'                 => null,
                        'rack'                          => null,
                        'masa_berlaku_garansi'          => null,
                        'keterangan'                    => null,
                    ]);

                    $successCount++;
                }

                fclose($handle);

            } catch (\Exception $e) {
                if (isset($handle) && is_resource($handle)) {
                    fclose($handle);
                }
                return back()->with('error', 'Gagal memproses file ' . $file->getClientOriginalName() . ': ' . $e->getMessage());
            }
        }

        $message = "Berhasil mengimpor {$successCount} data asset firewall dari file test.";
        if (count($skippedReasons) > 0) {
            session()->flash('import_skipped_reasons', $skippedReasons);
        }

        return redirect()
            ->route('manage-firewall')
            ->with('success', $message);
    }

    /**
     * SIMPAN FIREWALL BARU (MANUAL)
     */
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

        AssetHistory::create([
            'asset_id' => $firewall->id_aset ?? 'FIREWALL',
            'user_id' => auth()->id(),
            'action' => 'TAMBAH',
            'description' => 'Menambahkan aset Firewall baru: ' . $firewall->merk . ' ' . $firewall->model,
        ]);

        return redirect()
            ->route('manage-firewall') 
            ->with('success', 'Aset Firewall berhasil ditambahkan!');
    }

    /**
     * FORM EDIT
     */
    public function edit($id)
    {
        $asset = Firewall::findOrFail($id); 

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

        AssetHistory::create([
            'asset_id' => $firewall->id ?? 'FIREWALL',
            'user_id' => auth()->id(),
            'action' => 'UBAH',
            'description' => 'Memperbarui data aset Firewall: ' . $firewall->id_aset,
        ]);

        return redirect()
            ->route('manage-firewall') 
            ->with('success', 'Aset Firewall berhasil diperbarui!');
    }

    /**
     * HAPUS FIREWALL
     */
    public function destroy($id)
    {
        $firewall = Firewall::findOrFail($id);
        $firewall->delete();

        AssetHistory::create([
            'asset_id' => $firewall->id ?? 'FIREWALL',
            'user_id' => auth()->id(),
            'action' => 'HAPUS',
            'description' => 'Menghapus aset Firewall: ' . $firewall->id_aset,
        ]);

        return redirect()
            ->route('manage-firewall') 
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
            'versi_firmware_os' => ['required', 'string', 'max:255'], 
            'konsumsi_daya' => ['nullable', 'numeric'], 
            'rack' => ['nullable', 'string', 'max:255'],
            'masa_berlaku_garansi' => ['nullable', 'date'],
            'status_kepemilikan' => ['nullable', 'string', 'max:255'],
            'keterangan_status_kepemilikan' => ['nullable', 'string', 'max:255'], // Disamakan dengan DB (bukan ket_status...)
            'klasifikasi_keamanan_aset' => ['nullable', 'string', 'max:255'],
            'deskripsi_tujuan_peran_fungsi' => ['nullable', 'string'], // Disamakan dengan DB
            'keterangan_lokasi_aset' => ['nullable', 'string'],
            'tanggal_pemeriksaan_terakhir' => ['nullable', 'date'],
            'tanggal_mulai_aktif' => ['nullable', 'date'],
            'pic_pencatat' => ['nullable', 'string', 'max:255'],
            'bidang_pencatat_aset' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);
    }

    public function history(Request $request)
    {
        $query = \App\Models\AssetHistory::with('user')->latest();

        // Opsional: Fitur pencarian pada halaman riwayat perubahan
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                ->orWhere('asset_id', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $histories = $query->paginate(15)->withQueryString();

        return view('assetHistory', compact('histories'));
    }
}