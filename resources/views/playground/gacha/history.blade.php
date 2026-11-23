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
/* =========================================================
   BASE — CYBER NEON THEME
   ========================================================= */
.gh-page {
    --g-cyan: #22D3EE;
    --g-bright: #22D3EE;
    --g-purple: #C084FC;
    --g-muted: var(--muted);
    --g-line: var(--line-strong);
    --g-gold: #FFD76A;
    
    position: relative;
    isolation: isolate;
    width: 100%;
    padding-bottom: 40px;
    color: var(--text);
    font-family: 'Anta', system-ui, sans-serif;
    font-weight: 400;
}

.gh-page, .gh-page * { box-sizing: border-box; }

.gh-title, .gh-kicker, .gh-stat-value, .gh-stat-label, .gh-panel-title, .gh-count,
.gh-chip, .gh-badge, .gh-back, .gh-cta, .gh-name, .gh-tag, .gh-empty h2 {
    font-family: 'Iceberg', serif;
    font-weight: 400;
    -webkit-text-stroke: .35px currentColor;
}

/* =========================================================
   BACK + HEADER
   ========================================================= */
.gh-back {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding: 8px 22px 8px 14px;
    color: var(--cyan);
    font-size: 10px;
    letter-spacing: .18em;
    text-decoration: none;
    background: var(--panel);
    border-left: 2px solid var(--cyan);
    clip-path: polygon(0 0, 100% 0, calc(100% - 10px) 100%, 0 100%);
    transition: color .2s, transform .2s, background .2s;
    animation: ghRise .6s cubic-bezier(.22, 1, .36, 1) backwards;
}
.gh-back svg { width: 16px; height: 16px; transition: transform .25s; }
.gh-back:hover { color: var(--text); transform: translateX(-3px); background: var(--bg-soft); }
.gh-back:hover svg { transform: translateX(-4px); }

.gh-header { margin-bottom: 24px; animation: ghRise .8s cubic-bezier(.22, 1, .36, 1) .06s backwards; }

.gh-kicker {
    display: flex;
    align-items: center;
    gap: 9px;
    margin-bottom: 12px;
    color: var(--cyan);
    font-size: 10px;
    letter-spacing: .22em;
}

.gh-dot {
    position: relative;
    width: 7px;
    height: 7px;
    flex: 0 0 7px;
    transform: rotate(45deg);
    background: var(--cyan);
    box-shadow: 0 0 12px var(--cyan);
}
.gh-dot::after { content: ""; position: absolute; inset: 0; background: var(--cyan); animation: ghPing 2.2s ease-out infinite; }

.gh-title {
    position: relative;
    display: inline-block;
    font-size: clamp(30px, 5vw, 54px);
    line-height: 1.15;
    letter-spacing: .05em;
    text-transform: uppercase;
    transform: skewX(-7deg);
    transform-origin: left bottom;
    text-shadow: 0 0 34px var(--glow);
}

.gh-title span {
    background: linear-gradient(100deg, var(--cyan) 25%, #FFFFFF 50%, var(--cyan) 75%);
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
    color: var(--cyan);
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
    background: linear-gradient(90deg, var(--cyan), var(--accent-text), var(--cyan));
    background-size: 200% 100%;
    box-shadow: 0 0 16px var(--cyan);
    animation: ghLine 4s linear infinite;
}

.gh-subtitle { margin-top: 10px; color: var(--muted); font-size: 15px; }

/* =========================================================
   STATS
   ========================================================= */
.gh-stats { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; margin-bottom: 24px; }

.gh-stat {
    --c: 18px;
    --sc: var(--cyan);
    position: relative;
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 18px 20px;
    clip-path: polygon(var(--c) 0, 100% 0, 100% calc(100% - var(--c)), calc(100% - var(--c)) 100%, 0 100%, 0 var(--c));
    background: var(--panel);
    -webkit-backdrop-filter: blur(10px);
            backdrop-filter: blur(10px);
    box-shadow: inset 0 0 0 1px var(--line-strong);
    transition: transform .3s cubic-bezier(.22, 1, .36, 1), box-shadow .3s;
    animation: ghRise .8s cubic-bezier(.22, 1, .36, 1) backwards;
}
.gh-stat:nth-child(1) { animation-delay: .12s; }
.gh-stat:nth-child(2) { animation-delay: .2s; }
.gh-stat:nth-child(3) { animation-delay: .28s; }

.gh-stat::before { content: ""; position: absolute; top: 0; left: var(--c); width: 56px; height: 2px; background: var(--sc); box-shadow: 0 0 12px var(--sc); }
.gh-stat:hover { transform: translateY(-3px); box-shadow: inset 0 0 0 1px var(--sc), inset 0 0 50px var(--glow); }

.gh-stat-unique { --sc: var(--g-cyan); }
.gh-stat-dup { --sc: var(--g-gold); }

.gh-stat-ico {
    display: grid;
    place-items: center;
    width: 50px;
    height: 50px;
    flex: 0 0 50px;
    color: var(--sc);
    background: var(--bg-soft);
    box-shadow: inset 0 0 0 1px var(--line-strong);
    clip-path: polygon(25% 0, 75% 0, 100% 50%, 75% 100%, 25% 100%, 0 50%);
}
.gh-stat-ico svg { width: 24px; height: 24px; fill: currentColor; }
.gh-stat-ico svg[fill="none"] { fill: none; }

.gh-stat-label { color: var(--faint); font-size: 9px; letter-spacing: .2em; text-transform: uppercase; }
.gh-stat-value { margin-top: 3px; color: var(--sc); font-size: 30px; line-height: 1.1; font-variant-numeric: tabular-nums; text-shadow: 0 0 20px var(--glow); }
.gh-stat:first-child .gh-stat-value { color: var(--text); }

.gh-meter { height: 5px; margin-top: 8px; background: var(--line); }
.gh-meter div { height: 100%; background: var(--g-gold); box-shadow: 0 0 8px var(--g-gold); }

/* =========================================================
   FILTER
   ========================================================= */
.gh-filters { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; margin-bottom: 16px; animation: ghRise .8s cubic-bezier(.22, 1, .36, 1) .34s backwards; }

.gh-chip {
    --cc: var(--cyan);
    padding: 9px 20px;
    color: var(--muted);
    font-size: 10px;
    letter-spacing: .12em;
    text-transform: uppercase;
    cursor: pointer;
    background: var(--panel);
    border: none;
    box-shadow: inset 0 0 0 1px var(--line-strong);
    clip-path: polygon(10px 0, 100% 0, calc(100% - 10px) 100%, 0 100%);
    transition: color .2s, background .2s, box-shadow .2s, transform .2s;
}
.gh-chip:hover { color: var(--text); transform: translateY(-2px); box-shadow: inset 0 0 0 1px var(--cc); }
.gh-chip.on { color: var(--cyan-ink); background: var(--cc); box-shadow: inset 0 0 0 1px var(--cc), 0 0 18px var(--glow); }
.c-legendary { --cc: var(--g-gold); }
.c-epic { --cc: var(--g-purple); }
.c-rare { --cc: var(--cyan); }
.c-common { --cc: var(--faint); }
.c-dup { --cc: var(--text); }

.gh-filter-note { margin-left: auto; color: var(--faint); font-size: 12px; }

/* =========================================================
   PANEL + LIST
   ========================================================= */
.gh-panel {
    --c: 26px;
    position: relative;
    overflow: hidden;
    clip-path: polygon(var(--c) 0, 100% 0, 100% calc(100% - var(--c)), calc(100% - var(--c)) 100%, 0 100%, 0 var(--c));
    background: var(--panel);
    -webkit-backdrop-filter: blur(10px);
            backdrop-filter: blur(10px);
    box-shadow: inset 0 0 0 1px var(--line-strong);
    animation: ghRise .9s cubic-bezier(.22, 1, .36, 1) .38s backwards;
}
.gh-panel::before { content: ""; position: absolute; top: 0; left: var(--c); width: 72px; height: 2px; z-index: 2; background: var(--cyan); box-shadow: 0 0 12px var(--glow); }
.gh-panel::after { content: ""; position: absolute; right: var(--c); bottom: 0; width: 72px; height: 2px; z-index: 2; background: var(--cyan); box-shadow: 0 0 12px var(--glow); opacity: 0.5; }

.gh-panel-head { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; padding: 18px 24px; border-bottom: 1px solid var(--line-strong); background: var(--bg-soft); }
.gh-panel-title { font-size: 18px; letter-spacing: .1em; }
.gh-panel-title span { color: var(--cyan); text-shadow: 0 0 16px var(--glow); }

.gh-count { display: inline-flex; align-items: baseline; gap: 8px; padding: 7px 18px 7px 12px; background: var(--bg-soft); border-left: 2px solid var(--cyan); clip-path: polygon(0 0, 100% 0, calc(100% - 8px) 100%, 0 100%); }
.gh-count b { font-size: 15px; color: var(--text); }
.gh-count span { color: var(--faint); font-size: 9px; letter-spacing: .14em; }

.gh-list { padding: 8px 0; }

.gh-row {
    --rc: var(--faint);
    position: relative;
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 14px 24px;
    border-bottom: 1px solid var(--line);
    transition: background .2s;
    animation: ghRow .5s cubic-bezier(.22, 1, .36, 1) backwards;
    animation-delay: calc(min(var(--i, 0), 14) * 40ms + .5s);
}
.gh-row:last-child { border-bottom: none; }
.gh-row::before { content: ""; position: absolute; left: 0; top: 10px; bottom: 10px; width: 3px; background: var(--rc); box-shadow: 0 0 12px var(--rc); opacity: .75; transition: opacity .2s, top .2s, bottom .2s; }
.gh-row:hover { background: var(--bg-soft); }
.gh-row:hover::before { opacity: 1; top: 4px; bottom: 4px; }

.r-common { --rc: var(--faint); }
.r-rare { --rc: var(--cyan); }
.r-epic { --rc: var(--g-purple); }
.r-legendary { --rc: var(--g-gold); background: rgba(255, 215, 106, 0.03); }

.gh-art {
    position: relative;
    display: grid;
    place-items: center;
    width: 58px;
    height: 58px;
    flex: 0 0 58px;
    overflow: hidden;
    background: var(--bg-soft);
    box-shadow: inset 0 0 0 1px var(--line-strong);
    clip-path: polygon(0 0, calc(100% - 10px) 0, 100% 10px, 100% 100%, 10px 100%, 0 calc(100% - 10px));
}
.r-legendary .gh-art { box-shadow: inset 0 0 0 1px var(--g-gold), inset 0 0 22px rgba(255, 215, 106, 0.2); }

.gh-art-type { color: var(--rc); font-size: 16px; opacity: 0.8; }
.gh-art-mark { position: absolute; top: 4px; left: 6px; z-index: 2; color: var(--rc); font-size: 9px; }
.gh-art img { position: absolute; inset: 0; z-index: 1; width: 100%; height: 100%; padding: 6px; object-fit: contain; filter: drop-shadow(0 3px 8px rgba(0, 0, 0, 0.5)); background: var(--bg); }

.gh-info { min-width: 0; flex: 1; }
.gh-name { color: var(--text); font-size: 13px; letter-spacing: .03em; overflow-wrap: anywhere; }

.gh-tags { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 6px; }
.gh-tag { padding: 2px 10px; font-size: 8px; letter-spacing: .14em; text-transform: uppercase; clip-path: polygon(5px 0, 100% 0, calc(100% - 5px) 100%, 0 100%); }
.tag-rarity { color: var(--rc); background: color-mix(in srgb, var(--rc) 15%, transparent); box-shadow: inset 0 -2px 0 var(--rc); }
.tag-type { color: var(--faint); background: var(--bg-soft); }

.gh-time { margin-top: 6px; color: var(--faint); font-size: 12px; }
.gh-time i { margin: 0 6px; color: var(--cyan); font-style: normal; }

.gh-side { flex-shrink: 0; }
.gh-badge { display: inline-block; padding: 6px 16px; font-size: 9px; letter-spacing: .16em; clip-path: polygon(8px 0, 100% 0, calc(100% - 8px) 100%, 0 100%); }
.badge-new { color: var(--cyan-ink); background: var(--cyan); box-shadow: 0 0 14px var(--glow); }
.badge-dup { color: var(--g-gold); background: transparent; box-shadow: inset 0 0 0 1px var(--g-gold); }

/* =========================================================
   EMPTY
   ========================================================= */
.gh-empty { padding: 56px 20px; text-align: center; }
.gh-empty-ico { display: grid; place-items: center; width: 76px; height: 76px; margin: 0 auto 18px; color: var(--cyan); font-size: 30px; background: var(--bg-soft); box-shadow: inset 0 0 0 1px var(--line-strong); clip-path: polygon(25% 0, 75% 0, 100% 50%, 75% 100%, 25% 100%, 0 50%); animation: ghFloat 3s ease-in-out infinite; }
.gh-empty h2 { font-size: 18px; letter-spacing: .12em; }
.gh-empty p { margin-top: 6px; color: var(--muted); font-size: 15px; }

.gh-cta {
    position: relative;
    overflow: hidden;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    margin-top: 22px;
    padding: 14px 34px;
    color: var(--cyan-ink);
    font-size: 11px;
    letter-spacing: .2em;
    text-decoration: none;
    background: var(--cyan);
    clip-path: polygon(14px 0, 100% 0, calc(100% - 14px) 100%, 0 100%);
    transition: filter .2s, transform .2s;
}
.gh-cta svg { width: 16px; height: 16px; }
.gh-cta:hover { filter: brightness(1.2); transform: translateY(-2px); }
.gh-cta::after { content: ""; position: absolute; top: 0; bottom: 0; left: -60%; width: 40%; background: linear-gradient(100deg, transparent, rgba(255, 255, 255, 0.4), transparent); transform: skewX(-20deg); animation: ghShine 4s ease-in-out infinite; }

/* =========================================================
   PAGINATION (Custom Laravel Style)
   ========================================================= */
.gh-pagination { margin-top: 22px; display: flex; justify-content: center; color: var(--muted); font-size: 13px; }
.gh-pagination nav { width: 100%; }
.gh-pagination nav > div:first-child { display: none; }
.gh-pagination nav > div:last-child { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; }
.gh-pagination p { color: var(--muted); font-size: 13px; }
.gh-pagination .relative.z-0, .gh-pagination span.relative { display: inline-flex; flex-wrap: wrap; gap: 6px; box-shadow: none; }
.gh-pagination a, .gh-pagination span[aria-disabled="true"] > span, .gh-pagination span[aria-current="page"] > span, .gh-pagination .relative > span:not([aria-current]) > span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 12px;
    color: var(--muted);
    font-size: 11px;
    text-decoration: none;
    background: var(--panel);
    border: none;
    border-radius: 0;
    box-shadow: inset 0 0 0 1px var(--line-strong);
    clip-path: polygon(8px 0, 100% 0, calc(100% - 8px) 100%, 0 100%);
    transition: color .2s, background .2s, box-shadow .2s;
}
.gh-pagination a:hover { color: var(--text); background: var(--bg-soft); box-shadow: inset 0 0 0 1px var(--cyan); }
.gh-pagination span[aria-current="page"] > span { color: var(--cyan-ink); background: var(--cyan); box-shadow: inset 0 0 0 1px var(--cyan), 0 0 16px var(--glow); }
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
@keyframes ghGlitch {
    0%, 90%, 100% { opacity: 0; }
    91% { opacity: .85; clip-path: inset(12% 0 62% 0); translate: -4px 0; }
    93% { opacity: .85; clip-path: inset(58% 0 18% 0); translate: 4px 0; }
    95% { opacity: .85; clip-path: inset(30% 0 40% 0); translate: -2px 0; }
    97% { opacity: 0; }
}

/* =========================================================
   RESPONSIVE (DIPERBAIKI UNTUK HP)
   ========================================================= */
@media (max-width: 760px) {
    .gh-stats { grid-template-columns: 1fr; gap: 10px; margin-bottom: 16px; }
    .gh-stat { padding: 14px 16px; }
    .gh-stat-value { font-size: 24px; }
    .gh-filter-note { display: none; }
    .gh-panel-head { padding: 14px 16px; }
    .gh-row { gap: 12px; padding: 12px 16px; }
    
    /* Gambar item menyesuaikan */
    .gh-art { width: 50px; height: 50px; flex-basis: 50px; }
    .gh-art-type { font-size: 14px; }
    
    /* Teks waktu dan badge */
    .gh-time i { display: none; }
    .gh-time { display: flex; flex-direction: column; font-size: 10px; gap: 2px; }
    .gh-side { align-self: flex-start; }
    .gh-badge { padding: 4px 10px; font-size: 7px; }
    
    /* Filter chip lebih kecil */
    .gh-chip { padding: 7px 14px; font-size: 9px; }
    
    /* Pagination lebih rapi */
    .gh-pagination nav > div:last-child { flex-direction: column; justify-content: center; }
    .gh-pagination p { margin-bottom: 8px; }
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