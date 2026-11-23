@extends('playground.layouts.app')

@section('title', 'Leaderboard — DIGANOVA Playground')

@section('content')

<div class="leaderboard-page relative space-y-6">

    {{-- ===================== HEADER ===================== --}}
    <section class="lb-hero hud-panel" style="--c: 32px">
        <div class="lb-hero-grid"></div>
        <div class="lb-hero-scan"></div>
        
        {{-- Ornamen Lingkaran Cahaya --}}
        <div class="absolute -right-16 -top-24 h-72 w-72 rounded-full border border-[var(--cyan)]/10 opacity-50"></div>
        <div class="absolute -right-8 -top-12 h-56 w-56 rounded-full border border-[var(--cyan)]/20 opacity-30"></div>

        <div class="relative flex flex-col justify-between gap-6 sm:flex-row sm:items-center z-10">
            <div>
                <div class="lb-kicker">
                    <span class="lb-kicker-dot"></span>
                    DIGANOVA // COMPETITIVE ARENA
                </div>

                <h1 class="lb-title" data-text="LEADERBOARD">
                    Leader<span>board</span>
                </h1>

                <p class="mt-4 max-w-lg text-sm leading-relaxed text-[var(--muted)]">
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

        <div class="relative mt-8 flex flex-wrap items-center gap-3 border-t border-[var(--line-strong)] pt-5 z-10">
            <span class="lb-chip {{ $selectedSeason && $selectedSeason->is_active ? 'lb-chip-live' : '' }}">
                <i></i>
                @if ($selectedSeason)
                    <b>{{ $selectedSeason->name }}</b>
                    @if ($selectedSeason->is_active)
                        <em>LIVE SEASON</em>
                    @else
                        <span class="text-[var(--faint)]">Ended {{ $selectedSeason->end_date->format('d M Y') }}</span>
                    @endif
                @else
                    <b>All-Time Ranking</b>
                    <span class="text-[var(--faint)]">Combined seasons</span>
                @endif
            </span>

            <span class="lb-chip"><span class="text-[var(--faint)]">Official player standings</span></span>
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
                    <span class="h-1.5 w-1.5 rotate-45 {{ !request('season') || $selectedSeason?->id === $activeSeason->id ? 'bg-[var(--cyan-ink)]' : 'bg-[var(--cyan)]' }}"></span>
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
            <section class="my-card hud-panel lb-rise" style="--c: 24px; --d: .16s">
                <div class="my-card-bar"></div>

                <div class="relative flex flex-wrap items-center gap-5 z-10">
                    <div class="my-rank-hex">
                        <span class="lb-label" style="font-size:8px">Your Rank</span>
                        <span class="my-rank-num {{ $myPosition <= 3 ? 'is-top' : '' }}">#{{ $myPosition }}</span>
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="truncate text-lg font-bold text-[var(--text)]">{{ auth()->user()->name }}</h2>
                            <span class="lb-tag">Your Profile</span>
                        </div>
                        <p class="mt-0.5 text-xs text-[var(--faint)]">{{ '@' . auth()->user()->username }}</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-6 sm:gap-8">
                        <div>
                            <p class="lb-label">Points</p>
                            <p class="lb-num mt-1 text-xl text-[var(--cyan)]">{{ number_format($myEntry->points, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="lb-label">Win / Loss</p>
                            <p class="lb-num mt-1 text-xl text-[var(--text)]">{{ $myEntry->wins }}<span class="mx-1 text-[var(--faint)]">/</span>{{ $myEntry->losses }}</p>
                        </div>
                        <div>
                            <x-rank-badge :rank="$myEntry->rank" />
                        </div>
                    </div>
                </div>
            </section>
        @elseif ($selectedSeason)
            <div class="hud-panel relative p-5 lb-rise" style="--c: 16px; --d: .16s">
                <p class="text-sm font-semibold text-[var(--text)]">No ranking data yet</p>
                <p class="mt-1 text-xs leading-relaxed text-[var(--muted)]">
                    Kamu belum memiliki peringkat di season ini. Selesaikan battle untuk mulai masuk leaderboard.
                </p>
            </div>
        @endif
    @endauth

    {{-- ===================== TOP 3 PODIUM ===================== --}}
    @if ($topPlayers->take(3)->count() >= 3)
        <section class="relative lb-rise" style="--d: .24s">
            <div class="mb-6 flex items-center justify-between gap-3">
                <div>
                    <p class="lb-kicker" style="margin-bottom:4px"><span class="lb-kicker-dot"></span>HALL OF CHAMPIONS</p>
                    <h2 class="lb-h2">Top <span>Contenders</span></h2>
                </div>
                <div class="hidden items-center gap-2 sm:flex lb-label">
                    <span class="h-px w-12 bg-[var(--cyan)]/60"></span>
                    Top 03
                </div>
            </div>

            <div class="grid grid-cols-3 items-end gap-2 sm:gap-4">

                {{-- 2nd Place --}}
                <div class="order-1">
                    <div class="podium-card podium-silver">
                        <div class="mb-4 flex justify-center">
                            <span class="podium-number">02</span>
                        </div>

                        <div class="podium-avatar avatar-silver">
                            {{ strtoupper(substr($topPlayers[1]->user->name ?? '?', 0, 1)) }}
                        </div>

                        <p class="mt-4 truncate text-center text-xs font-bold text-[var(--text)] sm:text-sm">
                            {{ $topPlayers[1]->user->name ?? 'Unknown' }}
                        </p>
                        <p class="mt-1 truncate text-center text-[9px] text-[var(--faint)] sm:text-[10px]">
                            {{ '@' . ($topPlayers[1]->user->username ?? '-') }}
                        </p>

                        <div class="podium-divider"></div>

                        <p class="lb-num text-center text-lg text-[var(--cyan)] sm:text-xl">
                            {{ number_format($topPlayers[1]->points, 0, ',', '.') }}
                        </p>
                        <p class="lb-label mt-0.5 text-center">Points</p>

                        <div class="mt-3 flex justify-center">
                            <x-rank-badge :rank="$topPlayers[1]->rank" size="sm" />
                        </div>

                        <div class="podium-base mt-5 h-10 sm:h-14">
                            <span>2ND PLACE</span>
                        </div>
                    </div>
                </div>

                {{-- 1st Place --}}
                <div class="order-2">
                    <div class="podium-card podium-gold">
                        <div class="podium-beam"></div>

                        {{-- Pembungkus Angka & Mahkota --}}
                        <div class="relative flex justify-center mb-3">
                            <span class="podium-number podium-number-first">01</span>
                            
                            {{-- Mahkota ditarik ke bawah dari angka 01 --}}
                            <svg class="podium-crown" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M3 8l4.5 4L12 5l4.5 7L21 8l-2 11H5L3 8Z"/>
                            </svg>
                        </div>

                        {{-- Avatar --}}
                        <div class="relative">
                            <div class="podium-avatar avatar-gold">
                                {{ strtoupper(substr($topPlayers[0]->user->name ?? '?', 0, 1)) }}
                            </div>
                        </div>

                        <p class="mt-4 truncate text-center text-xs font-black text-[var(--text)] sm:text-base">
                            {{ $topPlayers[0]->user->name ?? 'Unknown' }}
                        </p>
                        <p class="mt-1 truncate text-center text-[9px] text-[var(--muted)] sm:text-[10px]">
                            {{ '@' . ($topPlayers[0]->user->username ?? '-') }}
                        </p>

                        <div class="podium-divider"></div>

                        <p class="lb-num podium-first-points text-center text-xl text-[var(--cyan)] sm:text-2xl">
                            {{ number_format($topPlayers[0]->points, 0, ',', '.') }}
                        </p>
                        <p class="lb-label mt-0.5 text-center" style="color:var(--cyan)">Champion Points</p>

                        <div class="mt-3 flex justify-center">
                            <x-rank-badge :rank="$topPlayers[0]->rank" size="sm" />
                        </div>

                        <div class="podium-base podium-base-first mt-5 h-12 sm:h-16">
                            <span>CHAMPION</span>
                        </div>
                    </div>
                </div>

                {{-- 3rd Place --}}
                <div class="order-3">
                    <div class="podium-card podium-bronze">
                        <div class="mb-4 flex justify-center">
                            <span class="podium-number">03</span>
                        </div>

                        <div class="podium-avatar avatar-bronze">
                            {{ strtoupper(substr($topPlayers[2]->user->name ?? '?', 0, 1)) }}
                        </div>

                        <p class="mt-4 truncate text-center text-xs font-bold text-[var(--text)] sm:text-sm">
                            {{ $topPlayers[2]->user->name ?? 'Unknown' }}
                        </p>
                        <p class="mt-1 truncate text-center text-[9px] text-[var(--faint)] sm:text-[10px]">
                            {{ '@' . ($topPlayers[2]->user->username ?? '-') }}
                        </p>

                        <div class="podium-divider"></div>

                        <p class="lb-num text-center text-lg text-[var(--cyan)] sm:text-xl">
                            {{ number_format($topPlayers[2]->points, 0, ',', '.') }}
                        </p>
                        <p class="lb-label mt-0.5 text-center">Points</p>

                        <div class="mt-3 flex justify-center">
                            <x-rank-badge :rank="$topPlayers[2]->rank" size="sm" />
                        </div>

                        <div class="podium-base mt-5 h-8 sm:h-12">
                            <span>3RD PLACE</span>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    @endif

    {{-- ===================== FULL LEADERBOARD ===================== --}}
    <section class="hud-panel lb-table-panel lb-rise" style="--c: 32px; --d: .32s">
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
            <div class="px-6 py-16 text-center">
                <div class="lb-empty-icon">
                    <svg class="h-8 w-8 text-[var(--cyan)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 21h8m-4-4v4M7 4H4v3a4 4 0 004 4m9-7h3v3a4 4 0 01-4 4M7 4h10v5a5 5 0 01-10 0V4z"/>
                    </svg>
                </div>
                <h3 class="lb-h2 mt-5" style="font-size:1.1rem">No players ranked yet</h3>
                <p class="mt-2 text-sm text-[var(--muted)]">Jadilah pemain pertama yang mengisi leaderboard.</p>

                <a href="{{ route('play.battle.pve') }}" class="lb-cta mt-6">
                    Start Battle
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6l6 6-6 6"/>
                    </svg>
                </a>
            </div>
        @else
            <div class="overflow-x-auto lb-table-wrap">
                <table class="lb-table w-full min-w-[760px]">
                    <thead>
                        <tr>
                            <th class="text-left sm:pl-6">Rank</th>
                            <th class="text-left">Player</th>
                            <th class="text-left">Badge</th>
                            <th class="text-right">Points</th>
                            <th class="text-right">Wins</th>
                            <th class="text-right">Losses</th>
                            <th class="text-right sm:pr-6">Win Rate</th>
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
                                <td class="whitespace-nowrap sm:pl-6">
                                    <div class="flex items-center gap-3">
                                        <span class="pos-hex {{ $position <= 3 ? 'is-top' : '' }}">
                                            {{ str_pad($position, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                        <span class="text-xs font-bold {{ $position <= 3 ? 'text-[var(--text)]' : 'text-[var(--faint)]' }}">
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
                                            <p class="truncate font-semibold text-[var(--text)]">
                                                {{ $entry->user->name ?? 'Unknown' }}
                                                @if ($isMe)
                                                    <span class="lb-tag ml-1">You</span>
                                                @endif
                                            </p>
                                            <p class="mt-0.5 truncate text-[10px] text-[var(--faint)]">
                                                {{ '@' . ($entry->user->username ?? '-') }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <x-rank-badge :rank="$entry->rank" size="sm" />
                                </td>

                                <td class="text-right">
                                    <span class="lb-num text-[var(--cyan)]">{{ number_format($entry->points, 0, ',', '.') }}</span>
                                </td>

                                <td class="text-right font-semibold text-[var(--cyan)]">
                                    {{ $entry->wins }}
                                </td>

                                <td class="text-right font-semibold text-[#C084FC]">
                                    {{ $entry->losses }}
                                </td>

                                <td class="text-right sm:pr-6">
                                    <div class="inline-flex min-w-[80px] flex-col items-end gap-1.5">
                                        <span class="lb-num text-xs {{ $winRate >= 60 ? 'text-[var(--cyan)]' : ($winRate >= 40 ? 'text-[var(--text)]' : 'text-[#C084FC]') }}">
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
    <section class="hud-panel lb-rise" style="--c: 24px; --d: .4s; padding: 1.5rem">
        <div class="relative mb-5 flex flex-wrap items-center justify-between gap-3 z-10">
            <div>
                <p class="lb-kicker" style="margin-bottom:4px"><span class="lb-kicker-dot"></span>RANKING SYSTEM</p>
                <h2 class="lb-h2" style="font-size:1.05rem">Rank <span>Classification</span></h2>
            </div>
            <span class="lb-tag">6 Tiers</span>
        </div>

        <div class="relative grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6 z-10">
            @php
                $rankTiers = [
                    ['name' => 'Bronze', 'range' => '0–499', 'color' => '#CD7F32'],
                    ['name' => 'Silver', 'range' => '500–999', 'color' => '#B8C4D8'],
                    ['name' => 'Gold', 'range' => '1000–1999', 'color' => '#E5C76B'],
                    ['name' => 'Platinum', 'range' => '2000–3499', 'color' => 'var(--cyan)'],
                    ['name' => 'Diamond', 'range' => '3500–4999', 'color' => '#C084FC'],
                    ['name' => 'Legend', 'range' => '5000+', 'color' => '#FFFFFF'],
                ];
            @endphp

            @foreach ($rankTiers as $tier)
                <div class="rank-tier-card" style="--tc: {{ $tier['color'] }}; --i: {{ $loop->index }}">
                    <span class="tier-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <span class="tier-bar"></span>
                    <p class="tier-name">{{ $tier['name'] }}</p>
                    <p class="mt-1 text-[10px] text-[var(--faint)]">{{ $tier['range'] }} pts</p>
                </div>
            @endforeach
        </div>
    </section>

</div>

<style>
/* =========================================================
   BASE — CYBER NEON THEME
   ========================================================= */
.leaderboard-page {
    --lb-line: var(--line-strong);
    color: var(--text);
    font-family: 'Anta', system-ui, sans-serif;
    font-weight: 400;
}

.leaderboard-page * { box-sizing: border-box; }

.lb-title, .lb-h2, .lb-num, .lb-stat-value, .lb-kicker, .lb-label,
.lb-chip, .lb-tag, .season-filter, .podium-number, .podium-base,
.pos-hex, .my-rank-num, .lb-table th, .tier-name, .tier-num, .lb-count, .lb-cta {
    font-family: 'Iceberg', serif;
    font-weight: 400;
    -webkit-text-stroke: .35px currentColor;
}

/* Animasi masuk bertahap */
.lb-rise { animation: lbRise .8s cubic-bezier(.22, 1, .36, 1) var(--d, 0s) backwards; }
.lb-hero { animation: lbRise .8s cubic-bezier(.22, 1, .36, 1) backwards; }

/* =========================================================
   PANEL HUD (Sudut Tajam & Garis Aksen)
   ========================================================= */
.hud-panel {
    --c: 18px;
    position: relative;
    overflow: hidden;
    clip-path: polygon(var(--c) 0, 100% 0, 100% calc(100% - var(--c)), calc(100% - var(--c)) 100%, 0 100%, 0 var(--c));
    background: var(--panel);
    -webkit-backdrop-filter: blur(10px);
            backdrop-filter: blur(10px);
    box-shadow: inset 0 0 0 1px var(--line-strong);
}

.hud-panel::before {
    content: "";
    position: absolute;
    top: 0;
    left: var(--c);
    width: 80px;
    height: 2px;
    z-index: 2;
    background: var(--cyan);
    box-shadow: 0 0 12px var(--glow);
}

.hud-panel::after {
    content: "";
    position: absolute;
    right: var(--c);
    bottom: 0;
    width: 80px;
    height: 2px;
    z-index: 2;
    background: var(--cyan);
    box-shadow: 0 0 12px var(--glow);
    opacity: 0.5;
}

/* =========================================================
   HEADER
   ========================================================= */
.lb-hero { padding: 2rem; }

.lb-hero-grid {
    position: absolute;
    inset: 0;
    opacity: .55;
    background-image:
        linear-gradient(var(--grid) 1px, transparent 1px),
        linear-gradient(90deg, var(--grid) 1px, transparent 1px);
    background-size: 32px 32px;
    -webkit-mask-image: linear-gradient(to bottom, #000, transparent 90%);
            mask-image: linear-gradient(to bottom, #000, transparent 90%);
}

.lb-hero-scan {
    position: absolute;
    left: 0;
    right: 0;
    top: -100px;
    height: 100px;
    pointer-events: none;
    background: linear-gradient(180deg, transparent, var(--glow), transparent);
    animation: lbScan 6s linear infinite;
}

.lb-kicker {
    display: flex;
    align-items: center;
    gap: 9px;
    margin-bottom: 12px;
    color: var(--cyan);
    font-size: 10px;
    letter-spacing: .28em;
}

.lb-kicker-dot {
    position: relative;
    width: 8px;
    height: 8px;
    flex: 0 0 8px;
    transform: rotate(45deg);
    background: var(--cyan);
    box-shadow: 0 0 12px var(--cyan);
}

.lb-kicker-dot::after {
    content: "";
    position: absolute;
    inset: 0;
    background: var(--cyan);
    animation: lbPing 2.2s ease-out infinite;
}

.lb-title {
    position: relative;
    display: inline-block;
    font-size: clamp(32px, 5.5vw, 56px);
    line-height: 1.1;
    letter-spacing: .08em;
    text-transform: uppercase;
    transform: skewX(-8deg);
    transform-origin: left bottom;
    text-shadow: 0 0 34px var(--glow);
}

.lb-title span {
    background: linear-gradient(100deg, var(--cyan) 25%, #FFFFFF 50%, var(--cyan) 75%);
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
    color: var(--cyan);
    mix-blend-mode: screen;
    opacity: 0;
    pointer-events: none;
    animation: lbGlitch 5s steps(1) infinite;
}

.lb-title::after {
    content: "";
    display: block;
    width: 110px;
    height: 4px;
    margin-top: 12px;
    background: linear-gradient(90deg, var(--cyan), var(--accent-text), var(--cyan));
    background-size: 200% 100%;
    box-shadow: 0 0 16px var(--cyan);
    animation: lbLine 4s linear infinite;
}

/* Stat card */
.lb-stat {
    position: relative;
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 24px 14px 14px;
    background: var(--bg-soft);
    border-left: 2px solid var(--cyan);
    clip-path: polygon(0 0, 100% 0, calc(100% - 16px) 100%, 0 100%);
}

.lb-stat-icon {
    display: grid;
    place-items: center;
    width: 48px;
    height: 48px;
    color: var(--cyan);
    background: var(--panel);
    box-shadow: inset 0 0 0 1px var(--line-strong);
    clip-path: polygon(25% 0, 75% 0, 100% 50%, 75% 100%, 25% 100%, 0 50%);
}

.lb-label {
    color: var(--faint);
    font-size: 9px;
    letter-spacing: .2em;
    text-transform: uppercase;
}

.lb-stat-value {
    font-size: 30px;
    line-height: 1.1;
    text-shadow: 0 0 18px var(--glow);
}

.lb-num {
    font-variant-numeric: tabular-nums;
}

/* Chip HUD */
.lb-chip {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    padding: 7px 22px 7px 14px;
    font-size: 10px;
    letter-spacing: .1em;
    color: var(--muted);
    background: var(--bg-soft);
    border-left: 2px solid var(--cyan);
    clip-path: polygon(0 0, 100% 0, calc(100% - 12px) 100%, 0 100%);
}

.lb-chip i { width: 8px; height: 8px; transform: rotate(45deg); background: var(--faint); }
.lb-chip-live i { background: var(--cyan); box-shadow: 0 0 10px var(--cyan); animation: lbBlink 1.6s ease-in-out infinite; }
.lb-chip b { color: var(--text); }
.lb-chip em { color: var(--cyan); font-style: normal; }

.lb-tag {
    display: inline-block;
    padding: 4px 12px;
    color: var(--cyan-ink);
    font-size: 8px;
    letter-spacing: .14em;
    text-transform: uppercase;
    background: var(--cyan);
    clip-path: polygon(6px 0, 100% 0, calc(100% - 6px) 100%, 0 100%);
}

/* Judul section */
.lb-section-title { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; }
.lb-section-title span { width: 6px; height: 22px; transform: skewX(-15deg); background: var(--cyan); box-shadow: 0 0 12px var(--cyan); }
.lb-section-title h2 { font-family: 'Iceberg', sans-serif; font-size: 13px; letter-spacing: .2em; text-transform: uppercase; color: var(--text); }

.lb-h2 { font-size: 1.4rem; letter-spacing: .08em; text-transform: uppercase; }
.lb-h2 span { color: var(--cyan); text-shadow: 0 0 16px var(--glow); }

/* =========================================================
   SEASON FILTER
   ========================================================= */
.season-filter {
    position: relative;
    overflow: hidden;
    display: inline-flex;
    align-items: center;
    gap: .55rem;
    padding: .75rem 1.5rem;
    color: var(--muted);
    font-size: .68rem;
    letter-spacing: .08em;
    text-transform: uppercase;
    background: var(--panel);
    box-shadow: inset 0 0 0 1px var(--line-strong);
    clip-path: polygon(14px 0, 100% 0, calc(100% - 14px) 100%, 0 100%);
    transition: color .2s, background .2s, box-shadow .2s, transform .2s;
}

.season-filter:hover {
    color: var(--text);
    background: var(--bg-soft);
    box-shadow: inset 0 0 0 1px var(--cyan);
    transform: translateY(-2px);
}

.season-filter-active {
    color: var(--cyan-ink);
    background: var(--cyan);
    box-shadow: inset 0 0 0 1px var(--cyan), 0 0 22px var(--glow);
}

.season-filter-active::after {
    content: "";
    position: absolute;
    top: 0;
    bottom: 0;
    left: -60%;
    width: 40%;
    background: linear-gradient(100deg, transparent, rgba(255,255,255,.4), transparent);
    transform: skewX(-20deg);
    animation: lbShine 3.5s ease-in-out infinite;
}

/* =========================================================
   MY POSITION
   ========================================================= */
.my-card { padding: 1.25rem 1.5rem; }
.my-card-bar { position: absolute; right: 0; top: 0; bottom: 0; width: 4px; background: var(--cyan); box-shadow: 0 0 16px var(--cyan); }

.my-rank-hex {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 80px;
    height: 80px;
    flex-shrink: 0;
    background: var(--bg-soft);
    box-shadow: inset 0 0 0 1px var(--line-strong);
    clip-path: polygon(25% 0, 75% 0, 100% 50%, 75% 100%, 25% 100%, 0 50%);
}

.my-rank-num { font-size: 1.45rem; line-height: 1.2; }
.my-rank-num.is-top { color: var(--cyan); text-shadow: 0 0 16px var(--glow); }

/* =========================================================
   PODIUM (PERSIS SEPERTI GAMBAR)
   ========================================================= */
.podium-card {
    --c: 20px;
    position: relative;
    overflow: hidden;
    padding: 1.1rem .7rem 0;
    clip-path: polygon(var(--c) 0, calc(100% - var(--c)) 0, 100% var(--c), 100% 100%, 0 100%, 0 var(--c));
    background: var(--panel);
    -webkit-backdrop-filter: blur(10px);
            backdrop-filter: blur(10px);
    box-shadow: inset 0 0 0 1px var(--line-strong);
    transition: transform .3s cubic-bezier(.22,1,.36,1), box-shadow .3s;
}

.podium-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--pc, var(--faint));
    box-shadow: 0 0 14px var(--pc, transparent);
}

.podium-card:hover { transform: translateY(-8px); box-shadow: inset 0 0 0 1px var(--cyan), inset 0 0 40px var(--glow); }

.podium-silver { --pc: var(--line-strong); }
.podium-bronze { --pc: var(--line-strong); }

.podium-gold {
    --pc: var(--cyan);
    padding-top: 1.4rem;
    background: var(--bg-soft);
    box-shadow: inset 0 0 0 1px var(--cyan), inset 0 0 50px var(--glow);
    transform: scale(1.05);
    z-index: 10;
}

.podium-gold::before { height: 4px; background: linear-gradient(90deg, transparent, var(--cyan), #FFF, var(--cyan), transparent); background-size: 200% 100%; animation: lbLine 3s linear infinite; }

/* Sunburst (Sinar di belakang) */
.podium-beam {
    position: absolute;
    left: 50%;
    top: 20px;
    width: 340px;
    height: 340px;
    margin-left: -170px;
    z-index: 0;
    pointer-events: none;
    border-radius: 50%;
    background: repeating-conic-gradient(var(--cyan) 0deg 6deg, transparent 6deg 24deg);
    -webkit-mask-image: radial-gradient(circle, #000, transparent 65%);
            mask-image: radial-gradient(circle, #000, transparent 65%);
    opacity: 0.15;
    animation: lbSpin 30s linear infinite;
}

.podium-card > *:not(.podium-beam) { position: relative; z-index: 1; }

/* Nomor Peringkat (Heksagonal) */
.podium-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.6rem;
    height: 2.6rem;
    font-size: .85rem;
    letter-spacing: .06em;
    color: var(--cyan);
    background: var(--bg-soft);
    box-shadow: inset 0 0 0 1px var(--cyan);
    clip-path: polygon(25% 0, 75% 0, 100% 50%, 75% 100%, 25% 100%, 0 50%);
}

.podium-number-first {
    width: 3.4rem;
    height: 3.4rem;
    font-size: 1.1rem;
    background: var(--cyan);
    color: var(--cyan-ink);
    box-shadow: inset 0 0 0 1px var(--cyan);
    animation: lbPulseCyan 2s infinite alternate;
}

/* Avatar Heksagonal Besar */
.podium-avatar {
    position: relative;
    isolation: isolate;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 3.6rem;
    height: 3.6rem;
    margin: 0 auto;
    font-family: 'Iceberg', sans-serif;
    font-size: 1.4rem;
    color: var(--cyan);
    clip-path: polygon(25% 0, 75% 0, 100% 50%, 75% 100%, 25% 100%, 0 50%);
    background: var(--bg-soft);
    box-shadow: inset 0 0 0 1px var(--cyan);
}

.podium-avatar::before {
    content: "";
    position: absolute;
    inset: 2px;
    z-index: -1;
    clip-path: polygon(25% 0, 75% 0, 100% 50%, 75% 100%, 25% 100%, 0 50%);
    background: var(--bg-soft);
}

.avatar-silver { background: var(--line-strong); color: var(--text); }
.avatar-bronze { background: var(--line-strong); color: var(--text); }

/* Avatar Juara 1 (Glow Cyan) */
.avatar-gold {
    width: 4.6rem;
    height: 4.6rem;
    font-size: 1.9rem;
    background: var(--cyan);
    color: var(--cyan-ink);
    filter: drop-shadow(0 0 16px var(--glow));
    box-shadow: inset 0 0 0 1px var(--cyan);
    animation: lbFloat 3.2s ease-in-out infinite;
}
.avatar-gold::before { display: none; }

/* Mahkota (Crown) - Disesuaikan posisinya */
.podium-crown {
    position: absolute;
    left: 50%;
    top: 100%; /* Ditarik ke bawah dari angka 01 */
    width: 1.6rem;
    height: 1.6rem;
    margin-left: -.8rem;
    margin-top: 4px; /* Jarak aman */
    z-index: 3;
    fill: var(--cyan);
    filter: drop-shadow(0 0 10px var(--cyan));
    animation: lbFloat 3.2s ease-in-out infinite;
}

/* Garis Pemisah */
.podium-divider { height: 1px; margin: .85rem 0; background: var(--line-strong); }
.podium-first-points { text-shadow: 0 0 20px var(--glow); }

/* Base (Alas Podium) */
.podium-base {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-left: -.7rem;
    margin-right: -.7rem;
    color: var(--faint);
    font-size: .6rem;
    letter-spacing: .14em;
    background: var(--bg-soft);
    border-top: 1px solid var(--line-strong);
}

.podium-base-first {
    color: var(--cyan-ink);
    background: var(--cyan);
    border-top-color: var(--cyan);
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
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--line-strong);
    background: var(--bg-soft);
}

.lb-count {
    display: inline-flex;
    align-items: baseline;
    gap: 7px;
    padding: 8px 18px 8px 14px;
    background: var(--panel);
    border-left: 2px solid var(--cyan);
    clip-path: polygon(0 0, 100% 0, calc(100% - 10px) 100%, 0 100%);
}
.lb-count b { font-size: .95rem; color: var(--text); }
.lb-count span { color: var(--faint); font-size: 9px; letter-spacing: .14em; text-transform: uppercase; }

.lb-note { padding: .75rem 1.5rem; color: var(--faint); font-size: 11px; border-bottom: 1px solid var(--line); }
.lb-note span { margin: 0 .35rem; color: var(--cyan); }

.lb-table-wrap {
    background-image: repeating-linear-gradient(0deg, transparent, transparent 39px, var(--line) 39px, var(--line) 40px);
}

.lb-table { border-collapse: separate; border-spacing: 0; }

.lb-table th {
    padding: .95rem 1rem;
    color: var(--faint);
    font-size: 9px;
    letter-spacing: .2em;
    text-transform: uppercase;
    background: var(--bg-soft);
    border-bottom: 1px solid var(--line-strong);
}

.lb-table td { padding: .95rem 1rem; border-bottom: 1px solid var(--line); font-size: .95rem; }

.leaderboard-row {
    background: var(--panel);
    transition: background .2s;
    animation: lbRow .5s cubic-bezier(.22,1,.36,1) backwards;
    animation-delay: calc(min(var(--i, 0), 14) * 40ms + .45s);
}

.leaderboard-row td:first-child { box-shadow: inset 3px 0 0 transparent; transition: box-shadow .2s; }
.leaderboard-row:hover { background: var(--bg-soft); }
.leaderboard-row:hover td:first-child { box-shadow: inset 3px 0 0 var(--cyan); }

.leaderboard-row-top { background: var(--panel); }
.leaderboard-row-top td:first-child { box-shadow: inset 3px 0 0 var(--cyan); }

.leaderboard-row-me { background: var(--bg-soft); }
.leaderboard-row-me td:first-child { box-shadow: inset 4px 0 0 var(--cyan); }
.leaderboard-row-me:hover { background: var(--panel); }

.pos-hex {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.3rem;
    height: 2.3rem;
    color: var(--faint);
    font-size: .75rem;
    background: var(--bg-soft);
    box-shadow: inset 0 0 0 1px var(--line-strong);
    clip-path: polygon(25% 0, 75% 0, 100% 50%, 75% 100%, 25% 100%, 0 50%);
}
.pos-hex.is-top { color: var(--cyan-ink); background: var(--cyan); box-shadow: 0 0 8px var(--glow); }

.row-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2.6rem;
    height: 2.6rem;
    flex-shrink: 0;
    color: var(--text);
    font-family: 'Iceberg', sans-serif;
    font-size: .9rem;
    background: var(--bg-soft);
    box-shadow: inset 0 0 0 1px var(--line-strong);
    clip-path: polygon(0 0, calc(100% - 9px) 0, 100% 9px, 100% 100%, 9px 100%, 0 calc(100% - 9px));
}
.row-avatar.is-me { background: var(--cyan); color: var(--cyan-ink); box-shadow: none; }

.wr-track {
    width: 5rem;
    height: 6px;
    background: var(--line-strong);
}
.wr-fill {
    height: 100%;
    background: var(--cyan);
    -webkit-mask-image: repeating-linear-gradient(90deg, #000 0 6px, transparent 6px 8px);
            mask-image: repeating-linear-gradient(90deg, #000 0 6px, transparent 6px 8px);
}

/* Empty state */
.lb-empty-icon {
    display: grid;
    place-items: center;
    width: 80px;
    height: 80px;
    margin: 0 auto;
    background: var(--bg-soft);
    box-shadow: inset 0 0 0 1px var(--line-strong);
    clip-path: polygon(25% 0, 75% 0, 100% 50%, 75% 100%, 25% 100%, 0 50%);
    animation: lbFloat 3s ease-in-out infinite;
}

.lb-cta {
    position: relative;
    overflow: hidden;
    display: inline-flex;
    align-items: center;
    gap: .6rem;
    margin-top: 1.5rem;
    padding: .95rem 2.2rem;
    color: var(--cyan-ink);
    font-size: .75rem;
    letter-spacing: .16em;
    text-transform: uppercase;
    background: var(--cyan);
    clip-path: polygon(16px 0, 100% 0, calc(100% - 16px) 100%, 0 100%);
    transition: filter .2s, transform .2s;
}
.lb-cta:hover { filter: brightness(1.2); transform: translateY(-2px); }
.lb-cta::after { content: ""; position: absolute; top: 0; bottom: 0; left: -60%; width: 40%; background: linear-gradient(100deg, transparent, rgba(255,255,255,.4), transparent); transform: skewX(-20deg); animation: lbShine 3.5s ease-in-out infinite; }

/* =========================================================
   RANK GUIDE
   ========================================================= */
.rank-tier-card {
    --tc: var(--cyan);
    position: relative;
    min-height: 6.2rem;
    padding: 1rem 1rem .9rem;
    overflow: hidden;
    clip-path: polygon(0 0, calc(100% - 14px) 0, 100% 14px, 100% 100%, 14px 100%, 0 calc(100% - 14px));
    background: var(--bg-soft);
    box-shadow: inset 0 0 0 1px var(--line-strong);
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
    opacity: .05;
    transition: opacity .25s;
}

.rank-tier-card:hover { transform: translateY(-4px); box-shadow: inset 0 0 0 1px var(--tc), 0 0 22px var(--glow); }
.rank-tier-card:hover::after { opacity: .15; }

.tier-num { position: absolute; right: .8rem; top: .6rem; color: var(--line-strong); font-size: 1.2rem; }
.tier-bar { display: block; width: 2.2rem; height: 4px; margin-bottom: .8rem; background: var(--tc); box-shadow: 0 0 12px var(--tc); transform: skewX(-20deg); }
.tier-name { color: var(--text); font-size: .85rem; letter-spacing: .1em; text-transform: uppercase; }

/* =========================================================
   KEYFRAMES
   ========================================================= */
@keyframes lbRise { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
@keyframes lbRow { from { opacity: 0; transform: translateX(-16px); } to { opacity: 1; transform: translateX(0); } }
@keyframes lbScan { from { transform: translateY(0); } to { transform: translateY(500px); } }
@keyframes lbPing { 0% { transform: scale(1); opacity: .7; } 100% { transform: scale(3.2); opacity: 0; } }
@keyframes lbShimmer { 0%, 20% { background-position: 120% 0; } 80%, 100% { background-position: -20% 0; } }
@keyframes lbLine { from { background-position: 0 0; } to { background-position: 200% 0; } }
@keyframes lbShine { 0%, 55% { translate: 0 0; } 100% { translate: 520% 0; } }
@keyframes lbBlink { 0%, 100% { opacity: 1; } 50% { opacity: .35; } }
@keyframes lbFloat { 0%, 100% { translate: 0 0; } 50% { translate: 0 -8px; } }
@keyframes lbSpin { to { rotate: 360deg; } }
@keyframes lbGlitch {
    0%, 90%, 100% { opacity: 0; }
    91% { opacity: .85; clip-path: inset(12% 0 62% 0); translate: -4px 0; }
    93% { opacity: .85; clip-path: inset(58% 0 18% 0); translate: 4px 0; }
    95% { opacity: .85; clip-path: inset(30% 0 40% 0); translate: -2px 0; }
    97% { opacity: 0; }
}
@keyframes lbPulseCyan {
    from { box-shadow: inset 0 0 0 1px var(--cyan), 0 0 10px var(--glow); }
    to   { box-shadow: inset 0 0 0 1px var(--cyan), 0 0 25px var(--cyan); }
}

/* =========================================================
   RESPONSIVE
   ========================================================= */
@media (max-width: 640px) {
    .lb-hero { padding: 1.5rem; }
    .lb-title { font-size: 32px; }
    .season-filter { padding: .65rem 1.2rem; }
    
    /* Podium Mobile */
    .podium-card { padding-top: 0.8rem; padding-left: 0.4rem; padding-right: 0.4rem; }
    .podium-gold { transform: scale(1); }
    .podium-avatar { width: 2.8rem; height: 2.8rem; font-size: 1rem; }
    .avatar-gold { width: 3.6rem; height: 3.6rem; font-size: 1.3rem; }
    .podium-number { width: 2.2rem; height: 2.2rem; font-size: 0.75rem; }
    .podium-number-first { width: 2.8rem; height: 2.8rem; font-size: 0.9rem; }
    
    /* Sesuaikan crown di HP */
    .podium-crown { width: 1.2rem; height: 1.2rem; margin-left: -.6rem; top: 100%; margin-top: 2px; }
    
    /* My Position */
    .my-rank-hex { width: 68px; height: 68px; }
    .my-card { padding: 1rem; }
    
    /* Tabel Mobile */
    .lb-table th, .lb-table td { padding: 0.8rem 0.6rem; font-size: 0.8rem; }
    .pos-hex { width: 1.9rem; height: 1.9rem; font-size: 0.7rem; }
    .row-avatar { width: 2.1rem; height: 2.1rem; font-size: 0.8rem; }
    .lb-count { padding: 6px 14px 6px 10px; }
}

@media (max-width: 420px) {
    .podium-base { margin-right: -.4rem; margin-left: -.4rem; font-size: .45rem; letter-spacing: .04em; }
    .podium-avatar { width: 2.4rem; height: 2.4rem; font-size: 0.85rem; }
    .avatar-gold { width: 3rem; height: 3rem; font-size: 1.1rem; }
    .podium-crown { width: 1rem; height: 1rem; margin-left: -.5rem; margin-top: 1px; }
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