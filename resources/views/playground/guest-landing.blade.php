<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#000000">
    <meta name="description" content="Bosen main game yang itu-itu aja? Bangun deck, kuasai arena, dan jadilah legenda di DIGANOVA NAVO CLASH.">
    <title>Kuasai Arena — DIGANOVA NAVO CLASH</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
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
           Dark  : pure black + cyan neon  (arena vibe)
           Light : slate-tinted soft grey + accessible cyan
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
            --mini-panel:    #12141A;

            --cyan:          #22D3EE;
            --accent-text:   #22D3EE;
            --cyan-ink:      #04141A;

            --glow:          rgba(34,211,238,.16);
            --shadow:        0 24px 48px -32px rgba(0,0,0,.9),
                             0 8px 24px -12px rgba(0,0,0,.6);
        }
        [data-theme="light"] {
            --bg:            #F1F5F9;     /* slate-100 — bukan putih murni */
            --bg-soft:       #F8FAFC;
            --text:          #0F172A;
            --muted:         rgba(15,23,42,.62);
            --faint:         rgba(15,23,42,.46);
            --line:          rgba(15,23,42,.09);
            --line-strong:   rgba(15,23,42,.20);
            --grid:          rgba(15,23,42,.045);
            --panel:         #FFFFFF;
            --mini-panel:    #FFFFFF;

            --accent-text:   #0891B2;      /* cyan lebih gelap, kontras di atas putih */
            --glow:          rgba(34,211,238,.28);
            --shadow:        0 24px 48px -28px rgba(15,23,42,.22),
                             0 8px 20px -10px rgba(15,23,42,.10);
        }

        /* ============================================================
           BASE
           ============================================================ */
        * { box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        html { scroll-behavior: smooth; }
        html, body { background: var(--bg); }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Anta', system-ui, sans-serif;
            font-weight: 400;
            color: var(--text);
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
            transition: background-color .35s ease, color .35s ease;
        }
        :where(a, button, [tabindex]):focus-visible {
            outline: 2px solid var(--cyan);
            outline-offset: 3px;
            border-radius: 2px;
        }
        .skip-link {
            position: fixed; top: -80px; left: 50%; transform: translateX(-50%);
            z-index: 999; background: var(--cyan); color: var(--cyan-ink);
            padding: .7rem 1.4rem; font-size: .8rem; text-decoration: none;
            border-radius: 2px; transition: top .2s ease;
        }
        .skip-link:focus { top: 1rem; }

        /* ============================================================
           TYPOGRAPHY
           ============================================================ */
        .h-display {
            font-family: 'Iceberg', serif;
            font-weight: 400;
            -webkit-text-stroke: .4px currentColor;
            letter-spacing: -.015em;
            line-height: 1.05;
            margin: 0;
            color: var(--text);
        }
        .kicker {
            font-family: 'Anta', sans-serif;
            font-size: .66rem;
            letter-spacing: .2em;
            text-transform: uppercase;
        }
        .accent { color: var(--accent-text); }

        .wm {
            display: inline-block;
            line-height: .85;
            letter-spacing: .01em;
            vertical-align: baseline;
            white-space: nowrap;
        }
        .wm .wm-ice {
            font-family: 'Iceberg', serif;
            -webkit-text-stroke: .4px currentColor;
            color: var(--accent-text);
            font-size: 1.06em;
        }
        .wm .wm-anta {
            font-family: 'Anta', sans-serif;
            -webkit-text-stroke: .3px currentColor;
            color: currentColor;
            letter-spacing: .04em;
            font-size: .86em;
            opacity: .92;
            margin-left: .28em;
        }
        .wm-sm { font-size: 1.02rem; }
        .wm-inline { font-size: .9em; display: inline-block; margin-top: .2em; }

        /* ============================================================
           BACKGROUND
           ============================================================ */
        .bg-layer {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }
        .bg-glow {
            position: absolute;
            top: -32rem;
            left: 50%;
            width: 72rem;
            height: 72rem;
            transform: translateX(-50%);
            background: radial-gradient(circle, var(--glow) 0%, transparent 62%);
            filter: blur(6px);
        }
        .bg-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(var(--grid) 1px, transparent 1px),
                linear-gradient(90deg, var(--grid) 1px, transparent 1px);
            background-size: 72px 72px;
            -webkit-mask-image: radial-gradient(ellipse 85% 65% at 50% 0%, #000 25%, transparent 78%);
                    mask-image: radial-gradient(ellipse 85% 65% at 50% 0%, #000 25%, transparent 78%);
        }

        /* ============================================================
           ANIMATIONS
           ============================================================ */
        @keyframes rise {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: none; }
        }
        @keyframes fade-in {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        @keyframes floaty {
            0%, 100% { translate: 0 0; }
            50%      { translate: 0 -10px; }
        }
        @keyframes spin {
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }
        @keyframes ping {
            0%        { transform: scale(1);   opacity: .55; }
            80%, 100% { transform: scale(2.6); opacity: 0; }
        }
        @keyframes shimmer {
            0%   { transform: translateX(-100%); }
            100% { transform: translateX(220%); }
        }
        .rise {
            opacity: 0;
            animation: rise .8s cubic-bezier(.2,.8,.2,1) forwards;
            animation-delay: calc(var(--i, 0) * 80ms + 100ms);
        }

        /* ============================================================
           TOPBAR
           ============================================================ */
        .topbar {
            position: relative;
            z-index: 5;
            width: 100%;
            max-width: 1180px;
            margin: 0 auto;
            padding: 1.5rem 1.5rem 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }
        .brand {
            display: inline-flex;
            align-items: center;
            gap: .7rem;
            text-decoration: none;
            color: var(--text);
        }
        .brand img {
            width: 2.1rem;
            height: 2.1rem;
            object-fit: contain;
            display: block;
        }
        .brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1;
        }
        .brand-tag {
            margin-top: .45rem;
            font-size: .5rem;
            letter-spacing: .16em;
            text-transform: uppercase;
            color: var(--faint);
            white-space: nowrap;
        }
        .topbar-actions {
            display: flex;
            align-items: center;
            gap: .6rem;
        }
        .link-login {
            display: inline-flex;
            align-items: center;
            padding: .6rem .9rem;
            font-size: .76rem;
            letter-spacing: .06em;
            color: var(--muted);
            text-decoration: none;
            border-radius: 2px;
            transition: color .18s ease;
        }
        .link-login:hover { color: var(--accent-text); }

        .theme-toggle {
            position: relative;
            width: 2.5rem;
            height: 2.5rem;
            display: grid;
            place-items: center;
            background: transparent;
            border: 1px solid var(--line);
            border-radius: 999px;
            color: var(--text);
            cursor: pointer;
            padding: 0;
            overflow: hidden;
            flex-shrink: 0;
            transition: border-color .2s ease, color .2s ease, background-color .2s ease;
        }
        [data-theme="light"] .theme-toggle { background: #FFFFFF; }
        .theme-toggle:hover { border-color: var(--cyan); color: var(--accent-text); }
        .theme-toggle svg {
            position: absolute;
            width: 15px;
            height: 15px;
            transition: transform .4s cubic-bezier(.3,1.4,.5,1), opacity .3s ease;
        }
        .theme-toggle .icon-sun  { opacity: 1; transform: rotate(0) scale(1); }
        .theme-toggle .icon-moon { opacity: 0; transform: rotate(-180deg) scale(0); }
        [data-theme="light"] .theme-toggle .icon-sun  { opacity: 0; transform: rotate(180deg) scale(0); }
        [data-theme="light"] .theme-toggle .icon-moon { opacity: 1; transform: rotate(0) scale(1); }

        /* ============================================================
           BUTTONS
           ============================================================ */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .55rem;
            padding: .95rem 1.55rem;
            font-family: 'Anta', sans-serif;
            font-size: .76rem;
            letter-spacing: .1em;
            text-transform: uppercase;
            text-decoration: none;
            border: 1px solid transparent;
            border-radius: 2px;
            cursor: pointer;
            transition: transform .18s ease, box-shadow .18s ease,
                        background-color .18s ease, border-color .18s ease, color .18s ease;
        }
        .btn-sm { padding: .6rem 1.05rem; font-size: .68rem; }
        .btn-primary {
            background: var(--cyan);
            color: var(--cyan-ink);
            border-color: var(--cyan);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 34px -14px var(--cyan);
        }
        .btn-ghost {
            background: transparent;
            color: var(--text);
            border-color: var(--line-strong);
        }
        [data-theme="light"] .btn-ghost { background: #FFFFFF; }
        .btn-ghost:hover {
            transform: translateY(-2px);
            border-color: var(--cyan);
            color: var(--accent-text);
        }
        .btn:active { transform: translateY(0); }

        /* ============================================================
           HERO
           ============================================================ */
        .hero-wrap {
            position: relative;
            z-index: 2;
            flex: 1;
            display: flex;
            align-items: center;
        }
        .hero {
            width: 100%;
            max-width: 1180px;
            margin: 0 auto;
            padding: clamp(2.5rem, 6vh, 4.5rem) 1.5rem clamp(3rem, 6vh, 4.5rem);
            display: grid;
            gap: clamp(3rem, 6vw, 5rem);
            align-items: center;
        }
        @media (min-width: 1024px) {
            .hero { grid-template-columns: 1.05fr .95fr; }
        }

        /* --- Left column --- */
        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: .6rem;
            padding: .5rem .9rem .5rem .75rem;
            border: 1px solid var(--line);
            border-radius: 999px;
            background: var(--panel);
            color: var(--muted);
            font-size: .64rem;
            letter-spacing: .18em;
            text-transform: uppercase;
            white-space: nowrap;
        }
        [data-theme="light"] .eyebrow { box-shadow: var(--shadow); }
        .eyebrow .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--cyan);
            box-shadow: 0 0 10px var(--cyan);
            flex-shrink: 0;
        }

        .headline {
            margin: 1.6rem 0 0;
            font-size: clamp(2.5rem, 6.2vw, 4.4rem);
        }
        .headline .br-lg { display: none; }
        @media (min-width: 640px) { .headline .br-lg { display: block; } }

        .lede {
            margin: 1.5rem 0 0;
            max-width: 32rem;
            font-size: clamp(.98rem, 1.5vw, 1.08rem);
            line-height: 1.75;
            color: var(--muted);
        }

        .hero-cta {
            margin-top: 2.2rem;
            display: flex;
            flex-wrap: wrap;
            gap: .75rem;
        }

        .hero-meta {
            margin-top: 2.75rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }
        .meta-item {
            display: flex;
            flex-direction: column;
            gap: .3rem;
        }
        .meta-item b {
            font-family: 'Iceberg', serif;
            font-weight: 400;
            -webkit-text-stroke: .35px currentColor;
            font-size: 1.35rem;
            line-height: 1;
            letter-spacing: .01em;
        }
        .meta-item span {
            font-size: .6rem;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--faint);
        }
        .meta-sep {
            width: 1px;
            height: 2rem;
            background: var(--line);
        }

        /* ============================================================
           HERO VISUAL — DECK STACK
           ============================================================ */
        .hero-visual {
            position: relative;
            display: grid;
            place-items: center;
            min-height: 26rem;
            padding: 3rem 1rem;
        }

        .ring {
            position: absolute;
            top: 50%;
            left: 50%;
            width: min(28rem, 88%);
            aspect-ratio: 1;
            transform: translate(-50%, -50%);
            border: 1px dashed var(--line-strong);
            border-radius: 50%;
            animation: spin 44s linear infinite;
        }
        .ring::after {
            content: '';
            position: absolute;
            top: -4px;
            left: 50%;
            width: 7px;
            height: 7px;
            margin-left: -3.5px;
            border-radius: 50%;
            background: var(--cyan);
            box-shadow: 0 0 12px var(--cyan);
        }
        .ring-2 {
            width: min(20rem, 68%);
            animation-direction: reverse;
            animation-duration: 32s;
            opacity: .55;
        }
        .ring-2::after {
            width: 5px; height: 5px;
            top: auto; bottom: -3px;
            margin-left: -2.5px;
        }
        .blob {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 26rem;
            height: 26rem;
            transform: translate(-50%, -50%);
            background: radial-gradient(circle, var(--glow) 0%, transparent 65%);
            filter: blur(16px);
            pointer-events: none;
        }

        /* Mini cards (behind main) */
        .mini-card {
            position: absolute;
            top: 50%;
            width: 8rem;
            padding: .8rem;
            border-radius: 6px;
            background: var(--mini-panel);
            border: 1px solid var(--line-strong);
            box-shadow: var(--shadow);
            opacity: 0;
            animation: fade-in .8s cubic-bezier(.2,.8,.2,1) forwards;
            z-index: 1;
            pointer-events: none;
            display: flex;
            flex-direction: column;
            gap: .5rem;
        }
        .mini-card-l {
            left: 0;
            transform: translate(-55%, -50%) rotate(-15deg);
            animation-delay: .55s;
        }
        .mini-card-r {
            right: 0;
            transform: translate(55%, -50%) rotate(15deg);
            animation-delay: .65s;
            margin-top: 1.5rem;
        }
        .mini-card-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: .55rem;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--faint);
        }
        .mini-card-top .mini-lv { color: var(--accent-text); }
        .mini-art {
            aspect-ratio: 1;
            border-radius: 4px;
            background: linear-gradient(145deg, var(--line), transparent 70%);
            border: 1px solid var(--line);
            display: grid;
            place-items: center;
            color: var(--accent-text);
        }
        [data-theme="light"] .mini-art {
            background: linear-gradient(145deg, #F1F5F9, #FFFFFF 70%);
        }
        .mini-art svg { width: 2rem; height: 2rem; }
        .mini-name {
            font-family: 'Iceberg', serif;
            -webkit-text-stroke: .35px currentColor;
            font-size: .82rem;
            letter-spacing: .01em;
            color: var(--text);
            line-height: 1.1;
        }

        /* Main card */
        .ticket-wrap {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 20rem;
            animation:
                rise .8s cubic-bezier(.2,.8,.2,1) .35s forwards,
                floaty 7s ease-in-out 1.3s infinite;
            opacity: 0;
        }
        .ticket {
            position: relative;
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 6px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
            -webkit-backdrop-filter: blur(10px);
                    backdrop-filter: blur(10px);
            overflow: hidden;
        }
        .ticket::before {
            content: '';
            position: absolute;
            top: 0;
            left: 1.5rem;
            right: 1.5rem;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--cyan), transparent);
            opacity: .8;
        }
        /* shimmer sweep on hover */
        .ticket::after {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 40%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(34,211,238,.10), transparent);
            transform: translateX(-100%);
            pointer-events: none;
        }
        .ticket-wrap:hover .ticket::after {
            animation: shimmer 1.1s ease;
        }

        .ticket-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }
        .ticket-top .kicker { color: var(--faint); }
        .status {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            font-size: .62rem;
            letter-spacing: .16em;
            text-transform: uppercase;
            color: var(--accent-text);
        }
        .status i {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--cyan);
            box-shadow: 0 0 8px var(--cyan);
        }
        .ticket-title {
            margin: 1.1rem 0 0;
            font-size: 1.55rem;
            line-height: 1.08;
        }
        .ticket-tags {
            margin-top: 1rem;
            display: flex;
            flex-wrap: wrap;
            gap: .4rem;
        }
        .tag {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .3rem .65rem;
            border: 1px solid var(--line-strong);
            border-radius: 999px;
            font-size: .62rem;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--muted);
        }
        .rarity-gem {
            width: 5px;
            height: 5px;
            transform: rotate(45deg);
            background: var(--cyan);
            box-shadow: 0 0 6px var(--cyan);
            flex-shrink: 0;
        }
        .ticket-divider {
            margin: 1.25rem 0;
            height: 0;
            border-top: 1px dashed var(--line-strong);
        }
        .ticket-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        .ticket-grid .label {
            margin: 0;
            font-size: .58rem;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--faint);
        }
        .ticket-grid .value {
            margin: .4rem 0 0;
            font-family: 'Iceberg', serif;
            -webkit-text-stroke: .35px currentColor;
            font-size: 1.05rem;
            line-height: 1;
        }
        .ticket-grid .value.accent { color: var(--accent-text); }

        /* Power bar (replaces barcode) */
        .card-power {
            margin-top: 1.25rem;
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .cp-label {
            font-size: .55rem;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--faint);
            flex-shrink: 0;
        }
        .cp-bar {
            flex: 1;
            height: 4px;
            background: var(--line);
            border-radius: 999px;
            overflow: hidden;
        }
        .cp-fill {
            height: 100%;
            width: 82%;
            background: linear-gradient(90deg, var(--cyan), var(--accent-text));
            border-radius: 999px;
        }
        .cp-value {
            font-family: 'Iceberg', serif;
            -webkit-text-stroke: .35px currentColor;
            font-size: .85rem;
            color: var(--accent-text);
            flex-shrink: 0;
        }

        /* Content chip inside ticket-wrap */
        .float-chip {
            position: absolute;
            top: -.75rem;
            right: -.5rem;
            display: inline-flex;
            align-items: center;
            gap: .55rem;
            padding: .55rem .85rem;
            background: var(--bg-soft);
            border: 1px solid var(--line-strong);
            border-radius: 999px;
            font-size: .62rem;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--muted);
            box-shadow: var(--shadow);
            white-space: nowrap;
            z-index: 3;
        }
        [data-theme="light"] .float-chip { background: #FFFFFF; }
        .float-chip b {
            font-weight: 400;
            color: var(--text);
        }
        .pulse {
            position: relative;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--cyan);
            flex-shrink: 0;
        }
        .pulse::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: var(--cyan);
            animation: ping 1.9s cubic-bezier(0,0,.2,1) infinite;
        }

        /* Battle HUD chips */
        .battle-stat {
            position: absolute;
            z-index: 3;
            pointer-events: none;
        }
        .battle-stat-inner {
            display: inline-flex;
            align-items: center;
            gap: .7rem;
            padding: .55rem .8rem;
            background: var(--bg-soft);
            border: 1px solid var(--line-strong);
            border-radius: 4px;
            box-shadow: var(--shadow);
            white-space: nowrap;
        }
        [data-theme="light"] .battle-stat-inner { background: #FFFFFF; }
        .bs-label {
            font-size: .52rem;
            letter-spacing: .22em;
            text-transform: uppercase;
            color: var(--faint);
            padding-right: .6rem;
            border-right: 1px solid var(--line-strong);
        }
        .bs-value {
            font-family: 'Iceberg', serif;
            -webkit-text-stroke: .35px currentColor;
            font-size: 1rem;
            line-height: 1;
            color: var(--text);
            letter-spacing: .02em;
        }
        .bs-value span {
            color: var(--faint);
            font-size: .82em;
            margin-left: .1em;
        }
        .bs-value.accent { color: var(--accent-text); }
        .battle-stat-tl {
            top: 4%;
            left: 0;
        }
        .battle-stat-br {
            bottom: 6%;
            right: 0;
        }
        .battle-stat-tr {
            top: 10%;
            right: 2%;
        }

        /* ============================================================
           FOOTBAR
           ============================================================ */
        .footbar {
            position: relative;
            z-index: 2;
            border-top: 1px solid var(--line);
        }
        .footbar-inner {
            width: 100%;
            max-width: 1180px;
            margin: 0 auto;
            padding: 1.15rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .9rem;
            font-size: .6rem;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--faint);
        }
        .footbar-inner .wm { font-size: .8rem; letter-spacing: .02em; }
        .footbar-left {
            display: inline-flex;
            align-items: center;
            gap: .7rem;
        }
        .footbar-left img {
            width: 1.35rem;
            height: 1.35rem;
            object-fit: contain;
            display: block;
        }

        /* ============================================================
           RESPONSIVE
           ============================================================ */
        @media (max-width: 1023px) {
            .hero-visual { margin-top: .5rem; min-height: 24rem; }
        }
        @media (max-width: 767px) {
            /* Hide decorative deck parts on phones */
            .mini-card, .battle-stat { display: none; }
        }
        @media (max-width: 639px) {
            .topbar { padding-top: 1.1rem; }
            .link-login { display: none; }
            .brand-tag { display: none; }
            .hero-cta .btn { flex: 1 1 100%; }
            .hero-meta { gap: 1.1rem; }
            .meta-sep { height: 1.6rem; }
            .float-chip { right: 0; font-size: .58rem; padding: .5rem .7rem; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
            }
            .rise, .ticket-wrap, .mini-card { opacity: 1 !important; }
            .ring, .pulse::after { animation: none !important; }
            .ticket-wrap:hover .ticket::after { animation: none !important; }
        }
    </style>
</head>
<body id="top">

@php
    $logo = fn (string $class = '') =>
        '<img src="' . asset('images/logo.png') . '" alt="Logo DIGANOVA NAVO CLASH" class="' . $class . '" draggable="false">';

    $wordmark = fn (string $class = 'wm-sm') =>
        '<span class="wm ' . $class . '">'
        . '<span class="wm-ice">DIGANOVA</span>'
        . '<span class="wm-anta">NAVO CLASH</span>'
        . '</span>';

    $svg = fn (string $path, string $class = '') =>
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . $path . '</svg>';

    $iconTarget = '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2" fill="currentColor"/>';
    $iconZap    = '<path d="m13 2-3 8H4l7 12 3-9h6L13 2Z"/>';
    $iconShield = '<path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"/><path d="m9 12 2 2 4-4"/>';
@endphp

    <a href="#hero" class="skip-link">Langsung ke konten →</a>

    {{-- BACKGROUND --}}
    <div class="bg-layer" aria-hidden="true">
        <div class="bg-glow"></div>
        <div class="bg-grid"></div>
    </div>

    {{-- TOPBAR --}}
    <header class="topbar rise" style="--i:0" role="banner">
        <a href="#top" class="brand" aria-label="DIGANOVA NAVO CLASH — kembali ke atas">
            {!! $logo() !!}
            <span class="brand-text">
                {!! $wordmark('wm-sm') !!}
                <span class="brand-tag">Plan It. Join It. Enjoy It.</span>
            </span>
        </a>

        <div class="topbar-actions">
            <button id="theme-toggle" class="theme-toggle" type="button" aria-label="Ganti tema terang / gelap">
                <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="4"/>
                    <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/>
                </svg>
                <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                </svg>
            </button>

            <a href="{{ route('login') }}" class="link-login">Masuk</a>
            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Gabung</a>
        </div>
    </header>

    {{-- MAIN --}}
    <div class="hero-wrap">
        <main id="hero" class="hero" role="main">

            {{-- LEFT --}}
            <div class="hero-copy">
                <span class="eyebrow rise" style="--i:1">
                    <span class="dot" aria-hidden="true"></span>
                    Turn-Based Strategy Card Game
                </span>

                <h1 class="h-display headline rise" style="--i:2">
                    Siap Taklukin Lawan<br class="br-lg">
                    di Arena {!! $wordmark('wm-inline') !!}
                </h1>

                <p class="lede rise" style="--i:3">
                    Bosen main game yang itu-itu aja? Di sini, otak kamu yang jadi senjata utama.
                    Rancang strategi, baca pergerakan lawan, dan panjat puncak leaderboard.
                    Buktiin kalau kamu yang paling jago!
                </p>

                <div class="hero-cta rise" style="--i:4">
                    <a href="{{ route('register') }}" class="btn btn-primary">
                        Gas Bertarung! <span aria-hidden="true">→</span>
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-ghost">Lanjut Main</a>
                </div>

                <div class="hero-meta rise" style="--i:5">
                    <div class="meta-item"><b>2.4K+</b><span>Player Aktif</span></div>
                    <div class="meta-sep" aria-hidden="true"></div>
                    <div class="meta-item"><b>180+</b><span>Kartu Unik</span></div>
                    <div class="meta-sep" aria-hidden="true"></div>
                    <div class="meta-item"><b>24/7</b><span>Battle Seru</span></div>
                </div>
            </div>

            {{-- RIGHT — Deck Stack Visual --}}
            <div class="hero-visual">
                <div class="blob" aria-hidden="true"></div>
                <div class="ring" aria-hidden="true"></div>
                <div class="ring ring-2" aria-hidden="true"></div>

                {{-- Mini card (left, behind) --}}
                <div class="mini-card mini-card-l" aria-hidden="true">
                    <div class="mini-card-top">
                        <span>Epic</span>
                        <span class="mini-lv">LV.88</span>
                    </div>
                    <div class="mini-art">{!! $svg($iconTarget) !!}</div>
                    <span class="mini-name">Void<br>Hunter</span>
                </div>

                {{-- Mini card (right, behind) --}}
                <div class="mini-card mini-card-r" aria-hidden="true">
                    <div class="mini-card-top">
                        <span>Rare</span>
                        <span class="mini-lv">LV.72</span>
                    </div>
                    <div class="mini-art">{!! $svg($iconShield) !!}</div>
                    <span class="mini-name">Shadow<br>Guard</span>
                </div>

                {{-- Main card --}}
                <div class="ticket-wrap">
                    <div class="float-chip">
                        <span class="pulse" aria-hidden="true"></span>
                        <span>Season 3 · <b>Live</b></span>
                    </div>

                    <article class="ticket">
                        <div class="ticket-top">
                            <span class="kicker">Kartu Andalan</span>
                            <span class="status"><i aria-hidden="true"></i>Siap Tempur!</span>
                        </div>

                        <h2 class="h-display ticket-title">Arcane<br>Knight</h2>

                        <div class="ticket-tags">
                            <span class="tag">Warrior</span>
                            <span class="tag"><span class="rarity-gem" aria-hidden="true"></span>Legendary</span>
                            <span class="tag">Tank</span>
                        </div>

                        <div class="ticket-divider" aria-hidden="true"></div>

                        <div class="ticket-grid">
                            <div>
                                <p class="label">ATK</p>
                                <p class="value">99</p>
                            </div>
                            <div>
                                <p class="label">HP</p>
                                <p class="value accent">120</p>
                            </div>
                        </div>

                        <div class="card-power">
                            <span class="cp-label">Power</span>
                            <div class="cp-bar"><div class="cp-fill"></div></div>
                            <span class="cp-value">9.8K</span>
                        </div>
                    </article>
                </div>

                {{-- Battle HUD chips --}}
                <div class="battle-stat battle-stat-tl">
                    <div class="battle-stat-inner rise" style="--i:6.5">
                        <span class="bs-label">Turn</span>
                        <span class="bs-value">03<span>/10</span></span>
                    </div>
                </div>

                <div class="battle-stat battle-stat-br">
                    <div class="battle-stat-inner rise" style="--i:7">
                        <span class="bs-label">Streak</span>
                        <span class="bs-value accent">× 7</span>
                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- FOOTBAR --}}
    <footer class="footbar rise" style="--i:8" role="contentinfo">
        <div class="footbar-inner">
            <span class="footbar-left">
                {!! $logo() !!}
                {!! $wordmark('wm-sm') !!}
                <span>Plan It. Join It. Enjoy It.</span>
            </span>
            <span>© {{ date('Y') }} DIGANOVA</span>
        </div>
    </footer>

    <script>
    (function () {
        const root = document.documentElement;
        const toggle = document.getElementById('theme-toggle');
        if (!toggle) return;

        // Sync browser chrome color with theme
        const syncThemeColor = () => {
            const meta = document.querySelector('meta[name="theme-color"]');
            if (!meta) return;
            meta.setAttribute('content',
                root.getAttribute('data-theme') === 'light' ? '#F1F5F9' : '#000000');
        };
        syncThemeColor();

        toggle.addEventListener('click', () => {
            const current = root.getAttribute('data-theme') || 'dark';
            const next = current === 'dark' ? 'light' : 'dark';
            root.setAttribute('data-theme', next);
            localStorage.setItem('navoclash.theme', next);
            syncThemeColor();
        });

        window.matchMedia('(prefers-color-scheme: light)').addEventListener('change', (e) => {
            if (localStorage.getItem('navoclash.theme')) return;
            root.setAttribute('data-theme', e.matches ? 'light' : 'dark');
            syncThemeColor();
        });
    })();
    </script>
</body>
</html>