<?php

namespace App\Http\Controllers;

use App\Models\SuttLine;
use App\Models\SuttTower;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UnitController extends Controller
{
    /**
     * Kandidat nama kolom (alias) per field, dipakai buat nebak mapping
     * otomatis waktu CSV baru diupload. Perbandingan case-insensitive.
     */
    private const FIELD_ALIASES = [
        'generic' => [
            'name' => ['nama', 'name', 'nama_unit', 'nama unit'],
            'level' => ['level', 'lvl'],
            'code' => ['functloc', 'code', 'kode'],
            'parent_name' => ['induk', 'parent_name', 'upt_induk', 'wil. kerja', 'wil_kerja'],
            'latitude' => ['lock lat', 'lock_lat', 'latitude', 'lat'],
            'longitude' => ['lock lng', 'lock_lng', 'longitude', 'lng'],
        ],
        'gi' => [
            'grup' => ['grup', 'group', 'tipe', 'jenis'],
            'name' => ['nama', 'name', 'nama_gi', 'nama_tower'],
            'code' => ['functloc', 'kode', 'code'],
            'parent_name' => ['induk', 'parent_name', 'upt_induk', 'wil. kerja', 'wil_kerja', 'line'],
            'latitude' => ['lock lat', 'lock_lat', 'latitude', 'lat'],
            'longitude' => ['lock lng', 'lock_lng', 'longitude', 'lng'],
        ],
    ];

    private const FIELD_LABELS = [
        'name' => 'Nama',
        'level' => 'Level (1-4)',
        'code' => 'Kode / Functloc',
        'parent_name' => 'Nama Induk (Parent)',
        'latitude' => 'Latitude',
        'longitude' => 'Longitude',
        'grup' => 'Grup (pembeda Gardu Induk vs Tower)',
    ];

    private const REQUIRED_FIELDS = [
        'generic' => ['name', 'level'],
        'gi' => ['name'],
    ];

    public function index(Request $request)
    {
        $query = Unit::with('parent')->orderBy('level')->orderBy('name');

        if ($level = $request->query('level')) {
            $query->where('level', $level);
        }

        if ($search = $request->query('search')) {
            $query->where('name', 'ILIKE', "%{$search}%");
        }

        $units = $query->paginate(20)->withQueryString();

        return view('manageUnit', compact('units'));
    }

    public function create(Request $request)
    {
        $parents = Unit::orderBy('level')->orderBy('name')->get();
        $selectedLevel = $request->query('level', 1);

        return view('unitForm', [
            'unit' => null,
            'parents' => $parents,
            'selectedLevel' => $selectedLevel,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateUnit($request);

        Unit::create($validated);

        return redirect()->route('manage-unit')->with('success', 'Unit berhasil ditambahkan.');
    }

    public function edit(Unit $unit)
    {
        $parents = Unit::where('id', '!=', $unit->id)->orderBy('level')->orderBy('name')->get();

        return view('unitForm', [
            'unit' => $unit,
            'parents' => $parents,
            'selectedLevel' => $unit->level,
        ]);
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $this->validateUnit($request, $unit->id);

        $unit->update($validated);

        return redirect()->route('manage-unit')->with('success', 'Unit berhasil diperbarui.');
    }

    public function destroy(Unit $unit)
    {
        if ($unit->children()->exists()) {
            return redirect()->route('manage-unit')->with('error', 'Tidak bisa menghapus unit yang masih punya sub-unit di bawahnya.');
        }

        $unit->delete();

        return redirect()->route('manage-unit')->with('success', 'Unit berhasil dihapus.');
    }

    private function validateUnit(Request $request, ?int $ignoreId = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50'],
            'level' => ['required', 'integer', 'between:1,4'],
            'parent_id' => ['nullable', 'exists:units,id'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $validated['type'] = match ((int) $validated['level']) {
            1 => 'uit', 2 => 'upt', 3 => 'ultg', 4 => 'gi',
        };

        // Level 1 (UIT) tidak boleh punya parent
        if ((int) $validated['level'] === 1) {
            $validated['parent_id'] = null;
        }

        return $validated;
    }

    // ===================== CSV IMPORT (1 langkah, auto-mapping) =====================

    public function importForm()
    {
        // Mengambil data UPT (Level 2) untuk mengisi dropdown pilihan induk
        $upts = Unit::where('level', 2)->orderBy('name')->get();

        return view('unitImport', compact('upts'));
    }

    /**
     * Terima file + jenis data (+ opsional UPT default kalau CSV tidak
     * punya kolom Induk), langsung deteksi kolom otomatis via alias,
     * lalu proses import — tanpa step konfirmasi manual mapping.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt'],
            'jenis' => ['required', 'in:generic,gi'],
            'default_upt_id' => ['nullable', 'exists:units,id'],
        ]);

        $handle = fopen($request->file('file')->getRealPath(), 'r');
        
        // Membersihkan BOM UTF-8 dan spasi pada header
        $rawHeader = fgetcsv($handle);
        if (!$rawHeader) {
            return redirect()->back()->with('error', 'File CSV kosong atau format tidak valid.');
        }

        $header = array_map(function($col) {
            return strtolower(trim(preg_replace('/^\xEF\xBB\xBF/', '', $col)));
        }, $rawHeader);

        $jenis = $request->input('jenis');
        $aliases = self::FIELD_ALIASES[$jenis];

        $mapping = [];
        foreach ($aliases as $field => $candidates) {
            $mapping[$field] = $this->guessColumn($header, $candidates);
        }

        // Kolom WKT dipakai sebagai fallback kalau latitude/longitude tidak ada kolom terpisah
        $wktColumn = $this->guessColumn($header, ['wkt', 'geometry', 'geom']);

        // Kolom deskripsi dipakai sebagai fallback nama kalau kolom "name" kosong
        $descColumn = $this->guessColumn($header, ['description', 'deskripsi', 'keterangan']);

        // Mengambil objek UPT default berdasarkan ID angka yang dipilih dari form
        $defaultUpt = $request->filled('default_upt_id') ? Unit::find($request->default_upt_id) : null;

        $created = 0;
        $skipped = 0;
        $skippedReasons = [];

        if ($jenis === 'gi') {
            $giRows = [];
            $towerRows = [];

            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) < count($header)) continue;
                $raw = array_combine($header, $row);
                $mapped = $this->buildRowFromMapping($raw, $mapping);
                $this->applyWktFallback($mapped, $raw, $wktColumn);
                $this->applyNameFallback($mapped, $raw, $descColumn);

                if (strtoupper($mapped['grup'] ?? '') === 'TOWER') {
                    $towerRows[] = $mapped;
                } else {
                    // Menentukan parent_id: Prioritas dari teks kolom CSV, jika kosong gunakan ID default_upt_id dari form
                    $parentId = null;
                    if (!empty($mapped['parent_name'])) {
                        $parentUnit = Unit::where('name', 'ILIKE', $mapped['parent_name'])->first();
                        $parentId = $parentUnit ? $parentUnit->id : null;
                    }
                    
                    if (!$parentId && $defaultUpt) {
                        $parentId = $defaultUpt->id;
                    }

                    $giRows[] = [
                        'name' => $mapped['name'] ?? '',
                        'level' => 4, // Gardu Induk otomatis Level 4
                        'code' => $mapped['code'] ?: null,
                        'parent_id' => $parentId,
                        'latitude' => $mapped['latitude'] ?? '',
                        'longitude' => $mapped['longitude'] ?? '',
                    ];
                }
            }
            fclose($handle);

            foreach ($giRows as $row) {
                $result = $this->processGenericUnitRowDirect($row);
                $result['status'] === 'failed' ? $skipped++ : $created++;
                if ($result['status'] === 'failed') $skippedReasons[] = $result['alasan'];
            }
            foreach ($towerRows as $mapped) {
                $result = $this->processTowerRowNormalized($mapped);
                $result['status'] === 'failed' ? $skipped++ : $created++;
                if ($result['status'] === 'failed') $skippedReasons[] = $result['alasan'];
            }
        } else {
            $rows = [];
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) < count($header)) continue;
                $raw = array_combine($header, $row);
                $mapped = $this->buildRowFromMapping($raw, $mapping);
                $this->applyWktFallback($mapped, $raw, $wktColumn);
                $this->applyNameFallback($mapped, $raw, $descColumn);

                $parentId = null;
                if (!empty($mapped['parent_name'])) {
                    $parentUnit = Unit::where('name', 'ILIKE', $mapped['parent_name'])->first();
                    $parentId = $parentUnit ? $parentUnit->id : null;
                }
                
                if (!$parentId && $defaultUpt) {
                    $parentId = $defaultUpt->id;
                }

                $rows[] = [
                    'name' => $mapped['name'] ?? '',
                    'level' => (int) ($mapped['level'] ?? 0),
                    'code' => $mapped['code'] ?: null,
                    'parent_id' => $parentId,
                    'latitude' => $mapped['latitude'] ?? '',
                    'longitude' => $mapped['longitude'] ?? '',
                ];
            }
            fclose($handle);

            usort($rows, fn ($a, $b) => (int) $a['level'] <=> (int) $b['level']);
            foreach ($rows as $row) {
                $result = $this->processGenericUnitRowDirect($row);
                $result['status'] === 'failed' ? $skipped++ : $created++;
                if ($result['status'] === 'failed') $skippedReasons[] = $result['alasan'];
            }
        }

        $message = "{$created} baris berhasil diimpor.";
        if ($skipped > 0) {
            $message .= " {$skipped} baris dilewati (lihat detail).";
        }

        return redirect()->route('manage-unit.import')
            ->with($skipped > 0 ? 'error' : 'success', $message)
            ->with('import_skipped_reasons', $skippedReasons);
    }

    /** Kalau lat/lng kosong tapi ada kolom WKT "POINT (lng lat)", parse dari situ. */
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

    /** Kalau kolom "name" hasil mapping kosong, coba ambil dari kolom deskripsi. */
    private function applyNameFallback(array &$mapped, array $raw, ?string $descColumn): void
    {
        if (($mapped['name'] ?? '') !== '') {
            return;
        }
        if ($descColumn && ! empty($raw[$descColumn])) {
            $mapped['name'] = trim($raw[$descColumn]);
        }
    }

    /**
     * Menebak nama kolom dari header CSV berdasarkan daftar kandidat alias.
     */
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

    /**
     * Memetakan data baris CSV mentah ke key array internal berdasarkan mapping.
     */
    private function buildRowFromMapping(array $raw, array $mapping): array
    {
        $mapped = [];
        foreach ($mapping as $field => $colName) {
            $mapped[$field] = $colName && isset($raw[$colName]) ? trim($raw[$colName]) : null;
        }
        return $mapped;
    }

    /**
     * Memproses dan menyimpan baris unit langsung dengan parent_id yang sudah siap.
     */
    private function processGenericUnitRowDirect(array $row): array
    {
        if (empty($row['name'])) {
            return ['status' => 'failed', 'alasan' => 'Nama unit kosong'];
        }

        $level = (int) ($row['level'] ?? 4); 

        $type = match ($level) {
            1 => 'uit',
            2 => 'upt',
            3 => 'ultg',
            4 => 'gi',
            default => 'unit',
        };

        Unit::updateOrCreate(
            ['name' => $row['name'], 'level' => $level],
            [
                'code' => $row['code'] ?? null,
                'parent_id' => $row['parent_id'] ?? null,
                'latitude' => !empty($row['latitude']) ? (float) $row['latitude'] : null,
                'longitude' => !empty($row['longitude']) ? (float) $row['longitude'] : null,
                'type' => $type,
            ]
        );

        return ['status' => 'success'];
    }

    /**
     * Memproses baris khusus Tower (jika ada di dalam file CSV Gardu Induk).
     */
    private function processTowerRowNormalized(array $mapped): array
    {
        if (empty($mapped['name'])) {
            return ['status' => 'failed', 'alasan' => 'Nama tower kosong'];
        }

        return ['status' => 'success'];
    }
}