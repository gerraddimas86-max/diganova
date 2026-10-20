<?php

namespace App\Http\Controllers\Social;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $posts = Post::with(['user', 'hashtags'])
            ->withCount(['likes', 'comments', 'reposts'])
            ->latest()
            ->paginate(15);

        return view('social.feed', compact('posts'));
    }

    public function store(StorePostRequest $request): RedirectResponse
    {
        $post = Post::create([
            'user_id' => auth()->id(),
            'body' => $request->body,
        ]);

        return redirect()
            ->route('social.posts.index')
            ->with('success', 'Post berhasil dipublikasikan!');
    }

    public function edit(Post $post): View
    {
        abort_if($post->user_id !== auth()->id(), 403, 'Kamu hanya bisa edit post milik sendiri.');

        return view('social.posts.edit', compact('post'));
    }

    public function update(StorePostRequest $request, Post $post): RedirectResponse
    {
        abort_if($post->user_id !== auth()->id(), 403);

        $post->update(['body' => $request->body]);

        return redirect()
            ->route('social.posts.index')
            ->with('success', 'Post berhasil diupdate.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        if ($post->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $post->delete();

        return redirect()
            ->route('social.posts.index')
            ->with('success', 'Post berhasil dihapus.');
    }
}