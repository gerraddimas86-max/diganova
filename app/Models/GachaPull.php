<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GachaPull extends Model
{
    protected $fillable = ['user_id', 'gacha_item_id', 'is_duplicate'];

    protected function casts(): array
    {
        return ['is_duplicate' => 'boolean'];
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