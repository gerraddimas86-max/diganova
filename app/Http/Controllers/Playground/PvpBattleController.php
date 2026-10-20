<?php

namespace App\Http\Controllers\Playground;

use App\Http\Controllers\Controller;
use App\Models\Battle;
use App\Models\BattleLog;
use App\Models\Card;
use App\Services\BattleEngine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PvpBattleController extends Controller
{
    public function __construct(protected BattleEngine $engine)
    {
    }

    /**
     * Halaman battle PvP.
     */
    public function show(Battle $battle): View|RedirectResponse
    {
        if ($battle->mode !== 'pvp') {
            return redirect()->route('play.home');
        }

        $userId = auth()->id();

        if (!in_array($userId, [$battle->player1_id, $battle->player2_id])) {
            abort(403, 'Battle bukan milikmu.');
        }

        if (in_array($battle->status, ['completed', 'forfeited', 'cancelled'])) {
            $battle->load(['player1', 'player2', 'winner', 'logs.card', 'logs.actor']);
            return view('playground.battle.pvp_result', compact('battle'));
        }

        if ($battle->status !== 'in_progress') {
            return redirect()->route('play.home')->with('error', 'Battle belum aktif.');
        }

        $battle->load(['player1', 'player2', 'state']);

        $mySlot = $battle->player1_id === $userId ? 1 : 2;

        $state = $battle->state;

        // Load cards in my hand
        $myHandIds = $mySlot === 1 ? ($state->player1_hand ?? []) : ($state->player2_hand ?? []);
        $handCards = Card::whereIn('id', $myHandIds)->get()->keyBy('id');
        $orderedHand = collect($myHandIds)->map(fn($id) => $handCards->get($id))->filter()->values();

        return view('playground.battle.pvp_show', compact('battle', 'state', 'mySlot', 'orderedHand'));
    }

    /**
     * Submit kartu.
     */
    public function play(Request $request, Battle $battle): JsonResponse
    {
        $request->validate(['card_id' => 'required|integer']);

        $userId = auth()->id();
        if (!in_array($userId, [$battle->player1_id, $battle->player2_id])) {
            return response()->json(['success' => false, 'error' => 'Bukan battle kamu.'], 403);
        }

        if ($battle->status !== 'in_progress') {
            return response()->json(['success' => false, 'error' => 'Battle sudah selesai.'], 400);
        }

        $mySlot = $battle->player1_id === $userId ? 1 : 2;

        $result = $this->engine->pvpSubmitCard($battle, $mySlot, $request->card_id);

        if (!$result['success']) {
            return response()->json($result, 400);
        }

        return response()->json($result);
    }

    /**
     * Skip turn.
     */
    public function skip(Battle $battle): JsonResponse
    {
        $userId = auth()->id();
        if (!in_array($userId, [$battle->player1_id, $battle->player2_id])) {
            return response()->json(['success' => false, 'error' => 'Bukan battle kamu.'], 403);
        }

        if ($battle->status !== 'in_progress') {
            return response()->json(['success' => false, 'error' => 'Battle sudah selesai.'], 400);
        }

        $mySlot = $battle->player1_id === $userId ? 1 : 2;

        $result = $this->engine->pvpSubmitSkip($battle, $mySlot);

        if (!$result['success']) {
            return response()->json($result, 400);
        }

        return response()->json($result);
    }

    /**
     * Poll state (AJAX, setiap 3 detik).
     */
    public function state(Battle $battle): JsonResponse
    {
        $userId = auth()->id();
        if (!in_array($userId, [$battle->player1_id, $battle->player2_id])) {
            return response()->json(['error' => 'Bukan battle kamu.'], 403);
        }

        // Try to resolve turn
        $this->engine->pvpCheckAndResolve($battle);

        $battle = $battle->fresh(['state', 'player1', 'player2']);

        $mySlot = $battle->player1_id === $userId ? 1 : 2;
        $state = $battle->state;

        $myHandIds = $mySlot === 1 ? ($state->player1_hand ?? []) : ($state->player2_hand ?? []);
        $handCards = Card::whereIn('id', $myHandIds)->get()->keyBy('id');
        $hand = collect($myHandIds)->map(fn($id) => $handCards->get($id))->filter()->map(fn($c) => [
            'id' => $c->id,
            'name' => $c->name,
            'type' => $c->type,
            'energy_cost' => $c->energy_cost,
            'damage' => $c->damage,
            'heal' => $c->heal,
            'block' => $c->block,
        ])->values();

        $logs = BattleLog::where('battle_id', $battle->id)
            ->orderByDesc('id')
            ->take(15)
            ->get()
            ->reverse()
            ->map(fn($l) => [
                'slot' => $l->actor_user_id === $battle->player1_id ? 1 : ($l->actor_user_id === $battle->player2_id ? 2 : 0),
                'description' => $l->description,
                'card' => $l->card?->name,
            ])
            ->values();

        $myPending = $mySlot === 1 ? $state->player1_pending_card_id : $state->player2_pending_card_id;
        $mySkip = $mySlot === 1 ? $state->player1_skip_ready : $state->player2_skip_ready;

        $oppPending = $mySlot === 1 ? $state->player2_pending_card_id : $state->player1_pending_card_id;
        $oppSkip = $mySlot === 1 ? $state->player2_skip_ready : $state->player1_skip_ready;

        return response()->json([
            'status' => $battle->status,
            'winner_id' => $battle->winner_id,
            'state' => [
                'player1_hp' => max(0, $state->player1_hp),
                'player2_hp' => max(0, $state->player2_hp),
                'my_energy' => $mySlot === 1 ? $state->player1_energy : $state->player2_energy,
                'turn_count' => $battle->turn_count,
                'turn_deadline' => $state->turn_deadline?->toIso8601String(),
                'my_pending' => (bool) $myPending,
                'my_skip' => (bool) $mySkip,
                'opp_pending' => (bool) $oppPending,
                'opp_skip' => (bool) $oppSkip,
            ],
            'hand' => $hand,
            'logs' => $logs,
        ]);
    }

    /**
     * Forfeit.
     */
    public function forfeit(Battle $battle): RedirectResponse
    {
        $userId = auth()->id();
        if (!in_array($userId, [$battle->player1_id, $battle->player2_id])) {
            abort(403);
        }

        if ($battle->status !== 'in_progress') {
            return redirect()->route('play.battle.pvp.show', $battle);
        }

        $mySlot = $battle->player1_id === $userId ? 1 : 2;
        $winnerId = $mySlot === 1 ? $battle->player2_id : $battle->player1_id;

        $state = $battle->state;
        $battle->update([
            'status' => 'forfeited',
            'winner_id' => $winnerId,
            'player1_hp_final' => max(0, $state->player1_hp ?? 0),
            'player2_hp_final' => max(0, $state->player2_hp ?? 0),
            'player1_points_change' => $mySlot === 1 ? -30 : 50,
            'player2_points_change' => $mySlot === 2 ? -30 : 50,
            'ended_at' => now(),
        ]);

        $me = auth()->user();
        $me->addPoints(-30);
        if ($me->navoProfile) {
            $me->navoProfile->increment('losses');
            $me->navoProfile->increment('total_battles');
            $me->navoProfile->update(['current_streak' => 0]);
        }

        $opponent = $mySlot === 1 ? $battle->player2 : $battle->player1;
        if ($opponent) {
            $opponent->addPoints(50);
            if ($opponent->navoProfile) {
                $opponent->navoProfile->increment('wins');
                $opponent->navoProfile->increment('total_battles');
            }
        }

        BattleLog::create([
            'battle_id' => $battle->id,
            'turn_number' => $battle->turn_count,
            'action_type' => 'forfeit',
            'actor_user_id' => $userId,
            'description' => $me->name . ' forfeit.',
        ]);

        return redirect()->route('play.battle.pvp.show', $battle);
    }
}