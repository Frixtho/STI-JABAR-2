<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    // Ganti $fillable dengan $guarded = []. 
    // Ini mengizinkan semua kolom di tabel (termasuk atribut umum baru) untuk diisi secara massal lewat import.
    protected $guarded = [];

    // Pastikan spesifikasi di-cast ke array (JSON)
    protected $casts = [
        'specifications' => 'array',
    ];
}