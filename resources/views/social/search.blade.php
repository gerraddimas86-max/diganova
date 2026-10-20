@extends('social.layouts.app')

@section('title', 'Pencarian — DIGANOVA Social')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">🔍 Pencarian</h1>
    @if ($q !== '')
        <p class="text-sm text-gray-500 mt-1">Hasil untuk "<strong>{{ $q }}</strong>"</p>
    @endif
</div>

{{-- Search Form --}}
<form method="GET" action="{{ route('social.search') }}" class="mb-6">
    <div class="relative">
        <input type="text" name="q" value="{{ $q }}"
               placeholder="Cari user, post, atau hashtag..."
               autocomplete="off" autofocus
               class="w-full px-4 py-3 pl-11 pr-24 border border-gray-300 rounded-xl focus:ring-2 focus:ring-violet-500 outline-none">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <button type="submit"
                class="absolute right-2 top-1/2 -translate-y-1/2 bg-violet-600 hover:bg-violet-700 text-white px-4 py-1.5 rounded-lg text-sm font-medium">
            Cari
        </button>
    </div>
</form>

@if ($q === '')
    {{-- Empty state --}}
    <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
        <div class="text-5xl mb-3">🔍</div>
        <p class="text-gray-700 font-medium">Mulai cari sesuatu</p>
        <p class="text-sm text-gray-500 mt-1">Ketik nama user, username, kata di post, atau hashtag</p>

        <div class="mt-6 flex flex-wrap justify-center gap-2 text-sm">
            <a href="{{ route('social.search') }}?q=rani" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded-full">rani</a>
            <a href="{{ route('social.search') }}?q=diganova" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded-full">diganova</a>
            <a href="{{ route('social.search') }}?q=festival" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded-full">festival</a>
            <a href="{{ route('social.search') }}?q=navoclash" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded-full">navoclash</a>
        </div>
    </div>
@else

    {{-- Tabs --}}
    <div class="bg-white rounded-xl border border-gray-200 mb-4 overflow-x-auto">
        <div class="flex min-w-max">
            @foreach ([
                'all' => ['label' => '🔍 Semua', 'count' => $users->count() + $posts->count() + $hashtags->count()],
                'users' => ['label' => '👤 User', 'count' => $users->count()],
                'posts' => ['label' => '📝 Post', 'count' => $posts->count()],
                'hashtags' => ['label' => '#️⃣ Hashtag', 'count' => $hashtags->count()],
            ] as $key => $cfg)
                <a href="{{ route('social.search') }}?q={{ urlencode($q) }}&tab={{ $key }}"
                   class="flex-1 px-4 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap text-center
                          {{ $tab === $key ? 'border-violet-600 text-violet-700 bg-violet-50' : 'text-gray-600 hover:bg-gray-50 border-transparent' }}">
                    {{ $cfg['label'] }}
                    <span class="text-xs opacity-75">({{ $cfg['count'] }})</span>
                </a>
            @endforeach
        </div>
    </div>

    <div class="space-y-6">

        {{-- Users --}}
        @if (($tab === 'all' || $tab === 'users') && $users->isNotEmpty())
            <div>
                <h2 class="text-sm uppercase tracking-wide text-gray-500 font-semibold mb-2">User</h2>
                <div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100">
                    @foreach ($users as $u)
                        <a href="{{ route('social.profile.show', $u->username) }}"
                           class="flex items-center gap-3 p-4 hover:bg-gray-50 transition">
                            <x-avatar :avatar="$u->avatar" :user="$u" size="w-12 h-12" />
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 truncate">{{ $u->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ '@' . $u->username }}</p>
                                @if ($u->bio)
                                    <p class="text-xs text-gray-600 mt-1 line-clamp-1">{{ $u->bio }}</p>
                                @endif
                            </div>
                            <span class="text-xs text-violet-600 whitespace-nowrap">Lihat →</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Hashtags --}}
        @if (($tab === 'all' || $tab === 'hashtags') && $hashtags->isNotEmpty())
            <div>
                <h2 class="text-sm uppercase tracking-wide text-gray-500 font-semibold mb-2">Hashtag</h2>
                <div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100">
                    @foreach ($hashtags as $tag)
                        <a href="{{ route('social.hashtag', $tag->tag) }}"
                           class="flex items-center gap-3 p-4 hover:bg-gray-50 transition">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-500 to-crystal-400 flex items-center justify-center text-white font-bold">
                                #
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">#{{ $tag->tag }}</p>
                                <p class="text-xs text-gray-500">{{ $tag->post_count }} post</p>
                            </div>
                            <span class="text-xs text-violet-600">Lihat →</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Posts --}}
        @if (($tab === 'all' || $tab === 'posts') && $posts->isNotEmpty())
            <div>
                <h2 class="text-sm uppercase tracking-wide text-gray-500 font-semibold mb-2">Post</h2>
                <div class="space-y-4">
                    @foreach ($posts as $post)
                        @include('social.posts._card', ['post' => $post])
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Empty --}}
        @if ($users->isEmpty() && $posts->isEmpty() && $hashtags->isEmpty())
            <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                <div class="text-5xl mb-3">😶</div>
                <p class="text-gray-700 font-medium">Tidak ada hasil untuk "{{ $q }}"</p>
                <p class="text-sm text-gray-500 mt-1">Coba kata kunci lain atau cek ejaan</p>
            </div>
        @endif
    </div>
@endif

@endsection