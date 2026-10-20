<?php

namespace App\Services;

use App\Models\Battle;
use App\Models\BattleLog;
use App\Models\BattleState;
use App\Models\Card;
use App\Models\Deck;
use App\Models\Leaderboard;
use App\Models\MatchmakingQueue;
use App\Models\Season;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BattleEngine
{
    const START_HP = 100;
    const START_ENERGY = 3;
    const START_HAND = 5;
    const MAX_HAND = 7;
    const WIN_POINTS = 50;
    const LOSE_POINTS = -20;

    // ============================================================
    // START BATTLE (PvE & PvP)
    // ============================================================

    public function start(Battle $battle): void
    {
        $this->initializeState($battle);
    }

    public function startPvp(Battle $battle): void
    {
        $this->initializeState($battle);
    }

    private function initializeState(Battle $battle): void
    {
        $p1Cards = $this->getPlayerDeck($battle->player1_id);
        $p2Cards = $battle->player2_id
            ? $this->getPlayerDeck($battle->player2_id)
            : $this->getRandomDeck();

        shuffle($p1Cards);
        shuffle($p2Cards);

        $p1Hand = array_splice($p1Cards, 0, self::START_HAND);
        $p2Hand = array_splice($p2Cards, 0, self::START_HAND);

        $battle->state()->create([
            'current_turn' => 1,
            'player1_hp' => self::START_HP,
            'player2_hp' => self::START_HP,
            'player1_energy' => self::START_ENERGY,
            'player2_energy' => self::START_ENERGY,
            'player1_deck' => array_values($p1Cards),
            'player2_deck' => array_values($p2Cards),
            'player1_hand' => $p1Hand,
            'player2_hand' => $p2Hand,
            'player1_effects' => [],
            'player2_effects' => [],
            'turn_deadline' => now()->addSeconds(30),
        ]);

        $battle->update([
            'status' => 'in_progress',
            'started_at' => now(),
            'turn_count' => 1,
        ]);
    }

    // ============================================================
    // PvE ACTIONS (Step 3)
    // ============================================================

    public function playerPlay(Battle $battle, int $cardId): array
    {
        $state = $battle->state;

        if (!$state) {
            return ['success' => false, 'error' => 'Battle state tidak ditemukan.'];
        }

        $card = Card::find($cardId);
        if (!$card) {
            return ['success' => false, 'error' => 'Kartu tidak ditemukan.'];
        }

        $hand = $state->player1_hand ?? [];
        if (!in_array($cardId, $hand)) {
            return ['success' => false, 'error' => 'Kartu tidak ada di tangan.'];
        }

        if ($state->player1_energy < $card->energy_cost) {
            return ['success' => false, 'error' => 'Energy tidak cukup (butuh ' . $card->energy_cost . ').'];
        }

        $log1 = $this->playCard($battle, 1, $cardId);

        $state->refresh();
        if ($state->player2_hp <= 0) {
            $this->finish($battle, 1);
            return ['success' => true, 'finished' => true, 'winner' => 1, 'logs' => [$log1]];
        }

        $aiCardId = $this->aiChooseCard($battle);
        $log2 = null;
        if ($aiCardId) {
            $log2 = $this->playCard($battle, 2, $aiCardId);
        }

        $state->refresh();
        if ($state->player1_hp <= 0) {
            $this->finish($battle, 2);
            return ['success' => true, 'finished' => true, 'winner' => 2, 'logs' => array_values(array_filter([$log1, $log2]))];
        }

        $this->beginRound($battle);

        return ['success' => true, 'finished' => false, 'logs' => array_values(array_filter([$log1, $log2]))];
    }

    public function playerSkip(Battle $battle): array
    {
        $state = $battle->state;
        if (!$state) return ['success' => false, 'error' => 'Battle state tidak ditemukan.'];

        BattleLog::create([
            'battle_id' => $battle->id,
            'turn_number' => $battle->turn_count,
            'actor_user_id' => $battle->player1_id,
            'action_type' => 'skip_turn',
            'description' => 'Kamu skip turn.',
        ]);

        $aiCardId = $this->aiChooseCard($battle);
        $log2 = null;
        if ($aiCardId) {
            $log2 = $this->playCard($battle, 2, $aiCardId);
        }

        $state->refresh();
        if ($state->player1_hp <= 0) {
            $this->finish($battle, 2);
            return ['success' => true, 'finished' => true, 'winner' => 2, 'logs' => array_values(array_filter([
                ['slot' => 1, 'card' => null, 'description' => 'Kamu skip turn.'],
                $log2,
            ]))];
        }

        $this->beginRound($battle);

        return ['success' => true, 'finished' => false, 'logs' => array_values(array_filter([
            ['slot' => 1, 'card' => null, 'description' => 'Kamu skip turn.'],
            $log2,
        ]))];
    }

    // ============================================================
    // PvP ACTIONS
    // ============================================================

    public function pvpSubmitCard(Battle $battle, int $slot, int $cardId): array
    {
        $state = $battle->state;
        if (!$state) return ['success' => false, 'error' => 'Battle state tidak ditemukan.'];

        $card = Card::find($cardId);
        if (!$card) return ['success' => false, 'error' => 'Kartu tidak ditemukan.'];

        $handKey = "player{$slot}_hand";
        $energyKey = "player{$slot}_energy";
        $pendingKey = "player{$slot}_pending_card_id";
        $skipKey = "player{$slot}_skip_ready";

        $hand = $state->$handKey ?? [];
        if (!in_array($cardId, $hand)) {
            return ['success' => false, 'error' => 'Kartu tidak ada di tangan.'];
        }

        if ($state->$energyKey < $card->energy_cost) {
            return ['success' => false, 'error' => 'Energy tidak cukup.'];
        }

        if ($state->$pendingKey) {
            return ['success' => false, 'error' => 'Kamu sudah submit kartu. Tunggu lawan.'];
        }

        if ($state->$skipKey) {
            return ['success' => false, 'error' => 'Kamu sudah pilih skip.'];
        }

        $state->update([$pendingKey => $cardId]);

        return ['success' => true, 'waiting' => true];
    }

    public function pvpSubmitSkip(Battle $battle, int $slot): array
    {
        $state = $battle->state;
        if (!$state) return ['success' => false, 'error' => 'Battle state tidak ditemukan.'];

        $skipKey = "player{$slot}_skip_ready";
        $pendingKey = "player{$slot}_pending_card_id";

        if ($state->$skipKey) {
            return ['success' => false, 'error' => 'Sudah skip.'];
        }

        if ($state->$pendingKey) {
            return ['success' => false, 'error' => 'Kamu sudah submit kartu.'];
        }

        $state->update([$skipKey => true]);

        return ['success' => true, 'waiting' => true];
    }

    /**
     * Resolve turn kalau kedua player sudah ready (atau timeout).
     * Dipanggil setiap poll.
     */
    public function pvpCheckAndResolve(Battle $battle): array
    {
        $state = $battle->state;
        if (!$state || $battle->status !== 'in_progress') {
            return ['resolved' => false];
        }

        // Timer expired — auto-skip players who haven't acted
        if ($state->turn_deadline && $state->turn_deadline->isPast()) {
            if (!$state->player1_pending_card_id && !$state->player1_skip_ready) {
                $state->update(['player1_skip_ready' => true]);
            }
            if (!$state->player2_pending_card_id && !$state->player2_skip_ready) {
                $state->update(['player2_skip_ready' => true]);
            }
            $state->refresh();
        }

        if (!$state->bothPlayersReady()) {
            return ['resolved' => false];
        }

        $logs = [];

        // === PLAYER 1 ACTION ===
        if ($state->player1_pending_card_id) {
            $logs[] = $this->playCard($battle, 1, $state->player1_pending_card_id);
        } else {
            $log = BattleLog::create([
                'battle_id' => $battle->id,
                'turn_number' => $battle->turn_count,
                'actor_user_id' => $battle->player1_id,
                'action_type' => 'skip_turn',
                'description' => 'Player 1 skip turn.',
            ]);
            $logs[] = ['slot' => 1, 'card' => null, 'description' => 'Player 1 skip turn.'];
        }
        $state->refresh();

        if ($state->player2_hp <= 0) {
            $this->finishPvp($battle, 1);
            return ['resolved' => true, 'finished' => true, 'winner' => 1, 'logs' => $logs];
        }

        // === PLAYER 2 ACTION ===
        if ($state->player2_pending_card_id) {
            $logs[] = $this->playCard($battle, 2, $state->player2_pending_card_id);
        } else {
            BattleLog::create([
                'battle_id' => $battle->id,
                'turn_number' => $battle->turn_count,
                'actor_user_id' => $battle->player2_id,
                'action_type' => 'skip_turn',
                'description' => 'Player 2 skip turn.',
            ]);
            $logs[] = ['slot' => 2, 'card' => null, 'description' => 'Player 2 skip turn.'];
        }
        $state->refresh();

        if ($state->player1_hp <= 0) {
            $this->finishPvp($battle, 2);
            return ['resolved' => true, 'finished' => true, 'winner' => 2, 'logs' => $logs];
        }

        // Clear pending + next round
        $state->update([
            'player1_pending_card_id' => null,
            'player2_pending_card_id' => null,
            'player1_skip_ready' => false,
            'player2_skip_ready' => false,
        ]);

        $this->beginRound($battle);

        return ['resolved' => true, 'finished' => false, 'logs' => $logs];
    }

    // ============================================================
    // MATCHMAKING
    // ============================================================

    public function findMatch(User $user): ?Battle
    {
        return DB::transaction(function () use ($user) {
            $myQueue = MatchmakingQueue::where('user_id', $user->id)
                ->where('status', 'waiting')
                ->lockForUpdate()
                ->first();

            if (!$myQueue) return null;

            $opponent = MatchmakingQueue::where('status', 'waiting')
                ->where('user_id', '!=', $user->id)
                ->where('rank', $user->rank)
                ->lockForUpdate()
                ->orderBy('joined_at')
                ->first();

            if (!$opponent) return null;

            $battle = Battle::create([
                'season_id' => Season::active()?->id,
                'mode' => 'pvp',
                'player1_id' => $opponent->user_id,
                'player2_id' => $user->id,
                'status' => 'waiting',
            ]);

            $this->startPvp($battle);

            $myQueue->update(['status' => 'matched', 'matched_battle_id' => $battle->id]);
            $opponent->update(['status' => 'matched', 'matched_battle_id' => $battle->id]);

            return $battle;
        });
    }

    // ============================================================
    // CORE CARD RESOLUTION
    // ============================================================

    private function playCard(Battle $battle, int $slot, int $cardId): array
    {
        $state = $battle->state;
        $card = Card::find($cardId);

        $hpKey = "player{$slot}_hp";
        $energyKey = "player{$slot}_energy";
        $handKey = "player{$slot}_hand";
        $effectsKey = "player{$slot}_effects";
        $oppHpKey = 'player' . (3 - $slot) . '_hp';
        $oppEffectsKey = 'player' . (3 - $slot) . '_effects';

        $hand = $state->$handKey ?? [];
        $effects = $state->$effectsKey ?? [];
        $oppEffects = $state->$oppEffectsKey ?? [];

        $newHand = array_values(array_diff($hand, [$cardId]));

        $damageDealt = 0;
        $damage = $card->damage;

        foreach ($effects as $e) {
            if (($e['type'] ?? '') === 'damage_up') {
                $damage += $e['value'] ?? 0;
            }
        }

        if ($damage > 0) {
            $hasDodge = false;
            $blockTotal = 0;
            $defUpTotal = 0;
            $reflectPercent = 0;

            foreach ($oppEffects as $e) {
                if (($e['type'] ?? '') === 'dodge') $hasDodge = true;
                if (($e['type'] ?? '') === 'block') $blockTotal += $e['value'] ?? 0;
                if (($e['type'] ?? '') === 'defense_up') $defUpTotal += $e['value'] ?? 0;
                if (($e['type'] ?? '') === 'reflect') $reflectPercent = $e['value'] ?? 0;
            }

            if ($hasDodge) {
                $damage = 0;
                $oppEffects = array_values(array_filter($oppEffects, fn($e) => ($e['type'] ?? '') !== 'dodge'));
            } else {
                if ($card->effect_type !== 'pierce') {
                    $absorbed = min($blockTotal, $damage);
                    $damage -= $absorbed;
                    $remaining = $absorbed;
                    foreach ($oppEffects as &$e) {
                        if (($e['type'] ?? '') === 'block' && $remaining > 0) {
                            $take = min($e['value'], $remaining);
                            $e['value'] -= $take;
                            $remaining -= $take;
                        }
                    }
                    unset($e);
                    $oppEffects = array_values(array_filter($oppEffects, fn($e) => ($e['type'] ?? '') !== 'block' || ($e['value'] ?? 0) > 0));
                }

                $damage = max(0, $damage - $defUpTotal);

                if ($damage > 0) {
                    $state->$oppHpKey -= $damage;
                    $damageDealt = $damage;

                    if ($reflectPercent > 0) {
                        $reflected = intval($damage * $reflectPercent / 100);
                        if ($reflected > 0) {
                            $state->$hpKey -= $reflected;
                        }
                        $oppEffects = array_values(array_filter($oppEffects, fn($e) => ($e['type'] ?? '') !== 'reflect'));
                    }
                }
            }

            if ($card->effect_type === 'burn' && $card->effect_value > 0) {
                $oppEffects[] = [
                    'type' => 'burn',
                    'value' => $card->effect_value,
                    'duration' => $card->effect_duration ?: 3,
                ];
            }
        }

        $healAmount = 0;
        if ($card->heal > 0) {
            $before = $state->$hpKey;
            $state->$hpKey = min(self::START_HP, $state->$hpKey + $card->heal);
            $healAmount = $state->$hpKey - $before;
        }

        if ($card->block > 0) {
            $effects[] = ['type' => 'block', 'value' => $card->block, 'duration' => 1];
        }

        switch ($card->effect_type) {
            case 'damage_up':
                $effects[] = ['type' => 'damage_up', 'value' => $card->effect_value, 'duration' => $card->effect_duration ?: 2];
                break;
            case 'defense_up':
                $effects[] = ['type' => 'defense_up', 'value' => $card->effect_value, 'duration' => $card->effect_duration ?: 2];
                break;
            case 'dodge':
                $effects[] = ['type' => 'dodge', 'duration' => 1];
                break;
            case 'reflect':
                $effects[] = ['type' => 'reflect', 'value' => $card->effect_value, 'duration' => 1];
                break;
            case 'energy_gain':
                $state->$energyKey += $card->effect_value;
                break;
            case 'sacrifice':
                $state->$hpKey -= $card->effect_value;
                $state->$energyKey += 3;
                break;
        }

        $state->$energyKey -= $card->energy_cost;

        $state->$handKey = $newHand;
        $state->$effectsKey = $effects;
        $state->$oppEffectsKey = $oppEffects;
        $state->save();

        $description = $this->describeAction($card, $damageDealt, $healAmount, $card->block);

        BattleLog::create([
            'battle_id' => $battle->id,
            'turn_number' => $battle->turn_count,
            'actor_user_id' => $slot === 1 ? $battle->player1_id : $battle->player2_id,
            'action_type' => 'play_card',
            'card_id' => $card->id,
            'damage_dealt' => $damageDealt,
            'heal_amount' => $healAmount,
            'block_amount' => $card->block,
            'description' => $description,
        ]);

        return [
            'slot' => $slot,
            'card' => $card->name,
            'damage' => $damageDealt,
            'heal' => $healAmount,
            'block' => $card->block,
            'description' => $description,
        ];
    }

    // ============================================================
    // ROUND MANAGEMENT
    // ============================================================

    private function beginRound(Battle $battle): void
    {
        $state = $battle->state;

        $state->player1_energy = self::START_ENERGY;
        $state->player2_energy = self::START_ENERGY;

        $this->drawCard($state, 1);
        $this->drawCard($state, 2);

        $state->player1_effects = $this->tickEffects($state->player1_effects, $state, 'player1_hp', $battle);
        $state->player2_effects = $this->tickEffects($state->player2_effects, $state, 'player2_hp', $battle);

        $state->turn_deadline = now()->addSeconds(30);
        $state->save();

        $battle->increment('turn_count');
    }

    private function drawCard(BattleState $state, int $slot): void
    {
        $deckKey = "player{$slot}_deck";
        $handKey = "player{$slot}_hand";

        $deck = $state->$deckKey ?? [];
        $hand = $state->$handKey ?? [];

        if (empty($deck) || count($hand) >= self::MAX_HAND) {
            return;
        }

        $drawn = array_shift($deck);
        $hand[] = $drawn;

        $state->$deckKey = array_values($deck);
        $state->$handKey = $hand;
    }

    private function tickEffects(array $effects, BattleState $state, string $hpKey, Battle $battle): array
    {
        $newEffects = [];

        foreach ($effects as $e) {
            $type = $e['type'] ?? '';

            if ($type === 'burn') {
                $dmg = $e['value'] ?? 0;
                if ($dmg > 0) {
                    $state->$hpKey -= $dmg;
                    BattleLog::create([
                        'battle_id' => $battle->id,
                        'turn_number' => $battle->turn_count,
                        'action_type' => 'play_card',
                        'damage_dealt' => $dmg,
                        'description' => "Burn: -{$dmg} HP",
                    ]);
                }
            }

            $e['duration'] = ($e['duration'] ?? 1) - 1;
            if ($e['duration'] > 0) {
                $newEffects[] = $e;
            }
        }

        return $newEffects;
    }

    // ============================================================
    // AI
    // ============================================================

    private function aiChooseCard(Battle $battle): ?int
    {
        $state = $battle->state;
        $hand = $state->player2_hand ?? [];
        $energy = $state->player2_energy ?? 0;

        if (empty($hand)) return null;

        $cards = Card::whereIn('id', $hand)->get();
        $affordable = $cards->where('energy_cost', '<=', $energy);

        if ($affordable->isEmpty()) return null;

        if ($state->player2_hp < 30) {
            $heal = $affordable->where('heal', '>', 0)->sortByDesc('heal')->first();
            if ($heal) return $heal->id;
        }

        if ($state->player2_hp < 50) {
            $block = $affordable->where('block', '>', 0)->sortByDesc('block')->first();
            if ($block) return $block->id;
        }

        $damageCard = $affordable->where('damage', '>', 0)->sortByDesc('damage')->first();
        if ($damageCard) return $damageCard->id;

        return $affordable->first()->id;
    }

    // ============================================================
    // FINISH
    // ============================================================

    private function finish(Battle $battle, int $winnerSlot): void
    {
        $state = $battle->state;
        $winnerId = $winnerSlot === 1 ? $battle->player1_id : $battle->player2_id;

        $battle->update([
            'status' => 'completed',
            'winner_id' => $winnerId,
            'player1_hp_final' => max(0, $state->player1_hp),
            'player2_hp_final' => max(0, $state->player2_hp),
            'player1_points_change' => $winnerSlot === 1 ? self::WIN_POINTS : self::LOSE_POINTS,
            'player2_points_change' => $winnerSlot === 2 ? self::WIN_POINTS : self::LOSE_POINTS,
            'ended_at' => now(),
        ]);

        $player1 = $battle->player1;
        if ($player1) {
            $this->awardPlayer($player1, $winnerSlot === 1);
        }

        BattleLog::create([
            'battle_id' => $battle->id,
            'turn_number' => $battle->turn_count,
            'action_type' => 'game_end',
            'description' => $winnerSlot === 1
                ? 'Kamu menang! +' . self::WIN_POINTS . ' poin.'
                : 'Kamu kalah. ' . self::LOSE_POINTS . ' poin.',
        ]);
    }

    private function finishPvp(Battle $battle, int $winnerSlot): void
    {
        $state = $battle->state;
        $winnerId = $winnerSlot === 1 ? $battle->player1_id : $battle->player2_id;

        $battle->update([
            'status' => 'completed',
            'winner_id' => $winnerId,
            'player1_hp_final' => max(0, $state->player1_hp),
            'player2_hp_final' => max(0, $state->player2_hp),
            'player1_points_change' => $winnerSlot === 1 ? self::WIN_POINTS : self::LOSE_POINTS,
            'player2_points_change' => $winnerSlot === 2 ? self::WIN_POINTS : self::LOSE_POINTS,
            'ended_at' => now(),
        ]);

        if ($battle->player1) $this->awardPlayer($battle->player1, $winnerSlot === 1);
        if ($battle->player2) $this->awardPlayer($battle->player2, $winnerSlot === 2);

        BattleLog::create([
            'battle_id' => $battle->id,
            'turn_number' => $battle->turn_count,
            'action_type' => 'game_end',
            'description' => $winnerSlot === 1
                ? ($battle->player1?->name ?? 'P1') . ' menang!'
                : ($battle->player2?->name ?? 'P2') . ' menang!',
        ]);
    }

    private function awardPlayer(User $user, bool $win): void
    {
        $pointsChange = $win ? self::WIN_POINTS : self::LOSE_POINTS;
        $user->addPoints($pointsChange);

        $profile = $user->navoProfile;
        if ($profile) {
            $profile->increment('total_battles');
            if ($win) {
                $profile->increment('wins');
                $profile->increment('current_streak');
                if ($profile->fresh()->current_streak > $profile->best_streak) {
                    $profile->update(['best_streak' => $profile->fresh()->current_streak]);
                }
            } else {
                $profile->increment('losses');
                $profile->update(['current_streak' => 0]);
            }
        }

        $season = Season::active();
        if ($season) {
            $lb = Leaderboard::firstOrCreate(
                ['user_id' => $user->id, 'season_id' => $season->id],
                ['rank' => $user->rank, 'points' => 0, 'wins' => 0, 'losses' => 0]
            );
            $lb->increment('points', $pointsChange);
            if ($win) {
                $lb->increment('wins');
            } else {
                $lb->increment('losses');
            }
            $lb->update(['rank' => $user->fresh()->rank]);
        }
    }

    // ============================================================
    // HELPERS
    // ============================================================

    private function getPlayerDeck(int $userId): array
    {
        $deck = Deck::where('user_id', $userId)
            ->where('is_active', true)
            ->with('cards')
            ->first();

        if (!$deck || $deck->cards->isEmpty()) {
            return $this->getRandomDeck();
        }

        $cards = [];
        foreach ($deck->cards as $card) {
            for ($i = 0; $i < ($card->pivot->quantity ?? 1); $i++) {
                $cards[] = $card->id;
            }
        }

        while (count($cards) < 10) {
            $random = $this->getRandomDeck();
            $cards[] = $random[array_rand($random)];
        }

        return array_slice($cards, 0, 10);
    }

    private function getRandomDeck(): array
    {
        return Card::where('is_active', true)
            ->inRandomOrder()
            ->take(10)
            ->pluck('id')
            ->toArray();
    }

    private function describeAction(Card $card, int $damage, int $heal, int $block): string
    {
        $parts = [$card->name];
        if ($damage > 0) $parts[] = "{$damage} damage";
        if ($heal > 0) $parts[] = "heal {$heal}";
        if ($block > 0) $parts[] = "block {$block}";
        if ($card->effect_type === 'pierce' && $damage > 0) $parts[] = '(pierce)';
        if ($card->effect_type === 'burn') $parts[] = "(burn {$card->effect_value}/turn)";
        return implode(' — ', $parts);
    }
}