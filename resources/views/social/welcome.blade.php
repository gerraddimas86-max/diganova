@extends('social.layouts.app')

@section('title', 'Beranda — DIGANOVA Social')

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
        --accent-cyan: #22D3EE;
        --shadow-color: #1F1F1F;
    }
    
    html.light-mode {
        --bg-page: #FFFFFF;
        --bg-card: #EFF6FF;
        --bg-card-alt: #DBEAFE;
        --bg-inner: #FFFFFF;
        --border-card: #BFDBFE;
        --border-strong: #93C5FD;
        
        /* Warna Teks Tema Terang (Hitam) */
        --text-primary: #000000;
        --text-secondary: #334155;
        --text-muted: #64748B;
        
        --accent-blue: #2563EB; 
        --accent-blue-soft: #3B82F6;
        --accent-cyan: #0891B2;
        --shadow-color: #BFDBFE;
    }

    body { background-color: var(--bg-page) !important; color: var(--text-primary); }

    /* Animasi Playful */
    @keyframes float-icon { 0%, 100% { transform: translateY(0) rotate(-6deg); } 50% { transform: translateY(-6px) rotate(6deg); } }
    .animate-float-icon { animation: float-icon 4s ease-in-out infinite; }
    @keyframes wobble { 0%, 100% { transform: rotate(0deg); } 25% { transform: rotate(3deg); } 75% { transform: rotate(-3deg); } }
    .hover-wobble:hover { animation: wobble 0.4s ease-in-out; }

    /* ===== Override Tailwind backgrounds ===== */
    html .bg-\[\#0F1F3D\] { background-color: var(--bg-card) !important; }
    html .bg-\[\#152D54\] { background-color: var(--bg-card-alt) !important; }
    html .bg-\[\#060D1F\] { background-color: var(--bg-inner) !important; }
    html .bg-\[\#1E3A8A\]\/50 { background-color: var(--bg-card-alt) !important; }

    /* ===== Override borders ===== */
    html .border-\[\#1E3A8A\] { border-color: var(--border-card) !important; }
    html .border-\[\#3B82F6\] { border-color: var(--accent-blue) !important; }
    html .border-\[\#22D3EE\] { border-color: var(--accent-blue) !important; }
    html .border-\[\#60A5FA\] { border-color: var(--accent-blue-soft) !important; }

    /* ===== GLOBAL TEXT OVERRIDES (HITAM & PUTIH) ===== */
    html .text-white, html .f-display, html h1, html h2, html h3, html h4, html p {
        color: var(--text-primary) !important;
    }

    /* ===== PROTEKSI WARNA (TOMBOL & AKSEN) ===== */
    /* Agar teks di atas background biru tetap putih di light mode */
    html.light-mode .bg-\[\#3B82F6\], html.light-mode .bg-\[\#3B82F6\] *,
    html.light-mode .bg-\[\#2563EB\], html.light-mode .bg-\[\#2563EB\] *,
    html.light-mode .bg-\[\#1E3A8A\], html.light-mode .bg-\[\#1E3A8A\] *,
    html.light-mode .group\/name .text-white {
        color: #FFFFFF !important;
    }

    /* Teks Aksen Biru */
    html.light-mode .text-\[\#3B82F6\], html.light-mode .text-\[\#60A5FA\], html.light-mode .text-\[\#2563EB\] { color: #2563EB !important; }
    html:not(.light-mode) .text-\[\#3B82F6\], html:not(.light-mode) .text-\[\#60A5FA\] { color: #60A5FA !important; }

    /* ===== Override shadows ===== */
    html .shadow-\[6px_6px_0_\#1E3A8A\] { box-shadow: 6px 6px 0 var(--shadow-color) !important; }
    html .shadow-\[5px_5px_0_\#1E3A8A\] { box-shadow: 5px 5px 0 var(--shadow-color) !important; }
    html .shadow-\[4px_4px_0_\#1E3A8A\] { box-shadow: 4px 4px 0 var(--shadow-color) !important; }
    html .shadow-\[3px_3px_0_\#1E3A8A\] { box-shadow: 3px 3px 0 var(--shadow-color) !important; }
    html .shadow-\[2px_2px_0_\#1E3A8A\] { box-shadow: 2px 2px 0 var(--shadow-color) !important; }
    html .shadow-\[4px_4px_0_\#060D1F\] { box-shadow: 4px 4px 0 var(--shadow-color) !important; }
    html .hover\:shadow-\[6px_6px_0_\#1E3A8A\]:hover { box-shadow: 6px 6px 0 var(--shadow-color) !important; }
    html .hover\:shadow-\[5px_5px_0_\#1E3A8A\]:hover { box-shadow: 5px 5px 0 var(--shadow-color) !important; }
    html .hover\:shadow-\[5px_5px_0_\#060D1F\]:hover { box-shadow: 5px 5px 0 var(--shadow-color) !important; }
    html .hover\:shadow-\[3px_3px_0_\#060D1F\]:hover { box-shadow: 3px 3px 0 var(--shadow-color) !important; }

    /* ===== Wrapper tanpa gradient ===== */
    .hero-wrap { background-color: var(--accent-blue); }
    .post-wrap { background-color: var(--border-strong); }

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
    html:not(.light-mode) .dark-card-wrapper article .text-\[\#60A5FA\] {
        color: #60A5FA !important; /* Biarkan link biru tetap biru */
    }
    html:not(.light-mode) .dark-card-wrapper article .text-\[\#FACC15\] {
        color: #FACC15 !important; /* Biarkan kuning tetap kuning */
    }

    /* TEMA TERANG (KARTU POSTINGAN): SEMUA TEKS HITAM PEKAT */
    html.light-mode .dark-card-wrapper article,
    html.light-mode .dark-card-wrapper article * {
        color: #000000 !important;
    }
    html.light-mode .dark-card-wrapper article .text-\[\#3B82F6\],
    html.light-mode .dark-card-wrapper article .text-\[\#60A5FA\],
    html.light-mode .dark-card-wrapper article .text-\[\#112E81\] {
        color: #2563EB !important; /* Biarkan link biru tetap biru */
    }

    /* Badge Hashtag di Light Mode */
    html.light-mode .dark-card-wrapper article a[href*="/hashtag/"],
    html.light-mode .dark-card-wrapper article span[class*="bg-[#FACC15]"] {
        background-color: #DBEAFE !important;
        color: #1E3A8A !important; 
        border-color: #93C5FD !important;
    }

    /* Tombol Like/Koment/Share di Light Mode */
    html.light-mode .dark-card-wrapper article button {
        background-color: #F1F5F9 !important;
        border-color: #E2E8F0 !important;
        color: #000000 !important;
    }
    /* Tombol Like/Koment/Share di Dark Mode */
    html:not(.light-mode) .dark-card-wrapper article button {
        background-color: transparent !important;
        border-color: #3F3F3F !important;
        color: #FFFFFF !important;
    }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-8">
        <!-- ============ HERO CARD ============ -->
        <div class="hero-wrap relative overflow-hidden rounded-[2rem] p-[3px] shadow-[6px_6px_0_#1E3A8A] hover:-translate-y-1 transition-transform duration-300 group">
            <div class="relative bg-[#0F1F3D] rounded-[29px] p-6 sm:p-8 overflow-hidden">
                <div class="relative z-10 flex flex-col sm:flex-row items-center sm:items-start justify-between gap-6">
                    <div class="text-center sm:text-left">
                        <div class="inline-flex items-center gap-2 bg-[#1E3A8A]/50 border-2 border-[#3B82F6] px-4 py-1.5 rounded-full text-xs font-black text-[#60A5FA] mb-5">
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#3B82F6] opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#3B82F6]"></span>
                            </span>
                            ONLINE
                        </div>

                        <h1 class="f-display text-3xl sm:text-4xl lg:text-5xl font-bold mb-4 text-white flex flex-col sm:flex-row items-center sm:items-start gap-3 flex-wrap">
                            Halo,
                            <span class="relative inline-block group/name cursor-pointer transition-all duration-300 ease-out hover:-translate-y-2 hover:scale-105 hover:rotate-1 active:translate-y-0 active:scale-95">
                                <span class="relative z-10 text-white px-4 py-0.5 font-extrabold inline-block transition-colors duration-300">
                                    {{ $user->name }}!
                                </span>
                                <span class="absolute inset-0 bg-[#3B82F6] rounded-xl transition-all duration-300"></span>
                            </span>

                            <div class="hidden sm:inline-flex items-center justify-center ml-1 animate-float-icon">
                                <div class="relative w-12 h-12 bg-[#22D3EE] rounded-full border-[3px] border-[#1E3A8A] flex items-center justify-center shadow-[3px_3px_0_#1E3A8A]">
                                    <svg class="w-6 h-6 text-[#1E3A8A]" fill="currentColor" viewBox="0 0 24 24"><path d="M9 4.5a.75.75 0 01.721.544l.813 2.846a3.75 3.75 0 002.576 2.576l2.846.813a.75.75 0 010 1.442l-2.846.813a3.75 3.75 0 00-2.576 2.576l-.813 2.846a.75.75 0 01-1.442 0l-.813-2.846a3.75 3.75 0 00-2.576-2.576l-2.846-.813a.75.75 0 010-1.442l2.846-.813A3.75 3.75 0 007.466 7.89l.813-2.846A.75.75 0 019 4.5z" /></svg>
                                </div>
                            </div>
                        </h1>

                        <p class="text-slate-300 text-sm sm:text-base max-w-md mt-2 font-medium leading-relaxed">Selamat datang kembali di DIGANOVA. Ada hal menarik apa yang ingin kamu bagikan hari ini?</p>

                        <div class="mt-7 flex flex-wrap justify-center sm:justify-start gap-4">
                            <a href="{{ route('social.posts.index') }}" class="hover-wobble inline-flex items-center gap-2 bg-[#3B82F6] text-white hover:-translate-y-1 px-6 py-3 rounded-xl font-black text-sm transition-all duration-300 border-[3px] border-[#1E3A8A] shadow-[4px_4px_0_#1E3A8A] hover:shadow-[6px_6px_0_#1E3A8A]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Buat Postingan
                            </a>
                            <a href="{{ route('social.profile.show', $user->username) }}" class="hover-wobble inline-flex items-center gap-2 bg-white text-[#1E3A8A] hover:-translate-y-1 px-6 py-3 rounded-xl font-black text-sm transition-all duration-300 border-[3px] border-[#1E3A8A] shadow-[4px_4px_0_#1E3A8A] hover:shadow-[6px_6px_0_#1E3A8A]">
                                Lihat Profil
                            </a>
                        </div>
                    </div>

                    <div class="hidden md:flex flex-col gap-4 min-w-[140px]">
                        <a href="{{ route('social.profile.show', $user->username) }}" class="block bg-[#152D54] border-[3px] border-[#3B82F6] rounded-[1.5rem] p-4 text-center shadow-[4px_4px_0_#1E3A8A] -rotate-2 hover:rotate-0 hover:-translate-y-1 hover:shadow-[5px_5px_0_#060D1F] transition-all cursor-pointer">
                            <p class="text-[10px] text-[#60A5FA] font-black uppercase tracking-wider">Postingan</p>
                            <p class="f-display text-3xl font-bold text-white mt-1">{{ $stats['posts'] }}</p>
                        </a>
                        <a href="{{ route('social.profile.show', $user->username) }}" class="block bg-[#152D54] border-[3px] border-[#3B82F6] rounded-[1.5rem] p-4 text-center shadow-[4px_4px_0_#1E3A8A] rotate-2 hover:rotate-0 hover:-translate-y-1 hover:shadow-[5px_5px_0_#060D1F] transition-all cursor-pointer">
                            <p class="text-[10px] text-[#60A5FA] font-black uppercase tracking-wider">Pengikut</p>
                            <p class="f-display text-3xl font-bold text-white mt-1">{{ $stats['followers'] }}</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============ RECENT POSTS ============ -->
        <div>
            <div class="flex justify-between items-end mb-6">
                <div>
                    <h2 class="f-display font-bold text-white text-3xl tracking-tight flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-[#3B82F6] text-white flex items-center justify-center shadow-[3px_3px_0_#1E3A8A] border-[2px] border-[#1E3A8A] rotate-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </span>
                        Aktivitas Temanmu
                    </h2>
                </div>
                <a href="{{ route('social.posts.index') }}" class="text-sm font-black text-white bg-[#3B82F6] border-[3px] border-[#1E3A8A] px-5 py-2 rounded-xl shadow-[3px_3px_0_#1E3A8A] hover:-translate-y-1 hover:shadow-[5px_5px_0_#1E3A8A] transition-all">
                    Lihat Semua
                </a>
            </div>

            @if ($recentPosts->isEmpty())
                <div class="bg-[#0F1F3D] border-[3px] border-[#3B82F6] rounded-3xl p-10 text-center shadow-[5px_5px_0_#1E3A8A] relative overflow-hidden">
                    <div class="w-24 h-24 mx-auto bg-[#152D54] rounded-full flex items-center justify-center mb-5 border-[3px] border-[#1E3A8A]">
                        <svg class="w-12 h-12 text-[#3B82F6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    </div>
                    <h3 class="f-display text-white font-bold text-2xl mb-2">Belum ada aktivitas nih!</h3>
                    <p class="text-slate-400 text-sm font-medium">Follow lebih banyak orang atau mulai bagikan postingan pertamamu!</p>
                </div>
            @else
                <div class="space-y-8">
                    @foreach ($recentPosts as $post)
                        <div class="relative mt-4">
                            <div class="post-wrap relative p-[2px] rounded-[26px] shadow-[4px_4px_0_#060D1F]">
                                <div class="relative bg-[#060D1F] rounded-[24px] p-1.5 overflow-hidden">
                                    <div class="dark-card-wrapper rounded-[20px] overflow-hidden relative z-10">
                                        @include('social.posts._card', ['post' => $post])
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- ============ SIDEBAR ============ -->
    <div class="space-y-6">
        <!-- Stats -->
        <div class="bg-[#0F1F3D] border-[3px] border-[#3B82F6] rounded-[2rem] rounded-tr-md p-6 shadow-[5px_5px_0_#1E3A8A] relative group/stat">
            <div class="flex items-center justify-between mb-5">
                <h3 class="f-display font-bold text-white text-xl flex items-center gap-2">
                    <span class="bg-[#3B82F6] p-1.5 rounded-lg border-[2px] border-[#1E3A8A] group-hover/stat:rotate-12 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </span>
                    Statistikmu
                </h3>
                <a href="{{ route('social.profile.show', $user->username) }}" class="text-[10px] font-black text-[#3B82F6] hover:text-white bg-[#152D54] px-3 py-1.5 rounded-full border border-[#3B82F6]/40 hover:bg-[#3B82F6] hover:border-[#3B82F6] transition-all">Detail</a>
            </div>

            <div class="grid grid-cols-3 gap-3 text-center">
                <a href="{{ route('social.profile.show', $user->username) }}" class="bg-[#152D54] rounded-2xl p-3 border-[2px] border-[#1E3A8A] hover:bg-[#1E3A8A] hover:-translate-y-1 hover:shadow-[3px_3px_0_#060D1F] transition-all cursor-pointer block">
                    <p class="f-display text-2xl font-bold text-[#3B82F6]">{{ $stats['posts'] }}</p>
                    <p class="text-[9px] text-slate-300 uppercase tracking-wider font-bold mt-1">Posts</p>
                </a>
                <a href="{{ route('social.profile.show', $user->username) }}" class="bg-[#152D54] rounded-2xl p-3 border-[2px] border-[#1E3A8A] hover:bg-[#1E3A8A] hover:-translate-y-1 hover:shadow-[3px_3px_0_#060D1F] transition-all cursor-pointer block">
                    <p class="f-display text-2xl font-bold text-[#3B82F6]">{{ $stats['followers'] }}</p>
                    <p class="text-[9px] text-slate-300 uppercase tracking-wider font-bold mt-1">Followers</p>
                </a>
                <a href="{{ route('social.profile.show', $user->username) }}" class="bg-[#152D54] rounded-2xl p-3 border-[2px] border-[#1E3A8A] hover:bg-[#1E3A8A] hover:-translate-y-1 hover:shadow-[3px_3px_0_#060D1F] transition-all cursor-pointer block">
                    <p class="f-display text-2xl font-bold text-[#3B82F6]">{{ $stats['following'] }}</p>
                    <p class="text-[9px] text-slate-300 uppercase tracking-wider font-bold mt-1">Following</p>
                </a>
            </div>
        </div>

        <!-- Trending -->
        <div class="bg-[#0F1F3D] border-[3px] border-[#3B82F6] rounded-[2rem] rounded-tl-md p-6 shadow-[5px_5px_0_#1E3A8A]">
            <div class="flex items-center justify-between mb-5">
                <h3 class="f-display font-bold text-white text-xl flex items-center gap-2">
                    <span class="bg-[#3B82F6] p-1.5 rounded-lg border-[2px] border-[#1E3A8A]">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                    </span>
                    Trending
                </h3>
                <a href="{{ route('social.trending') }}" class="text-[10px] font-black text-[#3B82F6] hover:text-white bg-[#152D54] px-3 py-1.5 rounded-full border border-[#3B82F6]/40 hover:bg-[#3B82F6] hover:border-[#3B82F6] transition-all">Lihat Semua</a>
            </div>

            @if ($trendingHashtags->isEmpty())
                <p class="text-sm text-slate-400 text-center font-medium">Belum ada topik yang trending.</p>
            @else
                <div class="space-y-3">
                    @foreach ($trendingHashtags as $tag)
                        <a href="{{ route('social.hashtag', $tag->tag) }}" class="flex justify-between items-center group p-3 rounded-2xl bg-[#152D54] border-[2px] border-[#1E3A8A] hover:border-[#3B82F6] hover:-translate-y-1 hover:shadow-[3px_3px_0_#1E3A8A] transition-all duration-300">
                            <div>
                                <p class="font-black text-[#3B82F6] group-hover:text-white transition-colors text-sm">#{{ $tag->tag }}</p>
                                <p class="text-[11px] text-slate-400 font-medium mt-0.5">{{ $tag->recent_count }} post hari ini</p>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-[#0F1F3D] flex items-center justify-center group-hover:bg-[#3B82F6] transition-colors border-[2px] border-transparent group-hover:border-[#1E3A8A]">
                                <svg class="w-4 h-4 text-slate-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Suggested -->
        <div class="bg-[#0F1F3D] border-[3px] border-[#3B82F6] rounded-[2rem] rounded-bl-md p-6 shadow-[5px_5px_0_#1E3A8A]">
            <div class="flex items-center justify-between mb-5">
                <h3 class="f-display font-bold text-white text-xl flex items-center gap-2">
                    <span class="bg-[#3B82F6] p-1.5 rounded-lg border-[2px] border-[#1E3A8A]">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </span>
                    Saran
                </h3>
                <a href="{{ route('social.search') }}" class="text-[10px] font-black text-[#3B82F6] hover:text-white bg-[#152D54] px-3 py-1.5 rounded-full border border-[#3B82F6]/40 hover:bg-[#3B82F6] hover:border-[#3B82F6] transition-all">Cari Lain</a>
            </div>

            @if ($suggestedUsers->isEmpty())
                <p class="text-sm text-slate-400 text-center font-medium">Kamu sudah mengikuti semua orang!</p>
            @else
                <div class="space-y-4">
                    @foreach ($suggestedUsers as $sugUser)
                        <div class="flex items-center gap-3 group">
                            <a href="{{ route('social.profile.show', $sugUser->username) }}" class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-full p-[3px] bg-[#3B82F6] group-hover:rotate-12 transition-transform shadow-sm">
                                    <div class="w-full h-full rounded-full border-2 border-[#0F1F3D] overflow-hidden bg-[#152D54]">
                                        <x-avatar :avatar="$sugUser->avatar" :user="$sugUser" size="w-full h-full" />
                                    </div>
                                </div>
                            </a>
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('social.profile.show', $sugUser->username) }}" class="font-black text-white hover:text-[#3B82F6] transition-colors text-sm block truncate">{{ $sugUser->name }}</a>
                                <p class="text-[11px] text-slate-400 truncate mt-0.5 font-medium">{{ '@' . $sugUser->username }}</p>
                            </div>
                            <a href="{{ route('social.profile.show', $sugUser->username) }}" class="text-[11px] font-black text-white bg-[#3B82F6] border-[2px] border-[#1E3A8A] px-3 py-1.5 rounded-lg shadow-[2px_2px_0_#1E3A8A] hover:-translate-y-0.5 hover:shadow-[3px_3px_0_#1E3A8A] transition-all">Ikuti</a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@endsection