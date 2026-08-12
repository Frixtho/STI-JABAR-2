<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modem extends Model
{
    use HasFactory;

    protected $table = 'modems';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_perolehan' => 'date',
        'tanggal_pemeriksaan_terakhir' => 'date',
        'masa_berlaku_garansi' => 'date',
    ];
}