<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#000000">
    <title>NAVO CLASH — Enter The Arena</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #4647AE;
            --blue: #4382DF;
            --navy: #112E81;
            --deep: #090E39;
            --black: #000000;
            --white: #FFFFFF;
        }

        html { scroll-behavior: smooth; }

        body {
            background: var(--black);
            color: var(--white);
        }

        .arena-grid {
            background-image:
                linear-gradient(rgba(67,130,223,.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(67,130,223,.08) 1px, transparent 1px);
            background-size: 40px 40px;
            mask-image: linear-gradient(to bottom, black, transparent 95%);
        }

        .arena-glow {
            background:
                radial-gradient(ellipse at 25% 25%, rgba(70,71,174,.22), transparent 45%),
                radial-gradient(ellipse at 75% 55%, rgba(67,130,223,.15), transparent 45%);
        }

        .gradient-text {
            background: linear-gradient(110deg, #4382DF, #4647AE 65%, #FFFFFF);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .glass {
            background: linear-gradient(145deg, #090E39 0%, #05071C 100%);
            border: 1px solid rgba(67,130,223,.30);
            backdrop-filter: blur(18px);
        }

        .neon-button {
            background: linear-gradient(110deg, #4647AE, #4382DF);
            box-shadow: 0 0 30px rgba(70,71,174,.32);
            transition: .25s ease;
        }

        .neon-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 0 38px rgba(67,130,223,.45);
            filter: brightness(1.12);
        }

        /* =========================================
           NAVO CLASH - FUTURISTIC FEATURE CARDS
        ========================================= */

        .n-feature-card {
            --panel-cut: polygon(
                0 48px,
                48px 0,
                34% 0,
                39% 17px,
                68% 17px,
                73% 36px,
                94% 36px,
                100% 62px,
                100% calc(100% - 36px),
                calc(100% - 36px) 100%,
                15% 100%,
                0 calc(100% - 32px)
            );

            position: relative;
            isolation: isolate;
            min-height: 390px;
            padding: 2px;

            background: linear-gradient(
                135deg,
                #171c80 0%,
                #4382df 22%,
                #090e39 43%,
                #172b9d 75%,
                #080d27 100%
            );

            clip-path: var(--panel-cut);
            transition: filter .3s ease, transform .3s ease;
        }

        .n-feature-card::before {
            content: "";
            position: absolute;
            inset: 1px;
            z-index: -1;

            background: linear-gradient(
                125deg,
                #101b52 0%,
                #050a1b 46%,
                #080d2b 100%
            );

            clip-path: var(--panel-cut);
        }

        .n-feature-card::after {
            content: "";
            position: absolute;
            z-index: -1;
            inset: 0;
            pointer-events: none;

            background:
                linear-gradient(
                    125deg,
                    transparent 35%,
                    #132b8915 36%,
                    transparent 57%
                ),
                repeating-linear-gradient(
                    135deg,
                    transparent 0,
                    transparent 44px,
                    #245dff08 45px,
                    #245dff08 46px
                );
        }

        .n-feature-card:hover {
            filter: drop-shadow(0 0 12px #155eff55);
            transform: translateY(-5px);
        }

        .n-feature-inner {
            position: relative;
            min-height: 386px;
            padding: 42px 28px 28px;
            overflow: hidden;
            clip-path: var(--panel-cut);
        }

            .n-feature-icon {
        width: 88px;
        height: 88px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        box-sizing: border-box;
        padding: 17px;
        background: linear-gradient(145deg, #112f7b, #080d27);
        border: 1px solid #247aff;
        box-shadow: 0 0 18px #176bff35;
    }

    .n-feature-icon svg {
        display: block;
        width: 100%;
        height: 100%;
        flex-shrink: 0;
    }

    .n-feature-label {
        display: block;
        margin-top: 42px;
        color: #7899ff;
        font-size: 0.8rem;
        line-height: 1.2;
        font-weight: 800;
        text-align: right;
        white-space: nowrap;
    }

        .n-feature-label {
            display: block;
            margin-top: 7px;
            color: #7899ff;
            font-size: .8rem;
            font-weight: 800;
            text-align: right;
        }

        .n-feature-title {
            margin-top: 30px;
            font-size: clamp(1.15rem, 1.7vw, 1.5rem);
            font-weight: 900;
            line-height: 1.2;
            letter-spacing: -.035em;
            text-transform: uppercase;
        }

        .n-feature-title span {
            color: #1688ff;
            letter-spacing: -.1em;
        }

        .n-feature-description {
            margin-top: 15px;
            max-width: 390px;
            color: #b7c5e8;
            font-size: .96rem;
            line-height: 1.8;
        }

        .n-feature-link {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 26px;
            color: #1599ff;
            font-size: .8rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .n-feature-link::after {
            content: "";
            flex: 1;
            height: 1px;
            margin-left: 4px;
            background: linear-gradient(
                90deg,
                #285bba99,
                transparent
            );
        }

        .n-feature-accent {
            position: absolute;
            top: 0;
            left: 0;
            width: 74px;
            height: 8px;

            background: linear-gradient(
                90deg,
                #4647ae,
                #4382df
            );

            transform: skewX(-42deg) translateX(-8px);
            box-shadow: 0 0 16px #4382df88;
        }

        @media (min-width: 1024px) {
            .n-feature-card:nth-child(2) {
                transform: translateY(4px);
            }

            .n-feature-card:nth-child(2):hover {
                transform: translateY(-5px);
            }

            .n-feature-card:nth-child(2) .n-feature-inner {
                background: radial-gradient(
                    ellipse at 50% 0%,
                    #173eaa38,
                    transparent 60%
                );
            }
        }

        @media (max-width: 639px) {
            .n-feature-card,
            .n-feature-inner {
                min-height: 365px;
            }

            .n-feature-inner {
                padding: 34px 22px 25px;
            }

            .n-feature-number {
                top: 42px;
                right: 20px;
                font-size: 1.8rem;
            }

            .n-feature-label {
                font-size: .7rem;
            }

            .n-feature-title {
                margin-top: 28px;
            }
        }

        .icon-box {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 54px;
            height: 54px;
            border-radius: 14px;
            color: #4382DF;
            background: rgba(70,71,174,.16);
            border: 1px solid rgba(67,130,223,.3);
        }

        .float-one { animation: float 5s ease-in-out infinite; }
        .float-two { animation: float 5s ease-in-out 1.3s infinite; }

        .pulse {
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes float {
            0%,100% { translate: 0 0; }
            50% { translate: 0 -12px; }
        }

        @keyframes pulse {
            0%,100% { opacity: 1; }
            50% { opacity: .4; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation: none !important;
                transition: none !important;
                scroll-behavior: auto !important;
            }
        }
    </style>
</head>

<body class="min-h-screen overflow-x-hidden font-mono">

{{-- SVG ICON LIBRARY --}}
<svg xmlns="http://www.w3.org/2000/svg" class="hidden" aria-hidden="true">

    <symbol id="i-shield" viewBox="0 0 24 24">
        <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"
              fill="none" stroke="currentColor" stroke-width="1.7"
              stroke-linejoin="round"/>
        <path d="m9 12 2 2 4-4" fill="none"
              stroke="currentColor" stroke-width="1.7"
              stroke-linecap="round" stroke-linejoin="round"/>
    </symbol>

    <symbol id="i-swords" viewBox="0 0 24 24">
        <path d="m14.5 17.5 3 3 3-3-3-3m-6-6L18 2l4 4-6.5 6.5M9.5 6.5 3 13l-1 5 5-1 6.5-6.5M5 19l-2 2m7-15 9 9"
              fill="none" stroke="currentColor" stroke-width="1.7"
              stroke-linecap="round" stroke-linejoin="round"/>
    </symbol>

    <symbol id="i-layers" viewBox="0 0 24 24">
        <path d="m12 2 9 5-9 5-9-5 9-5Zm-9 10 9 5 9-5M3 17l9 5 9-5"
              fill="none" stroke="currentColor" stroke-width="1.7"
              stroke-linecap="round" stroke-linejoin="round"/>
    </symbol>

    <symbol id="i-trophy" viewBox="0 0 24 24">
        <path d="M8 21h8m-4-4v4M7 4h10v5a5 5 0 0 1-10 0V4Zm0 2H3v2a5 5 0 0 0 5 5m9-7h4v2a5 5 0 0 1-5 5"
              fill="none" stroke="currentColor" stroke-width="1.7"
              stroke-linecap="round" stroke-linejoin="round"/>
    </symbol>

    <symbol id="i-lock" viewBox="0 0 24 24">
        <rect x="4" y="10" width="16" height="12" rx="2"
              fill="none" stroke="currentColor" stroke-width="1.7"/>
        <path d="M8 10V7a4 4 0 0 1 8 0v3"
              fill="none" stroke="currentColor" stroke-width="1.7"
              stroke-linecap="round"/>
        <circle cx="12" cy="16" r="1.3" fill="currentColor"/>
    </symbol>

    <symbol id="i-zap" viewBox="0 0 24 24">
        <path d="m13 2-3 8H4l7 12 3-9h6L13 2Z"
              fill="none" stroke="currentColor" stroke-width="1.7"
              stroke-linecap="round" stroke-linejoin="round"/>
    </symbol>

    <symbol id="i-arrow" viewBox="0 0 24 24">
        <path d="M7 17 17 7M7 7h10v10"
              fill="none" stroke="currentColor" stroke-width="1.8"
              stroke-linecap="round" stroke-linejoin="round"/>
    </symbol>

    <symbol id="i-users" viewBox="0 0 24 24">
        <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m6-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm10 10v-2a4 4 0 0 0-3-3.87m-1-12.13a4 4 0 0 1 0 7.75"
              fill="none" stroke="currentColor" stroke-width="1.7"
              stroke-linecap="round" stroke-linejoin="round"/>
    </symbol>

    <symbol id="i-target" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10" fill="none"
                stroke="currentColor" stroke-width="1.7"/>
        <circle cx="12" cy="12" r="6" fill="none"
                stroke="currentColor" stroke-width="1.7"/>
        <circle cx="12" cy="12" r="2" fill="currentColor"/>
    </symbol>

    <symbol id="i-crown" viewBox="0 0 24 24">
        <path d="m2 7 5 4 5-8 5 8 5-4-2 13H4L2 7Zm2 16h16"
              fill="none" stroke="currentColor" stroke-width="1.7"
              stroke-linecap="round" stroke-linejoin="round"/>
    </symbol>
</svg>

{{-- BACKGROUND --}}
<div class="fixed inset-0 pointer-events-none">
    <div class="absolute inset-0 arena-grid"></div>
    <div class="absolute inset-0 arena-glow"></div>

    <div class="absolute -top-40 -left-40 w-96 h-96
                bg-[#4647AE]/20 rounded-full blur-[140px]"></div>

    <div class="absolute top-[700px] -right-40 w-96 h-96
                bg-[#112E81]/30 rounded-full blur-[140px]"></div>
</div>

{{-- NAVBAR --}}
<nav class="sticky top-0 z-50 border-b border-[#4647AE]/40
            bg-black/85 backdrop-blur-xl">

    <div class="max-w-7xl mx-auto px-5 sm:px-8">
        <div class="h-20 flex items-center justify-between">

            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <div class="icon-box w-11 h-11 rounded-xl">
                    <svg class="w-6 h-6" fill="none">
                        <use href="#i-shield"/>
                    </svg>
                </div>

                <span class="font-black text-xl sm:text-2xl tracking-[.13em]">
                    <span class="text-[#4382DF]">NAVO</span>
                    <span class="text-[#FFFFFF]">CLASH</span>
                </span>
            </a>

            <div class="flex items-center gap-4 sm:gap-7 font-sans">
                <a href="{{ route('login') }}"
                   class="text-sm font-bold text-white/80 hover:text-[#4382DF] transition">
                    LOGIN
                </a>

                <a href="{{ route('register') }}"
                   class="px-5 sm:px-7 py-3 rounded-lg border border-[#4647AE]
                          text-white bg-[#090E39] hover:bg-[#4647AE] transition font-bold">
                    DAFTAR
                </a>
            </div>
        </div>
    </div>
</nav>

<main class="relative z-10">

    {{-- HERO --}}
    <section class="max-w-7xl mx-auto px-5 sm:px-8 pt-20 sm:pt-28 pb-20">
        <div class="grid lg:grid-cols-2 gap-12 items-center">

            <div class="text-center lg:text-left">
                <div class="inline-flex items-center gap-3 rounded-full px-4 py-2 mb-7
                            bg-[#090E39] border border-[#4647AE]/70">
                    <span class="w-2 h-2 rounded-full bg-[#4382DF] pulse"></span>
                    <span class="text-[10px] sm:text-xs tracking-[.2em] uppercase text-[#4382DF]">
                        Turn-Based Card Battle
                    </span>
                </div>

                <h1 class="font-sans font-black uppercase tracking-[-.065em] leading-[.94]
                           text-6xl sm:text-7xl lg:text-8xl">
                    <span class="block text-white">Kuasai</span>
                    <span class="block gradient-text mt-3">Arena.</span>
                </h1>

                <p class="mt-8 max-w-xl mx-auto lg:mx-0 text-sm sm:text-lg leading-8
                          text-white/60 font-sans">
                    Bangun deck terbaikmu, susun strategi, lawan pemain lain,
                    dan buktikan kemampuanmu. Arena menanti sang juara.
                </p>

                <div class="flex flex-wrap justify-center lg:justify-start gap-5 mt-6
                            text-xs text-white/80 font-sans">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#4382DF]"><use href="#i-shield"/></svg>
                        Free-to-Win
                    </span>

                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#4382DF]"><use href="#i-swords"/></svg>
                        Strategic Battle
                    </span>

                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#4382DF]"><use href="#i-trophy"/></svg>
                        Global Ranking
                    </span>
                </div>

                <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4 mt-9">
                    <a href="{{ route('register') }}"
                       class="neon-button inline-flex items-center justify-center gap-3 px-8 py-4
                              rounded-lg font-sans font-black">
                        MULAI BERTARUNG
                        <svg class="w-5 h-5"><use href="#i-arrow"/></svg>
                    </a>

                    <a href="#fitur"
                       class="px-8 py-4 rounded-lg border border-[#4647AE]/60 bg-[#090E39]/60
                              text-white hover:bg-[#112E81] transition font-sans font-bold text-center">
                        JELAJAHI GAME
                    </a>
                </div>

                <p class="mt-8 text-[10px] text-white/35 tracking-[.2em] uppercase">
                    Your Strategy. Your Deck. Your Victory.
                </p>
            </div>

            {{-- CARD SHOWCASE --}}
            <div class="relative min-h-[430px] sm:min-h-[510px] flex items-center justify-center">
                <div class="absolute w-80 h-80 rounded-full bg-[#4647AE]/20 blur-[100px]"></div>

                <div class="absolute w-[350px] h-[350px] sm:w-[430px] sm:h-[430px]
                            rounded-full border border-[#4382DF]/20"></div>

                <div class="absolute w-[270px] h-[270px] sm:w-[340px] sm:h-[340px]
                            rounded-full border border-[#4647AE]/30"></div>

                {{-- Left card --}}
                <div class="float-one absolute left-[1%] sm:left-[6%] top-[17%] z-10
                            w-[135px] sm:w-[175px] glass rounded-2xl p-3 sm:p-4 rotate-[-10deg]">
                    <div class="flex justify-between text-[9px] text-white/50 mb-3">
                        <span>NV-001</span>
                        <span class="text-[#4382DF]">RARE</span>
                    </div>

                    <div class="aspect-[4/5] rounded-xl border border-[#4647AE]/60
                                bg-gradient-to-br from-[#4647AE]/40 via-[#090E39] to-black
                                flex flex-col items-center justify-center">
                        <svg class="w-16 h-16 sm:w-20 sm:h-20 text-[#4382DF]">
                            <use href="#i-shield"/>
                        </svg>
                        <span class="mt-4 text-[9px] tracking-widest text-white/80">
                            SHADOW GUARD
                        </span>
                    </div>

                    <div class="flex justify-between mt-3 text-xs">
                        <span class="text-white/50">ATK</span>
                        <span class="font-black text-[#4382DF]">85</span>
                    </div>

                    <div class="h-1 bg-[#112E81] rounded-full mt-2">
                        <div class="h-full w-[85%] bg-[#4382DF] rounded-full"></div>
                    </div>
                </div>

                {{-- Main card --}}
                <div class="relative z-20 w-[190px] sm:w-[245px] glass rounded-2xl p-4 sm:p-5
                            border-[#4382DF]/70 shadow-[0_0_55px_rgba(70,71,174,0.22)]">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-[10px] tracking-widest text-[#4382DF] uppercase">
                            Featured Card
                        </span>
                        <svg class="w-5 h-5 text-[#4382DF]"><use href="#i-zap"/></svg>
                    </div>

                    <div class="relative aspect-[4/5] rounded-xl overflow-hidden
                                border border-[#4647AE]/70 bg-gradient-to-br from-[#112E81]
                                via-[#090E39] to-black">
                        <div class="absolute inset-0"
                             style="background-image:linear-gradient(rgba(67,130,223,.10) 1px,transparent 1px),linear-gradient(90deg,rgba(67,130,223,.10) 1px,transparent 1px);background-size:20px 20px">
                        </div>

                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-[9px] tracking-[.25em] text-[#4382DF]">
                                CLASS / LEGEND
                            </span>

                            <svg class="w-24 h-24 sm:w-28 sm:h-28 text-white mt-5
                                        drop-shadow-[0_0_22px_rgba(67,130,223,.6)]">
                                <use href="#i-swords"/>
                            </svg>

                            <h3 class="mt-6 font-black font-sans text-sm sm:text-base tracking-wider">
                                ARCANE KNIGHT
                            </h3>

                            <p class="text-[9px] text-white/50 tracking-widest mt-2">
                                LEGENDARY WARRIOR
                            </p>
                        </div>

                        <div class="absolute bottom-3 left-3 right-3 flex justify-between text-[10px]">
                            <span class="px-2 py-1 rounded bg-[#112E81] text-white">ATK 99</span>
                            <span class="px-2 py-1 rounded bg-[#4647AE] text-white">HP 120</span>
                        </div>
                    </div>

                    <div class="flex justify-between mt-4 text-[10px] text-white/50">
                        <span>BATTLE READY</span>
                        <span class="text-[#4382DF]">LV. 99</span>
                    </div>
                </div>

                {{-- Right card --}}
                <div class="float-two absolute right-[1%] sm:right-[5%] bottom-[12%] z-30
                            w-[130px] sm:w-[165px] glass rounded-2xl p-3 sm:p-4 rotate-[9deg]">
                    <div class="flex justify-between text-[9px] text-white/50 mb-3">
                        <span>NV-008</span>
                        <span class="text-[#4382DF]">EPIC</span>
                    </div>

                    <div class="aspect-[4/5] rounded-xl border border-[#4647AE]/60
                                bg-gradient-to-br from-[#112E81] to-black flex flex-col
                                items-center justify-center">
                        <svg class="w-16 h-16 sm:w-20 sm:h-20 text-[#4382DF]">
                            <use href="#i-target"/>
                        </svg>
                        <span class="mt-4 text-[9px] tracking-widest text-white/80">
                            VOID HUNTER
                        </span>
                    </div>

                    <div class="flex justify-between mt-3 text-xs">
                        <span class="text-white/50">HP</span>
                        <span class="font-black text-[#4382DF]">120</span>
                    </div>

                    <div class="h-1 bg-[#112E81] rounded-full mt-2">
                        <div class="h-full w-[70%] bg-[#4647AE] rounded-full"></div>
                    </div>
                </div>

                {{-- Status --}}
                <div class="absolute bottom-0 z-40 flex items-center gap-3 px-4 py-3 rounded-xl
                            bg-[#090E39] border border-[#4647AE]/60">
                    <div class="icon-box w-10 h-10">
                        <svg class="w-5 h-5"><use href="#i-zap"/></svg>
                    </div>

                    <div>
                        <p class="text-[10px] font-bold">BATTLE SYSTEM</p>
                        <p class="text-[9px] text-white/50 mt-1">STRATEGY IS EVERYTHING</p>
                    </div>

                    <span class="w-2 h-2 rounded-full bg-[#4382DF] pulse"></span>
                </div>
            </div>
        </div>

        <div class="mt-20 h-px bg-gradient-to-r from-transparent via-[#4647AE] to-transparent"></div>
    </section>

    {{-- =========================================
         FEATURES: FUTURISTIC CUT-CORNER PANELS
    ========================================== --}}
    <section id="fitur"
             class="relative max-w-[1600px] mx-auto px-5 sm:px-8 py-20 sm:py-28 overflow-hidden">

        {{-- Background decoration --}}
        <div class="absolute -top-8 right-0 w-72 h-72 rotate-45
                    border-[28px] border-[#112E81]/20 pointer-events-none"></div>

        <div class="absolute bottom-10 left-0 w-56 h-56
                    bg-[#4647AE]/10 blur-[100px] pointer-events-none"></div>

        <div class="absolute top-1/2 right-1/4 w-64 h-64
                    bg-[#4382DF]/5 blur-[100px] pointer-events-none"></div>

        {{-- Heading --}}
        <div class="relative z-10 text-center mb-14 sm:mb-16">
            <div class="inline-flex items-center gap-3 mb-5">
                <span class="w-8 sm:w-16 h-px bg-[#4647AE]"></span>

                <span class="border border-[#263c9a] px-5 py-2 font-mono
                             text-[10px] sm:text-xs font-bold tracking-[.22em] text-[#80a4ff]">
                    // FITUR UTAMA
                </span>

                <span class="w-8 sm:w-16 h-px bg-[#4647AE]"></span>
            </div>

            <h2 class="font-sans text-3xl sm:text-5xl lg:text-6xl
                       font-black uppercase leading-tight tracking-tight">
                BUKAN SEKADAR
                <span class="bg-gradient-to-r from-[#4382DF] to-[#6551ff]
                             bg-clip-text text-transparent">
                    KARTU.
                </span>
            </h2>

            <p class="max-w-3xl mx-auto mt-5 text-sm sm:text-lg leading-7
                      text-slate-300 font-sans">
                Strategi, kemampuan, dan keputusan menentukan perjalananmu menuju puncak arena.
            </p>
        </div>

        {{-- Feature panels --}}
        <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3
                    gap-6 lg:gap-7 items-stretch">

            {{-- FEATURE 01 --}}
            <article class="n-feature-card group">
                <div class="n-feature-inner">
                    <div class="n-feature-accent"></div>

                    <div class="flex items-start justify-between gap-3">
                        <div class="n-feature-icon transition-transform duration-300 group-hover:scale-105">
                            <svg class="w-11 h-11 text-white" viewBox="0 0 48 48"
                                 fill="none" stroke="currentColor" stroke-width="2.5"
                                 stroke-linejoin="round" aria-hidden="true">
                                <path d="M24 5 42 15 24 25 6 15 24 5Z"/>
                                <path d="M6 24 24 34 42 24"/>
                                <path d="M6 33 24 43 42 33"/>
                            </svg>
                        </div>

                        <div class="relative min-w-[76px] h-[76px]">
                            <span class="n-feature-number">01</span>
                            <span class="n-feature-label">/ DECK</span>
                        </div>
                    </div>

                    <h3 class="n-feature-title font-sans">
                        Build Your Deck <span>///</span>
                    </h3>

                    <p class="n-feature-description font-sans">
                        Rancang komposisi kartu dan temukan kombinasi yang tepat untuk menghadapi lawan.
                    </p>

                    <div class="n-feature-link mt-7">
                        <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2.2"
                             aria-hidden="true">
                            <path d="M4 12h15M13 5l7 7-7 7"/>
                        </svg>
                        Strategy starts here
                    </div>

                    <span aria-hidden="true"
                          class="pointer-events-none absolute -bottom-5 -right-1
                                 select-none text-8xl font-black italic text-[#172b81]/20">
                        01
                    </span>
                </div>
            </article>

            {{-- FEATURE 02 --}}
            <article class="n-feature-card group">
                <div class="n-feature-inner">
                    <div class="n-feature-accent" style="width:120px"></div>

                    <div class="flex items-start justify-between gap-3">
                        <div class="n-feature-icon transition-transform duration-300 group-hover:scale-105">
                            <svg
                                class="w-11 h-11 text-white"
                                viewBox="0 0 48 48"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                            >
                                <!-- Pedang diagonal dari kiri bawah ke kanan atas -->
                                <path d="M12 36 L33 10 L37 6 L35 13 L15 38 Z" />
                                <path d="M10 34 L17 41" />
                                <path d="M8 36 L13 31" />

                                <!-- Pedang diagonal dari kiri atas ke kanan bawah -->
                                <path d="M12 10 L35 34 L39 38 L32 36 L10 14 Z" />
                                <path d="M31 31 L38 24" />
                                <path d="M34 34 L40 28" />
                            </svg>
                        </div>

                        <div>
                            <span class="n-feature-number">02</span>
                            <span class="n-feature-label">/ BATTLE</span>
                        </div>
                    </div>

                    <h3 class="n-feature-title font-sans">
                        Outplay Your Rival <span>///</span>
                    </h3>

                    <p class="n-feature-description font-sans">
                        Baca strategi lawan, manfaatkan setiap giliran, dan temukan celah untuk menang.
                    </p>

                    <div class="n-feature-link mt-7">
                        <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2.2"
                             aria-hidden="true">
                            <path d="M4 12h15M13 5l7 7-7 7"/>
                        </svg>
                        Every move matters
                    </div>

                    <span aria-hidden="true"
                          class="pointer-events-none absolute -bottom-5 -right-1
                                 select-none text-8xl font-black italic text-[#172b81]/20">
                        02
                    </span>
                </div>
            </article>

            {{-- FEATURE 03 --}}
            <article class="n-feature-card group">
                <div class="n-feature-inner">
                    <div class="n-feature-accent" style="width:90px"></div>

                    <div class="flex items-start justify-between gap-3">
                        <div class="n-feature-icon transition-transform duration-300 group-hover:scale-105">
                            <svg class="w-11 h-11 text-white" viewBox="0 0 48 48"
                                 fill="none" stroke="currentColor" stroke-width="2.4"
                                 stroke-linecap="round" stroke-linejoin="round"
                                 aria-hidden="true">
                                <path d="M15 8h18v14a9 9 0 0 1-18 0V8Z"/>
                                <path d="M15 12H8v5a9 9 0 0 0 8 9"/>
                                <path d="M33 12h7v5a9 9 0 0 1-8 9"/>
                                <path d="M24 31v7M17 40h14M20 38h8"/>
                                <path d="m24 12 1.8 3.7 4.1.6-3 2.9.7 4.1-3.6-1.9-3.6 1.9.7-4.1-3-2.9 4.1-.6L24 12Z"/>
                            </svg>
                        </div>

                        <div>
                            <span class="n-feature-number">03</span>
                            <span class="n-feature-label">/ RANKING</span>
                        </div>
                    </div>

                    <h3 class="n-feature-title font-sans">
                        Claim Your Glory <span>///</span>
                    </h3>

                    <p class="n-feature-description font-sans">
                        Buktikan kemampuanmu, raih kemenangan, dan kejar posisi terbaik di leaderboard.
                    </p>

                    <div class="n-feature-link mt-7">
                        <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2.2"
                             aria-hidden="true">
                            <path d="M4 12h15M13 5l7 7-7 7"/>
                        </svg>
                        Climb the ranks
                    </div>

                    <span aria-hidden="true"
                          class="pointer-events-none absolute -bottom-5 -right-1
                                 select-none text-8xl font-black italic text-[#172b81]/20">
                        03
                    </span>
                </div>
            </article>
        </div>

        {{-- Bottom HUD status --}}
        <div class="relative z-10 flex flex-wrap items-center justify-between gap-4
                    mt-10 py-4 px-5 border-y border-[#4647AE]/25">
            <div class="flex items-center gap-3">
                <span class="w-2 h-2 rounded-full bg-[#4382DF]"></span>
                <span class="text-[9px] sm:text-[10px] tracking-[.15em] text-white/45">
                    SYSTEM READY
                </span>
            </div>

            <span class="text-[9px] sm:text-[10px] tracking-[.15em] text-white/30">
                SELECT YOUR PATH // MASTER THE ARENA
            </span>
        </div>
    </section>

    {{-- JOURNEY --}}
    <section class="max-w-7xl mx-auto px-5 sm:px-8 py-12 sm:py-20">
        <div class="relative overflow-hidden rounded-3xl border border-[#4647AE]/50
                    bg-gradient-to-br from-[#090E39] via-black to-[#112E81]/40
                    p-7 sm:p-12 lg:p-16">

            <div class="grid lg:grid-cols-2 gap-10 items-center">
                <div>
                    <p class="text-xs tracking-[.25em] text-[#4382DF] mb-5">
                        // YOUR JOURNEY
                    </p>

                    <h2 class="text-3xl sm:text-5xl font-black font-sans leading-tight">
                        SIAP UNTUK
                        <span class="gradient-text">MENANTANG?</span>
                    </h2>

                    <p class="text-sm sm:text-base text-white/55 leading-7 mt-5 max-w-lg font-sans">
                        Mulai perjalananmu, pelajari mekanisme permainan,
                        dan buktikan kemampuanmu di arena.
                    </p>

                    <a href="{{ route('register') }}"
                       class="neon-button inline-flex items-center gap-3 mt-8 px-7 py-4
                              rounded-lg font-sans font-black text-sm">
                        BUAT AKUN SEKARANG
                        <svg class="w-5 h-5"><use href="#i-arrow"/></svg>
                    </a>
                </div>

                <div class="space-y-4">
                    @foreach ([
                        ['01', 'CREATE YOUR ACCOUNT', 'Daftar dan siapkan identitas pemainmu.'],
                        ['02', 'MASTER YOUR STRATEGY', 'Pelajari mekanisme dan strategi permainan.'],
                        ['03', 'ENTER THE ARENA', 'Mulai perjalananmu menuju puncak peringkat.']
                    ] as $step)
                        <div class="flex items-center gap-4 rounded-xl border border-[#4647AE]/30
                                    bg-black/40 p-5">
                            <span class="text-2xl font-black text-[#4382DF]">
                                {{ $step[0] }}
                            </span>

                            <div>
                                <h3 class="font-sans font-bold text-sm">
                                    {{ $step[1] }}
                                </h3>

                                <p class="text-xs text-white/50 mt-2 leading-5">
                                    {{ $step[2] }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- LEADERBOARD --}}
    <section id="leaderboard"
             class="max-w-7xl mx-auto px-5 sm:px-8 pt-16 pb-24 sm:pt-20 sm:pb-32">

        <div class="mb-10">
            <p class="text-xs tracking-[.25em] text-[#4382DF] mb-4">
                // GLOBAL COMPETITION
            </p>

            <h2 class="text-3xl sm:text-5xl font-black font-sans">
                HALL OF <span class="gradient-text">LEGENDS.</span>
            </h2>

            <p class="text-sm text-white/55 mt-4 font-sans">
                Para petarung terbaik akan tercatat dalam sejarah arena.
            </p>
        </div>

        <div class="relative overflow-hidden rounded-2xl border border-[#4647AE]/50 bg-[#05050F]">
            <div class="p-5 sm:p-8 min-h-[350px] relative">
                <div class="grid sm:grid-cols-3 gap-5 blur-[5px] opacity-60
                            pointer-events-none select-none">

                    @forelse ($topPlayers->take(3) as $index => $p)
                        <div class="glass rounded-2xl p-6 text-center
                                    {{ $index === 0 ? 'sm:-translate-y-3' : '' }}">
                            <div class="text-xs text-white/45">
                                RANK {{ sprintf('%02d', $index + 1) }}
                            </div>

                            <svg class="w-12 h-12 mx-auto mt-5 text-[#4382DF]">
                                <use href="{{ $index === 0 ? '#i-crown' : ($index === 1 ? '#i-swords' : '#i-shield') }}"/>
                            </svg>

                            <h3 class="font-sans font-black mt-4">
                                {{ $p->user->name ?? 'Player' }}
                            </h3>

                            <p class="text-xs text-[#4382DF] mt-3">
                                {{ $p->wins }} WINS
                            </p>
                        </div>
                    @empty
                        @foreach (['#i-crown', '#i-swords', '#i-shield'] as $index => $icon)
                            <div class="glass rounded-2xl p-6 text-center">
                                <p class="text-xs text-white/45">
                                    RANK {{ sprintf('%02d', $index + 1) }}
                                </p>

                                <svg class="w-12 h-12 mx-auto mt-5 text-[#4382DF]">
                                    <use href="{{ $icon }}"/>
                                </svg>

                                <p class="font-sans font-bold mt-4">MYSTERY PLAYER</p>

                                <p class="text-xs text-[#4382DF] mt-3">-- WINS</p>
                            </div>
                        @endforeach
                    @endforelse
                </div>

                {{-- LOCK OVERLAY --}}
                <div class="absolute inset-0 flex items-center justify-center p-5">
                    <div class="glass rounded-2xl p-7 sm:p-8 max-w-md w-full text-center
                                border-[#4382DF]/50 shadow-[0_0_45px_rgba(70,71,174,0.18)]">

                        <div class="icon-box w-14 h-14 mx-auto">
                            <svg class="w-7 h-7"><use href="#i-lock"/></svg>
                        </div>

                        <p class="text-[10px] uppercase tracking-[.25em] text-[#4382DF] mt-5">
                            Restricted Access
                        </p>

                        <h3 class="font-sans font-black text-xl sm:text-2xl mt-3">
                            AKSES TERBATAS
                        </h3>

                        <p class="text-sm text-white/55 leading-6 mt-3 font-sans">
                            Login untuk melihat statistik dan peringkat pemain di arena.
                        </p>

                        <a href="{{ route('login') }}"
                           class="inline-flex items-center justify-center gap-2 mt-6 px-7 py-3.5
                                  rounded-lg bg-white text-black font-sans font-black text-sm
                                  hover:bg-[#4382DF] hover:text-white transition">
                            LOGIN SEKARANG
                            <svg class="w-4 h-4"><use href="#i-arrow"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="border-t border-[#4647AE]/20 px-5 sm:px-8 py-4 flex justify-between gap-4
                        text-[9px] sm:text-[10px] text-white/35 tracking-widest">
                <span>NAVO CLASH / LEADERBOARD</span>
                <span>PLAYER ACCESS</span>
            </div>
        </div>
    </section>

    {{-- FINAL CTA --}}
    <section class="max-w-7xl mx-auto px-5 sm:px-8 pb-24">
        <div class="relative overflow-hidden rounded-3xl border border-[#4647AE]/50
                    bg-gradient-to-br from-[#090E39] via-black to-[#112E81]/40
                    p-8 sm:p-14 text-center">

            <div class="icon-box w-16 h-16 mx-auto">
                <svg class="w-8 h-8"><use href="#i-shield"/></svg>
            </div>

            <p class="text-xs tracking-[.25em] text-[#4382DF] mt-6 mb-5">
                THE ARENA IS WAITING
            </p>

            <h2 class="text-3xl sm:text-5xl font-black font-sans leading-tight">
                GILIRANMU UNTUK
                <span class="gradient-text">MENANG.</span>
            </h2>

            <p class="max-w-xl mx-auto text-sm sm:text-base text-white/55 leading-7 mt-5 font-sans">
                Siapkan strategi terbaikmu. Kisah sang juara dimulai dari satu langkah.
            </p>

            <a href="{{ route('register') }}"
               class="neon-button inline-flex items-center gap-3 mt-8 px-9 py-4 rounded-lg
                      font-sans font-black text-sm sm:text-base">
                GABUNG KE ARENA
                <svg class="w-5 h-5"><use href="#i-arrow"/></svg>
            </a>
        </div>
    </section>
</main>

{{-- FOOTER --}}
<footer class="relative z-10 border-t border-[#4647AE]/30 bg-black">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 py-7 flex flex-col sm:flex-row
                justify-between items-center gap-4">

        <a href="{{ url('/') }}" class="font-black tracking-[.16em] text-sm">
            <span class="text-[#4382DF]">NAVO</span>
            <span class="text-white">CLASH</span>
        </a>

        <p class="text-[10px] sm:text-xs text-white/40 text-center">
            STRATEGY IS YOUR GREATEST WEAPON.
        </p>

        <p class="text-[10px] text-white/35">
            &copy; {{ date('Y') }} NAVO CLASH
        </p>
    </div>
</footer>

</body>
</html>