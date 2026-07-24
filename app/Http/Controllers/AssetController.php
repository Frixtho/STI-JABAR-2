<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    // ... method index(), destroy(), calculateKms() tetap sama seperti sebelumnya ...

    /**
     * Daftar kemungkinan nama kolom di CSV untuk tiap field standar.
     * Semua dicocokkan case-insensitive & abaikan spasi ekstra.
     */
    private const FIELD_ALIASES = [
        'functloc' => ['functloc', 'kode', 'code', 'id aset', '#'],
        'grup'     => ['grup', 'group', 'kategori', 'category', 'jenis'],
        'induk'    => ['induk', 'upt', 'parent', 'induk unit', 'nama induk'],
        'wil_kerja'=> ['wil. kerja', 'wil kerja', 'ultg', 'wilayah kerja'],
        'nama'     => ['nama', 'name', 'nama aset', 'nama unit'],
        'deskripsi'=> ['description', 'deskripsi', 'keterangan'],
        'lat'      => ['lock lat', 'latitude', 'lat', 'y'],
        'lng'      => ['lock lng', 'lock lon', 'longitude', 'lng', 'lon', 'x'],
        'wkt'      => ['wkt', 'geometry', 'geom'],
    ];

    public function importForm()
    {
        $upts = Unit::where('level', 2)->orderBy('name')->get();

        return view('assetImport', compact('upts'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt'],
            'default_upt_id' => ['nullable', 'exists:units,id'],
            'default_category' => ['nullable', 'in:sutt_30_70,sutt_150,sutt_500'],
        ]);

        $handle = fopen($request->file('file')->getRealPath(), 'r');
        $rawHeader = fgetcsv($handle);
        $header = array_map(fn ($h) => trim($h), $rawHeader);

        $colIndex = $this->resolveColumns($header);

        $defaultUpt = $request->filled('default_upt_id')
            ? Unit::find($request->default_upt_id)
            : null;
        $defaultCategory = $request->input('default_category', 'sutt_150');

        $imported = 0;
        $skippedNoUpt = 0;
        $skippedNoCoord = 0;
        $skippedNoCategory = 0;
        $missingUptNames = [];

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < count($header)) continue;

            $get = fn (string $field) => isset($colIndex[$field]) ? trim($row[$colIndex[$field]] ?? '') : '';

            $namaText = $get('nama') ?: $get('deskripsi');
            $grupText = $get('grup');
            $combinedText = trim($grupText.' '.$namaText.' '.$get('deskripsi'));

            // ===== 1. Tentukan kategori =====
            $category = $this->detectCategory($combinedText) ?? $defaultCategory;

            if (! $category) {
                $skippedNoCategory++;
                continue;
            }

            // ===== 2. Tentukan UPT (parent) =====
            $uptName = $get('induk');
            $upt = null;

            if ($uptName !== '') {
                $upt = Unit::where('level', 2)->where('name', 'ILIKE', "%{$uptName}%")->first();
            }

            if (! $upt) {
                $upt = $defaultUpt;
            }

            if (! $upt) {
                $skippedNoUpt++;
                if ($uptName !== '') $missingUptNames[$uptName] = true;
                continue;
            }

            // ===== 3. Koordinat: dari kolom lat/lng eksplisit, atau parse dari WKT =====
            $lat = $get('lat');
            $lng = $get('lng');

            if (($lat === '' || $lng === '') && $get('wkt') !== '') {
                [$parsedLng, $parsedLat] = $this->parseWkt($get('wkt'));
                $lat = $lat ?: $parsedLat;
                $lng = $lng ?: $parsedLng;
            }

            if (! is_numeric($lat) || ! is_numeric($lng)) {
                $skippedNoCoord++;
                continue;
            }

            // ===== 4. Functloc: pakai kolom asli, atau generate dari nama+koordinat kalau tidak ada =====
            $functloc = $get('functloc') ?: ('AUTO-'.md5($namaText.$lat.$lng));

            Asset::updateOrCreate(
                ['functloc' => $functloc],
                [
                    'category' => $category,
                    'grup_raw' => $grupText ?: $combinedText,
                    'name' => $namaText ?: '(tanpa nama)',
                    'upt_id' => $upt->id,
                    'wil_kerja' => $get('wil_kerja') ?: null,
                    'latitude' => $lat,
                    'longitude' => $lng,
                ]
            );
            $imported++;
        }
        fclose($handle);

        $message = "{$imported} baris berhasil diimpor.";
        if ($skippedNoUpt > 0) {
            $names = implode(', ', array_keys($missingUptNames));
            $message .= " {$skippedNoUpt} baris dilewati karena UPT tidak ditemukan".($names ? " (\"{$names}\")" : '')." — pilih UPT tujuan default sebelum import kalau file tidak punya kolom Induk.";
        }
        if ($skippedNoCoord > 0) {
            $message .= " {$skippedNoCoord} baris dilewati karena tidak ada koordinat yang valid.";
        }
        if ($skippedNoCategory > 0) {
            $message .= " {$skippedNoCategory} baris dilewati karena kategori tidak terdeteksi.";
        }

        return redirect()->route('manage-asset')->with(
            ($skippedNoUpt + $skippedNoCoord + $skippedNoCategory) > 0 ? 'error' : 'success',
            $message
        );
    }

    /** Cocokkan header CSV ke field standar pakai daftar alias, tanpa perlu konfirmasi manual */
    private function resolveColumns(array $header): array
    {
        $normalized = array_map(fn ($h) => strtolower(trim($h)), $header);
        $result = [];

        foreach (self::FIELD_ALIASES as $field => $aliases) {
            foreach ($aliases as $alias) {
                $idx = array_search($alias, $normalized, true);
                if ($idx !== false) {
                    $result[$field] = $idx;
                    break;
                }
            }
        }

        return $result;
    }

    /** Tebak kategori aset dari teks gabungan (Grup + Nama + Deskripsi) */
    private function detectCategory(string $text): ?string
    {
        $t = strtoupper($text);

        if (str_contains($t, 'GARDU INDUK') || preg_match('/\bGI\b/', $t) || str_contains($t, 'GITET') || str_contains($t, 'GIS')) {
            return 'gi';
        }

        if (str_contains($t, 'TOWER') || str_contains($t, 'SUTT')) {
            if (preg_match('/(\d+)\s*KV/', $t, $m)) {
                $kv = (int) $m[1];
                if ($kv >= 500) return 'sutt_500';
                if ($kv >= 150) return 'sutt_150';
                return 'sutt_30_70';
            }
            return null; // ketemu "tower/sutt" tapi kV-nya nggak ketauan → nanti fallback ke default_category
        }

        return null;
    }

    /** Parse "POINT (lng lat)" jadi [lng, lat] */
    private function parseWkt(string $wkt): array
    {
        if (preg_match('/POINT\s*\(\s*([-\d.]+)\s+([-\d.]+)\s*\)/i', $wkt, $m)) {
            return [$m[1], $m[2]]; // [lng, lat]
        }
        return [null, null];
    }
}