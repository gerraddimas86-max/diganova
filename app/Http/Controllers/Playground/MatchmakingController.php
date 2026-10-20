<?php

namespace App\Http\Controllers\Playground;

use App\Http\Controllers\Controller;
use App\Models\Battle;
use App\Models\MatchmakingQueue;
use App\Models\Season;
use App\Services\BattleEngine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MatchmakingController extends Controller
{
    public function __construct(protected BattleEngine $engine)
    {
    }

    /**
     * Masuk queue matchmaking.
     */
    public function queue(): RedirectResponse
    {
        $user = auth()->user();

        if (!$user->activeDeck || !$user->activeDeck->cards()->exists()) {
            return redirect()
                ->route('play.decks.index')
                ->with('error', 'Kamu harus punya deck aktif dengan 10 kartu sebelum PvP.');
        }

        // Cancel battle PvP yang masih waiting (in-progress yang belum mulai)
        Battle::where('mode', 'pvp')
            ->where('status', 'waiting')
            ->where(fn($q) => $q->where('player1_id', $user->id)->orWhere('player2_id', $user->id))
            ->update(['status' => 'cancelled', 'ended_at' => now()]);

        // Clear queue lama
        MatchmakingQueue::where('user_id', $user->id)
            ->where('status', 'waiting')
            ->delete();

        MatchmakingQueue::create([
            'user_id' => $user->id,
            'season_id' => Season::active()?->id,
            'rank' => $user->rank,
            'status' => 'waiting',
            'joined_at' => now(),
        ]);

        return redirect()->route('play.battle.pvp.queue');
    }

    /**
     * Halaman waiting queue.
     */
    public function queuePage(): View|RedirectResponse
    {
        $myQueue = MatchmakingQueue::where('user_id', auth()->id())
            ->where('status', 'waiting')
            ->first();

        if (!$myQueue) {
            // Sudah matched? Redirect ke battle
            $matched = MatchmakingQueue::where('user_id', auth()->id())
                ->where('status', 'matched')
                ->whereNotNull('matched_battle_id')
                ->latest()
                ->first();

            if ($matched) {
                return redirect()->route('play.battle.pvp.show', $matched->matched_battle_id);
            }

            return redirect()->route('play.home');
        }

        return view('playground.battle.pvp_queue');
    }

    /**
     * Poll status queue (AJAX).
     */
    public function status(): JsonResponse
    {
        $user = auth()->user();

        $myQueue = MatchmakingQueue::where('user_id', $user->id)
            ->whereIn('status', ['waiting', 'matched'])
            ->orderByDesc('id')
            ->first();

        if (!$myQueue) {
            return response()->json(['status' => 'not_queued']);
        }

        if ($myQueue->status === 'matched' && $myQueue->matched_battle_id) {
            return response()->json([
                'status' => 'matched',
                'battle_id' => $myQueue->matched_battle_id,
            ]);
        }

        // Try to find match
        $match = $this->engine->findMatch($user);

        if ($match) {
            return response()->json([
                'status' => 'matched',
                'battle_id' => $match->id,
            ]);
        }

        $position = MatchmakingQueue::where('status', 'waiting')
            ->where('joined_at', '<=', $myQueue->joined_at)
            ->count();

        return response()->json([
            'status' => 'waiting',
            'position' => max(1, $position),
            'wait_seconds' => $myQueue->joined_at ? now()->diffInSeconds($myQueue->joined_at) : 0,
        ]);
    }

    /**
     * Cancel queue.
     */
    public function cancel(): RedirectResponse
    {
        MatchmakingQueue::where('user_id', auth()->id())
            ->where('status', 'waiting')
            ->delete();

        return redirect()->route('play.home')->with('success', 'Matchmaking dibatalkan.');
    }
}