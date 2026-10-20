@extends('social.layouts.app')

@section('title', 'Beranda — DIGANOVA Social')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- KOLOM KIRI: Profil & Recent Activity --}}
    <div class="lg:col-span-2 space-y-6">
        
        {{-- Sapaan --}}
        <div class="bg-gradient-to-r from-violet-600 to-fuchsia-600 rounded-2xl p-6 sm:p-8 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Halo, {{ $user->name }}! 👋</h1>
            <p class="text-white/80">Selamat datang kembali di DIGANOVA Social. Ada apa hari ini?</p>
            
            <div class="mt-6 flex gap-3">
                <a href="{{ route('social.posts.index') }}" class="bg-white text-violet-700 hover:bg-gray-50 px-5 py-2.5 rounded-lg font-bold transition shadow-lg shadow-black/10">
                    💬 Buka Feed
                </a>
                <a href="{{ route('social.profile.show', $user->username) }}" class="bg-white/20 hover:bg-white/30 text-white px-5 py-2.5 rounded-lg font-medium transition backdrop-blur">
                    Lihat Profil
                </a>
            </div>
        </div>

        {{-- Recent Posts from Following --}}
        <div>
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-bold text-gray-900 text-lg">Aktivitas Temanmu</h2>
                <a href="{{ route('social.posts.index') }}" class="text-xs text-violet-600 hover:underline font-medium">Lihat Feed →</a>
            </div>

            @if ($recentPosts->isEmpty())
                <div class="bg-white rounded-xl border border-gray-200 p-8 text-center">
                    <div class="text-4xl mb-2">👻</div>
                    <p class="text-gray-700 font-medium">Belum ada aktivitas</p>
                    <p class="text-sm text-gray-500 mt-1">Follow lebih banyak orang atau mulai posting sendiri!</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($recentPosts as $post)
                        @include('social.posts._card', ['post' => $post])
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- KOLOM KANAN: Stats, Trending, Suggested --}}
    <div class="space-y-6">
        
        {{-- Stats Card --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="font-bold text-gray-900 mb-4">Statistikmu</h3>
            <div class="grid grid-cols-3 gap-2 text-center">
                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-xl font-bold text-gray-900">{{ $stats['posts'] }}</p>
                    <p class="text-[10px] text-gray-500 uppercase tracking-wide mt-1">Posts</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-xl font-bold text-gray-900">{{ $stats['followers'] }}</p>
                    <p class="text-[10px] text-gray-500 uppercase tracking-wide mt-1">Followers</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-xl font-bold text-gray-900">{{ $stats['following'] }}</p>
                    <p class="text-[10px] text-gray-500 uppercase tracking-wide mt-1">Following</p>
                </div>
            </div>
        </div>

        {{-- Trending --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="font-bold text-gray-900 mb-4">🔥 Trending Topik</h3>
            @if ($trendingHashtags->isEmpty())
                <p class="text-sm text-gray-500 text-center py-4">Belum ada trending</p>
            @else
                <div class="space-y-3">
                    @foreach ($trendingHashtags as $tag)
                        <a href="{{ route('social.hashtag', $tag->tag) }}" class="block group">
                            <p class="font-semibold text-gray-900 group-hover:text-violet-600 transition">#{{ $tag->tag }}</p>
                            <p class="text-xs text-gray-500">{{ $tag->recent_count }} post hari ini</p>
                        </a>
                    @endforeach
                </div>
                <a href="{{ route('social.trending') }}" class="block text-center text-xs text-violet-600 hover:underline mt-4">Lihat semua trending</a>
            @endif
        </div>

        {{-- Suggested Users --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="font-bold text-gray-900 mb-4">👥 Saran Untukmu</h3>
            @if ($suggestedUsers->isEmpty())
                <p class="text-sm text-gray-500 text-center py-4">Kamu sudah follow semua orang!</p>
            @else
                <div class="space-y-3">
                    @foreach ($suggestedUsers as $sugUser)
                        <div class="flex items-center gap-3">
                            <a href="{{ route('social.profile.show', $sugUser->username) }}">
                                <x-avatar :avatar="$sugUser->avatar" :user="$sugUser" size="w-10 h-10" />
                            </a>
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('social.profile.show', $sugUser->username) }}" class="font-semibold text-gray-900 hover:underline text-sm block truncate">
                                    {{ $sugUser->name }}
                                </a>
                                <p class="text-xs text-gray-500 truncate">{{ '@' . $sugUser->username }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('social.search') }}" class="block text-center text-xs text-violet-600 hover:underline mt-4">Cari orang lain</a>
            @endif
        </div>

    </div>
</div>

@endsection