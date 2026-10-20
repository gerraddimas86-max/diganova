<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Battle extends Model
{
    protected $fillable = [
        'season_id', 'mode', 'player1_id', 'player2_id', 'status', 'winner_id',
        'player1_hp_final', 'player2_hp_final',
        'player1_points_change', 'player2_points_change',
        'turn_count', 'started_at', 'ended_at',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function player1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player1_id');
    }

    public function player2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player2_id');
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function state(): HasOne
    {
        return $this->hasOne(BattleState::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(BattleLog::class)->orderBy('turn_number');
    }
}