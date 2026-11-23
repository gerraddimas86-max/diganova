<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="view-transition" content="same-origin">
    <meta name="theme-color" content="#000000">
    <meta name="description" content="Playground — DIGANOVA NAVO CLASH">

    <title>@yield('title', 'Playground — DIGANOVA')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="{{ asset('fonts/iceberg-regular.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('fonts/anta-regular.woff2') }}" as="font" type="font/woff2" crossorigin>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
    (function(){
        const saved = localStorage.getItem('navoclash.theme');
        const sys = window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', saved || sys);
    })();
    </script>

    <style>
        @font-face {
            font-family: 'Iceberg';
            src: url('/fonts/iceberg-regular.woff2') format('woff2'),
                 url('/fonts/iceberg-regular.ttf') format('truetype');
            font-weight: 400; font-style: normal; font-display: swap;
        }
        @font-face {
            font-family: 'Anta';
            src: url('/fonts/anta-regular.woff2') format('woff2'),
                 url('/fonts/anta-regular.ttf') format('truetype');
            font-weight: 400; font-style: normal; font-display: swap;
        }

        /* ============================================================
           TOKENS
           ============================================================ */
        :root {
            --bg:            #000000;
            --bg-soft:       #0A0A0F;
            --text:          #FFFFFF;
            --muted:         rgba(255,255,255,.56);
            --faint:         rgba(255,255,255,.34);
            --line:          rgba(255,255,255,.12);
            --line-strong:   rgba(255,255,255,.24);
            --grid:          rgba(255,255,255,.055);
            --panel:         rgba(255,255,255,.035);
            
            --cyan:          #22D3EE;
            --accent-text:   #22D3EE;
            --cyan-ink:      #04141A;
            --glow:          rgba(34,211,238,.16);
            --shadow:        0 24px 48px -32px rgba(0,0,0,.9),
                             0 8px 24px -12px rgba(0,0,0,.6);
        }
        [data-theme="light"] {
            --bg:            #F1F5F9;
            --bg-soft:       #F8FAFC;
            --text:          #0F172A;
            --muted:         rgba(15,23,42,.62);
            --faint:         rgba(15,23,42,.46);
            --line:          rgba(15,23,42,.09);
            --line-strong:   rgba(15,23,42,.20);
            --grid:          rgba(15,23,42,.045);
            --panel:         #FFFFFF;
            --accent-text:   #0891B2;
            --glow:          rgba(34,211,238,.28);
            --shadow:        0 24px 48px -28px rgba(15,23,42,.22),
                             0 8px 20px -10px rgba(15,23,42,.10);
        }

        /* ============================================================
           BASE & BACKGROUND
           ============================================================ */
        * { box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        html { scroll-behavior: smooth; }
        
        html, body {
            background-color: var(--bg) !important;
            color: var(--text) !important;
            margin: 0;
            min-height: 100vh;
        }

        body {
            font-family: 'Anta', system-ui, sans-serif;
            font-weight: 400;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            transition: background-color .35s ease, color .35s ease;
            
            /* Gambar Grid */
            background-image: 
                radial-gradient(circle at 50% 0%, var(--glow) 0%, transparent 50%),
                linear-gradient(var(--grid) 1px, transparent 1px),
                linear-gradient(90deg, var(--grid) 1px, transparent 1px) !important;
            background-size: 100% 100%, 72px 72px, 72px 72px !important;
            background-position: top center, center top, center top !important;
            background-repeat: no-repeat, repeat, repeat !important;
            background-attachment: fixed, fixed, fixed !important;
        }

        :where(a, button, [tabindex]):focus-visible {
            outline: 2px solid var(--cyan);
            outline-offset: 3px;
            border-radius: 2px;
        }

        /* Typography */
        .h-display {
            font-family: 'Iceberg', serif;
            font-weight: 400;
            -webkit-text-stroke: .4px currentColor;
            letter-spacing: -.015em;
            line-height: 1.05;
            margin: 0;
            color: var(--text);
        }
        .accent { color: var(--accent-text); }

        .wm { display: inline-block; line-height: .85; letter-spacing: .01em; vertical-align: baseline; white-space: nowrap; }
        .wm .wm-ice { font-family: 'Iceberg', serif; -webkit-text-stroke: .4px currentColor; color: var(--accent-text); font-size: 1.06em; }
        .wm .wm-anta { font-family: 'Anta', sans-serif; -webkit-text-stroke: .3px currentColor; color: currentColor; letter-spacing: .04em; font-size: .86em; opacity: .92; margin-left: .28em; }
        .wm-sm { font-size: 1.02rem; }

        /* ============================================================
           ANIMATIONS
           ============================================================ */
        @keyframes rise {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: none; }
        }
        @keyframes shimmer {
            0%   { transform: translateX(-100%); }
            100% { transform: translateX(220%); }
        }
        @keyframes floaty {
            0%, 100% { translate: 0 0; }
            50%      { translate: 0 -6px; }
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .rise { 
            opacity: 0; 
            animation: rise .8s cubic-bezier(.2,.8,.2,1) forwards; 
            animation-delay: calc(var(--i, 0) * 80ms + 100ms); 
        }

        /* ============================================================
           TOPBAR & NAVIGASI
           ============================================================ */
        .topbar {
            position: sticky; top: 0; z-index: 50; width: 100%;
            border-bottom: 1px solid var(--line);
            background: rgba(0, 0, 0, 0.6);
            -webkit-backdrop-filter: blur(12px); backdrop-filter: blur(12px);
        }
        [data-theme="light"] .topbar { background: rgba(255, 255, 255, 0.6); }
        
        .topbar-inner {
            width: 100%; max-width: 1180px; margin: 0 auto; padding: 1rem 1.5rem;
            display: flex; align-items: center; justify-content: space-between; gap: 1rem;
        }
        .brand { display: inline-flex; align-items: center; gap: .7rem; text-decoration: none; color: var(--text); transition: transform .2s ease; }
        .brand:hover { transform: translateY(-1px); }
        .brand img { width: 2rem; height: 2rem; object-fit: contain; display: block; }
        .brand-text { display: flex; flex-direction: column; line-height: 1; }
        .brand-tag { margin-top: .4rem; font-size: .5rem; letter-spacing: .16em; text-transform: uppercase; color: var(--faint); white-space: nowrap; }
        
        .topbar-actions { display: flex; align-items: center; gap: .5rem; }

        /* Desktop Nav */
        .nav-desktop { display: none; }
        @media (min-width: 1024px) { .nav-desktop { display: flex; align-items: center; gap: .15rem; margin-right: 1rem; } }

        /* Nav Link & Mobile Nav */
        .nav-link {
            position: relative; display: inline-flex; align-items: center; gap: .45rem; padding: .55rem .8rem;
            font-size: .66rem; letter-spacing: .12em; text-transform: uppercase; text-decoration: none;
            color: var(--muted); border-radius: 2px; transition: all .18s ease; white-space: nowrap;
            overflow: hidden;
        }
        .nav-link::after {
            content: ''; position: absolute; bottom: 0; left: 50%; width: 0; height: 2px; 
            background: var(--cyan); transition: all .3s ease; transform: translateX(-50%);
            opacity: 0;
        }
        .nav-link:hover::after, .nav-link.is-active::after {
            width: 80%; opacity: 1;
        }
        .nav-link:hover, .nav-link.is-active { color: var(--accent-text); background: var(--panel); }
        .nav-link.is-active { box-shadow: inset 0 0 0 1px var(--line-strong); }
        .nav-link svg { width: 13px; height: 13px; flex-shrink: 0; }

        /* Mobile Nav (Scrollable) */
        .nav-mobile {
            display: flex; align-items: center; gap: .3rem; overflow-x: auto; padding: 0 1rem .8rem;
            scrollbar-width: none;
        }
        .nav-mobile::-webkit-scrollbar { display: none; }
        @media (min-width: 1024px) { .nav-mobile { display: none; } }

        /* Points Pill */
        .points-pill {
            position: relative; overflow: hidden; display: inline-flex; align-items: center; gap: .4rem; padding: .35rem .6rem;
            border: 1px solid var(--line-strong); border-radius: 999px; background: var(--panel);
            font-size: .6rem; letter-spacing: .08em; color: var(--muted); white-space: nowrap;
            transition: all .3s ease;
        }
        .points-pill:hover { border-color: var(--cyan); box-shadow: 0 0 15px var(--glow); }
        .points-pill::before {
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(34,211,238,.15), transparent);
            transform: translateX(-100%);
        }
        .points-pill:hover::before { animation: shimmer 1s ease; }
        [data-theme="light"] .points-pill { background: #FFFFFF; }
        .points-pill b { font-family: 'Iceberg', serif; font-weight: 400; color: var(--text); font-size: .85rem; }
        .points-pill .dot { width: 5px; height: 5px; border-radius: 50%; background: var(--cyan); box-shadow: 0 0 8px var(--cyan); }

        /* Theme Toggle & Buttons */
        .theme-toggle {
            position: relative; width: 2.2rem; height: 2.2rem; display: grid; place-items: center;
            background: transparent; border: 1px solid var(--line); border-radius: 999px; color: var(--text);
            cursor: pointer; padding: 0; overflow: hidden; flex-shrink: 0; transition: all .2s ease;
        }
        [data-theme="light"] .theme-toggle { background: #FFFFFF; }
        .theme-toggle:hover { border-color: var(--cyan); color: var(--accent-text); }
        .theme-toggle svg { position: absolute; width: 14px; height: 14px; transition: transform .4s ease, opacity .3s ease; }
        .theme-toggle .icon-sun { opacity: 1; transform: rotate(0) scale(1); }
        .theme-toggle .icon-moon { opacity: 0; transform: rotate(-180deg) scale(0); }
        [data-theme="light"] .theme-toggle .icon-sun { opacity: 0; transform: rotate(180deg) scale(0); }
        [data-theme="light"] .theme-toggle .icon-moon { opacity: 1; transform: rotate(0) scale(1); }

        .profile-ring {
            position: relative; width: 2.2rem; height: 2.2rem; display: grid; place-items: center;
            border-radius: 50%; border: 1px solid var(--line-strong); background: var(--panel);
            text-decoration: none; color: var(--text); transition: all .2s ease;
        }
        .profile-ring:hover { border-color: var(--cyan); transform: translateY(-1px); box-shadow: 0 0 15px var(--glow); }

        .btn-icon {
            display: inline-grid; place-items: center; width: 2rem; height: 2rem; padding: 0;
            border: 1px solid var(--line); border-radius: 999px; background: transparent;
            color: var(--faint); cursor: pointer; transition: all .2s ease;
        }
        .btn-icon:hover { color: #F87171; border-color: rgba(248,113,113,.5); transform: scale(1.05); }
        .btn-icon svg { width: 13px; height: 13px; }

        /* Buttons */
        .btn {
            position: relative; overflow: hidden;
            display: inline-flex; align-items: center; justify-content: center; gap: .55rem;
            padding: .7rem 1.2rem; font-family: 'Anta', sans-serif; font-size: .7rem;
            letter-spacing: .1em; text-transform: uppercase; text-decoration: none;
            border: 1px solid transparent; border-radius: 2px; cursor: pointer; transition: all .18s ease;
        }
        .btn-sm { padding: .5rem .9rem; font-size: .65rem; }
        .btn-primary { background: var(--cyan); color: var(--cyan-ink); border-color: var(--cyan); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 14px 34px -14px var(--cyan); }
        .btn-ghost { background: transparent; color: var(--text); border-color: var(--line-strong); }
        [data-theme="light"] .btn-ghost { background: #FFFFFF; }
        .btn-ghost:hover { transform: translateY(-2px); border-color: var(--cyan); color: var(--accent-text); }

        /* Efek kilau pada tombol */
        .btn::after {
            content: ''; position: absolute; top: 0; left: -100%; width: 50%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.3), transparent);
            transform: skewX(-20deg);
        }
        .btn:hover::after { animation: shimmer 0.8s ease; }

        /* Main Content */
        .page-main {
            position: relative; z-index: 2; width: 100%; max-width: 1180px; margin: 0 auto;
            padding: 1.5rem 1rem 4rem; flex: 1;
        }
        @media (min-width: 640px) { .page-main { padding: 2rem 1.5rem 4rem; } }
        
        .panel {
            background: var(--panel); border: 1px solid var(--line); border-radius: 6px;
            box-shadow: var(--shadow); -webkit-backdrop-filter: blur(10px); backdrop-filter: blur(10px);
        }
        [data-theme="light"] .panel { background: #FFFFFF; }

        /* Hide utility */
        .hide-sm { display: none !important; }
        @media (min-width: 640px) { .hide-sm { display: inline-flex !important; } }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: .01ms !important; transition-duration: .01ms !important; }
            .rise { opacity: 1 !important; }
        }
    </style>
</head>
<body id="top">

@php
    $logo = fn (string $class = '') => '<img src="' . asset('images/logo.png') . '" alt="Logo" class="' . $class . '" draggable="false">';
    $wordmark = fn (string $class = 'wm-sm') => '<span class="wm ' . $class . '"><span class="wm-ice">DIGANOVA</span><span class="wm-anta">NAVO CLASH</span></span>';
    $svg = fn (string $path, string $class = '') => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . $path . '</svg>';
    $pointsDisplay = auth()->check() ? auth()->user()->total_points : 0;

    $iconHome  = '<path d="M3 12L12 3l9 9"/><path d="M5 10v10h14V10"/><path d="M9 20v-6h6v6"/>';
    $iconDeck  = '<rect x="3" y="7" width="12" height="14" rx="2"/><path d="M7 4h10a2 2 0 0 1 2 2v12"/>';
    $iconGacha = '<rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 8h6"/><path d="M12 11l1.2 2.4L16 14l-2 1.9.5 2.7-2.5-1.3-2.5 1.3.5-2.7-2-1.9 2.8-.6L12 11z"/>';
    $iconSword = '<path d="M14.5 17.5 3 6V3h3l11.5 11.5"/><path d="M13 19l6-6"/><path d="M16 16l4 4"/><path d="M19 21l2-2"/>';
    $iconTrophy= '<path d="M6 4h12v5a6 6 0 0 1-12 0V4Z"/><path d="M6 6H3v2a4 4 0 0 0 4 4"/><path d="M18 6h3v2a4 4 0 0 1-4 4"/><path d="M12 15v4"/><path d="M8 21h8"/>';
    $iconLogout= '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>';
    $iconLogin = '<path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>';
@endphp

    <header class="topbar rise" style="--i:0" role="banner">
        <div class="topbar-inner">
            <div style="display:flex;align-items:center;gap:.5rem;min-width:0;">
                @auth
                    <a href="{{ route('hub') }}" class="nav-link hide-sm" title="Hub">
                        {!! $svg($iconHome) !!}<span>Hub</span>
                    </a>
                @endauth
                <a href="{{ route('play.home') }}" class="brand">
                    {!! $logo() !!}
                    <span class="brand-text">
                        {!! $wordmark('wm-sm') !!}
                        <span class="brand-tag">Playground</span>
                    </span>
                </a>
            </div>

            <div class="topbar-actions">
                @auth
                    <div class="nav-desktop">
                        <a href="{{ route('play.home') }}" class="nav-link {{ request()->routeIs('play.home') ? 'is-active' : '' }}">{!! $svg($iconHome) !!}<span>Lobby</span></a>
                        <a href="{{ route('play.decks.index') }}" class="nav-link {{ request()->routeIs('play.decks.*') ? 'is-active' : '' }}">{!! $svg($iconDeck) !!}<span>Deck</span></a>
                        <a href="{{ route('play.gacha.index') }}" class="nav-link {{ request()->routeIs('play.gacha.*') ? 'is-active' : '' }}">{!! $svg($iconGacha) !!}<span>Gacha</span></a>
                        <a href="{{ route('play.battle.pve') }}" class="nav-link {{ request()->routeIs('play.battle.*') ? 'is-active' : '' }}">{!! $svg($iconSword) !!}<span>Battle</span></a>
                        <a href="{{ route('play.leaderboard') }}" class="nav-link {{ request()->routeIs('play.leaderboard') ? 'is-active' : '' }}">{!! $svg($iconTrophy) !!}<span>Rank</span></a>
                    </div>

                    <div class="points-pill hide-sm">
                        <span class="dot"></span><b>{{ $pointsDisplay }}</b><span>Poin</span>
                    </div>

                    <a href="{{ route('profile.show') }}" class="profile-ring">
                        <x-navo :skin="auth()->user()->navoProfile?->equippedSkin" size="w-6 h-6" textSize="text-[10px]" />
                    </a>

                    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn-icon">{!! $svg($iconLogout) !!}</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-link">{!! $svg($iconLogin) !!}<span>Masuk</span></a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Gabung</a>
                @endauth

                <button id="theme-toggle" class="theme-toggle" type="button" aria-label="Ganti tema">
                    <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
                    <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                </button>
            </div>
        </div>
        
        {{-- MOBILE NAVIGATION --}}
        @auth
        <div class="nav-mobile">
            <a href="{{ route('play.home') }}" class="nav-link {{ request()->routeIs('play.home') ? 'is-active' : '' }}">{!! $svg($iconHome) !!}<span>Lobby</span></a>
            <a href="{{ route('play.decks.index') }}" class="nav-link {{ request()->routeIs('play.decks.*') ? 'is-active' : '' }}">{!! $svg($iconDeck) !!}<span>Deck</span></a>
            <a href="{{ route('play.gacha.index') }}" class="nav-link {{ request()->routeIs('play.gacha.*') ? 'is-active' : '' }}">{!! $svg($iconGacha) !!}<span>Gacha</span></a>
            <a href="{{ route('play.battle.pve') }}" class="nav-link {{ request()->routeIs('play.battle.*') ? 'is-active' : '' }}">{!! $svg($iconSword) !!}<span>Battle</span></a>
            <a href="{{ route('play.leaderboard') }}" class="nav-link {{ request()->routeIs('play.leaderboard') ? 'is-active' : '' }}">{!! $svg($iconTrophy) !!}<span>Rank</span></a>
        </div>
        @endauth
    </header>

    <main class="page-main">
        @yield('content')
    </main>

    <script>
    (function () {
        const root = document.documentElement;
        const toggle = document.getElementById('theme-toggle');
        if (!toggle) return;

        toggle.addEventListener('click', () => {
            const current = root.getAttribute('data-theme') || 'dark';
            const next = current === 'dark' ? 'light' : 'dark';
            root.setAttribute('data-theme', next);
            localStorage.setItem('navoclash.theme', next);
        });
    })();
    </script>

</body>
</html>