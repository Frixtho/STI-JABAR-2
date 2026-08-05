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
    /**
     * Kandidat nama kolom (alias) per field, dipakai buat nebak mapping
     * otomatis waktu CSV baru diupload. Perbandingan case-insensitive.
     */
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

    /** Pilihan tegangan yang valid buat dropdown filter & deteksi otomatis dari nama. */
    private const TEGANGAN_OPTIONS = ['30', '70', '150', '500'];

    public function index(Request $request)
    {
        // Balik ke per-jalur (1 baris = 1 jalur SUTT), bukan per-tower.
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

            // GI Awal / Akhir (kalau kolomnya ada isinya & unit-nya ketemu)
            $line->gi_awal_name = isset($line->gi_awal_id) ? optional(Unit::find($line->gi_awal_id))->name : null;
            $line->gi_akhir_name = isset($line->gi_akhir_id) ? optional(Unit::find($line->gi_akhir_id))->name : null;
        }

        return view('manageAsset', [
            'assets' => $assets,
            'teganganOptions' => self::TEGANGAN_OPTIONS,
            'selectedTegangan' => $tegangan,
        ]);
    }

    /**
     * Menampilkan daftar semua tower secara independen untuk menu Manage Tower.
     */
    public function indexTower(Request $request)
    {
        $query = SuttTower::with('suttLine')->orderBy('name');

        if ($search = $request->query('search')) {
            $query->where('name', 'ILIKE', "%{$search}%")
                  ->orWhere('functloc', 'ILIKE', "%{$search}%");
        }

        $towers = $query->paginate(20)->withQueryString();

        return view('manageTower', [
            'towers' => $towers,
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

        // Catat ke Asset History
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

        // Catat ke Asset History
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
        
        $asset->delete();

        // Catat ke Asset History
        AssetHistory::create([
            'asset_id' => $assetId,
            'user_id' => auth()->id(),
            'action' => 'HAPUS',
            'description' => 'Menghapus aset/jalur SUTT: ' . $assetName,
        ]);

        return redirect()->route('manage-asset')->with('success', 'Aset berhasil dihapus.');
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

    // ===================== CSV IMPORT =====================

    public function importForm()
    {
        $garduInduks = Unit::where('level', 4)->orderBy('name')->get();

        return view('assetImport', compact('garduInduks'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'files'   => ['required', 'array'],
            'files.*' => ['required', 'file', 'mimes:csv,txt'],
            'default_category' => ['nullable', 'string', 'max:50'],
        ]);

        $defaultCategory = $request->input('default_category', 'sutt'); 
        $uploadedFiles = $request->file('files');

        $defaultUpt = Unit::where('level', 2)->first();
        $defaultUptId = $defaultUpt ? $defaultUpt->id : 1;

        $totalCreated = 0;
        $totalSkipped = 0;
        $allSkippedReasons = [];
        $processedAssetIds = [];

        foreach ($uploadedFiles as $file) {
            $handle = fopen($file->getRealPath(), 'r');
            
            // Deteksi delimiter otomatis (koma atau titik koma)
            $sampleLine = fgets($handle);
            rewind($handle);
            $delimiter = (substr_count($sampleLine, ';') > substr_count($sampleLine, ',')) ? ';' : ',';

            $rawHeader = fgetcsv($handle, 0, $delimiter);
            if (!$rawHeader) {
                fclose($handle);
                continue;
            }

            $header = array_map(function($col) {
                return strtolower(trim(preg_replace('/^\xEF\xBB\xBF/', '', $col)));
            }, $rawHeader);

            $aliases = self::FIELD_ALIASES['generic'];
            $mapping = [];
            foreach ($aliases as $field => $candidates) {
                $mapping[$field] = $this->guessColumn($header, $candidates);
            }

            $wktColumn = $this->guessColumn($header, ['wkt', 'geometry', 'geom']);
            $descColumn = $this->guessColumn($header, ['description', 'deskripsi', 'keterangan', 'nama']);

            $namaJalurDefault = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                if ($row === [null] || empty(array_filter($row))) {
                    continue;
                }

                if (count($row) < count($header)) {
                    $row = array_pad($row, count($header), null);
                } elseif (count($row) > count($header)) {
                    $row = array_slice($row, 0, count($header));
                }
                
                $raw = array_combine($header, $row);
                $mapped = $this->buildRowFromMapping($raw, $mapping);
                $this->applyWktFallback($mapped, $raw, $wktColumn);
                $this->applyNameFallback($mapped, $raw, $descColumn);

                if (empty($mapped['name']) && empty($mapped['code'])) {
                    continue;
                }

                $rowData = [
                    'name'       => $mapped['name'] ?? $mapped['code'] ?? 'Unnamed Tower',
                    'category'   => $defaultCategory,
                    'functloc'   => $mapped['code'] ?? ('TOWER-' . uniqid()),
                    'code'       => $mapped['code'] ?? null,
                    'upt_id'     => $defaultUptId,
                    'gi_awal_id' => null,
                    'gi_akhir_id'=> null,
                    'latitude'   => !empty($mapped['latitude']) ? $mapped['latitude'] : null,
                    'longitude'  => !empty($mapped['longitude']) ? $mapped['longitude'] : null,
                ];

                $result = $this->processAssetRowDirect($rowData, $namaJalurDefault);
                
                if ($result['status'] === 'failed') {
                    $totalSkipped++;
                    $allSkippedReasons[] = $result['alasan'];
                } else {
                    $totalCreated++;
                    if (isset($result['asset_id'])) {
                        $processedAssetIds[] = $result['asset_id'];
                    }
                }
            }
            fclose($handle);
        }

        // --- HITUNG OTOMATIS PANJANG KM & JUMLAH TOWER ---
        foreach (array_unique($processedAssetIds) as $assetId) {
            $this->recalcLineStats($assetId);
            
            // Catat history import/tambah lewat CSV
            AssetHistory::create([
                'asset_id' => $assetId,
                'user_id' => auth()->id(),
                'action' => 'TAMBAH',
                'description' => 'Melakukan impor data titik tower via CSV.',
            ]);
        }

        $message = "{$totalCreated} baris titik tower berhasil diimpor & dihitung panjang jalurnya.";
        if ($totalSkipped > 0) {
            $message .= " {$totalSkipped} baris dilewati.";
        }

        return redirect()->route('manage-asset.import')
            ->with($totalSkipped > 0 ? 'error' : 'success', $message)
            ->with('import_skipped_reasons', $allSkippedReasons);
    }

    private function applyWktFallback(array &$mapped, array $raw, ?string $wktColumn): void
    {
        if (($mapped['latitude'] ?? '') !== '' && ($mapped['longitude'] ?? '') !== '') {
            return;
        }
        if (! $wktColumn || empty($raw[$wktColumn])) {
            return;
        }
        if (preg_match('/POINT\s*\(\s*([-\d.]+)\s+([-\d.]+)\s*\)/i', $raw[$wktColumn], $m)) {
            $mapped['longitude'] = $mapped['longitude'] ?: $m[1];
            $mapped['latitude'] = $mapped['latitude'] ?: $m[2];
        }
    }

    private function applyNameFallback(array &$mapped, array $raw, ?string $descColumn): void
    {
        if (($mapped['name'] ?? '') !== '') {
            return;
        }
        if ($descColumn && ! empty($raw[$descColumn])) {
            $mapped['name'] = trim($raw[$descColumn]);
        }
    }

    private function safeCombineRow(array $header, array $row): ?array
    {
        if (count($row) === 1 && $row[0] === null) {
            return null;
        }

        $headerCount = count($header);
        $rowCount = count($row);

        if ($rowCount > $headerCount) {
            $row = array_slice($row, 0, $headerCount);
        } elseif ($rowCount < $headerCount) {
            $row = array_pad($row, $headerCount, '');
        }

        return array_combine($header, $row);
    }

    private function guessColumn(array $header, array $candidates): ?string
    {
        foreach ($header as $col) {
            $cleanCol = strtolower(trim($col));
            foreach ($candidates as $candidate) {
                if ($cleanCol === strtolower(trim($candidate))) {
                    return $col;
                }
            }
        }
        return null;
    }

    private function buildRowFromMapping(array $raw, array $mapping): array
    {
        $mapped = [];
        foreach ($mapping as $field => $colName) {
            $mapped[$field] = $colName && isset($raw[$colName]) ? trim($raw[$colName]) : null;
        }
        return $mapped;
    }

    private function processAssetRowDirect(array $row, string $namaJalurDefault): array
    {
        if (empty($row['name']) && empty($row['functloc'])) {
            return ['status' => 'failed', 'alasan' => 'Nama atau Functloc aset kosong'];
        }

        try {
            $line = DB::table('sutt_lines')->where('name', $namaJalurDefault)->first();

            if (!$line) {
                $insertData = ['name' => $namaJalurDefault];
                try {
                    $lineId = DB::table('sutt_lines')->insertGetId(array_merge($insertData, [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]));
                } catch (\Exception $ex) {
                    $lineId = DB::table('sutt_lines')->insertGetId($insertData);
                }
            } else {
                $lineId = $line->id;
            }

            $rawText = trim($row['name'] ?? $row['code'] ?? '');
            $towerNumber = 1;
            if (preg_match_all('/\d+/', $rawText, $matches)) {
                $allNumbers = $matches[0];
                $towerNumber = (int) end($allNumbers);
            }

            SuttTower::updateOrCreate(
                [
                    'sutt_line_id' => $lineId,
                    'tower_number' => $towerNumber,
                ], 
                [
                    'functloc' => $row['functloc'] ?? ('TOWER-' . $lineId . '-' . $towerNumber),
                    'name' => $row['name'] ?? 'Tower ' . $towerNumber,
                    'latitude' => !empty($row['latitude']) ? (float) $row['latitude'] : null,
                    'longitude' => !empty($row['longitude']) ? (float) $row['longitude'] : null,
                ]
            );

            return ['status' => 'success', 'asset_id' => $lineId];
        } catch (\Exception $e) {
            return ['status' => 'failed', 'alasan' => $e->getMessage()];
        }
    }

    public function show($id)
    {
        $line = DB::table('sutt_lines')->where('id', $id)->first();

        if (!$line) {
            return redirect()->back()->with('error', 'Jalur SUTT tidak ditemukan.');
        }

        // Ambil SEMUA tower (tanpa pagination) khusus buat hitung jumlah & panjang jalur
        $allTowersOrdered = SuttTower::where('sutt_line_id', $id)->orderBy('tower_number')->get();

        $pathLengthKm = 0;
        for ($i = 0; $i < $allTowersOrdered->count() - 1; $i++) {
            $t1 = $allTowersOrdered[$i];
            $t2 = $allTowersOrdered[$i + 1];

            if ($t1->latitude && $t1->longitude && $t2->latitude && $t2->longitude) {
                $pathLengthKm += $this->calculateHaversineDistance(
                    $t1->latitude, $t1->longitude,
                    $t2->latitude, $t2->longitude
                );
            }
        }

        // Yang ditampilkan di tabel: di-paginate biar gak numpuk ratusan baris
        $towers = SuttTower::where('sutt_line_id', $id)
            ->orderBy('tower_number')
            ->paginate(50)
            ->withQueryString();

        // Deteksi tegangan dinamis untuk halaman detail
        $teganganDinamis = '150 kV';
        if (preg_match('/(30|70|150|500)\s*kv/i', $line->name, $matchTegangan)) {
            $teganganDinamis = $matchTegangan[1] . ' kV';
        }

        $line = (object) (array) $line;
        $line->tegangan = $teganganDinamis;

        return view('assetShow', [
            'line' => $line,
            'towers' => $towers,
            'totalTowers' => $allTowersOrdered->count(),
            'pathLengthKm' => $pathLengthKm,
        ]);
    }

    // ===================== CRUD TOWER (dari halaman detail jalur) =====================

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

        // Catat ke Asset History untuk perubahan tower
        AssetHistory::create([
            'asset_id' => $tower->sutt_line_id,
            'user_id' => auth()->id(),
            'action' => 'UBAH',
            'description' => 'Memperbarui data tower: ' . $tower->name,
        ]);

        return redirect()->route('manage-asset.show', $tower->sutt_line_id)
            ->with('success', 'Data tower berhasil diperbarui.');
    }

    public function destroyTower($towerId)
    {
        $tower = SuttTower::findOrFail($towerId);
        $lineId = $tower->sutt_line_id;
        $towerName = $tower->name;

        $tower->delete();

        $this->recalcLineStats($lineId);

        // Catat ke Asset History untuk penghapusan tower
        AssetHistory::create([
            'asset_id' => $lineId,
            'user_id' => auth()->id(),
            'action' => 'HAPUS',
            'description' => 'Menghapus tower: ' . $towerName,
        ]);

        return redirect()->route('manage-asset.show', $lineId)
            ->with('success', 'Tower berhasil dihapus.');
    }

    /** Hitung ulang panjang_km & jumlah_tower punya sebuah jalur, simpan kalau kolomnya ada. */
    private function recalcLineStats(int $lineId): void
    {
        $panjang = $this->hitungPanjangJalurSUTT($lineId);
        $jumlahTower = SuttTower::where('sutt_line_id', $lineId)->count();

        try {
            DB::table('sutt_lines')->where('id', $lineId)->update([
                'panjang_km' => $panjang,
                'jumlah_tower' => $jumlahTower,
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Abaikan jika kolom tidak ada di tabel sutt_lines
        }
    }

    private function calculateHaversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; 

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
    
    public function hitungPanjangJalurSUTT($assetId)
    {
        $towers = SuttTower::where('sutt_line_id', $assetId)
                    ->orderBy('name', 'asc')
                    ->get();

        $totalKm = 0;

        for ($i = 0; $i < count($towers) - 1; $i++) {
            $lat1 = $towers[$i]->latitude;
            $lon1 = $towers[$i]->longitude;
            
            $lat2 = $towers[$i+1]->latitude;
            $lon2 = $towers[$i+1]->longitude;

            $totalKm += $this->haversineGreatCircleDistance($lat1, $lon1, $lat2, $lon2);
        }

        return round($totalKm, 2); 
    }

    public function indexByCategory($category, Request $request)
    {
        $currentCategory = AssetCategory::where('slug', $category)->firstOrFail();

        $assets = Asset::where('category', $currentCategory->name)
            ->when($request->search, function($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString();

        // Tentukan nama view berdasarkan slug dari database
        // Contoh: slug 'tower' -> view 'manageAsset'
        // Contoh: slug 'access-point' -> view 'manageAccessPoint'
        $viewName = match($currentCategory->slug) {
            'tower' => 'manageAsset',          // file manageAsset.blade.php milik tower
            'access-point' => 'manageAccessPoint', // file manageAccessPoint.blade.php milik AP
            default => 'manageAsset',          // fallback default jika ada kategori lain
        };

        return view($viewName, compact('currentCategory', 'assets'));
    }

    private function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371)
    {
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        
        return $angle * $earthRadius;
    }

    /**
     * Menampilkan halaman riwayat perubahan (Audit Trail)
     */
    public function history()
    {
        $histories = \App\Models\AssetHistory::with('user')->latest()->paginate(10); 

        return view('assetHistory', compact('histories'));
    }
}