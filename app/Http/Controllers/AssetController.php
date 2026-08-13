<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Unit;
use App\Models\AssetCategory;
use App\Models\SuttTower;
use App\Models\AssetHistory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class AssetController extends Controller
{
    private const FIELD_ALIASES = [
        'generic' => [
            'name' => ['nama', 'name', 'nama_asset', 'nama aset'],
            'category' => ['category', 'kategori', 'jenis', 'grup', 'group'],
            'code' => ['functloc', 'code', 'kode', 'no_tiang', 'no_tower'],
            'gi_awal' => ['gi_awal', 'gardu_induk_awal', 'gi awal', 'from_gi', 'gi1'],
            'gi_akhir' => ['gi_akhir', 'gardu_induk_akhir', 'gi akhir', 'to_gi', 'gi2'],
            'latitude' => ['lock lat', 'lock_lat', 'latitude', 'lat'],
            'longitude' => ['lock lng', 'lock_lng', 'longitude', 'lng'],
            'wil_kerja' => ['wil. kerja', 'wil kerja', 'wilayah kerja', 'wil_kerja'],
        ],
    ];

    private const FIELD_LABELS = [
        'name' => 'Nama Aset',
        'category' => 'Kategori',
        'code' => 'Kode / Functloc',
        'gi_awal' => 'GI Awal',
        'gi_akhir' => 'GI Akhir',
        'latitude' => 'Latitude',
        'longitude' => 'Longitude',
    ];

    private const TEGANGAN_OPTIONS = ['30', '70', '150', '500'];

    // =========================================================================
    // 1. MANAGE ASSET UMUM
    // =========================================================================

    public function index(Request $request)
    {
        $query = DB::table('sutt_lines')->orderBy('name');

        if ($search = $request->query('search')) {
            $query->where('name', 'ILIKE', "%{$search}%");
        }

        $tegangan = $request->query('tegangan');
        if ($tegangan && in_array($tegangan, self::TEGANGAN_OPTIONS)) {
            $query->whereRaw('name ~* ?', ["{$tegangan}\\s*kv"]);
        }

        $assets = $query->paginate(20)->withQueryString();

        foreach ($assets as $line) {
            $teganganDinamis = '150 kV';
            if (preg_match('/(30|70|150|500)\s*kv/i', $line->name, $matchTegangan)) {
                $teganganDinamis = $matchTegangan[1] . ' kV';
            }
            $line->tegangan = $teganganDinamis;

            $line->gi_awal_name = isset($line->gi_awal_id) ? optional(Unit::find($line->gi_awal_id))->name : null;
            $line->gi_akhir_name = isset($line->gi_akhir_id) ? optional(Unit::find($line->gi_akhir_id))->name : null;
        }

        return view('manageAsset', [
            'assets' => $assets,
            'teganganOptions' => self::TEGANGAN_OPTIONS,
            'selectedTegangan' => $tegangan,
        ]);
    }

    public function indexByCategory($categorySlug)
    {
        $currentCategory = AssetCategory::where('slug', $categorySlug)->firstOrFail();

        $assets = Asset::where('asset_category_id', $currentCategory->id)
                    ->orderBy('name')
                    ->paginate(20)
                    ->withQueryString();

        return view('manageAsset', [
            'assets' => $assets,
            'currentCategory' => $currentCategory,
        ]);
    }

    public function create(Request $request)
    {
        $upts = Unit::where('level', 2)->orderBy('name')->get();
        $garduInduks = Unit::where('level', 4)->orderBy('name')->get();
        $asset = null;

        return view('assetForm', compact('upts', 'garduInduks', 'asset'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'functloc' => 'required|string|max:50',
            'upt_id' => 'required|exists:units,id',
            'tegangan' => 'required|string',
            'gi_awal_id' => 'required|exists:units,id',
            'gi_akhir_id' => 'required|exists:units,id|different:gi_awal_id',
            'jumlah_tower' => 'required|integer|min:1',
            'panjang_km' => 'required|numeric|min:0',
        ]);

        $asset = Asset::create([
            'name' => $request->name,
            'functloc' => $request->functloc,
            'category' => 'sutt',
            'upt_id' => $request->upt_id,
            'tegangan' => $request->tegangan,
            'gi_awal_id' => $request->gi_awal_id,
            'gi_akhir_id' => $request->gi_akhir_id,
            'jumlah_tower' => $request->jumlah_tower,
            'panjang_km' => $request->panjang_km,
        ]);

        AssetHistory::create([
            'asset_id' => $asset->id,
            'user_id' => auth()->id(),
            'action' => 'TAMBAH',
            'description' => 'Menambahkan jalur SUTT baru: ' . $asset->name,
        ]);

        return redirect()->route('manage-asset')->with('success', 'Jalur SUTT berhasil ditambahkan.');
    }

    public function edit(Asset $asset)
    {
        $upts = Unit::where('level', 2)->orderBy('name')->get();
        $garduInduks = Unit::where('level', 4)->orderBy('name')->get();

        return view('assetForm', compact('upts', 'garduInduks', 'asset'));
    }

    public function update(Request $request, Asset $asset)
    {
        $validated = $this->validateAsset($request, $asset->id);
        $asset->update($validated);

        AssetHistory::create([
            'asset_id' => $asset->id,
            'user_id' => auth()->id(),
            'action' => 'UBAH',
            'description' => 'Memperbarui data informasi aset: ' . $asset->name,
        ]);

        return redirect()->route('manage-asset')->with('success', 'Aset berhasil diperbarui.');
    }

    public function destroy(Asset $asset)
    {
        $assetName = $asset->name;
        $assetId = $asset->id;

        // Jika Anda mengatur foreign key constraint dengan ON DELETE CASCADE di database, 
        // sutt_towers akan otomatis terhapus. Jika tidak, aktifkan baris di bawah ini:
        // SuttTower::where('sutt_line_id', $assetId)->delete();

        $asset->delete();

        AssetHistory::create([
            'asset_id' => $assetId,
            'user_id' => auth()->id(),
            'action' => 'HAPUS',
            'description' => 'Menghapus aset/jalur SUTT (File Tower): ' . $assetName,
        ]);

        return back()->with('success', 'File dan Data Tower di dalamnya berhasil dihapus.');
    }

    private function validateAsset(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50'],
            'gi_awal_id' => ['nullable', 'exists:units,id'],
            'gi_akhir_id' => ['nullable', 'exists:units,id'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);
    }


    // =========================================================================
    // 2. MANAGE TOWER (FILE BASED IMPORT & DETAIL)
    // =========================================================================

    /**
     * Menampilkan daftar File CSV (Jalur SUTT) yang telah diunggah.
     */
    public function indexTower(Request $request)
    {
        // 1. Baca data langsung dari Model Asset dengan kategori 'sutt' (bukan sutt_lines)
        $query = Asset::where('category', 'sutt');

        if ($search = $request->query('search')) {
            $query->where('name', 'ILIKE', "%{$search}%");
        }

        // 2. Simpan di variabel $assets agar sesuai dengan foreach di manageAsset.blade.php
        $assets = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $currentCategory = (object) ['name' => 'Tower', 'slug' => 'tower'];

        // 3. Return ke view manageAsset
        return view('manageAsset', compact('assets', 'currentCategory'));
    }

    /**
     * Menampilkan isi rincian Tower di dalam satu File / Jalur SUTT
     */
    public function show($id, Request $request)
    {
        // Ganti dari DB::table('sutt_lines') menjadi Asset::
        $line = Asset::where('id', $id)->first();

        if (!$line) {
            return redirect()->back()->with('error', 'Jalur SUTT tidak ditemukan.');
        }

        $allTowersOrdered = SuttTower::where('sutt_line_id', $id)->orderBy('tower_number', 'asc')->get();

        $pathLengthKm = 0;
        $prevLat = null;
        $prevLng = null;

        foreach ($allTowersOrdered as $tower) {
            $dist = $this->calculateHaversineDistance($prevLat, $prevLng, $tower->latitude, $tower->longitude);
            $tower->jarak_antar_tower = $dist ? round($dist * 1000, 2) : null; 
            
            $pathLengthKm += ($dist ?? 0);

            $prevLat = $tower->latitude;
            $prevLng = $tower->longitude;
        }

        $query = SuttTower::where('sutt_line_id', $id);
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('tower_number', 'ILIKE', "%{$search}%")
                  ->orWhere('functloc', 'ILIKE', "%{$search}%")
                  ->orWhere('name', 'ILIKE', "%{$search}%");
            });
        }
        
        $towers = $query->orderBy('tower_number', 'asc')->paginate(50)->withQueryString();

        foreach ($towers as $t) {
            $matched = $allTowersOrdered->firstWhere('id', $t->id);
            $t->jarak_antar_tower = $matched ? $matched->jarak_antar_tower : null;
        }

        return view('assetShow', [
            'line' => $line,
            'towers' => $towers,
            'totalTowers' => $allTowersOrdered->count(),
            'pathLengthKm' => $pathLengthKm,
        ]);
    }

    public function importForm()
    {
        return view('assetImport');
    }

    /**
     * Proses Import Multiple CSV File
     */
    public function import(Request $request)
    {
        $request->validate([
            'files'   => ['required', 'array'],
            'files.*' => ['required', 'file'],
        ]);

        $uploadedFiles = $request->file('files');
        $totalCreatedFiles = 0;
        $allSkippedReasons = [];

        foreach ($uploadedFiles as $file) {
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            
            $defaultUpt = Unit::where('level', 2)->first();
            $defaultUptId = $defaultUpt ? $defaultUpt->id : null;

            // 1. Buat Jalur Utama di Tabel assets
            $assetLine = Asset::create([
                'name'         => $originalName,
                'category'     => 'sutt',
                'functloc'     => 'LINE-' . strtoupper(Str::random(6)),
                'upt_id'       => $defaultUptId,
                'tegangan'     => '150 kV',
                'jumlah_tower' => 0,
                'panjang_km'   => 0,
            ]);
            
            $lineId = $assetLine->id;

            // Sinkronisasi ke sutt_lines (jika tabel sutt_lines wajib ada)
            try {
                if (!DB::table('sutt_lines')->where('id', $lineId)->exists()) {
                    DB::table('sutt_lines')->insert([
                        'id' => $lineId,
                        'name' => $originalName,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } catch (\Exception $e) {}

            // 2. Buka dan Baca File CSV
            $handle = fopen($file->getRealPath(), 'r');
            if (!$handle) continue;

            $sampleLine = fgets($handle);
            rewind($handle);
            $delimiter = (substr_count($sampleLine, ';') > substr_count($sampleLine, ',')) ? ';' : ',';

            $rowIndex = 0;
            $towersToInsert = [];
            
            // Buat counter buatan sendiri agar tower_number selalu unik, berurutan, dan 100% INTEGER
            $autoIncrementTowerId = 1; 

            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                $rowIndex++;
                
                // Lewati header
                if ($rowIndex == 1) continue;
                if (empty($row) || count($row) < 3) continue;

                $functloc = trim($row[2] ?? '');
                $nama     = trim($row[6] ?? ('Tower ' . $rowIndex));
                
                $lat = !empty(trim($row[7] ?? '')) ? (float) trim($row[7]) : null;
                $lng = !empty(trim($row[8] ?? '')) ? (float) trim($row[8]) : null;

                if (empty($functloc) && empty($nama)) continue;

                $towersToInsert[] = [
                    'sutt_line_id' => $lineId,
                    'tower_number' => $autoIncrementTowerId, // Menggunakan angka urut 1, 2, 3... berapapun panjang CSV-nya
                    'functloc'     => $functloc,
                    'name'         => $nama,
                    'latitude'     => $lat,
                    'longitude'    => $lng,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];
                
                // Naikkan angka untuk baris berikutnya
                $autoIncrementTowerId++; 
            }
            fclose($handle);

            if (count($towersToInsert) > 0) {
                // Insert ke database
                foreach (array_chunk($towersToInsert, 500) as $chunk) {
                    SuttTower::insertOrIgnore($chunk);
                }

                $this->recalcLineStats($lineId);

                AssetHistory::create([
                    'asset_id' => $lineId,
                    'user_id' => auth()->id(),
                    'action' => 'TAMBAH',
                    'description' => "Mengimpor File CSV Tower: {$originalName} (" . count($towersToInsert) . " tower)",
                ]);

                $totalCreatedFiles++;
            }
        }

        return redirect()->route('manage-asset.tower.index')
            ->with('success', "Berhasil mengimpor {$totalCreatedFiles} File Data Tower ke database.");
    }

    public function editTower($towerId)
    {
        $tower = SuttTower::findOrFail($towerId);
        $line = DB::table('sutt_lines')->where('id', $tower->sutt_line_id)->first();

        return view('towerForm', compact('tower', 'line'));
    }

    public function updateTower(Request $request, $towerId)
    {
        $tower = SuttTower::findOrFail($towerId);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'functloc' => ['required', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $tower->update($validated);

        $this->recalcLineStats($tower->sutt_line_id);

        AssetHistory::create([
            'asset_id' => $tower->sutt_line_id,
            'user_id' => auth()->id(),
            'action' => 'UBAH',
            'description' => 'Memperbarui data titik tower: ' . $tower->name,
        ]);

        return redirect()->route('manage-tower.show', $tower->sutt_line_id)
            ->with('success', 'Data tower berhasil diperbarui.');
    }

    public function destroyTower($towerId)
    {
        $tower = SuttTower::findOrFail($towerId);
        $lineId = $tower->sutt_line_id;
        $towerName = $tower->name;

        $tower->delete();

        $this->recalcLineStats($lineId);

        AssetHistory::create([
            'asset_id' => $lineId,
            'user_id' => auth()->id(),
            'action' => 'HAPUS',
            'description' => 'Menghapus titik tower: ' . $towerName,
        ]);

        return redirect()->route('manage-tower.show', $lineId)
            ->with('success', 'Tower berhasil dihapus.');
    }

    private function recalcLineStats(int $lineId): void
    {
        $panjang = $this->hitungPanjangJalurSUTT($lineId);
        $jumlahTower = SuttTower::where('sutt_line_id', $lineId)->count();

        try {
            // Gunakan Model Asset agar pasti menargetkan tabel utama (assets)
            Asset::where('id', $lineId)->update([
                'panjang_km' => $panjang,
                'jumlah_tower' => $jumlahTower,
            ]);
        } catch (\Exception $e) {
            //
        }
    }

    public function hitungPanjangJalurSUTT($assetId)
    {
        $towers = SuttTower::where('sutt_line_id', $assetId)
                    ->orderBy('tower_number', 'asc')
                    ->get();

        $totalKm = 0;

        for ($i = 0; $i < count($towers) - 1; $i++) {
            $lat1 = $towers[$i]->latitude;
            $lon1 = $towers[$i]->longitude;

            $lat2 = $towers[$i+1]->latitude;
            $lon2 = $towers[$i+1]->longitude;

            $totalKm += $this->calculateHaversineDistance($lat1, $lon1, $lat2, $lon2) ?? 0;
        }

        return round($totalKm, 2);
    }

    private function calculateHaversineDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
    {
        if (!$latitudeFrom || !$longitudeFrom || !$latitudeTo || !$longitudeTo) return null;

        $earthRadius = 6371; // Radius bumi dalam KM

        $latFrom = deg2rad((float)$latitudeFrom);
        $lonFrom = deg2rad((float)$longitudeFrom);
        $latTo = deg2rad((float)$latitudeTo);
        $lonTo = deg2rad((float)$longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos($latFrom) * cos($latTo) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // Nilai Kembali berupa KM
    }

    // =========================================================================
    // 3. ASSET HISTORY & EXPORT (DISATUKAN)
    // =========================================================================

    public function history(Request $request)
    {
        $query = AssetHistory::with('user')->latest();

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

    public function exportCsv()
    {
        $fileName = 'riwayat_perubahan_aset_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['Waktu', 'Aksi', 'ID Aset', 'Rincian', 'Oleh']);

            AssetHistory::with('user')->orderBy('created_at', 'desc')->chunk(500, function ($histories) use ($file) {
                foreach ($histories as $history) {
                    fputcsv($file, [
                        $history->created_at->format('Y-m-d H:i:s'),    
                        strtoupper($history->action),                   
                        $history->asset_id,                             
                        $history->description,                          
                        $history->user->name ?? 'Pengguna Dihapus'      
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}