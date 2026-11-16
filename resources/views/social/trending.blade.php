@extends('social.layouts.app')

@section('title', 'Trending — DIGANOVA Social')

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
    html .bg-\[\#152D54\]\/50 { background-color: var(--bg-card-alt) !important; }

    /* ===== Override borders ===== */
    html .border-\[\#1E3A8A\] { border-color: var(--border-card) !important; }
    html .border-\[\#3B82F6\] { border-color: var(--accent-blue) !important; }
    html .border-\[\#22D3EE\] { border-color: var(--accent-blue) !important; }
    html .border-\[\#60A5FA\] { border-color: var(--accent-blue-soft) !important; }

    /* ===== GLOBAL TEXT OVERRIDES (HITAM & PUTIH) ===== */
    html .text-white, html .f-display, html h1, html h2, html h3, html h4, html p {
        color: var(--text-primary) !important;
    }
    html .text-slate-300, html .text-slate-400 { color: var(--text-muted) !important; }
    
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
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 relative z-10">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="f-display text-3xl font-bold text-white tracking-tight flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-[#3B82F6] text-white flex items-center justify-center shadow-[3px_3px_0_#1E3A8A] border-[2px] border-[#1E3A8A] -rotate-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </span>
                Trending
            </h1>
            <p class="text-sm font-medium text-slate-400 mt-2 ml-12">Hashtag populer dalam 24 jam terakhir</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-[#0F1F3D] border-[3px] border-[#1E3A8A] rounded-[2rem] p-6 shadow-[5px_5px_0_#060D1F] relative overflow-hidden">
                <div class="flex items-center gap-2 mb-1 relative z-10">
                    <svg class="w-5 h-5 text-[#3B82F6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path></svg>
                    <h2 class="f-display font-bold text-white text-xl">Post Terbaru</h2>
                </div>
                <p class="text-xs font-medium text-slate-400 mb-6 relative z-10 ml-7">Update 24 jam terakhir</p>

                @if ($recentPosts->isEmpty())
                    <div class="text-center py-12 bg-[#152D54]/50 rounded-2xl border-[2px] border-[#1E3A8A] relative z-10">
                        <div class="w-20 h-20 mx-auto bg-[#152D54] rounded-full flex items-center justify-center mb-5 border-[3px] border-[#1E3A8A] shadow-inner">
                            <svg class="w-10 h-10 text-[#3B82F6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        </div>
                        <h3 class="f-display text-white font-bold text-xl mb-2">Belum ada post dalam 24 jam</h3>
                        <p class="text-sm text-slate-400 font-medium">Jadilah yang pertama posting hari ini!</p>
                        @auth
                            <a href="{{ route('social.posts.index') }}" class="inline-flex items-center gap-2 mt-6 bg-[#3B82F6] border-[3px] border-[#1E3A8A] text-white px-6 py-2.5 rounded-xl text-sm font-black transition-all shadow-[3px_3px_0_#1E3A8A] hover:-translate-y-1 hover:shadow-[5px_5px_0_#1E3A8A]">Buat Postingan</a>
                        @endauth
                    </div>
                @else
                    <div class="space-y-8 mt-4">
                        @foreach ($recentPosts as $post)
                            <div class="relative mt-4">
                                <div class="relative bg-[#0F1F3D] border-[3px] border-[#1E3A8A] rounded-[26px] p-1.5 shadow-[5px_5px_0_#060D1F] hover:-translate-y-1 transition-transform duration-300">
                                    <!-- Sudut Siku Biru -->
                                    <div class="absolute top-0 left-0 w-4 h-4 border-t-2 border-l-2 border-[#3B82F6] rounded-tl-xl z-20 pointer-events-none"></div>
                                    <div class="absolute top-0 right-0 w-4 h-4 border-t-2 border-r-2 border-[#3B82F6] rounded-tr-xl z-20 pointer-events-none"></div>
                                    <div class="absolute bottom-0 left-0 w-4 h-4 border-b-2 border-l-2 border-[#3B82F6] rounded-bl-xl z-20 pointer-events-none"></div>
                                    <div class="absolute bottom-0 right-0 w-4 h-4 border-b-2 border-r-2 border-[#3B82F6] rounded-br-xl z-20 pointer-events-none"></div>
                                    
                                    <div class="dark-card-wrapper rounded-[20px] overflow-hidden relative z-10">
                                        @include('social.posts._card', ['post' => $post])
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <aside class="lg:col-span-1">
            <div class="bg-[#0F1F3D] border-[3px] border-[#3B82F6] rounded-[2rem] rounded-tr-md p-6 shadow-[5px_5px_0_#060D1F] sticky top-24 relative overflow-hidden">
                <div class="flex items-center gap-2 mb-6 relative z-10">
                    <span class="bg-[#3B82F6] p-1.5 rounded-lg border-[2px] border-[#1E3A8A]">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    </span>
                    <h2 class="f-display font-bold text-white text-xl tracking-wide">Trending</h2>
                </div>

                @if ($trending->isEmpty())
                    <p class="text-sm font-medium text-slate-400 text-center py-6 bg-[#152D54] rounded-xl border-[2px] border-[#1E3A8A] relative z-10">Belum ada hashtag trending</p>
                @else
                    <div class="space-y-3 relative z-10">
                        @foreach ($trending as $i => $tag)
                            <a href="{{ route('social.hashtag', $tag->tag) }}" class="block p-3 rounded-2xl bg-[#152D54] border-[2px] border-[#1E3A8A] hover:border-[#3B82F6] hover:-translate-y-1 hover:shadow-[3px_3px_0_#1E3A8A] transition-all duration-300 group/tag">
                                <div class="flex items-center gap-3">
                                    <span class="f-display text-lg font-bold w-6 text-center transition-all duration-300 {{ $i == 0 ? 'text-[#3B82F6] group-hover/tag:scale-110' : '' }} {{ $i == 1 ? 'text-[#60A5FA] group-hover/tag:scale-110' : '' }} {{ $i > 1 ? 'text-slate-500' : '' }}">{{ $i + 1 }}</span>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-black text-[#3B82F6] group-hover/tag:text-white transition-colors text-sm truncate">#{{ $tag->tag }}</p>
                                        <div class="flex items-center gap-1.5 text-[11px] font-medium text-slate-400 mt-1">
                                            <span class="flex items-center gap-1"><svg class="w-3 h-3 text-[#3B82F6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>{{ $tag->recent_count }} post</span>
                                            <span class="text-slate-600">•</span>
                                            <span>{{ $tag->post_count }} total</span>
                                        </div>
                                    </div>
                                    <div class="w-7 h-7 rounded-lg bg-[#0F1F3D] flex items-center justify-center group-hover/tag:bg-[#3B82F6] transition-colors border-[2px] border-transparent group-hover/tag:border-[#1E3A8A]">
                                        <svg class="w-4 h-4 text-slate-400 group-hover/tag:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </aside>
    </div>
</div>

@endsection