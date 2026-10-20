<?php

namespace App\Http\Controllers\Playground;

use App\Http\Controllers\Controller;
use App\Models\Leaderboard;
use App\Models\Season;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaderboardController extends Controller
{
    public function index(Request $request): View
    {
        $seasons = Season::orderByDesc('start_date')->get();

        // Pilih season: default = aktif, atau yang dipilih
        $selectedSeasonId = $request->get('season');
        $activeSeason = Season::active();

        if ($selectedSeasonId && $selectedSeasonId !== 'active') {
            $selectedSeason = Season::find($selectedSeasonId);
        } elseif ($selectedSeasonId === 'all') {
            $selectedSeason = null;
        } else {
            $selectedSeason = $activeSeason;
        }

        $query = Leaderboard::with('user');

        if ($selectedSeason) {
            $query->where('season_id', $selectedSeason->id);
        }

        $topPlayers = $query
            ->orderByDesc('points')
            ->orderByDesc('wins')
            ->take(100)
            ->get();

        // User rank position (kalau login)
        $myPosition = null;
        $myEntry = null;
        $myUserId = auth()->id();
        if ($myUserId && $selectedSeason) {
            $myEntry = Leaderboard::where('user_id', $myUserId)
                ->where('season_id', $selectedSeason->id)
                ->first();

            if ($myEntry) {
                $myPosition = Leaderboard::where('season_id', $selectedSeason->id)
                    ->where(function ($q) use ($myEntry) {
                        $q->where('points', '>', $myEntry->points)
                            ->orWhere(function ($q2) use ($myEntry) {
                                $q2->where('points', $myEntry->points)
                                    ->where('wins', '>', $myEntry->wins);
                            });
                    })
                    ->count() + 1;
            }
        }

        return view('playground.leaderboard.index', compact(
            'seasons',
            'selectedSeason',
            'activeSeason',
            'topPlayers',
            'myEntry',
            'myPosition'
        ));
    }
}