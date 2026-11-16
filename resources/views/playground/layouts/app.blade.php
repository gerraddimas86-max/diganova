<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="view-transition" content="same-origin">

    <title>@yield('title', 'Playground — DIGANOVA')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#050505] text-white font-mono selection:bg-[#4382DF]/40">

    @php
        $pointsDisplay = auth()->check()
            ? auth()->user()->total_points
            : 0;
    @endphp

    {{-- ============ NAVBAR HEXA-CUT ============ --}}
    <header class="sticky top-0 z-40 px-2 pt-2 sm:px-4 sm:pt-3">

        <nav class="relative mx-auto max-w-7xl">

            {{-- Glow luar --}}
            <div class="pointer-events-none absolute -inset-1 rounded-[2.5rem] bg-gradient-to-r from-[#4382DF]/50 via-[#4647AE]/50 to-[#4382DF]/50 opacity-70 blur-2xl"></div>

            {{-- Body dengan clip-path sudut miring (bukan kotak) --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-[#090E39] via-[#0c1448] to-[#112E81] shadow-[0_10px_50px_rgba(9,14,57,0.95)]"
                 style="clip-path: polygon(
                    0 0,
                    calc(100% - 26px) 0,
                    100% 26px,
                    100% 100%,
                    26px 100%,
                    0 calc(100% - 26px)
                 );">

                {{-- Border gradien tipis via pseudo layer --}}
                <div class="pointer-events-none absolute inset-0 border border-[#4382DF]/40"
                     style="clip-path: polygon(
                        0 0,
                        calc(100% - 26px) 0,
                        100% 26px,
                        100% 100%,
                        26px 100%,
                        0 calc(100% - 26px)
                     );"></div>

                {{-- Dekorasi blob --}}
                <div class="pointer-events-none absolute -left-16 -top-16 h-44 w-44 rounded-full bg-[#4647AE]/40 blur-3xl"></div>
                <div class="pointer-events-none absolute -bottom-20 -right-10 h-44 w-44 rounded-full bg-[#4382DF]/40 blur-3xl"></div>

                {{-- Grid dekoratif --}}
                <div class="pointer-events-none absolute inset-0 opacity-[0.07]"
                     style="background-image: linear-gradient(#4382DF 1px, transparent 1px), linear-gradient(90deg, #4382DF 1px, transparent 1px); background-size: 26px 26px;"></div>

                {{-- Aksen garis miring dekoratif kiri-atas --}}
                <div class="pointer-events-none absolute left-0 top-0 h-full w-1 bg-gradient-to-b from-[#4382DF] via-[#4647AE] to-transparent"></div>

                {{-- ===== KONTEN ===== --}}
                <div class="relative flex h-[86px] items-center justify-between gap-3 px-4 sm:px-6 lg:px-8">

                    {{-- ============ BRAND ============ --}}
                    <div class="flex min-w-0 items-center gap-2 sm:gap-3">

                        @auth
                            {{-- HUB: bentuk tag/jajar genjang --}}
                            <a href="{{ route('hub') }}" title="Kembali ke Hub"
                               class="group relative flex shrink-0 items-center gap-1.5 px-3.5 py-2 text-[#4382DF] transition-colors duration-200 hover:text-white">
                                <span class="absolute inset-0 -skew-x-12 rounded-md border border-[#4382DF]/40 bg-[#090E39]/70 transition-all duration-300 group-hover:border-[#4382DF] group-hover:bg-[#112E81]/80 group-hover:shadow-[0_0_18px_rgba(67,130,223,0.5)]"></span>
                                <svg class="relative h-4 w-4 transition group-hover:-translate-y-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 12L12 3l9 9"/>
                                    <path d="M5 10v10h14V10"/>
                                    <path d="M9 20v-6h6v6"/>
                                </svg>
                                <span class="relative text-[10px] font-bold uppercase tracking-[0.18em]">Hub</span>
                            </a>
                        @endauth

                        {{-- LOGO: hexagon emblem + teks --}}
                        <a href="{{ route('play.home') }}" class="group flex shrink-0 items-center gap-2">
                            {{-- Hexagon badge dengan inisial D --}}
                            <div class="relative flex h-9 w-9 items-center justify-center transition-transform duration-300 group-hover:rotate-[20deg]">
                                <div class="absolute inset-0 bg-gradient-to-br from-[#4382DF] to-[#4647AE]"
                                     style="clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);"></div>
                                <div class="absolute inset-[2px] bg-[#090E39]"
                                     style="clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);"></div>
                                <span class="relative font-sans text-sm font-black text-[#4382DF]">D</span>
                            </div>
                            <div class="flex flex-col leading-none">
                                <span class="font-sans text-base font-black tracking-[0.16em] bg-gradient-to-r from-white via-[#4382DF] to-white bg-clip-text text-transparent sm:text-lg">
                                    DIGANOVA
                                </span>
                                <span class="mt-0.5 text-[8px] font-bold uppercase tracking-[0.3em] text-[#4647AE]">
                                    Playground
                                </span>
                            </div>
                        </a>

                    </div>

                    {{-- ============ NAVIGASI ============ --}}
                    <div class="flex shrink-0 items-center gap-1 font-sans sm:gap-1.5">

                        @auth

                            {{-- LOBBY --}}
                            @php $activeLobby = request()->routeIs('play.home'); @endphp
                            <a href="{{ route('play.home') }}"
                               class="group relative hidden items-center gap-1.5 px-3 py-1.5 text-[10px] font-black uppercase tracking-wider transition-colors duration-200 sm:inline-flex
                               {{ $activeLobby ? 'text-white' : 'text-slate-300 hover:text-white' }}">
                                <span class="absolute inset-0 -skew-x-12 transition-all duration-300
                                    {{ $activeLobby
                                        ? 'bg-gradient-to-r from-[#112E81] to-[#4647AE] shadow-[0_0_16px_rgba(67,130,223,0.5)]'
                                        : 'bg-transparent group-hover:bg-[#112E81]/50' }}">
                                </span>
                                <svg class="relative h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 12L12 3l9 9"/><path d="M5 10v10h14V10"/><path d="M9 20v-6h6v6"/>
                                </svg>
                                <span class="relative">Lobby</span>
                            </a>

                            {{-- DECK --}}
                            @php $activeDeck = request()->routeIs('play.decks.*'); @endphp
                            <a href="{{ route('play.decks.index') }}"
                               class="group relative hidden items-center gap-1.5 px-3 py-1.5 text-[10px] font-black uppercase tracking-wider transition-colors duration-200 md:inline-flex
                               {{ $activeDeck ? 'text-white' : 'text-slate-300 hover:text-white' }}">
                                <span class="absolute inset-0 -skew-x-12 transition-all duration-300
                                    {{ $activeDeck
                                        ? 'bg-gradient-to-r from-[#112E81] to-[#4647AE] shadow-[0_0_16px_rgba(67,130,223,0.5)]'
                                        : 'bg-transparent group-hover:bg-[#112E81]/50' }}">
                                </span>
                                {{-- icon: kartu bertumpuk --}}
                                <svg class="relative h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="7" width="12" height="14" rx="2"/>
                                    <path d="M7 4h10a2 2 0 0 1 2 2v12"/>
                                </svg>
                                <span class="relative">Deck</span>
                            </a>

                            
{{-- GACHA --}}
@php $activeGacha = request()->routeIs('play.gacha.*'); @endphp

<a href="{{ route('play.gacha.index') }}"
   class="group relative hidden items-center gap-1.5 px-3 py-1.5 text-[10px] font-black uppercase tracking-wider transition-colors duration-200 md:inline-flex
   {{ $activeGacha ? 'text-white' : 'text-slate-300 hover:text-white' }}">

    {{-- Background aktif --}}
    <span class="absolute inset-0 -skew-x-12 transition-all duration-300
        {{ $activeGacha
            ? 'bg-gradient-to-r from-[#112E81] to-[#4647AE] shadow-[0_0_16px_rgba(67,130,223,0.5)]'
            : 'bg-transparent group-hover:bg-[#112E81]/50' }}">
    </span>

    {{-- Icon Gacha: kartu dengan kilauan --}}
    <svg
        class="relative h-3.5 w-3.5 transition-transform duration-300 group-hover:-rotate-6 group-hover:scale-110"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
        aria-hidden="true"
    >
        <rect x="5" y="4" width="14" height="17" rx="2"/>
        <path d="M9 8h6"/>
        <path d="M12 11l1.2 2.4L16 14l-2 1.9.5 2.7-2.5-1.3-2.5 1.3.5-2.7-2-1.9 2.8-.6L12 11z"/>
        <path d="M19 3v3"/>
        <path d="M17.5 4.5h3"/>
    </svg>

    <span class="relative">Gacha</span>
</a>


                            {{-- BATTLE --}}
                            @php $activeBattle = request()->routeIs('play.battle.*'); @endphp
                            <a href="{{ route('play.battle.pve') }}"
                               class="group relative hidden items-center gap-1.5 px-3 py-1.5 text-[10px] font-black uppercase tracking-wider transition-colors duration-200 md:inline-flex
                               {{ $activeBattle ? 'text-white' : 'text-slate-300 hover:text-white' }}">
                                <span class="absolute inset-0 -skew-x-12 transition-all duration-300
                                    {{ $activeBattle
                                        ? 'bg-gradient-to-r from-[#112E81] to-[#4647AE] shadow-[0_0_16px_rgba(67,130,223,0.5)]'
                                        : 'bg-transparent group-hover:bg-[#112E81]/50' }}">
                                </span>
                                {{-- icon: pedang silang --}}
                                <svg class="relative h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14.5 17.5 3 6V3h3l11.5 11.5"/>
                                    <path d="M13 19l6-6"/>
                                    <path d="M16 16l4 4"/>
                                    <path d="M19 21l2-2"/>
                                </svg>
                                <span class="relative">Battle</span>
                            </a>

                            {{-- RANK --}}
                            @php $activeRank = request()->routeIs('play.leaderboard'); @endphp
                            <a href="{{ route('play.leaderboard') }}"
                               class="group relative hidden items-center gap-1.5 px-3 py-1.5 text-[10px] font-black uppercase tracking-wider transition-colors duration-200 md:inline-flex
                               {{ $activeRank ? 'text-white' : 'text-slate-300 hover:text-white' }}">
                                <span class="absolute inset-0 -skew-x-12 transition-all duration-300
                                    {{ $activeRank
                                        ? 'bg-gradient-to-r from-[#112E81] to-[#4647AE] shadow-[0_0_16px_rgba(67,130,223,0.5)]'
                                        : 'bg-transparent group-hover:bg-[#112E81]/50' }}">
                                </span>
                                {{-- icon: piala --}}
                                <svg class="relative h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 4h12v5a6 6 0 0 1-12 0V4Z"/>
                                    <path d="M6 6H3v2a4 4 0 0 0 4 4"/>
                                    <path d="M18 6h3v2a4 4 0 0 1-4 4"/>
                                    <path d="M12 15v4"/>
                                    <path d="M8 21h8"/>
                                </svg>
                                <span class="relative">Rank</span>
                            </a>

                            {{-- POINTS: diamond/pil dengan hexagon icon --}}
                            <div class="group relative ml-1 hidden items-center gap-2 py-1 pl-1.5 pr-3 sm:flex">
                                <span class="absolute inset-0 -skew-x-12 rounded-md border border-[#4382DF]/50 bg-[#090E39]/80 transition-all duration-300 group-hover:border-[#4382DF] group-hover:shadow-[0_0_20px_rgba(67,130,223,0.45)]"></span>
                                {{-- bintang di dalam hexagon --}}
                                <span class="relative flex h-7 w-7 items-center justify-center">
                                    <span class="absolute inset-0 bg-gradient-to-br from-[#4647AE] to-[#112E81]"
                                          style="clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);"></span>
                                    <svg class="relative h-3.5 w-3.5 text-[#4382DF] transition-transform duration-500 group-hover:rotate-[72deg]" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="m12 3 2.7 5.5 6.1.9-4.4 4.3 1 6.1-5.4-2.9-5.4 2.9 1-6.1-4.4-4.3 6.1-.9L12 3Z"/>
                                    </svg>
                                </span>
                                <div class="relative flex flex-col leading-none">
                                    <span class="text-sm font-black tabular-nums text-white">{{ $pointsDisplay }}</span>
                                    <span class="text-[7px] font-bold uppercase tracking-[0.22em] text-[#4382DF]/70">poin</span>
                                </div>
                            </div>

                            {{-- PROFILE: hex ring --}}
                            <a href="{{ route('profile.show') }}" title="Profil"
                               class="group relative ml-1 flex h-10 w-10 shrink-0 items-center justify-center transition-transform duration-300 hover:scale-110 active:scale-90">
                                <span class="absolute inset-0 bg-[conic-gradient(from_0deg,#4382DF,#4647AE,#4382DF)]"
                                      style="clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%); animation: spinSlow 6s linear infinite;"></span>
                                <span class="absolute inset-[2px] bg-[#090E39]"
                                      style="clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);"></span>
                                <span class="relative flex items-center justify-center">
                                    <x-navo :skin="auth()->user()->navoProfile?->equippedSkin" size="w-8 h-8" textSize="text-sm" />
                                </span>
                            </a>

                            {{-- LOGOUT: hex kecil --}}
                            <form method="POST" action="{{ route('logout') }}" class="m-0 shrink-0">
                                @csrf
                                <button type="submit" title="Logout"
                                        class="group relative flex h-8 w-8 items-center justify-center text-red-400/80 transition-colors duration-200 hover:text-red-300 active:scale-90">
                                    <span class="absolute inset-0 bg-red-500/10 opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                                          style="clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);"></span>
                                    <svg class="relative h-3.5 w-3.5 transition-transform duration-300 group-hover:rotate-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                        <polyline points="16 17 21 12 16 7"/>
                                        <line x1="21" y1="12" x2="9" y2="12"/>
                                    </svg>
                                </button>
                            </form>

                        @else

                            {{-- LOGIN --}}
                            <a href="{{ route('login') }}"
                               class="group relative flex items-center gap-1.5 px-3 py-2 text-xs font-bold uppercase tracking-wide text-slate-300 transition-colors duration-200 hover:text-white">
                                <span class="absolute inset-0 -skew-x-12 rounded-md bg-transparent transition-all duration-300 group-hover:bg-[#112E81]/50"></span>
                                <svg class="relative h-4 w-4 text-[#4382DF]/70 transition group-hover:text-[#4382DF]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                                    <polyline points="10 17 15 12 10 7"/>
                                    <line x1="15" y1="12" x2="3" y2="12"/>
                                </svg>
                                <span class="relative">Login</span>
                            </a>

                            {{-- REGISTER: pil miring dengan kilau --}}
                            <a href="{{ route('register') }}"
                               class="group relative flex items-center gap-1.5 px-4 py-2 text-xs font-black uppercase tracking-wide text-white transition-all duration-300 active:scale-95">
                                <span class="absolute inset-0 -skew-x-12 rounded-md bg-gradient-to-r from-[#112E81] via-[#4647AE] to-[#112E81] shadow-[0_0_18px_rgba(67,130,223,0.4)] ring-1 ring-[#4382DF]/50 transition-all duration-300 group-hover:shadow-[0_0_30px_rgba(67,130,223,0.65)]"></span>
                                <span class="absolute inset-0 -skew-x-12 overflow-hidden rounded-md">
                                    <span class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/30 to-transparent transition-transform duration-700 group-hover:translate-x-full"></span>
                                </span>
                                <svg class="relative z-10 h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                    <circle cx="8.5" cy="7" r="4"/>
                                    <line x1="20" y1="8" x2="20" y2="14"/>
                                    <line x1="23" y1="11" x2="17" y2="11"/>
                                </svg>
                                <span class="relative z-10">Daftar</span>
                            </a>

                        @endauth

                    </div>

                </div>

                {{-- Garis aksen bawah melengkung --}}
                <div class="relative h-[2px] w-full bg-gradient-to-r from-transparent via-[#4382DF] to-transparent opacity-70"></div>

            </div>
        </nav>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8">
        @yield('content')
    </main>

    <style>
        @keyframes spinSlow { to { transform: rotate(360deg); } }
    </style>

</body>
</html>