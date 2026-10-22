
@extends('playground.layouts.app')

@section('title', 'Cyber Lobby — DIGANOVA Playground')

@section('content')

<div class="relative space-y-6 text-white">

    {{-- BACKGROUND DECORATION --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-[#4647AE]/10 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-[#4382DF]/10 rounded-full blur-[130px]"></div>
    </div>

{{-- HEADER CYBER LOBBY --}}
<div class="group relative isolate overflow-hidden border border-[#4382DF]/50 bg-[#090E39] transition duration-500 hover:border-[#4382DF]/80"
     style="clip-path: polygon(0 0, 96% 0, 100% 22%, 100% 78%, 96% 100%, 4% 100%, 0 78%, 0 22%);">

    {{-- Background futuristik --}}
    <div class="absolute inset-0 -z-20 bg-gradient-to-r from-[#112E81] via-[#090E39] to-[#090E39]"></div>

    {{-- Grid background --}}
    <div class="absolute inset-0 -z-10 opacity-[0.09]"
         style="background-image: linear-gradient(#4382DF 1px, transparent 1px), linear-gradient(90deg, #4382DF 1px, transparent 1px); background-size: 25px 25px;"></div>

    {{-- Glow --}}
    <div class="absolute -left-16 -top-20 -z-10 h-64 w-64 rounded-full bg-[#4382DF]/20 blur-[90px]"></div>
    <div class="absolute -right-10 bottom-0 -z-10 h-48 w-48 rounded-full bg-[#4647AE]/25 blur-[80px]"></div>

    {{-- Garis dekoratif --}}
    <div class="pointer-events-none absolute left-0 top-0 h-1 w-2/3 bg-gradient-to-r from-[#4382DF] via-[#4382DF]/60 to-transparent"></div>
    <div class="pointer-events-none absolute bottom-0 right-0 h-1 w-1/3 bg-gradient-to-l from-[#4647AE] to-transparent"></div>

    {{-- Isi header --}}
    <div class="relative z-10 flex flex-col gap-6 p-6 sm:p-8 md:flex-row md:items-center md:justify-between">

        <div class="min-w-0">
            {{-- System Online --}}
            <div class="mb-4 inline-flex items-center gap-2 border border-[#4382DF]/50 bg-[#090E39]/80 px-4 py-2"
                 style="clip-path: polygon(0 0, 94% 0, 100% 35%, 100% 100%, 0 100%);">
                <span class="h-2 w-2 animate-pulse rounded-full bg-[#4382DF] shadow-[0_0_10px_#4382DF]"></span>
                <span class="text-[10px] font-black uppercase tracking-[0.25em] text-[#4382DF]">
                    System Online
                </span>
            </div>

            {{-- Title --}}
            <h1 class="text-3xl font-black uppercase italic tracking-tight text-white sm:text-4xl md:text-5xl">
                Cyber <span class="text-[#4382DF] drop-shadow-[0_0_15px_rgba(67,130,223,0.4)]">Lobby</span>
            </h1>

            <p class="mt-3 max-w-xl text-sm leading-relaxed text-slate-400">
                Siapkan deck, asah strategi, dan buktikan kemampuanmu di arena pertempuran.
            </p>

            {{-- Dekorasi status --}}
            <div class="mt-5 flex items-center gap-2">
                <span class="h-px w-10 bg-[#4382DF]/70"></span>
                <span class="text-[9px] font-bold uppercase tracking-[0.25em] text-slate-500">
                    DIGANOVA // Battle System
                </span>
            </div>
        </div>

        {{-- Player Status --}}
        <div class="relative flex shrink-0 items-center gap-4 border border-[#4382DF]/50 bg-black/40 p-4 sm:min-w-[240px]"
             style="clip-path: polygon(0 0, 90% 0, 100% 22%, 100% 100%, 10% 100%, 0 78%);">

            <div class="flex h-12 w-12 shrink-0 items-center justify-center border border-[#4382DF]/50 bg-[#112E81]/70 text-[#4382DF]"
                 style="clip-path: polygon(25% 0, 75% 0, 100% 25%, 100% 75%, 75% 100%, 25% 100%, 0 75%, 0 25%);">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 3 19 6V11C19 16 15.5 19 12 21 8.5 19 5 16 5 11V6L12 3Z"/>
                    <path d="m9 12 2 2 4-4"/>
                </svg>
            </div>

            <div>
                <p class="text-[9px] font-bold uppercase tracking-[0.2em] text-slate-500">
                    Player Status
                </p>
                <p class="mt-1 whitespace-nowrap text-sm font-black text-[#4382DF]">
                    READY TO FIGHT
                </p>
                <div class="mt-2 h-1 w-full overflow-hidden bg-[#112E81]">
                    <div class="h-full w-full bg-[#4382DF] shadow-[0_0_8px_#4382DF]"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Aksen sudut --}}
    <span class="pointer-events-none absolute bottom-3 left-3 h-4 w-4 border-b-2 border-l-2 border-[#4382DF]/70"></span>
    <span class="pointer-events-none absolute right-3 top-3 h-4 w-4 border-r-2 border-t-2 border-[#4382DF]/70"></span>
</div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- KIRI: PROFIL & STATS --}}
        <div class="space-y-6 lg:col-span-1">

            {{-- PROFILE CARD --}}
            <div class="group relative overflow-hidden rounded-2xl border border-[#4382DF]/30 bg-[#090E39] transition duration-300 hover:border-[#4382DF]/70">
                <div class="absolute left-0 top-0 h-1 w-full bg-gradient-to-r from-[#4647AE] via-[#4382DF] to-transparent"></div>
                <div class="absolute -right-16 -top-16 h-48 w-48 rounded-full bg-[#4647AE]/20 blur-3xl transition duration-500 group-hover:bg-[#4382DF]/20"></div>

                <div class="relative z-10 p-6">
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.25em] text-[#4382DF]">Player Profile</p>
                            <p class="mt-1 text-xs text-slate-500">Your battle identity</p>
                        </div>
                        <div class="rounded-lg border border-[#4382DF]/30 bg-[#112E81]/40 p-2 text-[#4382DF]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <circle cx="12" cy="8" r="4"/>
                                <path d="M5 21V19C5 15.7 8.1 13 12 13S19 15.7 19 19V21"/>
                            </svg>
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="relative mx-auto mb-5 inline-block">
                            <div class="absolute -inset-2 rounded-2xl border border-[#4382DF]/30 rotate-6 transition-transform duration-300 group-hover:rotate-12"></div>
                            <div class="absolute -inset-1 rounded-2xl bg-[#4647AE]/20 blur-md"></div>

                            <div class="relative rounded-2xl border border-[#4382DF]/50 bg-[#112E81]/40 p-1">
                                <x-navo :skin="$profile->equippedSkin" size="w-28 h-28" textSize="text-6xl" class="rounded-xl" />
                            </div>

                            <div class="absolute -bottom-3 -right-4 rounded-md border border-[#4382DF]/60 bg-[#090E39] px-2.5 py-1 text-[9px] font-black uppercase tracking-wider text-[#4382DF] shadow-lg">
                                {{ $profile->equippedSkin->rarity ?? 'Common' }}
                            </div>
                        </div>

                        <h2 class="text-2xl font-black tracking-tight text-white">
                            {{ $profile->equippedSkin->name ?? 'Navo Original' }}
                        </h2>
                        <p class="mt-1 font-mono text-sm text-[#4382DF]">{{ '@' . $user->username }}</p>

                        <div class="mt-5 flex flex-wrap justify-center gap-2">
                            <span class="inline-flex items-center gap-2 rounded-lg border border-[#4382DF]/30 bg-[#112E81]/50 px-3 py-2 text-xs font-black uppercase tracking-wide text-white">
                                <svg class="h-4 w-4 text-[#4382DF]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2L12 17.3l-5.6 2.9 1.1-6.2L3 9.6l6.2-.9L12 3Z"/>
                                </svg>
                                {{ $user->rank }}
                            </span>
                            <span class="inline-flex items-center gap-2 rounded-lg border border-[#4647AE]/60 bg-[#4647AE]/20 px-3 py-2 text-xs font-black text-white">
                                <svg class="h-4 w-4 text-[#4382DF]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 3v18M17 7H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6"/>
                                </svg>
                                {{ $user->total_points }} PTS
                            </span>
                        </div>

                        <div class="mt-6 grid grid-cols-2 gap-3">
                            <a href="{{ route('play.profile.skins') }}" class="flex items-center justify-center gap-2 rounded-lg bg-[#4647AE] px-3 py-3 text-xs font-black uppercase tracking-wider text-white transition hover:bg-[#4382DF]">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/>
                                </svg>
                                Ganti Skin
                            </a>
                            <a href="{{ route('play.profile.show') }}" class="flex items-center justify-center gap-2 rounded-lg border border-[#4382DF]/40 bg-[#112E81]/30 px-3 py-3 text-xs font-black uppercase tracking-wider text-slate-200 transition hover:bg-[#112E81]/70">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="8" r="4"/><path d="M5 21v-2a7 7 0 0 1 14 0v2"/>
                                </svg>
                                Profil
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- COMBAT STATS --}}
            <div class="rounded-2xl border border-[#4382DF]/25 bg-[#090E39] p-5">
                <div class="mb-5 flex items-center justify-between border-b border-[#4382DF]/20 pb-3">
                    <div>
                        <h3 class="text-xs font-black uppercase tracking-[0.2em] text-white">Combat Stats</h3>
                        <p class="mt-1 text-[10px] text-slate-500">Your battle performance</p>
                    </div>
                    <svg class="h-5 w-5 text-[#4382DF]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M3 3v18h18"/><path d="m7 14 4-4 4 4 6-8"/>
                    </svg>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="rounded-xl border border-[#4382DF]/20 bg-[#112E81]/30 p-4 transition hover:border-[#4382DF]/50">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Battle</p>
                        <p class="mt-2 text-3xl font-black text-white">{{ $profile->total_battles }}</p>
                        <div class="mt-3 h-1 rounded-full bg-black/50">
                            <div class="h-1 w-3/4 rounded-full bg-[#4382DF]"></div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-[#4382DF]/20 bg-[#112E81]/30 p-4 transition hover:border-[#4382DF]/50">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Win Rate</p>
                        <p class="mt-2 text-3xl font-black text-[#4382DF]">{{ $profile->winRate() }}<span class="text-lg">%</span></p>
                        <div class="mt-3 h-1 rounded-full bg-black/50">
                            <div class="h-1 rounded-full bg-[#4382DF]" style="width: {{ min(100, max(0, $profile->winRate())) }}%"></div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 rounded-xl border border-[#4382DF]/20 bg-black/20 p-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#4382DF]/15 text-[#4382DF]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m5 12 4 4L19 6"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Wins</p>
                            <p class="text-xl font-black text-white">{{ $profile->wins }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 rounded-xl border border-[#4382DF]/20 bg-black/20 p-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#4647AE]/25 text-[#4382DF]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 6 6 18M6 6l12 12"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Losses</p>
                            <p class="text-xl font-black text-white">{{ $profile->losses }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KANAN: ACTIONS, DECK, BATTLE HISTORY --}}
        <div class="space-y-6 lg:col-span-2">

{{-- QUICK ACTIONS --}}
<div>
    <div class="mb-5 flex items-end justify-between gap-3">
        <div>
            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-[#4382DF]">
                Mission Control // 01
            </p>
            <h2 class="mt-2 text-2xl font-black uppercase tracking-tight text-white sm:text-3xl">
                Choose Your <span class="text-[#4382DF]">Battle</span>
            </h2>
            <p class="mt-2 text-xs text-slate-400">
                Tentukan mode permainan dan bersiaplah memasuki arena.
            </p>
        </div>

        <div class="hidden items-center gap-2 pb-1 sm:flex">
            <span class="h-2 w-2 animate-pulse rounded-full bg-[#4382DF]"></span>
            <span class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-500">
                Arena Online
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

        {{-- BATTLE PVE --}}
        <a href="{{ route('play.battle.pve') }}"
           class="group relative isolate block min-h-[290px] overflow-hidden rounded-[1.6rem] border border-[#4382DF]/40 bg-[#090E39] transition duration-500 hover:-translate-y-2 hover:border-[#4382DF] hover:shadow-[0_15px_45px_rgba(67,130,223,0.2)]">

            {{-- Background --}}
            <div class="absolute inset-0 -z-20 bg-gradient-to-br from-[#112E81] via-[#090E39] to-black"></div>

            {{-- Grid --}}
            <div class="absolute inset-0 -z-10 opacity-[0.12]"
                 style="background-image: linear-gradient(135deg, #4382DF 1px, transparent 1px), linear-gradient(45deg, #4382DF 1px, transparent 1px); background-size: 24px 24px;"></div>

            {{-- Ambient glow --}}
            <div class="absolute -right-16 -top-16 -z-10 h-56 w-56 rounded-full bg-[#4382DF]/20 blur-[70px] transition duration-500 group-hover:bg-[#4382DF]/40"></div>

            {{-- Decorative rings --}}
            <div class="pointer-events-none absolute -right-12 top-8 h-48 w-48 rotate-45 border border-[#4382DF]/20 transition duration-700 group-hover:rotate-[65deg] group-hover:scale-110"></div>
            <div class="pointer-events-none absolute -right-6 top-14 h-36 w-36 rotate-45 border border-[#4382DF]/20 transition duration-700 group-hover:-rotate-[-25deg]"></div>

            {{-- Top label --}}
            <div class="relative flex items-center justify-between px-5 pt-5 sm:px-6">
                <span class="inline-flex items-center gap-2 border border-[#4382DF]/40 bg-black/30 px-3 py-1.5 text-[9px] font-black uppercase tracking-[0.2em] text-[#4382DF]"
                      style="clip-path: polygon(0 0, 100% 0, 100% 70%, 88% 100%, 0 100%);">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#4382DF]"></span>
                    Mode 01 / Training
                </span>

                <span class="font-mono text-[10px] font-bold tracking-widest text-[#4382DF]/60">
                    PVE_001
                </span>
            </div>

            {{-- Icon stage --}}
            <div class="relative mx-5 mt-5 flex h-28 items-center justify-center overflow-hidden border border-[#4382DF]/20 bg-gradient-to-r from-[#4382DF]/10 via-[#112E81]/30 to-transparent sm:mx-6"
                 style="clip-path: polygon(0 0, 90% 0, 100% 22%, 100% 100%, 10% 100%, 0 78%);">

                <div class="absolute inset-0 bg-gradient-to-r from-[#4382DF]/10 to-transparent opacity-50"></div>

                {{-- Hexagonal icon --}}
                <div class="relative flex h-[76px] w-[76px] items-center justify-center border border-[#4382DF]/60 bg-[#090E39]/90 text-[#4382DF] shadow-[0_0_30px_rgba(67,130,223,0.15)] transition duration-500 group-hover:scale-110 group-hover:shadow-[0_0_40px_rgba(67,130,223,0.35)]"
                     style="clip-path: polygon(8% 0, 92% 0, 100% 8%, 100% 68%, 92% 80%, 50% 100%, 8% 80%, 0% 68%, 0% 8%);">

                    <svg class="h-10 w-10 transition duration-500 group-hover:rotate-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m14.5 6.5 3-3 3 3-3 3z"/>
                        <path d="m6.5 14.5-3 3 3 3 3-3z"/>
                        <path d="m15 9-6 6"/>
                        <path d="m5 5 4 4M15 15l4 4"/>
                        <path d="M12 2v3M2 12h3M19 12h3"/>
                    </svg>
                </div>

                <div class="absolute bottom-3 right-4 text-[8px] font-black uppercase tracking-[0.2em] text-[#4382DF]/50">
                    AI COMBAT
                </div>
            </div>

            {{-- Content --}}
            <div class="relative px-5 pb-5 pt-5 sm:px-6 sm:pb-6">
                <p class="text-[9px] font-black uppercase tracking-[0.3em] text-[#4382DF]">
                    Single Player // AI
                </p>

                <h3 class="mt-1 text-2xl font-black uppercase italic tracking-tight text-white sm:text-3xl">
                    Battle <span class="text-[#4382DF]">PvE</span>
                </h3>

                <p class="mt-2 max-w-xs text-xs leading-relaxed text-slate-400">
                    Latih strategi, uji kekuatan kartu, dan taklukkan lawan AI.
                </p>

                <div class="mt-5 flex items-center justify-between border-t border-[#4382DF]/20 pt-4">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-white transition group-hover:text-[#4382DF]">
                        Enter Training Arena
                    </span>

                    <span class="flex h-9 w-12 items-center justify-center border border-[#4382DF]/50 bg-[#4382DF]/10 text-lg text-[#4382DF] transition duration-300 group-hover:w-14 group-hover:bg-[#4382DF] group-hover:text-white"
                          style="clip-path: polygon(0 0, 78% 0, 100% 50%, 78% 100%, 0 100%);">
                        →
                    </span>
                </div>
            </div>

            {{-- Corner accents --}}
            <span class="pointer-events-none absolute bottom-0 left-0 h-8 w-8 border-b-2 border-l-2 border-[#4382DF]/70"></span>
            <span class="pointer-events-none absolute right-0 top-0 h-8 w-8 border-r-2 border-t-2 border-[#4382DF]/70"></span>
        </a>


        {{-- BATTLE PVP --}}
        <a href="{{ route('play.battle.pvp') }}"
           class="group relative isolate block min-h-[290px] overflow-hidden rounded-[1.6rem] border border-[#4647AE]/70 bg-[#090E39] transition duration-500 hover:-translate-y-2 hover:border-[#4382DF] hover:shadow-[0_15px_45px_rgba(70,71,174,0.3)]">

            {{-- Background --}}
            <div class="absolute inset-0 -z-20 bg-gradient-to-br from-[#4647AE]/50 via-[#090E39] to-black"></div>

            {{-- Grid --}}
            <div class="absolute inset-0 -z-10 opacity-[0.12]"
                 style="background-image: linear-gradient(135deg, #4647AE 1px, transparent 1px), linear-gradient(45deg, #4382DF 1px, transparent 1px); background-size: 24px 24px;"></div>

            {{-- Ambient glow --}}
            <div class="absolute -bottom-20 -right-10 -z-10 h-56 w-56 rounded-full bg-[#4647AE]/30 blur-[70px] transition duration-500 group-hover:bg-[#4382DF]/30"></div>

            {{-- Decorative rings --}}
            <div class="pointer-events-none absolute -right-12 top-8 h-48 w-48 rotate-45 border border-[#4382DF]/20 transition duration-700 group-hover:rotate-[65deg] group-hover:scale-110"></div>
            <div class="pointer-events-none absolute -right-6 top-14 h-36 w-36 rotate-45 border border-[#4647AE]/50 transition duration-700 group-hover:rotate-[25deg]"></div>

            {{-- Top label --}}
            <div class="relative flex items-center justify-between px-5 pt-5 sm:px-6">
                <span class="inline-flex items-center gap-2 border border-[#4647AE] bg-black/30 px-3 py-1.5 text-[9px] font-black uppercase tracking-[0.2em] text-[#4382DF]"
                      style="clip-path: polygon(0 0, 100% 0, 100% 70%, 88% 100%, 0 100%);">
                    <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-[#4382DF]"></span>
                    Mode 02 / Competitive
                </span>

                <span class="font-mono text-[10px] font-bold tracking-widest text-[#4382DF]/60">
                    PVP_002
                </span>
            </div>

            {{-- Icon stage --}}
            <div class="relative mx-5 mt-5 flex h-28 items-center justify-center overflow-hidden border border-[#4647AE]/50 bg-gradient-to-r from-[#4647AE]/20 via-[#112E81]/30 to-transparent sm:mx-6"
                 style="clip-path: polygon(0 0, 90% 0, 100% 22%, 100% 100%, 10% 100%, 0 78%);">

                <div class="absolute inset-0 bg-gradient-to-r from-[#4647AE]/20 to-transparent opacity-60"></div>

                {{-- Competitive icon --}}
                <div class="relative flex h-[76px] w-[76px] items-center justify-center border border-[#4382DF]/60 bg-[#090E39]/90 text-[#4382DF] shadow-[0_0_30px_rgba(70,71,174,0.25)] transition duration-500 group-hover:scale-110 group-hover:shadow-[0_0_40px_rgba(67,130,223,0.35)]"
                     style="clip-path: polygon(25% 0, 75% 0, 100% 50%, 75% 100%, 25% 100%, 0 50%);">

                    <svg class="h-10 w-10 transition duration-500 group-hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 21h8M12 17v4"/>
                        <path d="M7 4h10v5a5 5 0 0 1-10 0V4Z"/>
                        <path d="M7 6H4v2a4 4 0 0 0 4 4M17 6h3v2a4 4 0 0 1-4 4"/>
                        <path d="m9 9 2 2 4-4"/>
                    </svg>
                </div>

                <div class="absolute bottom-3 right-4 text-[8px] font-black uppercase tracking-[0.2em] text-[#4382DF]/50">
                    PLAYER VS PLAYER
                </div>
            </div>

            {{-- Content --}}
            <div class="relative px-5 pb-5 pt-5 sm:px-6 sm:pb-6">
                <p class="text-[9px] font-black uppercase tracking-[0.3em] text-[#4382DF]">
                    Multiplayer // Ranked
                </p>

                <h3 class="mt-1 text-2xl font-black uppercase italic tracking-tight text-white sm:text-3xl">
                    Battle <span class="text-[#4382DF]">PvP</span>
                </h3>

                <p class="mt-2 max-w-xs text-xs leading-relaxed text-slate-400">
                    Tantang pemain lain, buktikan kemampuanmu, dan kejar posisi leaderboard.
                </p>

                <div class="mt-5 flex items-center justify-between border-t border-[#4647AE]/40 pt-4">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-white transition group-hover:text-[#4382DF]">
                        Enter Battle Arena
                    </span>

                    <span class="flex h-9 w-12 items-center justify-center border border-[#4382DF]/50 bg-[#4647AE]/20 text-lg text-[#4382DF] transition duration-300 group-hover:w-14 group-hover:bg-[#4382DF] group-hover:text-white"
                          style="clip-path: polygon(0 0, 78% 0, 100% 50%, 78% 100%, 0 100%);">
                        →
                    </span>
                </div>
            </div>

            {{-- Corner accents --}}
            <span class="pointer-events-none absolute bottom-0 left-0 h-8 w-8 border-b-2 border-l-2 border-[#4647AE]"></span>
            <span class="pointer-events-none absolute right-0 top-0 h-8 w-8 border-r-2 border-t-2 border-[#4382DF]/70"></span>
        </a>

    </div>
</div>

{{-- ACTIVE DECK --}}
<div class="relative overflow-hidden rounded-2xl border border-[#4382DF]/30 bg-[#090E39]">

    {{-- Header --}}
    <div class="relative flex flex-col gap-3 overflow-hidden border-b border-[#4382DF]/20 bg-gradient-to-r from-[#112E81] via-[#090E39] to-[#4647AE]/30 p-5 sm:flex-row sm:items-center sm:justify-between">
        <div class="absolute inset-0 opacity-[0.08]"
             style="background-image: linear-gradient(135deg, #4382DF 1px, transparent 1px); background-size: 18px 18px;"></div>

        <div class="relative flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-xl border border-[#4382DF]/40 bg-[#4382DF]/10 text-[#4382DF] shadow-[0_0_20px_rgba(67,130,223,0.12)]">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                    <rect x="7" y="3" width="13" height="17" rx="2" transform="rotate(5 7 3)"/>
                    <rect x="4" y="5" width="13" height="17" rx="2" transform="rotate(-8 4 5)"/>
                    <path d="m10 10 2 2 3-3"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-black uppercase tracking-tight text-white">Deck Aktif</h3>
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#4382DF]">Battle Loadout // Ready</p>
            </div>
        </div>

        <a href="{{ route('play.decks.index') }}"
           class="relative inline-flex items-center gap-2 self-start rounded-lg border border-[#4382DF]/40 bg-[#4382DF]/10 px-4 py-2.5 text-[10px] font-black uppercase tracking-widest text-white transition hover:border-[#4382DF] hover:bg-[#4382DF] sm:self-auto">
            Kelola Deck <span>↗</span>
        </a>
    </div>

    <div class="p-5">
        @if ($activeDeck && $activeDeck->cards->isNotEmpty())

            <div class="mb-5 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Deployed Cards</p>
                    <p class="mt-1 text-xs text-slate-500">Pilih strategi terbaik untuk memenangkan battle.</p>
                </div>
                <div class="rounded-md border border-[#4382DF]/30 bg-[#112E81]/50 px-3 py-1.5 font-mono text-xs font-bold text-[#4382DF]">
                    {{ $activeDeck->cards->sum('pivot.quantity') }}<span class="text-slate-500">/10</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-x-3 gap-y-5 sm:grid-cols-3 xl:grid-cols-5">
                @foreach ($activeDeck->cards->take(10) as $card)

                    {{-- GAMING CARD --}}
                    <div class="group/card relative mx-auto w-full max-w-[190px] transition duration-300 hover:-translate-y-2">

                        {{-- Glow di belakang kartu --}}
                        <div class="absolute -inset-1 rounded-[1.2rem] bg-gradient-to-br from-[#4382DF]/0 via-[#4647AE]/0 to-[#4382DF]/0 opacity-0 blur-md transition duration-500 group-hover/card:from-[#4382DF]/30 group-hover/card:via-[#4647AE]/20 group-hover/card:to-[#4382DF]/30 group-hover/card:opacity-100"></div>

                        {{-- Siluet kartu bersudut --}}
                        <div class="relative overflow-hidden rounded-[1.1rem] border border-[#4382DF]/40 bg-[#000000] p-[1px] shadow-[0_8px_20px_rgba(0,0,0,0.35)] transition duration-300 group-hover/card:border-[#4382DF] group-hover/card:shadow-[0_0_24px_rgba(67,130,223,0.2)]">

                            <div class="relative overflow-hidden rounded-[1rem] bg-gradient-to-b from-[#112E81] via-[#090E39] to-black">

                                {{-- Efek garis futuristik --}}
                                <div class="pointer-events-none absolute inset-0 opacity-[0.10]"
                                     style="background-image: repeating-linear-gradient(135deg, #4382DF 0px, #4382DF 1px, transparent 1px, transparent 9px);"></div>

                                {{-- Cahaya dekoratif --}}
                                <div class="pointer-events-none absolute -right-10 top-8 h-24 w-24 rounded-full bg-[#4382DF]/20 blur-2xl transition duration-500 group-hover/card:bg-[#4382DF]/40"></div>

                                {{-- Header kartu --}}
                                <div class="relative z-10 flex items-center justify-between border-b border-white/10 px-3 py-3">
                                    <span class="text-[8px] font-black uppercase tracking-[0.2em] text-[#4382DF]">
                                        NAVO // CARD
                                    </span>

                                    <div class="flex h-7 w-7 items-center justify-center rounded-full border border-[#4382DF] bg-[#090E39] text-xs font-black text-white shadow-[0_0_12px_rgba(67,130,223,0.25)]">
                                        {{ $card->energy_cost }}
                                    </div>
                                </div>

                                {{-- Area ilustrasi berbasis ikon --}}
                                <div class="relative z-10 px-3 pt-4">
                                    <div class="relative flex aspect-square items-center justify-center overflow-hidden rounded-t-[2rem] rounded-b-lg border border-[#4382DF]/30 bg-gradient-to-br from-[#4647AE]/40 via-[#112E81]/60 to-black">

                                        <div class="absolute inset-2 rounded-t-[1.7rem] rounded-b-md border border-[#4382DF]/15"></div>

                                        @if ($card->type === 'attack')
                                            <svg class="relative z-10 h-14 w-14 text-[#4382DF] drop-shadow-[0_0_12px_rgba(67,130,223,0.55)] transition duration-300 group-hover/card:scale-110 group-hover/card:rotate-[-8deg]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="m14.5 6.5 3-3 3 3-3 3z"/>
                                                <path d="m6.5 14.5-3 3 3 3 3-3z"/>
                                                <path d="m15 9-6 6"/>
                                                <path d="m5 5 4 4M15 15l4 4"/>
                                                <path d="M12 2v3M2 12h3M19 12h3M12 19v3"/>
                                            </svg>
                                        @elseif ($card->type === 'defense')
                                            <svg class="relative z-10 h-14 w-14 text-[#4382DF] drop-shadow-[0_0_12px_rgba(67,130,223,0.55)] transition duration-300 group-hover/card:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round">
                                                <path d="M12 2 20 5v6c0 5-3.5 8.5-8 11-4.5-2.5-8-6-8-11V5l8-3Z"/>
                                                <path d="m8.5 12 2.3 2.3 4.7-5"/>
                                            </svg>
                                        @else
                                            <svg class="relative z-10 h-14 w-14 text-[#4382DF] drop-shadow-[0_0_12px_rgba(67,130,223,0.55)] transition duration-300 group-hover/card:scale-110 group-hover/card:rotate-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round">
                                                <path d="m12 2 2.5 7.5L22 12l-7.5 2.5L12 22l-2.5-7.5L2 12l7.5-2.5L12 2Z"/>
                                            </svg>
                                        @endif

                                        {{-- Dekorasi sudut --}}
                                        <span class="absolute left-2 top-2 h-3 w-3 border-l-2 border-t-2 border-[#4382DF]"></span>
                                        <span class="absolute bottom-2 right-2 h-3 w-3 border-b-2 border-r-2 border-[#4382DF]"></span>
                                    </div>
                                </div>

                                {{-- Nama dan tipe --}}
                                <div class="relative z-10 px-3 pb-4 pt-3 text-center">
                                    <h4 class="line-clamp-2 min-h-9 text-xs font-black leading-tight text-white transition group-hover/card:text-[#4382DF]">
                                        {{ $card->name }}
                                    </h4>

                                    <div class="mx-auto my-2 h-px w-2/3 bg-gradient-to-r from-transparent via-[#4382DF]/70 to-transparent"></div>

                                    <span class="inline-flex items-center rounded-md border border-[#4382DF]/30 bg-[#4382DF]/10 px-2.5 py-1 text-[8px] font-black uppercase tracking-[0.2em] text-[#4382DF]">
                                        {{ $card->type }}
                                    </span>
                                </div>

                                {{-- Footer kartu --}}
                                <div class="relative z-10 flex items-center justify-between border-t border-white/10 bg-black/30 px-3 py-2">
                                    <span class="text-[8px] font-bold uppercase tracking-widest text-slate-500">DIGANOVA</span>
                                    <span class="h-1.5 w-1.5 rounded-full bg-[#4382DF] shadow-[0_0_8px_#4382DF]"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Status deck --}}
            <div class="mt-6 flex flex-col gap-3 rounded-xl border border-[#4382DF]/20 bg-gradient-to-r from-[#112E81]/30 to-[#090E39] p-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[#4382DF] opacity-50"></span>
                        <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-[#4382DF]"></span>
                    </span>
                    <div>
                        <p class="text-xs font-black uppercase tracking-wider text-white">Deck Siap Tempur</p>
                        <p class="mt-1 text-[10px] text-slate-500">Loadout tersimpan dan siap digunakan.</p>
                    </div>
                </div>
                <div class="text-xs font-bold text-slate-400">
                    Total kartu: <span class="text-white">{{ $activeDeck->cards->sum('pivot.quantity') }}</span> / 10
                </div>
            </div>

        @else

            {{-- Empty deck --}}
            <div class="relative overflow-hidden rounded-2xl border border-dashed border-[#4382DF]/40 bg-gradient-to-br from-[#112E81]/30 to-black px-4 py-12 text-center">
                <div class="absolute inset-0 opacity-[0.07]"
                     style="background-image: linear-gradient(#4382DF 1px, transparent 1px), linear-gradient(90deg, #4382DF 1px, transparent 1px); background-size: 24px 24px;"></div>

                <div class="relative z-10 mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl border border-[#4382DF]/40 bg-[#4382DF]/10 text-[#4382DF] shadow-[0_0_25px_rgba(67,130,223,0.12)]">
                    <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="5" y="3" width="14" height="18" rx="2"/>
                        <path d="M9 8h6M9 12h6M9 16h3"/>
                    </svg>
                </div>

                <h4 class="relative z-10 text-sm font-black uppercase tracking-wider text-white">Belum Ada Deck</h4>
                <p class="relative z-10 mt-2 text-xs text-slate-400">Susun deck pertamamu untuk bersiap memasuki arena.</p>

                <a href="{{ route('play.decks.index') }}"
                   class="relative z-10 mt-5 inline-flex items-center gap-2 rounded-lg bg-[#4647AE] px-5 py-3 text-xs font-black uppercase tracking-widest text-white transition hover:bg-[#4382DF] hover:shadow-[0_0_20px_rgba(67,130,223,0.25)]">
                    + Buat Deck Sekarang
                </a>
            </div>

        @endif
    </div>
</div>
            {{-- RECENT BATTLES --}}
            <div class="overflow-hidden rounded-2xl border border-[#4382DF]/25 bg-[#090E39]">
                <div class="flex items-center justify-between border-b border-[#4382DF]/20 bg-[#112E81]/25 p-5">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl border border-[#4382DF]/30 bg-[#4382DF]/10 text-[#4382DF]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                <path d="M3 12a9 9 0 1 0 3-6.7L3 8"/>
                                <path d="M3 3v5h5M12 7v5l3 2"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-black uppercase tracking-wider">Riwayat Pertempuran</h3>
                            <p class="mt-1 text-[10px] text-slate-500">Your recent matches</p>
                        </div>
                    </div>

                    <a href="{{ route('play.leaderboard') }}" class="text-[10px] font-black uppercase tracking-widest text-[#4382DF] transition hover:text-white">
                        Leaderboard →
                    </a>
                </div>

                <div class="p-5">
                    @if ($recentBattles->isEmpty())
                        <div class="py-8 text-center">
                            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full border border-[#4382DF]/20 bg-[#112E81]/30 text-[#4382DF]">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <path d="M12 8v4l3 3"/><circle cx="12" cy="12" r="9"/>
                                </svg>
                            </div>
                            <p class="text-xs font-bold text-slate-400">Belum ada riwayat pertempuran.</p>
                            <p class="mt-1 text-[10px] text-slate-600">Mulai battle untuk mencatat pertandingan pertamamu.</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach ($recentBattles as $battle)
                                @php
                                    $isWin = $battle->winner_id === $user->id;
                                    $opp = $battle->player1_id === $user->id ? $battle->player2 : $battle->player1;
                                @endphp

                                <div class="group flex items-center gap-3 rounded-xl border border-[#4382DF]/15 bg-black/20 p-3 transition hover:border-[#4382DF]/40 hover:bg-[#112E81]/20 sm:gap-4 sm:p-4">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border {{ $isWin ? 'border-[#4382DF]/50 bg-[#4382DF]/15 text-[#4382DF]' : 'border-[#4647AE]/50 bg-[#4647AE]/20 text-slate-300' }} text-sm font-black">
                                        {{ $isWin ? 'W' : 'L' }}
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-xs font-bold text-white">
                                            VS <strong class="text-[#4382DF]">{{ $opp->name ?? 'AI' }}</strong>
                                        </p>
                                        <p class="mt-1 text-[10px] text-slate-500">{{ $battle->created_at->diffForHumans() }}</p>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-sm font-black {{ $isWin ? 'text-[#4382DF]' : 'text-slate-300' }}">
                                            {{ $isWin ? '+50' : '-20' }}
                                        </p>
                                        <p class="text-[9px] font-bold uppercase tracking-widest text-slate-600">Points</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
