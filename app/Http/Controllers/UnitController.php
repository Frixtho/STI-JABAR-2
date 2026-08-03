<?php

namespace App\Http\Controllers;

use App\Models\SuttLine;
use App\Models\SuttTower;
use App\Models\AssetHistory;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $units = Unit::with(['parent.parent.parent'])
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($request->level, function ($query, $level) {
                $query->where('level', $level);
            })
            ->paginate(20);

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

        $unit = Unit::create($validated);

        // Catat riwayat penambahan unit
        AssetHistory::create([
            'asset_id'    => null,
            'user_id'     => Auth::id(),
            'action'      => 'TAMBAH',
            'description' => 'Menambahkan unit baru: ' . $unit->name,
        ]);

        return redirect()->route('manage-unit')->with('success', 'Unit berhasil ditambahkan.');
    }

    public function edit(Unit $unit)
    {
        // Ambil data unit lain untuk dijadikan opsi parent berdasarkan level yang diperbolehkan
        // Level 4 butuh parent Level 3, Level 3 butuh parent Level 2, Level 2 butuh parent Level 1
        $parentUnits = Unit::where('level', '<', $unit->level)->get();

        return view('unitForm', compact('unit', 'parentUnits'));
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|integer|in:1,2,3,4',
            'parent_id' => 'nullable|exists:units,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $unit->update([
            'name' => $request->name,
            'level' => $request->level,
            // Jika level 1, parent_id otomatis null
            'parent_id' => $request->level == 1 ? null : $request->parent_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('manage-unit')->with('success', 'Unit berhasil diperbarui.');
    }

    public function destroy(Unit $unit)
    {
        if ($unit->children()->exists()) {
            return redirect()->route('manage-unit')->with('error', 'Tidak bisa menghapus unit yang masih punya sub-unit di bawahnya.');
        }

        $unitName = $unit->name;
        $unit->delete();

        // Catat riwayat penghapusan unit
        AssetHistory::create([
            'asset_id'    => null,
            'user_id'     => Auth::id(),
            'action'      => 'HAPUS',
            'description' => 'Menghapus unit: ' . $unitName,
        ]);

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
            'default_upt_id.*' => ['nullable', 'exists:units,id'],
        ]);

        $jenis = $request->input('jenis');
        $defaultUptIds = $request->input('default_upt_id', []);

        $totalCreated = 0;
        $totalSkipped = 0;
        $allSkippedReasons = [];

        foreach ($request->file('files') as $index => $uploadedFile) {
            $path = $uploadedFile->getRealPath();
            
            $currentDefaultUptId = $defaultUptIds[$index] ?? ($defaultUptIds[0] ?? null);
            $defaultUpt = $currentDefaultUptId ? Unit::find($currentDefaultUptId) : null;

            $fileLines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if (empty($fileLines)) {
                continue;
            }

            // Cari baris header secara dinamis
            $headerLineIndex = 0;
            $header = [];
            
            foreach ($fileLines as $i => $line) {
                $cols = str_getcsv($line);
                $cleanedCols = array_map(function($col) {
                    return strtolower(trim(preg_replace('/^\xEF\xBB\xBF/', '', $col)));
                }, $cols);

                if (in_array('lv 1', $cleanedCols) || in_array('lv1', $cleanedCols) || in_array('lv 2', $cleanedCols) || in_array('lat', $cleanedCols) || in_array('lng', $cleanedCols)) {
                    $headerLineIndex = $i;
                    $header = $cleanedCols;
                    break;
                }
            }

            if (empty($header)) {
                $rawHeaderData = str_getcsv($fileLines[0]);
                $header = array_map(function($col) {
                    return strtolower(trim(preg_replace('/^\xEF\xBB\xBF/', '', $col)));
                }, $rawHeaderData);
                $headerLineIndex = 0;
            }

            $created = 0;
            $skipped = 0;
            $skippedReasons = [];

            $dataLines = array_slice($fileLines, $headerLineIndex + 1);

            foreach ($dataLines as $line) {
                $row = str_getcsv($line);
                if (empty(array_filter($row))) continue;
                
                $row = array_pad($row, count($header), null);
                $raw = array_combine($header, $row);

                // Tangkap nilai dari masing-masing kolom level secara fleksibel
                $valLv1 = trim($raw['lv 1'] ?? $raw['lv1'] ?? '');
                $valLv2 = trim($raw['lv 2'] ?? $raw['lv2'] ?? '');
                $valLv3 = trim($raw['lv 3'] ?? $raw['lv3'] ?? '');
                $valLv4 = trim($raw['lv 4'] ?? $raw['lv4'] ?? $raw['lv 5'] ?? '');

                $unitName = null;
                $level = null;
                $parentId = null;

                // Tentukan baris ini masuk level berapa berdasarkan kolom mana yang terisi
                if (!empty($valLv1) && $valLv1 !== '-') {

                        $uit = Unit::updateOrCreate(
                            ['name' => trim($valLv1), 'level' => 1],
                            ['type' => 'uit']
                        );

                        $currentLv1Id = $uit->id;
                        $currentLv2Id = null;
                        $currentLv3Id = null;
                    }

                    if (!empty($valLv2) && $valLv2 !== '-') {

                        $upt = Unit::updateOrCreate(
                            ['name' => trim($valLv2), 'level' => 2],
                            [
                                'parent_id' => $currentLv1Id,
                                'type' => 'upt',
                            ]
                        );

                        $currentLv2Id = $upt->id;
                        $currentLv3Id = null;
                    }

                    if (!empty($valLv3) && $valLv3 !== '-') {

                        $ultg = Unit::updateOrCreate(
                            ['name' => trim($valLv3), 'level' => 3],
                            [
                                'parent_id' => $currentLv2Id,
                                'type' => 'ultg',
                            ]
                        );

                        $currentLv3Id = $ultg->id;
                    }

                    $unitName = null;
                    $level = null;
                    $parentId = null;

                    if (!empty($valLv4) && $valLv4 !== '-') {

                        $unitName = trim($valLv4);
                        $level = 4;
                        $parentId = $currentLv3Id;

                    } elseif (!empty($valLv3) && $valLv3 !== '-') {

                        $unitName = trim($valLv3);
                        $level = 3;
                        $parentId = $currentLv2Id;

                    } elseif (!empty($valLv2) && $valLv2 !== '-') {

                        $unitName = trim($valLv2);
                        $level = 2;
                        $parentId = $currentLv1Id;

                    } elseif (!empty($valLv1) && $valLv1 !== '-') {

                        $unitName = trim($valLv1);
                        $level = 1;
                        $parentId = null;
                    }

                if (empty($unitName) || $unitName === '-') {
                    $skipped++;
                    $skippedReasons[] = 'Nama unit kosong pada salah satu baris.';
                    continue;
                }

                // Tangkap koordinat
                $lng = null;
                foreach (['lng', 'longitude', 'long'] as $key) {
                    if (!empty($raw[$key] ?? null)) {
                        $lng = str_replace(',', '.', trim($raw[$key]));
                        break;
                    }
                }

                $lat = null;
                foreach (['lat', 'latitude'] as $key) {
                    if (!empty($raw[$key] ?? null)) {
                        $lat = str_replace(',', '.', trim($raw[$key]));
                        break;
                    }
                }

                // Tangkap kode
                $code = null;
                foreach (['code', 'kode', 'functloc'] as $key) {
                    if (!empty($raw[$key] ?? null)) {
                        $code = trim($raw[$key]);
                        break;
                    }
                }

                $type = match ($level) {
                    1 => 'uit',
                    2 => 'upt',
                    3 => 'ultg',
                    4 => 'gi',
                    default => 'unit',
                };

                // Simpan atau update unit ke database
                $unit = Unit::updateOrCreate(
                    [
                        'name' => $unitName,
                        'level' => $level,
                    ],
                    [
                        'code' => $code,
                        'parent_id' => $parentId,
                        'latitude' => !empty($lat) ? (float) $lat : null,
                        'longitude' => !empty($lng) ? (float) $lng : null,
                        'type' => $type,
                    ]
                );

                // Perbarui cache ID hierarki berjalan untuk baris-baris anak di bawahnya
                if ($level === 1) {
                    $currentLv1Id = $unit->id;
                    $currentLv2Id = null;
                    $currentLv3Id = null;
                } elseif ($level === 2) {
                    $currentLv2Id = $unit->id;
                    $currentLv3Id = null;
                } elseif ($level === 3) {
                    $currentLv3Id = $unit->id;
                }

                $created++;
            }

            $totalCreated += $created;
            $totalSkipped += $skipped;
            $allSkippedReasons = array_merge($allSkippedReasons, $skippedReasons);
        }

        if ($totalCreated > 0) {
            AssetHistory::create([
                'asset_id'    => 0, // Sesuaikan jika kolom mengizinkan null atau ID default
                'user_id'     => Auth::id(),
                'action'      => 'TAMBAH',
                'description' => "Melakukan impor data unit sebanyak {$totalCreated} baris.",
            ]);
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