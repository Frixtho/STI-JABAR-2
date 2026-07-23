<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuttLine extends Model
{
    protected $fillable = [
        'name',
        'voltage',
        'code_pair',
        'gi_start_id',
        'gi_end_id',
    ];

    public function towers()
    {
        return $this->hasMany(SuttTower::class)->orderBy('tower_number');
    }

    public function giStart()
    {
        return $this->belongsTo(Unit::class, 'gi_start_id');
    }

    public function giEnd()
    {
        return $this->belongsTo(Unit::class, 'gi_end_id');
    }

    /**
     * Jarak total menyusuri jalur (km): jumlah jarak antar tower yang berurutan
     * (bukan garis lurus dari GI awal ke GI akhir).
     * Return null kalau tower-nya kurang dari 2 (gak bisa dihitung jaraknya).
     */
    public function pathLengthKm(): ?float
    {
        $towers = $this->towers()->get(['latitude', 'longitude']);

        if ($towers->count() < 2) {
            return null;
        }

        $total = 0.0;
        for ($i = 0; $i < $towers->count() - 1; $i++) {
            $total += self::haversineKm(
                (float) $towers[$i]->latitude,
                (float) $towers[$i]->longitude,
                (float) $towers[$i + 1]->latitude,
                (float) $towers[$i + 1]->longitude,
            );
        }

        return round($total, 3);
    }

    private static function haversineKm(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadiusKm = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadiusKm * $c;
    }
}