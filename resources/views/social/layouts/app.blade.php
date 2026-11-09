<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Social — DIGANOVA')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background-color: #060D1F;
            color: #E2E8F0;
        }
        .f-display {
            font-family: 'Fredoka', 'Plus Jakarta Sans', sans-serif;
            letter-spacing: -.01em;
        }
        
        /* Latar Belakang Fun */
        .bg-pattern {
            background-image: radial-gradient(rgba(96, 165, 250, .15) 1px, transparent 1px);
            background-size: 24px 24px;
        }
        
        @keyframes float-shape {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(5deg); }
        }
        .animate-float { animation: float-shape 6s ease-in-out infinite; }
        
        @keyframes pulse-glow {
            0%, 100% { opacity: 0.4; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.05); }
        }
        .animate-glow { animation: pulse-glow 8s ease-in-out infinite; }

        /* NAVBAR AUTO-HIDE EFFECT (SMOOTH VERSION) */
        #main-navbar {
            transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.5s ease;
            will-change: transform, opacity;
        }
        .nav-hidden {
            transform: translateY(-150%) !important;
            opacity: 0 !important;
            pointer-events: none;
        }
    </style>
</head>
<body class="antialiased overflow-x-hidden selection:bg-[#FACC15] selection:text-[#1E3A8A] min-h-screen relative flex flex-col">

    @php
        $unreadDmCount = 0;
        if (auth()->check()) {
            $me = auth()->id();
            $unreadDmCount = \App\Models\DirectMessage::whereHas('conversation', function ($q) use ($me) {
                $q->where('user_one_id', $me)->orWhere('user_two_id', $me);
            })->where('sender_id', '!=', $me)->where('is_read', false)->count();
        }
    @endphp

    {{-- ================= BACKGROUND FUN ================= --}}
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden bg-[#060D1F]">
        <div class="absolute inset-0 bg-pattern opacity-60"></div>
        <div class="absolute top-[-10%] left-[-10%] w-[40vw] h-[40vw] rounded-full bg-[#3B82F6] blur-[120px] opacity-20 animate-glow mix-blend-screen"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50vw] h-[50vw] rounded-full bg-[#22D3EE] blur-[150px] opacity-10 animate-glow mix-blend-screen" style="animation-delay: 2s;"></div>
        
        {{-- Bentuk Dekoratif --}}
        <div class="absolute top-[20%] left-[5%] w-12 h-12 bg-[#FACC15] opacity-20 animate-float" style="clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);"></div>
        <div class="absolute top-[60%] right-[10%] w-16 h-16 bg-[#60A5FA] opacity-20 animate-float rounded-full" style="animation-delay: 1.5s;"></div>
        <div class="absolute bottom-[20%] left-[20%] w-8 h-8 bg-[#F43F5E] opacity-20 animate-float" style="clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%); animation-delay: 3s;"></div>
    </div>

    {{-- ================= HEADER (FLOATING NAVBAR) ================= --}}
    <header id="main-navbar" class="sticky top-4 inset-x-0 z-50 px-4 sm:px-6 pointer-events-none transition-all duration-300">
        <nav class="pointer-events-auto max-w-7xl mx-auto flex items-center justify-between gap-4 sm:gap-6" aria-label="Navigasi utama">

            {{-- Logo --}}
            <a href="{{ route('social.home') }}"
               class="flex items-center gap-3 shrink-0 group">
                <span class="relative w-11 h-11 rounded-2xl bg-gradient-to-br from-[#2563EB] to-[#60A5FA] grid place-items-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span class="absolute -top-1 -right-1 w-3.5 h-3.5 rounded-full bg-[#FACC15] border-2 border-[#060D1F]"></span>
                </span>
                <span class="leading-none hidden sm:block">
                    <span class="f-display text-xl font-bold text-white block tracking-wide">DIGANOVA</span>
                    <span class="text-[10px] font-bold text-[#60A5FA] block mt-1.5 tracking-wide">Plan It. Join It. Enjoy It.</span>
                </span>
            </a>

            {{-- Menu Tengah --}}
            <div class="hidden md:flex relative bg-[#0F1F3D] border-[3px] border-[#1E3A8A] rounded-full p-1.5 shadow-[4px_4px_0_#060D1F] items-center">
                @php
                    $navItems = [
                        ['route' => 'social.home', 'label' => 'Beranda'],
                        ['route' => 'social.posts.index', 'label' => 'Feed'],
                        ['route' => 'social.trending', 'label' => 'Trending'],
                        ['route' => 'social.groups.index', 'label' => 'Grup'],
                        ['route' => 'social.search', 'label' => 'Cari'],
                    ];
                @endphp

                @foreach($navItems as $item)
                    @php $isActive = request()->routeIs($item['route']); @endphp
                    <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}" 
                       class="px-5 py-2 rounded-full text-sm font-extrabold transition-all duration-300 {{ $isActive ? 'bg-[#FACC15] text-[#1E3A8A] shadow-[2px_2px_0_#1E3A8A] -translate-y-0.5' : 'text-slate-300 hover:text-white hover:bg-[#1E3A8A] hover:-translate-y-0.5' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>

            {{-- Kanan (Profil & Logout) --}}
            <div class="flex items-center gap-3 shrink-0">
                @auth
                    {{-- Ikon Pesan --}}
                    <a href="{{ route('social.messages.index') }}" class="relative w-11 h-11 rounded-full bg-[#0F1F3D] border-[3px] border-[#1E3A8A] hover:border-[#FACC15] hover:bg-[#152D54] flex items-center justify-center text-slate-300 hover:text-white transition-all hover:-translate-y-1 shadow-[3px_3px_0_#060D1F] group" title="Pesan">
                        <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        @if ($unreadDmCount > 0)
                            <span class="absolute -top-1 -right-1 min-w-[18px] h-5 px-1 bg-[#F43F5E] text-white text-[10px] font-bold rounded-full flex items-center justify-center border-2 border-[#060D1F] animate-bounce">{{ $unreadDmCount > 9 ? '9+' : $unreadDmCount }}</span>
                        @endif
                    </a>

                    {{-- Avatar Profil (SUDAH DIGANTI MENJADI FOTO AVATAR) --}}
                    <a href="{{ route('social.profile.show', auth()->user()->username) }}" 
                       class="w-11 h-11 rounded-full bg-[#152D54] border-[3px] border-[#1E3A8A] hover:border-[#FACC15] flex items-center justify-center hover:-translate-y-1 hover:scale-105 transition-all shadow-[3px_3px_0_#060D1F] overflow-hidden" 
                       title="Profil Saya">
                        <x-avatar :avatar="auth()->user()->avatar" :user="auth()->user()" size="w-full h-full" />
                    </a>

                    {{-- Tombol Logout --}}
                    <form method="POST" action="{{ route('logout') }}" class="hidden sm:block ml-1">
                        @csrf
                        <button class="inline-flex items-center gap-1.5 bg-[#F43F5E] border-[3px] border-[#1E3A8A] text-white font-extrabold text-sm px-5 py-2.5 rounded-full shadow-[3px_3px_0_#1E3A8A] hover:-translate-y-1 hover:bg-[#E11D48] hover:shadow-[5px_5px_0_#1E3A8A] transition-all">
                            Logout
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                @endauth
            </div>
        </nav>
    </header>

    {{-- ================= MAIN CONTENT ================= --}}
    <main class="relative z-10 flex-1 pt-8 pb-24 md:pb-10 w-full overflow-hidden">
        @yield('content')
    </main>

    {{-- ================= MOBILE NAVIGATION ================= --}}
    <nav class="md:hidden fixed bottom-3 inset-x-3 z-50 bg-[#0F1F3D] border-[3px] border-[#1E3A8A] rounded-full shadow-[4px_4px_0_#060D1F] flex justify-around py-2 text-xs font-bold text-slate-400">
        <a href="{{ route('social.home') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('social.home') ? 'text-[#FACC15] -translate-y-1' : 'hover:text-white' }} transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Beranda
        </a>
        <a href="{{ route('social.posts.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('social.posts.*') ? 'text-[#FACC15] -translate-y-1' : 'hover:text-white' }} transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l6 6v10a2 2 0 01-2 2z"/></svg>
            Feed
        </a>
        <a href="{{ route('social.profile.show', auth()->user()->username ?? '#') }}" class="flex flex-col items-center gap-1 hover:text-white transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Profil
        </a>
    </nav>

    {{-- SCRIPT AUTO-HIDE NAVBAR --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.getElementById('main-navbar');
            let lastScrollTop = 0;
            const scrollThreshold = 50;

            window.addEventListener('scroll', function() {
                let currentScroll = window.pageYOffset || document.documentElement.scrollTop;
                if (currentScroll > lastScrollTop && currentScroll > scrollThreshold) {
                    navbar.classList.add('nav-hidden');
                } else if (currentScroll < lastScrollTop) {
                    navbar.classList.remove('nav-hidden');
                }
                if (currentScroll <= 0) navbar.classList.remove('nav-hidden');
                lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
            }, { passive: true });
        });
    </script>
</body>
</html>