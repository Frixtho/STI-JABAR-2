<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'serial_number',
        'status',
        'description',
        'functloc',
        'grup_raw',
        'gi_awal_id',
        'gi_akhir_id',
        'upt_id',
        'wil_kerja',
        'latitude',
        'longitude',
    ];

    public function upt()
    {
        return $this->belongsTo(Unit::class, 'upt_id');
    }

    /**
     * Tegangan (misal "30kV") diambil otomatis dari pola angka+kV di nama,
     * karena tabel assets tidak punya kolom voltage tersendiri.
     */
    public function getVoltageAttribute(): ?string
    {
        if (preg_match('/(\d+\s?kV)/i', $this->name ?? '', $m)) {
            return $m[1];
        }

        return null;
    }

    public function giAwal()
    {
        return $this->belongsTo(Unit::class, 'gi_awal_id');
    }

    public function giAkhir()
    {
        return $this->belongsTo(Unit::class, 'gi_akhir_id');
    }
}