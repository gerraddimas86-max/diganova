<?php

namespace App\Http\Controllers\Playground;

use App\Http\Controllers\Controller;
use App\Models\NavoSkin;
use App\Models\Season;
use Illuminate\View\View;

class PlaygroundController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        // ==========================================
        // JIKA GUEST (BELUM LOGIN) -> Tampilkan Landing
        // ==========================================
        if (!$user) {
            $topPlayers = \App\Models\NavoProfile::with('user')
                ->orderByDesc('wins')
                ->take(3)
                ->get();

            return view('playground.guest-landing', compact('topPlayers'));
        }

        // ==========================================
        // JIKA USER (SUDAH LOGIN) -> Tampilkan Lobby
        // ==========================================
        
        // Auto-create NavoProfile jika belum ada
        $profile = $user->navoProfile;
        if (!$profile) {
            $defaultSkin = NavoSkin::where('is_default', true)->first();
            $profile = $user->navoProfile()->create([
                'equipped_skin_id' => $defaultSkin?->id,
                'total_battles' => 0, 'wins' => 0, 'losses' => 0,
                'current_streak' => 0, 'best_streak' => 0,
            ]);
        }

        $profile->load(['equippedSkin', 'favoriteCard']);

        $activeDeck = $user->activeDeck()->with('cards')->first();
        
        $recentBattles = $user->battlesAsPlayer1()
            ->with(['player1', 'player2', 'winner'])
            ->whereIn('status', ['completed', 'forfeited'])
            ->latest()
            ->take(5)
            ->get()
            ->merge(
                $user->battlesAsPlayer2()
                    ->with(['player1', 'player2', 'winner'])
                    ->whereIn('status', ['completed', 'forfeited'])
                    ->latest()
                    ->take(5)
                    ->get()
            )
            ->sortByDesc('created_at')
            ->take(5);

        $activeSeason = Season::active();
        
        return view('playground.lobby', compact(
            'user', 'profile', 'activeDeck', 'recentBattles', 'activeSeason'
        ));
    }
}