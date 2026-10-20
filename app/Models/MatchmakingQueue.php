<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchmakingQueue extends Model
{
    protected $table = 'matchmaking_queue';

    protected $fillable = [
        'user_id', 'season_id', 'rank', 'status', 'matched_battle_id', 'joined_at',
    ];

    protected function casts(): array
    {
        return ['joined_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function battle(): BelongsTo
    {
        return $this->belongsTo(Battle::class, 'matched_battle_id');
    }
}