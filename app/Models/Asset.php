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
    ];

    public function giAwal()
    {
        return $this->belongsTo(GarduInduk::class, 'gi_awal_id');
    }

    public function giAkhir()
    {
        return $this->belongsTo(GarduInduk::class, 'gi_akhir_id');
    }
}
