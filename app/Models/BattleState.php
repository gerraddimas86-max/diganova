<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BattleState extends Model
{
    protected $fillable = [
        'battle_id', 'current_turn',
        'player1_hp', 'player2_hp', 'player1_energy', 'player2_energy',
        'player1_deck', 'player2_deck', 'player1_hand', 'player2_hand',
        'player1_effects', 'player2_effects',
        'player1_pending_card_id', 'player2_pending_card_id',
        'player1_skip_ready', 'player2_skip_ready',
        'turn_deadline',
        'player1_consecutive_disconnects', 'player2_consecutive_disconnects',
    ];

    protected function casts(): array
    {
        return [
            'player1_deck' => 'array',
            'player2_deck' => 'array',
            'player1_hand' => 'array',
            'player2_hand' => 'array',
            'player1_effects' => 'array',
            'player2_effects' => 'array',
            'turn_deadline' => 'datetime',
            'player1_skip_ready' => 'boolean',
            'player2_skip_ready' => 'boolean',
        ];
    }

    public function battle(): BelongsTo
    {
        return $this->belongsTo(Battle::class);
    }

    public function isPlayerReady(int $slot): bool
    {
        $pendingKey = "player{$slot}_pending_card_id";
        $skipKey = "player{$slot}_skip_ready";
        return !empty($this->$pendingKey) || $this->$skipKey;
    }

    public function bothPlayersReady(): bool
    {
        return $this->isPlayerReady(1) && $this->isPlayerReady(2);
    }
}