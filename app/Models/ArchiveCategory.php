<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class ArchiveCategory extends Model
{
    protected $fillable = ['name', 'classification_code', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function archives(): HasMany
    {
        return $this->hasMany(Arsip::class, 'kategori', 'name');
    }
}
