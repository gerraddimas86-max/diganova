<?php

namespace App\Http\Controllers\Social;

use App\Http\Controllers\Controller;
use App\Models\Hashtag;
use App\Models\Post;
use App\Models\User;
use Illuminate\View\View;

class SocialHomeController extends Controller
{
    public function home(): View
    {
        // Jika belum login, tampilkan Guest Landing
        if (!auth()->check()) {
            return view('social.guest-landing');
        }

        $user = auth()->user();

        // Stats
        $stats = [
            'posts' => $user->posts()->count(),
            'followers' => $user->followers()->count(),
            'following' => $user->following()->count(),
        ];

        // Trending Hashtag (Top 5 dalam 24 jam terakhir)
        $since = now()->subHours(24);
        $trendingHashtags = Hashtag::whereHas('posts', fn($q) => $q->where('created_at', '>=', $since))
            ->withCount(['posts as recent_count' => fn($q) => $q->where('created_at', '>=', $since)])
            ->orderByDesc('recent_count')
            ->take(5)
            ->get();

        // Saran Follow (User yang belum di-follow, limit 4)
        $followingIds = $user->following()->pluck('users.id')->push($user->id)->toArray();
        $suggestedUsers = User::whereNotIn('id', $followingIds)
            ->with('avatar')
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Recent Posts dari orang yang di-follow
        $recentPosts = Post::with(['user', 'hashtags'])
            ->withCount(['likes', 'comments', 'reposts'])
            ->whereIn('user_id', $user->following()->pluck('users.id'))
            ->latest()
            ->take(3)
            ->get();

        return view('social.welcome', compact('user', 'stats', 'trendingHashtags', 'suggestedUsers', 'recentPosts'));
    }
}