@extends('playground.layouts.app')

@section('title', 'Profil Navo — DIGANOVA Playground')

@section('content')

<style>
    /* ============================================================
       PROFILE PAGE — aligned with landing page design system
       ============================================================ */
    .profile-page {
        position: relative;
        z-index: 1;
        max-width: 1180px;
        margin: 0 auto;
        padding: 0 1.5rem 3rem;
    }

    /* Back link */
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        font-size: .7rem;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: var(--muted);
        text-decoration: none;
        padding: .5rem 0;
        transition: color .18s ease;
    }
    .back-link:hover { color: var(--accent-text); }

    /* ===== HEADER PROFILE ===== */
    .profile-hero {
        position: relative;
        margin-top: 1.25rem;
        padding: 2rem;
        border: 1px solid var(--line);
        border-radius: 6px;
        background: var(--panel);
        -webkit-backdrop-filter: blur(10px);
                backdrop-filter: blur(10px);
        box-shadow: var(--shadow);
        overflow: hidden;
    }
    .profile-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 2rem; right: 2rem;
        height: 2px;
        background: linear-gradient(90deg, transparent, var(--cyan), transparent);
        opacity: .8;
    }
    .profile-hero::after {
        content: '';
        position: absolute;
        top: -40%;
        right: -10%;
        width: 28rem;
        height: 28rem;
        background: radial-gradient(circle, var(--glow) 0%, transparent 65%);
        filter: blur(20px);
        pointer-events: none;
    }

    .profile-hero-inner {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1.5rem;
    }
    @media (min-width: 768px) {
        .profile-hero-inner {
            flex-direction: row;
            align-items: center;
            gap: 2rem;
        }
    }

    .profile-avatar-wrap {
        position: relative;
        flex-shrink: 0;
    }
    .profile-avatar-ring {
        position: absolute;
        inset: -10px;
        border: 1px dashed var(--line-strong);
        border-radius: 50%;
        animation: profile-spin 30s linear infinite;
    }
    .profile-avatar-ring::after {
        content: '';
        position: absolute;
        top: -3px; left: 50%;
        width: 6px; height: 6px;
        margin-left: -3px;
        border-radius: 50%;
        background: var(--cyan);
        box-shadow: 0 0 10px var(--cyan);
    }
    @keyframes profile-spin {
        to { transform: rotate(360deg); }
    }

    .profile-copy {
        flex: 1;
        text-align: center;
    }
    @media (min-width: 768px) {
        .profile-copy { text-align: left; }
    }

    .profile-kicker {
        font-family: 'Anta', sans-serif;
        font-size: .62rem;
        letter-spacing: .2em;
        text-transform: uppercase;
        color: var(--faint);
    }

    .profile-name {
        font-family: 'Iceberg', serif;
        -webkit-text-stroke: .4px currentColor;
        font-size: clamp(1.6rem, 4vw, 2.2rem);
        line-height: 1.08;
        letter-spacing: -.01em;
        color: var(--text);
        margin: .4rem 0 0;
    }

    .profile-meta {
        margin-top: .5rem;
        font-size: .82rem;
        color: var(--muted);
    }
    .profile-meta strong {
        color: var(--text);
        font-weight: 400;
        text-transform: capitalize;
    }

    /* Rarity badge */
    .rarity-badge {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        margin-top: .75rem;
        padding: .3rem .7rem;
        border: 1px solid var(--line-strong);
        border-radius: 999px;
        font-size: .6rem;
        letter-spacing: .14em;
        text-transform: uppercase;
        color: var(--muted);
    }
    .rarity-badge .rarity-gem {
        width: 5px; height: 5px;
        transform: rotate(45deg);
        background: var(--cyan);
        box-shadow: 0 0 6px var(--cyan);
        flex-shrink: 0;
    }
    .rarity-badge.legendary { color: #FACC15; border-color: rgba(250,204,21,.3); }
    .rarity-badge.legendary .rarity-gem { background: #FACC15; box-shadow: 0 0 6px #FACC15; }
    .rarity-badge.epic { color: #C084FC; border-color: rgba(192,132,252,.3); }
    .rarity-badge.epic .rarity-gem { background: #C084FC; box-shadow: 0 0 6px #C084FC; }
    .rarity-badge.rare { color: #60A5FA; border-color: rgba(96,165,250,.3); }
    .rarity-badge.rare .rarity-gem { background: #60A5FA; box-shadow: 0 0 6px #60A5FA; }
    .rarity-badge.common { color: var(--muted); }

    /* Buttons (inherit landing page style) */
    .p-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .5rem;
        padding: .7rem 1.2rem;
        font-family: 'Anta', sans-serif;
        font-size: .68rem;
        letter-spacing: .1em;
        text-transform: uppercase;
        text-decoration: none;
        border: 1px solid transparent;
        border-radius: 2px;
        cursor: pointer;
        transition: transform .18s ease, box-shadow .18s ease,
                    background-color .18s ease, border-color .18s ease, color .18s ease;
    }
    .p-btn-primary {
        background: var(--cyan);
        color: var(--cyan-ink);
        border-color: var(--cyan);
    }
    .p-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 34px -14px var(--cyan);
    }
    .p-btn-ghost {
        background: transparent;
        color: var(--text);
        border-color: var(--line-strong);
    }
    [data-theme="light"] .p-btn-ghost { background: #FFFFFF; }
    .p-btn-ghost:hover {
        transform: translateY(-2px);
        border-color: var(--cyan);
        color: var(--accent-text);
    }

    /* ===== STATS GRID ===== */
    .stats-grid {
        margin-top: 1.25rem;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: .75rem;
    }
    @media (min-width: 768px) {
        .stats-grid { grid-template-columns: repeat(5, 1fr); }
    }

    .stat-card {
        position: relative;
        padding: 1rem 1.15rem;
        border: 1px solid var(--line);
        border-radius: 6px;
        background: var(--panel);
        box-shadow: var(--shadow);
        overflow: hidden;
    }
    [data-theme="light"] .stat-card { background: #FFFFFF; }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 1rem; right: 1rem;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--line-strong), transparent);
    }

    .stat-label {
        font-family: 'Anta', sans-serif;
        font-size: .55rem;
        letter-spacing: .18em;
        text-transform: uppercase;
        color: var(--faint);
    }

    .stat-value {
        font-family: 'Iceberg', serif;
        -webkit-text-stroke: .4px currentColor;
        font-size: 1.85rem;
        line-height: 1;
        margin-top: .4rem;
        color: var(--text);
    }
    .stat-value.accent { color: var(--accent-text); }
    .stat-value.win { color: #4ADE80; }
    .stat-value.loss { color: #F87171; }
    .stat-value.streak { color: #FACC15; }

    /* ===== MAIN GRID ===== */
    .content-grid {
        margin-top: 1.25rem;
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    @media (min-width: 1024px) {
        .content-grid { grid-template-columns: 2fr 1fr; }
    }

    /* Panel generic */
    .p-panel {
        position: relative;
        padding: 1.25rem;
        border: 1px solid var(--line);
        border-radius: 6px;
        background: var(--panel);
        box-shadow: var(--shadow);
    }
    [data-theme="light"] .p-panel { background: #FFFFFF; }

    .p-panel-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-family: 'Iceberg', serif;
        -webkit-text-stroke: .35px currentColor;
        font-size: 1.05rem;
        color: var(--text);
        margin: 0 0 1rem;
        letter-spacing: .01em;
    }
    .p-panel-title .title-ic {
        margin-right: .45rem;
        color: var(--accent-text);
    }

    /* ===== BATTLE HISTORY ===== */
    .battle-list { display: flex; flex-direction: column; gap: .4rem; }

    .battle-row {
        display: flex;
        align-items: center;
        gap: .75rem;
        padding: .6rem .75rem;
        border: 1px solid transparent;
        border-radius: 4px;
        background: color-mix(in srgb, var(--panel) 50%, transparent);
        transition: border-color .18s ease, background-color .18s ease;
    }
    [data-theme="light"] .battle-row { background: #F8FAFC; }
    .battle-row:hover {
        border-color: var(--line-strong);
        background: var(--panel);
    }

    .battle-badge {
        width: 2.2rem;
        height: 2.2rem;
        display: grid;
        place-items: center;
        border-radius: 50%;
        font-family: 'Iceberg', serif;
        -webkit-text-stroke: .3px currentColor;
        font-size: .9rem;
        flex-shrink: 0;
        border: 1px solid currentColor;
    }
    .battle-badge.win  { color: #4ADE80; background: rgba(74,222,128,.10); }
    .battle-badge.loss { color: #F87171; background: rgba(248,113,113,.10); }
    .battle-badge.draw { color: var(--muted); background: rgba(148,163,184,.10); }

    .battle-info { flex: 1; min-width: 0; }
    .battle-vs {
        font-size: .82rem;
        color: var(--text);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .battle-vs strong { font-weight: 400; }

    .battle-mode {
        display: inline-block;
        margin-left: .4rem;
        padding: .12rem .4rem;
        font-size: .52rem;
        letter-spacing: .14em;
        text-transform: uppercase;
        border-radius: 2px;
        border: 1px solid currentColor;
        vertical-align: middle;
    }
    .battle-mode.pve { color: #60A5FA; }
    .battle-mode.pvp { color: #C084FC; }

    .battle-time {
        font-size: .62rem;
        color: var(--faint);
        letter-spacing: .06em;
        margin-top: .12rem;
    }

    .battle-delta {
        font-family: 'Iceberg', serif;
        -webkit-text-stroke: .3px currentColor;
        font-size: .9rem;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .battle-delta.pos { color: #4ADE80; }
    .battle-delta.neg { color: #F87171; }

    /* Empty state */
    .empty-state {
        padding: 2.5rem 1rem;
        text-align: center;
    }
    .empty-state p {
        font-size: .82rem;
        color: var(--muted);
        margin: 0;
    }
    .empty-state a {
        display: inline-block;
        margin-top: .6rem;
        font-size: .7rem;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--accent-text);
        text-decoration: none;
    }
    .empty-state a:hover { text-decoration: underline; }

    /* ===== SIDEBAR ===== */
    .sidebar { display: flex; flex-direction: column; gap: 1rem; }

    .side-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: .78rem;
    }
    .side-row + .side-row { margin-top: .45rem; }
    .side-row .label { color: var(--muted); }
    .side-row .value {
        font-family: 'Iceberg', serif;
        -webkit-text-stroke: .3px currentColor;
        color: var(--text);
    }
    .side-row .value.accent { color: var(--accent-text); }
    .side-row .value.streak-best { color: #FACC15; }

    .side-deck-name {
        font-family: 'Iceberg', serif;
        -webkit-text-stroke: .35px currentColor;
        font-size: 1.05rem;
        color: var(--text);
        margin: .25rem 0 0;
    }
    .side-muted {
        font-size: .68rem;
        letter-spacing: .06em;
        color: var(--faint);
        margin-top: .2rem;
    }
    .side-link {
        font-size: .62rem;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: var(--accent-text);
        text-decoration: none;
    }
    .side-link:hover { text-decoration: underline; }

    @media (prefers-reduced-motion: reduce) {
        .profile-avatar-ring { animation: none !important; }
    }
</style>

<div class="profile-page">

    {{-- Back --}}
    <a href="{{ route('play.home') }}" class="back-link">← Kembali ke Lobby</a>

    {{-- ===== HEADER PROFILE ===== --}}
    <div class="profile-hero">
        <div class="profile-hero-inner">

            <div class="profile-avatar-wrap">
                <div class="profile-avatar-ring" aria-hidden="true"></div>
                <x-navo :skin="$profile->equippedSkin" size="w-28 h-28 md:w-32 md:h-32" textSize="text-6xl md:text-7xl" />
            </div>

            <div class="profile-copy">
                <p class="profile-kicker">Navo Profile</p>
                <h1 class="profile-name">{{ $profile->equippedSkin->name ?? 'Navo Original' }}</h1>
                <p class="profile-meta">
                    {{ $user->name }} · {{ '@' . $user->username }}
                    · Rank <strong>{{ $user->rank }}</strong>
                </p>

                @if ($profile->equippedSkin)
                    @php $rarity = $profile->equippedSkin->rarity; @endphp
                    <span class="rarity-badge {{ $rarity }}">
                        <span class="rarity-gem" aria-hidden="true"></span>
                        {{ $rarity }}
                    </span>
                @endif

                <div style="margin-top:1.1rem">
                    <a href="{{ route('play.profile.skins') }}" class="p-btn p-btn-ghost">
                        🎨 Ganti Skin
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== STATS ===== --}}
    <div class="stats-grid">
        <div class="stat-card">
            <p class="stat-label">Total Battle</p>
            <p class="stat-value">{{ $profile->total_battles }}</p>
        </div>
        <div class="stat-card">
            <p class="stat-label">Wins</p>
            <p class="stat-value win">{{ $profile->wins }}</p>
        </div>
        <div class="stat-card">
            <p class="stat-label">Losses</p>
            <p class="stat-value loss">{{ $profile->losses }}</p>
        </div>
        <div class="stat-card">
            <p class="stat-label">Win Rate</p>
            <p class="stat-value accent">{{ $profile->winRate() }}%</p>
        </div>
        <div class="stat-card">
            <p class="stat-label">Best Streak</p>
            <p class="stat-value streak">{{ $profile->best_streak }}</p>
        </div>
    </div>

    {{-- ===== CONTENT GRID ===== --}}
    <div class="content-grid">

        {{-- Battle History --}}
        <div class="p-panel">
            <h3 class="p-panel-title">
                <span><span class="title-ic">⚔️</span>Battle History</span>
            </h3>

            @if ($battleHistory->isEmpty())
                <div class="empty-state">
                    <p>Belum ada battle</p>
                    <a href="{{ route('play.battle.pve') }}">Mulai battle pertama →</a>
                </div>
            @else
                <div class="battle-list">
                    @foreach ($battleHistory as $battle)
                        @php
                            $isWinner = $battle->winner_id === $user->id;
                            $isDraw   = !$battle->winner_id;
                            $opponent = $battle->player1_id === $user->id ? $battle->player2 : $battle->player1;
                            $isPve    = $battle->mode === 'pve';
                            $myPointsChange = $battle->player1_id === $user->id
                                ? $battle->player1_points_change
                                : $battle->player2_points_change;
                        @endphp
                        <div class="battle-row">
                            <div class="battle-badge {{ $isDraw ? 'draw' : ($isWinner ? 'win' : 'loss') }}">
                                {{ $isDraw ? '–' : ($isWinner ? 'W' : 'L') }}
                            </div>
                            <div class="battle-info">
                                <p class="battle-vs">
                                    vs <strong>{{ $opponent->name ?? ($isPve ? 'AI' : 'Unknown') }}</strong>
                                    <span class="battle-mode {{ $isPve ? 'pve' : 'pvp' }}">
                                        {{ $isPve ? 'PvE' : 'PvP' }}
                                    </span>
                                </p>
                                <p class="battle-time">{{ $battle->created_at->diffForHumans() }}</p>
                            </div>
                            @if ($battle->player1_points_change !== 0 || $battle->player2_points_change !== 0)
                                <span class="battle-delta {{ $myPointsChange >= 0 ? 'pos' : 'neg' }}">
                                    {{ $myPointsChange >= 0 ? '+' : '' }}{{ $myPointsChange }}
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="sidebar">

            {{-- Active Deck --}}
            <div class="p-panel">
                <h3 class="p-panel-title" style="font-size:.85rem">
                    <span><span class="title-ic">🎴</span>Deck Aktif</span>
                    <a href="{{ route('play.decks.index') }}" class="side-link">Kelola</a>
                </h3>
                @if ($activeDeck)
                    <p class="side-deck-name">{{ $activeDeck->name }}</p>
                    <p class="side-muted">{{ $activeDeck->cards->sum('pivot.quantity') }} kartu</p>
                @else
                    <p class="side-muted">Belum ada deck aktif</p>
                @endif
                <p class="side-muted" style="margin-top:.5rem">{{ $decksCount }} / 3 slot terpakai</p>
            </div>

            {{-- Favorite Card --}}
            @if ($profile->favoriteCard)
                <div class="p-panel">
                    <h3 class="p-panel-title" style="font-size:.85rem">
                        <span><span class="title-ic">⭐</span>Kartu Favorit</span>
                    </h3>
                    <p class="side-deck-name">{{ $profile->favoriteCard->name }}</p>
                    <p class="side-muted" style="text-transform:capitalize">{{ $profile->favoriteCard->type }}</p>
                </div>
            @endif

            {{-- Streak --}}
            <div class="p-panel">
                <h3 class="p-panel-title" style="font-size:.85rem">
                    <span><span class="title-ic">🔥</span>Streak</span>
                </h3>
                <div class="side-row">
                    <span class="label">Current</span>
                    <span class="value accent">{{ $profile->current_streak }}</span>
                </div>
                <div class="side-row">
                    <span class="label">Best</span>
                    <span class="value streak-best">{{ $profile->best_streak }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection