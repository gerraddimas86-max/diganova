@extends('social.layouts.app')

@section('title', 'Pencarian — DIGANOVA Social')

@section('content')

<style>
    /* ============================================================
       CUSTOM STYLE — Simple & Clean (Match Theme)
       ============================================================ */
    .dark-card-wrapper article {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        border-radius: inherit !important;
    }
    .dark-card-wrapper h3,
    .dark-card-wrapper .text-\[\#000000\] { color: #F1F5F9 !important; }
    .dark-card-wrapper .text-gray-500,
    .dark-card-wrapper .text-gray-600 { color: #94A3B8 !important; }
    .dark-card-wrapper .text-\[\#112E81\] { color: #60A5FA !important; }
    .dark-card-wrapper .bg-\[\#F8FAFC\] { background-color: #152D54 !important; }
    .dark-card-wrapper .border-gray-100 { border-color: rgba(96, 165, 250, 0.1) !important; }

    /* Custom Scrollbar untuk Tabs */
    .custom-scrollbar::-webkit-scrollbar { height: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #3B82F6; border-radius: 4px; }
</style>

<div class="relative z-10 max-w-4xl mx-auto px-4 py-6">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="f-display text-3xl font-bold text-white tracking-tight flex items-center gap-3">
                {{-- Icon Search --}}
                <span class="w-10 h-10 rounded-xl bg-[#3B82F6] text-white flex items-center justify-center shadow-[3px_3px_0_#1E3A8A] border-[2px] border-[#1E3A8A] -rotate-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                Pencarian
            </h1>
            @if ($q !== '')
                <p class="text-sm font-medium text-slate-400 mt-3 ml-13">Hasil untuk "<strong class="text-[#FACC15]">{{ $q }}</strong>"</p>
            @endif
        </div>
    </div>

    {{-- Search Form --}}
    <form method="GET" action="{{ route('social.search') }}" class="mb-8">
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-slate-500 group-focus-within:text-[#60A5FA] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" name="q" value="{{ $q }}"
                   placeholder="Cari user, post, atau hashtag..."
                   autocomplete="off" autofocus
                   class="w-full bg-[#0F1F3D] border-2 border-[#1E3A8A] text-white pl-12 pr-28 py-4 rounded-2xl focus:outline-none focus:border-[#3B82F6] transition-all placeholder:text-slate-500 shadow-sm">
            <button type="submit"
                    class="absolute right-2 top-1/2 -translate-y-1/2 bg-[#3B82F6] hover:bg-[#2563EB] text-white px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 shadow-sm border border-[#1E3A8A]">
                Cari
            </button>
        </div>
    </form>

    @if ($q === '')
        {{-- Empty State (Belum ada pencarian) --}}
        <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-3xl p-12 text-center shadow-sm">
            <div class="w-20 h-20 mx-auto bg-[#152D54] rounded-full flex items-center justify-center mb-4 border border-[#1E3A8A]">
                <svg class="w-10 h-10 text-[#60A5FA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16l2.879-2.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="f-display text-white font-bold text-xl mb-2">Mulai cari sesuatu</h3>
            <p class="text-slate-400 text-sm max-w-sm mx-auto mb-8 font-medium">Ketik nama user, username, kata di post, atau hashtag untuk menemukan apa yang kamu cari.</p>

            <div class="flex flex-wrap justify-center gap-2 text-sm">
                @foreach(['rani', 'diganova', 'festival', 'navoclash'] as $suggestion)
                    <a href="{{ route('social.search') }}?q={{ $suggestion }}" 
                       class="bg-[#152D54] hover:bg-[#1E3A8A] border-2 border-[#1E3A8A] text-[#60A5FA] hover:text-white px-4 py-2 rounded-xl font-bold transition-all duration-300 shadow-sm">
                        {{ $suggestion }}
                    </a>
                @endforeach
            </div>
        </div>
    @else

        {{-- Tabs --}}
        <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-2xl mb-6 overflow-x-auto custom-scrollbar">
            <div class="flex min-w-max">
                @foreach ([
                    'all' => ['label' => 'Semua', 'count' => $users->count() + $posts->count() + $hashtags->count(), 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
                    'users' => ['label' => 'User', 'count' => $users->count(), 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                    'posts' => ['label' => 'Post', 'count' => $posts->count(), 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    'hashtags' => ['label' => 'Hashtag', 'count' => $hashtags->count(), 'icon' => 'M7 20l4-16m2 16l4-16M6 9h14M4 15h14'],
                ] as $key => $cfg)
                    <a href="{{ route('social.search') }}?q={{ urlencode($q) }}&tab={{ $key }}"
                       class="flex-1 flex items-center justify-center gap-2 px-6 py-4 text-sm font-bold border-b-4 transition-all duration-300 whitespace-nowrap
                              {{ $tab === $key ? 'border-[#FACC15] text-[#FACC15] bg-[#1E3A8A]/30' : 'text-slate-400 hover:bg-[#152D54] border-transparent hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $cfg['icon'] }}"></path>
                        </svg>
                        {{ $cfg['label'] }}
                        <span class="text-[10px] px-2 py-0.5 rounded-md font-bold {{ $tab === $key ? 'bg-[#FACC15] text-[#1E3A8A]' : 'bg-[#152D54] text-slate-400 border border-[#1E3A8A]' }}">
                            {{ $cfg['count'] }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="space-y-8">

            {{-- Users --}}
            @if (($tab === 'all' || $tab === 'users') && $users->isNotEmpty())
                <div>
                    <h2 class="text-xs uppercase tracking-wider text-[#FACC15] font-bold mb-3 ml-1 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        User
                    </h2>
                    <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-2xl overflow-hidden shadow-sm">
                        @foreach ($users as $u)
                            <a href="{{ route('social.profile.show', $u->username) }}"
                               class="flex items-center gap-4 p-4 hover:bg-[#152D54] transition-colors border-b-2 border-[#1E3A8A]/50 last:border-0 group">
                                {{-- Avatar --}}
                                <div class="w-12 h-12 rounded-full p-[2px] bg-gradient-to-tr from-[#22D3EE] to-[#3B82F6] flex-shrink-0 group-hover:rotate-6 transition-transform">
                                    <div class="w-full h-full rounded-full border-2 border-[#0F1F3D] overflow-hidden bg-[#0F1F3D]">
                                        <x-avatar :avatar="$u->avatar" :user="$u" size="w-full h-full" />
                                    </div>
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-white truncate group-hover:text-[#FACC15] transition-colors">{{ $u->name }}</p>
                                    <p class="text-xs font-medium text-slate-400 truncate">{{ '@' . $u->username }}</p>
                                    @if ($u->bio)
                                        <p class="text-[11px] text-slate-500 mt-1 line-clamp-1">{{ $u->bio }}</p>
                                    @endif
                                </div>
                                
                                <span class="text-[11px] font-bold text-[#60A5FA] bg-[#152D54] px-3 py-1.5 rounded-lg border-2 border-[#1E3A8A] group-hover:bg-[#3B82F6] group-hover:text-white transition-all whitespace-nowrap">
                                    Lihat
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Hashtags --}}
            @if (($tab === 'all' || $tab === 'hashtags') && $hashtags->isNotEmpty())
                <div>
                    <h2 class="text-xs uppercase tracking-wider text-[#FACC15] font-bold mb-3 ml-1 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path></svg>
                        Hashtag
                    </h2>
                    <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-2xl overflow-hidden shadow-sm">
                        @foreach ($hashtags as $tag)
                            <a href="{{ route('social.hashtag', $tag->tag) }}"
                               class="flex items-center gap-4 p-4 hover:bg-[#152D54] transition-colors border-b-2 border-[#1E3A8A]/50 last:border-0 group">
                                {{-- Hash Icon --}}
                                <div class="w-10 h-10 rounded-xl bg-[#1E3A8A] flex items-center justify-center text-[#22D3EE] font-bold flex-shrink-0 shadow-sm border border-[#3B82F6] group-hover:bg-[#22D3EE] group-hover:text-[#1E3A8A] transition-colors">
                                    <span class="text-lg">#</span>
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-white group-hover:text-[#FACC15] transition-colors truncate">#{{ $tag->tag }}</p>
                                    <p class="text-[11px] font-medium text-slate-400 mt-0.5">{{ $tag->post_count }} post</p>
                                </div>
                                
                                <span class="text-[11px] font-bold text-[#60A5FA] bg-[#152D54] px-3 py-1.5 rounded-lg border-2 border-[#1E3A8A] group-hover:bg-[#3B82F6] group-hover:text-white transition-all whitespace-nowrap">
                                    Lihat
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Posts --}}
            @if (($tab === 'all' || $tab === 'posts') && $posts->isNotEmpty())
                <div>
                    <h2 class="text-xs uppercase tracking-wider text-[#FACC15] font-bold mb-3 ml-1 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Post
                    </h2>
                    <div class="space-y-6">
                        @foreach ($posts as $post)
                            {{-- Pembungkus Double Border Sederhana --}}
                            <div class="dark-card-wrapper bg-[#0F1F3D] border-2 border-[#3B82F6] shadow-[0_0_0_4px_#1E3A8A] rounded-2xl overflow-hidden hover:border-[#60A5FA] transition-colors">
                                @include('social.posts._card', ['post' => $post])
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Global Empty State --}}
            @if ($users->isEmpty() && $posts->isEmpty() && $hashtags->isEmpty())
                <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-3xl p-12 text-center shadow-sm">
                    <div class="w-20 h-20 mx-auto bg-[#152D54] rounded-full flex items-center justify-center mb-4 border border-[#1E3A8A]">
                        <svg class="w-10 h-10 text-[#F43F5E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="f-display text-white font-bold text-xl mb-2">Tidak ada hasil untuk "{{ $q }}"</h3>
                    <p class="text-slate-400 text-sm font-medium">Coba kata kunci lain atau periksa kembali ejaanmu.</p>
                </div>
            @endif
        </div>
    @endif
</div>

@endsection