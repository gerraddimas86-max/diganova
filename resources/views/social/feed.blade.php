@extends('social.layouts.app')

@section('title', 'Feed — DIGANOVA Social')

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

    /* Custom Styling for Pagination (Dark Theme) */
    .pagination-dark nav div:first-child { color: #94a3b8; }
    .pagination-dark nav div:first-child span {
        background-color: #0F1F3D; border-color: #1E3A8A; color: #94a3b8;
    }
    .pagination-dark nav div:first-child a {
        background-color: #0F1F3D; border-color: #1E3A8A; color: #cbd5e1; transition: all 0.2s;
    }
    .pagination-dark nav div:first-child a:hover {
        background-color: #1E3A8A; color: #ffffff; border-color: #3B82F6;
    }
    .pagination-dark nav div:first-child span[aria-current="page"] span {
        background-color: #FACC15 !important; border-color: #1E3A8A !important; color: #1E3A8A !important; font-weight: bold;
    }
    .pagination-dark nav .sm\:hidden span, 
    .pagination-dark nav .sm\:hidden a {
        background-color: #0F1F3D; border-color: #1E3A8A; color: #cbd5e1;
    }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 grid grid-cols-1 lg:grid-cols-4 gap-8 relative z-10">

    {{-- ================= MAIN FEED ================= --}}
    <div class="lg:col-span-3 space-y-8">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="f-display text-3xl font-bold text-white tracking-tight drop-shadow-md flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-[#3B82F6] text-white flex items-center justify-center shadow-[3px_3px_0_#1E3A8A] border-[2px] border-[#1E3A8A] -rotate-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l6 6v10a2 2 0 01-2 2z"/></svg>
                    </span>
                    Feed
                </h1>
                <p class="text-sm font-medium text-slate-400 mt-2 ml-12">Apa yang sedang terjadi di DIGANOVA hari ini?</p>
            </div>
            
            {{-- Indikator Live --}}
            <div class="hidden sm:flex items-center gap-2 bg-[#1E3A8A]/50 border-2 border-[#FACC15] px-4 py-1.5 rounded-full shadow-[0_0_15px_rgba(250,204,21,0.2)]">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#FACC15] opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#FACC15]"></span>
                </span>
                <span class="text-xs font-black text-[#FACC15]">Live Update</span>
            </div>
        </div>

        {{-- Create Post (Auth) --}}
        @auth
            <div class="bg-[#0F1F3D] border-[3px] border-[#1E3A8A] rounded-[2rem] p-5 shadow-[5px_5px_0_#060D1F] relative group">
                <form method="POST" action="{{ route('social.posts.store') }}" @submit="submitting = true" x-data="{ body: '', count: 0, submitting: false }">
                    @csrf
                    <div class="flex items-start gap-4">
                        {{-- Avatar --}}
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#22D3EE] to-[#2563EB] border-[3px] border-[#1E3A8A] flex items-center justify-center text-white font-black flex-shrink-0 shadow-[2px_2px_0_#1E3A8A]">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        
                        <div class="flex-1">
                            <textarea name="body" x-model="body" @input="count = body.length" maxlength="500" rows="3" required
                                      placeholder="Apa yang kamu pikirkan? Bagikan momenmu..."
                                      class="w-full bg-transparent border-0 focus:ring-0 outline-none resize-none text-base text-white placeholder:text-slate-500 font-medium"></textarea>
                            
                            @error('body')
                                <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
                            @enderror

                            <div class="flex justify-between items-center pt-3 border-t-[2px] border-[#1E3A8A] mt-2">
                                <div class="text-xs font-bold text-slate-400 flex items-center gap-1.5">
                                    <svg class="w-5 h-5 text-[#60A5FA]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    <span x-text="count"></span> / 500
                                </div>
                                <button type="submit" :disabled="!body.trim() || submitting"
                                        class="bg-[#FACC15] border-[3px] border-[#1E3A8A] text-[#1E3A8A] px-6 py-2.5 rounded-xl text-sm font-black transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed shadow-[3px_3px_0_#1E3A8A] hover:-translate-y-1 hover:shadow-[5px_5px_0_#1E3A8A]">
                                    <span x-show="!submitting" class="flex items-center gap-2">
                                        Posting
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                    </span>
                                    <span x-show="submitting" class="flex items-center gap-2">
                                        <svg class="animate-spin w-4 h-4 text-[#1E3A8A]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        Posting...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @else
            {{-- Create Post (Guest) --}}
            <div class="bg-[#0F1F3D] border-[3px] border-[#1E3A8A] rounded-[2rem] p-8 text-center shadow-[5px_5px_0_#060D1F] relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-[#3B82F6] rounded-full blur-[50px] opacity-20"></div>
                <div class="w-16 h-16 mx-auto bg-[#152D54] rounded-full flex items-center justify-center mb-4 border-[3px] border-[#1E3A8A] shadow-inner">
                    <svg class="w-8 h-8 text-[#FACC15]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h3 class="f-display text-white font-bold text-2xl mb-2">Ingin ikut posting?</h3>
                <p class="text-sm font-medium text-slate-400 mb-6">Login dulu untuk bergabung dalam percakapan.</p>
                <div class="flex justify-center gap-4">
                    <a href="{{ route('login') }}" class="bg-[#3B82F6] border-[3px] border-[#1E3A8A] text-white hover:-translate-y-1 px-6 py-2.5 rounded-xl text-sm font-black transition-all shadow-[3px_3px_0_#1E3A8A] hover:shadow-[5px_5px_0_#1E3A8A]">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="bg-white border-[3px] border-[#1E3A8A] text-[#1E3A8A] hover:-translate-y-1 px-6 py-2.5 rounded-xl text-sm font-black transition-all shadow-[3px_3px_0_#1E3A8A] hover:shadow-[5px_5px_0_#1E3A8A]">
                        Daftar
                    </a>
                </div>
            </div>
        @endauth

        {{-- ================== POST LIST ================== --}}
        @if ($posts->isEmpty())
            <div class="bg-[#0F1F3D] border-[3px] border-[#3B82F6] rounded-3xl p-12 text-center shadow-[5px_5px_0_#1E3A8A] relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-[#3B82F6] blur-[40px] opacity-20"></div>
                <div class="w-24 h-24 mx-auto bg-[#152D54] rounded-full flex items-center justify-center mb-5 border-[3px] border-[#1E3A8A] shadow-inner">
                    <svg class="w-12 h-12 text-[#FACC15]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                </div>
                <h3 class="f-display text-white font-bold text-2xl mb-2">Belum ada post nih!</h3>
                <p class="text-slate-400 text-sm font-medium">Jadilah yang pertama memulai percakapan!</p>
            </div>
        @else
            <div class="space-y-8 mt-4">
                @foreach ($posts as $post)
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

            {{-- Pagination --}}
            <div class="pt-8">
                <div class="pagination-dark">
                    {{ $posts->links() }}
                </div>
            </div>
        @endif
    </div>

    {{-- ================= SIDEBAR ================= --}}
    <aside class="hidden lg:block lg:col-span-1">
        <div class="sticky top-24 space-y-6">

            @auth
                {{-- User Profile Card (Bisa Diklik Seluruhnya) --}}
                <a href="{{ route('social.profile.show', auth()->user()->username) }}" 
                   class="block bg-[#0F1F3D] border-[3px] border-[#1E3A8A] rounded-[2rem] overflow-hidden shadow-[4px_4px_0_#060D1F] relative group hover:-translate-y-1 hover:shadow-[6px_6px_0_#060D1F] transition-all duration-300 cursor-pointer">
                    
                    {{-- Mini Cover --}}
                    <div class="h-20 bg-gradient-to-br from-[#1E3A8A] to-[#2563EB] relative">
                        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 16px 16px;"></div>
                    </div>
                    
                    <div class="px-5 pb-5 relative">
                        {{-- Avatar Overlapping --}}
                        <div class="flex justify-between items-end -mt-8 mb-3">
                            <div class="w-14 h-14 rounded-2xl p-[3px] bg-gradient-to-tr from-[#22D3EE] to-[#3B82F6] shadow-[2px_2px_0_#1E3A8A]">
                                <div class="w-full h-full rounded-xl bg-[#152D54] flex items-center justify-center text-white font-black text-xl border-2 border-[#0F1F3D]">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            </div>
                            {{-- Tombol visual (Span, karena pembungkus utamanya sudah <a>) --}}
                            <span class="text-[10px] font-black text-[#1E3A8A] bg-[#FACC15] px-3 py-1.5 rounded-lg border-[2px] border-[#1E3A8A] shadow-[2px_2px_0_#1E3A8A] group-hover:-translate-y-0.5 transition-all">Profil</span>
                        </div>
                        
                        <div class="min-w-0 mb-4">
                            <p class="font-bold text-white truncate text-base f-display group-hover:text-[#FACC15] transition-colors">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ '@' . auth()->user()->username }}</p>
                        </div>
                        
                        {{-- Stats Grid --}}
                        <div class="grid grid-cols-3 gap-2 text-center text-xs">
                            <div class="bg-[#152D54] rounded-xl py-2 border-[2px] border-[#1E3A8A] group-hover:bg-[#1E3A8A] transition-colors">
                                <p class="font-black text-[#FACC15] text-sm f-display">{{ auth()->user()->posts()->count() }}</p>
                                <p class="text-[9px] text-slate-300 uppercase tracking-wider mt-0.5 font-bold">Post</p>
                            </div>
                            <div class="bg-[#152D54] rounded-xl py-2 border-[2px] border-[#1E3A8A] group-hover:bg-[#1E3A8A] transition-colors">
                                <p class="font-black text-[#22D3EE] text-sm f-display">{{ auth()->user()->followers()->count() }}</p>
                                <p class="text-[9px] text-slate-300 uppercase tracking-wider mt-0.5 font-bold">Followers</p>
                            </div>
                            <div class="bg-[#152D54] rounded-xl py-2 border-[2px] border-[#1E3A8A] group-hover:bg-[#1E3A8A] transition-colors">
                                <p class="font-black text-[#60A5FA] text-sm f-display">{{ auth()->user()->following()->count() }}</p>
                                <p class="text-[9px] text-slate-300 uppercase tracking-wider mt-0.5 font-bold">Following</p>
                            </div>
                        </div>
                    </div>
                </a>
            @endauth

            {{-- Welcome Card --}}
            <div class="bg-[#0F1F3D] border-[3px] border-[#22D3EE] rounded-[2rem] rounded-tr-md p-6 shadow-[4px_4px_0_#060D1F] relative overflow-hidden">
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-[#22D3EE] rounded-full blur-[40px] opacity-20"></div>
                <p class="font-bold text-white text-base flex items-center gap-2 relative z-10 mb-2 f-display">
                    <span class="w-8 h-8 rounded-lg bg-[#22D3EE] text-[#1E3A8A] flex items-center justify-center border-[2px] border-[#1E3A8A] shadow-[2px_2px_0_#1E3A8A]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </span>
                    Selamat datang!
                </p>
                <p class="text-[12px] text-slate-300 mt-3 leading-relaxed relative z-10 font-medium">
                    DIGANOVA Social adalah tempat komunitas festival ngobrol, sharing, dan diskusi. Mari berteman dan berbagi momen!
                </p>
            </div>

            {{-- Cross-Domain Links --}}
            <div class="bg-[#0F1F3D] border-[3px] border-[#FACC15] rounded-[2rem] rounded-tl-md p-6 shadow-[4px_4px_0_#060D1F]">
                <p class="font-bold text-white text-base mb-4 flex items-center gap-2 f-display">
                    <span class="bg-[#FACC15] p-1 rounded-md border-[2px] border-[#1E3A8A]">
                        <svg class="w-4 h-4 text-[#1E3A8A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                    </span>
                    Jelajahi
                </p>
                <div class="space-y-3">
                    <a href="{{ route('event.home') }}" class="group flex items-center justify-between p-3 rounded-2xl bg-[#152D54] border-[2px] border-[#1E3A8A] hover:border-[#FACC15] hover:-translate-y-1 hover:shadow-[3px_3px_0_#1E3A8A] transition-all duration-300">
                        <span class="text-xs font-black text-slate-300 group-hover:text-white transition-colors flex items-center gap-2">
                            <span class="w-7 h-7 rounded-md bg-[#0F1F3D] flex items-center justify-center text-[#FACC15] border border-[#1E3A8A]"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></span>
                            Event & Festival
                        </span>
                        <svg class="w-4 h-4 text-slate-500 group-hover:text-[#FACC15] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                    
                    <a href="{{ route('play.home') }}" class="group flex items-center justify-between p-3 rounded-2xl bg-[#152D54] border-[2px] border-[#1E3A8A] hover:border-[#22D3EE] hover:-translate-y-1 hover:shadow-[3px_3px_0_#1E3A8A] transition-all duration-300">
                        <span class="text-xs font-black text-slate-300 group-hover:text-white transition-colors flex items-center gap-2">
                            <span class="w-7 h-7 rounded-md bg-[#0F1F3D] flex items-center justify-center text-[#22D3EE] border border-[#1E3A8A]"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span>
                            Playground
                        </span>
                        <svg class="w-4 h-4 text-slate-500 group-hover:text-[#22D3EE] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>

        </div>
    </aside>
</div>

@endsection