<?php

namespace App\Http\Controllers;

use App\Models\WirelessLan;
use App\Models\AssetHistory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WirelessLanController extends Controller
{
    public function index(Request $request)
    {
        $query = WirelessLan::query();

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

        $query->when($request->filled('kondisi'), fn ($q) => $q->where('status_kondisi', $request->kondisi));
        $query->when($request->filled('status_operasional'), fn ($q) => $q->where('status_operasional', $request->status_operasional));
        $query->when($request->filled('lokasi'), fn ($q) => $q->where('lokasi_aset_saat_ini', 'ILIKE', "%{$request->lokasi}%"));

        $wlcList = $query->orderBy('id_aset')->paginate(15)->withQueryString();
        $lokasi = WirelessLan::select('lokasi_aset_saat_ini')->distinct()->orderBy('lokasi_aset_saat_ini')->pluck('lokasi_aset_saat_ini');

        return view('assets.wirelesslan.index', compact('wlcList', 'lokasi'));
    }

    public function create()
    {
        $asset = null;
        return view('assets.wirelesslan.form', compact('asset'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateInput($request);
        $validated['klasifikasi_keamanan'] = $validated['klasifikasi_keamanan'] ?? 'internal';
        $validated['tanggal_perolehan'] = $validated['tanggal_perolehan'] ?? now()->toDateString();
        $validated['pic_pencatat'] = $request->input('pic_pencatat') ?? auth()->user()->name;

        $wlc = WirelessLan::create($validated);

        AssetHistory::create([
            'asset_id' => $wlc->id,
            'user_id' => auth()->id(),
            'action' => 'TAMBAH',
            'description' => 'Menambahkan Wireless LAN Controller baru: ' . $wlc->merk . ' ' . $wlc->model,
        ]);

        return redirect()->route('manage-wireless-lan')->with('success', 'Aset Wireless LAN Controller berhasil ditambahkan.');
    }

    public function edit(WirelessLan $wirelessLan)
    {
        $asset = $wirelessLan;
        return view('assets.wirelesslan.form', compact('asset'));
    }

    public function update(Request $request, WirelessLan $wirelessLan)
    {
        $validated = $this->validateInput($request, $wirelessLan->id);
        $validated['klasifikasi_keamanan'] = $validated['klasifikasi_keamanan'] ?? 'internal';
        $validated['pic_pencatat'] = $request->input('pic_pencatat') ?? auth()->user()->name;

        $wirelessLan->update($validated);

        AssetHistory::create([
            'asset_id' => $wirelessLan->id,
            'user_id' => auth()->id(),
            'action' => 'UBAH',
            'description' => 'Memperbarui data Wireless LAN Controller: ' . $wirelessLan->id_aset,
        ]);

        return redirect()->route('manage-wireless-lan')->with('success', 'Aset Wireless LAN Controller berhasil diperbarui.');
    }

    public function destroy(WirelessLan $wirelessLan)
    {
        $idAset = $wirelessLan->id_aset;
        $wlcId = $wirelessLan->id;
        $wirelessLan->delete();

        AssetHistory::create([
            'asset_id' => $wlcId,
            'user_id' => auth()->id(),
            'action' => 'HAPUS',
            'description' => 'Menghapus Wireless LAN Controller: ' . $idAset,
        ]);

        return redirect()->route('manage-wireless-lan')->with('success', 'Data Wireless LAN Controller berhasil dihapus.');
    }

    public function importForm()
    {
        return view('assets.wirelesslan.import');
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'files'   => ['required', 'array'],
            'files.*' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        if (!$request->hasFile('files')) {
            return back()->with('error', 'Tidak ada file yang diunggah.');
        }

        $skippedReasons = [];
        $successCount = 0;
        $wlc = null;

        foreach ($request->file('files') as $file) {
            try {
                $handle = fopen($file->getRealPath(), 'r');
                $sampleLine = fgets($handle);
                rewind($handle);
                $delimiter = (substr_count($sampleLine, ';') > substr_count($sampleLine, ',')) ? ';' : ',';

                $rowIndex = 0;
                while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                    $rowIndex++;
                    if ($rowIndex <= 5) continue; // Skip header template
                    
                    if (count($row) < 27) {
                        $skippedReasons[] = "Baris ke-{$rowIndex} dilewati: Jumlah kolom kurang dari template (27 kolom).";
                        continue;
                    }
                    if (empty(trim($row[1] ?? '')) && empty(trim($row[15] ?? ''))) continue;

                    $merk = $row[15] ?? null;
                    $model = $row[16] ?? null;

                    if (empty(trim($merk)) || empty(trim($model))) {
                        $skippedReasons[] = "Baris ke-{$rowIndex} dilewati: Kolom wajib (Merk, Model) kosong.";
                        continue;
                    }

                    $parseDate = fn($val) => !empty(trim($val ?? '')) && strtotime($val) ? date('Y-m-d', strtotime($val)) : null;

                    $wlc = WirelessLan::create([
                        'id_aset'                       => !empty(trim($row[1] ?? '')) ? trim($row[1]) : 'WLC-' . strtoupper(uniqid()),
                        'tanggal_perolehan'             => $parseDate($row[2] ?? '') ?? now()->toDateString(),
                        'status_kepemilikan'            => trim($row[3] ?? 'Milik PLN'),
                        'keterangan_status_kepemilikan' => trim($row[4] ?? ''),
                        'status_kondisi'                => strtolower(trim($row[5] ?? 'baik')),
                        'status_operasional'            => strtolower(trim($row[6] ?? 'aktif')),
                        'tingkat_kritikalitas'          => strtolower(trim($row[7] ?? 'normal')),
                        'klasifikasi_keamanan'          => strtolower(trim($row[8] ?? 'internal')),
                        'deskripsi_tujuan'              => trim($row[9] ?? ''),
                        'lokasi_aset_saat_ini'          => trim($row[10] ?? 'Pusat'),
                        'keterangan_lokasi_aset'        => trim($row[11] ?? ''),
                        'tanggal_pemeriksaan_terakhir'  => $parseDate($row[12] ?? ''),
                        'pic_pencatat'                  => trim($row[13] ?? '') ?: auth()->user()->name,
                        'bidang_pencatat_aset'          => trim($row[14] ?? ''),
                        
                        // Atribut Spesifik WLC
                        'merk'                          => trim($merk),
                        'model'                         => trim($model),
                        'bentuk_fisik'                  => trim($row[17] ?? ''),
                        'serial_number'                 => trim($row[18] ?? ''),
                        'mac_address'                   => trim($row[19] ?? ''),
                        'ip_address'                    => trim($row[20] ?? ''),
                        'enkripsi'                      => trim($row[21] ?? ''),
                        'versi_firmware'                => trim($row[22] ?? ''),
                        'konsumsi_daya'                 => !empty(trim($row[23] ?? '')) ? (float) trim($row[23]) : null,
                        'rack'                          => trim($row[24] ?? ''),
                        'masa_berlaku_garansi'          => $parseDate($row[25] ?? ''),
                        'keterangan'                    => trim($row[26] ?? ''),
                    ]);

                    $successCount++;
                }
                fclose($handle);
            } catch (\Exception $e) {
                if (isset($handle) && is_resource($handle)) fclose($handle);
                return back()->with('error', 'Gagal memproses file: ' . $e->getMessage());
            }
        }

        if ($successCount > 0 && $wlc) {
            AssetHistory::create([
                'asset_id' => $wlc->id,
                'user_id' => auth()->id(),
                'action' => 'TAMBAH',
                'description' => "Melakukan impor massal data Wireless LAN Controller ({$successCount} data berhasil).",
            ]);
        }

        $message = "Berhasil mengimpor {$successCount} data Wireless LAN Controller.";
        if (count($skippedReasons) > 0) {
            session()->flash('import_skipped_reasons', $skippedReasons);
        }

        return redirect()->route('manage-wireless-lan')->with('success', $message);
    }

    private function validateInput(Request $request, $id = null)
    {
        return $request->validate([
            'id_aset' => ['required', 'string', 'max:255', $id ? Rule::unique('wireless_lans', 'id_aset')->ignore($id) : 'unique:wireless_lans,id_aset'],
            'merk' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'bentuk_fisik' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'mac_address' => 'nullable|string|max:255',
            'ip_address' => 'nullable|string|max:255',
            'enkripsi' => 'nullable|string|max:255',
            'versi_firmware' => 'nullable|string|max:255',
            'lokasi_aset_saat_ini' => 'required|string|max:255',
            'keterangan_lokasi_aset' => 'nullable|string|max:255',
            'status_kondisi' => 'required|string|max:255',
            'status_operasional' => 'required|string|max:255',
            'tingkat_kritikalitas' => 'required|string|max:255',
            'klasifikasi_keamanan' => 'nullable|string|max:255',
            'status_kepemilikan' => 'nullable|string|max:255',
            'keterangan_status_kepemilikan' => 'nullable|string|max:255',
            'deskripsi_tujuan' => 'nullable|string',
            'tanggal_perolehan' => 'nullable|date',
            'tanggal_pemeriksaan_terakhir' => 'nullable|date',
            'konsumsi_daya' => 'nullable|numeric',
            'rack' => 'nullable|string|max:255',
            'masa_berlaku_garansi' => 'nullable|date',
            'keterangan' => 'nullable|string',
            'bidang_pencatat_aset' => 'nullable|string|max:255',
        ]);
    }
}