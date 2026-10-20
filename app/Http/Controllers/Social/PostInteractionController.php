<?php

namespace App\Http\Controllers\Social;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostLike;
use App\Models\Repost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostInteractionController extends Controller
{
    /**
     * Toggle like (AJAX).
     */
    public function like(Post $post): JsonResponse
    {
        $userId = auth()->id();

        $existing = PostLike::where('post_id', $post->id)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            $existing->delete();
            $post->decrement('likes_count');
            $liked = false;
        } else {
            PostLike::create(['post_id' => $post->id, 'user_id' => $userId]);
            $post->increment('likes_count');
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $post->fresh()->likes_count,
        ]);
    }

    /**
     * Toggle repost (AJAX).
     * Aturan PRD: 1 user 1x repost per post.
     */
    public function repost(Post $post): JsonResponse
    {
        $userId = auth()->id();

        if ($post->user_id === $userId) {
            return response()->json([
                'error' => 'Tidak bisa repost post sendiri.',
            ], 400);
        }

        $existing = Repost::where('post_id', $post->id)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            $existing->delete();
            $post->decrement('reposts_count');
            $reposted = false;
        } else {
            Repost::create(['post_id' => $post->id, 'user_id' => $userId]);
            $post->increment('reposts_count');
            $reposted = true;
        }

        return response()->json([
            'reposted' => $reposted,
            'reposts_count' => $post->fresh()->reposts_count,
        ]);
    }

    /**
     * Store komentar (AJAX).
     */
    public function comment(Request $request, Post $post): JsonResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:500'],
            'parent_id' => ['nullable', 'integer', 'exists:post_comments,id'],
        ]);

        // Kalau parent_id diisi, pastikan parent milik post yang sama
        if (!empty($validated['parent_id'])) {
            $parent = PostComment::find($validated['parent_id']);
            if (!$parent || $parent->post_id !== $post->id) {
                return response()->json(['error' => 'Komentar induk tidak valid.'], 400);
            }
        }

        $comment = PostComment::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'parent_id' => $validated['parent_id'] ?? null,
            'body' => $validated['body'],
        ]);

        $post->increment('comments_count');
        $comment->load('user');

        return response()->json([
            'comment' => [
                'id' => $comment->id,
                'body' => $comment->body,
                'parent_id' => $comment->parent_id,
                'user' => [
                    'name' => $comment->user->name,
                    'username' => $comment->user->username,
                    'initial' => strtoupper(substr($comment->user->name, 0, 1)),
                ],
                'time_ago' => $comment->created_at->diffForHumans(),
                'is_mine' => true,
            ],
            'comments_count' => $post->fresh()->comments_count,
        ]);
    }

    /**
     * Hapus komentar.
     */
    public function destroyComment(Post $post, PostComment $comment): JsonResponse
    {
        if ($comment->post_id !== $post->id) {
            abort(404);
        }

        if ($comment->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $comment->delete();
        $post->decrement('comments_count');

        return response()->json([
            'deleted' => true,
            'comments_count' => $post->fresh()->comments_count,
        ]);
    }

    /**
     * Get list komentar (untuk lazy load).
     */
    public function comments(Post $post): JsonResponse
    {
        $comments = $post->comments()
            ->with('user')
            ->whereNull('parent_id')
            ->orderBy('created_at')
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'body' => $c->body,
                'user' => [
                    'name' => $c->user->name,
                    'username' => $c->user->username,
                    'initial' => strtoupper(substr($c->user->name, 0, 1)),
                ],
                'time_ago' => $c->created_at->diffForHumans(),
                'is_mine' => $c->user_id === auth()->id(),
            ]);

        return response()->json(['comments' => $comments]);
    }
}