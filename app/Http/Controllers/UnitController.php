<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\SuttLine;
use App\Models\SuttTower;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UnitController extends Controller
{
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

    // ===================== CSV IMPORT =====================

    public function importForm()
    {
        return view('unitImport');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $handle = fopen($request->file('file')->getRealPath(), 'r');
        $header = array_map('trim', fgetcsv($handle));

        // Deteksi format dari header baris pertama.
        // Format A (umum, semua level): level,name,code,parent_name,latitude,longitude
        // Format B (export SAP/aset PLN): WKT,#,Functloc,Grup,Induk,Wil. Kerja,Nama,Lock Lat,Lock Lng
        //   Format B punya 2 jenis baris, dibedakan lewat kolom Grup:
        //     - "GARDU INDUK" -> jadi Unit level 4 (GI), Induk = nama UPT
        //     - "TOWER"       -> jadi SuttTower, Induk = nama jalur SUTT
        $isGiFormat = in_array('Functloc', $header) && in_array('Induk', $header) && in_array('Nama', $header);
        $isGenericFormat = in_array('level', $header) && in_array('name', $header) && in_array('parent_name', $header);

        if (! $isGiFormat && ! $isGenericFormat) {
            fclose($handle);
            return back()->with('error', 'Header CSV tidak dikenali. Gunakan salah satu format kolom yang sudah ditentukan.');
        }

        $created = 0;
        $skipped = 0;
        $skippedReasons = [];

        if ($isGiFormat) {
            $giRows = [];
            $towerRows = [];

            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) < count($header)) continue;
                $raw = array_combine($header, $row);
                $grup = strtoupper(trim($raw['Grup'] ?? ''));

                if ($grup === 'TOWER') {
                    $towerRows[] = $raw;
                } else {
                    // Default-kan ke GI kalau Grup-nya "GARDU INDUK" atau semacamnya
                    $giRows[] = $this->normalizeGiRow($raw);
                }
            }
            fclose($handle);

            // proses GI dulu, biar towers bisa nyambung ke GI yang baru dibikin
            usort($giRows, fn ($a, $b) => (int) $a['level'] <=> (int) $b['level']);
            foreach ($giRows as $row) {
                $result = $this->processGenericUnitRow($row);
                $result['status'] === 'failed' ? $skipped++ : $created++;
                if ($result['status'] === 'failed') $skippedReasons[] = $result['alasan'];
            }

            foreach ($towerRows as $raw) {
                $result = $this->processTowerRow($raw);
                $result['status'] === 'failed' ? $skipped++ : $created++;
                if ($result['status'] === 'failed') $skippedReasons[] = $result['alasan'];
            }
        } else {
            $rows = [];
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) < count($header)) continue;
                $raw = array_combine($header, $row);
                $rows[] = $this->normalizeGenericRow($raw);
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

        return back()
            ->with($skipped > 0 ? 'error' : 'success', $message)
            ->with('import_skipped_reasons', $skippedReasons);
    }

    /**
     * Proses satu baris unit (hasil normalisasi dari format umum ATAU baris GI
     * dari format SAP) jadi record Unit. Parent dicari dari parent_name (+
     * dipersempit ke parent_level kalau ada).
     */
    private function processGenericUnitRow(array $row): array
    {
        $parent = null;

        if (! empty($row['parent_name'])) {
            $parentQuery = Unit::where('name', 'ILIKE', trim($row['parent_name']));

            if (! empty($row['parent_level'])) {
                $parentQuery->where('level', $row['parent_level']);
            }

            $parent = $parentQuery->first();

            // Kalau induknya belum ada, otomatis dibikinin (bukan di-skip).
            // Level induk dipakai dari parent_level kalau diketahui (misal
            // format GI selalu UPT/level 2), atau ditebak sebagai
            // level unit ini dikurangi 1.
            if (! $parent) {
                $parentLevel = $row['parent_level'] ?? max(1, (int) $row['level'] - 1);

                $parent = Unit::create([
                    'name' => trim($row['parent_name']),
                    'level' => $parentLevel,
                    'type' => match ($parentLevel) {
                        1 => 'uit', 2 => 'upt', 3 => 'ultg', 4 => 'gi', default => 'gi',
                    },
                    'parent_id' => null, // induknya sendiri (misal UIT) belum diketahui, bisa diisi manual belakangan
                ]);
            }
        }

        Unit::updateOrCreate(
            ['name' => trim($row['name']), 'level' => (int) $row['level']],
            [
                'code' => $row['code'] ?? null,
                'type' => match ((int) $row['level']) {
                    1 => 'uit', 2 => 'upt', 3 => 'ultg', 4 => 'gi', default => 'gi',
                },
                'parent_id' => $parent?->id,
                'latitude' => ($row['latitude'] ?? '') !== '' ? $row['latitude'] : null,
                'longitude' => ($row['longitude'] ?? '') !== '' ? $row['longitude'] : null,
            ]
        );

        return ['status' => 'created'];
    }

    /**
     * Proses satu baris TOWER (Grup=TOWER) jadi SuttTower, sekaligus
     * bikin/pakai SuttLine yang sesuai. Line & GI endpoint dideteksi dari
     * pola Functloc: TRS-3512-{kodeA}.{kodeB}-T{nomor}
     */
    private function processTowerRow(array $raw): array
    {
        $functloc = trim($raw['Functloc'] ?? '');
        $induk = trim($raw['Induk'] ?? ''); // nama line, misal "TRS 70kV UJUNGBERUNG-SUMEDANG"
        $nama = trim($raw['Nama'] ?? '');
        $lat = trim($raw['Lock Lat'] ?? '');
        $lng = trim($raw['Lock Lng'] ?? '');

        $parsed = $this->parseTowerFunctloc($functloc);

        if (! $parsed || $induk === '' || $lat === '' || $lng === '') {
            return ['status' => 'failed', 'alasan' => "Tower \"{$nama}\": Functloc tidak sesuai pola atau data koordinat kosong"];
        }

        $codePair = "{$parsed['code_a']}.{$parsed['code_b']}";

        $line = SuttLine::firstOrCreate(
            ['name' => $induk, 'code_pair' => $codePair],
            [
                'voltage' => $this->extractVoltageFromLineName($induk),
                'gi_start_id' => $this->findGiByFunctlocCode($parsed['code_a'])?->id,
                'gi_end_id' => $this->findGiByFunctlocCode($parsed['code_b'])?->id,
            ]
        );

        SuttTower::updateOrCreate(
            ['sutt_line_id' => $line->id, 'tower_number' => $parsed['tower_number']],
            [
                'functloc' => $functloc,
                'name' => $nama,
                'latitude' => $lat,
                'longitude' => $lng,
            ]
        );

        return ['status' => 'created'];
    }

    /**
     * Parsing Functloc tower, format: TRS-3512-{kodeA}.{kodeB}-T{nomor}
     * Contoh: TRS-3512-254.229-T0049 -> kodeA=254, kodeB=229, nomor=49
     */
    private function parseTowerFunctloc(string $functloc): ?array
    {
        if (! preg_match('/-(\d+)\.(\d+)-T(\d+)$/', $functloc, $m)) {
            return null;
        }

        return [
            'code_a' => $m[1],
            'code_b' => $m[2],
            'tower_number' => (int) $m[3],
        ];
    }

    /**
     * Cari GI (level 4) yang kode Functloc-nya cocok, misal kode "254"
     * dicari lewat pola "...-254.254" di kolom code milik GI tersebut.
     */
    private function findGiByFunctlocCode(string $code): ?Unit
    {
        return Unit::where('level', 4)
            ->where('code', 'LIKE', "%-{$code}.{$code}")
            ->first();
    }

    private function extractVoltageFromLineName(string $lineName): ?string
    {
        return preg_match('/(\d+\s?kV)/i', $lineName, $m) ? $m[1] : null;
    }

    /**
     * Normalisasi baris format umum (level,name,code,parent_name,latitude,longitude)
     * ke struktur baris standar yang dipakai proses import.
     */
    private function normalizeGenericRow(array $raw): array
    {
        return [
            'name' => trim($raw['name'] ?? ''),
            'level' => (int) ($raw['level'] ?? 0),
            'code' => trim($raw['code'] ?? '') ?: null,
            'parent_name' => trim($raw['parent_name'] ?? ''),
            'parent_level' => null, // parent bisa level berapa aja, gak dibatasi
            'latitude' => trim($raw['latitude'] ?? ''),
            'longitude' => trim($raw['longitude'] ?? ''),
        ];
    }

    /**
     * Normalisasi baris format export GI dari SAP
     * (WKT,#,Functloc,Grup,Induk,Wil. Kerja,Nama,Lock Lat,Lock Lng)
     * ke struktur baris standar yang dipakai proses import.
     */
    private function normalizeGiRow(array $raw): array
    {
        return [
            'name' => trim($raw['Nama'] ?? ''),
            'level' => 4, // format ini khusus dipakai buat GI
            'code' => trim($raw['Functloc'] ?? '') ?: null,
            'parent_name' => trim($raw['Induk'] ?? ''),
            'parent_level' => 2, // kolom "Induk" di format ini selalu nama UPT
            'latitude' => trim($raw['Lock Lat'] ?? ''),
            'longitude' => trim($raw['Lock Lng'] ?? ''),
        ];
    }

    // ===================== HITUNG JARAK (KMS) =====================

    /** Hitung jarak dari sebuah unit (biasanya GI) ke UPT Bandung */
    public function distanceToBandung(Unit $unit)
    {
        $bandung = Unit::where('type', 'upt')
            ->where('name', 'ILIKE', '%bandung%')
            ->first();

        if (! $bandung) {
            return response()->json(['error' => 'Unit "UPT Bandung" belum ada di database. Tambahkan dulu koordinatnya.'], 404);
        }

        $distance = $unit->distanceToKm($bandung);

        if ($distance === null) {
            return response()->json(['error' => 'Koordinat (latitude/longitude) belum lengkap pada salah satu unit.'], 422);
        }

        return response()->json([
            'from' => $unit->name,
            'to' => $bandung->name,
            'distance_km' => $distance,
        ]);
    }
}