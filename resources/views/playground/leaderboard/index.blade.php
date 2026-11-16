@extends('playground.layouts.app')

@section('title', 'Leaderboard — DIGANOVA Playground')

@section('content')

<div class="leaderboard-page relative space-y-6">

    {{-- FULL SCREEN GRID BACKGROUND (tidak diubah) --}}
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden bg-[#05070F]">

        {{-- Pola kotak memenuhi seluruh layar --}}
        <div
            class="absolute inset-0"
            style="
                background-image:
                    linear-gradient(rgba(67, 130, 223, 0.09) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(67, 130, 223, 0.09) 1px, transparent 1px);
                background-size: 45px 45px;
                background-position: center top;
            ">
        </div>

        {{-- Cahaya biru dekoratif --}}
        <div class="absolute -left-40 top-0 h-[500px] w-[500px] rounded-full bg-[#112E81]/20 blur-[150px]"></div>
        <div class="absolute -right-40 bottom-0 h-[500px] w-[500px] rounded-full bg-[#4382DF]/10 blur-[150px]"></div>
    </div>

    {{-- ===================== HEADER ===================== --}}
    <section class="lb-hero hud-panel" style="--c: 26px">
        <div class="lb-hero-grid"></div>
        <div class="lb-hero-scan"></div>
        <div class="absolute -right-10 -top-16 h-48 w-48 rounded-full border border-[#4382DF]/20"></div>
        <div class="absolute -right-2 -top-8 h-32 w-32 rounded-full border border-[#4382DF]/20"></div>

        <div class="relative flex flex-col justify-between gap-5 sm:flex-row sm:items-center">
            <div>
                <div class="lb-kicker">
                    <span class="lb-kicker-dot"></span>
                    DIGANOVA // COMPETITIVE ARENA
                </div>

                <h1 class="lb-title" data-text="LEADERBOARD">
                    Leader<span>board</span>
                </h1>

                <p class="mt-3 max-w-lg text-sm leading-relaxed text-slate-300">
                    Arena rankings. Track your progress, compete for the top spot, and prove your place among the best.
                </p>
            </div>

            <div class="lb-stat self-start sm:self-center">
                <div class="lb-stat-icon">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M8 21h8m-4-4v4M7 4H4v3a4 4 0 004 4m9-7h3v3a4 4 0 01-4 4M7 4h10v5a5 5 0 01-10 0V4z"/>
                    </svg>
                </div>
                <div>
                    <p class="lb-label">Ranked Players</p>
                    <p class="lb-stat-value">{{ $topPlayers->count() }}</p>
                </div>
            </div>
        </div>

        <div class="relative mt-6 flex flex-wrap items-center gap-2.5 border-t border-white/10 pt-4">
            <span class="lb-chip {{ $selectedSeason && $selectedSeason->is_active ? 'lb-chip-live' : '' }}">
                <i></i>
                @if ($selectedSeason)
                    <b>{{ $selectedSeason->name }}</b>
                    @if ($selectedSeason->is_active)
                        <em>LIVE SEASON</em>
                    @else
                        <span class="text-slate-400">Ended {{ $selectedSeason->end_date->format('d M Y') }}</span>
                    @endif
                @else
                    <b>All-Time Ranking</b>
                    <span class="text-slate-400">Combined seasons</span>
                @endif
            </span>

            <span class="lb-chip"><span class="text-slate-400">Official player standings</span></span>
        </div>
    </section>

    {{-- ===================== SEASON FILTER ===================== --}}
    <section class="relative lb-rise" style="--d: .08s">
        <div class="lb-section-title">
            <span></span>
            <h2>Ranking Period</h2>
        </div>

        <div class="flex flex-wrap gap-2">
            @if ($activeSeason)
                <a href="{{ route('play.leaderboard') }}"
                   class="season-filter {{ !request('season') || $selectedSeason?->id === $activeSeason->id ? 'season-filter-active' : '' }}">
                    <span class="h-1.5 w-1.5 rotate-45 {{ !request('season') || $selectedSeason?->id === $activeSeason->id ? 'bg-white' : 'bg-[#4382DF]' }}"></span>
                    {{ $activeSeason->name }}
                    @if ($activeSeason->is_active)
                        <span class="text-[9px] uppercase tracking-wider opacity-70">Live</span>
                    @endif
                </a>
            @endif

            @foreach ($seasons as $s)
                @if (!$activeSeason || $s->id !== $activeSeason->id)
                    <a href="{{ route('play.leaderboard') }}?season={{ $s->id }}"
                       class="season-filter {{ $selectedSeason?->id === $s->id ? 'season-filter-active' : '' }}">
                        {{ $s->name }}
                    </a>
                @endif
            @endforeach

            <a href="{{ route('play.leaderboard') }}?season=all"
               class="season-filter {{ request('season') === 'all' ? 'season-filter-active' : '' }}">
                All-Time
            </a>
        </div>
    </section>

    {{-- ===================== MY POSITION ===================== --}}
    @auth
        @if ($myEntry && $myPosition)
            <section class="my-card hud-panel lb-rise" style="--c: 20px; --d: .16s">
                <div class="my-card-bar"></div>

                <div class="relative flex flex-wrap items-center gap-4">
                    <div class="my-rank-hex">
                        <span class="lb-label" style="font-size:8px">Your Rank</span>
                        <span class="my-rank-num {{ $myPosition <= 3 ? 'is-top' : '' }}">#{{ $myPosition }}</span>
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="truncate text-lg font-bold text-white">{{ auth()->user()->name }}</h2>
                            <span class="lb-tag">Your Profile</span>
                        </div>
                        <p class="mt-0.5 text-xs text-slate-400">{{ '@' . auth()->user()->username }}</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-5 sm:gap-7">
                        <div>
                            <p class="lb-label">Points</p>
                            <p class="lb-num mt-1 text-xl text-[#4382DF]">{{ number_format($myEntry->points, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="lb-label">Win / Loss</p>
                            <p class="lb-num mt-1 text-xl text-white">{{ $myEntry->wins }}<span class="mx-1 text-slate-600">/</span>{{ $myEntry->losses }}</p>
                        </div>
                        <div>
                            <x-rank-badge :rank="$myEntry->rank" />
                        </div>
                    </div>
                </div>
            </section>
        @elseif ($selectedSeason)
            <div class="hud-panel relative p-4 lb-rise" style="--c: 14px; --d: .16s">
                <p class="text-sm font-semibold text-white">No ranking data yet</p>
                <p class="mt-1 text-xs leading-relaxed text-slate-300">
                    Kamu belum memiliki peringkat di season ini. Selesaikan battle untuk mulai masuk leaderboard.
                </p>
            </div>
        @endif
    @endauth

    {{-- ===================== TOP 3 PODIUM ===================== --}}
    @if ($topPlayers->take(3)->count() >= 3)
        <section class="relative lb-rise" style="--d: .24s">
            <div class="mb-5 flex items-center justify-between gap-3">
                <div>
                    <p class="lb-kicker" style="margin-bottom:4px"><span class="lb-kicker-dot"></span>HALL OF CHAMPIONS</p>
                    <h2 class="lb-h2">Top <span>Contenders</span></h2>
                </div>
                <div class="hidden items-center gap-2 sm:flex lb-label">
                    <span class="h-px w-10 bg-[#4382DF]/60"></span>
                    Top 03
                </div>
            </div>

            <div class="grid grid-cols-3 items-end gap-2 sm:gap-4">

                {{-- 2nd Place --}}
                <div class="order-1">
                    <div class="podium-card podium-silver">
                        <div class="mb-3 flex justify-center">
                            <span class="podium-number">02</span>
                        </div>

                        <div class="podium-avatar avatar-silver">
                            {{ strtoupper(substr($topPlayers[1]->user->name ?? '?', 0, 1)) }}
                        </div>

                        <p class="mt-3 truncate text-center text-xs font-bold text-white sm:text-sm">
                            {{ $topPlayers[1]->user->name ?? 'Unknown' }}
                        </p>
                        <p class="mt-1 truncate text-center text-[9px] text-slate-400 sm:text-[10px]">
                            {{ '@' . ($topPlayers[1]->user->username ?? '-') }}
                        </p>

                        <div class="podium-divider"></div>

                        <p class="lb-num text-center text-lg text-[#4382DF] sm:text-xl">
                            {{ number_format($topPlayers[1]->points, 0, ',', '.') }}
                        </p>
                        <p class="lb-label mt-0.5 text-center">Points</p>

                        <div class="mt-3 flex justify-center">
                            <x-rank-badge :rank="$topPlayers[1]->rank" size="sm" />
                        </div>

                        <div class="podium-base mt-4 h-8 sm:h-12">
                            <span>2ND PLACE</span>
                        </div>
                    </div>
                </div>

                {{-- 1st Place --}}
                <div class="order-2">
                    <div class="podium-card podium-gold">
                        <div class="podium-beam"></div>

                        <div class="mb-3 flex justify-center">
                            <span class="podium-number podium-number-first">01</span>
                        </div>

                        <div class="relative">
                            <svg class="podium-crown" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M3 8l4.5 4L12 5l4.5 7L21 8l-2 11H5L3 8Z"/>
                            </svg>
                            <div class="podium-avatar avatar-gold">
                                {{ strtoupper(substr($topPlayers[0]->user->name ?? '?', 0, 1)) }}
                            </div>
                        </div>

                        <p class="mt-3 truncate text-center text-xs font-black text-white sm:text-base">
                            {{ $topPlayers[0]->user->name ?? 'Unknown' }}
                        </p>
                        <p class="mt-1 truncate text-center text-[9px] text-slate-300 sm:text-[10px]">
                            {{ '@' . ($topPlayers[0]->user->username ?? '-') }}
                        </p>

                        <div class="podium-divider"></div>

                        <p class="lb-num podium-first-points text-center text-xl text-white sm:text-2xl">
                            {{ number_format($topPlayers[0]->points, 0, ',', '.') }}
                        </p>
                        <p class="lb-label mt-0.5 text-center" style="color:#4382DF">Champion Points</p>

                        <div class="mt-3 flex justify-center">
                            <x-rank-badge :rank="$topPlayers[0]->rank" size="sm" />
                        </div>

                        <div class="podium-base podium-base-first mt-4 h-10 sm:h-14">
                            <span>CHAMPION</span>
                        </div>
                    </div>
                </div>

                {{-- 3rd Place --}}
                <div class="order-3">
                    <div class="podium-card podium-bronze">
                        <div class="mb-3 flex justify-center">
                            <span class="podium-number">03</span>
                        </div>

                        <div class="podium-avatar avatar-bronze">
                            {{ strtoupper(substr($topPlayers[2]->user->name ?? '?', 0, 1)) }}
                        </div>

                        <p class="mt-3 truncate text-center text-xs font-bold text-white sm:text-sm">
                            {{ $topPlayers[2]->user->name ?? 'Unknown' }}
                        </p>
                        <p class="mt-1 truncate text-center text-[9px] text-slate-400 sm:text-[10px]">
                            {{ '@' . ($topPlayers[2]->user->username ?? '-') }}
                        </p>

                        <div class="podium-divider"></div>

                        <p class="lb-num text-center text-lg text-[#4382DF] sm:text-xl">
                            {{ number_format($topPlayers[2]->points, 0, ',', '.') }}
                        </p>
                        <p class="lb-label mt-0.5 text-center">Points</p>

                        <div class="mt-3 flex justify-center">
                            <x-rank-badge :rank="$topPlayers[2]->rank" size="sm" />
                        </div>

                        <div class="podium-base mt-4 h-6 sm:h-10">
                            <span>3RD PLACE</span>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    @endif

    {{-- ===================== FULL LEADERBOARD ===================== --}}
    <section class="hud-panel lb-table-panel lb-rise" style="--c: 26px; --d: .32s">
        <div class="lb-table-head">
            <div>
                <p class="lb-kicker" style="margin-bottom:4px"><span class="lb-kicker-dot"></span>OFFICIAL STANDINGS</p>
                <h2 class="lb-h2" style="font-size:1.05rem">Player <span>Rankings</span></h2>
            </div>

            <div class="lb-count">
                <b>{{ $topPlayers->count() }}</b>
                <span>Players</span>
            </div>
        </div>

        @if ($selectedSeason)
            <div class="lb-note">
                Season points <span>/</span> Reset setiap season baru
            </div>
        @else
            <div class="lb-note">
                All-time total points <span>/</span> Combined seasons
            </div>
        @endif

        @if ($topPlayers->isEmpty())
            <div class="px-6 py-14 text-center">
                <div class="lb-empty-icon">
                    <svg class="h-8 w-8 text-[#4382DF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 21h8m-4-4v4M7 4H4v3a4 4 0 004 4m9-7h3v3a4 4 0 01-4 4M7 4h10v5a5 5 0 01-10 0V4z"/>
                    </svg>
                </div>
                <h3 class="lb-h2 mt-4" style="font-size:1.1rem">No players ranked yet</h3>
                <p class="mt-1 text-sm text-slate-400">Jadilah pemain pertama yang mengisi leaderboard.</p>

                <a href="{{ route('play.battle.pve') }}" class="lb-cta">
                    Start Battle
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6l6 6-6 6"/>
                    </svg>
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="lb-table w-full min-w-[760px]">
                    <thead>
                        <tr>
                            <th class="text-left sm:pl-5">Rank</th>
                            <th class="text-left">Player</th>
                            <th class="text-left">Badge</th>
                            <th class="text-right">Points</th>
                            <th class="text-right">Wins</th>
                            <th class="text-right">Losses</th>
                            <th class="text-right sm:pr-5">Win Rate</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($topPlayers as $i => $entry)
                            @php
                                $position = $i + 1;
                                $isMe = auth()->check() && $entry->user_id === auth()->id();
                                $totalGames = $entry->wins + $entry->losses;
                                $winRate = $totalGames > 0 ? round(($entry->wins / $totalGames) * 100) : 0;
                            @endphp

                            <tr class="leaderboard-row {{ $isMe ? 'leaderboard-row-me' : '' }} {{ $position <= 3 ? 'leaderboard-row-top' : '' }}"
                                style="--i: {{ $i }}">
                                <td class="whitespace-nowrap sm:pl-5">
                                    <div class="flex items-center gap-3">
                                        <span class="pos-hex {{ $position <= 3 ? 'is-top' : '' }}">
                                            {{ str_pad($position, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                        <span class="text-xs font-bold {{ $position <= 3 ? 'text-white' : 'text-slate-500' }}">
                                            #{{ $position }}
                                        </span>
                                    </div>
                                </td>

                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="row-avatar {{ $isMe ? 'is-me' : '' }}">
                                            {{ strtoupper(substr($entry->user->name ?? '?', 0, 1)) }}
                                        </div>

                                        <div class="min-w-0">
                                            <p class="truncate font-semibold text-white">
                                                {{ $entry->user->name ?? 'Unknown' }}
                                                @if ($isMe)
                                                    <span class="lb-tag ml-1">You</span>
                                                @endif
                                            </p>
                                            <p class="mt-0.5 truncate text-[10px] text-slate-500">
                                                {{ '@' . ($entry->user->username ?? '-') }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <x-rank-badge :rank="$entry->rank" size="sm" />
                                </td>

                                <td class="text-right">
                                    <span class="lb-num text-[#4382DF]">{{ number_format($entry->points, 0, ',', '.') }}</span>
                                </td>

                                <td class="text-right font-semibold text-[#4382DF]">
                                    {{ $entry->wins }}
                                </td>

                                <td class="text-right font-semibold text-[#8E90E8]">
                                    {{ $entry->losses }}
                                </td>

                                <td class="text-right sm:pr-5">
                                    <div class="inline-flex min-w-[72px] flex-col items-end gap-1">
                                        <span class="lb-num text-xs {{ $winRate >= 60 ? 'text-[#4382DF]' : ($winRate >= 40 ? 'text-white' : 'text-[#8E90E8]') }}">
                                            {{ $winRate }}%
                                        </span>
                                        <div class="wr-track">
                                            <div class="wr-fill" style="width: {{ $winRate }}%"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    {{-- ===================== RANK GUIDE ===================== --}}
    <section class="hud-panel lb-rise" style="--c: 22px; --d: .4s; padding: 1.25rem">
        <div class="relative mb-4 flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="lb-kicker" style="margin-bottom:4px"><span class="lb-kicker-dot"></span>RANKING SYSTEM</p>
                <h2 class="lb-h2" style="font-size:1.05rem">Rank <span>Classification</span></h2>
            </div>
            <span class="lb-tag">6 Tiers</span>
        </div>

        <div class="relative grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
            @php
                $rankTiers = [
                    ['name' => 'Bronze', 'range' => '0–499', 'color' => '#CD7F32'],
                    ['name' => 'Silver', 'range' => '500–999', 'color' => '#B8C4D8'],
                    ['name' => 'Gold', 'range' => '1000–1999', 'color' => '#E5C76B'],
                    ['name' => 'Platinum', 'range' => '2000–3499', 'color' => '#4382DF'],
                    ['name' => 'Diamond', 'range' => '3500–4999', 'color' => '#4647AE'],
                    ['name' => 'Legend', 'range' => '5000+', 'color' => '#FFFFFF'],
                ];
            @endphp

            @foreach ($rankTiers as $tier)
                <div class="rank-tier-card" style="--tc: {{ $tier['color'] }}; --i: {{ $loop->index }}">
                    <span class="tier-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <span class="tier-bar"></span>
                    <p class="tier-name">{{ $tier['name'] }}</p>
                    <p class="mt-1 text-[10px] text-slate-400">{{ $tier['range'] }} pts</p>
                </div>
            @endforeach
        </div>
    </section>

</div>

<style>
@import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700;900&family=Rajdhani:wght@500;600;700&display=swap');

/* =========================================================
   BASE
   Palet: #090E39 · #112E81 · #4647AE · #4382DF · #FFFFFF · #000000
   ========================================================= */
.leaderboard-page {
    --lb-navy: #090E39;
    --lb-blue: #112E81;
    --lb-purple: #4647AE;
    --lb-bright: #4382DF;
    --lb-line: rgba(67, 130, 223, .28);
    color: #FFFFFF;
    font-family: 'Rajdhani', 'Segoe UI', system-ui, sans-serif;
    font-weight: 600;
}

.leaderboard-page * { box-sizing: border-box; }

.lb-title, .lb-h2, .lb-num, .lb-stat-value, .lb-kicker, .lb-label,
.lb-chip, .lb-tag, .season-filter, .podium-number, .podium-base,
.pos-hex, .my-rank-num, .lb-table th, .tier-name, .tier-num, .lb-count, .lb-cta {
    font-family: 'Orbitron', 'Rajdhani', sans-serif;
}

/* Animasi masuk bertahap */
.lb-rise { animation: lbRise .8s cubic-bezier(.22, 1, .36, 1) var(--d, 0s) backwards; }
.lb-hero { animation: lbRise .8s cubic-bezier(.22, 1, .36, 1) backwards; }

/* =========================================================
   PANEL HUD (sudut terpotong + garis aksen)
   ========================================================= */
.hud-panel {
    --c: 18px;
    position: relative;
    overflow: hidden;
    clip-path: polygon(var(--c) 0, 100% 0, 100% calc(100% - var(--c)), calc(100% - var(--c)) 100%, 0 100%, 0 var(--c));
    background:
        radial-gradient(ellipse at 100% 0%, rgba(70, 71, 174, .28), transparent 55%),
        linear-gradient(145deg, #0D1749 0%, #090E39 70%);
    box-shadow: inset 0 0 0 1px var(--lb-line), inset 0 0 50px rgba(17, 46, 129, .3);
}

.hud-panel::before {
    content: "";
    position: absolute;
    top: 0;
    left: var(--c);
    width: 64px;
    height: 2px;
    z-index: 2;
    background: var(--lb-bright);
    box-shadow: 0 0 12px var(--lb-bright);
}

.hud-panel::after {
    content: "";
    position: absolute;
    right: var(--c);
    bottom: 0;
    width: 64px;
    height: 2px;
    z-index: 2;
    background: var(--lb-purple);
    box-shadow: 0 0 12px var(--lb-purple);
}

/* =========================================================
   HEADER
   ========================================================= */
.lb-hero { padding: 1.75rem; }

.lb-hero-grid {
    position: absolute;
    inset: 0;
    opacity: .55;
    background-image:
        linear-gradient(rgba(67,130,223,.1) 1px, transparent 1px),
        linear-gradient(90deg, rgba(67,130,223,.1) 1px, transparent 1px);
    background-size: 28px 28px;
    -webkit-mask-image: linear-gradient(to bottom, #000, transparent 90%);
            mask-image: linear-gradient(to bottom, #000, transparent 90%);
}

.lb-hero-scan {
    position: absolute;
    left: 0;
    right: 0;
    top: -70px;
    height: 70px;
    pointer-events: none;
    background: linear-gradient(180deg, transparent, rgba(67,130,223,.12), transparent);
    animation: lbScan 6s linear infinite;
}

.lb-kicker {
    display: flex;
    align-items: center;
    gap: 9px;
    margin-bottom: 12px;
    color: var(--lb-bright);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .28em;
}

.lb-kicker-dot {
    position: relative;
    width: 7px;
    height: 7px;
    flex: 0 0 7px;
    transform: rotate(45deg);
    background: var(--lb-bright);
    box-shadow: 0 0 12px var(--lb-bright);
}

.lb-kicker-dot::after {
    content: "";
    position: absolute;
    inset: 0;
    background: var(--lb-bright);
    animation: lbPing 2.2s ease-out infinite;
}

.lb-title {
    position: relative;
    display: inline-block;
    font-size: clamp(30px, 5.2vw, 54px);
    font-weight: 900;
    line-height: 1.1;
    letter-spacing: .06em;
    text-transform: uppercase;
    transform: skewX(-7deg);
    transform-origin: left bottom;
    text-shadow: 0 0 34px rgba(67,130,223,.45);
}

.lb-title span {
    background: linear-gradient(100deg, var(--lb-bright) 25%, #FFFFFF 50%, var(--lb-bright) 75%);
    background-size: 220% 100%;
    -webkit-background-clip: text;
            background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: lbShimmer 5s ease-in-out infinite;
}

.lb-title::before {
    content: attr(data-text);
    position: absolute;
    left: 0;
    top: 0;
    color: var(--lb-purple);
    mix-blend-mode: screen;
    opacity: 0;
    pointer-events: none;
    animation: lbGlitch 5s steps(1) infinite;
}

.lb-title::after {
    content: "";
    display: block;
    width: 96px;
    height: 4px;
    margin-top: 10px;
    background: linear-gradient(90deg, var(--lb-blue), var(--lb-bright), var(--lb-purple), var(--lb-bright), var(--lb-blue));
    background-size: 200% 100%;
    box-shadow: 0 0 16px rgba(67,130,223,.6);
    animation: lbLine 4s linear infinite;
}

/* Stat card */
.lb-stat {
    position: relative;
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 22px 12px 12px;
    background: linear-gradient(90deg, rgba(67,130,223,.16), rgba(9,14,57,.8));
    border-left: 2px solid var(--lb-bright);
    clip-path: polygon(0 0, 100% 0, calc(100% - 14px) 100%, 0 100%);
}

.lb-stat-icon {
    display: grid;
    place-items: center;
    width: 46px;
    height: 46px;
    color: var(--lb-bright);
    background: linear-gradient(145deg, rgba(67,130,223,.3), rgba(17,46,129,.6));
    clip-path: polygon(25% 0, 75% 0, 100% 50%, 75% 100%, 25% 100%, 0 50%);
}

.lb-label {
    color: #8793B7;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .2em;
    text-transform: uppercase;
}

.lb-stat-value {
    font-size: 28px;
    font-weight: 900;
    line-height: 1.1;
    text-shadow: 0 0 18px rgba(67,130,223,.5);
}

.lb-num {
    font-weight: 900;
    font-variant-numeric: tabular-nums;
}

/* Chip HUD */
.lb-chip {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    padding: 6px 20px 6px 12px;
    font-size: 10px;
    letter-spacing: .1em;
    color: #CBD5E1;
    background: linear-gradient(90deg, rgba(67,130,223,.16), rgba(9,14,57,.75));
    border-left: 2px solid var(--lb-bright);
    clip-path: polygon(0 0, 100% 0, calc(100% - 10px) 100%, 0 100%);
}

.lb-chip i { width: 7px; height: 7px; transform: rotate(45deg); background: var(--lb-purple); }
.lb-chip-live i { background: var(--lb-bright); box-shadow: 0 0 10px var(--lb-bright); animation: lbBlink 1.6s ease-in-out infinite; }
.lb-chip b { color: #FFFFFF; font-weight: 700; }
.lb-chip em { color: var(--lb-bright); font-style: normal; font-weight: 800; }

.lb-tag {
    display: inline-block;
    padding: 3px 10px;
    color: #FFFFFF;
    font-size: 8px;
    font-weight: 800;
    letter-spacing: .14em;
    text-transform: uppercase;
    background: linear-gradient(100deg, var(--lb-blue), var(--lb-purple));
    clip-path: polygon(6px 0, 100% 0, calc(100% - 6px) 100%, 0 100%);
}

/* Judul section */
.lb-section-title { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; }
.lb-section-title span { width: 6px; height: 20px; transform: skewX(-15deg); background: linear-gradient(180deg, var(--lb-bright), var(--lb-purple)); box-shadow: 0 0 12px rgba(67,130,223,.6); }
.lb-section-title h2 { font-family: 'Orbitron', sans-serif; font-size: 12px; font-weight: 800; letter-spacing: .2em; text-transform: uppercase; }

.lb-h2 { font-size: 1.35rem; font-weight: 900; letter-spacing: .08em; text-transform: uppercase; }
.lb-h2 span { color: var(--lb-bright); text-shadow: 0 0 16px rgba(67,130,223,.55); }

/* =========================================================
   SEASON FILTER
   ========================================================= */
.season-filter {
    position: relative;
    overflow: hidden;
    display: inline-flex;
    align-items: center;
    gap: .55rem;
    padding: .7rem 1.4rem;
    color: #B7C3E6;
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    background: rgba(9,14,57,.9);
    box-shadow: inset 0 0 0 1px rgba(67,130,223,.28);
    clip-path: polygon(12px 0, 100% 0, calc(100% - 12px) 100%, 0 100%);
    transition: color .2s, background .2s, box-shadow .2s, transform .2s;
}

.season-filter:hover {
    color: #FFFFFF;
    background: rgba(17,46,129,.75);
    box-shadow: inset 0 0 0 1px rgba(67,130,223,.8);
    transform: translateY(-2px);
}

.season-filter-active {
    color: #FFFFFF;
    background: linear-gradient(100deg, var(--lb-blue), var(--lb-bright) 60%, var(--lb-purple));
    box-shadow: inset 0 0 0 1px rgba(255,255,255,.3), 0 0 22px rgba(67,130,223,.4);
}

.season-filter-active::after {
    content: "";
    position: absolute;
    top: 0;
    bottom: 0;
    left: -60%;
    width: 40%;
    background: linear-gradient(100deg, transparent, rgba(255,255,255,.35), transparent);
    transform: skewX(-20deg);
    animation: lbShine 3.5s ease-in-out infinite;
}

/* =========================================================
   MY POSITION
   ========================================================= */
.my-card { padding: 1.1rem 1.25rem; }
.my-card-bar { position: absolute; right: 0; top: 0; bottom: 0; width: 4px; background: linear-gradient(180deg, var(--lb-bright), var(--lb-purple)); box-shadow: 0 0 16px var(--lb-bright); }

.my-rank-hex {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 76px;
    height: 76px;
    flex-shrink: 0;
    background: linear-gradient(145deg, rgba(67,130,223,.35), rgba(17,46,129,.7));
    clip-path: polygon(25% 0, 75% 0, 100% 50%, 75% 100%, 25% 100%, 0 50%);
}

.my-rank-num { font-size: 1.35rem; font-weight: 900; line-height: 1.2; }
.my-rank-num.is-top { color: #FFFFFF; text-shadow: 0 0 16px var(--lb-bright); }

/* =========================================================
   PODIUM
   ========================================================= */
.podium-card {
    --c: 16px;
    position: relative;
    overflow: hidden;
    padding: .9rem .55rem 0;
    clip-path: polygon(var(--c) 0, calc(100% - var(--c)) 0, 100% var(--c), 100% 100%, 0 100%, 0 var(--c));
    background: linear-gradient(180deg, rgba(17,46,129,.5), rgba(9,14,57,.98));
    box-shadow: inset 0 0 0 1px rgba(67,130,223,.3);
    transition: transform .3s cubic-bezier(.22,1,.36,1), box-shadow .3s;
}

.podium-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--pc, var(--lb-purple));
    box-shadow: 0 0 14px var(--pc, var(--lb-purple));
}

.podium-card:hover { transform: translateY(-6px); box-shadow: inset 0 0 0 1px rgba(67,130,223,.8), inset 0 0 40px rgba(67,130,223,.18); }

.podium-silver { --pc: #4647AE; }
.podium-bronze { --pc: #112E81; }
.podium-bronze::before { background: linear-gradient(90deg, var(--lb-blue), var(--lb-bright)); }

.podium-gold {
    --pc: #4382DF;
    padding-top: 1.1rem;
    background: radial-gradient(ellipse at 50% 0%, rgba(67,130,223,.35), transparent 60%), linear-gradient(180deg, rgba(17,46,129,.6), rgba(9,14,57,.99) 60%);
    box-shadow: inset 0 0 0 1px rgba(67,130,223,.65), inset 0 0 50px rgba(67,130,223,.18);
}

.podium-gold::before { height: 4px; background: linear-gradient(90deg, var(--lb-blue), var(--lb-bright), #FFFFFF, var(--lb-bright), var(--lb-blue)); background-size: 200% 100%; animation: lbLine 3s linear infinite; }

.podium-card > .podium-beam {
    position: absolute;
    left: 50%;
    top: 20px;
    width: 340px;
    height: 340px;
    margin-left: -170px;
    z-index: 0;
    pointer-events: none;
    border-radius: 50%;
    background: repeating-conic-gradient(rgba(67,130,223,.16) 0deg 6deg, transparent 6deg 24deg);
    -webkit-mask-image: radial-gradient(circle, #000, transparent 65%);
            mask-image: radial-gradient(circle, #000, transparent 65%);
    animation: lbSpin 30s linear infinite;
}

.podium-card > *:not(.podium-beam) { position: relative; z-index: 1; }

.podium-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.4rem;
    height: 2.4rem;
    font-size: .8rem;
    font-weight: 900;
    letter-spacing: .06em;
    color: #FFFFFF;
    background: linear-gradient(145deg, rgba(67,130,223,.4), rgba(17,46,129,.8));
    clip-path: polygon(25% 0, 75% 0, 100% 50%, 75% 100%, 25% 100%, 0 50%);
}

.podium-number-first {
    width: 3rem;
    height: 3rem;
    font-size: 1rem;
    background: linear-gradient(145deg, #FFFFFF -40%, var(--lb-bright) 40%, var(--lb-purple));
    filter: drop-shadow(0 0 14px rgba(67,130,223,.8));
}

.podium-avatar {
    position: relative;
    isolation: isolate;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 3.4rem;
    height: 3.4rem;
    margin: 0 auto;
    font-family: 'Orbitron', sans-serif;
    font-size: 1.2rem;
    font-weight: 900;
    color: #FFFFFF;
    clip-path: polygon(25% 0, 75% 0, 100% 50%, 75% 100%, 25% 100%, 0 50%);
    background: linear-gradient(145deg, var(--lb-bright), var(--lb-purple));
}

.podium-avatar::before {
    content: "";
    position: absolute;
    inset: 2px;
    z-index: -1;
    clip-path: polygon(25% 0, 75% 0, 100% 50%, 75% 100%, 25% 100%, 0 50%);
    background: linear-gradient(145deg, #112E81, #090E39);
}

.avatar-silver { background: linear-gradient(145deg, #8E90E8, #4647AE); }
.avatar-bronze { background: linear-gradient(145deg, #4382DF, #112E81); }

.avatar-gold {
    width: 4.2rem;
    height: 4.2rem;
    font-size: 1.5rem;
    background: linear-gradient(145deg, #FFFFFF, var(--lb-bright));
    filter: drop-shadow(0 0 16px rgba(67,130,223,.75));
    animation: lbFloat 3.2s ease-in-out infinite;
}

.podium-crown {
    position: absolute;
    left: 50%;
    top: -1.35rem;
    width: 1.6rem;
    height: 1.6rem;
    margin-left: -.8rem;
    z-index: 3;
    fill: #FFFFFF;
    filter: drop-shadow(0 0 10px var(--lb-bright));
    animation: lbFloat 3.2s ease-in-out infinite;
}

.podium-divider { height: 1px; margin: .75rem 0; background: linear-gradient(90deg, transparent, rgba(67,130,223,.6), transparent); }
.podium-first-points { text-shadow: 0 0 20px rgba(67,130,223,.7); }

.podium-base {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-left: -.55rem;
    margin-right: -.55rem;
    color: #8793B7;
    font-size: .55rem;
    font-weight: 800;
    letter-spacing: .14em;
    background: repeating-linear-gradient(135deg, rgba(67,130,223,.1) 0 6px, rgba(17,46,129,.35) 6px 12px);
    border-top: 1px solid rgba(67,130,223,.3);
}

.podium-base-first {
    color: #FFFFFF;
    background: linear-gradient(90deg, rgba(67,130,223,.45), rgba(70,71,174,.5));
    border-top-color: rgba(67,130,223,.7);
}

/* =========================================================
   TABEL
   ========================================================= */
.lb-table-panel { padding: 0; }

.lb-table-head {
    position: relative;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 1.1rem 1.25rem;
    border-bottom: 1px solid rgba(67,130,223,.25);
    background: linear-gradient(90deg, rgba(17,46,129,.8), rgba(9,14,57,.4));
}

.lb-count {
    display: inline-flex;
    align-items: baseline;
    gap: 7px;
    padding: 7px 16px 7px 12px;
    background: rgba(67,130,223,.14);
    border-left: 2px solid var(--lb-bright);
    clip-path: polygon(0 0, 100% 0, calc(100% - 8px) 100%, 0 100%);
}
.lb-count b { font-size: .9rem; font-weight: 900; }
.lb-count span { color: #8793B7; font-size: 9px; letter-spacing: .14em; text-transform: uppercase; }

.lb-note { padding: .6rem 1.25rem; color: #8793B7; font-size: 11px; border-bottom: 1px solid rgba(255,255,255,.05); }
.lb-note span { margin: 0 .35rem; color: var(--lb-bright); }

.lb-table { border-collapse: separate; border-spacing: 0; }

.lb-table th {
    padding: .85rem 1rem;
    color: #8793B7;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .2em;
    text-transform: uppercase;
    background: rgba(17,46,129,.5);
    border-bottom: 1px solid rgba(67,130,223,.2);
}

.lb-table td { padding: .85rem 1rem; border-bottom: 1px solid rgba(255,255,255,.04); font-size: .95rem; }

.leaderboard-row {
    background: rgba(9,14,57,.5);
    transition: background .2s;
    animation: lbRow .5s cubic-bezier(.22,1,.36,1) backwards;
    animation-delay: calc(min(var(--i, 0), 14) * 40ms + .45s);
}

.leaderboard-row td:first-child { box-shadow: inset 3px 0 0 transparent; transition: box-shadow .2s; }
.leaderboard-row:hover { background: linear-gradient(90deg, rgba(67,130,223,.2), rgba(17,46,129,.25)); }
.leaderboard-row:hover td:first-child { box-shadow: inset 3px 0 0 var(--lb-bright); }

.leaderboard-row-top { background: linear-gradient(90deg, rgba(67,130,223,.1), rgba(9,14,57,.5) 60%); }
.leaderboard-row-top td:first-child { box-shadow: inset 3px 0 0 var(--lb-purple); }

.leaderboard-row-me { background: linear-gradient(90deg, rgba(67,130,223,.22), rgba(67,130,223,.06)); }
.leaderboard-row-me td:first-child { box-shadow: inset 4px 0 0 var(--lb-bright); }
.leaderboard-row-me:hover { background: linear-gradient(90deg, rgba(67,130,223,.3), rgba(67,130,223,.1)); }

.pos-hex {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.1rem;
    height: 2.1rem;
    color: #8793B7;
    font-size: .7rem;
    font-weight: 900;
    background: rgba(17,46,129,.55);
    clip-path: polygon(25% 0, 75% 0, 100% 50%, 75% 100%, 25% 100%, 0 50%);
}
.pos-hex.is-top { color: #FFFFFF; background: linear-gradient(145deg, var(--lb-bright), var(--lb-purple)); filter: drop-shadow(0 0 8px rgba(67,130,223,.6)); }

.row-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2.4rem;
    height: 2.4rem;
    flex-shrink: 0;
    color: #FFFFFF;
    font-family: 'Orbitron', sans-serif;
    font-size: .85rem;
    font-weight: 900;
    background: linear-gradient(145deg, var(--lb-blue), var(--lb-purple));
    clip-path: polygon(0 0, calc(100% - 9px) 0, 100% 9px, 100% 100%, 9px 100%, 0 calc(100% - 9px));
}
.row-avatar.is-me { background: linear-gradient(145deg, var(--lb-bright), var(--lb-purple)); }

.wr-track {
    width: 4.5rem;
    height: 6px;
    background: repeating-linear-gradient(90deg, rgba(67,130,223,.18) 0 6px, transparent 6px 8px);
}
.wr-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--lb-purple), var(--lb-bright), #FFFFFF);
    -webkit-mask-image: repeating-linear-gradient(90deg, #000 0 6px, transparent 6px 8px);
            mask-image: repeating-linear-gradient(90deg, #000 0 6px, transparent 6px 8px);
}

/* Empty state */
.lb-empty-icon {
    display: grid;
    place-items: center;
    width: 76px;
    height: 76px;
    margin: 0 auto;
    background: linear-gradient(145deg, rgba(67,130,223,.3), rgba(17,46,129,.6));
    clip-path: polygon(25% 0, 75% 0, 100% 50%, 75% 100%, 25% 100%, 0 50%);
    animation: lbFloat 3s ease-in-out infinite;
}

.lb-cta {
    position: relative;
    overflow: hidden;
    display: inline-flex;
    align-items: center;
    gap: .6rem;
    margin-top: 1.25rem;
    padding: .85rem 2rem;
    color: #FFFFFF;
    font-size: .72rem;
    font-weight: 900;
    letter-spacing: .16em;
    text-transform: uppercase;
    background: linear-gradient(100deg, var(--lb-blue), var(--lb-bright) 55%, var(--lb-purple));
    clip-path: polygon(14px 0, 100% 0, calc(100% - 14px) 100%, 0 100%);
    transition: filter .2s, transform .2s;
}
.lb-cta:hover { filter: brightness(1.2); transform: translateY(-2px); }
.lb-cta::after { content: ""; position: absolute; top: 0; bottom: 0; left: -60%; width: 40%; background: linear-gradient(100deg, transparent, rgba(255,255,255,.35), transparent); transform: skewX(-20deg); animation: lbShine 3.5s ease-in-out infinite; }

/* =========================================================
   RANK GUIDE
   ========================================================= */
.rank-tier-card {
    --tc: #4382DF;
    position: relative;
    min-height: 5.8rem;
    padding: .9rem .9rem .8rem;
    overflow: hidden;
    clip-path: polygon(0 0, calc(100% - 12px) 0, 100% 12px, 100% 100%, 12px 100%, 0 calc(100% - 12px));
    background: linear-gradient(160deg, rgba(17,46,129,.35), rgba(9,14,57,.85));
    box-shadow: inset 0 0 0 1px rgba(67,130,223,.2);
    transition: transform .25s cubic-bezier(.22,1,.36,1), box-shadow .25s;
    animation: lbRow .5s cubic-bezier(.22,1,.36,1) backwards;
    animation-delay: calc(var(--i, 0) * 60ms + .55s);
}

.rank-tier-card::after {
    content: "";
    position: absolute;
    inset: auto -20% -60% -20%;
    height: 80%;
    pointer-events: none;
    background: radial-gradient(ellipse, var(--tc), transparent 70%);
    opacity: .14;
    transition: opacity .25s;
}

.rank-tier-card:hover { transform: translateY(-3px); box-shadow: inset 0 0 0 1px var(--tc), 0 0 22px rgba(67,130,223,.2); }
.rank-tier-card:hover::after { opacity: .3; }

.tier-num { position: absolute; right: .7rem; top: .55rem; color: rgba(255,255,255,.12); font-size: 1.1rem; font-weight: 900; }
.tier-bar { display: block; width: 2rem; height: 4px; margin-bottom: .7rem; background: var(--tc); box-shadow: 0 0 12px var(--tc); transform: skewX(-20deg); }
.tier-name { color: #FFFFFF; font-size: .78rem; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; }

/* =========================================================
   KEYFRAMES
   ========================================================= */
@keyframes lbRise { from { opacity: 0; transform: translateY(22px); } to { opacity: 1; transform: translateY(0); } }
@keyframes lbRow { from { opacity: 0; transform: translateX(-14px); } to { opacity: 1; transform: translateX(0); } }
@keyframes lbScan { from { transform: translateY(0); } to { transform: translateY(420px); } }
@keyframes lbPing { 0% { transform: scale(1); opacity: .7; } 100% { transform: scale(3.2); opacity: 0; } }
@keyframes lbShimmer { 0%, 20% { background-position: 120% 0; } 80%, 100% { background-position: -20% 0; } }
@keyframes lbLine { from { background-position: 0 0; } to { background-position: 200% 0; } }
@keyframes lbShine { 0%, 55% { translate: 0 0; } 100% { translate: 520% 0; } }
@keyframes lbBlink { 0%, 100% { opacity: 1; } 50% { opacity: .35; } }
@keyframes lbFloat { 0%, 100% { translate: 0 0; } 50% { translate: 0 -6px; } }
@keyframes lbSpin { to { rotate: 360deg; } }
@keyframes lbGlitch {
    0%, 90%, 100% { opacity: 0; }
    91% { opacity: .85; clip-path: inset(12% 0 62% 0); translate: -4px 0; }
    93% { opacity: .85; clip-path: inset(58% 0 18% 0); translate: 4px 0; }
    95% { opacity: .85; clip-path: inset(30% 0 40% 0); translate: -2px 0; }
    97% { opacity: 0; }
}

/* =========================================================
   RESPONSIVE
   ========================================================= */
@media (max-width: 640px) {
    .lb-hero { padding: 1.25rem; }
    .lb-title { font-size: 30px; }
    .season-filter { padding: .6rem 1.1rem; }
    .podium-avatar { width: 2.8rem; height: 2.8rem; font-size: 1rem; }
    .avatar-gold { width: 3.4rem; height: 3.4rem; font-size: 1.2rem; }
    .my-rank-hex { width: 64px; height: 64px; }
}

@media (max-width: 420px) {
    .podium-card { padding-right: .35rem; padding-left: .35rem; }
    .podium-base { margin-right: -.35rem; margin-left: -.35rem; font-size: .48rem; letter-spacing: .06em; }
}

@media (prefers-reduced-motion: reduce) {
    .leaderboard-page *, .leaderboard-page *::before, .leaderboard-page *::after {
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: .01ms !important;
    }
}
</style>

@endsection