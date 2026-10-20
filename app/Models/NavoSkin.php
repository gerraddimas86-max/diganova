<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NavoSkin extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'image_path', 'rarity', 'is_default'];

    protected function casts(): array
    {
        return ['is_default' => 'boolean'];
    }
}