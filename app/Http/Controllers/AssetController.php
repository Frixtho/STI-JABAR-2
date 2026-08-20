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

class AssetController extends Controller
{
    private const TEGANGAN_OPTIONS = ['30', '70', '150', '500'];

    /*
    |--------------------------------------------------------------------------
    | 1. MANAGE TOWER (SISTEM KHUSUS / STATIS)
    |--------------------------------------------------------------------------
    */

    public function indexTower(Request $request)
    {
        $query = Asset::where('category', 'sutt');

        if ($search = $request->query('search')) {
            $query->where('name', 'ILIKE', "%{$search}%");
        }

        $assets = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $currentCategory = (object) ['name' => 'Tower', 'slug' => 'tower'];

        return view('assets.tower.index', compact('assets', 'currentCategory'));
    }

    public function show($id, Request $request)
    {
        $line = Asset::where('id', $id)->first();
        if (!$line) return redirect()->back()->with('error', 'Jalur SUTT tidak ditemukan.');

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

        return view('assets.tower.show', [
            'line' => $line,
            'towers' => $towers,
            'totalTowers' => $allTowersOrdered->count(),
            'pathLengthKm' => $pathLengthKm,
        ]);
    }

    public function importForm()
    {
        return view('assets.tower.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'files'   => ['required', 'array'],
            'files.*' => ['required', 'file'],
        ]);

        $uploadedFiles = $request->file('files');
        $totalCreatedFiles = 0;

        foreach ($uploadedFiles as $file) {
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $defaultUptId = Unit::where('level', 2)->first()->id ?? null;

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

            try {
                if (!DB::table('sutt_lines')->where('id', $lineId)->exists()) {
                    DB::table('sutt_lines')->insert(['id' => $lineId, 'name' => $originalName, 'created_at' => now(), 'updated_at' => now()]);
                }
            } catch (\Exception $e) {}

            $handle = fopen($file->getRealPath(), 'r');
            if (!$handle) continue;

            $sampleLine = fgets($handle);
            rewind($handle);
            $delimiter = (substr_count($sampleLine, ';') > substr_count($sampleLine, ',')) ? ';' : ',';

            $rowIndex = 0;
            $towersToInsert = [];
            $autoIncrementTowerId = 1; 

            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                $rowIndex++;
                if ($rowIndex == 1 || empty($row) || count($row) < 3) continue;

                $functloc = trim($row[2] ?? '');
                $nama     = trim($row[6] ?? ('Tower ' . $rowIndex));
                $lat = !empty(trim($row[7] ?? '')) ? (float) trim($row[7]) : null;
                $lng = !empty(trim($row[8] ?? '')) ? (float) trim($row[8]) : null;

                if (empty($functloc) && empty($nama)) continue;

                $towersToInsert[] = [
                    'sutt_line_id' => $lineId,
                    'tower_number' => $autoIncrementTowerId++, 
                    'functloc'     => $functloc,
                    'name'         => $nama,
                    'latitude'     => $lat,
                    'longitude'    => $lng,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];
            }
            fclose($handle);

            if (count($towersToInsert) > 0) {
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

        return redirect()->route('manage-asset.tower.index')->with('success', "Berhasil mengimpor {$totalCreatedFiles} File Data Tower ke database.");
    }

    public function destroy(Asset $asset)
    {
        $assetName = $asset->name;
        $assetId = $asset->id;
        $asset->delete();

        AssetHistory::create([
            'asset_id' => $assetId,
            'user_id' => auth()->id(),
            'action' => 'HAPUS',
            'description' => 'Menghapus aset/jalur SUTT: ' . $assetName,
        ]);

        return back()->with('success', 'File dan Data Tower di dalamnya berhasil dihapus.');
    }

    public function editTower($towerId)
    {
        $tower = SuttTower::findOrFail($towerId);
        $line = DB::table('sutt_lines')->where('id', $tower->sutt_line_id)->first();
        return view('assets.tower.form', compact('tower', 'line'));
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

        return redirect()->route('manage-asset.show', $tower->sutt_line_id)->with('success', 'Data tower berhasil diperbarui.');
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

        return redirect()->route('manage-asset.show', $lineId)->with('success', 'Tower berhasil dihapus.');
    }

    private function recalcLineStats(int $lineId): void
    {
        $towers = SuttTower::where('sutt_line_id', $lineId)->orderBy('tower_number', 'asc')->get();
        $totalKm = 0;

        for ($i = 0; $i < count($towers) - 1; $i++) {
            $totalKm += $this->calculateHaversineDistance($towers[$i]->latitude, $towers[$i]->longitude, $towers[$i+1]->latitude, $towers[$i+1]->longitude) ?? 0;
        }

        try {
            Asset::where('id', $lineId)->update([
                'panjang_km' => round($totalKm, 2),
                'jumlah_tower' => count($towers),
            ]);
        } catch (\Exception $e) {}
    }

    private function calculateHaversineDistance($latFrom, $lonFrom, $latTo, $lonTo)
    {
        if (!$latFrom || !$lonFrom || !$latTo || !$lonTo) return null;
        $earthRadius = 6371; 
        $latDelta = deg2rad($latTo - $latFrom);
        $lonDelta = deg2rad($lonTo - $lonFrom);

        $a = sin($latDelta / 2) * sin($latDelta / 2) + cos(deg2rad($latFrom)) * cos(deg2rad($latTo)) * sin($lonDelta / 2) * sin($lonDelta / 2);
        return $earthRadius * (2 * atan2(sqrt($a), sqrt(1 - $a))); 
    }


    /*
    |--------------------------------------------------------------------------
    | 2. MANAGE ASSET GENERIK (SISTEM SAPU JAGAT DINAMIS)
    |--------------------------------------------------------------------------
    */

    public function indexByCategory($categorySlug, Request $request)
    {
        $currentCategory = AssetCategory::where('slug', $categorySlug)->firstOrFail();
        $user = auth()->user();

        $query = Asset::where('category', $currentCategory->name);

        // DATA ISOLATION: User (Non-STI) hanya melihat aset di Unit-nya sendiri
        if (strcasecmp($user->role, 'STI') !== 0) {
            $query->where('unit_name', $user->department);
        }

        if ($search = $request->search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                  ->orWhere('asset_id', 'ILIKE', "%{$search}%")
                  ->orWhere('unit_name', 'ILIKE', "%{$search}%");
            });
        }

        $assets = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('assets.generic.index', compact('currentCategory', 'assets'));
    }

    public function createByCategory($categorySlug)
    {
        $currentCategory = AssetCategory::where('slug', $categorySlug)->firstOrFail();
        $asset = null;

        return view('assets.generic.form', compact('currentCategory', 'asset'));
    }

    public function storeByCategory(Request $request, $categorySlug)
    {
        $currentCategory = AssetCategory::with('fields')->where('slug', $categorySlug)->firstOrFail();

        // 1. TANGKAP SEMUA INPUTAN SPESIFIK (DINAMIS)
        $specifications = [];
        foreach ($currentCategory->fields as $field) {
            $specifications[$field->field_key] = $request->input($field->field_key);
        }

        // 2. SIMPAN DATA KE TABLE ASSETS (ATRIBUT UMUM + JSON SPESIFIKASI)
        $asset = Asset::create([
            'asset_id' => $request->asset_id,
            'name' => $request->name ?? $request->asset_id,
            'category' => $currentCategory->name,
            'unit_name' => $request->unit_name,
            'upt_id' => $request->upt_id ?? 1, // Fallback migrasi lama
            
            // Kolom Atribut Umum
            'acquisition_date' => $request->acquisition_date,
            'ownership_status' => $request->ownership_status,
            'ownership_desc' => $request->ownership_desc,
            'condition_status' => $request->condition_status,
            'operational_status' => $request->operational_status,
            'criticality_level' => $request->criticality_level,
            'security_classification' => $request->security_classification,
            'last_maintenance_date' => $request->last_maintenance_date,
            'description' => $request->description,
            'location_desc' => $request->location_desc,
            'pic' => $request->pic,
            'pic_department' => $request->pic_department,

            // Kolom Atribut Dinamis
            'specifications' => $specifications,
        ]);

        AssetHistory::create([
            'asset_id' => $asset->id,
            'user_id' => auth()->id(),
            'action' => 'TAMBAH',
            'description' => "Menambahkan {$currentCategory->name} baru: {$asset->name}",
        ]);

        return redirect()->route('manage-asset.generic.index', $categorySlug)->with('success', "{$currentCategory->name} berhasil ditambahkan.");
    }

    public function editByCategory($categorySlug, $id)
    {
        $currentCategory = AssetCategory::where('slug', $categorySlug)->firstOrFail();
        $asset = Asset::where('category', $currentCategory->name)->findOrFail($id);

        return view('assets.generic.form', compact('currentCategory', 'asset'));
    }

    public function updateByCategory(Request $request, $categorySlug, $id)
    {
        $currentCategory = AssetCategory::with('fields')->where('slug', $categorySlug)->firstOrFail();
        $asset = Asset::where('category', $currentCategory->name)->findOrFail($id);

        // 1. TANGKAP SEMUA INPUTAN SPESIFIK (DINAMIS)
        $specifications = [];
        foreach ($currentCategory->fields as $field) {
            $specifications[$field->field_key] = $request->input($field->field_key);
        }

        // 2. PERBARUI DATABASE
        $asset->update([
            'asset_id' => $request->asset_id,
            'name' => $request->name ?? $request->asset_id,
            'unit_name' => $request->unit_name,
            
            // Kolom Atribut Umum
            'acquisition_date' => $request->acquisition_date,
            'ownership_status' => $request->ownership_status,
            'ownership_desc' => $request->ownership_desc,
            'condition_status' => $request->condition_status,
            'operational_status' => $request->operational_status,
            'criticality_level' => $request->criticality_level,
            'security_classification' => $request->security_classification,
            'last_maintenance_date' => $request->last_maintenance_date,
            'description' => $request->description,
            'location_desc' => $request->location_desc,
            'pic' => $request->pic,
            'pic_department' => $request->pic_department,

            // Kolom Atribut Dinamis
            'specifications' => $specifications,
        ]);

        AssetHistory::create([
            'asset_id' => $asset->id,
            'user_id' => auth()->id(),
            'action' => 'UBAH',
            'description' => "Memperbarui {$currentCategory->name}: {$asset->name}",
        ]);

        return redirect()->route('manage-asset.generic.index', $categorySlug)->with('success', "{$currentCategory->name} berhasil diperbarui.");
    }

    public function destroyByCategory($categorySlug, $id)
    {
        $currentCategory = AssetCategory::where('slug', $categorySlug)->firstOrFail();
        $asset = Asset::where('category', $currentCategory->name)->findOrFail($id);
        
        $assetName = $asset->name;
        $asset->delete();

        AssetHistory::create([
            'asset_id' => $id,
            'user_id' => auth()->id(),
            'action' => 'HAPUS',
            'description' => "Menghapus {$currentCategory->name}: {$assetName}",
        ]);

        return redirect()->route('manage-asset.generic.index', $categorySlug)->with('success', "{$currentCategory->name} berhasil dihapus.");
    }

    public function importFormByCategory($categorySlug)
    {
        $currentCategory = AssetCategory::where('slug', $categorySlug)->firstOrFail();
        return view('assets.generic.import', compact('currentCategory'));
    }

    public function importStoreByCategory(Request $request, $categorySlug)
    {
        $currentCategory = AssetCategory::where('slug', $categorySlug)->firstOrFail();
        
        $request->validate([
            'files'   => ['required', 'array'],
            'files.*' => ['required', 'file'],
        ]);

        $totalCreated = 0;
        $defaultUptId = Unit::where('level', 2)->first()->id ?? 1;

        // DAFTAR ATRIBUT UMUM (Nama Header CSV yang akan masuk ke kolom utama tabel 'assets', bukan ke JSON)
        // Anda bisa menyesuaikan atau menambah alias nama header di sini
        $commonFieldsMap = [
            'id aset' => 'asset_id',
            'asset id' => 'asset_id',
            'nama' => 'name',
            'nama aset' => 'name',
            'unit' => 'unit_name',
            'departemen' => 'unit_name',
            'status kondisi' => 'condition_status',
            'kondisi' => 'condition_status',
            'status operasional' => 'operational_status',
            'operasional' => 'operational_status',
            'pic' => 'pic',
            'pic pencatat' => 'pic',
        ];

        foreach ($request->file('files') as $file) {
            $handle = fopen($file->getRealPath(), 'r');
            
            // 1. DETEKSI DELIMITER & BACA HEADER (BARIS PERTAMA CSV)
            $firstLine = fgets($handle);
            rewind($handle);
            $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';
            
            $headers = fgetcsv($handle, 0, $delimiter);
            if (!$headers) continue;

            // Bersihkan nama header dari spasi berlebih atau karakter aneh
            $headers = array_map(function($val) {
                return trim(preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $val)); 
            }, $headers);

            // 2. AUTO-SCHEMA DISCOVERY (Bikin Form Otomatis dari Header CSV)
            $dynamicKeys = [];
            foreach ($headers as $index => $headerName) {
                if (empty($headerName)) continue;

                $headerLower = strtolower($headerName);
                
                // Cek apakah header ini bagian dari Atribut Umum? Jika ya, lewati.
                if (array_key_exists($headerLower, $commonFieldsMap)) {
                    continue; 
                }

                // Jika bukan atribut umum, ini adalah ATRIBUT SPESIFIK (Dinamis).
                // Buat key yang aman untuk JSON (contoh: "IP Address" jadi "ip_address")
                $fieldKey = \Illuminate\Support\Str::slug($headerName, '_');
                $dynamicKeys[$index] = $fieldKey;

               // AUTO-CREATE FORM FIELD JIKA BELUM ADA DI DATABASE
                \App\Models\AssetCategoryField::firstOrCreate(
                    [
                        'asset_category_id' => $currentCategory->id,
                        'field_key' => $fieldKey,
                    ],
                    [
                        'name' => ucwords(str_replace('_', ' ', $headerName)), // <-- Sudah disesuaikan dengan DB
                        'field_type' => 'text', 
                        'is_required' => false,
                        'show_in_table' => true,
                        'group_name' => 'ATRIBUT SPESIFIK', // Agar masuk ke dalam grup form yang rapi
                    ]
                );
            }

            // 3. BACA DATA BARIS DEMI BARIS
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                if (empty($row) || count($row) < 1) continue;

                $assetData = [
                    'category' => $currentCategory->name,
                    'upt_id' => $defaultUptId,
                    'pic' => auth()->user()->name, // Default PIC
                    'condition_status' => 'Baik', // Default Kondisi
                    'operational_status' => 'Aktif', // Default Operasional
                ];
                $specifications = [];

                // Mapping isi baris ke header
                foreach ($headers as $index => $headerName) {
                    $val = trim($row[$index] ?? '');
                    $headerLower = strtolower($headerName);

                    // Jika ini atribut umum, masukkan ke kolom utama
                    if (array_key_exists($headerLower, $commonFieldsMap)) {
                        $dbColumn = $commonFieldsMap[$headerLower];
                        if (!empty($val)) {
                            $assetData[$dbColumn] = $val;
                        }
                    } 
                    // Jika ini atribut spesifik, masukkan ke JSON
                    else if (isset($dynamicKeys[$index])) {
                        $specifications[$dynamicKeys[$index]] = $val;
                    }
                }

                // Pastikan ID Aset dan Nama tidak kosong
                if (empty($assetData['asset_id'])) {
                    $assetData['asset_id'] = strtoupper($currentCategory->slug) . '-' . strtoupper(\Illuminate\Support\Str::random(6));
                }
                if (empty($assetData['name'])) {
                    $assetData['name'] = $assetData['asset_id'];
                }

                $assetData['specifications'] = $specifications;

                // SIMPAN KE DATABASE
                $asset = Asset::create($assetData);
                $totalCreated++;
            }
            fclose($handle);
        }

        if ($totalCreated > 0) {
            AssetHistory::create([
                'asset_id' => $asset->id ?? 0,
                'user_id' => auth()->id(),
                'action' => 'TAMBAH',
                'description' => "Impor massal dan Auto-Generate Form {$currentCategory->name} ({$totalCreated} data).",
            ]);
        }

        return redirect()->route('manage-asset.generic.index', $categorySlug)->with('success', "{$totalCreated} data {$currentCategory->name} berhasil diimpor & form otomatis disesuaikan.");
    }

    /*
    |--------------------------------------------------------------------------
    | 3. ASSET HISTORY & EXPORT
    |--------------------------------------------------------------------------
    */

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