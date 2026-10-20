<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserGachaItem extends Model
{
    protected $fillable = ['user_id', 'gacha_item_id', 'is_equipped', 'obtained_at'];

    protected function casts(): array
    {
        return [
            'is_equipped' => 'boolean',
            'obtained_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(GachaItem::class, 'gacha_item_id');
    }
}