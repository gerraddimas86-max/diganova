@extends('social.layouts.app')

@section('title', 'Trending — DIGANOVA Social')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">📈 Trending</h1>
    <p class="text-sm text-gray-500 mt-1">Hashtag populer dalam 24 jam terakhir</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- MAIN: Recent Posts --}}
    <div class="lg:col-span-2 space-y-4">
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <h2 class="font-bold text-gray-900 mb-3">🔥 Post Terbaru</h2>
            <p class="text-xs text-gray-500 mb-3">Update 24 jam terakhir</p>

            @if ($recentPosts->isEmpty())
                <div class="text-center py-8">
                    <div class="text-4xl mb-2">😴</div>
                    <p class="text-gray-700 font-medium">Belum ada post dalam 24 jam</p>
                    <p class="text-xs text-gray-500 mt-1">Jadilah yang pertama posting hari ini!</p>
                    @auth
                        <a href="{{ route('social.posts.index') }}"
                           class="inline-block mt-3 text-violet-600 hover:underline text-sm">
                            Buat post →
                        </a>
                    @endauth
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($recentPosts as $post)
                        @include('social.posts._card', ['post' => $post])
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- SIDEBAR: Trending Hashtags --}}
    <aside class="lg:col-span-1">
        <div class="bg-white rounded-xl border border-gray-200 p-4 sticky top-20">
            <h2 class="font-bold text-gray-900 mb-3">🏷️ Trending Hashtag</h2>

            @if ($trending->isEmpty())
                <p class="text-xs text-gray-500 text-center py-4">
                    Belum ada hashtag trending
                </p>
            @else
                <div class="space-y-1">
                    @foreach ($trending as $i => $tag)
                        <a href="{{ route('social.hashtag', $tag->tag) }}"
                           class="block px-2 py-2 rounded-lg hover:bg-violet-50 transition group">
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-400 w-5 text-right">{{ $i + 1 }}</span>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900 text-sm group-hover:text-violet-600">
                                        #{{ $tag->tag }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $tag->recent_count }} post · {{ $tag->post_count }} total
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </aside>
</div>

@endsection