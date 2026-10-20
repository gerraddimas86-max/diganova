<?php

namespace App\Http\Controllers\Social;

use App\Http\Controllers\Controller;
use App\Models\Hashtag;
use App\Models\Post;
use Illuminate\View\View;

class TrendingController extends Controller
{
    /**
     * Halaman trending — hashtag populer 24 jam.
     */
    public function index(): View
    {
        $since = now()->subHours(24);

        $trending = Hashtag::query()
            ->whereHas('posts', fn($q) => $q->where('created_at', '>=', $since))
            ->withCount(['posts as recent_count' => fn($q) => $q->where('created_at', '>=', $since)])
            ->orderByDesc('recent_count')
            ->take(20)
            ->get();

        $recentPosts = Post::with(['user', 'hashtags'])
            ->withCount(['likes', 'comments', 'reposts'])
            ->where('created_at', '>=', $since)
            ->latest()
            ->take(10)
            ->get();

        return view('social.trending', compact('trending', 'recentPosts', 'since'));
    }

    /**
     * Halaman hashtag — semua post dengan tag ini.
     */
    public function hashtag(string $tag): View
    {
        $normalized = ltrim(mb_strtolower($tag), '#');

        $hashtag = Hashtag::where('tag', $normalized)->firstOrFail();

        $posts = $hashtag->posts()
            ->with(['user', 'hashtags'])
            ->withCount(['likes', 'comments', 'reposts'])
            ->latest()
            ->paginate(15);

        return view('social.hashtag', compact('hashtag', 'posts'));
    }
}