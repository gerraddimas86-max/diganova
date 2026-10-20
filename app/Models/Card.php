<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Card extends Model
{
    protected $fillable = [
        'name', 'slug', 'type', 'description', 'energy_cost',
        'damage', 'heal', 'block', 'effect_type', 'effect_value',
        'effect_duration', 'image_path', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function decks(): BelongsToMany
    {
        return $this->belongsToMany(Deck::class, 'deck_cards')
            ->withPivot('quantity');
    }

    public function isAttack(): bool { return $this->type === 'attack'; }
    public function isDefense(): bool { return $this->type === 'defense'; }
    public function isSkill(): bool { return $this->type === 'skill'; }
}