<?php

namespace App\Http\Controllers\Playground;

use App\Http\Controllers\Controller;
use App\Models\Battle;
use App\Models\BattleLog;
use App\Models\Card;
use App\Models\Season;
use App\Services\BattleEngine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BattleController extends Controller
{
    public function __construct(protected BattleEngine $engine)
    {
    }

    /**
     * Halaman battle active atau result.
     */
    public function show(Battle $battle): View|RedirectResponse
    {
        if (!in_array(auth()->id(), [$battle->player1_id, $battle->player2_id])) {
            abort(403, 'Battle bukan milikmu.');
        }

        if ($battle->status === 'completed') {
            $battle->load(['player1', 'player2', 'winner', 'logs.card', 'logs.actor']);
            return view('playground.battle.result', compact('battle'));
        }

        if ($battle->status !== 'in_progress') {
            return redirect()->route('play.home')->with('error', 'Battle tidak aktif.');
        }

        $battle->load(['player1', 'player2', 'state']);
        $state = $battle->state;

        if (!$state) {
            return redirect()->route('play.home')->with('error', 'Battle state tidak ditemukan.');
        }

        // Auto-skip if deadline passed
        if ($state->turn_deadline && $state->turn_deadline->isPast()) {
            $this->engine->playerSkip($battle);
            return redirect()->route('play.battle.show', $battle);
        }

        // Load cards in hand
        $handCards = Card::whereIn('id', $state->player1_hand ?? [])->get()->keyBy('id');
        $orderedHand = collect($state->player1_hand ?? [])
            ->map(fn($id) => $handCards->get($id))
            ->filter()
            ->values();

        $recentLogs = BattleLog::where('battle_id', $battle->id)
            ->with(['card', 'actor'])
            ->orderByDesc('id')
            ->take(20)
            ->get()
            ->reverse()
            ->values();

        return view('playground.battle.show', compact('battle', 'state', 'orderedHand', 'recentLogs'));
    }

    /**
     * Start battle PvE baru.
     */
    public function startPve(): RedirectResponse
    {
        $user = auth()->user();

        // Check active deck
        if (!$user->activeDeck || !$user->activeDeck->cards()->exists()) {
            return redirect()
                ->route('play.decks.index')
                ->with('error', 'Kamu harus punya deck aktif dengan 10 kartu sebelum battle.');
        }

        // Auto-abandon previous in-progress battles
        Battle::where('player1_id', $user->id)
            ->whereIn('status', ['waiting', 'in_progress'])
            ->update(['status' => 'cancelled', 'ended_at' => now()]);

        $battle = Battle::create([
            'season_id' => Season::active()?->id,
            'mode' => 'pve',
            'player1_id' => $user->id,
            'player2_id' => null,
            'status' => 'waiting',
        ]);

        $this->engine->start($battle);

        return redirect()
            ->route('play.battle.show', $battle)
            ->with('success', 'Battle dimulai! Pilih kartu dari tanganmu.');
    }

    /**
     * Play a card (AJAX).
     */
    public function playCard(Request $request, Battle $battle): JsonResponse
    {
        if (auth()->id() !== $battle->player1_id) {
            return response()->json(['success' => false, 'error' => 'Bukan battle kamu.'], 403);
        }

        if ($battle->status !== 'in_progress') {
            return response()->json(['success' => false, 'error' => 'Battle sudah selesai.'], 400);
        }

        $request->validate([
            'card_id' => ['required', 'integer'],
        ]);

        $result = $this->engine->playerPlay($battle, $request->card_id);

        if (!$result['success']) {
            return response()->json($result, 400);
        }

        $battle = $battle->fresh(['state']);

        return response()->json([
            'success' => true,
            'state' => $this->statePayload($battle),
            'logs' => $result['logs'] ?? [],
            'finished' => $result['finished'] ?? false,
            'winner' => $result['winner'] ?? null,
        ]);
    }

    /**
     * Skip turn (AJAX, timer expired).
     */
    public function skip(Battle $battle): JsonResponse
    {
        if (auth()->id() !== $battle->player1_id) {
            return response()->json(['success' => false, 'error' => 'Bukan battle kamu.'], 403);
        }

        if ($battle->status !== 'in_progress') {
            return response()->json(['success' => false, 'error' => 'Battle sudah selesai.'], 400);
        }

        $result = $this->engine->playerSkip($battle);

        $battle = $battle->fresh(['state']);

        return response()->json([
            'success' => true,
            'state' => $this->statePayload($battle),
            'logs' => $result['logs'] ?? [],
            'finished' => $result['finished'] ?? false,
            'winner' => $result['winner'] ?? null,
        ]);
    }

    /**
     * Forfeit battle (auto-lose).
     */
    public function forfeit(Battle $battle): RedirectResponse
    {
        if (auth()->id() !== $battle->player1_id) {
            abort(403);
        }

        if ($battle->status !== 'in_progress') {
            return redirect()->route('play.battle.show', $battle);
        }

        // Mark forfeit
        $state = $battle->state;
        $battle->update([
            'status' => 'forfeited',
            'winner_id' => null,
            'player1_points_change' => -30,
            'player1_hp_final' => max(0, $state->player1_hp ?? 0),
            'player2_hp_final' => max(0, $state->player2_hp ?? 0),
            'ended_at' => now(),
        ]);

        $user = auth()->user();
        $user->addPoints(-30);
        if ($user->navoProfile) {
            $user->navoProfile->increment('losses');
            $user->navoProfile->increment('total_battles');
            $user->navoProfile->update(['current_streak' => 0]);
        }

        BattleLog::create([
            'battle_id' => $battle->id,
            'turn_number' => $battle->turn_count,
            'action_type' => 'forfeit',
            'actor_user_id' => $user->id,
            'description' => 'Kamu forfeit. -30 poin.',
        ]);

        return redirect()
            ->route('play.battle.show', $battle)
            ->with('error', 'Kamu forfeit battle. -30 poin.');
    }

    // ============================================================
    // HELPER
    // ============================================================

    private function statePayload(Battle $battle): array
    {
        $state = $battle->state;

        if (!$state) return [];

        return [
            'player1_hp' => max(0, $state->player1_hp),
            'player2_hp' => max(0, $state->player2_hp),
            'player1_energy' => $state->player1_energy,
            'player2_energy' => $state->player2_energy,
            'player1_hand' => $state->player1_hand ?? [],
            'turn_count' => $battle->turn_count,
            'turn_deadline' => $state->turn_deadline?->toIso8601String(),
            'status' => $battle->status,
        ];
    }
}