<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Social — DIGANOVA')</title>
    
    <!-- 🌟 Script penting: baca tema dari localStorage SEBELUM render -->
    <script>
        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.add('light-mode');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background-color: #171717;
            color: #E2E8F0;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .f-display {
            font-family: 'Fredoka', 'Plus Jakarta Sans', sans-serif;
            letter-spacing: -.01em;
        }
        
        /* Pattern dots */
        .bg-pattern {
            background-image: radial-gradient(rgba(255, 255, 255, .04) 1px, transparent 1px);
            background-size: 24px 24px;
        }
        
        /* NAVBAR AUTO-HIDE EFFECT */
        #main-navbar {
            transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.5s ease;
            will-change: transform, opacity;
        }
        .nav-hidden {
            transform: translateY(-150%) !important;
            opacity: 0 !important;
            pointer-events: none;
        }

        /* ============================================================
           🌗 GLOBAL THEME
           ============================================================ */
        html:not(.light-mode) body {
            background-color: #171717 !important;
            color: #E2E8F0 !important;
        }

        html.light-mode body {
            background-color: #F8FAFC !important;
            color: #0F172A !important;
        }

        #bg-decoration {
            background-color: #171717;
            transition: background-color 0.3s ease;
        }
        html.light-mode #bg-decoration {
            background-color: #F8FAFC !important;
        }

        html.light-mode #bg-decoration .bg-pattern {
            display: none !important;
        }

        #main-navbar {
            background-color: #171717;
        }
        html.light-mode #main-navbar {
            background-color: #F8FAFC !important;
            border-bottom: 1px solid #E2E8F0;
        }

        /* ============================================================
           🖤 TAB MENU DESKTOP
           ============================================================ */
        .nav-tab {
            transition: background-color .25s ease, color .25s ease, border-color .25s ease;
        }

        html:not(.light-mode) .nav-tab-active {
            background-color: #000000 !important;
            border-color: #000000 !important;
            color: #FFFFFF !important;
        }

        html:not(.light-mode) .nav-tab-inactive:hover {
            background-color: #000000 !important;
            color: #FFFFFF !important;
        }

        html.light-mode .nav-tab-active {
            background-color: #000000 !important;
            border-color: #000000 !important;
            color: #FFFFFF !important;
            box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.08) !important;
        }

        html.light-mode .nav-tab-inactive { color: #64748B !important; }
        html.light-mode .nav-tab-inactive:hover {
            background-color: #000000 !important;
            color: #FFFFFF !important;
        }

        /* Tombol Kanan Light Mode */
        html.light-mode .btn-icon-right { color: #475569 !important; }
        html.light-mode .btn-icon-right:hover { background-color: #E2E8F0 !important; color: #0F172A !important; }
        html.light-mode .logo-subtext { color: #475569 !important; }
        html.light-mode .hub-btn { border-color: #0F172A !important; color: #0F172A !important; }
        html.light-mode .hub-btn:hover { background-color: #E2E8F0 !important; }
        html.light-mode .logo-text { color: #0F172A !important; }

        /* Icon Toggle Visibility */
        .sun-icon { display: none; }
        .light-mode .sun-icon { display: block; color: #F59E0B; }
        .light-mode .moon-icon { display: none; }

        /* 🌟 STYLE BOTTOM NAV MOBILE (FLOATING PILL) */
        .mobile-nav-container {
            background-color: #111111;
            border: 1px solid #333333;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }
        html.light-mode .mobile-nav-container {
            background-color: #000000;
            border: 1px solid #000000;
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

    {{-- ================= BACKGROUND ================= --}}
    <div id="bg-decoration" class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute inset-0 bg-pattern opacity-60"></div>
    </div>

    {{-- ================= HEADER (NAVBAR TAB CHROME STYLE) ================= --}}
    <header id="main-navbar" class="sticky top-0 z-50 w-full transition-transform duration-300">
        <nav class="max-w-7xl mx-auto flex items-center justify-between px-4 sm:px-6 h-[60px] md:h-[72px]" aria-label="Navigasi utama">

            {{-- ================= KIRI: TOMBOL HUB + LOGO ================= --}}
            <div class="flex items-center gap-2 sm:gap-4 shrink-0">
                
                {{-- Tombol Hub --}}
                <a href="{{ route('hub') }}" class="hub-btn flex items-center gap-1.5 px-3 py-1.5 md:px-4 md:py-1.5 border-[2px] border-white rounded-full text-white font-extrabold text-xs md:text-sm hover:bg-white/10 transition-all duration-300">
                    <svg class="w-3.5 h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Hub
                </a>

                {{-- Logo & Teks --}}
                <a href="{{ route('social.home') }}" class="flex items-center gap-2 shrink-0 group">
                    <div class="w-7 h-7 md:w-8 md:h-8 shrink-0 flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                        <img src="{{ asset('images/LOGO.png') }}" alt="Logo DIGANOVA" class="w-full h-full object-contain drop-shadow-md">
                    </div>
                    
                    <div class="hidden sm:flex flex-col justify-center">
                        <span class="logo-text font-sans text-[18px] md:text-[20px] font-black text-white tracking-[0.15em] leading-none">DIGANOVA</span>
                        <span class="logo-subtext font-sans text-[8px] md:text-[9px] font-bold text-slate-300 tracking-[0.2em] mt-1 leading-none uppercase">Plan It. Join It. Enjoy It.</span>
                    </div>
                </a>
            </div>

            {{-- ================= TENGAH: MENU TAB (DESKTOP ONLY) ================= --}}
            <div class="hidden md:flex items-end h-full pt-4">
                @php
                    $navItems = [
                        ['route' => 'social.home', 'label' => 'Beranda', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                        ['route' => 'social.posts.index', 'label' => 'Feed', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l6 6v10a2 2 0 01-2 2z'],
                        ['route' => 'social.trending', 'label' => 'Trending', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                        ['route' => 'social.groups.index', 'label' => 'Grup', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                        ['route' => 'social.search', 'label' => 'Cari', 'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
                    ];
                @endphp

                <div class="flex items-end">
                    @foreach($navItems as $item)
                        @php $isActive = request()->routeIs($item['route']); @endphp
                        <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}" 
                           class="nav-tab flex items-center gap-2 px-5 py-3 text-sm font-bold rounded-t-xl relative -mb-[1px] border-t border-x
                           {{ $isActive 
                               ? 'nav-tab-active bg-black text-white border-black z-10' 
                               : 'nav-tab-inactive text-slate-400 border-transparent hover:bg-black hover:text-white' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $item['icon'] }}"/></svg>
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- ================= KANAN: Ikon Pencarian, Toggle Tema, Pesan, Profil ================= --}}
            <div class="flex items-center gap-1.5 sm:gap-3 shrink-0">
                
                {{-- Ikon Pencarian (Hanya muncul di HP) --}}
                <a href="{{ route('social.search') }}" class="md:hidden w-9 h-9 rounded-full flex items-center justify-center text-slate-400 hover:text-white hover:bg-[#262626] transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </a>

                {{-- 🌟 TOMBOL TOGGLE GELAP/TERANG 🌟 --}}
                <button id="theme-toggle" class="w-9 h-9 md:w-10 md:h-10 rounded-full flex items-center justify-center text-slate-400 hover:text-white hover:bg-[#262626] transition-all duration-300" title="Ganti Tema">
                    <svg class="w-5 h-5 sun-icon transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <svg class="w-5 h-5 moon-icon transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                </button>

                @auth
                    {{-- Ikon Pesan --}}
                    <a href="{{ route('social.messages.index') }}" class="hidden sm:flex relative w-10 h-10 rounded-full items-center justify-center text-slate-400 hover:text-white hover:bg-[#262626] transition-all duration-300" title="Pesan">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        @if ($unreadDmCount > 0)
                            <span class="absolute -top-1 -right-1 min-w-[18px] h-5 px-1 bg-[#F43F5E] text-white text-[10px] font-bold rounded-full flex items-center justify-center border-2 border-[#171717] animate-bounce">{{ $unreadDmCount > 9 ? '9+' : $unreadDmCount }}</span>
                        @endif
                    </a>

                    {{-- Avatar Profil --}}
                    <a href="{{ route('social.profile.show', auth()->user()->username) }}" 
                       class="w-8 h-8 md:w-9 md:h-9 rounded-full bg-[#262626] border-2 border-[#3F3F3F] flex items-center justify-center overflow-hidden hover:scale-105 transition-all duration-300 shadow-sm ml-0.5 md:ml-1" 
                       title="Profil Saya">
                        <x-avatar :avatar="auth()->user()->avatar" :user="auth()->user()" size="w-full h-full" />
                    </a>

                    {{-- Tombol Logout (Desktop Only) --}}
                    <form method="POST" action="{{ route('logout') }}" class="hidden md:block ml-2">
                        @csrf
                        <button class="inline-flex items-center gap-1.5 bg-[#F43F5E] text-white font-bold text-xs px-4 py-2 rounded-full hover:bg-[#E11D48] hover:shadow-md transition-all duration-300">
                            Logout
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                @endauth
            </div>
        </nav>
    </header>

    {{-- ================= MAIN CONTENT ================= --}}
    <main class="relative z-10 flex-1 pt-4 md:pt-8 pb-32 md:pb-10 w-full overflow-hidden">
        @yield('content')
    </main>

    {{-- ================= MOBILE NAVIGATION (FLOATING PILL) ================= --}}
    <!-- Menggunakan grid-cols-5 agar 5 menu muat dengan rapi di HP -->
    <nav class="md:hidden fixed bottom-5 left-1/2 -translate-x-1/2 w-[92%] max-w-sm mobile-nav-container rounded-full grid grid-cols-5 items-center py-2.5 px-2 z-50">
        
        {{-- 1. Beranda --}}
        <a href="{{ route('social.home') }}" class="flex flex-col items-center gap-1 transition-all duration-300 {{ request()->routeIs('social.home') ? 'text-[#FACC15] -translate-y-0.5' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span class="text-[9px] font-bold {{ request()->routeIs('social.home') ? 'text-[#FACC15]' : '' }}">Beranda</span>
        </a>

        {{-- 2. Feed --}}
        <a href="{{ route('social.posts.index') }}" class="flex flex-col items-center gap-1 transition-all duration-300 {{ request()->routeIs('social.posts.*') ? 'text-[#FACC15] -translate-y-0.5' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l6 6v10a2 2 0 01-2 2z"/></svg>
            <span class="text-[9px] font-bold {{ request()->routeIs('social.posts.*') ? 'text-[#FACC15]' : '' }}">Feed</span>
        </a>

        {{-- 3. Grup (Baru ditambahkan) --}}
        <a href="{{ route('social.groups.index') }}" class="flex flex-col items-center gap-1 transition-all duration-300 {{ request()->routeIs('social.groups.*') ? 'text-[#FACC15] -translate-y-0.5' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span class="text-[9px] font-bold {{ request()->routeIs('social.groups.*') ? 'text-[#FACC15]' : '' }}">Grup</span>
        </a>

        {{-- 4. Pesan (DM) --}}
        <a href="{{ route('social.messages.index') }}" class="relative flex flex-col items-center gap-1 transition-all duration-300 {{ request()->routeIs('social.messages.*') ? 'text-[#FACC15] -translate-y-0.5' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <span class="text-[9px] font-bold {{ request()->routeIs('social.messages.*') ? 'text-[#FACC15]' : '' }}">Pesan</span>
            
            @if ($unreadDmCount > 0)
                <span class="absolute top-0 right-1 min-w-[16px] h-4 px-1 bg-[#F43F5E] text-white text-[9px] font-bold rounded-full flex items-center justify-center border-2 border-[#111]">{{ $unreadDmCount > 9 ? '9+' : $unreadDmCount }}</span>
            @endif
        </a>

        {{-- 5. Profil --}}
        <a href="{{ route('social.profile.show', auth()->user()->username ?? '#') }}" class="flex flex-col items-center gap-1 transition-all duration-300 {{ request()->routeIs('social.profile.*') ? 'text-[#FACC15] -translate-y-0.5' : 'text-gray-400 hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span class="text-[9px] font-bold {{ request()->routeIs('social.profile.*') ? 'text-[#FACC15]' : '' }}">Profil</span>
        </a>
    </nav>

    {{-- SCRIPT AUTO-HIDE NAVBAR & TEMA --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Navbar Auto-Hide
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

            // 2. Theme Toggle Logic
            const themeToggleBtn = document.getElementById('theme-toggle');
            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', () => {
                    document.documentElement.classList.toggle('light-mode');
                    if (document.documentElement.classList.contains('light-mode')) {
                        localStorage.setItem('theme', 'light');
                    } else {
                        localStorage.setItem('theme', 'dark');
                    }
                });
            }
        });
    </script>
</body>
</html>