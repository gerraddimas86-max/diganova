<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DIGANOVA Events — Plan It. Join It. Enjoy It.</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,200..800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- ============================================================
         DETEKSI AWAL — harus SEBELUM paint (anti-flash)
         Logika:
         - reload / first visit          → intro
         - navigasi apapun (link, back)  → arrival transition
         ============================================================ --}}
    <script>
    (function () {
        try {
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

            const nav = performance.getEntriesByType && performance.getEntriesByType('navigation')[0];
            const navType = nav ? nav.type : 'navigate';
            const visited = sessionStorage.getItem('diganova:visited') === '1';

            if (navType === 'reload' || !visited) {
                sessionStorage.setItem('diganova:visited', '1');
                document.documentElement.classList.add('show-intro');
            } else {
                // Navigasi apapun (link, back button, direct URL) → transition
                document.documentElement.classList.add('is-from-nav');
            }
        } catch (e) {}
    })();
    </script>

    <style>
        /* ===== BASE ===== */
        * { box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        html { scroll-behavior: smooth; }
        html, body { background: #090E39; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Bricolage Grotesque', system-ui, sans-serif;
            color: #fff;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }
        .font-display { font-family: 'Bricolage Grotesque', system-ui, sans-serif; }

        /* ===== Skip link ===== */
        .skip-link {
            position: fixed; top: -100px; left: 50%; transform: translateX(-50%);
            z-index: 999; background: #fff; color: #090E39;
            padding: .7rem 1.4rem; font-weight: 800; font-size: .85rem;
            border-radius: .75rem; text-decoration: none;
            border: 2px solid #4382DF;
            box-shadow: 4px 4px 0 #4382DF;
            transition: top .2s ease;
        }
        .skip-link:focus { top: 1rem; }

        /* ===== Extrude ===== */
        .ex-w { --ec: #FFFFFF; }
        .ex-b { --ec: #4382DF; }
        .ex-i { --ec: #4647AE; }
        .ex-d { --ec: #112E81; }
        .ex-k { --ec: #000000; }
        .extrude {
            box-shadow:
                1px 1px 0 var(--ec), 2px 2px 0 var(--ec), 3px 3px 0 var(--ec), 4px 4px 0 var(--ec),
                5px 5px 0 var(--ec), 6px 6px 0 var(--ec), 7px 7px 0 var(--ec), 8px 8px 0 var(--ec),
                14px 20px 30px rgba(0,0,0,.5);
        }
        .extrude-sm {
            box-shadow:
                1px 1px 0 var(--ec), 2px 2px 0 var(--ec), 3px 3px 0 var(--ec), 4px 4px 0 var(--ec),
                8px 12px 18px rgba(0,0,0,.45);
        }

        /* ===== 3D Button ===== */
        .btn-3d {
            --ec: #4382DF;
            transform: translate(var(--tx, 0px), var(--ty, 0px));
            box-shadow: 6px 6px 0 var(--ec), 6px 14px 24px rgba(0,0,0,.45);
            transition: transform .15s ease, box-shadow .15s ease;
        }
        .btn-3d:hover {
            transform: translate(calc(var(--tx, 0px) + 3px), calc(var(--ty, 0px) + 3px));
            box-shadow: 3px 3px 0 var(--ec), 3px 8px 14px rgba(0,0,0,.4);
        }
        .btn-3d:active {
            transform: translate(calc(var(--tx, 0px) + 6px), calc(var(--ty, 0px) + 6px));
            box-shadow: 0 0 0 var(--ec);
        }

        /* ===== Grid background ===== */
        .home-grid {
            background-image:
                linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
            background-size: 48px 48px;
            mask-image: radial-gradient(ellipse at 50% 25%, #000 25%, transparent 75%);
            -webkit-mask-image: radial-gradient(ellipse at 50% 25%, #000 25%, transparent 75%);
        }

        /* ===== Hover pop ===== */
        .hov-pop {
            transition: scale .3s cubic-bezier(.3,1.8,.5,1), rotate .3s cubic-bezier(.3,1.8,.5,1), box-shadow .2s;
        }
        .hov-pop:hover { scale: 1.05; rotate: -2deg; z-index: 5; }

        /* ===== Tilt ===== */
        [data-tilt] {
            position: relative;
            transition: transform .15s ease-out;
            transform-style: preserve-3d;
            will-change: transform;
        }
        .glare {
            position: absolute; inset: 0; border-radius: inherit; pointer-events: none;
            opacity: 0; transition: opacity .3s; z-index: 5;
            background: radial-gradient(circle at var(--gx, 50%) var(--gy, 50%), rgba(255,255,255,.3), transparent 50%);
        }
        [data-tilt]:hover > .glare { opacity: 1; }

        /* ===== Orbs ===== */
        .orb {
            --oc: #4382DF; --os: rgba(9,14,57,.34); --oh: #fff; --oe: #000;
            border-radius: 9999px; border: 3px solid #fff;
            background:
                radial-gradient(ellipse 13% 8% at 27% 23%, var(--oh) 0 92%, transparent 100%),
                radial-gradient(circle at 32% 30%, transparent 0 60%, var(--os) 61%),
                var(--oc);
            box-shadow: 4px 5px 0 var(--oe), 10px 16px 24px rgba(0,0,0,.35);
        }
        .orb-i { --oc: #4647AE; --oe: #000; }
        .orb-w { --oc: #fff; --os: rgba(67,130,223,.4); --oe: #4382DF; }
        .orb-lg { border-width: 5px; box-shadow: 8px 10px 0 var(--oe), 18px 28px 40px rgba(0,0,0,.35); }

        /* ===== Floating animations ===== */
        .floaty    { animation: floaty 6s ease-in-out infinite; }
        .floaty-2  { animation: floaty 7.5s ease-in-out infinite reverse; }
        .wiggle    { animation: wiggle 2.4s ease-in-out infinite; }
        .breathe   { animation: breathe 4s ease-in-out infinite; }
        @keyframes floaty {
            0%, 100% { transform: translateY(0) rotate(var(--rot, 0deg)); }
            50%      { transform: translateY(-14px) rotate(calc(var(--rot, 0deg) + 2deg)); }
        }
        @keyframes wiggle {
            0%, 100% { transform: rotate(-5deg); }
            50%      { transform: rotate(5deg); }
        }
        @keyframes breathe {
            0%, 100% { transform: scale(1); }
            50%      { transform: scale(1.08); }
        }
        @keyframes rise {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: none; }
        }
        .rise {
            opacity: 0;
            animation: rise .7s cubic-bezier(.2,.8,.2,1) forwards;
            animation-delay: calc(var(--i, 0) * 90ms + 80ms);
        }

        /* ============================================================
           INTRO LOADER — versi baru (lebih menarik)
           ============================================================ */
        .intro-loader {
            position: fixed;
            inset: 0;
            z-index: 10000;
            display: grid;
            place-items: center;
            background: #090E39;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity .45s ease, visibility 0s ease .45s;
        }
        html.show-intro .intro-loader {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transition: opacity .45s ease, visibility 0s ease 0s;
        }

        /* Ambient glow di belakang */
        .intro-bg-glow {
            position: absolute;
            inset: 0;
            pointer-events: none;
            background:
                radial-gradient(circle at 50% 45%, rgba(67,130,223,.22) 0%, transparent 55%),
                radial-gradient(circle at 50% 45%, rgba(70,71,174,.28) 0%, transparent 75%);
            animation: introGlowPulse 3s ease-in-out infinite;
        }
        @keyframes introGlowPulse {
            0%, 100% { opacity: .55; transform: scale(1); }
            50%      { opacity: 1;   transform: scale(1.05); }
        }

        .intro-content {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .9rem;
        }

        /* Stage — orbit rings + logo */
        .intro-stage {
            position: relative;
            width: 11rem;
            height: 11rem;
            display: grid;
            place-items: center;
            margin-bottom: .25rem;
        }
        .intro-orbit {
            position: absolute;
            border-radius: 50%;
            border: 1px dashed rgba(67,130,223,.4);
        }
        .intro-orbit-1 {
            inset: 0;
            animation: spin360 8s linear infinite;
        }
        .intro-orbit-2 {
            inset: 1.5rem;
            border-color: rgba(255,255,255,.22);
            animation: spin360 6s linear infinite reverse;
        }
        .intro-orbit-3 {
            inset: 2.9rem;
            border: 1px solid rgba(67,130,223,.65);
            animation: spin360 4s linear infinite;
        }
        .intro-dot {
            position: absolute;
            top: -4px;
            left: 50%;
            width: 8px;
            height: 8px;
            margin-left: -4px;
            border-radius: 50%;
            background: #4382DF;
            box-shadow: 0 0 12px #4382DF, 0 0 24px rgba(67,130,223,.6);
        }
        .intro-orbit-2 .intro-dot {
            background: #fff;
            box-shadow: 0 0 12px #fff, 0 0 24px rgba(255,255,255,.5);
        }
        .intro-orbit-3 .intro-dot {
            background: #22D3EE;
            box-shadow: 0 0 12px #22D3EE, 0 0 24px rgba(34,211,238,.7);
        }

        /* Logo */
        .intro-logo-wrap {
            position: relative;
            width: 5rem;
            height: 5rem;
            display: grid;
            place-items: center;
            animation: introLogoIn .9s cubic-bezier(.34,1.56,.64,1) .2s both;
        }
        .intro-logo-glow {
            position: absolute;
            inset: -35%;
            border-radius: 50%;
            pointer-events: none;
            background: radial-gradient(circle, rgba(67,130,223,.55) 0%, transparent 70%);
            animation: introGlowPulse 2s ease-in-out infinite;
        }
        .intro-logo {
            position: relative;
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 0 22px rgba(67,130,223,.85));
            animation: introLogoFloat 3s ease-in-out infinite;
        }

        /* Wordmark — letter reveal */
        .intro-brand {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-weight: 800;
            font-size: 2rem;
            letter-spacing: -.02em;
            color: #fff;
            display: flex;
            flex-wrap: nowrap;
            line-height: 1;
        }
        .intro-letter {
            display: inline-block;
            opacity: 0;
            animation: introLetterIn .5s cubic-bezier(.34,1.56,.64,1) both;
        }
        .intro-letter:nth-child(1) { animation-delay: .30s; }
        .intro-letter:nth-child(2) { animation-delay: .35s; }
        .intro-letter:nth-child(3) { animation-delay: .40s; }
        .intro-letter:nth-child(4) { animation-delay: .45s; }
        .intro-letter:nth-child(5) { animation-delay: .50s; }
        .intro-letter:nth-child(6) { animation-delay: .55s; }
        .intro-letter:nth-child(7) { animation-delay: .60s; }
        .intro-letter:nth-child(8) { animation-delay: .65s; }
        .intro-letter.accent { color: #4382DF; }

        /* Tagline */
        .intro-tagline {
            font-size: 10px;
            letter-spacing: .3em;
            text-transform: uppercase;
            color: rgba(255,255,255,.55);
            font-weight: 800;
            animation: introFadeUp .5s ease .85s both;
        }

        /* Progress */
        .intro-progress {
            margin-top: .75rem;
            width: 12rem;
            height: 3px;
            background: rgba(255,255,255,.08);
            border-radius: 99px;
            overflow: hidden;
            position: relative;
            animation: introFadeUp .5s ease .9s both;
        }
        .intro-progress-fill {
            height: 100%;
            width: 0;
            border-radius: inherit;
            background: linear-gradient(90deg, #4382DF, #22D3EE);
            animation: introBarFill 1s cubic-bezier(.4,0,.2,1) .6s forwards;
            position: relative;
            overflow: hidden;
        }
        .intro-progress-fill::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.7), transparent);
            animation: introShimmer 1.2s ease-in-out infinite;
        }

        /* Keyframes intro */
        @keyframes spin360 { to { transform: rotate(360deg); } }
        @keyframes introLogoIn {
            0%   { transform: scale(.2) rotate(-180deg); opacity: 0; }
            70%  { transform: scale(1.1) rotate(10deg);  opacity: 1; }
            100% { transform: scale(1) rotate(0);        opacity: 1; }
        }
        @keyframes introLogoFloat {
            0%, 100% { transform: translateY(0); }
            50%      { transform: translateY(-6px); }
        }
        @keyframes introLetterIn {
            0%   { opacity: 0; transform: translateY(20px) rotateX(-90deg); }
            100% { opacity: 1; transform: translateY(0) rotateX(0); }
        }
        @keyframes introFadeUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: none; }
        }
        @keyframes introBarFill {
            from { width: 0; }
            to   { width: 100%; }
        }
        @keyframes introShimmer {
            0%   { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        /* ============================================================
           PAGE TRANSITION — versi baru
           ============================================================ */
        .page-transition {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: grid;
            place-items: center;
            background: #090E39;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity .35s ease, visibility 0s ease .35s;
        }
        .page-transition.is-active {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transition: opacity .35s ease, visibility 0s ease 0s;
        }
        /* Saat datang dari navigasi — tampil instan sebelum paint */
        html.is-from-nav .page-transition {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transition: none;
        }

        .transition-bg-glow {
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: radial-gradient(circle at 50% 45%, rgba(67,130,223,.18) 0%, transparent 60%);
        }

        .transition-content {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .75rem;
        }
        .transition-mark {
            position: relative;
            width: 4rem;
            height: 4rem;
            display: grid;
            place-items: center;
            margin-bottom: .25rem;
        }
        .transition-mark::before,
        .transition-mark::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 2px solid #4382DF;
            animation: transitionPulse 1.6s ease-out infinite;
        }
        .transition-mark::after {
            animation-delay: .8s;
        }
        @keyframes transitionPulse {
            0%   { transform: scale(.9); opacity: .9; }
            100% { transform: scale(2.2); opacity: 0; }
        }
        .transition-logo {
            position: relative;
            z-index: 2;
            width: 2.6rem;
            height: 2.6rem;
            object-fit: contain;
            filter: drop-shadow(0 0 12px rgba(67,130,223,.7));
            animation: transitionLogoPulse 1.2s ease-in-out infinite;
        }
        @keyframes transitionLogoPulse {
            0%, 100% { transform: scale(1); }
            50%      { transform: scale(1.1); }
        }
        .transition-brand {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-weight: 800;
            font-size: 1.4rem;
            letter-spacing: -.02em;
            color: #fff;
        }
        .transition-brand span { color: #4382DF; }
        .transition-bar {
            width: 6rem;
            height: 3px;
            border-radius: 99px;
            overflow: hidden;
            background: rgba(255,255,255,.08);
        }
        .transition-bar-fill {
            height: 100%;
            width: 40%;
            border-radius: inherit;
            background: linear-gradient(90deg, #4382DF, #22D3EE);
            animation: transitionSlide 1.1s ease-in-out infinite;
        }
        @keyframes transitionSlide {
            0%   { transform: translateX(-100%); }
            100% { transform: translateX(250%); }
        }

        /* ===== Reduced motion ===== */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
            }
            .rise { opacity: 1; }
        }
    </style>
</head>
<body>

{{-- ============================================================
     INTRO LOADER
     ============================================================ --}}
<div id="intro-loader" class="intro-loader" aria-hidden="true">
    <div class="intro-bg-glow"></div>
    <div class="intro-content">
        <div class="intro-stage">
            <div class="intro-orbit intro-orbit-1"><span class="intro-dot"></span></div>
            <div class="intro-orbit intro-orbit-2"><span class="intro-dot"></span></div>
            <div class="intro-orbit intro-orbit-3"><span class="intro-dot"></span></div>
            <div class="intro-logo-wrap">
                <div class="intro-logo-glow"></div>
                <img src="{{ asset('images/logo.png') }}" alt="Logo DIGANOVA" class="intro-logo" draggable="false">
            </div>
        </div>

        <div class="intro-brand">
            <span class="intro-letter">D</span>
            <span class="intro-letter">I</span>
            <span class="intro-letter">G</span>
            <span class="intro-letter">A</span>
            <span class="intro-letter accent">N</span>
            <span class="intro-letter accent">O</span>
            <span class="intro-letter accent">V</span>
            <span class="intro-letter accent">A</span>
        </div>

        <div class="intro-tagline">Plan It. Join It. Enjoy It.</div>
        <div class="intro-progress"><div class="intro-progress-fill"></div></div>
    </div>
</div>

{{-- ============================================================
     PAGE TRANSITION
     ============================================================ --}}
<div id="page-transition" class="page-transition" aria-hidden="true">
    <div class="transition-bg-glow"></div>
    <div class="transition-content">
        <div class="transition-mark">
            <img src="{{ asset('images/logo.png') }}" alt="Logo DIGANOVA" class="transition-logo" draggable="false">
        </div>
        <div class="transition-brand">DIGA<span>NOVA</span></div>
        <div class="transition-bar"><div class="transition-bar-fill"></div></div>
    </div>
</div>

@php
    /* ====== Icon library (Lucide-style stroke) ====== */
    $icons = [
        'hand'     => '<path d="M18 11V6a2 2 0 0 0-4 0v5"/><path d="M14 10V4a2 2 0 0 0-4 0v6"/><path d="M10 10.5V6a2 2 0 0 0-4 0v8"/><path d="M18 8a2 2 0 1 1 4 0v6a8 8 0 0 1-8 8h-2c-2.8 0-4.5-.86-5.99-2.34l-3.6-3.6a2 2 0 0 1 2.83-2.82L7 15"/>',
        'ticket'   => '<path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><path d="M13 5v2M13 17v2M13 11v2"/>',
        'party'    => '<path d="M5.8 11.3 2 22l10.7-3.79"/><path d="M4 3h.01M22 8h.01M15 2h.01M22 20h.01"/><path d="m22 2-2.24.75a2.9 2.9 0 0 0-1.96 3.12c.1.86-.57 1.63-1.45 1.63h-.38c-.86 0-1.6.6-1.76 1.44L14 10"/><path d="m22 13-.82-.33c-.86-.34-1.82.2-1.98 1.11-.11.7-.72 1.22-1.43 1.22H17"/><path d="m11 2 .33.82c.34.86-.2 1.82-1.11 1.98C9.52 4.9 9 5.52 9 6.23V7"/><path d="M11 13c1.93 1.93 2.83 4.17 2 5-.83.83-3.07-.07-5-2-1.93-1.93-2.83-4.17-2-5 .83-.83 3.07.07 5 2z"/>',
        'sparkles' => '<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/><path d="M20 3v4M22 5h-4M4 17v2M5 18H3"/>',
        'music'    => '<path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/>',
        'calendar' => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
        'map-pin'  => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
        'mic'      => '<rect x="9" y="2" width="6" height="12" rx="3"/><path d="M19 10v2a7 7 0 0 1-14 0v-2M12 19v3"/>',
        'shirt'    => '<path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/>',
        'palette'  => '<circle cx="13.5" cy="6.5" r=".5"/><circle cx="17.5" cy="10.5" r=".5"/><circle cx="8.5" cy="7.5" r=".5"/><circle cx="6.5" cy="12.5" r=".5"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.93 0 1.5-.75 1.5-1.5 0-.39-.15-.73-.39-1-.23-.27-.38-.62-.38-1 0-.83.67-1.5 1.5-1.5H16c3.31 0 6-2.69 6-6 0-4.96-4.49-9-10-9z"/>',
        'gamepad'  => '<path d="M6 11h4M8 9v4M15 12h.01M18 10h.01"/><path d="M17.32 5H6.68a4 4 0 0 0-3.98 3.59C2.6 9.42 2 14.46 2 16a3 3 0 0 0 3 3c1 0 1.5-.5 2-1l1.41-1.41A2 2 0 0 1 9.83 16h4.34a2 2 0 0 1 1.41.59L17 18c.5.5 1 1 2 1a3 3 0 0 0 3-3c0-1.54-.6-6.58-.69-7.26A4 4 0 0 0 17.32 5z"/>',
    ];

    $icon = fn (string $name, string $class = 'w-5 h-5') =>
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';
@endphp

    <a href="#hero" class="skip-link">Langsung ke konten →</a>

    <div id="landing-page" class="relative min-h-screen bg-[#090E39] text-white overflow-hidden flex flex-col">

        {{-- ================= BACKGROUND DECOR ================= --}}
        <div class="absolute inset-0 home-grid opacity-50 pointer-events-none" aria-hidden="true"></div>
        <div class="absolute -top-40 -left-40 w-[38rem] h-[38rem] bg-[#4647AE]/40 rounded-full blur-[120px] pointer-events-none" aria-hidden="true"></div>
        <div class="absolute top-1/3 -right-40 w-[38rem] h-[38rem] bg-[#4382DF]/25 rounded-full blur-[120px] pointer-events-none" aria-hidden="true"></div>

        {{-- ================= TOPBAR ================= --}}
        <header class="relative z-20 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 pt-6 pb-2 flex items-center justify-between gap-4 rise" style="--i:0" role="banner">
            <a href="#hero" class="inline-flex items-center gap-3 group" aria-label="DIGANOVA — kembali ke atas">
                <img src="{{ asset('images/logo.png') }}" alt="Logo DIGANOVA" class="w-10 h-10 object-contain drop-shadow-lg group-hover:scale-105 transition-transform" draggable="false">
                <div class="leading-none">
                    <div class="font-display font-extrabold text-lg sm:text-xl tracking-tight">
                        DIGA<span class="text-[#4382DF]">NOVA</span>
                    </div>
                    <div class="text-[10px] tracking-[.18em] uppercase text-white/50 mt-1 hidden sm:block">
                        Plan It. Join It. Enjoy It.
                    </div>
                </div>
            </a>

            <div class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center px-4 py-2 text-sm font-extrabold text-white/80 hover:text-white transition-colors">
                    Login
                </a>
                <a href="{{ route('register') }}"
                   class="btn-3d ex-b inline-flex items-center gap-2 bg-[#4382DF] text-white px-5 py-2.5 rounded-2xl font-extrabold text-sm border-2 border-white">
                    Daftar <span aria-hidden="true">→</span>
                </a>
            </div>
        </header>

        {{-- ================= HERO ================= --}}
        <main id="hero" class="relative z-10 flex-1" role="main">
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 lg:pt-16 pb-16 grid lg:grid-cols-2 gap-10 lg:gap-12 items-center">

                {{-- ----- KIRI ----- --}}
                <div class="text-center lg:text-left">

                    <span class="rise hov-pop inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white text-[#090E39] text-sm font-bold extrude-sm ex-b mb-6" style="--i:1">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#4382DF] opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#4647AE]"></span>
                        </span>
                        <span>Festival &amp; Komunitas · {{ date('Y') }}</span>
                        <span class="text-[#4647AE]">{!! $icon('sparkles', 'w-4 h-4') !!}</span>
                    </span>

                    <h1 class="rise font-display text-4xl sm:text-5xl xl:text-6xl font-extrabold leading-[1.05] tracking-tight mb-5" style="--i:2">
                        Temukan
                        <span class="relative inline-block mx-1 my-1">
                            <span class="absolute inset-0 bg-[#4647AE] -rotate-2 rounded-2xl extrude-sm ex-k" aria-hidden="true"></span>
                            <span class="relative px-3">festival</span>
                        </span>
                        impianmu di sini.
                    </h1>

                    <p class="rise text-base sm:text-lg text-white/70 max-w-xl mx-auto lg:mx-0 mb-8" style="--i:3">
                        Satu platform untuk semua event musik, cosplay, seni, dan turnamen game.
                        Gabung komunitas, ambil e-ticket, dan kumpulkan pencapaianmu.
                    </p>

                    <div class="rise flex flex-wrap justify-center lg:justify-start gap-3 sm:gap-4 mb-10" style="--i:4">
                        <a href="{{ route('register') }}"
                           class="btn-3d ex-i inline-flex items-center gap-2 bg-[#4382DF] text-white px-6 py-3 rounded-2xl font-extrabold border-2 border-white text-sm sm:text-base">
                            Mulai Sekarang <span aria-hidden="true">→</span>
                        </a>
                        <a href="{{ route('login') }}"
                           class="btn-3d ex-k inline-flex items-center gap-2 bg-white text-[#090E39] px-6 py-3 rounded-2xl font-extrabold border-2 border-white text-sm sm:text-base">
                            Masuk Akun
                        </a>
                    </div>

                    <div class="rise flex flex-wrap justify-center lg:justify-start gap-3 sm:gap-4" style="--i:5">
                        <div data-tilt data-rot="-2"
                             class="hov-pop extrude-sm ex-b bg-white text-[#090E39] border-2 border-white rounded-2xl px-4 sm:px-5 py-3.5 flex items-center gap-3 min-w-[10rem]">
                            <span class="grid place-items-center w-11 h-11 rounded-xl bg-[#4382DF] text-white shrink-0">
                                {!! $icon('party', 'w-5 h-5') !!}
                            </span>
                            <div class="leading-tight text-left">
                                <p class="font-display font-extrabold text-2xl">500+</p>
                                <p class="text-[10px] uppercase tracking-widest font-extrabold text-[#112E81]/70">Event</p>
                            </div>
                        </div>

                        <div data-tilt data-rot="1.5"
                             class="hov-pop extrude-sm ex-i bg-[#4382DF] text-[#090E39] border-2 border-white rounded-2xl px-4 sm:px-5 py-3.5 flex items-center gap-3 min-w-[10rem]">
                            <span class="grid place-items-center w-11 h-11 rounded-xl bg-[#090E39] text-white shrink-0">
                                {!! $icon('ticket', 'w-5 h-5') !!}
                            </span>
                            <div class="leading-tight text-left">
                                <p class="font-display font-extrabold text-2xl">10K+</p>
                                <p class="text-[10px] uppercase tracking-widest font-extrabold text-[#112E81]/70">Member</p>
                            </div>
                        </div>

                        <div data-tilt data-rot="-1"
                             class="hov-pop extrude-sm ex-b bg-white text-[#090E39] border-2 border-white rounded-2xl px-4 sm:px-5 py-3.5 flex items-center gap-3 min-w-[10rem]">
                            <span class="grid place-items-center w-11 h-11 rounded-xl bg-[#4647AE] text-white shrink-0">
                                {!! $icon('sparkles', 'w-5 h-5') !!}
                            </span>
                            <div class="leading-tight text-left">
                                <p class="font-display font-extrabold text-2xl">24</p>
                                <p class="text-[10px] uppercase tracking-widest font-extrabold text-[#112E81]/70">Badge</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ----- KANAN ----- --}}
                <div class="relative h-[26rem] sm:h-[30rem] md:h-[34rem] max-w-md mx-auto w-full" data-parallax>
                    <div data-depth="40" class="absolute top-2 right-6">
                        <div class="orb orb-lg breathe w-20 h-20"></div>
                    </div>
                    <div data-depth="55" class="absolute bottom-16 left-0">
                        <div class="orb orb-i floaty-2 w-12 h-12"></div>
                    </div>
                    <div data-depth="30" class="absolute top-1/4 left-2">
                        <div class="orb orb-w floaty w-8 h-8"></div>
                    </div>

                    <div data-depth="45" class="absolute top-4 left-6 wiggle w-16 h-16 rounded-2xl bg-white text-[#4647AE] grid place-items-center border-2 border-white extrude-sm ex-b">
                        {!! $icon('sparkles', 'w-8 h-8') !!}
                    </div>
                    <div data-depth="35" class="absolute top-1/2 right-0 floaty w-14 h-14 rounded-full bg-[#4647AE] text-white border-2 border-white grid place-items-center extrude-sm ex-k">
                        {!! $icon('music', 'w-6 h-6') !!}
                    </div>

                    <div data-depth="50" class="absolute top-16 right-0 inline-flex items-center gap-2 px-3 py-1.5 bg-white text-[#090E39] rounded-full border-2 border-white extrude-sm ex-b text-[11px] font-extrabold">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#4382DF] opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-[#4647AE]"></span>
                        </span>
                        12 slot tersisa
                    </div>

                    <div class="relative z-10 h-full flex flex-col items-center justify-between pt-6 pb-0">
                        <img src="{{ asset('images/navo-mascot.png') }}"
                             alt="Navo mascot"
                             class="max-h-[55%] w-auto hover:scale-105 transition-transform duration-500 drop-shadow-2xl">

                        <div class="w-64 sm:w-72 hov-pop" data-tilt data-rot="-2">
                            <div class="bg-white text-[#090E39] rounded-2xl border-2 border-white extrude-sm ex-b p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] uppercase tracking-[.18em] font-extrabold text-[#4647AE]">E-Ticket</span>
                                    <span class="inline-flex items-center gap-1 text-[10px] uppercase font-extrabold text-[#112E81]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Valid
                                    </span>
                                </div>
                                <h3 class="font-display font-extrabold text-lg mt-2 leading-tight">
                                    Neon Night Music Fest
                                </h3>
                                <div class="flex flex-wrap gap-1.5 mt-2">
                                    <span class="text-[9px] uppercase tracking-widest font-extrabold px-2 py-0.5 rounded-full bg-[#4382DF] text-white">Musik</span>
                                    <span class="text-[9px] uppercase tracking-widest font-extrabold px-2 py-0.5 rounded-full border-2 border-[#090E39]/20">Palembang</span>
                                    <span class="text-[9px] uppercase tracking-widest font-extrabold px-2 py-0.5 rounded-full border-2 border-[#090E39]/20">Live</span>
                                </div>
                                <div class="border-t-2 border-dashed border-[#090E39]/15 mt-3 pt-3 grid grid-cols-2 gap-2">
                                    <div>
                                        <p class="text-[9px] uppercase tracking-widest font-extrabold text-[#112E81]/70">Tanggal</p>
                                        <p class="font-display font-extrabold text-sm mt-0.5">24 Agu</p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] uppercase tracking-widest font-extrabold text-[#112E81]/70">Kursi</p>
                                        <p class="font-display font-extrabold text-sm text-[#4647AE] mt-0.5">VIP-07</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        {{-- ================= FOOTBAR ================= --}}
        <footer class="relative z-10 border-t-2 border-white/10" role="contentinfo">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex items-center justify-between flex-wrap gap-4 text-[11px] uppercase tracking-widest font-extrabold text-white/50">
                <span class="inline-flex items-center gap-3 flex-wrap">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo DIGANOVA" class="w-6 h-6 object-contain" draggable="false">
                    <span>DIGA<span class="text-[#4382DF]">NOVA</span></span>
                    <span class="text-white/30 hidden sm:inline">·</span>
                    <span class="hidden sm:inline">Plan It. Join It. Enjoy It.</span>
                </span>
                <span>© {{ date('Y') }} DIGANOVA Events</span>
            </div>
        </footer>
    </div>

    {{-- ============================================================
         INTRO + PAGE TRANSITION JS (v3 — robust + return fix)
         ============================================================ --}}
    <script>
    (function () {
        const html = document.documentElement;
        const trans = document.getElementById('page-transition');
        const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        /* --------------------------------------------------------
           1. INTRO (reload / first visit)
           -------------------------------------------------------- */
        if (html.classList.contains('show-intro')) {
            const MIN_MS = 1600; // cukup untuk semua animasi intro selesai
            const startedAt = performance.now();
            let hidden = false;

            const hideIntro = () => {
                if (hidden) return;
                hidden = true;
                html.classList.remove('show-intro');
            };

            const tryHide = () => {
                const elapsed = performance.now() - startedAt;
                setTimeout(hideIntro, Math.max(0, MIN_MS - elapsed));
            };

            if (document.readyState !== 'loading') {
                tryHide();
            } else {
                document.addEventListener('DOMContentLoaded', tryHide, { once: true });
            }
            setTimeout(hideIntro, 5000); // safety
        }

        /* --------------------------------------------------------
           2. ARRIVAL — saat datang dari navigasi
           Overlay sudah terlihat (is-from-nav). Fade out sekarang.
           -------------------------------------------------------- */
        if (html.classList.contains('is-from-nav') && trans) {
            if (reduced) {
                html.classList.remove('is-from-nav');
            } else {
                const anim = trans.animate(
                    [{ opacity: 1 }, { opacity: 0 }],
                    { duration: 420, easing: 'ease-out', fill: 'forwards' }
                );

                let finished = false;
                const finish = () => {
                    if (finished) return;
                    finished = true;
                    html.classList.remove('is-from-nav');
                    try { anim.cancel(); } catch (e) {}
                };
                anim.onfinish = finish;
                anim.oncancel = finish;
                setTimeout(finish, 900);
            }
        }

        /* --------------------------------------------------------
           3. RETURN — user back button (bfcache restore)
           FIX: tampilkan return transition, bukan langsung dihilangkan.
           -------------------------------------------------------- */
        window.addEventListener('pageshow', (e) => {
            if (!e.persisted) return;

            // Bersihkan class sisa dari state sebelumnya
            html.classList.remove('is-from-nav');
            html.classList.remove('show-intro');

            if (!trans) return;

            // Cancel animasi WAAPI yang mungkin masih nyangkut
            if (typeof trans.getAnimations === 'function') {
                trans.getAnimations().forEach(a => {
                    try { a.cancel(); } catch (err) {}
                });
            }

            // Reset semua inline style dari sesi sebelumnya
            trans.style.cssText = '';
            trans.classList.remove('is-active');

            if (reduced) return;

            // --- Tampilkan return transition ---
            // 1. Tampil instan (tanpa transisi)
            trans.style.transition = 'none';
            trans.style.opacity = '1';
            trans.style.visibility = 'visible';
            trans.style.pointerEvents = 'auto';

            // 2. Force reflow agar browser "melihat" state awal
            void trans.offsetWidth;

            // 3. Aktifkan transisi fade-out
            trans.style.transition = 'opacity 0.45s ease-out, visibility 0s ease 0.45s';

            // 4. Fade out setelah jeda singkat
            setTimeout(() => {
                trans.style.opacity = '0';
                trans.style.visibility = 'hidden';
                trans.style.pointerEvents = 'none';
            }, 520);

            // 5. Bersihkan inline style
            setTimeout(() => {
                trans.style.cssText = '';
            }, 1200);
        });

        /* --------------------------------------------------------
           4. LEAVE — intercept link internal
           -------------------------------------------------------- */
        if (!reduced && trans) {
            document.querySelectorAll('a[href]').forEach(link => {
                link.addEventListener('click', (e) => {
                    const href = link.getAttribute('href');
                    if (!href) return;
                    if (href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:') || href.startsWith('javascript:')) return;
                    if (link.target === '_blank' || link.hasAttribute('download')) return;
                    if (e.metaKey || e.ctrlKey || e.shiftKey || e.button !== 0) return;
                    if (link.dataset.noTransition === 'true') return;

                    try {
                        const url = new URL(href, location.href);
                        if (url.pathname === location.pathname && url.search === location.search) return;
                    } catch (err) { return; }

                    e.preventDefault();

                    trans.classList.add('is-active');

                    // Navigate lebih cepat — CSS yang urus fade-in (250ms)
                    setTimeout(() => {
                        window.location.href = href;
                    }, 230);
                });
            });
        }
    })();

    /* ============================================================
       3D TILT + PARALLAX
       ============================================================ */
    (function () {
        const reduce   = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const canHover = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

        if (reduce || !canHover) return;

        document.querySelectorAll('[data-tilt]').forEach(el => {
            const rot  = parseFloat(el.dataset.rot || 0);
            const base = 'rotate(' + rot + 'deg)';
            el.style.transform = base;

            const glare = document.createElement('span');
            glare.className = 'glare';
            el.appendChild(glare);

            el.addEventListener('mousemove', (e) => {
                const r = el.getBoundingClientRect();
                const x = (e.clientX - r.left) / r.width  - 0.5;
                const y = (e.clientY - r.top)  / r.height - 0.5;
                el.style.transform =
                    'perspective(800px) ' + base +
                    ' rotateX(' + (-y * 10).toFixed(2) + 'deg)' +
                    ' rotateY(' + ( x * 10).toFixed(2) + 'deg)' +
                    ' translateY(-4px)';
                glare.style.setProperty('--gx', ((x + 0.5) * 100).toFixed(1) + '%');
                glare.style.setProperty('--gy', ((y + 0.5) * 100).toFixed(1) + '%');
            });
            el.addEventListener('mouseleave', () => { el.style.transform = base; });
        });

        document.querySelectorAll('[data-parallax]').forEach(scene => {
            const items = [...scene.querySelectorAll('[data-depth]')];
            let tx = 0, ty = 0, cx = 0, cy = 0;

            scene.addEventListener('mousemove', (e) => {
                const r = scene.getBoundingClientRect();
                tx = (e.clientX - r.left) / r.width  - 0.5;
                ty = (e.clientY - r.top)  / r.height - 0.5;
            });
            scene.addEventListener('mouseleave', () => { tx = 0; ty = 0; });

            (function loop() {
                cx += (tx - cx) * 0.08;
                cy += (ty - cy) * 0.08;
                items.forEach(el => {
                    const d = +el.dataset.depth;
                    el.style.transform = 'translate3d(' + (-cx * d).toFixed(2) + 'px,' + (-cy * d).toFixed(2) + 'px,0)';
                });
                requestAnimationFrame(loop);
            })();
        });
    })();
    </script>
</body>
</html>