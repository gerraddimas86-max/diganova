@extends('social.layouts.app')

@section('title', 'Pencarian — DIGANOVA Social')

@section('content')

<style>
    /* ===================== THEME VARIABLES ===================== */
    html {
        --bg-page: #000000;
        --bg-card: #0D0D0D;
        --bg-card-alt: #171717;
        --bg-inner: #050505;
        --border-card: #262626;
        --border-strong: #3F3F3F;
        
        /* Warna Teks Tema Gelap (Putih) */
        --text-primary: #FFFFFF;
        --text-secondary: #E2E8F0;
        --text-muted: #94A3B8;
        
        --accent-blue: #3B82F6;
        --accent-blue-soft: #60A5FA;
        --shadow-color: #1F1F1F;
    }
    
    html.light-mode {
        --bg-page: #FFFFFF;
        --bg-card: #EFF6FF;     /* Biru sangat soft */
        --bg-card-alt: #DBEAFE; /* Biru soft */
        --bg-inner: #FFFFFF;
        --border-card: #BFDBFE;
        --border-strong: #93C5FD;
        
        /* Warna Teks Tema Terang (Hitam) */
        --text-primary: #000000;
        --text-secondary: #334155;
        --text-muted: #64748B;
        
        --accent-blue: #2563EB; 
        --accent-blue-soft: #3B82F6;
        --shadow-color: #BFDBFE;
    }

    body { background-color: var(--bg-page) !important; color: var(--text-primary); }

    /* ===== Override Tailwind backgrounds ===== */
    html .bg-\[\#0F1F3D\] { background-color: var(--bg-card) !important; }
    html .bg-\[\#152D54\] { background-color: var(--bg-card-alt) !important; }
    html .bg-\[\#060D1F\] { background-color: var(--bg-inner) !important; }
    html .bg-\[\#1E3A8A\] { background-color: var(--bg-card-alt) !important; }
    html .bg-\[\#1E3A8A\]\/30 { background-color: rgba(59, 130, 246, 0.1) !important; } /* Soft blue for active tab */
    html .bg-\[\#F8FAFC\] { background-color: var(--bg-card-alt) !important; }
    html .hover\:bg-\[\#152D54\]:hover { background-color: var(--bg-card-alt) !important; }
    html .hover\:bg-\[\#1E3A8A\]:hover { background-color: var(--bg-card-alt) !important; }

    /* ===== Override borders ===== */
    html .border-\[\#1E3A8A\] { border-color: var(--border-card) !important; }
    html .border-\[\#3B82F6\] { border-color: var(--accent-blue) !important; }
    html .border-\[\#22D3EE\] { border-color: var(--accent-blue) !important; }
    html .border-\[\#60A5FA\] { border-color: var(--accent-blue-soft) !important; }

    /* ===== GLOBAL TEXT OVERRIDES (HITAM & PUTIH) ===== */
    html .text-white, html .f-display, html h1, html h2, html h3, html h4, html p {
        color: var(--text-primary) !important;
    }
    html .text-slate-300, html .text-slate-400, html .text-slate-500 { color: var(--text-muted) !important; }
    
    /* ===== PROTEKSI WARNA (TOMBOL & AKSEN) ===== */
    /* Agar teks di atas background biru tetap putih di light mode */
    html.light-mode .bg-\[\#3B82F6\], html.light-mode .bg-\[\#3B82F6\] *,
    html.light-mode .bg-\[\#2563EB\], html.light-mode .bg-\[\#2563EB\] * {
        color: #FFFFFF !important;
    }

    /* Teks Aksen Biru */
    html.light-mode .text-\[\#3B82F6\], html.light-mode .text-\[\#60A5FA\], html.light-mode .text-\[\#2563EB\] { color: #2563EB !important; }
    html:not(.light-mode) .text-\[\#3B82F6\], html:not(.light-mode) .text-\[\#60A5FA\] { color: #60A5FA !important; }

    /* ===== Override shadows ===== */
    html .shadow-\[5px_5px_0_\#060D1F\],
    html .shadow-\[3px_3px_0_\#1E3A8A\],
    html .shadow-\[2px_2px_0_\#1E3A8A\] { box-shadow: 5px 5px 0 var(--shadow-color) !important; }
    
    html .hover\:shadow-\[5px_5px_0_\#1E3A8A\]:hover,
    html .hover\:shadow-\[3px_3px_0_\#1E3A8A\]:hover { box-shadow: 5px 5px 0 var(--shadow-color) !important; }

    /* ===== KARTU POSTINGAN (KHUSUS) ===== */
    .dark-card-wrapper article {
        background: var(--bg-card) !important;
        border: 3px solid var(--border-card) !important;
        box-shadow: 5px 5px 0 var(--shadow-color) !important;
        border-radius: 20px !important;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .dark-card-wrapper article:hover {
        transform: translateY(-4px);
        box-shadow: 7px 7px 0 var(--shadow-color) !important;
    }

    /* TEMA GELAP (KARTU POSTINGAN): SEMUA TEKS PUTIH */
    html:not(.light-mode) .dark-card-wrapper article,
    html:not(.light-mode) .dark-card-wrapper article * {
        color: #FFFFFF !important;
    }
    html:not(.light-mode) .dark-card-wrapper article .text-\[\#3B82F6\],
    html:not(.light-mode) .dark-card-wrapper article .text-\[\#60A5FA\] { color: #60A5FA !important; }

    /* TEMA TERANG (KARTU POSTINGAN): SEMUA TEKS HITAM PEKAT */
    html.light-mode .dark-card-wrapper article,
    html.light-mode .dark-card-wrapper article * {
        color: #000000 !important;
    }
    html.light-mode .dark-card-wrapper article .text-\[\#3B82F6\],
    html.light-mode .dark-card-wrapper article .text-\[\#60A5FA\],
    html.light-mode .dark-card-wrapper article .text-\[\#112E81\] { color: #2563EB !important; }

    /* Badge Hashtag di Light Mode */
    html.light-mode .dark-card-wrapper article a[href*="/hashtag/"],
    html.light-mode .dark-card-wrapper article span[class*="bg-[#FACC15]"] {
        background-color: #DBEAFE !important;
        color: #1E3A8A !important; 
        border-color: #93C5FD !important;
    }

    /* ===== CUSTOM SCROLLBAR ===== */
    .custom-scrollbar::-webkit-scrollbar { height: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: var(--accent-blue); border-radius: 4px; }

    /* ===== INPUT SEARCH ===== */
    html.light-mode input[name="q"] { background-color: #FFFFFF !important; color: #000000 !important; border-color: #BFDBFE !important; }
    html.light-mode input[name="q"]::placeholder { color: #64748B !important; }
</style>

<div class="relative z-10 max-w-4xl mx-auto px-4 py-6">
    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="f-display text-3xl font-bold text-white tracking-tight flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-[#3B82F6] text-white flex items-center justify-center shadow-[3px_3px_0_#1E3A8A] border-[2px] border-[#1E3A8A] -rotate-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                Pencarian
            </h1>
            @if ($q !== '')
                {{-- Highlight Pencarian: Biru --}}
                <p class="text-sm font-medium text-slate-400 mt-3 ml-13">Hasil untuk "<strong class="text-[#3B82F6]">{{ $q }}</strong>"</p>
            @endif
        </div>
    </div>

    {{-- Search Form --}}
    <form method="GET" action="{{ route('social.search') }}" class="mb-8">
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-slate-500 group-focus-within:text-[#3B82F6] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" name="q" value="{{ $q }}" placeholder="Cari user, post, atau hashtag..." autocomplete="off" autofocus class="w-full bg-[#0F1F3D] border-2 border-[#1E3A8A] text-white pl-12 pr-28 py-4 rounded-2xl focus:outline-none focus:border-[#3B82F6] transition-all placeholder:text-slate-500 shadow-sm">
            <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-[#3B82F6] hover:bg-[#2563EB] text-white px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 shadow-sm border border-[#1E3A8A]">Cari</button>
        </div>
    </form>

    @if ($q === '')
        {{-- Empty State --}}
        <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-3xl p-12 text-center shadow-[5px_5px_0_#060D1F]">
            <div class="w-20 h-20 mx-auto bg-[#152D54] rounded-full flex items-center justify-center mb-4 border border-[#1E3A8A]">
                <svg class="w-10 h-10 text-[#3B82F6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16l2.879-2.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="f-display text-white font-bold text-xl mb-2">Mulai cari sesuatu</h3>
            <p class="text-slate-400 text-sm max-w-sm mx-auto mb-8 font-medium">Ketik nama user, username, kata di post, atau hashtag untuk menemukan apa yang kamu cari.</p>

            <div class="flex flex-wrap justify-center gap-2 text-sm">
                @foreach(['rani', 'diganova', 'festival', 'navoclash'] as $suggestion)
                    <a href="{{ route('social.search') }}?q={{ $suggestion }}" class="bg-[#152D54] hover:bg-[#1E3A8A] border-2 border-[#1E3A8A] text-[#3B82F6] hover:text-white px-4 py-2 rounded-xl font-bold transition-all duration-300 shadow-sm">
                        {{ $suggestion }}
                    </a>
                @endforeach
            </div>
        </div>
    @else
        {{-- Tabs --}}
        <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-2xl mb-6 overflow-x-auto custom-scrollbar shadow-[5px_5px_0_#060D1F]">
            <div class="flex min-w-max">
                @foreach ([
                    'all' => ['label' => 'Semua', 'count' => $users->count() + $posts->count() + $hashtags->count(), 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
                    'users' => ['label' => 'User', 'count' => $users->count(), 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                    'posts' => ['label' => 'Post', 'count' => $posts->count(), 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    'hashtags' => ['label' => 'Hashtag', 'count' => $hashtags->count(), 'icon' => 'M7 20l4-16m2 16l4-16M6 9h14M4 15h14'],
                ] as $key => $cfg)
                    <a href="{{ route('social.search') }}?q={{ urlencode($q) }}&tab={{ $key }}"
                       class="flex-1 flex items-center justify-center gap-2 px-6 py-4 text-sm font-bold border-b-4 transition-all duration-300 whitespace-nowrap
                              {{ $tab === $key ? 'border-[#3B82F6] text-[#3B82F6] bg-[#1E3A8A]/30' : 'text-slate-400 hover:bg-[#152D54] border-transparent hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $cfg['icon'] }}"></path>
                        </svg>
                        {{ $cfg['label'] }}
                        <span class="text-[10px] px-2 py-0.5 rounded-md font-bold {{ $tab === $key ? 'bg-[#3B82F6] text-white' : 'bg-[#152D54] text-slate-400 border border-[#1E3A8A]' }}">
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
                    {{-- Judul Section: Biru --}}
                    <h2 class="text-xs uppercase tracking-wider text-[#3B82F6] font-bold mb-3 ml-1 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        User
                    </h2>
                    <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-2xl overflow-hidden shadow-[5px_5px_0_#060D1F]">
                        @foreach ($users as $u)
                            <a href="{{ route('social.profile.show', $u->username) }}"
                               class="flex items-center gap-4 p-4 hover:bg-[#152D54] transition-colors border-b-2 border-[#1E3A8A]/50 last:border-0 group">
                                {{-- Avatar: Biru Solid (Bebas Gradient) --}}
                                <div class="w-12 h-12 rounded-full p-[2px] bg-[#3B82F6] flex-shrink-0 group-hover:rotate-6 transition-transform">
                                    <div class="w-full h-full rounded-full border-2 border-[#0F1F3D] overflow-hidden bg-[#0F1F3D]">
                                        <x-avatar :avatar="$u->avatar" :user="$u" size="w-full h-full" />
                                    </div>
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-white truncate group-hover:text-[#3B82F6] transition-colors">{{ $u->name }}</p>
                                    <p class="text-xs font-medium text-slate-400 truncate">{{ '@' . $u->username }}</p>
                                    @if ($u->bio)
                                        <p class="text-[11px] text-slate-500 mt-1 line-clamp-1">{{ $u->bio }}</p>
                                    @endif
                                </div>
                                
                                <span class="text-[11px] font-bold text-[#3B82F6] bg-[#152D54] px-3 py-1.5 rounded-lg border-2 border-[#1E3A8A] group-hover:bg-[#3B82F6] group-hover:text-white transition-all whitespace-nowrap">
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
                    <h2 class="text-xs uppercase tracking-wider text-[#3B82F6] font-bold mb-3 ml-1 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path></svg>
                        Hashtag
                    </h2>
                    <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-2xl overflow-hidden shadow-[5px_5px_0_#060D1F]">
                        @foreach ($hashtags as $tag)
                            <a href="{{ route('social.hashtag', $tag->tag) }}"
                               class="flex items-center gap-4 p-4 hover:bg-[#152D54] transition-colors border-b-2 border-[#1E3A8A]/50 last:border-0 group">
                                {{-- Hash Icon: Biru --}}
                                <div class="w-10 h-10 rounded-xl bg-[#1E3A8A] flex items-center justify-center text-[#3B82F6] font-bold flex-shrink-0 shadow-sm border border-[#3B82F6] group-hover:bg-[#3B82F6] group-hover:text-white transition-colors">
                                    <span class="text-lg">#</span>
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-white group-hover:text-[#3B82F6] transition-colors truncate">#{{ $tag->tag }}</p>
                                    <p class="text-[11px] font-medium text-slate-400 mt-0.5">{{ $tag->post_count }} post</p>
                                </div>
                                
                                <span class="text-[11px] font-bold text-[#3B82F6] bg-[#152D54] px-3 py-1.5 rounded-lg border-2 border-[#1E3A8A] group-hover:bg-[#3B82F6] group-hover:text-white transition-all whitespace-nowrap">
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
                    <h2 class="text-xs uppercase tracking-wider text-[#3B82F6] font-bold mb-3 ml-1 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Post
                    </h2>
                    <div class="space-y-6">
                        @foreach ($posts as $post)
                            <div class="dark-card-wrapper bg-[#0F1F3D] border-2 border-[#3B82F6] shadow-[0_0_0_4px_#1E3A8A] rounded-2xl overflow-hidden hover:border-[#60A5FA] transition-colors">
                                @include('social.posts._card', ['post' => $post])
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Global Empty State --}}
            @if ($users->isEmpty() && $posts->isEmpty() && $hashtags->isEmpty())
                <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-3xl p-12 text-center shadow-[5px_5px_0_#060D1F]">
                    <div class="w-20 h-20 mx-auto bg-[#152D54] rounded-full flex items-center justify-center mb-4 border border-[#1E3A8A]">
                        {{-- Ikon Kosong: Biru (Menggantikan Merah) --}}
                        <svg class="w-10 h-10 text-[#3B82F6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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