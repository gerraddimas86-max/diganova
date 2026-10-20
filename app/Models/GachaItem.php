<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GachaItem extends Model
{
    protected $fillable = [
        'name', 'slug', 'type', 'rarity', 'image_path',
        'description', 'drop_rate', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'drop_rate' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_gacha_items')
            ->withPivot(['is_equipped', 'obtained_at']);
    }
}