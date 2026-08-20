<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetCategory extends Model
{
    protected $fillable = ['name', 'slug', 'is_active'];

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'asset_category_id');
    }

    // Relasi: Satu kategori punya banyak field (kolom kustom)
    public function fields()
    {
        return $this->hasMany(AssetCategoryField::class)->orderBy('order_position', 'asc');
    }
}
