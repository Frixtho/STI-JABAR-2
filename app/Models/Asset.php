<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $table = 'assets';

    protected $fillable = [
        'name',
        'functloc',
        'category',
        'upt_id',
        'tegangan',
        'gi_awal_id',
        'gi_akhir_id',
        'jumlah_tower',
        'panjang_km',
        'latitude',
        'longitude',
    ];

    // Relasi ke Gardu Induk (GI) Awal
    public function giAwal()
    {
        return $this->belongsTo(Unit::class, 'gi_awal_id');
    }

    // Relasi ke Gardu Induk (GI) Akhir
    public function giAkhir()
    {
        return $this->belongsTo(Unit::class, 'gi_akhir_id');
    }
}