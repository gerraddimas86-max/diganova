<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NavoProfile extends Model
{
    protected $fillable = [
        'user_id', 'equipped_skin_id', 'favorite_card_id', 'equipped_accessories',
        'total_battles', 'wins', 'losses', 'current_streak', 'best_streak',
    ];

    protected function casts(): array
    {
        return ['equipped_accessories' => 'array'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function equippedSkin(): BelongsTo
    {
        return $this->belongsTo(NavoSkin::class, 'equipped_skin_id');
    }

    public function favoriteCard(): BelongsTo
    {
        return $this->belongsTo(Card::class, 'favorite_card_id');
    }

    public function winRate(): float
    {
        if ($this->total_battles === 0) return 0;
        return round(($this->wins / $this->total_battles) * 100, 1);
    }
}