@extends('playground.layouts.app')

@section('title', 'My Decks — DIGANOVA Playground')

@section('content')

@php
    $readyCount  = $decks->filter(fn ($d) => $d->cards->sum('pivot.quantity') === 10)->count();
    $activeDeck  = $decks->firstWhere('is_active', true);
@endphp

<div class="dk-page relative isolate space-y-8 text-[var(--text)]">

    {{-- BACKGROUND --}}
    <div class="dk-bg" aria-hidden="true">
        <div class="dk-bg-grid"></div>
        <div class="dk-bg-stars"></div>
        <span class="dk-orb dk-orb-1"></span>
        <span class="dk-orb dk-orb-2"></span>
        <span class="dk-orb dk-orb-3"></span>
        <div class="dk-bg-scan"></div>
    </div>

    {{-- HUD BAR --}}
    <div class="dk-hud dk-rise flex flex-wrap gap-2">
        <span class="dk-chip dk-online"><i></i>ONLINE</span>
        <span class="dk-chip">PLAYER <b>{{ strtoupper(auth()->user()->name ?? 'PLAYER') }}</b></span>
        <span class="dk-chip">DECKS <b>{{ $decks->count() }}</b></span>
        <span class="dk-chip">READY <b>{{ $readyCount }}</b></span>
        <span class="dk-chip">ACTIVE <b>{{ $activeDeck ? $activeDeck->name : '—' }}</b></span>
    </div>

    {{-- HERO --}}
    <section class="dk-hero dk-rise" style="--i:1">
        <div class="dk-ring" aria-hidden="true"></div>
        <div class="dk-ring dk-ring-2" aria-hidden="true"></div>

        <div class="relative flex flex-col gap-8 p-6 sm:p-8 lg:flex-row lg:items-center lg:justify-between lg:p-10">
            <div class="max-w-2xl">
                <div class="dk-kicker">
                    <span class="dk-dot"></span>
                    PLAYER LOADOUT SYSTEM
                </div>

                <h1 class="dk-title" data-text="MY DECKS">
                    MY <span>DECKS</span>
                </h1>

                <p class="dk-lead">
                    Bangun kombinasi kartu terbaikmu. Atur strategi, temukan sinergi, dan persiapkan loadout untuk menghadapi arena.
                </p>

                <div class="dk-tags">
                    <span>DIGANOVA COLLECTION</span>
                    <i></i>
                    <span>BATTLE PREPARATION</span>
                </div>
            </div>

            <div class="flex flex-col items-start gap-5 sm:flex-row sm:items-center lg:flex-col lg:items-end">
                <div class="dk-stats flex-wrap">
                    <div class="dk-stat">
                        <span>DECKS</span>
                        <b>{{ str_pad((string) $decks->count(), 2, '0', STR_PAD_LEFT) }}</b>
                    </div>
                    <div class="dk-stat">
                        <span>READY</span>
                        <b>{{ str_pad((string) $readyCount, 2, '0', STR_PAD_LEFT) }}</b>
                    </div>
                    <div class="dk-stat dk-stat-gold">
                        <span>ACTIVE</span>
                        <b>{{ $activeDeck ? str_pad((string) $activeDeck->slot, 2, '0', STR_PAD_LEFT) : '--' }}</b>
                    </div>
                </div>

                <a href="{{ route('play.decks.create') }}" class="dk-btn dk-btn-cyan dk-btn-lg group/create mt-2 lg:mt-0 w-full sm:w-auto">
                    <svg class="h-4 w-4 transition-transform duration-300 group-hover/create:rotate-90"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    <span>Buat Deck Baru</span>
                    <svg class="h-3.5 w-3.5 transition-transform group-hover/create:translate-x-1"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M13 6l6 6-6 6"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>


    {{-- COLLECTION --}}
    <section>
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between dk-rise" style="--i:2">
            <div>
                <div class="dk-kicker">
                    <span class="dk-dot"></span>
                    COLLECTION / 01
                </div>
                <h2 class="dk-h2">
                    Koleksi <span>Deck</span>
                </h2>
                <p class="dk-sub">
                    Setiap kartu punya peran. Susun komposisi yang tepat untuk strategimu.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <span class="dk-chip dk-chip-lg"><i class="dk-pip"></i>{{ $decks->count() }} DECK TERSIMPAN</span>
                <span class="dk-chip dk-chip-lg dk-chip-purple">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M12 3 3.5 7.5 12 12l8.5-4.5L12 3Z"/>
                        <path d="m3.5 12 8.5 4.5 8.5-4.5"/>
                    </svg>
                    10 KARTU / DECK
                </span>
            </div>
        </div>

        {{-- LEGEND TIPE KARTU --}}
        <div class="dk-legend dk-rise" style="--i:3">
            <span class="t-attack"><i></i>ATTACK</span>
            <span class="t-defense"><i></i>DEFENSE</span>
            <span class="t-other"><i></i>SUPPORT</span>
        </div>


        @if ($decks->isNotEmpty())
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 2xl:grid-cols-3">
                @foreach ($decks as $deck)
                    @php
                        $totalCards   = $deck->cards->sum('pivot.quantity');
                        $isReady      = $totalCards === 10;
                        $isActive     = $deck->is_active;
                        $previewCards = $deck->cards->take(10);
                        $previewCount = $previewCards->count();

                        $atkCount = $deck->cards->where('type', 'attack')->sum('pivot.quantity');
                        $defCount = $deck->cards->where('type', 'defense')->sum('pivot.quantity');
                        $othCount = max(0, $totalCards - $atkCount - $defCount);
                    @endphp

                    <article class="dk-card dk-rise {{ $isActive ? 'is-active' : '' }} {{ $isReady ? 'is-ready' : '' }}"
                             style="--i: {{ $loop->index + 3 }}">

                        <div class="dk-card-glow"></div>

                        @if ($isActive)
                            <div class="dk-ribbon">
                                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.5 14.9 8l6.1.9-4.4 4.3 1 6.1-5.6-3-5.6 3 1-6.1L3 8.9 9.1 8 12 2.5Z"/></svg>
                                EQUIPPED
                            </div>
                        @endif

                        {{-- HEADER --}}
                        <div class="relative flex items-start justify-between gap-3 p-5 pb-4">
                            <div class="flex min-w-0 items-center gap-3.5">
                                <div class="dk-hex">
                                    <small>SLOT</small>
                                    <b>{{ str_pad((string) $deck->slot, 2, '0', STR_PAD_LEFT) }}</b>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="dk-deck-name">{{ $deck->name }}</h3>
                                    <p class="dk-deck-state {{ $isReady ? 'is-ok' : '' }}">
                                        <span class="dk-state-dot"></span>
                                        {{ $isReady ? 'READY FOR BATTLE' : 'DECK IN PROGRESS' }}
                                    </p>
                                </div>
                            </div>

                            <div class="relative shrink-0 pl-2 text-right">
                                <p class="dk-count {{ $isReady ? 'is-ok' : '' }}">
                                    {{ str_pad((string) $totalCards, 2, '0', STR_PAD_LEFT) }}<span>/10</span>
                                </p>
                                <p class="dk-count-label">CARDS</p>
                            </div>
                        </div>

                        {{-- SEGMENT PROGRESS --}}
                        <div class="px-5">
                            <div class="dk-seg" aria-label="{{ $totalCards }} dari 10 kartu">
                                @for ($i = 1; $i <= 10; $i++)
                                    <i class="{{ $i <= $totalCards ? 'on' : '' }}" style="--n: {{ $i }}"></i>
                                @endfor
                            </div>
                        </div>

                        {{-- KOMPOSISI --}}
                        <div class="flex flex-wrap items-center gap-2 px-5 pt-4">
                            <span class="dk-comp t-attack"><i></i>ATK <b>{{ $atkCount }}</b></span>
                            <span class="dk-comp t-defense"><i></i>DEF <b>{{ $defCount }}</b></span>
                            <span class="dk-comp t-other"><i></i>SUP <b>{{ $othCount }}</b></span>
                        </div>

                        {{-- CARD DISPLAY --}}
                        <div class="px-5 pt-4">
                            <div class="dk-board">
                                <div class="relative grid grid-cols-5 gap-2">
                                    @foreach ($previewCards as $card)
                                        @php
                                            $tClass = match ($card->type) {
                                                'attack'  => 't-attack',
                                                'defense' => 't-defense',
                                                default   => 't-other',
                                            };
                                        @endphp

                                        <div title="{{ $card->name }}"
                                             class="dk-slot {{ $tClass }}"
                                             style="--d: {{ $loop->index * 40 }}ms">

                                            @if ($card->type === 'attack')
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="m14.5 6.5 3-3 3 3-3 3z"/>
                                                    <path d="m6.5 14.5-3 3 3 3 3-3z"/>
                                                    <path d="m15 9-6 6"/>
                                                    <path d="m5 5 4 4M15 15l4 4"/>
                                                </svg>
                                            @elseif ($card->type === 'defense')
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round">
                                                    <path d="M12 2 20 5v6c0 5-3.5 8.5-8 11-4.5-2.5-8-6-8-11V5l8-3Z"/>
                                                    <path d="m8.5 12 2.3 2.3 4.7-5"/>
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="m12 3 2.4 6.6L21 12l-6.6 2.4L12 21l-2.4-6.6L3 12l6.6-2.4L12 3Z"/>
                                                </svg>
                                            @endif

                                            <span>{{ $card->type }}</span>
                                        </div>
                                    @endforeach

                                    {{-- EMPTY SLOTS --}}
                                    @for ($i = $previewCount; $i < 10; $i++)
                                        <div class="dk-slot dk-slot-empty">
                                            <span>+</span>
                                        </div>
                                    @endfor
                                </div>

                                <div class="dk-board-foot">
                                    <span><i></i>DIGANOVA / LOADOUT</span>
                                    <span class="font-mono">{{ str_pad((string) $deck->slot, 2, '0', STR_PAD_LEFT) }} — 10</span>
                                </div>
                            </div>
                        </div>

                        {{-- STATUS NOTE --}}
                        <div class="flex flex-1 items-center justify-between gap-3 px-5 py-4">
                            <p class="dk-note">
                                {{ $isReady ? 'Semua slot kartu telah terisi.' : 'Lengkapi kartu untuk mengaktifkan deck.' }}
                            </p>

                            @if (!$isReady)
                                <span class="dk-incomplete">INCOMPLETE</span>
                            @elseif ($isActive)
                                <span class="dk-equipped">ACTIVE</span>
                            @endif
                        </div>

                        {{-- ACTION BAR --}}
                        <div class="dk-actions">
                            <a href="{{ route('play.decks.edit', $deck) }}"
                               class="dk-btn dk-btn-ghost group/edit min-w-0 flex-1">
                                <svg class="h-4 w-4 shrink-0 transition-transform group-hover/edit:-rotate-6"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 20h9"/>
                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/>
                                </svg>
                                <span class="truncate">{{ $totalCards > 0 ? 'Edit Deck' : 'Susun Deck' }}</span>
                            </a>

                            @if ($isReady && !$isActive)
                                <form method="POST" action="{{ route('play.decks.activate', $deck) }}" class="flex">
                                    @csrf
                                    <button type="submit" class="dk-btn dk-btn-cyan">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m5 12 4 4L19 6"/>
                                        </svg>
                                        Aktifkan
                                    </button>
                                </form>
                            @endif

                            @if ($totalCards > 0)
                                <form method="POST"
                                      action="{{ route('play.decks.clear', $deck) }}"
                                      class="flex"
                                      onsubmit="return confirm('Kosongkan deck ini?');">
                                    @csrf
                                    <button type="submit"
                                            aria-label="Kosongkan deck"
                                            title="Kosongkan deck"
                                            class="dk-btn dk-btn-danger group/clear">
                                        <svg class="h-4 w-4 transition-transform group-hover/clear:scale-110"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 6h18"/>
                                            <path d="M8 6V4h8v2"/>
                                            <path d="m19 6-1 14H6L5 6"/>
                                            <path d="M10 11v5M14 11v5"/>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            {{-- EMPTY COLLECTION --}}
            <div class="dk-empty dk-rise" style="--i:3">
                <div class="dk-empty-icon">
                    <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="5" y="3" width="14" height="18" rx="1"/>
                        <path d="M9 8h6M9 12h6M9 16h3"/>
                    </svg>
                </div>

                <p class="dk-kicker justify-center">LOADOUT SYSTEM / EMPTY</p>
                <h3 class="dk-h2 mt-3">Arena <span>Menantimu</span></h3>
                <p class="mx-auto mt-3 max-w-sm text-sm leading-6 text-[var(--muted)]">
                    Koleksi deck-mu masih kosong. Mulai bangun strategi pertamamu dan siapkan kartu untuk pertempuran.
                </p>

                <a href="{{ route('play.decks.create') }}" class="dk-btn dk-btn-cyan dk-btn-lg group/create mt-7">
                    <svg class="h-4 w-4 transition-transform group-hover/create:rotate-90"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    Buat Deck Pertama
                    <svg class="h-3.5 w-3.5 transition-transform group-hover/create:translate-x-1"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M13 6l6 6-6 6"/>
                    </svg>
                </a>
            </div>
        @endif
    </section>


    {{-- STRATEGY GUIDE --}}
    <section class="dk-guide dk-rise" style="--i:4">
        <div class="flex flex-col gap-5 p-5 sm:p-6 lg:flex-row lg:items-start">
            <div class="flex items-start gap-3 lg:w-56 lg:shrink-0">
                <div class="dk-guide-icon">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 11v5M12 8h.01"/>
                    </svg>
                </div>
                <div>
                    <p class="dk-kicker !mb-0">INTEL / 001</p>
                    <h3 class="dk-guide-title">Panduan Deck</h3>
                    <p class="mt-1 text-[13px] leading-5 text-[var(--faint)]">
                        Ketahui aturan sebelum memasuki arena.
                    </p>
                </div>
            </div>

            <div class="grid flex-1 grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
                <div class="dk-tip">
                    <div class="flex items-center justify-between">
                        <span class="dk-tip-no">01</span>
                        <svg class="h-4 w-4 dk-tip-ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <rect x="5" y="3" width="14" height="18" rx="1"/>
                            <path d="M9 8h6M9 12h6"/>
                        </svg>
                    </div>
                    <p>Setiap deck harus berisi tepat <strong>10 kartu</strong>.</p>
                </div>

                <div class="dk-tip">
                    <div class="flex items-center justify-between">
                        <span class="dk-tip-no">02</span>
                        <svg class="h-4 w-4 dk-tip-ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M8 12h8M12 8v8"/>
                        </svg>
                    </div>
                    <p>Semua 20 kartu tersedia bagi pemain. <strong>Free-to-win.</strong></p>
                </div>

                <div class="dk-tip">
                    <div class="flex items-center justify-between">
                        <span class="dk-tip-no">03</span>
                        <svg class="h-4 w-4 dk-tip-ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3 3.5 7.5 12 12l8.5-4.5L12 3Z"/>
                            <path d="m3.5 12 8.5 4.5 8.5-4.5"/>
                        </svg>
                    </div>
                    <p>Hanya <strong>1 deck</strong> yang dapat aktif saat battle.</p>
                </div>

                <div class="dk-tip">
                    <div class="flex items-center justify-between">
                        <span class="dk-tip-no">04</span>
                        <svg class="h-4 w-4 dk-tip-ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16v16H4z"/>
                            <path d="m9 9 6 6m0-6-6 6"/>
                        </svg>
                    </div>
                    <p>Kartu tidak boleh <strong>diduplikasi</strong> dalam satu deck.</p>
                </div>
            </div>
        </div>
    </section>

</div>

<style>
/* =========================================================
   MY DECKS — CYBER THEME (Adaptasi Kode Kiri)
   ========================================================= */
.dk-page {
    --navy: var(--bg-soft);
    --blue: var(--panel);
    --purple: var(--line-strong);
    --bright: var(--cyan);
    --cyan: #22D3EE;
    --gold: #FFD76A;
    --pink: #FF4FD8;
    --muted: var(--muted);
    --line: var(--line);
    
    font-family: 'Anta', system-ui, sans-serif;
    font-weight: 400;
    padding-bottom: 20px;
}

.dk-page, .dk-page * { box-sizing: border-box; }
.dk-page button, .dk-page a { font-family: inherit; }

/* Font Judul menggunakan Iceberg */
.dk-title, .dk-h2, .dk-chip b, .dk-btn, .dk-hex b, .dk-count, .dk-stat b,
.dk-deck-name, .dk-tip-no, .dk-guide-title, .dk-ribbon, .dk-hex small {
    font-family: 'Iceberg', serif;
    font-weight: 400;
    -webkit-text-stroke: .4px currentColor;
}

/* ---------- Background ---------- */
.dk-bg { position: fixed; inset: 0; z-index: -1; overflow: hidden; pointer-events: none; background: transparent; }

.dk-bg-grid {
    position: absolute;
    inset: -60px;
    background-image:
        linear-gradient(var(--grid) 1px, transparent 1px),
        linear-gradient(90deg, var(--grid) 1px, transparent 1px);
    background-size: 45px 45px;
    -webkit-mask-image: radial-gradient(ellipse at 50% 25%, #000 0%, transparent 75%);
            mask-image: radial-gradient(ellipse at 50% 25%, #000 0%, transparent 75%);
    animation: dkGrid 14s linear infinite;
}

.dk-bg-stars {
    position: absolute;
    inset: 0;
    background-image:
        radial-gradient(1px 1px at 20px 30px, #FFFFFF, transparent),
        radial-gradient(1.5px 1.5px at 90px 90px, #22D3EE, transparent),
        radial-gradient(1px 1px at 160px 40px, #FFFFFF, transparent),
        radial-gradient(1.5px 1.5px at 120px 150px, #22D3EE, transparent),
        radial-gradient(1px 1px at 40px 170px, #FFFFFF, transparent);
    background-size: 210px 210px;
    opacity: .3;
    animation: dkTwinkle 5s ease-in-out infinite alternate;
}

.dk-orb { position: absolute; border-radius: 50%; filter: blur(90px); }
.dk-orb-1 { width: 440px; height: 440px; left: -130px; top: -90px; background: var(--glow); animation: dkDrift 16s ease-in-out infinite alternate; }
.dk-orb-2 { width: 380px; height: 380px; right: -110px; top: 32%; background: var(--glow); animation: dkDrift 20s ease-in-out -6s infinite alternate-reverse; }
.dk-orb-3 { width: 340px; height: 340px; left: 32%; bottom: -170px; background: var(--glow); animation: dkDrift 18s ease-in-out -3s infinite alternate; }

.dk-bg-scan { position: absolute; inset: 0; background: repeating-linear-gradient(0deg, rgba(255, 255, 255, .01) 0 1px, transparent 1px 3px); }

/* ---------- Entrance ---------- */
.dk-rise { animation: dkRise .8s cubic-bezier(.22, 1, .36, 1) backwards; animation-delay: calc(var(--i, 0) * 90ms); }

/* ---------- HUD chips ---------- */
.dk-hud { display: flex; flex-wrap: wrap; gap: 8px; }

.dk-chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 18px 6px 12px;
    font-size: 10px;
    letter-spacing: 1.6px;
    color: var(--muted);
    background: var(--panel);
    border-left: 2px solid var(--cyan);
    clip-path: polygon(0 0, 100% 0, calc(100% - 10px) 100%, 0 100%);
}

.dk-chip b { color: var(--text); font-size: 11px; letter-spacing: 1px; max-width: 160px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.dk-online { color: var(--cyan); }
.dk-online i { width: 7px; height: 7px; border-radius: 50%; background: var(--cyan); box-shadow: 0 0 10px var(--cyan); animation: dkBlink 1.6s ease-in-out infinite; }
.dk-chip-lg { padding: 9px 20px 9px 14px; font-size: 12px; }
.dk-chip-purple { border-left-color: var(--cyan); background: var(--panel); }
.dk-pip { width: 6px; height: 6px; transform: rotate(45deg); background: var(--cyan); box-shadow: 0 0 8px var(--cyan); }

/* ---------- Hero ---------- */
.dk-hero {
    --c: 36px;
    position: relative;
    isolation: isolate;
    overflow: hidden;
    clip-path: polygon(var(--c) 0, 100% 0, 100% calc(100% - var(--c)), calc(100% - var(--c)) 100%, 0 100%, 0 var(--c));
    background:
        linear-gradient(to bottom right, transparent 50%, var(--cyan) 50%, var(--cyan) calc(50% + 2px), transparent calc(50% + 2px)) left top / var(--c) var(--c) no-repeat,
        linear-gradient(to top left, transparent 50%, var(--cyan) 50%, var(--cyan) calc(50% + 2px), transparent calc(50% + 2px)) right bottom / var(--c) var(--c) no-repeat,
        radial-gradient(ellipse at 100% 0%, var(--glow), transparent 55%),
        var(--panel);
    background-origin: border-box;
    box-shadow: inset 0 0 0 1px var(--line), inset 0 0 80px var(--glow);
}

.dk-hero::before {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    width: 42%;
    height: 2px;
    z-index: 1;
    background: linear-gradient(90deg, var(--cyan), transparent);
}

.dk-hero::after {
    content: "";
    position: absolute;
    inset: 0;
    z-index: -1;
    opacity: .05;
    background-image: linear-gradient(var(--grid) 1px, transparent 1px), linear-gradient(90deg, var(--grid) 1px, transparent 1px);
    background-size: 30px 30px;
}

.dk-ring {
    position: absolute;
    right: -70px;
    top: -80px;
    width: 340px;
    height: 340px;
    border-radius: 50%;
    pointer-events: none;
    z-index: -1;
    background: repeating-conic-gradient(var(--cyan) 0deg 1deg, transparent 1deg 9deg);
    -webkit-mask-image: radial-gradient(circle, transparent 66%, #000 67%, #000 70%, transparent 71%);
            mask-image: radial-gradient(circle, transparent 66%, #000 67%, #000 70%, transparent 71%);
    opacity: .3;
    animation: dkSpin 36s linear infinite;
}

.dk-ring-2 {
    right: -20px;
    top: -30px;
    width: 230px;
    height: 230px;
    opacity: .2;
    animation-direction: reverse;
    animation-duration: 24s;
}

.dk-kicker {
    display: flex;
    align-items: center;
    gap: 9px;
    margin-bottom: 12px;
    color: var(--cyan);
    font-size: 10px;
    letter-spacing: 3px;
}

.dk-dot { position: relative; width: 7px; height: 7px; border-radius: 50%; background: var(--cyan); box-shadow: 0 0 12px var(--cyan); }
.dk-dot::after { content: ""; position: absolute; inset: 0; border-radius: 50%; background: var(--cyan); animation: dkRingPulse 2.2s ease-out infinite; }

.dk-title {
    position: relative;
    font-size: clamp(38px, 6vw, 68px);
    line-height: 1;
    letter-spacing: 4px;
    transform: skewX(-7deg);
    transform-origin: left bottom;
    text-shadow: 0 0 30px var(--glow);
}

.dk-title span {
    background: linear-gradient(100deg, var(--cyan) 20%, #FFFFFF 50%, var(--cyan) 80%);
    background-size: 220% 100%;
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: dkShimmer 5s ease-in-out infinite;
}

.dk-title::before {
    content: attr(data-text);
    position: absolute;
    left: 0;
    top: 0;
    color: var(--cyan);
    mix-blend-mode: screen;
    opacity: 0;
    pointer-events: none;
    animation: dkGlitch 5s steps(1) infinite;
}

.dk-title::after {
    content: "";
    display: block;
    width: 110px;
    height: 4px;
    margin-top: 14px;
    background: linear-gradient(90deg, var(--cyan), var(--accent-text), var(--cyan));
    background-size: 200% 100%;
    box-shadow: 0 0 16px var(--cyan);
    animation: dkLine 4s linear infinite;
}

.dk-lead { margin-top: 18px; max-width: 520px; font-size: 15px; line-height: 1.55; color: var(--muted); }

.dk-tags { margin-top: 20px; display: flex; flex-wrap: wrap; align-items: center; gap: 12px; font-size: 10px; letter-spacing: 2.5px; color: var(--faint); }
.dk-tags i { width: 5px; height: 5px; transform: rotate(45deg); background: var(--cyan); box-shadow: 0 0 8px var(--cyan); }

.dk-stats { display: flex; gap: 10px; }

.dk-stat {
    min-width: 84px;
    padding: 10px 16px 10px 14px;
    border-left: 2px solid var(--cyan);
    background: var(--panel);
    clip-path: polygon(0 0, 100% 0, calc(100% - 10px) 100%, 0 100%);
}

.dk-stat span { display: block; font-size: 9px; letter-spacing: 2.5px; color: var(--muted); }
.dk-stat b { display: block; margin-top: 2px; font-size: 24px; color: var(--text); }
.dk-stat-gold { border-left-color: var(--cyan); background: var(--panel); }
.dk-stat-gold b { color: var(--cyan); text-shadow: 0 0 16px var(--glow); }

/* ---------- Buttons ---------- */
.dk-btn {
    position: relative;
    overflow: hidden;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    padding: 12px 22px;
    border: none;
    font-size: 11px;
    letter-spacing: 2px;
    text-transform: uppercase;
    text-decoration: none;
    cursor: pointer;
    clip-path: polygon(12px 0, 100% 0, calc(100% - 12px) 100%, 0 100%);
    transition: filter .2s, transform .2s;
}

.dk-btn::after {
    content: "";
    position: absolute;
    top: 0;
    bottom: 0;
    left: -60%;
    width: 40%;
    pointer-events: none;
    background: linear-gradient(100deg, transparent, rgba(255, 255, 255, .2), transparent);
    transform: skewX(-20deg);
    animation: dkShine 4.5s ease-in-out infinite;
}

.dk-btn:hover { filter: brightness(1.2); transform: translateY(-1px); }
.dk-btn:active { transform: translateY(1px) scale(.99); }
.dk-btn-lg { padding: 15px 30px; font-size: 12px; letter-spacing: 3px; }

.dk-btn-cyan {
    color: var(--cyan-ink);
    background: var(--cyan);
    box-shadow: 0 8px 30px var(--glow);
}

.dk-btn-ghost {
    color: var(--text);
    background: var(--panel);
    box-shadow: inset 0 0 0 1px var(--line-strong);
}

.dk-btn-ghost:hover { box-shadow: inset 0 0 0 1px var(--cyan); }
.dk-btn-ghost svg { color: var(--cyan); }

.dk-btn-danger {
    padding: 12px 16px;
    color: var(--muted);
    background: var(--panel);
    box-shadow: inset 0 0 0 1px var(--line);
}
.dk-btn-danger::after { display: none; }
.dk-btn-danger:hover { color: #FF8A8A; box-shadow: inset 0 0 0 1px rgba(255, 90, 90, .6); background: rgba(255, 60, 60, .1); }

/* ---------- Headings ---------- */
.dk-h2 { margin-top: 2px; font-size: 28px; letter-spacing: 3px; text-transform: uppercase; color: var(--text); }
.dk-h2 span { color: var(--cyan); text-shadow: 0 0 16px var(--glow); }
.dk-sub { margin-top: 6px; font-size: 14px; color: var(--muted); }

/* ---------- Type colors ---------- */
.t-attack  { --tc: #F87171; } /* Merah */
.t-defense { --tc: #22D3EE; } /* Cyan */
.t-other   { --tc: #C084FC; } /* Purple */

.dk-legend { display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 18px; font-size: 11px; letter-spacing: 2px; }
.dk-legend span { display: inline-flex; align-items: center; gap: 8px; color: var(--tc); }
.dk-legend i { width: 7px; height: 7px; transform: rotate(45deg); background: var(--tc); box-shadow: 0 0 10px var(--tc); }

/* ---------- Deck card ---------- */
.dk-card {
    --c: 26px;
    --edge: var(--line-strong);
    position: relative;
    isolation: isolate;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    clip-path: polygon(0 0, calc(100% - var(--c)) 0, 100% var(--c), 100% 100%, var(--c) 100%, 0 calc(100% - var(--c)));
    background:
        linear-gradient(to bottom left, transparent 50%, var(--edge) 50%, var(--edge) calc(50% + 2px), transparent calc(50% + 2px)) right top / var(--c) var(--c) no-repeat,
        linear-gradient(to top right, transparent 50%, var(--edge) 50%, var(--edge) calc(50% + 2px), transparent calc(50% + 2px)) left bottom / var(--c) var(--c) no-repeat,
        var(--panel);
    background-origin: border-box;
    box-shadow: inset 0 0 0 1px var(--line);
    transition: transform .3s cubic-bezier(.22, 1, .36, 1), box-shadow .3s;
}

.dk-card:nth-child(even):not(.is-active) {
    clip-path: polygon(var(--c) 0, 100% 0, 100% calc(100% - var(--c)), calc(100% - var(--c)) 100%, 0 100%, 0 var(--c));
    background:
        linear-gradient(to bottom right, transparent 50%, var(--edge) 50%, var(--edge) calc(50% + 2px), transparent calc(50% + 2px)) left top / var(--c) var(--c) no-repeat,
        linear-gradient(to top left, transparent 50%, var(--edge) 50%, var(--edge) calc(50% + 2px), transparent calc(50% + 2px)) right bottom / var(--c) var(--c) no-repeat,
        var(--panel);
    background-origin: border-box;
}

.dk-card:hover { --edge: var(--cyan); transform: translateY(-6px); box-shadow: inset 0 0 0 1px var(--cyan), inset 0 0 60px var(--glow); }

.dk-card::before {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    width: 40px;
    height: 2px;
    z-index: 2;
    background: var(--cyan);
    transition: width .5s cubic-bezier(.22, 1, .36, 1);
}

.dk-card:hover::before { width: 100%; }

.dk-card-glow {
    position: absolute;
    right: -50px;
    top: -60px;
    width: 190px;
    height: 190px;
    z-index: -1;
    border-radius: 50%;
    background: var(--glow);
    filter: blur(60px);
    transition: background .4s;
}

.dk-card:hover .dk-card-glow { background: var(--cyan); opacity: 0.2; }

/* Active / equipped: bentuk permata + cyan terang */
.dk-card.is-active {
    --edge: var(--cyan);
    clip-path: polygon(var(--c) 0, calc(100% - var(--c)) 0, 100% var(--c), 100% calc(100% - var(--c)), calc(100% - var(--c)) 100%, var(--c) 100%, 0 calc(100% - var(--c)), 0 var(--c));
    background:
        linear-gradient(to bottom right, transparent 50%, var(--edge) 50%, var(--edge) calc(50% + 2px), transparent calc(50% + 2px)) left top / var(--c) var(--c) no-repeat,
        linear-gradient(to bottom left, transparent 50%, var(--edge) 50%, var(--edge) calc(50% + 2px), transparent calc(50% + 2px)) right top / var(--c) var(--c) no-repeat,
        linear-gradient(to top left, transparent 50%, var(--edge) 50%, var(--edge) calc(50% + 2px), transparent calc(50% + 2px)) right bottom / var(--c) var(--c) no-repeat,
        linear-gradient(to top right, transparent 50%, var(--edge) 50%, var(--edge) calc(50% + 2px), transparent calc(50% + 2px)) left bottom / var(--c) var(--c) no-repeat,
        var(--panel);
    background-origin: border-box;
    box-shadow: inset 0 0 0 1px var(--cyan);
    animation: dkRise .8s cubic-bezier(.22, 1, .36, 1) backwards, dkActive 3s ease-in-out 1s infinite alternate;
    animation-delay: calc(var(--i, 0) * 90ms), 1s;
}

.dk-card.is-active::before { width: 100%; background: var(--cyan); }
.dk-card.is-active:hover { --edge: #FFFFFF; }

.dk-ribbon {
    position: absolute;
    right: 34px;
    top: 0;
    z-index: 3;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px 6px;
    font-size: 9px;
    letter-spacing: 2px;
    color: var(--cyan-ink);
    background: var(--cyan);
    clip-path: polygon(0 0, 100% 0, 100% 70%, 50% 100%, 0 70%);
    padding-bottom: 10px;
}

/* Header */
.dk-hex {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 54px;
    height: 54px;
    flex-shrink: 0;
    background: var(--panel);
    box-shadow: inset 0 0 0 1px var(--line-strong);
    clip-path: polygon(25% 0, 75% 0, 100% 50%, 75% 100%, 25% 100%, 0 50%);
}

.dk-hex small { font-size: 7px; letter-spacing: 1.5px; color: var(--muted); }
.dk-hex b { font-size: 17px; line-height: 1; color: var(--cyan); text-shadow: 0 0 12px var(--glow); }
.dk-card.is-active .dk-hex { background: var(--cyan); }
.dk-card.is-active .dk-hex small { color: var(--cyan-ink); }
.dk-card.is-active .dk-hex b { color: var(--cyan-ink); text-shadow: none; }

.dk-deck-name { font-size: 16px; letter-spacing: 1px; color: var(--text); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

.dk-deck-state { display: flex; align-items: center; gap: 7px; margin-top: 6px; font-size: 10px; letter-spacing: 2px; color: var(--muted); }
.dk-state-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--faint); }
.dk-deck-state.is-ok { color: var(--cyan); }
.dk-deck-state.is-ok .dk-state-dot { background: var(--cyan); box-shadow: 0 0 10px var(--cyan); animation: dkBlink 1.6s ease-in-out infinite; }

.dk-count { font-size: 28px; line-height: 1; color: var(--muted); }
.dk-count span { font-size: 12px; color: var(--faint); margin-left: 2px; }
.dk-count.is-ok { color: var(--cyan); text-shadow: 0 0 18px var(--glow); }
.dk-count-label { margin-top: 6px; font-size: 10px; letter-spacing: 3px; color: var(--faint); }

/* Segmen progress */
.dk-seg { display: flex; gap: 4px; }
.dk-seg i { flex: 1; height: 7px; background: var(--line); transform: skewX(-22deg); }
.dk-seg i.on { background: var(--cyan); box-shadow: 0 0 8px var(--glow); animation: dkSeg .5s ease-out backwards; animation-delay: calc(var(--n) * 45ms + .3s); }
.dk-card.is-ready .dk-seg i.on { background: var(--cyan); }

/* Komposisi */
.dk-comp { display: inline-flex; align-items: center; gap: 7px; padding: 4px 11px 4px 9px; font-size: 10px; letter-spacing: 1.5px; color: var(--tc); background: var(--panel); box-shadow: inset 2px 0 0 var(--tc); clip-path: polygon(0 0, 100% 0, calc(100% - 6px) 100%, 0 100%); }
.dk-comp i { width: 5px; height: 5px; transform: rotate(45deg); background: var(--tc); box-shadow: 0 0 8px var(--tc); }
.dk-comp b { color: var(--text); font-family: 'Iceberg', serif; font-size: 11px; }

/* Board */
.dk-board {
    position: relative;
    padding: 14px;
    background: var(--bg-soft);
    box-shadow: inset 0 0 0 1px var(--line);
}

.dk-board::before {
    content: "";
    position: absolute;
    inset: 0;
    opacity: .05;
    background-image: linear-gradient(var(--grid) 1px, transparent 1px);
    background-size: 15px 15px;
    pointer-events: none;
}

.dk-slot {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 4px;
    min-width: 0;
    aspect-ratio: 4 / 5;
    color: var(--tc);
    overflow: hidden;
    background: var(--panel);
    box-shadow: inset 0 0 0 1px var(--line-strong);
    animation: dkSlot .45s cubic-bezier(.22, 1, .36, 1) backwards;
    animation-delay: calc(var(--d, 0ms) + .35s);
    transition: transform .2s, filter .2s, box-shadow .2s;
}

.dk-slot::before { content: ""; position: absolute; left: 0; right: 0; top: 0; height: 2px; background: var(--tc); box-shadow: 0 0 10px var(--tc); }
.dk-slot::after { content: ""; position: absolute; top: 0; bottom: 0; left: -70%; width: 40%; background: linear-gradient(100deg, transparent, rgba(255, 255, 255, .2), transparent); transform: skewX(-20deg); opacity: 0; }

.dk-slot:hover { transform: translateY(-5px); z-index: 5; filter: brightness(1.2); box-shadow: inset 0 0 0 1px var(--tc), 0 0 18px var(--tc); }
.dk-slot:hover::after { opacity: 1; animation: dkShine 1s ease-out; }
.dk-slot svg { filter: drop-shadow(0 0 6px var(--tc)); transition: transform .2s; }
.dk-slot:hover svg { transform: scale(1.15); }
.dk-slot span { max-width: 100%; padding: 0 3px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 9px; letter-spacing: 1px; text-transform: uppercase; }

.dk-slot-empty {
    color: var(--faint);
    background: transparent;
    box-shadow: inset 0 0 0 1px var(--line);
    border: 1px dashed var(--line-strong);
    transition: color .2s, background .2s, border-color .2s;
}
.dk-slot-empty::before { display: none; }
.dk-slot-empty span { font-size: 20px; }
.dk-slot-empty:hover { transform: none; filter: none; box-shadow: none; color: var(--cyan); border-color: var(--cyan); background: var(--panel); }

.dk-board-foot { position: relative; display: flex; align-items: center; justify-content: space-between; margin-top: 12px; padding-top: 11px; border-top: 1px solid var(--line); font-size: 10px; letter-spacing: 2.5px; color: var(--faint); }
.dk-board-foot span { display: inline-flex; align-items: center; gap: 8px; }
.dk-board-foot i { width: 5px; height: 5px; transform: rotate(45deg); background: var(--cyan); }

.dk-note { font-size: 13px; color: var(--muted); }
.dk-incomplete { flex-shrink: 0; font-size: 10px; letter-spacing: 2.5px; color: var(--faint); }
.dk-equipped { flex-shrink: 0; padding: 3px 10px; font-size: 10px; letter-spacing: 2.5px; color: var(--cyan); background: var(--panel); box-shadow: inset 2px 0 0 var(--cyan); }

.dk-actions { position: relative; display: flex; gap: 8px; padding: 14px 16px 22px; background: var(--bg-soft); border-top: 1px solid var(--line); }

/* ---------- Empty ---------- */
.dk-empty {
    --c: 34px;
    position: relative;
    padding: 64px 24px;
    text-align: center;
    clip-path: polygon(var(--c) 0, calc(100% - var(--c)) 0, 100% var(--c), 100% calc(100% - var(--c)), calc(100% - var(--c)) 100%, var(--c) 100%, 0 calc(100% - var(--c)), 0 var(--c));
    background: var(--panel);
    box-shadow: inset 0 0 0 1px var(--line-strong);
}

.dk-empty-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 84px;
    height: 84px;
    margin: 0 auto 24px;
    color: var(--cyan);
    background: var(--bg-soft);
    box-shadow: inset 0 0 0 1px var(--line-strong);
    clip-path: polygon(25% 0, 75% 0, 100% 50%, 75% 100%, 25% 100%, 0 50%);
    animation: dkFloat 3s ease-in-out infinite;
}

/* ---------- Guide ---------- */
.dk-guide {
    --c: 28px;
    position: relative;
    isolation: isolate;
    clip-path: polygon(0 0, calc(100% - var(--c)) 0, 100% var(--c), 100% 100%, 0 100%);
    background:
        linear-gradient(to bottom left, transparent 50%, var(--line-strong) 50%, var(--line-strong) calc(50% + 2px), transparent calc(50% + 2px)) right top / var(--c) var(--c) no-repeat,
        var(--panel);
    background-origin: border-box;
    box-shadow: inset 0 0 0 1px var(--line);
}

.dk-guide::before { content: ""; position: absolute; left: 0; top: 0; bottom: 0; width: 2px; background: var(--cyan); }

.dk-guide-icon { display: flex; align-items: center; justify-content: center; width: 46px; height: 46px; flex-shrink: 0; color: var(--cyan); background: var(--bg-soft); box-shadow: inset 0 0 0 1px var(--line-strong); clip-path: polygon(25% 0, 75% 0, 100% 50%, 75% 100%, 25% 100%, 0 50%); }
.dk-guide-title { margin-top: 4px; font-size: 14px; letter-spacing: 2px; text-transform: uppercase; color: var(--text); }

.dk-tip {
    padding: 16px;
    background: var(--bg-soft);
    box-shadow: inset 0 0 0 1px var(--line);
    transition: background .25s, box-shadow .25s, transform .25s;
}

.dk-tip:hover { background: var(--panel); box-shadow: inset 0 0 0 1px var(--cyan); transform: translateY(-3px); }
.dk-tip-no { font-size: 14px; color: var(--cyan); text-shadow: 0 0 12px var(--glow); }
.dk-tip-ic { color: var(--faint); transition: color .25s; }
.dk-tip:hover .dk-tip-ic { color: var(--cyan); }
.dk-tip p { margin-top: 12px; font-size: 14px; line-height: 1.4; color: var(--muted); }
.dk-tip strong { color: var(--text); }

/* ---------- Keyframes ---------- */
@keyframes dkRise { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
@keyframes dkGrid { from { transform: translateY(0); } to { transform: translateY(45px); } }
@keyframes dkTwinkle { from { opacity: .15; } to { opacity: .35; } }
@keyframes dkDrift { from { translate: 0 0; } to { translate: 70px 50px; } }
@keyframes dkBlink { 0%, 100% { opacity: 1; } 50% { opacity: .4; } }
@keyframes dkSpin { to { rotate: 360deg; } }
@keyframes dkRingPulse { 0% { transform: scale(1); opacity: .7; } 100% { transform: scale(3.2); opacity: 0; } }
@keyframes dkShimmer { 0%, 20% { background-position: 120% 0; } 80%, 100% { background-position: -20% 0; } }
@keyframes dkLine { from { background-position: 0 0; } to { background-position: 200% 0; } }
@keyframes dkShine { 0%, 55% { translate: 0 0; } 100% { translate: 520% 0; } }
@keyframes dkSeg { from { opacity: 0; transform: skewX(-22deg) scaleY(0); } to { opacity: 1; transform: skewX(-22deg) scaleY(1); } }
@keyframes dkSlot { from { opacity: 0; transform: translateY(14px) scale(.85); } to { opacity: 1; transform: translateY(0) scale(1); } }
@keyframes dkFloat { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
@keyframes dkActive {
    from { box-shadow: inset 0 0 0 1px var(--line-strong), inset 0 0 22px var(--glow); }
    to   { box-shadow: inset 0 0 0 1px var(--cyan), inset 0 0 60px var(--glow); }
}
@keyframes dkGlitch {
    0%, 90%, 100% { opacity: 0; }
    91% { opacity: .8; clip-path: inset(12% 0 62% 0); translate: -4px 0; }
    93% { opacity: .8; clip-path: inset(58% 0 18% 0); translate: 4px 0; }
    95% { opacity: .8; clip-path: inset(30% 0 40% 0); translate: -2px 0; }
    97% { opacity: 0; }
}

/* ---------- Responsive HP ---------- */
@media (max-width: 640px) {
    .dk-stats { flex-wrap: wrap; gap: 8px; }
    .dk-stat { flex: 1; min-width: 70px; padding: 8px 12px; }
    .dk-stat b { font-size: 20px; }
    .dk-h2 { font-size: 22px; }
    .dk-deck-name { font-size: 14px; }
    .dk-ring { width: 240px; height: 240px; }
    .dk-actions { flex-wrap: wrap; }
    .dk-btn-ghost { flex: 1 1 100%; }
    .dk-btn-cyan, .dk-btn-danger { flex: 1; }
    .dk-hero .dk-btn-lg { width: 100%; }
}

@media (prefers-reduced-motion: reduce) {
    .dk-page *, .dk-page *::before, .dk-page *::after {
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: .01ms !important;
    }
}
</style>

@endsection