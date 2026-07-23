<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    protected $fillable = [
        'name', 'code', 'level', 'type', 'parent_id', 'latitude', 'longitude',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Unit::class, 'parent_id');
    }

    /** Contoh: "UIT JBT → UPT Bandung → ULTG Bandung Selatan" */
    public function pathLabel(): string
    {
        $names = [];
        $unit = $this;
        while ($unit->parent) {
            $names[] = $unit->parent->name;
            $unit = $unit->parent;
        }
        return implode(' → ', array_reverse($names)) ?: '—';
    }

    public function levelLabel(): string
    {
        return match ((int) $this->level) {
            1 => 'UIT',
            2 => 'UPT',
            3 => 'ULTG',
            4 => 'GI',
            default => '—',
        };
    }

    /** Jarak garis lurus (Haversine) dalam KM ke unit lain yang punya koordinat */
    public function distanceToKm(Unit $other): ?float
    {
        if (! $this->latitude || ! $this->longitude || ! $other->latitude || ! $other->longitude) {
            return null;
        }

        $earthRadius = 6371; // km
        $lat1 = deg2rad($this->latitude);
        $lat2 = deg2rad($other->latitude);
        $dLat = deg2rad($other->latitude - $this->latitude);
        $dLon = deg2rad($other->longitude - $this->longitude);

        $a = sin($dLat / 2) ** 2 + cos($lat1) * cos($lat2) * sin($dLon / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }
}