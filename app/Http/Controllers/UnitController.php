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
            'name' => ['name', 'nama', 'nama_unit', 'nama unit'],
            'level' => ['level', 'lvl'],
            'code' => ['code', 'kode', 'functloc'],
            'parent_name' => ['parent_name', 'induk', 'parent', 'nama_induk', 'upt_induk'],
            'latitude' => ['latitude', 'lat', 'lock lat', 'lock_lat'],
            'longitude' => ['longitude', 'lng', 'long', 'lock lng', 'lock_lng'],
        ],
        'gi' => [
            'grup' => ['grup', 'group', 'tipe', 'jenis'],
            'name' => ['nama', 'name', 'nama_gi', 'nama_tower'],
            'code' => ['functloc', 'kode', 'code'],
            'parent_name' => ['induk', 'parent_name', 'upt_induk', 'nama_line', 'line'],
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

        $units = $query->paginate(15)->withQueryString();

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
        $header = array_map('trim', fgetcsv($handle));

        $jenis = $request->input('jenis');
        $aliases = self::FIELD_ALIASES[$jenis];

        $mapping = [];
        foreach ($aliases as $field => $candidates) {
            $mapping[$field] = $this->guessColumn($header, $candidates);
        }

        // Kolom WKT dipakai sebagai fallback kalau latitude/longitude
        // tidak ada kolom terpisah (format "POINT (lng lat)")
        $wktColumn = $this->guessColumn($header, ['wkt', 'geometry', 'geom']);

        // Kolom deskripsi dipakai sebagai fallback nama kalau kolom "name"
        // ada di header tapi datanya kosong / nama sebenarnya ada di sini
        $descColumn = $this->guessColumn($header, ['description', 'deskripsi', 'keterangan']);

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
                    $giRows[] = [
                        'name' => $mapped['name'] ?? '',
                        'level' => 4,
                        'code' => $mapped['code'] ?: null,
                        'parent_name' => $mapped['parent_name'] ?: ($defaultUpt->name ?? ''),
                        'parent_level' => 2,
                        'latitude' => $mapped['latitude'] ?? '',
                        'longitude' => $mapped['longitude'] ?? '',
                    ];
                }
            }
            fclose($handle);

            foreach ($giRows as $row) {
                $result = $this->processGenericUnitRow($row);
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

                $rows[] = [
                    'name' => $mapped['name'] ?? '',
                    'level' => (int) ($mapped['level'] ?? 0),
                    'code' => $mapped['code'] ?: null,
                    'parent_name' => $mapped['parent_name'] ?: ($defaultUpt->name ?? ''),
                    'parent_level' => null,
                    'latitude' => $mapped['latitude'] ?? '',
                    'longitude' => $mapped['longitude'] ?? '',
                ];
            }
            fclose($handle);

            usort($rows, fn ($a, $b) => (int) $a['level'] <=> (int) $b['level']);
            foreach ($rows as $row) {
                $result = $this->processGenericUnitRow($row);
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
}