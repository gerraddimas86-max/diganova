@extends('social.layouts.app')

@section('title', 'Trending — DIGANOVA Social')

@section('content')

<style>
    /* ============================================================
       CUSTOM STYLE — Override Card tema Fun/Dark Navy
       ============================================================ */
    
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

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 relative z-10">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="f-display text-3xl font-bold text-white tracking-tight drop-shadow-md flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-[#FACC15] text-[#1E3A8A] flex items-center justify-center shadow-[3px_3px_0_#1E3A8A] border-[2px] border-[#1E3A8A] -rotate-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </span>
                Trending
            </h1>
            <p class="text-sm font-medium text-slate-400 mt-2 ml-12">Hashtag populer dalam 24 jam terakhir</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ================= MAIN: Recent Posts ================= --}}
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-[#0F1F3D] border-[3px] border-[#1E3A8A] rounded-[2rem] p-6 shadow-[5px_5px_0_#060D1F] relative overflow-hidden">
                {{-- Ornamen Background --}}
                <div class="absolute top-0 right-0 w-32 h-32 bg-[#3B82F6] rounded-full blur-[50px] opacity-10"></div>

                {{-- Card Header --}}
                <div class="flex items-center gap-2 mb-1 relative z-10">
                    <svg class="w-5 h-5 text-[#60A5FA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                    </svg>
                    <h2 class="f-display font-bold text-white text-xl">Post Terbaru</h2>
                </div>
                <p class="text-xs font-medium text-slate-400 mb-6 relative z-10 ml-7">Update 24 jam terakhir</p>

                @if ($recentPosts->isEmpty())
                    {{-- Empty State --}}
                    <div class="text-center py-12 bg-[#152D54]/50 rounded-2xl border-[2px] border-[#1E3A8A] relative z-10">
                        <div class="w-20 h-20 mx-auto bg-[#152D54] rounded-full flex items-center justify-center mb-5 border-[3px] border-[#1E3A8A] shadow-inner">
                            <svg class="w-10 h-10 text-[#FACC15]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                        </div>
                        <h3 class="f-display text-white font-bold text-xl mb-2">Belum ada post dalam 24 jam</h3>
                        <p class="text-sm text-slate-400 font-medium">Jadilah yang pertama posting hari ini!</p>
                        
                        @auth
                            <a href="{{ route('social.posts.index') }}" class="inline-flex items-center gap-2 mt-6 bg-[#FACC15] border-[3px] border-[#1E3A8A] text-[#1E3A8A] px-6 py-2.5 rounded-xl text-sm font-black transition-all shadow-[3px_3px_0_#1E3A8A] hover:-translate-y-1 hover:shadow-[5px_5px_0_#1E3A8A]">
                                Buat Postingan
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        @endauth
                    </div>
                @else
                    {{-- Post List --}}
                    <div class="space-y-8 mt-4">
                        @foreach ($recentPosts as $post)
                            <div class="relative mt-4">
                                {{-- Bingkai Luar (Border Tebal & Shadow) --}}
                                <div class="relative bg-[#0F1F3D] border-[3px] border-[#1E3A8A] rounded-[26px] p-1.5 shadow-[5px_5px_0_#060D1F] hover:-translate-y-1 transition-transform duration-300">
                                    
                                    {{-- Tech Corners --}}
                                    <div class="absolute top-0 left-0 w-4 h-4 border-t-2 border-l-2 border-[#60A5FA] rounded-tl-xl z-20 pointer-events-none"></div>
                                    <div class="absolute top-0 right-0 w-4 h-4 border-t-2 border-r-2 border-[#60A5FA] rounded-tr-xl z-20 pointer-events-none"></div>
                                    <div class="absolute bottom-0 left-0 w-4 h-4 border-b-2 border-l-2 border-[#60A5FA] rounded-bl-xl z-20 pointer-events-none"></div>
                                    <div class="absolute bottom-0 right-0 w-4 h-4 border-b-2 border-r-2 border-[#60A5FA] rounded-br-xl z-20 pointer-events-none"></div>

                                    {{-- Garis Aksen Atas --}}
                                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-3/4 h-[2px] bg-gradient-to-r from-transparent via-[#FACC15] to-transparent opacity-80 z-20"></div>
                                    
                                    {{-- Konten Card --}}
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

        {{-- ================= SIDEBAR: Trending Hashtags ================= --}}
        <aside class="lg:col-span-1">
            {{-- Card Sidebar dengan Border Cyan --}}
            <div class="bg-[#0F1F3D] border-[3px] border-[#22D3EE] rounded-[2rem] rounded-tr-md p-6 shadow-[5px_5px_0_#060D1F] sticky top-24 relative overflow-hidden">
                
                <div class="flex items-center gap-2 mb-6 relative z-10">
                    <span class="bg-[#22D3EE] p-1.5 rounded-lg border-[2px] border-[#1E3A8A]">
                        <svg class="w-5 h-5 text-[#1E3A8A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </span>
                    <h2 class="f-display font-bold text-white text-xl tracking-wide">Trending</h2>
                </div>

                @if ($trending->isEmpty())
                    <p class="text-sm font-medium text-slate-400 text-center py-6 bg-[#152D54] rounded-xl border-[2px] border-[#1E3A8A] relative z-10">
                        Belum ada hashtag trending
                    </p>
                @else
                    <div class="space-y-3 relative z-10">
                        @foreach ($trending as $i => $tag)
                            <a href="{{ route('social.hashtag', $tag->tag) }}" class="block p-3 rounded-2xl bg-[#152D54] border-[2px] border-[#1E3A8A] hover:border-[#22D3EE] hover:-translate-y-1 hover:shadow-[3px_3px_0_#1E3A8A] transition-all duration-300 group/tag">
                                <div class="flex items-center gap-3">
                                    {{-- Ranking Number --}}
                                    <span class="f-display text-lg font-bold w-6 text-center transition-all duration-300
                                        {{ $i == 0 ? 'text-[#FACC15] drop-shadow-[0_0_5px_rgba(250,204,21,0.5)] group-hover/tag:scale-110' : '' }}
                                        {{ $i == 1 ? 'text-[#22D3EE] drop-shadow-[0_0_5px_rgba(34,211,238,0.5)] group-hover/tag:scale-110' : '' }}
                                        {{ $i > 1 ? 'text-slate-500' : '' }}">
                                        {{ $i + 1 }}
                                    </span>
                                    
                                    <div class="flex-1 min-w-0">
                                        <p class="font-black text-[#60A5FA] group-hover/tag:text-[#FACC15] transition-colors text-sm truncate">
                                            #{{ $tag->tag }}
                                        </p>
                                        <div class="flex items-center gap-1.5 text-[11px] font-medium text-slate-400 mt-1">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3 h-3 text-[#FACC15]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                                {{ $tag->recent_count }} post
                                            </span>
                                            <span class="text-slate-600">•</span>
                                            <span>{{ $tag->post_count }} total</span>
                                        </div>
                                    </div>
                                    
                                    {{-- Arrow Icon --}}
                                    <div class="w-7 h-7 rounded-lg bg-[#0F1F3D] flex items-center justify-center group-hover/tag:bg-[#22D3EE] transition-colors border-[2px] border-transparent group-hover/tag:border-[#1E3A8A]">
                                        <svg class="w-4 h-4 text-slate-400 group-hover/tag:text-[#1E3A8A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
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