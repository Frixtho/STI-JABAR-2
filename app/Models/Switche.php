<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Switche extends Model
{
    protected $table = 'switches';
    protected $guarded = [];

    protected $casts = [
        'tanggal_perolehan' => 'date',
        'tanggal_pemeriksaan_terakhir' => 'date',
        'masa_berlaku_garansi' => 'date',
    ];
}