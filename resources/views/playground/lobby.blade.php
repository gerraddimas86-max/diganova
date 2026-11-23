@extends('playground.layouts.app')

@section('title', 'Cyber Lobby — DIGANOVA Playground')

@section('content')

<div class="relative isolate space-y-7 overflow-hidden text-[var(--text)]">

    {{-- =========================================================
         FULL SCREEN GRID BACKGROUND
    ========================================================== --}}
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden bg-[var(--bg)]">

        {{-- Pola kotak memenuhi seluruh layar --}}
        <div
            class="absolute inset-0"
            style="
                background-image:
                    linear-gradient(var(--grid) 1px, transparent 1px),
                    linear-gradient(90deg, var(--grid) 1px, transparent 1px);
                background-size: 45px 45px;
                background-position: center top;
            ">
        </div>

        {{-- Cahaya cyan dekoratif --}}
        <div class="absolute -left-40 top-0 h-[500px] w-[500px] rounded-full bg-[var(--glow)] blur-[150px]"></div>
        <div class="absolute -right-40 bottom-0 h-[500px] w-[500px] rounded-full bg-[var(--glow)] blur-[150px]"></div>
    </div>


    {{-- =========================================================
         CYBER LOBBY HEADER
    ========================================================== --}}
    <section class="group relative isolate overflow-hidden border border-[var(--line)] bg-[var(--panel)] transition duration-500 hover:border-[var(--cyan)]">

        {{-- Header Glow --}}
        <div class="absolute -left-20 -top-24 -z-10 h-72 w-72 rounded-full bg-[var(--glow)] blur-[100px]"></div>
        <div class="absolute -right-10 bottom-0 -z-10 h-56 w-56 rounded-full bg-[var(--glow)] blur-[100px]"></div>

        {{-- Decorative Lines --}}
        <div class="absolute left-0 top-0 h-px w-2/3 bg-gradient-to-r from-[var(--cyan)] via-[var(--cyan)]/50 to-transparent"></div>
        <div class="absolute bottom-0 right-0 h-px w-1/3 bg-gradient-to-l from-[var(--cyan)] to-transparent"></div>

        {{-- Header Content --}}
        <div class="relative z-10 flex flex-col gap-8 p-6 sm:p-8 lg:flex-row lg:items-center lg:justify-between lg:p-10">

            <div class="min-w-0 flex-1">

                {{-- System Status --}}
                <div class="mb-6 inline-flex items-center gap-3 rounded-full border border-[var(--line-strong)] bg-[var(--bg-soft)] px-4 py-2">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[var(--cyan)] opacity-60"></span>
                        <span class="relative h-2 w-2 rounded-full bg-[var(--cyan)]"></span>
                    </span>
                    <span class="text-[9px] font-bold uppercase tracking-[0.3em] text-[var(--cyan)]">
                        System Online
                    </span>
                </div>

                {{-- Main Heading --}}
                <h1 class="text-4xl font-black uppercase leading-[1.05] tracking-tight sm:text-5xl lg:text-6xl">
                    Cyber
                    <span class="mt-2 block text-[var(--cyan)]">
                        Lobby.
                    </span>
                </h1>

                <p class="mt-5 max-w-xl text-sm leading-7 text-[var(--muted)] sm:text-base">
                    Siapkan deck, asah strategi, dan buktikan kemampuanmu di arena pertempuran.
                    Arena menanti sang juara.
                </p>

                {{-- Feature Labels --}}
                <div class="mt-6 flex flex-wrap gap-x-5 gap-y-3">
                    <div class="flex items-center gap-2 text-xs text-[var(--muted)]">
                        <svg class="h-4 w-4 text-[var(--cyan)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M12 3 19 6V11C19 16 15.5 19 12 21 8.5 19 5 16 5 11V6L12 3Z"/>
                            <path d="m9 12 2 2 4-4"/>
                        </svg>
                        Strategic Battle
                    </div>
                    <div class="flex items-center gap-2 text-xs text-[var(--muted)]">
                        <svg class="h-4 w-4 text-[var(--cyan)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="m14.5 6.5 3-3 3 3-3 3z"/>
                            <path d="m6.5 14.5-3 3 3 3 3-3z"/>
                            <path d="m15 9-6 6"/>
                            <path d="m5 5 4 4M15 15l4 4"/>
                        </svg>
                        Card Strategy
                    </div>
                    <div class="flex items-center gap-2 text-xs text-[var(--muted)]">
                        <svg class="h-4 w-4 text-[var(--cyan)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M8 21h8M12 17v4"/>
                            <path d="M7 4h10v5a5 5 0 0 1-10 0V4Z"/>
                            <path d="M7 6H4v2a4 4 0 0 0 4 4M17 6h3v2a4 4 0 0 1-4 4"/>
                        </svg>
                        Competitive Arena
                    </div>
                </div>

                {{-- Header Actions --}}
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('play.battle.pve') }}"
                       class="inline-flex items-center justify-center gap-3 rounded-lg bg-[var(--cyan)] px-6 py-4 text-xs font-black uppercase tracking-widest text-[var(--cyan-ink)] shadow-[0_8px_30px_var(--glow)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_12px_35px_var(--cyan)]">
                        Mulai Bertarung
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M7 17 17 7M7 7h10v10"/>
                        </svg>
                    </a>
                    <a href="{{ route('play.decks.index') }}"
                       class="inline-flex items-center justify-center gap-3 rounded-lg border border-[var(--line-strong)] bg-transparent px-6 py-4 text-xs font-black uppercase tracking-widest text-[var(--text)] transition duration-300 hover:border-[var(--cyan)] hover:bg-[var(--panel)]">
                        Jelajahi Deck
                        <svg class="h-4 w-4 text-[var(--cyan)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M7 7h10v10"/><path d="M7 17 17 7"/>
                        </svg>
                    </a>
                </div>

            </div>


            {{-- PLAYER STATUS --}}
            <div class="relative w-full shrink-0 overflow-hidden border border-[var(--line-strong)] bg-[var(--bg-soft)] p-5 sm:p-6 lg:w-[280px]">
                <div class="absolute right-0 top-0 h-24 w-24 rounded-full bg-[var(--glow)] blur-3xl"></div>
                <div class="absolute left-0 top-0 h-px w-full bg-gradient-to-r from-[var(--cyan)] to-transparent"></div>

                <div class="relative z-10 flex items-center gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl border border-[var(--line-strong)] bg-[var(--panel)] text-[var(--cyan)]">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M12 3 19 6V11C19 16 15.5 19 12 21 8.5 19 5 16 5 11V6L12 3Z"/>
                            <path d="m9 12 2 2 4-4"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[9px] font-bold uppercase tracking-[0.25em] text-[var(--faint)]">Player Status</p>
                        <p class="mt-2 text-sm font-black text-[var(--cyan)]">READY TO FIGHT</p>
                    </div>
                </div>

                <div class="relative z-10 mt-6 border-t border-[var(--line-strong)] pt-4">
                    <div class="flex items-center justify-between">
                        <span class="text-[9px] uppercase tracking-widest text-[var(--faint)]">Battle System</span>
                        <span class="flex items-center gap-2 text-[9px] font-bold text-emerald-400">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span> ACTIVE
                        </span>
                    </div>
                    <div class="mt-3 h-1 overflow-hidden rounded-full bg-[var(--line-strong)]">
                        <div class="h-full w-full rounded-full bg-[var(--cyan)]"></div>
                    </div>
                    <p class="mt-3 text-[9px] leading-relaxed text-[var(--faint)]">DIGANOVA // STRATEGY IS EVERYTHING</p>
                </div>
            </div>

        </div>

        {{-- Corner Decorations --}}
        <span class="pointer-events-none absolute bottom-3 left-3 h-5 w-5 border-b border-l border-[var(--cyan)]/60"></span>
        <span class="pointer-events-none absolute right-3 top-3 h-5 w-5 border-r border-t border-[var(--cyan)]/60"></span>
    </section>


    {{-- =========================================================
         MAIN GRID
    ========================================================== --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- LEFT COLUMN: PROFILE + STATS --}}
        <div class="space-y-6 lg:col-span-1">

            {{-- PLAYER PROFILE --}}
            <section class="group relative overflow-hidden rounded-2xl border border-[var(--line-strong)] bg-[var(--panel)] transition duration-300 hover:border-[var(--cyan)]">
                <div class="absolute left-0 top-0 h-px w-full bg-gradient-to-r from-[var(--cyan)] via-[var(--cyan)] to-transparent"></div>
                <div class="absolute -right-16 -top-16 h-48 w-48 rounded-full bg-[var(--glow)] blur-3xl transition group-hover:bg-[var(--cyan)]/20"></div>

                <div class="relative z-10 p-6">
                    <div class="mb-7 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.25em] text-[var(--cyan)]">Player Profile</p>
                            <p class="mt-1 text-xs text-[var(--faint)]">Your battle identity</p>
                        </div>
                        <div class="rounded-lg border border-[var(--line-strong)] bg-[var(--bg-soft)] p-2 text-[var(--cyan)]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <circle cx="12" cy="8" r="4"/>
                                <path d="M5 21V19C5 15.7 8.1 13 12 13S19 15.7 19 19V21"/>
                            </svg>
                        </div>
                    </div>

                    {{-- EQUIPPED SKIN --}}
                    <div class="text-center">
                        <div class="relative mx-auto mb-5 inline-block">
                            <div class="absolute -inset-2 rotate-6 rounded-2xl border border-[var(--line-strong)] transition duration-500 group-hover:rotate-12"></div>
                            <div class="absolute -inset-1 rounded-2xl bg-[var(--cyan)]/20 blur-md"></div>
                            <div class="relative rounded-2xl border border-[var(--line-strong)] bg-[var(--bg-soft)] p-2">
                                <x-navo :skin="$profile->equippedSkin" size="w-28 h-28" textSize="text-6xl" class="rounded-xl" />
                            </div>
                            <div class="absolute -bottom-3 -right-4 rounded-md border border-[var(--line-strong)] bg-[var(--bg-soft)] px-3 py-1 text-[9px] font-black uppercase tracking-wider text-[var(--cyan)]">
                                {{ $profile->equippedSkin->rarity ?? 'Common' }}
                            </div>
                        </div>

                        <h2 class="text-2xl font-black tracking-tight text-[var(--text)]">
                            {{ $profile->equippedSkin->name ?? 'Navo Original' }}
                        </h2>
                        <p class="mt-1 font-mono text-sm text-[var(--cyan)]">
                            {{ '@' . $user->username }}
                        </p>

                        {{-- PLAYER RANK + POINTS --}}
                        <div class="mt-5 flex flex-wrap justify-center gap-2">
                            <span class="inline-flex items-center gap-2 rounded-lg border border-[var(--line-strong)] bg-[var(--panel)] px-3 py-2 text-xs font-black uppercase tracking-wide text-[var(--text)]">
                                <svg class="h-4 w-4 text-[var(--cyan)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2L12 17.3l-5.6 2.9 1.1-6.2L3 9.6l6.2-.9L12 3Z"/>
                                </svg>
                                {{ $user->rank }}
                            </span>
                            <span class="inline-flex items-center gap-2 rounded-lg border border-[var(--line-strong)] bg-[var(--panel)] px-3 py-2 text-xs font-black text-[var(--text)]">
                                <svg class="h-4 w-4 text-[var(--cyan)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 3v18M17 7H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6"/>
                                </svg>
                                {{ $user->total_points }} PTS
                            </span>
                        </div>

                        {{-- PROFILE ACTIONS --}}
                        <div class="mt-6 grid grid-cols-2 gap-3">
                            <a href="{{ route('play.profile.skins') }}" class="flex items-center justify-center gap-2 rounded-lg bg-[var(--cyan)] px-3 py-3 text-[10px] font-black uppercase tracking-wider text-[var(--cyan-ink)] transition hover:-translate-y-0.5 hover:shadow-[0_5px_20px_var(--glow)]">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/>
                                </svg>
                                Ganti Skin
                            </a>
                            <a href="{{ route('play.profile.show') }}" class="flex items-center justify-center gap-2 rounded-lg border border-[var(--line-strong)] bg-transparent px-3 py-3 text-[10px] font-black uppercase tracking-wider text-[var(--text)] transition hover:border-[var(--cyan)] hover:bg-[var(--panel)]">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="8" r="4"/><path d="M5 21v-2a7 7 0 0 1 14 0v2"/>
                                </svg>
                                Profil
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            {{-- COMBAT STATS --}}
            <section class="relative overflow-hidden rounded-2xl border border-[var(--line-strong)] bg-[var(--panel)] p-5">
                <div class="absolute -right-16 -top-16 h-40 w-40 rounded-full bg-[var(--glow)] blur-3xl"></div>

                <div class="relative z-10 mb-5 flex items-center justify-between border-b border-[var(--line-strong)] pb-4">
                    <div>
                        <h3 class="text-xs font-black uppercase tracking-[0.2em] text-[var(--text)]">Combat Stats</h3>
                        <p class="mt-1 text-[10px] text-[var(--faint)]">Your battle performance</p>
                    </div>
                    <svg class="h-5 w-5 text-[var(--cyan)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M3 3v18h18"/><path d="m7 14 4-4 4 4 6-8"/>
                    </svg>
                </div>

                <div class="relative z-10 grid grid-cols-2 gap-3">
                    {{-- TOTAL BATTLES --}}
                    <div class="rounded-xl border border-[var(--line-strong)] bg-[var(--bg-soft)] p-4 transition hover:border-[var(--cyan)]">
                        <p class="text-[9px] font-bold uppercase tracking-wider text-[var(--faint)]">Total Battle</p>
                        <p class="mt-2 text-3xl font-black text-[var(--text)]">{{ $profile->total_battles }}</p>
                        <div class="mt-3 h-1 overflow-hidden rounded-full bg-[var(--line)]">
                            <div class="h-full w-3/4 rounded-full bg-[var(--cyan)]"></div>
                        </div>
                    </div>

                    {{-- WIN RATE --}}
                    <div class="rounded-xl border border-[var(--line-strong)] bg-[var(--bg-soft)] p-4 transition hover:border-[var(--cyan)]">
                        <p class="text-[9px] font-bold uppercase tracking-wider text-[var(--faint)]">Win Rate</p>
                        <p class="mt-2 text-3xl font-black text-[var(--cyan)]">
                            {{ $profile->winRate() }}<span class="text-lg">%</span>
                        </p>
                        <div class="mt-3 h-1 overflow-hidden rounded-full bg-[var(--line)]">
                            <div class="h-full rounded-full bg-[var(--cyan)]" style="width: {{ min(100, max(0, $profile->winRate())) }}%"></div>
                        </div>
                    </div>

                    {{-- WINS --}}
                    <div class="flex items-center gap-3 rounded-xl border border-[var(--line-strong)] bg-[var(--bg-soft)] p-4 transition hover:border-[var(--cyan)]">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-[var(--line-strong)] bg-[var(--panel)] text-[var(--cyan)]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 4 4L19 6"/></svg>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold uppercase tracking-wider text-[var(--faint)]">Wins</p>
                            <p class="text-xl font-black text-[var(--text)]">{{ $profile->wins }}</p>
                        </div>
                    </div>

                    {{-- LOSSES --}}
                    <div class="flex items-center gap-3 rounded-xl border border-[var(--line-strong)] bg-[var(--bg-soft)] p-4 transition hover:border-[var(--cyan)]">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-[var(--line-strong)] bg-[var(--panel)] text-[var(--faint)]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold uppercase tracking-wider text-[var(--faint)]">Losses</p>
                            <p class="text-xl font-black text-[var(--text)]">{{ $profile->losses }}</p>
                        </div>
                    </div>
                </div>
            </section>

        </div>


        {{-- RIGHT COLUMN: BATTLES + DECK + HISTORY --}}
        <div class="space-y-7 lg:col-span-2">

            {{-- QUICK ACTIONS --}}
            <section>
                <div class="mb-5 flex items-end justify-between gap-3">
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-[0.3em] text-[var(--cyan)]">Mission Control // 01</p>
                        <h2 class="mt-2 text-2xl font-black uppercase tracking-tight text-[var(--text)] sm:text-3xl">
                            Choose Your <span class="text-[var(--cyan)]">Battle</span>
                        </h2>
                        <p class="mt-2 text-xs leading-relaxed text-[var(--muted)]">Tentukan mode permainan dan bersiaplah memasuki arena.</p>
                    </div>
                    <div class="hidden items-center gap-2 pb-1 sm:flex">
                        <span class="h-2 w-2 animate-pulse rounded-full bg-[var(--cyan)]"></span>
                        <span class="text-[9px] font-black uppercase tracking-[0.2em] text-[var(--faint)]">Arena Online</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    {{-- BATTLE PVE --}}
                    <a href="{{ route('play.battle.pve') }}" class="group relative isolate block min-h-[300px] overflow-hidden rounded-2xl border border-[var(--line-strong)] bg-[var(--panel)] transition duration-500 hover:-translate-y-1 hover:border-[var(--cyan)] hover:shadow-[0_15px_45px_var(--glow)]">
                        <div class="absolute -right-16 -top-16 -z-10 h-52 w-52 rounded-full bg-[var(--glow)] blur-[80px] transition group-hover:bg-[var(--cyan)]/30"></div>
                        <div class="absolute -right-10 top-12 -z-10 h-40 w-40 rotate-45 border border-[var(--line-strong)] transition duration-700 group-hover:rotate-[60deg] group-hover:border-[var(--cyan)]/30"></div>

                        <div class="relative flex items-center justify-between px-5 pt-5">
                            <span class="inline-flex items-center gap-2 rounded-full border border-[var(--line-strong)] bg-[var(--bg-soft)] px-3 py-2 text-[8px] font-black uppercase tracking-[0.2em] text-[var(--cyan)]">
                                <span class="h-1.5 w-1.5 rounded-full bg-[var(--cyan)]"></span> Mode 01 / Training
                            </span>
                            <span class="font-mono text-[9px] text-[var(--faint)]">PVE_001</span>
                        </div>

                        {{-- PVE ICON --}}
                        <div class="relative mx-5 mt-5 flex h-28 items-center justify-center overflow-hidden rounded-xl border border-[var(--line-strong)] bg-[var(--bg-soft)]">
                            <div class="relative flex h-[72px] w-[72px] items-center justify-center rounded-xl border border-[var(--line-strong)] bg-[var(--panel)] text-[var(--cyan)] shadow-[0_0_30px_var(--glow)] transition duration-500 group-hover:scale-110 group-hover:shadow-[0_0_35px_var(--cyan)]">
                                <svg class="h-9 w-9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m14.5 6.5 3-3 3 3-3 3z"/><path d="m6.5 14.5-3 3 3 3 3-3z"/><path d="m15 9-6 6"/><path d="m5 5 4 4M15 15l4 4"/><path d="M12 2v3M2 12h3M19 12h3"/>
                                </svg>
                            </div>
                            <span class="absolute bottom-3 right-3 text-[8px] font-black uppercase tracking-[0.2em] text-[var(--faint)]">AI COMBAT</span>
                        </div>

                        <div class="px-5 pb-5 pt-5">
                            <p class="text-[9px] font-black uppercase tracking-[0.25em] text-[var(--cyan)]">Single Player // AI</p>
                            <h3 class="mt-2 text-2xl font-black uppercase italic tracking-tight text-[var(--text)]">Battle <span class="text-[var(--cyan)]">PvE</span></h3>
                            <p class="mt-2 text-xs leading-relaxed text-[var(--muted)]">Latih strategi, uji kekuatan kartu, dan taklukkan lawan AI.</p>
                            <div class="mt-5 flex items-center justify-between border-t border-[var(--line-strong)] pt-4">
                                <span class="text-[9px] font-black uppercase tracking-[0.15em] text-[var(--text)] transition group-hover:text-[var(--cyan)]">Enter Training Arena</span>
                                <span class="flex h-9 w-10 items-center justify-center rounded-lg border border-[var(--line-strong)] bg-[var(--bg-soft)] text-lg text-[var(--cyan)] transition group-hover:bg-[var(--cyan)] group-hover:text-[var(--cyan-ink)]">→</span>
                            </div>
                        </div>
                        <span class="absolute bottom-0 left-0 h-6 w-6 border-b border-l border-[var(--cyan)]/60"></span>
                        <span class="absolute right-0 top-0 h-6 w-6 border-r border-t border-[var(--cyan)]/60"></span>
                    </a>

                    {{-- BATTLE PVP --}}
                    <a href="{{ route('play.battle.pvp') }}" class="group relative isolate block min-h-[300px] overflow-hidden rounded-2xl border border-[var(--line-strong)] bg-[var(--panel)] transition duration-500 hover:-translate-y-1 hover:border-[var(--cyan)] hover:shadow-[0_15px_45px_var(--glow)]">
                        <div class="absolute -bottom-16 -right-12 -z-10 h-52 w-52 rounded-full bg-[var(--glow)] blur-[80px] transition group-hover:bg-[var(--cyan)]/20"></div>
                        <div class="absolute -right-10 top-12 -z-10 h-40 w-40 rotate-45 border border-[var(--line-strong)] transition duration-700 group-hover:rotate-[65deg] group-hover:border-[var(--cyan)]/30"></div>

                        <div class="relative flex items-center justify-between px-5 pt-5">
                            <span class="inline-flex items-center gap-2 rounded-full border border-[var(--line-strong)] bg-[var(--bg-soft)] px-3 py-2 text-[8px] font-black uppercase tracking-[0.2em] text-[var(--cyan)]">
                                <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-[var(--cyan)]"></span> Mode 02 / Competitive
                            </span>
                            <span class="font-mono text-[9px] text-[var(--faint)]">PVP_002</span>
                        </div>

                        {{-- PVP ICON --}}
                        <div class="relative mx-5 mt-5 flex h-28 items-center justify-center overflow-hidden rounded-xl border border-[var(--line-strong)] bg-[var(--bg-soft)]">
                            <div class="relative flex h-[72px] w-[72px] items-center justify-center rounded-xl border border-[var(--line-strong)] bg-[var(--panel)] text-[var(--cyan)] shadow-[0_0_30px_var(--glow)] transition duration-500 group-hover:scale-110 group-hover:shadow-[0_0_35px_var(--cyan)]">
                                <svg class="h-9 w-9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M8 21h8M12 17v4"/><path d="M7 4h10v5a5 5 0 0 1-10 0V4Z"/><path d="M7 6H4v2a4 4 0 0 0 4 4M17 6h3v2a4 4 0 0 1-4 4"/><path d="m9 9 2 2 4-4"/>
                                </svg>
                            </div>
                            <span class="absolute bottom-3 right-3 text-[8px] font-black uppercase tracking-[0.2em] text-[var(--faint)]">PLAYER VS PLAYER</span>
                        </div>

                        <div class="px-5 pb-5 pt-5">
                            <p class="text-[9px] font-black uppercase tracking-[0.25em] text-[var(--cyan)]">Multiplayer // Ranked</p>
                            <h3 class="mt-2 text-2xl font-black uppercase italic tracking-tight text-[var(--text)]">Battle <span class="text-[var(--cyan)]">PvP</span></h3>
                            <p class="mt-2 text-xs leading-relaxed text-[var(--muted)]">Tantang pemain lain, buktikan kemampuanmu, dan kejar posisi leaderboard.</p>
                            <div class="mt-5 flex items-center justify-between border-t border-[var(--line-strong)] pt-4">
                                <span class="text-[9px] font-black uppercase tracking-[0.15em] text-[var(--text)] transition group-hover:text-[var(--cyan)]">Enter Battle Arena</span>
                                <span class="flex h-9 w-10 items-center justify-center rounded-lg border border-[var(--line-strong)] bg-[var(--bg-soft)] text-lg text-[var(--cyan)] transition group-hover:bg-[var(--cyan)] group-hover:text-[var(--cyan-ink)]">→</span>
                            </div>
                        </div>
                        <span class="absolute bottom-0 left-0 h-6 w-6 border-b border-l border-[var(--cyan)]/60"></span>
                        <span class="absolute right-0 top-0 h-6 w-6 border-r border-t border-[var(--cyan)]/60"></span>
                    </a>
                </div>
            </section>


            {{-- ACTIVE DECK --}}
            <section class="relative overflow-hidden rounded-2xl border border-[var(--line-strong)] bg-[var(--panel)]">
                <div class="relative flex flex-col gap-4 overflow-hidden border-b border-[var(--line-strong)] bg-[var(--bg-soft)] p-5 sm:flex-row sm:items-center sm:justify-between">
                    <div class="relative flex items-center gap-3">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border border-[var(--line-strong)] bg-[var(--panel)] text-[var(--cyan)]">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                <rect x="7" y="3" width="13" height="17" rx="2" transform="rotate(5 7 3)"/>
                                <rect x="4" y="5" width="13" height="17" rx="2" transform="rotate(-8 4 5)"/>
                                <path d="m10 10 2 2 3-3"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black uppercase tracking-tight text-[var(--text)]">Deck Aktif</h3>
                            <p class="mt-1 text-[9px] font-bold uppercase tracking-[0.2em] text-[var(--cyan)]">Battle Loadout // Ready</p>
                        </div>
                    </div>
                    <a href="{{ route('play.decks.index') }}" class="relative inline-flex items-center justify-center gap-2 self-start rounded-lg border border-[var(--line-strong)] bg-[var(--bg-soft)] px-4 py-3 text-[9px] font-black uppercase tracking-widest text-[var(--text)] transition hover:border-[var(--cyan)] sm:self-auto">
                        Kelola Deck <span>↗</span>
                    </a>
                </div>

                <div class="p-5">
                    @if ($activeDeck && $activeDeck->cards->isNotEmpty())
                        {{-- Deck Information --}}
                        <div class="mb-6 flex items-center justify-between gap-3">
                            <div>
                                <p class="text-[9px] font-black uppercase tracking-[0.2em] text-[var(--faint)]">Deployed Cards</p>
                                <p class="mt-1 text-xs text-[var(--faint)]">Pilih strategi terbaik untuk memenangkan battle.</p>
                            </div>
                            <div class="shrink-0 rounded-lg border border-[var(--line-strong)] bg-[var(--bg-soft)] px-3 py-2 font-mono text-xs font-bold text-[var(--cyan)]">
                                {{ $activeDeck->cards->sum('pivot.quantity') }}<span class="text-[var(--faint)]">/10</span>
                            </div>
                        </div>

                        {{-- Cards Grid --}}
                        <div class="grid grid-cols-2 gap-x-3 gap-y-5 sm:grid-cols-3 xl:grid-cols-5">
                            @foreach ($activeDeck->cards->take(10) as $card)
                                <div class="group/card relative mx-auto w-full max-w-[190px] transition duration-300 hover:-translate-y-2">
                                    <div class="absolute -inset-1 rounded-2xl bg-[var(--cyan)]/0 opacity-0 blur-md transition duration-500 group-hover/card:bg-[var(--cyan)]/30 group-hover/card:opacity-100"></div>
                                    <div class="relative overflow-hidden rounded-2xl border border-[var(--line-strong)] bg-[var(--panel)] p-[1px] shadow-[0_8px_20px_rgba(0,0,0,0.4)] transition duration-300 group-hover/card:border-[var(--cyan)]">
                                        <div class="relative overflow-hidden rounded-[15px] bg-[var(--bg-soft)]">
                                            <div class="pointer-events-none absolute inset-0 opacity-[0.12]"
                                                 style="background-image:
                                                    linear-gradient(var(--grid) 1px, transparent 1px),
                                                    linear-gradient(90deg, var(--grid) 1px, transparent 1px);
                                                    background-size: 18px 18px;">
                                            </div>
                                            <div class="pointer-events-none absolute -right-10 top-8 h-24 w-24 rounded-full bg-[var(--glow)] blur-2xl transition group-hover/card:bg-[var(--cyan)]/30"></div>

                                            {{-- Card Top --}}
                                            <div class="relative z-10 flex items-center justify-between border-b border-[var(--line-strong)] px-3 py-3">
                                                <span class="text-[8px] font-black uppercase tracking-[0.15em] text-[var(--faint)]">NAVO // CARD</span>
                                                <div class="flex h-7 min-w-7 items-center justify-center rounded-lg border border-[var(--line-strong)] bg-[var(--panel)] px-2 text-xs font-black text-[var(--text)]">
                                                    {{ $card->energy_cost }}
                                                </div>
                                            </div>

                                            {{-- Card Art --}}
                                            <div class="relative z-10 px-3 pt-4">
                                                <div class="relative flex aspect-square items-center justify-center overflow-hidden rounded-xl border border-[var(--line-strong)] bg-[var(--panel)]">
                                                    <div class="absolute inset-2 rounded-lg border border-[var(--line-strong)]"></div>
                                                    @if ($card->type === 'attack')
                                                        <svg class="relative z-10 h-14 w-14 text-[var(--cyan)] drop-shadow-[0_0_12px_var(--glow)] transition duration-300 group-hover/card:scale-110 group-hover/card:rotate-[-8deg]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="m14.5 6.5 3-3 3 3-3 3z"/><path d="m6.5 14.5-3 3 3 3 3-3z"/><path d="m15 9-6 6"/><path d="m5 5 4 4M15 15l4 4"/><path d="M12 2v3M2 12h3M19 12h3M12 19v3"/>
                                                        </svg>
                                                    @elseif ($card->type === 'defense')
                                                        <svg class="relative z-10 h-14 w-14 text-[var(--cyan)] drop-shadow-[0_0_12px_var(--glow)] transition duration-300 group-hover/card:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round">
                                                            <path d="M12 2 20 5v6c0 5-3.5 8.5-8 11-4.5-2.5-8-6-8-11V5l8-3Z"/><path d="m8.5 12 2.3 2.3 4.7-5"/>
                                                        </svg>
                                                    @else
                                                        <svg class="relative z-10 h-14 w-14 text-[var(--cyan)] drop-shadow-[0_0_12px_var(--glow)] transition duration-300 group-hover/card:scale-110 group-hover/card:rotate-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round">
                                                            <path d="m12 2 2.5 7.5L22 12l-7.5 2.5L12 22l-2.5-7.5L2 12l7.5-2.5L12 2Z"/>
                                                        </svg>
                                                    @endif
                                                    <span class="absolute left-2 top-2 h-3 w-3 border-l border-t border-[var(--cyan)]/70"></span>
                                                    <span class="absolute bottom-2 right-2 h-3 w-3 border-b border-r border-[var(--cyan)]/70"></span>
                                                </div>
                                            </div>

                                            {{-- Card Name --}}
                                            <div class="relative z-10 px-3 pb-4 pt-4 text-center">
                                                <h4 class="line-clamp-2 min-h-9 text-xs font-black leading-tight text-[var(--text)] transition group-hover/card:text-[var(--cyan)]">
                                                    {{ $card->name }}
                                                </h4>
                                                <div class="mx-auto my-3 h-px w-2/3 bg-gradient-to-r from-transparent via-[var(--cyan)]/60 to-transparent"></div>
                                                <span class="inline-flex items-center rounded-md border border-[var(--line-strong)] bg-[var(--bg-soft)] px-2.5 py-1 text-[8px] font-black uppercase tracking-[0.15em] text-[var(--cyan)]">
                                                    {{ $card->type }}
                                                </span>
                                            </div>

                                            {{-- Card Footer --}}
                                            <div class="relative z-10 flex items-center justify-between border-t border-[var(--line-strong)] bg-[var(--panel)] px-3 py-2">
                                                <span class="text-[8px] font-bold uppercase tracking-widest text-[var(--faint)]">DIGANOVA</span>
                                                <span class="h-1.5 w-1.5 rounded-full bg-[var(--cyan)] shadow-[0_0_8px_var(--cyan)]"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Deck Ready Status --}}
                        <div class="mt-6 flex flex-col gap-3 rounded-xl border border-[var(--line-strong)] bg-[var(--bg-soft)] p-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-center gap-3">
                                <span class="relative flex h-2.5 w-2.5 shrink-0">
                                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[var(--cyan)] opacity-50"></span>
                                    <span class="relative h-2.5 w-2.5 rounded-full bg-[var(--cyan)]"></span>
                                </span>
                                <div>
                                    <p class="text-xs font-black uppercase tracking-wider text-[var(--text)]">Deck Siap Tempur</p>
                                    <p class="mt-1 text-[10px] text-[var(--faint)]">Loadout tersimpan dan siap digunakan.</p>
                                </div>
                            </div>
                            <div class="text-xs font-bold text-[var(--muted)]">
                                Total kartu: <span class="text-[var(--cyan)]">{{ $activeDeck->cards->sum('pivot.quantity') }}</span> / 10
                            </div>
                        </div>
                    @else
                        {{-- EMPTY DECK --}}
                        <div class="relative overflow-hidden rounded-xl border border-dashed border-[var(--line-strong)] bg-[var(--bg-soft)] px-4 py-12 text-center">
                            <div class="absolute inset-0 opacity-[0.10]"
                                 style="background-image:
                                    linear-gradient(var(--grid) 1px, transparent 1px),
                                    linear-gradient(90deg, var(--grid) 1px, transparent 1px);
                                    background-size: 25px 25px;">
                            </div>
                            <div class="relative z-10 mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl border border-[var(--line-strong)] bg-[var(--panel)] text-[var(--cyan)]">
                                <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <rect x="5" y="3" width="14" height="18" rx="2"/><path d="M9 8h6M9 12h6M9 16h3"/>
                                </svg>
                            </div>
                            <h4 class="relative z-10 text-sm font-black uppercase tracking-wider text-[var(--text)]">Belum Ada Deck</h4>
                            <p class="relative z-10 mt-2 text-xs leading-relaxed text-[var(--muted)]">Susun deck pertamamu untuk bersiap memasuki arena.</p>
                            <a href="{{ route('play.decks.index') }}" class="relative z-10 mt-6 inline-flex items-center gap-2 rounded-lg bg-[var(--cyan)] px-5 py-3 text-[10px] font-black uppercase tracking-widest text-[var(--cyan-ink)] transition hover:-translate-y-0.5 hover:shadow-[0_0_25px_var(--glow)]">
                                + Buat Deck Sekarang
                            </a>
                        </div>
                    @endif
                </div>
            </section>


            {{-- RECENT BATTLES --}}
            <section class="relative overflow-hidden rounded-2xl border border-[var(--line-strong)] bg-[var(--panel)]">
                <div class="relative flex items-center justify-between gap-3 overflow-hidden border-b border-[var(--line-strong)] bg-[var(--bg-soft)] p-5">
                    <div class="relative flex min-w-0 items-center gap-3">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-[var(--line-strong)] bg-[var(--panel)] text-[var(--cyan)]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                <path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5M12 7v5l3 2"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h3 class="truncate text-sm font-black uppercase tracking-wider text-[var(--text)]">Riwayat Pertempuran</h3>
                            <p class="mt-1 text-[10px] text-[var(--faint)]">Your recent matches</p>
                        </div>
                    </div>
                    <a href="{{ route('play.leaderboard') }}" class="relative shrink-0 text-[9px] font-black uppercase tracking-wider text-[var(--cyan)] transition hover:text-[var(--text)] sm:text-[10px]">
                        Leaderboard →
                    </a>
                </div>

                <div class="p-5">
                    @if ($recentBattles->isEmpty())
                        <div class="py-10 text-center">
                            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full border border-[var(--line-strong)] bg-[var(--panel)] text-[var(--cyan)]">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <path d="M12 8v4l3 3"/><circle cx="12" cy="12" r="9"/>
                                </svg>
                            </div>
                            <p class="text-xs font-bold text-[var(--muted)]">Belum ada riwayat pertempuran.</p>
                            <p class="mt-2 text-[10px] text-[var(--faint)]">Mulai battle untuk mencatat pertandingan pertamamu.</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach ($recentBattles as $battle)
                                @php
                                    $isWin = $battle->winner_id === $user->id;
                                    $opp = $battle->player1_id === $user->id ? $battle->player2 : $battle->player1;
                                @endphp
                                <div class="group flex items-center gap-3 rounded-xl border border-[var(--line-strong)] bg-[var(--bg-soft)] p-3 transition duration-300 hover:border-[var(--cyan)] sm:gap-4 sm:p-4">
                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border {{ $isWin ? 'border-[var(--cyan)] bg-[var(--cyan)]/10 text-[var(--cyan)]' : 'border-[var(--line-strong)] bg-[var(--panel)] text-[var(--faint)]' }} text-sm font-black">
                                        {{ $isWin ? 'W' : 'L' }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-xs font-bold text-[var(--text)]">
                                            VS <strong class="text-[var(--cyan)]">{{ $opp->name ?? 'AI' }}</strong>
                                        </p>
                                        <p class="mt-1 text-[10px] text-[var(--faint)]">{{ $battle->created_at->diffForHumans() }}</p>
                                    </div>
                                    <div class="shrink-0 text-right">
                                        <p class="text-sm font-black {{ $isWin ? 'text-[var(--cyan)]' : 'text-[var(--muted)]' }}">
                                            {{ $isWin ? '+50' : '-20' }}
                                        </p>
                                        <p class="mt-1 text-[8px] font-bold uppercase tracking-widest text-[var(--faint)]">Points</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>

        </div>
    </div>


    {{-- FOOTER DECORATION --}}
    <div class="flex flex-col items-center justify-between gap-3 border-t border-[var(--line-strong)] py-5 sm:flex-row">
        <p class="text-[9px] font-bold uppercase tracking-[0.25em] text-[var(--faint)]">DIGANOVA // CYBER LOBBY</p>
        <div class="flex items-center gap-2 text-[9px] uppercase tracking-widest text-[var(--faint)]">
            <span class="h-1.5 w-1.5 rounded-full bg-[var(--cyan)]"></span> Battle System Online
        </div>
    </div>

</div>

@endsection