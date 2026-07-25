<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

    public function index(Request $request)
    {
        $query = Asset::with(['giAwal', 'giAkhir'])->orderBy('name');

        if ($category = $request->query('category')) {
            $query->where('category', $category);
        }

        if ($search = $request->query('search')) {
            $query->where('name', 'ILIKE', "%{$search}%");
        }

        $assets = $query->paginate(20)->withQueryString();

        return view('manageAsset', compact('assets'));
    }

    public function create(Request $request)
    {
        $upts = Unit::where('level', 2)->orderBy('name')->get();
        
        // Mengambil data Gardu Induk langsung dari tabel units (Level 4 / type 'gi')
        $garduInduks = Unit::where('level', 4)->orderBy('name')->get(); 
        $asset = null;

        return view('assetForm', compact('upts', 'garduInduks', 'asset'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateAsset($request);

        Asset::create($validated);

        return redirect()->route('manage-asset')->with('success', 'Aset berhasil ditambahkan.');
    }

    public function edit(Asset $asset)
    {
        $upts = Unit::where('level', 2)->orderBy('name')->get();
        
        // Mengambil data Gardu Induk dari tabel units
        $garduInduks = Unit::where('level', 4)->orderBy('name')->get();

        return view('assetForm', compact('upts', 'garduInduks', 'asset'));
    }

    public function update(Request $request, Asset $asset)
    {
        $validated = $this->validateAsset($request, $asset->id);

        $asset->update($validated);

        return redirect()->route('manage-asset')->with('success', 'Aset berhasil diperbarui.');
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();

        return redirect()->route('manage-asset')->with('success', 'Aset berhasil dihapus.');
    }

    private function validateAsset(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:50'],
            'code' => ['nullable', 'string', 'max:50'],
            // Validasi relasi ID mengarah ke tabel units, bukan assets lagi
            'gi_awal_id' => ['nullable', 'exists:units,id'],
            'gi_akhir_id' => ['nullable', 'exists:units,id'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);
    }

    // ===================== CSV IMPORT (1 langkah, auto-mapping) =====================

public function importForm()
    {
        // Sumber dropdown GI untuk import juga diarahkan ke Unit
        $garduInduks = Unit::where('level', 4)->orderBy('name')->get();

        return view('assetImport', compact('garduInduks'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt'],
            'default_category' => ['nullable', 'string', 'max:50'],
        ]);

        $handle = fopen($request->file('file')->getRealPath(), 'r');
        
        $rawHeader = fgetcsv($handle);
        if (!$rawHeader) {
            return redirect()->back()->with('error', 'File CSV kosong atau format tidak valid.');
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
        $descColumn = $this->guessColumn($header, ['description', 'deskripsi', 'keterangan']);
        $defaultCategory = $request->input('default_category', 'umum');

        $created = 0;
        $skipped = 0;
        $skippedReasons = [];

        $rows = [];
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < count($header)) continue;
            $raw = array_combine($header, $row);
            $mapped = $this->buildRowFromMapping($raw, $mapping);
            $this->applyWktFallback($mapped, $raw, $wktColumn);
            $this->applyNameFallback($mapped, $raw, $descColumn);

            // Resolusi relasi GI Awal & GI Akhir berdasarkan nama jika ada di CSV
            $giAwalId = null;
            if (!empty($mapped['gi_awal'])) {
                $giAwal = Asset::where('category', 'gi')->where('name', 'ILIKE', $mapped['gi_awal'])->first();
                $giAwalId = $giAwal ? $giAwal->id : null;
            }

            $giAkhirId = null;
            if (!empty($mapped['gi_akhir'])) {
                $giAkhir = Asset::where('category', 'gi')->where('name', 'ILIKE', $mapped['gi_akhir'])->first();
                $giAkhirId = $giAkhir ? $giAkhir->id : null;
            }

            $rows[] = [
                'name' => $mapped['name'] ?? '',
                'category' => $mapped['category'] ?: $defaultCategory,
                'code' => $mapped['code'] ?: null,
                'gi_awal_id' => $giAwalId,
                'gi_akhir_id' => $giAkhirId,
                'latitude' => $mapped['latitude'] ?? '',
                'longitude' => $mapped['longitude'] ?? '',
            ];
        }
        fclose($handle);

        foreach ($rows as $row) {
            $result = $this->processAssetRowDirect($row);
            $result['status'] === 'failed' ? $skipped++ : $created++;
            if ($result['status'] === 'failed') $skippedReasons[] = $result['alasan'];
        }

        $message = "{$created} baris aset berhasil diimpor.";
        if ($skipped > 0) {
            $message .= " {$skipped} baris dilewati.";
        }

        return redirect()->route('manage-asset.import')
            ->with($skipped > 0 ? 'error' : 'success', $message)
            ->with('import_skipped_reasons', $skippedReasons);
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

    private function processAssetRowDirect(array $row): array
    {
        if (empty($row['name'])) {
            return ['status' => 'failed', 'alasan' => 'Nama aset kosong'];
        }

        Asset::updateOrCreate(
            ['name' => $row['name'], 'category' => $row['category']],
            [
                'functloc' => $row['code'] ?? null,
                'gi_awal_id' => $row['gi_awal_id'] ?? null,
                'gi_akhir_id' => $row['gi_akhir_id'] ?? null,
                'latitude' => !empty($row['latitude']) ? (float) $row['latitude'] : null,
                'longitude' => !empty($row['longitude']) ? (float) $row['longitude'] : null,
            ]
        );

        return ['status' => 'success'];
    }
}