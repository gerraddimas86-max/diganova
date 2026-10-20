<?php

namespace App\Http\Controllers;

use App\Models\Avatar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProfileController extends Controller
{
    public function show(): View
    {
        $user = Auth::user()->load(['avatar', 'achievements']);
        
        // Data Event
        $eventRegistrations = $user->eventRegistrations()
            ->with('event', 'ticketCategory')
            ->latest()
            ->take(5)
            ->get();
        
        // Data Social
        $socialStats = [
            'posts' => $user->posts()->count(),
            'followers' => $user->followers()->count(),
            'following' => $user->following()->count(),
        ];
        $recentPosts = $user->posts()->latest()->take(3)->get();
        
        // Data Playground
        $navoProfile = $user->navoProfile;
        $recentBattles = $user->battlesAsPlayer1()
            ->whereIn('status', ['completed', 'forfeited'])
            ->latest()
            ->take(5)
            ->get();
        
        return view('profile.show', compact(
            'user', 'eventRegistrations', 'socialStats', 'recentPosts', 'navoProfile', 'recentBattles'
        ));
    }

    public function edit(): View
    {
        $user = Auth::user()->load(['avatar', 'avatars']);
        $allAvatars = Avatar::orderBy('is_default', 'desc')->orderBy('id')->get();
        
        // Avatar yang bisa dipakai = default + yang sudah di-unlock
        $unlockedIds = $user->avatars->pluck('id')->toArray();
        foreach ($allAvatars as $a) {
            if ($a->is_default) $unlockedIds[] = $a->id;
        }

        return view('profile.edit', compact('user', 'allAvatars', 'unlockedIds'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:160',
            'avatar_id' => 'nullable|exists:avatars,id',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->bio = $request->bio;

        if ($request->filled('avatar_id')) {
            $avatar = Avatar::find($request->avatar_id);
            $isUnlocked = $avatar->is_default || $user->avatars()->where('avatar_id', $avatar->id)->exists();
            
            if ($isUnlocked) {
                $user->avatar_id = $avatar->id;
            }
        }

        $user->save();

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui!');
    }
}