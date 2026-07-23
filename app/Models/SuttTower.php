<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuttTower extends Model
{
    protected $fillable = [
        'sutt_line_id',
        'tower_number',
        'functloc',
        'name',
        'latitude',
        'longitude',
    ];

    public function line()
    {
        return $this->belongsTo(SuttLine::class, 'sutt_line_id');
    }
}