<?php

namespace App\Http\Controllers\Social;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Avatar;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(string $username): View
    {
        $user = User::where('username', $username)
            ->with(['avatar', 'achievements'])
            ->firstOrFail();

        $isMe = auth()->check() && auth()->id() === $user->id;
        $isFollowing = auth()->check() && !$isMe && auth()->user()->isFollowing($user);

        // Tab: Posts
        $posts = $user->posts()
            ->with(['user', 'hashtags'])
            ->withCount(['likes', 'comments', 'reposts'])
            ->latest()
            ->take(20)
            ->get();

        // Tab: Likes (post yang di-like oleh user ini)
        $likedPosts = Post::whereHas('likes', fn($q) => $q->where('user_id', $user->id))
            ->with(['user', 'hashtags'])
            ->withCount(['likes', 'comments', 'reposts'])
            ->latest()
            ->take(20)
            ->get();

        // Tab: Followers / Following
        $followers = $user->followers()->take(50)->get();
        $following = $user->following()->take(50)->get();

        // Tab: Achievement
        $achievements = $user->achievements;

        $stats = [
            'posts' => $user->posts()->count(),
            'followers' => $user->followers()->count(),
            'following' => $user->following()->count(),
            'achievements' => $achievements->count(),
        ];

        return view('social.profile.show', compact(
            'user',
            'isMe',
            'isFollowing',
            'posts',
            'likedPosts',
            'followers',
            'following',
            'achievements',
            'stats'
        ));
    }

    public function edit(): View
    {
        $user = auth()->user()->load(['avatar', 'avatars']);

        $allAvatars = Avatar::orderBy('is_default', 'desc')
            ->orderBy('id')
            ->get();

        // Avatar yang bisa dipakai = default + yang sudah di-unlock
        $unlockedIds = $allAvatars
            ->filter(fn($a) => $a->is_default || $user->avatars->contains('id', $a->id))
            ->pluck('id')
            ->toArray();

        return view('social.profile.edit', compact('user', 'allAvatars', 'unlockedIds'));
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = auth()->user();

        $data = [
            'bio' => $request->bio,
        ];

        if ($request->filled('avatar_id')) {
            $avatar = Avatar::find($request->avatar_id);

            if (!$avatar) {
                return back()->withErrors(['avatar_id' => 'Avatar tidak ditemukan.']);
            }

            $isUnlocked = $avatar->is_default
                || $user->avatars()->where('avatar_id', $avatar->id)->exists();

            if (!$isUnlocked) {
                return back()->withErrors(['avatar_id' => 'Avatar belum di-unlock.']);
            }

            $data['avatar_id'] = $avatar->id;
        }

        $user->update($data);

        return redirect()
            ->route('social.profile.show', $user->username)
            ->with('success', 'Profil berhasil diupdate.');
    }
}