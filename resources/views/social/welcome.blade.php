@extends('social.layouts.app')

@section('title', 'Beranda — DIGANOVA Social')

@section('content')

<style>
    /* ============================================================
       CUSTOM STYLE — Animasi & Override Card tema Fun/Dark Navy
       ============================================================ */
    
    /* Animasi Playful */
    @keyframes float-icon {
        0%, 100% { transform: translateY(0) rotate(-6deg); }
        50% { transform: translateY(-6px) rotate(6deg); }
    }
    .animate-float-icon { animation: float-icon 4s ease-in-out infinite; }

    @keyframes wobble {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(3deg); }
        75% { transform: rotate(-3deg); }
    }
    .hover-wobble:hover { animation: wobble 0.4s ease-in-out; }

    /* Override Card Postingan (Gaya Fun & Tebal) */
    .dark-card-wrapper article {
        background: #0F1F3D !important;
        border: 3px solid #3B82F6 !important;
        box-shadow: 5px 5px 0 #1E3A8A !important;
        border-radius: 20px !important;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .dark-card-wrapper article:hover {
        transform: translateY(-4px);
        box-shadow: 7px 7px 0 #1E3A8A !important;
    }
    .dark-card-wrapper h3,
    .dark-card-wrapper .text-\[\#000000\] { color: #F1F5F9 !important; }
    .dark-card-wrapper .text-gray-500,
    .dark-card-wrapper .text-gray-600 { color: #94A3B8 !important; }
    .dark-card-wrapper .text-\[\#112E81\] { color: #FACC15 !important; } /* Hashtag jadi kuning */
    .dark-card-wrapper .bg-\[\#F8FAFC\] { background-color: #152D54 !important; }
    .dark-card-wrapper .border-gray-100 { border-color: rgba(96, 165, 250, 0.3) !important; }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- ============================================ --}}
    {{-- KOLOM KIRI: Profil & Feed                     --}}
    {{-- ============================================ --}}
    <div class="lg:col-span-2 space-y-8">

        {{-- ---------- Banner Sapaan (Fun & Colorful) ---------- --}}
        <div class="relative overflow-hidden rounded-[2rem] p-[3px] bg-gradient-to-br from-[#FACC15] via-[#22D3EE] to-[#3B82F6] shadow-[6px_6px_0_#1E3A8A] hover:-translate-y-1 transition-transform duration-300 group">
            <div class="relative bg-[#0F1F3D] rounded-[29px] p-6 sm:p-8 overflow-hidden">
                
                {{-- Dekorasi Dalam Card --}}
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-[#3B82F6] rounded-full blur-[40px] opacity-20 group-hover:opacity-40 transition-opacity"></div>
                <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-[#FACC15] rounded-full blur-[40px] opacity-10"></div>

                <div class="relative z-10 flex flex-col sm:flex-row items-center sm:items-start justify-between gap-6">
                    <div class="text-center sm:text-left">
                        {{-- Badge Online --}}
                        <div class="inline-flex items-center gap-2 bg-[#1E3A8A]/50 border-2 border-[#FACC15] px-4 py-1.5 rounded-full text-xs font-black text-[#FACC15] mb-5 shadow-[0_0_15px_rgba(250,204,21,0.2)]">
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#FACC15] opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#FACC15]"></span>
                            </span>
                            ONLINE
                        </div>

                        {{-- Teks Sapaan & Animasi Nama --}}
                        <h1 class="f-display text-3xl sm:text-4xl lg:text-5xl font-bold mb-4 text-white flex flex-col sm:flex-row items-center sm:items-start gap-3 flex-wrap drop-shadow-md">
                            Halo, 
                            
                            {{-- EFEK TIMBUL PADA NAMA --}}
                            <span class="relative inline-block group/name cursor-pointer transition-all duration-300 ease-out hover:-translate-y-2 hover:scale-105 hover:rotate-1 active:translate-y-0 active:scale-95">
                                <span class="relative z-10 text-[#1E3A8A] px-4 py-0.5 font-extrabold inline-block transition-colors duration-300">
                                    {{ $user->name }}!
                                </span>
                                <span class="absolute inset-0 bg-[#FACC15] rounded-xl border-[3px] border-transparent group-hover/name:border-[#1E3A8A] group-hover/name:shadow-[5px_5px_0_#1E3A8A] transition-all duration-300"></span>
                            </span>

                            <div class="hidden sm:inline-flex items-center justify-center ml-1 animate-float-icon">
                                <div class="relative w-12 h-12 bg-[#22D3EE] rounded-full border-[3px] border-[#1E3A8A] flex items-center justify-center shadow-[3px_3px_0_#1E3A8A]">
                                    <svg class="w-6 h-6 text-[#1E3A8A]" fill="currentColor" viewBox="0 0 24 24"><path d="M9 4.5a.75.75 0 01.721.544l.813 2.846a3.75 3.75 0 002.576 2.576l2.846.813a.75.75 0 010 1.442l-2.846.813a3.75 3.75 0 00-2.576 2.576l-.813 2.846a.75.75 0 01-1.442 0l-.813-2.846a3.75 3.75 0 00-2.576-2.576l-2.846-.813a.75.75 0 010-1.442l2.846-.813A3.75 3.75 0 007.466 7.89l.813-2.846A.75.75 0 019 4.5z" /></svg>
                                </div>
                            </div>
                        </h1>

                        <p class="text-slate-300 text-sm sm:text-base max-w-md mt-2 font-medium leading-relaxed">Selamat datang kembali di DIGANOVA. Ada hal menarik apa yang ingin kamu bagikan hari ini?</p>

                        {{-- Tombol Aksi --}}
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

                    {{-- Statistik Melayang (Bisa Diklik) --}}
                    <div class="hidden md:flex flex-col gap-4 min-w-[140px]">
                        {{-- Klik Postingan --}}
                        <a href="{{ route('social.profile.show', $user->username) }}" class="block bg-[#152D54] border-[3px] border-[#FACC15] rounded-[1.5rem] p-4 text-center shadow-[4px_4px_0_#1E3A8A] -rotate-2 hover:rotate-0 hover:-translate-y-1 hover:shadow-[5px_5px_0_#060D1F] transition-all cursor-pointer">
                            <p class="text-[10px] text-[#FACC15] font-black uppercase tracking-wider">Postingan</p>
                            <p class="f-display text-3xl font-bold text-white mt-1">{{ $stats['posts'] }}</p>
                        </a>
                        {{-- Klik Pengikut --}}
                        <a href="{{ route('social.profile.show', $user->username) }}" class="block bg-[#152D54] border-[3px] border-[#22D3EE] rounded-[1.5rem] p-4 text-center shadow-[4px_4px_0_#1E3A8A] rotate-2 hover:rotate-0 hover:-translate-y-1 hover:shadow-[5px_5px_0_#060D1F] transition-all cursor-pointer">
                            <p class="text-[10px] text-[#22D3EE] font-black uppercase tracking-wider">Pengikut</p>
                            <p class="f-display text-3xl font-bold text-white mt-1">{{ $stats['followers'] }}</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ---------- Recent Posts ---------- --}}
        <div>
            <div class="flex justify-between items-end mb-6">
                <div>
                    <h2 class="f-display font-bold text-white text-3xl tracking-tight flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-[#FACC15] text-[#1E3A8A] flex items-center justify-center shadow-[3px_3px_0_#1E3A8A] border-[2px] border-[#1E3A8A] rotate-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </span>
                        Aktivitas Temanmu
                    </h2>
                </div>
                <a href="{{ route('social.posts.index') }}" class="text-sm font-black text-[#1E3A8A] bg-[#FACC15] border-[3px] border-[#1E3A8A] px-5 py-2 rounded-xl shadow-[3px_3px_0_#1E3A8A] hover:-translate-y-1 hover:shadow-[5px_5px_0_#1E3A8A] transition-all">
                    Lihat Semua
                </a>
            </div>

            @if ($recentPosts->isEmpty())
                {{-- Empty state --}}
                <div class="bg-[#0F1F3D] border-[3px] border-[#3B82F6] rounded-3xl p-10 text-center shadow-[5px_5px_0_#1E3A8A] relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-[#3B82F6] blur-[40px] opacity-20"></div>
                    <div class="w-24 h-24 mx-auto bg-[#152D54] rounded-full flex items-center justify-center mb-5 border-[3px] border-[#1E3A8A] shadow-inner">
                        <svg class="w-12 h-12 text-[#FACC15]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    </div>
                    <h3 class="f-display text-white font-bold text-2xl mb-2">Belum ada aktivitas nih!</h3>
                    <p class="text-slate-400 text-sm font-medium">Follow lebih banyak orang atau mulai bagikan postingan pertamamu!</p>
                </div>
            @else
                {{-- List Postingan --}}
                <div class="space-y-8">
                    @foreach ($recentPosts as $post)
                        <div class="relative mt-4">

                            {{-- Bingkai Luar (Garis Neon Gradient) --}}
                            <div class="relative p-[2px] bg-gradient-to-br from-[#60A5FA] via-[#3B82F6] to-[#1E3A8A] rounded-[26px] shadow-[4px_4px_0_#060D1F]">
                                <div class="relative bg-[#060D1F] rounded-[24px] p-1.5 overflow-hidden">
                                    <div class="absolute top-0 left-0 w-4 h-4 border-t-2 border-l-2 border-[#60A5FA] rounded-tl-xl z-20 pointer-events-none"></div>
                                    <div class="absolute top-0 right-0 w-4 h-4 border-t-2 border-r-2 border-[#60A5FA] rounded-tr-xl z-20 pointer-events-none"></div>
                                    <div class="absolute bottom-0 left-0 w-4 h-4 border-b-2 border-l-2 border-[#60A5FA] rounded-bl-xl z-20 pointer-events-none"></div>
                                    <div class="absolute bottom-0 right-0 w-4 h-4 border-b-2 border-r-2 border-[#60A5FA] rounded-br-xl z-20 pointer-events-none"></div>
                                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-3/4 h-[2px] bg-gradient-to-r from-transparent via-[#FACC15] to-transparent opacity-80 z-20"></div>
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

    {{-- ============================================ --}}
    {{-- KOLOM KANAN: Sidebar (Semua Bisa Diklik)      --}}
    {{-- ============================================ --}}
    <div class="space-y-6">

        {{-- ---------- Statistik Card (Bisa Diklik) ---------- --}}
        <div class="bg-[#0F1F3D] border-[3px] border-[#22D3EE] rounded-[2rem] rounded-tr-md p-6 shadow-[5px_5px_0_#1E3A8A] relative group/stat">
            <div class="flex items-center justify-between mb-5">
                <h3 class="f-display font-bold text-white text-xl flex items-center gap-2">
                    <span class="bg-[#22D3EE] p-1.5 rounded-lg border-[2px] border-[#1E3A8A] group-hover/stat:rotate-12 transition-transform">
                        <svg class="w-5 h-5 text-[#1E3A8A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </span>
                    Statistikmu
                </h3>
                <a href="{{ route('social.profile.show', $user->username) }}" class="text-[10px] font-black text-[#22D3EE] hover:text-white bg-[#152D54] px-3 py-1.5 rounded-full border border-[#22D3EE]/40 hover:bg-[#22D3EE] hover:border-[#22D3EE] transition-all">Detail</a>
            </div>
            
            <div class="grid grid-cols-3 gap-3 text-center">
                {{-- Klik Posts --}}
                <a href="{{ route('social.profile.show', $user->username) }}" class="bg-[#152D54] rounded-2xl p-3 border-[2px] border-[#1E3A8A] hover:bg-[#1E3A8A] hover:-translate-y-1 hover:shadow-[3px_3px_0_#060D1F] transition-all cursor-pointer block">
                    <p class="f-display text-2xl font-bold text-[#FACC15]">{{ $stats['posts'] }}</p>
                    <p class="text-[9px] text-slate-300 uppercase tracking-wider font-bold mt-1">Posts</p>
                </a>
                {{-- Klik Followers --}}
                <a href="{{ route('social.profile.show', $user->username) }}" class="bg-[#152D54] rounded-2xl p-3 border-[2px] border-[#1E3A8A] hover:bg-[#1E3A8A] hover:-translate-y-1 hover:shadow-[3px_3px_0_#060D1F] transition-all cursor-pointer block">
                    <p class="f-display text-2xl font-bold text-[#22D3EE]">{{ $stats['followers'] }}</p>
                    <p class="text-[9px] text-slate-300 uppercase tracking-wider font-bold mt-1">Followers</p>
                </a>
                {{-- Klik Following --}}
                <a href="{{ route('social.profile.show', $user->username) }}" class="bg-[#152D54] rounded-2xl p-3 border-[2px] border-[#1E3A8A] hover:bg-[#1E3A8A] hover:-translate-y-1 hover:shadow-[3px_3px_0_#060D1F] transition-all cursor-pointer block">
                    <p class="f-display text-2xl font-bold text-[#60A5FA]">{{ $stats['following'] }}</p>
                    <p class="text-[9px] text-slate-300 uppercase tracking-wider font-bold mt-1">Following</p>
                </a>
            </div>
        </div>

        {{-- ---------- Trending Topik (Bisa Diklik) ---------- --}}
        <div class="bg-[#0F1F3D] border-[3px] border-[#FACC15] rounded-[2rem] rounded-tl-md p-6 shadow-[5px_5px_0_#1E3A8A]">
            <div class="flex items-center justify-between mb-5">
                <h3 class="f-display font-bold text-white text-xl flex items-center gap-2">
                    <span class="bg-[#FACC15] p-1.5 rounded-lg border-[2px] border-[#1E3A8A]">
                        <svg class="w-5 h-5 text-[#1E3A8A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                    </span>
                    Trending
                </h3>
                <a href="{{ route('social.trending') }}" class="text-[10px] font-black text-[#FACC15] hover:text-white bg-[#152D54] px-3 py-1.5 rounded-full border border-[#FACC15]/40 hover:bg-[#FACC15] hover:border-[#FACC15] transition-all">Lihat Semua</a>
            </div>

            @if ($trendingHashtags->isEmpty())
                <p class="text-sm text-slate-400 text-center font-medium">Belum ada topik yang trending.</p>
            @else
                <div class="space-y-3">
                    @foreach ($trendingHashtags as $tag)
                        <a href="{{ route('social.hashtag', $tag->tag) }}" class="flex justify-between items-center group p-3 rounded-2xl bg-[#152D54] border-[2px] border-[#1E3A8A] hover:border-[#FACC15] hover:-translate-y-1 hover:shadow-[3px_3px_0_#1E3A8A] transition-all duration-300">
                            <div>
                                <p class="font-black text-[#60A5FA] group-hover:text-[#FACC15] transition-colors text-sm">#{{ $tag->tag }}</p>
                                <p class="text-[11px] text-slate-400 font-medium mt-0.5">{{ $tag->recent_count }} post hari ini</p>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-[#0F1F3D] flex items-center justify-center group-hover:bg-[#FACC15] transition-colors border-[2px] border-transparent group-hover:border-[#1E3A8A]">
                                <svg class="w-4 h-4 text-slate-400 group-hover:text-[#1E3A8A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ---------- Suggested Users (Bisa Diklik) ---------- --}}
        <div class="bg-[#0F1F3D] border-[3px] border-[#F43F5E] rounded-[2rem] rounded-bl-md p-6 shadow-[5px_5px_0_#1E3A8A]">
            <div class="flex items-center justify-between mb-5">
                <h3 class="f-display font-bold text-white text-xl flex items-center gap-2">
                    <span class="bg-[#F43F5E] p-1.5 rounded-lg border-[2px] border-[#1E3A8A]">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </span>
                    Saran
                </h3>
                <a href="{{ route('social.search') }}" class="text-[10px] font-black text-[#F43F5E] hover:text-white bg-[#152D54] px-3 py-1.5 rounded-full border border-[#F43F5E]/40 hover:bg-[#F43F5E] hover:border-[#F43F5E] transition-all">Cari Lain</a>
            </div>

            @if ($suggestedUsers->isEmpty())
                <p class="text-sm text-slate-400 text-center font-medium">Kamu sudah mengikuti semua orang!</p>
            @else
                <div class="space-y-4">
                    @foreach ($suggestedUsers as $sugUser)
                        <div class="flex items-center gap-3 group">
                            <a href="{{ route('social.profile.show', $sugUser->username) }}" class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-full p-[3px] bg-gradient-to-tr from-[#F43F5E] to-[#FACC15] group-hover:rotate-12 transition-transform shadow-sm">
                                    <div class="w-full h-full rounded-full border-2 border-[#0F1F3D] overflow-hidden bg-[#152D54]">
                                        <x-avatar :avatar="$sugUser->avatar" :user="$sugUser" size="w-full h-full" />
                                    </div>
                                </div>
                            </a>
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('social.profile.show', $sugUser->username) }}" class="font-black text-white hover:text-[#FACC15] transition-colors text-sm block truncate">{{ $sugUser->name }}</a>
                                <p class="text-[11px] text-slate-400 truncate mt-0.5 font-medium">{{ '@' . $sugUser->username }}</p>
                            </div>
                            <a href="{{ route('social.profile.show', $sugUser->username) }}" class="text-[11px] font-black text-[#1E3A8A] bg-[#22D3EE] border-[2px] border-[#1E3A8A] px-3 py-1.5 rounded-lg shadow-[2px_2px_0_#1E3A8A] hover:-translate-y-0.5 hover:shadow-[3px_3px_0_#1E3A8A] transition-all">Ikuti</a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>

@endsection