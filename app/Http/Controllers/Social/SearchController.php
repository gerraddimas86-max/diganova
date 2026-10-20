<?php

namespace App\Http\Controllers\Social;

use App\Http\Controllers\Controller;
use App\Models\Hashtag;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim($request->get('q', ''));
        $tab = $request->get('tab', 'all');
        if (!in_array($tab, ['all', 'posts', 'users', 'hashtags'])) {
            $tab = 'all';
        }

        $users = collect();
        $posts = collect();
        $hashtags = collect();

        if (strlen($q) >= 1) {
            // Users
            if ($tab === 'all' || $tab === 'users') {
                $users = User::where(function ($query) use ($q) {
                    $query->where('name', 'like', "%{$q}%")
                        ->orWhere('username', 'like', "%{$q}%");
                })
                    ->with('avatar')
                    ->take(20)
                    ->get();
            }

            // Posts
            if ($tab === 'all' || $tab === 'posts') {
                $posts = Post::where('body', 'like', "%{$q}%")
                    ->with(['user', 'hashtags'])
                    ->withCount(['likes', 'comments', 'reposts'])
                    ->latest()
                    ->take(30)
                    ->get();
            }

            // Hashtags
            if ($tab === 'all' || $tab === 'hashtags') {
                $hashtags = Hashtag::where('tag', 'like', "%{$q}%")
                    ->orderByDesc('post_count')
                    ->take(20)
                    ->get();
            }
        }

        return view('social.search', compact('q', 'tab', 'users', 'posts', 'hashtags'));
    }

    /**
     * Autocomplete suggestions untuk search bar (AJAX).
     */
    public function suggestions(Request $request): JsonResponse
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 2) {
            return response()->json(['users' => [], 'hashtags' => []]);
        }

        $users = User::where(function ($query) use ($q) {
            $query->where('name', 'like', "%{$q}%")
                ->orWhere('username', 'like', "%{$q}%");
        })
            ->take(5)
            ->get(['name', 'username'])
            ->toArray();

        $hashtags = Hashtag::where('tag', 'like', "%{$q}%")
            ->orderByDesc('post_count')
            ->take(5)
            ->get(['tag', 'post_count'])
            ->toArray();

        return response()->json([
            'users' => $users,
            'hashtags' => $hashtags,
        ]);
    }
}