<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetCategoryField extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_category_id', 'name', 'group_name', 'field_key', 'field_type', 
        'options', 'is_required', 'show_in_table', 'order_position'
    ];

    // Beritahu Laravel bahwa 'options' itu formatnya Array/JSON
    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'show_in_table' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(AssetCategory::class, 'asset_category_id');
    }
}