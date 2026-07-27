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
            'name' => ['nama', 'name', 'nama_unit', 'nama unit', 'gi', 'gardu_induk'],
            'upt' => ['upt', 'unit pelaksana transmisi'],
            'ultg' => ['ultg', 'unit layanan transmisi dan gardu induk'],
            'level' => ['level', 'lvl'],
            'code' => ['functloc', 'code', 'kode'],
            'parent_name' => ['induk', 'parent_name', 'upt_induk', 'wil. kerja', 'wil_kerja'],
            'latitude' => ['lock lat', 'lock_lat', 'latitude', 'lat'],
            'longitude' => ['lock lng', 'lock_lng', 'longitude', 'lng'],
        ],
        'gi' => [
            'grup' => ['grup', 'group', 'tipe', 'jenis'],
            'name' => ['nama', 'name', 'nama_gi', 'nama_tower', 'gi'], // <-- Tambahkan 'gi' di sini
            'code' => ['functloc', 'kode', 'code'],
            'upt' => ['upt', 'unit pelaksana transmisi'], // <-- Tambahkan mapping kolom UPT
            'ultg' => ['ultg', 'unit layanan transmisi dan gardu induk'],
            'parent_name' => ['induk', 'parent_name', 'upt_induk', 'wil. kerja', 'wil_kerja', 'line', 'ultg', 'upt'], // <-- Ditambahkan juga ultg/upt sebagai parent
            'latitude' => ['lock lat', 'lock_lat', 'latitude', 'lat'], // <-- Menangkap kolom 'lat'
            'longitude' => ['lock lng', 'lock_lng', 'longitude', 'lng'], // <-- Menangkap kolom 'lng'
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
        $upts = \App\Models\Unit::where('level', 2)->orderBy('name')->get();

        // Ubah dari 'unit.import' menjadi 'manage-unit.import'
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
            'files' => ['required', 'array'],
            'files.*' => ['file', 'mimes:csv,txt'],
            'jenis' => ['required', 'in:generic,gi'],
            'default_upt_id' => ['nullable', 'array'],
            'default_upt_id.*' => ['nullable', 'exists:units,id'], // Tambahkan nullable di sini
        ]);

        $jenis = $request->input('jenis');
        $aliases = self::FIELD_ALIASES[$jenis];
        
        // Ambil array dari pilihan default UPT
        $defaultUptIds = $request->input('default_upt_id', []);

        $totalCreated = 0;
        $totalSkipped = 0;
        $allSkippedReasons = [];

        // Looping setiap file yang di-upload
        foreach ($request->file('files') as $index => $uploadedFile) {
            $path = $uploadedFile->getRealPath();
            
            // Tentukan UPT default untuk file ke-index ini (jika ada pilihannya)
            $currentDefaultUptId = $defaultUptIds[$index] ?? ($defaultUptIds[0] ?? null);
            $defaultUpt = $currentDefaultUptId ? Unit::find($currentDefaultUptId) : null;

            $fileLines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if (empty($fileLines)) {
                continue;
            }

            $rawHeaderData = str_getcsv(array_shift($fileLines));
            $header = array_map(function($col) {
                return strtolower(trim(preg_replace('/^\xEF\xBB\xBF/', '', $col)));
            }, $rawHeaderData);

            $mapping = [];
            foreach ($aliases as $field => $candidates) {
                $mapping[$field] = $this->guessColumn($header, $candidates);
            }

            $wktColumn = $this->guessColumn($header, ['wkt', 'geometry', 'geom']);
            $descColumn = $this->guessColumn($header, ['description', 'deskripsi', 'keterangan']);

            $created = 0;
            $skipped = 0;
            $skippedReasons = [];

            if ($jenis === 'gi') {
                $rowsToProcess = [];

                foreach ($fileLines as $line) {
                    $row = str_getcsv($line);
                    if (count($row) < count($header)) continue;
                    
                    $row = array_pad($row, count($header), null);
                    $raw = array_combine($header, $row);
                    
                    $mapped = $this->buildRowFromMapping($raw, $mapping);
                    $this->applyWktFallback($mapped, $raw, $wktColumn);
                    $this->applyNameFallback($mapped, $raw, $descColumn);

                    $uptName = null;
                    foreach (['upt', 'unit pelaksana transmisi', 'induk', 'upt_induk', 'wil. kerja'] as $key) {
                        if (!empty($raw[$key] ?? null)) {
                            $uptName = trim($raw[$key]);
                            break;
                        }
                    }

                    $ultgName = null;
                    foreach (['ultg', 'unit layanan transmisi dan gardu induk'] as $key) {
                        if (!empty($raw[$key] ?? null)) {
                            $ultgName = trim($raw[$key]);
                            break;
                        }
                    }

                    $rowsToProcess[] = [
                        'upt_name' => $uptName,
                        'ultg_name' => $ultgName,
                        'gi_name' => $mapped['name'] ?? null,
                        'code' => $mapped['code'] ?: null,
                        'grup' => $mapped['grup'] ?? null,
                        'latitude' => isset($mapped['latitude']) ? str_replace(',', '.', $mapped['latitude']) : null,
                        'longitude' => isset($mapped['longitude']) ? str_replace(',', '.', $mapped['longitude']) : null,
                    ];
                }

                foreach ($rowsToProcess as $data) {
                    $uptId = null;
                    if (!empty($data['upt_name'])) {
                        $uptUnit = Unit::firstOrCreate(
                            ['name' => $data['upt_name'], 'level' => 2],
                            ['type' => 'upt']
                        );
                        $uptId = $uptUnit->id;
                    } else if ($defaultUpt) {
                        $uptId = $defaultUpt->id;
                    }

                    $ultgId = null;
                    if (!empty($data['ultg_name'])) {
                        $ultgUnit = Unit::firstOrCreate(
                            ['name' => $data['ultg_name'], 'level' => 3],
                            [
                                'parent_id' => $uptId,
                                'type' => 'ultg'
                            ]
                        );
                        if (!$ultgUnit->parent_id && $uptId) {
                            $ultgUnit->update(['parent_id' => $uptId]);
                        }
                        $ultgId = $ultgUnit->id;
                    }

                    $finalParentId = $ultgId ?? $uptId;

                    if (empty($data['gi_name'])) {
                        $skipped++;
                        $skippedReasons[] = 'Nama unit/GI kosong pada salah satu baris.';
                        continue;
                    }

                    if (strtoupper($data['grup'] ?? '') === 'TOWER') {
                        $created++;
                    } else {
                        Unit::updateOrCreate(
                            ['name' => $data['gi_name'], 'level' => 4],
                            [
                                'code' => $data['code'],
                                'parent_id' => $finalParentId,
                                'latitude' => !empty($data['latitude']) ? (float) $data['latitude'] : null,
                                'longitude' => !empty($data['longitude']) ? (float) $data['longitude'] : null,
                                'type' => 'gi',
                            ]
                        );
                        $created++;
                    }
                }
            } else {
                $rows = [];
                foreach ($fileLines as $line) {
                    $row = str_getcsv($line);
                    if (count($row) < count($header)) continue;
                    
                    $row = array_pad($row, count($header), null);
                    $raw = array_combine($header, $row);
                    
                    $mapped = $this->buildRowFromMapping($raw, $mapping);
                    $this->applyWktFallback($mapped, $raw, $wktColumn);
                    $this->applyNameFallback($mapped, $raw, $descColumn);

                    $parentId = null;
                    if (!empty($mapped['parent_name'])) {
                        $parentUnit = Unit::where('name', 'ILIKE', $mapped['parent_name'])->first();
                        $parentId = $parentUnit ? $parentUnit->id : null;
                    } elseif ($defaultUpt) {
                        $parentId = $defaultUpt->id;
                    }

                    $rows[] = [
                        'name' => $mapped['name'] ?? '',
                        'level' => (int) ($mapped['level'] ?? 0),
                        'code' => $mapped['code'] ?: null,
                        'parent_id' => $parentId,
                        'latitude' => isset($mapped['latitude']) ? str_replace(',', '.', $mapped['latitude']) : null,
                        'longitude' => isset($mapped['longitude']) ? str_replace(',', '.', $mapped['longitude']) : null,
                    ];
                }

                usort($rows, fn ($a, $b) => (int) $a['level'] <=> (int) $b['level']);
                foreach ($rows as $row) {
                    $result = $this->processGenericUnitRowDirect($row);
                    $result['status'] === 'failed' ? $skipped++ : $created++;
                    if ($result['status'] === 'failed') $skippedReasons[] = $result['alasan'];
                }
            }

            $totalCreated += $created;
            $totalSkipped += $skipped;
            $allSkippedReasons = array_merge($allSkippedReasons, $skippedReasons);
        }

        $message = "{$totalCreated} baris berhasil diimpor dari semua file.";
        if ($totalSkipped > 0) {
            $message .= " {$totalSkipped} baris dilewati.";
        }

        return redirect()->route('manage-unit.import')
            ->with($totalSkipped > 0 && $totalCreated === 0 ? 'error' : 'success', $message)
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