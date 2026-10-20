<?php

namespace App\Http\Controllers\Playground;

use App\Http\Controllers\Controller;
use App\Models\NavoSkin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NavoProfileController extends Controller
{
    public function show(): View
    {
        $user = auth()->user();

        // Ensure profile exists
        $profile = $user->navoProfile;
        if (!$profile) {
            $defaultSkin = NavoSkin::where('is_default', true)->first();
            $profile = $user->navoProfile()->create([
                'equipped_skin_id' => $defaultSkin?->id,
            ]);
        }

        $profile->load(['equippedSkin', 'favoriteCard']);

        $decksCount = $user->decks()->count();
        $activeDeck = $user->activeDeck()->with('cards')->first();

        // Battle history
        $battleHistory = $user->battlesAsPlayer1()
            ->with(['player1', 'player2', 'winner'])
            ->whereIn('status', ['completed', 'forfeited'])
            ->latest()
            ->take(10)
            ->get()
            ->merge(
                $user->battlesAsPlayer2()
                    ->with(['player1', 'player2', 'winner'])
                    ->whereIn('status', ['completed', 'forfeited'])
                    ->latest()
                    ->take(10)
                    ->get()
            )
            ->sortByDesc('created_at')
            ->take(10);

        return view('playground.profile.show', compact(
            'user',
            'profile',
            'decksCount',
            'activeDeck',
            'battleHistory'
        ));
    }

    public function skins(): View
    {
        $user = auth()->user();
        $profile = $user->navoProfile;

        if (!$profile) {
            $defaultSkin = NavoSkin::where('is_default', true)->first();
            $profile = $user->navoProfile()->create([
                'equipped_skin_id' => $defaultSkin?->id,
            ]);
        }

        $skins = NavoSkin::orderBy('is_default', 'desc')
            ->orderByRaw("FIELD(rarity, 'common', 'rare', 'epic', 'legendary')")
            ->orderBy('id')
            ->get();

        return view('playground.profile.skins', compact('user', 'profile', 'skins'));
    }

    public function updateSkin(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'skin_id' => ['required', 'integer', 'exists:navo_skins,id'],
        ]);

        $user = auth()->user();
        $profile = $user->navoProfile;

        if (!$profile) {
            return back()->with('error', 'Profil Navo belum dibuat.');
        }

        $profile->update(['equipped_skin_id' => $validated['skin_id']]);

        $skin = NavoSkin::find($validated['skin_id']);

        return redirect()
            ->route('play.profile.show')
            ->with('success', 'Skin "' . $skin->name . '" berhasil dipakai!');
    }
}