<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BattleLog extends Model
{
    protected $fillable = [
        'battle_id', 'turn_number', 'actor_user_id', 'action_type', 'card_id',
        'damage_dealt', 'heal_amount', 'block_amount', 'effect_applied', 'description',
    ];

    public function battle(): BelongsTo
    {
        return $this->belongsTo(Battle::class);
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}