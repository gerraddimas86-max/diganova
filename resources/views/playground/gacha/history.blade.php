@extends('playground.layouts.app')

@section('title', 'Riwayat Pull — DIGANOVA Playground')

@section('content')

@php
    $total      = (int) ($stats['total'] ?? 0);
    $unique     = (int) ($stats['unique_items'] ?? 0);
    $duplicates = (int) ($stats['duplicates'] ?? 0);
    $dupRate    = $total > 0 ? min(100, round(($duplicates / $total) * 100)) : 0;
@endphp

<div class="gh-page" x-data="{ f: 'all' }">

    {{-- DEKORASI BACKGROUND (sama dengan halaman Gacha) --}}
    <div class="gh-bg" aria-hidden="true">
        <div class="gh-bg-grid"></div>
        <div class="gh-bg-stars"></div>
        <span class="gh-orb orb-1"></span>
        <span class="gh-orb orb-2"></span>
        <span class="gh-orb orb-3"></span>
    </div>

    {{-- BACK --}}
    <a href="{{ route('play.gacha.index') }}" class="gh-back">
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M19 12H5M12 5l-7 7 7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        KEMBALI KE GACHA
    </a>

    {{-- HEADER --}}
    <header class="gh-header">
        <div class="gh-kicker"><span class="gh-dot"></span>DIGANOVA PLAYGROUND / PULL LOG</div>

        <h1 class="gh-title" data-text="RIWAYAT PULL">
            RIWAYAT <span>PULL</span>
        </h1>

        <p class="gh-subtitle">Semua hasil gacha kamu, tercatat rapi di satu tempat.</p>
    </header>

    {{-- STATS --}}
    <div class="gh-stats">
        <div class="gh-stat">
            <div class="gh-stat-ico">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.5 14.9 8l6.1.9-4.4 4.3 1 6.1-5.6-3-5.6 3 1-6.1L3 8.9 9.1 8 12 2.5Z"/></svg>
            </div>
            <div>
                <p class="gh-stat-label">Total Pull</p>
                <p class="gh-stat-value">{{ number_format($total, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="gh-stat gh-stat-unique">
            <div class="gh-stat-ico">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3 4 7v5c0 4.5 3.2 7.6 8 9 4.8-1.4 8-4.5 8-9V7l-8-4Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="m8.5 12 2.5 2.5 4.5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <div>
                <p class="gh-stat-label">Item Unik</p>
                <p class="gh-stat-value">{{ number_format($unique, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="gh-stat gh-stat-dup">
            <div class="gh-stat-ico">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="8" y="8" width="12" height="12" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M16 8V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2" stroke="currentColor" stroke-width="1.8"/></svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="gh-stat-label">Duplikat</p>
                <p class="gh-stat-value">{{ number_format($duplicates, 0, ',', '.') }}</p>
                <div class="gh-meter" title="{{ $dupRate }}% dari total pull">
                    <div style="width: {{ $dupRate }}%"></div>
                </div>
            </div>
        </div>
    </div>

    @if ($pulls->isEmpty())

        {{-- EMPTY --}}
        <div class="gh-panel gh-empty">
            <div class="gh-empty-ico">?</div>
            <h2>BELUM ADA PULL</h2>
            <p>Coba keberuntunganmu di gacha!</p>
            <a href="{{ route('play.gacha.index') }}" class="gh-cta">
                BUKA GACHA
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
        </div>

    @else

        {{-- FILTER --}}
        <div class="gh-filters">
            <button type="button" class="gh-chip" :class="{ on: f === 'all' }" @click="f = 'all'">Semua</button>
            <button type="button" class="gh-chip c-legendary" :class="{ on: f === 'legendary' }" @click="f = 'legendary'">Legendary</button>
            <button type="button" class="gh-chip c-epic" :class="{ on: f === 'epic' }" @click="f = 'epic'">Epic</button>
            <button type="button" class="gh-chip c-rare" :class="{ on: f === 'rare' }" @click="f = 'rare'">Rare</button>
            <button type="button" class="gh-chip c-common" :class="{ on: f === 'common' }" @click="f = 'common'">Common</button>
            <button type="button" class="gh-chip c-dup" :class="{ on: f === 'dup' }" @click="f = 'dup'">Duplikat</button>
            <span class="gh-filter-note">Filter berlaku untuk halaman ini</span>
        </div>

        {{-- LIST --}}
        <section class="gh-panel">
            <div class="gh-panel-head">
                <div>
                    <div class="gh-kicker" style="margin-bottom:4px"><span class="gh-dot"></span>REWARD LOG</div>
                    <h2 class="gh-panel-title">PULL <span>HISTORY</span></h2>
                </div>
                <div class="gh-count">
                    <b>{{ $pulls->count() }}</b>
                    <span>PADA HALAMAN INI</span>
                </div>
            </div>

            <div class="gh-list">
                @foreach ($pulls as $pull)
                    @php
                        $item = $pull->item;
                    @endphp

                    @continue(!$item)

                    @php
                        $rarity = in_array($item->rarity, ['common', 'rare', 'epic', 'legendary'], true) ? $item->rarity : 'common';
                        $mark = ['common' => 'C', 'rare' => 'R', 'epic' => 'E', 'legendary' => 'L'][$rarity];
                        $typeMark = ['skin' => 'SK', 'emote' => 'EM', 'badge' => 'BD'][$item->type] ?? 'IT';

                        $img = $item->image_url ?? $item->image ?? $item->image_path ?? $item->icon ?? $item->thumbnail ?? $item->preview ?? null;
                        if ($img && !preg_match('#^((https?:)?//|/|data:)#', $img)) {
                            $img = asset('storage/' . ltrim(preg_replace('#^storage/#', '', $img), '/'));
                        }
                    @endphp

                    <div class="gh-row r-{{ $rarity }}"
                         style="--i: {{ $loop->index }}"
                         x-show="f === 'all' || f === '{{ $rarity }}' || (f === 'dup' && {{ $pull->is_duplicate ? 'true' : 'false' }})">

                        <div class="gh-art">
                            <span class="gh-art-mark">{{ $mark }}</span>
                            <span class="gh-art-type">{{ $typeMark }}</span>
                            @if ($img)
                                <img src="{{ $img }}" alt="{{ $item->name }}" loading="lazy" onerror="this.style.display='none'">
                            @endif
                        </div>

                        <div class="gh-info">
                            <p class="gh-name">{{ $item->name }}</p>
                            <div class="gh-tags">
                                <span class="gh-tag tag-rarity">{{ $item->rarity }}</span>
                                <span class="gh-tag tag-type">{{ $item->type }}</span>
                            </div>
                            <p class="gh-time">
                                {{ $pull->created_at->format('d M Y H:i') }}
                                <i>/</i>
                                {{ $pull->created_at->diffForHumans() }}
                            </p>
                        </div>

                        <div class="gh-side">
                            @if ($pull->is_duplicate)
                                <span class="gh-badge badge-dup">DUPLICATE</span>
                            @else
                                <span class="gh-badge badge-new">NEW</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <div class="gh-pagination">
            {{ $pulls->links() }}
        </div>

    @endif
</div>

<style>
@import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700;900&family=Rajdhani:wght@500;600;700&display=swap');

[x-cloak] { display: none !important; }

/* =========================================================
   BASE
   Palet: #090E39 · #112E81 · #4647AE · #4382DF · #FFFFFF · #000000
   ========================================================= */
.gh-page {
    --g-navy: #090E39;
    --g-blue: #112E81;
    --g-purple: #4647AE;
    --g-bright: #4382DF;
    --g-muted: #8793B7;
    --g-line: rgba(67, 130, 223, .28);
    --g-cyan: #22E4FF;
    --g-gold: #FFD76A;
    position: relative;
    isolation: isolate;
    width: 100%;
    padding-bottom: 40px;
    color: #FFFFFF;
    font-family: 'Rajdhani', 'Segoe UI', system-ui, sans-serif;
    font-weight: 600;
}

.gh-page, .gh-page * { box-sizing: border-box; }

.gh-page::before {
    content: "";
    position: fixed;
    inset: 0;
    z-index: -1;
    pointer-events: none;
    background:
        radial-gradient(ellipse at 15% 0%, rgba(70, 71, 174, .38), transparent 55%),
        radial-gradient(ellipse at 90% 45%, rgba(67, 130, 223, .2), transparent 50%),
        linear-gradient(180deg, #050A2A 0%, #02030F 100%);
}

.gh-title, .gh-kicker, .gh-stat-value, .gh-stat-label, .gh-panel-title, .gh-count,
.gh-chip, .gh-badge, .gh-back, .gh-cta, .gh-name, .gh-tag, .gh-empty h2 {
    font-family: 'Orbitron', 'Rajdhani', sans-serif;
}

/* =========================================================
   BACKGROUND DECOR
   ========================================================= */
.gh-bg { position: fixed; inset: 0; z-index: -1; overflow: hidden; pointer-events: none; }

.gh-bg-grid {
    position: absolute;
    inset: -60px;
    background-image:
        linear-gradient(rgba(67, 130, 223, .07) 1px, transparent 1px),
        linear-gradient(90deg, rgba(67, 130, 223, .07) 1px, transparent 1px);
    background-size: 56px 56px;
    -webkit-mask-image: radial-gradient(ellipse at 50% 30%, #000 0%, transparent 72%);
            mask-image: radial-gradient(ellipse at 50% 30%, #000 0%, transparent 72%);
    animation: ghGrid 14s linear infinite;
}

.gh-bg-stars {
    position: absolute;
    inset: 0;
    background-image:
        radial-gradient(1px 1px at 20px 30px, #FFFFFF, transparent),
        radial-gradient(1.5px 1.5px at 90px 90px, #8FB4FF, transparent),
        radial-gradient(1px 1px at 160px 40px, #FFFFFF, transparent),
        radial-gradient(1.5px 1.5px at 120px 150px, #B3B5FF, transparent),
        radial-gradient(1px 1px at 40px 170px, #FFFFFF, transparent);
    background-size: 210px 210px;
    opacity: .55;
    animation: ghTwinkle 5s ease-in-out infinite alternate;
}

.gh-orb { position: absolute; border-radius: 50%; filter: blur(80px); }
.gh-orb.orb-1 { width: 420px; height: 420px; left: -120px; top: -100px; background: rgba(70, 71, 174, .38); animation: ghOrb 16s ease-in-out infinite alternate; }
.gh-orb.orb-2 { width: 380px; height: 380px; right: -100px; top: 30%; background: rgba(67, 130, 223, .26); animation: ghOrb 20s ease-in-out -6s infinite alternate-reverse; }
.gh-orb.orb-3 { width: 340px; height: 340px; left: 30%; bottom: -160px; background: rgba(17, 46, 129, .5); animation: ghOrb 18s ease-in-out -3s infinite alternate; }

.gh-bg::after {
    content: "";
    position: absolute;
    inset: 0;
    background: repeating-linear-gradient(0deg, rgba(255, 255, 255, .025) 0 1px, transparent 1px 3px);
}

/* =========================================================
   BACK + HEADER
   ========================================================= */
.gh-back {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 18px;
    padding: 8px 22px 8px 14px;
    color: #CFE0FF;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .18em;
    text-decoration: none;
    background: linear-gradient(90deg, rgba(34, 228, 255, .12), rgba(9, 14, 57, .75));
    border-left: 2px solid var(--g-cyan);
    clip-path: polygon(0 0, 100% 0, calc(100% - 10px) 100%, 0 100%);
    transition: color .2s, transform .2s, background .2s;
    animation: ghRise .6s cubic-bezier(.22, 1, .36, 1) backwards;
}
.gh-back svg { width: 16px; height: 16px; transition: transform .25s; }
.gh-back:hover { color: #FFFFFF; transform: translateX(-3px); background: linear-gradient(90deg, rgba(34, 228, 255, .25), rgba(9, 14, 57, .8)); }
.gh-back:hover svg { transform: translateX(-4px); }

.gh-header { margin-bottom: 24px; animation: ghRise .8s cubic-bezier(.22, 1, .36, 1) .06s backwards; }

.gh-kicker {
    display: flex;
    align-items: center;
    gap: 9px;
    margin-bottom: 12px;
    color: var(--g-bright);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .22em;
}

.gh-dot {
    position: relative;
    width: 7px;
    height: 7px;
    flex: 0 0 7px;
    transform: rotate(45deg);
    background: var(--g-bright);
    box-shadow: 0 0 12px var(--g-bright);
}
.gh-dot::after { content: ""; position: absolute; inset: 0; background: var(--g-bright); animation: ghPing 2.2s ease-out infinite; }

.gh-title {
    position: relative;
    display: inline-block;
    font-size: clamp(30px, 5vw, 54px);
    font-weight: 900;
    line-height: 1.15;
    letter-spacing: .05em;
    text-transform: uppercase;
    transform: skewX(-7deg);
    transform-origin: left bottom;
    text-shadow: 0 0 34px rgba(34, 228, 255, .35);
}

.gh-title span {
    background: linear-gradient(100deg, var(--g-cyan) 25%, #FFFFFF 50%, var(--g-bright) 75%);
    background-size: 220% 100%;
    -webkit-background-clip: text;
            background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: ghShimmer 5s ease-in-out infinite;
}

.gh-title::before {
    content: attr(data-text);
    position: absolute;
    left: 0;
    top: 0;
    color: #FF4FD8;
    mix-blend-mode: screen;
    opacity: 0;
    pointer-events: none;
    animation: ghGlitch 5s steps(1) infinite;
}

.gh-title::after {
    content: "";
    display: block;
    width: 96px;
    height: 4px;
    margin-top: 10px;
    background: linear-gradient(90deg, var(--g-cyan), var(--g-bright), var(--g-purple), var(--g-bright), var(--g-cyan));
    background-size: 200% 100%;
    box-shadow: 0 0 16px rgba(34, 228, 255, .55);
    animation: ghLine 4s linear infinite;
}

.gh-subtitle { margin-top: 10px; color: var(--g-muted); font-size: 16px; }

/* =========================================================
   STATS
   ========================================================= */
.gh-stats { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; margin-bottom: 22px; }

.gh-stat {
    --c: 18px;
    --sc: var(--g-bright);
    position: relative;
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 18px 20px;
    clip-path: polygon(var(--c) 0, 100% 0, 100% calc(100% - var(--c)), calc(100% - var(--c)) 100%, 0 100%, 0 var(--c));
    background: radial-gradient(ellipse at 100% 0%, rgba(70, 71, 174, .28), transparent 55%), linear-gradient(145deg, #0D1749, #090E39 75%);
    box-shadow: inset 0 0 0 1px var(--g-line), inset 0 0 40px rgba(17, 46, 129, .3);
    transition: transform .3s cubic-bezier(.22, 1, .36, 1), box-shadow .3s;
    animation: ghRise .8s cubic-bezier(.22, 1, .36, 1) backwards;
}
.gh-stat:nth-child(1) { animation-delay: .12s; }
.gh-stat:nth-child(2) { animation-delay: .2s; }
.gh-stat:nth-child(3) { animation-delay: .28s; }

.gh-stat::before { content: ""; position: absolute; top: 0; left: var(--c); width: 56px; height: 2px; background: var(--sc); box-shadow: 0 0 12px var(--sc); }
.gh-stat:hover { transform: translateY(-3px); box-shadow: inset 0 0 0 1px var(--sc), inset 0 0 50px rgba(67, 130, 223, .18); }

.gh-stat-unique { --sc: #7FE3FF; }
.gh-stat-dup { --sc: var(--g-gold); }

.gh-stat-ico {
    display: grid;
    place-items: center;
    width: 50px;
    height: 50px;
    flex: 0 0 50px;
    color: var(--sc);
    background: linear-gradient(145deg, rgba(67, 130, 223, .28), rgba(17, 46, 129, .6));
    clip-path: polygon(25% 0, 75% 0, 100% 50%, 75% 100%, 25% 100%, 0 50%);
}
.gh-stat-ico svg { width: 24px; height: 24px; fill: currentColor; }
.gh-stat-ico svg[fill="none"] { fill: none; }

.gh-stat-label { color: var(--g-muted); font-size: 10px; font-weight: 800; letter-spacing: .2em; text-transform: uppercase; }
.gh-stat-value { margin-top: 3px; color: var(--sc); font-size: 30px; font-weight: 900; line-height: 1.1; font-variant-numeric: tabular-nums; text-shadow: 0 0 20px color-mix(in srgb, var(--sc) 45%, transparent); }
.gh-stat:first-child .gh-stat-value { color: #FFFFFF; }

.gh-meter { height: 5px; margin-top: 8px; background: repeating-linear-gradient(90deg, rgba(255, 215, 106, .18) 0 6px, transparent 6px 8px); }
.gh-meter div { height: 100%; background: linear-gradient(90deg, var(--g-purple), var(--g-gold)); -webkit-mask-image: repeating-linear-gradient(90deg, #000 0 6px, transparent 6px 8px); mask-image: repeating-linear-gradient(90deg, #000 0 6px, transparent 6px 8px); }

/* =========================================================
   FILTER
   ========================================================= */
.gh-filters { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; margin-bottom: 14px; animation: ghRise .8s cubic-bezier(.22, 1, .36, 1) .34s backwards; }

.gh-chip {
    --cc: var(--g-bright);
    padding: 9px 20px;
    color: #B7C3E6;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    cursor: pointer;
    background: rgba(9, 14, 57, .9);
    border: none;
    box-shadow: inset 0 0 0 1px rgba(67, 130, 223, .28);
    clip-path: polygon(10px 0, 100% 0, calc(100% - 10px) 100%, 0 100%);
    transition: color .2s, background .2s, box-shadow .2s, transform .2s;
}
.gh-chip:hover { color: #FFFFFF; transform: translateY(-2px); box-shadow: inset 0 0 0 1px var(--cc); }
.gh-chip.on { color: #FFFFFF; background: color-mix(in srgb, var(--cc) 35%, #090E39); box-shadow: inset 0 0 0 1px var(--cc), 0 0 18px color-mix(in srgb, var(--cc) 40%, transparent); }
.c-legendary { --cc: #FFD76A; }
.c-epic { --cc: #8A8CFF; }
.c-rare { --cc: #4382DF; }
.c-common { --cc: #8793B7; }
.c-dup { --cc: #FFFFFF; }

.gh-filter-note { margin-left: auto; color: #5C688F; font-size: 12px; }

/* =========================================================
   PANEL + LIST
   ========================================================= */
.gh-panel {
    --c: 26px;
    position: relative;
    overflow: hidden;
    clip-path: polygon(var(--c) 0, 100% 0, 100% calc(100% - var(--c)), calc(100% - var(--c)) 100%, 0 100%, 0 var(--c));
    background: radial-gradient(ellipse at 100% 0%, rgba(70, 71, 174, .22), transparent 55%), linear-gradient(145deg, #0D1749 0%, #090E39 70%);
    box-shadow: inset 0 0 0 1px var(--g-line), inset 0 0 60px rgba(17, 46, 129, .25);
    animation: ghRise .9s cubic-bezier(.22, 1, .36, 1) .38s backwards;
}
.gh-panel::before { content: ""; position: absolute; top: 0; left: var(--c); width: 72px; height: 2px; z-index: 2; background: var(--g-bright); box-shadow: 0 0 12px var(--g-bright); }
.gh-panel::after { content: ""; position: absolute; right: var(--c); bottom: 0; width: 72px; height: 2px; z-index: 2; background: var(--g-purple); box-shadow: 0 0 12px var(--g-purple); }

.gh-panel-head { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; padding: 18px 24px; border-bottom: 1px solid var(--g-line); background: linear-gradient(90deg, rgba(17, 46, 129, .75), rgba(9, 14, 57, .3)); }
.gh-panel-title { font-size: 18px; font-weight: 900; letter-spacing: .1em; }
.gh-panel-title span { color: var(--g-cyan); text-shadow: 0 0 16px rgba(34, 228, 255, .5); }

.gh-count { display: inline-flex; align-items: baseline; gap: 8px; padding: 7px 18px 7px 12px; background: rgba(67, 130, 223, .14); border-left: 2px solid var(--g-bright); clip-path: polygon(0 0, 100% 0, calc(100% - 8px) 100%, 0 100%); }
.gh-count b { font-size: 15px; font-weight: 900; }
.gh-count span { color: var(--g-muted); font-size: 9px; letter-spacing: .14em; }

.gh-list { padding: 8px 0; }

.gh-row {
    --rc: #8793B7;
    position: relative;
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 12px 24px;
    border-bottom: 1px solid rgba(255, 255, 255, .04);
    transition: background .2s;
    animation: ghRow .5s cubic-bezier(.22, 1, .36, 1) backwards;
    animation-delay: calc(min(var(--i, 0), 14) * 40ms + .5s);
}
.gh-row:last-child { border-bottom: none; }
.gh-row::before { content: ""; position: absolute; left: 0; top: 10px; bottom: 10px; width: 3px; background: var(--rc); box-shadow: 0 0 12px var(--rc); opacity: .75; transition: opacity .2s, top .2s, bottom .2s; }
.gh-row:hover { background: linear-gradient(90deg, color-mix(in srgb, var(--rc) 16%, transparent), transparent 70%); }
.gh-row:hover::before { opacity: 1; top: 4px; bottom: 4px; }

.r-common { --rc: #8793B7; }
.r-rare { --rc: #4382DF; }
.r-epic { --rc: #8A8CFF; }
.r-legendary { --rc: #FFD76A; background: linear-gradient(90deg, rgba(255, 215, 106, .07), transparent 55%); }

.gh-art {
    position: relative;
    display: grid;
    place-items: center;
    width: 58px;
    height: 58px;
    flex: 0 0 58px;
    overflow: hidden;
    background: linear-gradient(145deg, color-mix(in srgb, var(--rc) 28%, #090E39), #090E39);
    box-shadow: inset 0 0 0 1px color-mix(in srgb, var(--rc) 70%, transparent), inset 0 0 18px color-mix(in srgb, var(--rc) 25%, transparent);
    clip-path: polygon(0 0, calc(100% - 10px) 0, 100% 10px, 100% 100%, 10px 100%, 0 calc(100% - 10px));
}
.r-legendary .gh-art { box-shadow: inset 0 0 0 1px #FFD76A, inset 0 0 22px rgba(255, 215, 106, .35); }

.gh-art-type { color: color-mix(in srgb, var(--rc) 80%, #FFFFFF); font-family: 'Orbitron', sans-serif; font-size: 16px; font-weight: 900; }
.gh-art-mark { position: absolute; top: 4px; left: 6px; z-index: 2; color: var(--rc); font-family: 'Orbitron', sans-serif; font-size: 9px; font-weight: 900; }
.gh-art img { position: absolute; inset: 0; z-index: 1; width: 100%; height: 100%; padding: 6px; object-fit: contain; filter: drop-shadow(0 3px 8px rgba(0, 0, 0, .5)); background: #090E39; }

.gh-info { min-width: 0; flex: 1; }
.gh-name { color: #FFFFFF; font-size: 13px; font-weight: 700; letter-spacing: .03em; overflow-wrap: anywhere; }

.gh-tags { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 6px; }
.gh-tag { padding: 2px 10px; font-size: 8px; font-weight: 800; letter-spacing: .14em; text-transform: uppercase; clip-path: polygon(5px 0, 100% 0, calc(100% - 5px) 100%, 0 100%); }
.tag-rarity { color: var(--rc); background: color-mix(in srgb, var(--rc) 18%, transparent); box-shadow: inset 0 -2px 0 var(--rc); }
.tag-type { color: var(--g-muted); background: rgba(135, 147, 183, .12); }

.gh-time { margin-top: 6px; color: #6C7AA6; font-size: 13px; }
.gh-time i { margin: 0 6px; color: var(--g-bright); font-style: normal; }

.gh-side { flex-shrink: 0; }
.gh-badge { display: inline-block; padding: 6px 16px; font-size: 9px; font-weight: 900; letter-spacing: .16em; clip-path: polygon(8px 0, 100% 0, calc(100% - 8px) 100%, 0 100%); }
.badge-new { color: #FFFFFF; background: linear-gradient(100deg, var(--g-blue), var(--g-bright)); box-shadow: 0 0 14px rgba(67, 130, 223, .4); }
.badge-dup { color: #FFE9A8; background: rgba(255, 215, 106, .14); box-shadow: inset 0 0 0 1px rgba(255, 215, 106, .45); }

/* =========================================================
   EMPTY
   ========================================================= */
.gh-empty { padding: 56px 20px; text-align: center; }
.gh-empty-ico { display: grid; place-items: center; width: 76px; height: 76px; margin: 0 auto 18px; color: var(--g-bright); font-family: 'Orbitron', sans-serif; font-size: 30px; font-weight: 900; background: linear-gradient(145deg, rgba(67, 130, 223, .3), rgba(17, 46, 129, .6)); clip-path: polygon(25% 0, 75% 0, 100% 50%, 75% 100%, 25% 100%, 0 50%); animation: ghFloat 3s ease-in-out infinite; }
.gh-empty h2 { font-size: 18px; font-weight: 900; letter-spacing: .12em; }
.gh-empty p { margin-top: 6px; color: var(--g-muted); font-size: 15px; }

.gh-cta {
    position: relative;
    overflow: hidden;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    margin-top: 22px;
    padding: 14px 34px;
    color: #FFFFFF;
    font-size: 11px;
    font-weight: 900;
    letter-spacing: .2em;
    text-decoration: none;
    background: linear-gradient(100deg, #1B4FD8, var(--g-purple));
    clip-path: polygon(14px 0, 100% 0, calc(100% - 14px) 100%, 0 100%);
    transition: filter .2s, transform .2s;
}
.gh-cta svg { width: 16px; height: 16px; }
.gh-cta:hover { filter: brightness(1.2); transform: translateY(-2px); }
.gh-cta::after { content: ""; position: absolute; top: 0; bottom: 0; left: -60%; width: 40%; background: linear-gradient(100deg, transparent, rgba(255, 255, 255, .3), transparent); transform: skewX(-20deg); animation: ghShine 4s ease-in-out infinite; }

/* =========================================================
   PAGINATION (membungkus tampilan bawaan Laravel)
   ========================================================= */
.gh-pagination { margin-top: 22px; display: flex; justify-content: center; color: var(--g-muted); font-size: 13px; }
.gh-pagination nav { width: 100%; }
.gh-pagination nav > div:first-child { display: none; }
.gh-pagination nav > div:last-child { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; }
.gh-pagination p { color: var(--g-muted); font-size: 13px; }
.gh-pagination .relative.z-0, .gh-pagination span.relative { display: inline-flex; flex-wrap: wrap; gap: 6px; box-shadow: none; }
.gh-pagination a, .gh-pagination span[aria-disabled="true"] > span, .gh-pagination span[aria-current="page"] > span, .gh-pagination .relative > span:not([aria-current]) > span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 12px;
    color: #B7C3E6;
    font-family: 'Orbitron', sans-serif;
    font-size: 11px;
    font-weight: 700;
    text-decoration: none;
    background: rgba(9, 14, 57, .9);
    border: none;
    border-radius: 0;
    box-shadow: inset 0 0 0 1px rgba(67, 130, 223, .28);
    clip-path: polygon(8px 0, 100% 0, calc(100% - 8px) 100%, 0 100%);
    transition: color .2s, background .2s, box-shadow .2s;
}
.gh-pagination a:hover { color: #FFFFFF; background: rgba(17, 46, 129, .8); box-shadow: inset 0 0 0 1px var(--g-bright); }
.gh-pagination span[aria-current="page"] > span { color: #FFFFFF; background: linear-gradient(100deg, var(--g-blue), var(--g-bright), var(--g-purple)); box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .3), 0 0 16px rgba(67, 130, 223, .4); }
.gh-pagination span[aria-disabled="true"] > span { opacity: .35; }
.gh-pagination svg { width: 16px; height: 16px; }

/* =========================================================
   KEYFRAMES
   ========================================================= */
@keyframes ghRise { from { opacity: 0; transform: translateY(22px); } to { opacity: 1; transform: translateY(0); } }
@keyframes ghRow { from { opacity: 0; transform: translateX(-14px); } to { opacity: 1; transform: translateX(0); } }
@keyframes ghPing { 0% { transform: scale(1); opacity: .7; } 100% { transform: scale(3.2); opacity: 0; } }
@keyframes ghShimmer { 0%, 20% { background-position: 120% 0; } 80%, 100% { background-position: -20% 0; } }
@keyframes ghLine { from { background-position: 0 0; } to { background-position: 200% 0; } }
@keyframes ghShine { 0%, 55% { translate: 0 0; } 100% { translate: 520% 0; } }
@keyframes ghFloat { 0%, 100% { translate: 0 0; } 50% { translate: 0 -8px; } }
@keyframes ghGrid { from { transform: translateY(0); } to { transform: translateY(56px); } }
@keyframes ghTwinkle { from { opacity: .25; } to { opacity: .7; } }
@keyframes ghOrb { from { translate: 0 0; } to { translate: 70px 50px; } }
@keyframes ghGlitch {
    0%, 90%, 100% { opacity: 0; }
    91% { opacity: .85; clip-path: inset(12% 0 62% 0); translate: -4px 0; }
    93% { opacity: .85; clip-path: inset(58% 0 18% 0); translate: 4px 0; }
    95% { opacity: .85; clip-path: inset(30% 0 40% 0); translate: -2px 0; }
    97% { opacity: 0; }
}

/* =========================================================
   RESPONSIVE
   ========================================================= */
@media (max-width: 760px) {
    .gh-stats { grid-template-columns: 1fr; }
    .gh-stat { padding: 14px 16px; }
    .gh-stat-value { font-size: 26px; }
    .gh-filter-note { display: none; }
    .gh-panel-head { padding: 14px 16px; }
    .gh-row { gap: 12px; padding: 12px 16px; }
    .gh-art { width: 50px; height: 50px; flex-basis: 50px; }
    .gh-side { align-self: flex-start; }
    .gh-badge { padding: 5px 12px; font-size: 8px; }
    .gh-time i { display: none; }
    .gh-time { display: flex; flex-direction: column; }
}

@media (prefers-reduced-motion: reduce) {
    .gh-page *, .gh-page *::before, .gh-page *::after {
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: .01ms !important;
    }
}
</style>

@endsection