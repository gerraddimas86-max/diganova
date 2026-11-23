@extends('playground.layouts.app')

@section('title', 'Gacha — DIGANOVA Playground')

@section('content')

<div x-data="gachaApp()" x-cloak class="gacha-page" :class="{ 'is-busy': showOpening }">

    {{-- ==================== DEKORASI BACKGROUND ==================== --}}
    <div class="bg-decor" aria-hidden="true">
        <div class="bg-grid"></div>
        <div class="bg-stars"></div>
        <span class="bg-orb orb-1"></span>
        <span class="bg-orb orb-2"></span>
        <span class="bg-orb orb-3"></span>
    </div>

    <div class="gx" x-data="{ tab: 'banner' }">

        {{-- ==================== TOP BAR ==================== --}}
        <header class="gx-top">
            <a href="{{ url()->previous() }}" class="gx-back" aria-label="Kembali">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M15 5l-7 7 7 7M21 5l-7 7 7 7" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <h1 class="gx-title">Gacha <i>★</i></h1>

            <div class="gx-currency">
                <div class="gx-chip">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 2.5 14.9 8l6.1.9-4.4 4.3 1 6.1-5.6-3-5.6 3 1-6.1L3 8.9 9.1 8 12 2.5Z"/>
                    </svg>
                    <span x-text="points"></span>
                </div>
                <div class="gx-chip gx-chip-dim hide-sm">PULL <b>{{ $stats['total_pulls'] }}</b></div>

                {{-- TOMBOL BANTUAN / TUTORIAL --}}
                <button type="button"
                        class="gx-help"
                        @click="openTutorial()"
                        aria-label="Buka tutorial spin"
                        title="Cara spin">
                    ?
                </button>
            </div>
        </header>

        <div class="gx-body">

            {{-- ==================== SIDEBAR ==================== --}}
            <nav class="gx-side">
                <button type="button" class="gx-tab" :class="{ on: tab === 'banner' }" @click="tab = 'banner'">
                    <span class="gx-tab-ico">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 2.5 14.9 8l6.1.9-4.4 4.3 1 6.1-5.6-3-5.6 3 1-6.1L3 8.9 9.1 8 12 2.5Z"/>
                        </svg>
                    </span>
                    <span><b>Crystal</b><small>Limited</small></span>
                </button>

                <button type="button" class="gx-tab" :class="{ on: tab === 'koleksi' }" @click="tab = 'koleksi'">
                    <span class="gx-tab-ico">
                        <svg viewBox="0 0 24 24">
                            <path d="M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z"/>
                        </svg>
                    </span>
                    <span><b>Koleksi</b><small>{{ $stats['total_owned'] }}/{{ $stats['total_items'] }} item</small></span>
                </button>

                <a href="{{ route('play.gacha.history') }}" class="gx-tab">
                    <span class="gx-tab-ico">
                        <svg viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="8.5" stroke="currentColor" stroke-width="2"/>
                            <path d="M12 7.5V12l3 2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </span>
                    <span><b>Riwayat</b><small>Pull history</small></span>
                </a>
            </nav>

            <main class="gx-main">

                {{-- ==================== TAB BANNER ==================== --}}
                <section x-show="tab === 'banner'" class="gx-stage-wrap">

                    <div class="gx-stage" :class="{ 'is-active': pulling || showResult }">

                        {{-- HERO --}}
                        <div class="gx-hero">
                            <span class="gx-badge">Event Terbatas</span>

                            <h2 class="gx-hero-title">Crystal<br><span>of Fortune</span><i>★</i></h2>

                            <p class="gx-hero-text">
                                Buka kristal dan kumpulkan kosmetik eksklusif.<br>
                                Peluang lebih besar untuk item Legendary!
                            </p>

                            <div class="gx-hero-art" aria-hidden="true">
                                <div class="gx-moon"></div>

                                <img class="gx-crystal"
                                     :class="{ 'crystal-shake': pulling }"
                                     src="{{ asset('images/gacha-logo.png') }}"
                                     alt="Logo Gacha">

                                <span class="gx-spark s1"></span>
                                <span class="gx-spark s2"></span>
                                <span class="gx-spark s3"></span>
                            </div>

                            <div class="gx-featured">
                                <div class="gx-featured-ico">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M12 2.5 14.9 8l6.1.9-4.4 4.3 1 6.1-5.6-3-5.6 3 1-6.1L3 8.9 9.1 8 12 2.5Z"/>
                                    </svg>
                                </div>
                                <div>
                                    <b>Crystal of Fortune</b>
                                    <div class="gx-stars">★★★★★</div>
                                    <small>Kristal berisi {{ $stats['total_items'] }} item kosmetik.</small>
                                </div>
                            </div>
                        </div>

                        {{-- PANEL KANAN --}}
                        <aside class="gx-right">
                            <div class="gx-rarity-list">
                                <div class="gx-rarity r-legendary"><span>LEGENDARY</span><i>★★★★★</i></div>
                                <div class="gx-rarity r-epic"><span>EPIC</span><i>★★★★</i></div>
                                <div class="gx-rarity r-rare"><span>RARE</span><i>★★★</i></div>
                                <div class="gx-rarity r-common"><span>COMMON</span><i>★★</i></div>
                            </div>

                            <div class="gx-info">
                                <div class="gx-info-row">
                                    <svg viewBox="0 0 24 24" fill="none">
                                        <circle cx="12" cy="12" r="8.5" stroke="currentColor" stroke-width="1.8"/>
                                        <path d="M12 7.5V12l3 2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    </svg>
                                    <span>Koleksi</span>
                                    <b>{{ $stats['total_owned'] }} / {{ $stats['total_items'] }} item</b>
                                </div>
                                <div class="gx-info-row gx-info-note">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M12 2.5 14.9 8l6.1.9-4.4 4.3 1 6.1-5.6-3-5.6 3 1-6.1L3 8.9 9.1 8 12 2.5Z" fill="none" stroke="currentColor" stroke-width="1.5"/>
                                    </svg>
                                    <span>Item duplikat dikembalikan<br>menjadi poin!</span>
                                </div>
                            </div>
                        </aside>
                    </div>

                    {{-- ACTION BAR --}}
                    <div class="gx-actions">
                        <div class="gx-actions-left">
                            <button type="button" class="gx-pill" @click="tab = 'koleksi'">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M7 3h8l4 4v14H7z M15 3v4h4 M10 12h6 M10 16h6" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                                </svg>
                                Detail
                            </button>
                            <a href="{{ route('play.gacha.history') }}" class="gx-pill">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <circle cx="12" cy="12" r="8.5" stroke="currentColor" stroke-width="1.8"/>
                                    <path d="M12 7.5V12l3 2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                </svg>
                                Riwayat
                            </a>
                        </div>

                        <div class="gx-actions-right">
                            {{-- 1x PULL --}}
                            <div class="gx-pull-wrap" data-tour="pull-single">
                                <div class="gx-cost">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M12 2.5 14.9 8l6.1.9-4.4 4.3 1 6.1-5.6-3-5.6 3 1-6.1L3 8.9 9.1 8 12 2.5Z"/>
                                    </svg>
                                    50
                                </div>
                                <button type="button"
                                        class="gx-pull"
                                        @click="openConfirm(1)"
                                        :disabled="pulling || points < 50">
                                    <span x-show="!pulling">1x Pull</span>
                                    <span x-show="pulling">Membuka...</span>
                                </button>
                                <p class="gx-lack" x-show="points < 50">Poin belum cukup.</p>
                            </div>

                            {{-- 10x PULL --}}
                            <div class="gx-pull-wrap" data-tour="pull-bulk">
                                <span class="gx-sale">Diskon!</span>
                                <div class="gx-cost">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M12 2.5 14.9 8l6.1.9-4.4 4.3 1 6.1-5.6-3-5.6 3 1-6.1L3 8.9 9.1 8 12 2.5Z"/>
                                    </svg>
                                    450
                                </div>
                                <button type="button"
                                        class="gx-pull gx-pull-10"
                                        @click="openConfirm(10)"
                                        :disabled="pulling || points < 450">
                                    <span x-show="!pulling">10x Pull</span>
                                    <span x-show="pulling">Membuka...</span>
                                </button>
                                <p class="gx-lack" x-show="points < 450">Poin belum cukup.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Callout "Baru di sini?" kalau tutorial belum pernah dibuka --}}
                    <button type="button"
                            class="gx-first-hint"
                            x-show="!tutorialSeen"
                            @click="openTutorial()">
                        <span class="gx-first-hint-dot"></span>
                        Baru di sini? Lihat cara spin →
                    </button>
                </section>

                {{-- ==================== TAB KOLEKSI ==================== --}}
                <section x-show="tab === 'koleksi'" class="gx-collection">
                    @php
                        $collectionProgress = $stats['total_items'] > 0
                            ? min(100, round(($stats['total_owned'] / $stats['total_items']) * 100))
                            : 0;
                    @endphp

                    <div class="gx-col-head">
                        <div>
                            <h2>Koleksi Item</h2>
                            <p>{{ $stats['total_owned'] }} / {{ $stats['total_items'] }} item terkumpul · {{ $stats['total_pulls'] }} total pull</p>
                        </div>
                        <strong>{{ $collectionProgress }}%</strong>
                    </div>

                    <div class="gx-bar"><div style="width: {{ $collectionProgress }}%"></div></div>

                    <div class="item-grid">
                        @forelse ($allItems as $item)
                            <div class="item-cell" style="--i: {{ $loop->index }}">
                                <x-gacha-item
                                    :item="$item"
                                    :owned="in_array($item->id, $ownedIds)"
                                />
                            </div>
                        @empty
                            <div class="empty-collection">
                                <div class="empty-symbol">?</div>
                                <p>Belum ada item di reward pool.</p>
                            </div>
                        @endforelse
                    </div>
                </section>
            </main>
        </div>
    </div>

    {{-- ==================== TUTORIAL MODAL ==================== --}}
    <div x-show="showTutorial"
         x-transition.opacity.duration.250ms
         class="gacha-overlay tutorial-overlay"
         @keydown.escape.window="closeTutorial()">

        <div class="tutorial-modal"
             x-show="showTutorial"
             x-transition.scale.origin.center.duration.280ms
             @click.outside="closeTutorial()">

            <div class="modal-top-line"></div>

            <button type="button"
                    class="modal-close"
                    @click="closeTutorial()"
                    aria-label="Tutup tutorial">
                &times;
            </button>

            {{-- Progress dots --}}
            <div class="tutorial-progress">
                <template x-for="(step, idx) in tutorialSteps" :key="'dot-' + idx">
                    <span class="tutorial-dot"
                          :class="{ on: tutorialStep === idx, done: tutorialStep > idx }"></span>
                </template>
            </div>

            {{-- Step content --}}
            <div class="tutorial-content">
                <div class="tutorial-visual" :class="'tv-' + tutorialSteps[tutorialStep].key">
                    {{-- Icon per step --}}
                    <template x-if="tutorialSteps[tutorialStep].key === 'welcome'">
                        <div class="tv-orb">
                            <svg viewBox="0 0 80 80" aria-hidden="true">
                                <polygon points="15,22 29,9 51,9 65,22 40,70" fill="#0B3B4A"/>
                                <polygon points="15,22 40,22 40,70" fill="#22D3EE"/>
                                <polygon points="40,22 65,22 40,70" fill="#0891B2"/>
                                <polygon points="29,9 51,9 65,22 15,22" fill="#67E8F9"/>
                            </svg>
                        </div>
                    </template>

                    <template x-if="tutorialSteps[tutorialStep].key === 'points'">
                        <div class="tv-coins">
                            <span class="tv-coin c1">★</span>
                            <span class="tv-coin c2">★</span>
                            <span class="tv-coin c3">★</span>
                        </div>
                    </template>

                    <template x-if="tutorialSteps[tutorialStep].key === 'pull'">
                        <div class="tv-pull">
                            <span class="tv-pull-btn">PULL</span>
                            <span class="tv-pull-arrow">↓</span>
                            <span class="tv-pull-coin">★ 50</span>
                        </div>
                    </template>

                    <template x-if="tutorialSteps[tutorialStep].key === 'rarity'">
                        <div class="tv-rarity">
                            <span class="tr tr-c">C</span>
                            <span class="tr tr-r">R</span>
                            <span class="tr tr-e">E</span>
                            <span class="tr tr-l">L</span>
                        </div>
                    </template>
                </div>

                <p class="tutorial-kicker"
                   x-text="'STEP ' + (tutorialStep + 1) + ' / ' + tutorialSteps.length"></p>

                <h3 class="tutorial-title"
                    x-text="tutorialSteps[tutorialStep].title"></h3>

                <p class="tutorial-desc"
                   x-text="tutorialSteps[tutorialStep].desc"></p>

                {{-- Tips per step --}}
                <div class="tutorial-tip" x-show="tutorialSteps[tutorialStep].tip">
                    <span class="tutorial-tip-badge">TIPS</span>
                    <span x-text="tutorialSteps[tutorialStep].tip"></span>
                </div>
            </div>

            {{-- Footer actions --}}
            <div class="tutorial-actions">
                <button type="button"
                        class="tutorial-btn tutorial-btn-ghost"
                        @click="prevTutorialStep()"
                        :disabled="tutorialStep === 0"
                        x-show="tutorialStep > 0">
                    ← Kembali
                </button>

                <button type="button"
                        class="tutorial-btn tutorial-btn-ghost"
                        @click="closeTutorial()"
                        x-show="tutorialStep === 0"
                        style="margin-right:auto;">
                    Lewati
                </button>

                <button type="button"
                        class="tutorial-btn tutorial-btn-primary"
                        @click="nextTutorialStep()">
                    <span x-show="tutorialStep < tutorialSteps.length - 1">Lanjut →</span>
                    <span x-show="tutorialStep === tutorialSteps.length - 1">Mengerti! ✦</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ==================== CONFIRMATION MODAL ==================== --}}
    <div x-show="showConfirm"
         x-transition.opacity.duration.200ms
         class="gacha-overlay confirm-overlay"
         @keydown.escape.window="if (!pulling) showConfirm = false">

        <div class="confirm-modal"
             x-show="showConfirm"
             x-transition.scale.origin.center.duration.250ms
             @click.outside="if (!pulling) showConfirm = false">

            <div class="modal-top-line"></div>

            <div class="modal-kicker">GACHA SYSTEM / TRANSACTION</div>

            <button type="button"
                    class="modal-close"
                    @click="showConfirm = false"
                    :disabled="pulling"
                    aria-label="Tutup konfirmasi">
                &times;
            </button>

            <div class="confirm-crystal">
                <svg viewBox="0 0 80 80" aria-hidden="true">
                    <polygon points="15,22 29,9 51,9 65,22 40,70" fill="#0B3B4A"/>
                    <polygon points="15,22 40,22 40,70" fill="#22D3EE"/>
                    <polygon points="40,22 65,22 40,70" fill="#0891B2"/>
                    <polygon points="29,9 51,9 65,22 15,22" fill="#67E8F9"/>
                </svg>
            </div>

            <h2 class="confirm-title">KONFIRMASI PULL</h2>

            <p class="confirm-description">
                Pastikan jumlah pull dan biaya sudah sesuai sebelum melanjutkan.
            </p>

            <div class="transaction-box">
                <div class="transaction-row">
                    <span>Jenis pull</span>
                    <strong x-text="selectedCount + 'x Pull'"></strong>
                </div>

                <div class="transaction-row">
                    <span>Biaya</span>
                    <strong class="transaction-cost">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 2.5 14.9 8l6.1.9-4.4 4.3 1 6.1-5.6-3-5.6 3 1-6.1L3 8.9 9.1 8 12 2.5Z"/>
                        </svg>
                        <span x-text="selectedCost"></span>
                        <small>POIN</small>
                    </strong>
                </div>

                <div class="transaction-row transaction-balance">
                    <span>Sisa poin setelah pull</span>
                    <strong x-text="Math.max(0, points - selectedCost)"></strong>
                </div>
            </div>

            <div class="confirm-warning">
                Poin akan digunakan setelah kamu mengonfirmasi transaksi.
            </div>

            <div class="confirm-actions">
                <button type="button"
                        class="cancel-button"
                        @click="showConfirm = false"
                        :disabled="pulling">
                    BATAL
                </button>

                <button type="button"
                        class="confirm-button"
                        @click="pull(selectedCount)"
                        :disabled="pulling || points < selectedCost">
                    <span x-show="!pulling">KONFIRMASI</span>
                    <span x-show="pulling">MEMPROSES...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ==================== PULL ANIMATION OVERLAY ==================== --}}
    <div x-show="showOpening"
         x-transition.opacity.duration.400ms
         class="gacha-overlay opening-overlay">

        <div class="opening-flash"
             x-show="openingPhase === 'burst'"
             :class="'burst-' + bestRarity"></div>

        <div class="opening-content"
             :class="['phase-' + openingPhase, 'burst-' + bestRarity]"
             :style="'--charge:' + (openingProgress / 100)">

            <div class="opening-rings">

                <div class="energy-field">
                    <template x-for="n in 16" :key="'e' + n">
                        <span class="energy-dot"
                              :style="'--a:' + (n * 22.5) + 'deg; --delay:' + ((n % 8) * 0.14) + 's'"></span>
                    </template>
                </div>

                <div class="opening-ring ring-a"></div>
                <div class="opening-ring ring-b"></div>
                <div class="opening-ring ring-c"></div>

                <div class="shockwave"></div>
                <div class="shockwave shockwave-2"></div>

                <div class="shard-field">
                    <template x-for="n in 14" :key="'s' + n">
                        <span class="burst-shard"
                              :style="'--a:' + (n * 25.7) + 'deg; --far:' + (170 + (n % 3) * 55) + 'px'"></span>
                    </template>
                </div>

                <div class="core-glow"></div>

                <div class="opening-core">
                    <svg viewBox="0 0 180 180" aria-hidden="true">
                        <defs>
                            <linearGradient id="oTop" x1="0" y1="0" x2="1" y2="1">
                                <stop offset="0%" stop-color="#A5F3FC"/>
                                <stop offset="100%" stop-color="#0891B2"/>
                            </linearGradient>
                            <linearGradient id="oLeft" x1="0" y1="0" x2="1" y2="1">
                                <stop offset="0%" stop-color="#0B3B4A"/>
                                <stop offset="100%" stop-color="#22D3EE"/>
                            </linearGradient>
                            <linearGradient id="oRight" x1="0" y1="0" x2="1" y2="1">
                                <stop offset="0%" stop-color="#0891B2"/>
                                <stop offset="100%" stop-color="#062833"/>
                            </linearGradient>
                            <linearGradient id="oShine" x1="0" y1="0" x2="1" y2="0">
                                <stop offset="0%" stop-color="#FFFFFF" stop-opacity="0"/>
                                <stop offset="50%" stop-color="#FFFFFF" stop-opacity=".65"/>
                                <stop offset="100%" stop-color="#FFFFFF" stop-opacity="0"/>
                            </linearGradient>
                            <clipPath id="oClip">
                                <polygon points="35,42 65,18 115,18 145,42 90,155"/>
                            </clipPath>
                        </defs>

                        <polygon points="35,42 65,18 115,18 145,42 90,155" fill="#0B3B4A"/>
                        <polygon points="35,42 90,42 90,155" fill="url(#oLeft)"/>
                        <polygon points="90,42 145,42 90,155" fill="url(#oRight)"/>
                        <polygon points="65,18 115,18 145,42 35,42" fill="url(#oTop)"/>
                        <polygon points="35,42 65,18 90,42" fill="#A5F3FC" opacity=".5"/>
                        <polygon points="90,42 115,18 145,42" fill="#0891B2" opacity=".5"/>
                        <polygon points="90,42 62,91 90,155" fill="#FFFFFF" opacity=".08"/>

                        <polygon class="open-inner" points="35,42 65,18 115,18 145,42 90,155"/>

                        <g clip-path="url(#oClip)">
                            <rect class="open-shine" x="-70" y="0" width="40" height="180"
                                  fill="url(#oShine)" transform="skewX(-20)"/>
                        </g>

                        <path d="M35 42H145L90 155L35 42Z" fill="none"
                              stroke="#FFFFFF" stroke-opacity=".4" stroke-width="1.5"/>

                        <g class="crystal-cracks" fill="none" stroke="#FFFFFF"
                           stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path pathLength="100" d="M90 20 L84 52 L96 76 L86 104 L92 152"/>
                            <path pathLength="100" d="M84 52 L58 66 L50 88"/>
                            <path pathLength="100" d="M96 76 L124 84 L132 60"/>
                            <path pathLength="100" d="M86 104 L66 118"/>
                        </g>
                    </svg>
                </div>
            </div>

            <div class="opening-status" x-text="openingStatus"></div>

            <div class="opening-progress">
                <div class="opening-progress-fill"
                     :style="'transform:scaleX(' + (openingProgress / 100) + ')'">
                </div>
            </div>

            <div class="opening-subtitle">
                PLEASE WAIT WHILE THE CRYSTAL OPENS
            </div>
        </div>
    </div>

    {{-- ==================== RESULT MODAL ==================== --}}
    <div x-show="showResult"
         x-transition.opacity.duration.250ms
         class="gacha-overlay result-overlay"
         :class="'tone-' + bestRarity"
         @keydown.escape.window="closeResult()">

        <div class="result-rays"></div>

        <div class="result-sparks" x-show="showResult">
            <template x-for="n in 28" :key="'sp' + n">
                <span class="result-spark"
                      :style="'--a:' + (n * 12.85) + 'deg; --far:' + (160 + (n % 4) * 70) + 'px; --d:' + ((n % 7) * 0.04) + 's'"></span>
            </template>
        </div>

        <div class="result-modal" @click.outside="closeResult()">

            <div class="result-top-line"></div>

            <button type="button"
                    class="modal-close result-close"
                    @click="closeResult()"
                    aria-label="Tutup hasil">
                &times;
            </button>

            <div class="result-heading">
                <div class="modal-kicker">REWARD SYSTEM / COMPLETE</div>

                <h2 class="result-title">
                    PULL <span>COMPLETE</span>
                </h2>

                <p class="result-description">
                    Kristal berhasil dibuka. Berikut item yang kamu dapatkan.
                </p>

                <div class="duplicate-refund" x-show="duplicatePoints > 0">
                    <span>REFUND DUPLIKAT</span>
                    <strong>+<span x-text="duplicatePoints"></span> POIN</strong>
                </div>
            </div>

            <div class="result-grid"
                 :class="results.length === 1 ? 'single-result' : 'multi-result'">

                <template x-for="(r, i) in results" :key="'result-' + i">
                    <div class="result-item"
                         x-show="revealed > i"
                         x-transition:enter="result-enter"
                         :style="'--reveal-delay:' + (i * 45) + 'ms'">

                        <div class="result-item-glow" :class="'glow-' + r.rarity"></div>

                        <span class="reveal-ring" :class="'ring-' + r.rarity"></span>

                        <div class="result-item-art" :class="'rarity-' + r.rarity">

                            <span class="result-rarity-mark"
                                  x-text="rarityMark(r.rarity)"></span>

                            <span class="new-tag" x-show="!r.is_duplicate">NEW</span>

                            <img class="result-item-img"
                                 x-show="itemImage(r) && !r._imgError"
                                 :src="itemImage(r)"
                                 :alt="r.name"
                                 loading="lazy"
                                 @@error="r._imgError = true">

                            <span class="result-type-icon"
                                  x-show="!itemImage(r) || r._imgError"
                                  x-text="typeMark(r.type)"></span>

                            <span class="duplicate-tag" x-show="r.is_duplicate">DUPLICATE</span>
                        </div>

                        <div class="result-item-name" x-text="r.name"></div>

                        <div class="result-rarity-label"
                             :class="'label-' + r.rarity"
                             x-text="String(r.rarity || 'common').toUpperCase()">
                        </div>

                        <div class="result-refund" x-show="r.is_duplicate">
                            +<span x-text="r.refund"></span> POIN
                        </div>
                    </div>
                </template>
            </div>

            <div class="result-footer">
                <div class="result-summary">
                    <span>ITEM BERHASIL DIPEROLEH</span>
                    <strong>
                        <span x-text="revealed"></span>
                        /
                        <span x-text="results.length"></span>
                    </strong>
                </div>

                <div class="result-actions">
                    <button type="button"
                            @click="closeResult()"
                            class="result-button secondary-result-button">
                        SELESAI
                    </button>

                    <a href="{{ route('play.inventory.index') }}"
                       class="result-button primary-result-button">
                        BUKA INVENTORY
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M5 12H19M12 5L19 12L12 19"
                                  stroke="currentColor"
                                  stroke-width="1.7"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
[x-cloak] { display: none !important; }

/* =========================================================
   TOKENS
   ========================================================= */
.gacha-page {
    --ct:      var(--accent-text);
    --ln:      var(--line);
    --ln2:     var(--line-strong);
    --surface: var(--mini-panel);
    --gold:    #FFD76A;
    --epic:    #8A8CFF;
    position: relative;
    color: var(--text);
    padding-bottom: 40px;
    overflow-x: hidden;
    width: 100%;
    background: transparent;
    font-family: 'Anta', system-ui, sans-serif;
    font-weight: 400;
}
[data-theme="light"] .gacha-page { --gold: #B7791F; --epic: #6D5BD0; }

.gacha-page, .gacha-page * { box-sizing: border-box; }
.gacha-page button, .gacha-page a, .gacha-page input { font-family: inherit; }

.bg-decor { display: none; }

.gx-title, .gx-hero-title, .gx-tab b, .gx-featured b, .gx-col-head h2, .gx-col-head strong,
.confirm-title, .result-title, .opening-status, .tutorial-title, .result-summary strong,
.gx-chip span, .gx-chip-dim b, .transaction-cost, .gx-cost, .gx-help, .tv-coin {
    font-family: 'Iceberg', serif;
    font-weight: 400;
    -webkit-text-stroke: .35px currentColor;
}

/* =========================================================
   LAYOUT UTAMA
   ========================================================= */
.gx { color: var(--text); animation: riseIn .8s cubic-bezier(.22, 1, .36, 1) backwards; }
.gx svg { fill: currentColor; }
.gx svg[fill="none"] { fill: none; }

/* TOP BAR */
.gx-top { display: flex; align-items: center; gap: 16px; margin-bottom: 18px; }
.gx-back {
    width: 2.5rem; height: 2.5rem; flex-shrink: 0;
    display: grid; place-items: center;
    border: 1px solid var(--ln); border-radius: 999px;
    color: var(--text); text-decoration: none;
    transition: border-color .2s, color .2s, background-color .2s;
}
[data-theme="light"] .gx-back { background: #FFFFFF; }
.gx-back:hover { border-color: var(--cyan); color: var(--ct); }
.gx-back svg { width: 20px; height: 20px; }

.gx-title { font-size: clamp(1.9rem, 4vw, 2.7rem); line-height: 1.05; letter-spacing: -.015em; margin: 0; }
.gx-title i { font-style: normal; font-size: .5em; vertical-align: super; color: var(--ct); }

.gx-currency { margin-left: auto; display: flex; gap: 10px; align-items: center; }
.gx-chip {
    display: inline-flex; align-items: center; gap: .6rem;
    min-width: 120px; padding: .5rem .85rem;
    background: var(--bg-soft); border: 1px solid var(--ln2); border-radius: 4px;
    box-shadow: var(--shadow); white-space: nowrap;
}
[data-theme="light"] .gx-chip { background: #FFFFFF; }
.gx-chip span { font-size: 1.1rem; line-height: 1; letter-spacing: .02em; }
.gx-chip svg { width: 16px; height: 16px; color: var(--ct); flex-shrink: 0; }
.gx-chip-dim {
    min-width: 0; font-size: .58rem; letter-spacing: .2em; text-transform: uppercase; color: var(--faint);
}
.gx-chip-dim b { color: var(--text); font-size: 1rem; letter-spacing: .02em; margin-left: .3rem; }

.gx-help {
    width: 2.5rem; height: 2.5rem; flex-shrink: 0;
    display: grid; place-items: center; cursor: pointer;
    background: transparent; border: 1px solid var(--ln); border-radius: 999px;
    color: var(--text); font-size: 1.05rem;
    transition: border-color .2s, color .2s, transform .2s;
}
[data-theme="light"] .gx-help { background: #FFFFFF; }
.gx-help:hover { border-color: var(--cyan); color: var(--ct); transform: translateY(-2px); }

/* BODY */
.gx-body { display: grid; grid-template-columns: 250px minmax(0, 1fr); gap: 20px; align-items: start; }
.gx-side { display: grid; gap: 12px; }

.gx-tab {
    position: relative; display: flex; align-items: center; gap: .9rem;
    padding: .8rem; min-height: 76px; text-align: left;
    color: var(--text); text-decoration: none; cursor: pointer;
    border: 1px solid var(--ln); border-radius: 2px; background: var(--panel);
    transition: border-color .2s, transform .2s, box-shadow .2s, background-color .2s;
}
.gx-tab b { display: block; font-size: 1.2rem; line-height: 1; }
.gx-tab small {
    display: block; margin-top: .4rem;
    font-size: .6rem; letter-spacing: .18em; text-transform: uppercase; color: var(--faint);
}
.gx-tab:hover { border-color: var(--cyan); transform: translateX(3px); }
.gx-tab.on {
    border-color: var(--cyan);
    box-shadow: inset 3px 0 0 var(--cyan), 0 14px 34px -22px var(--cyan);
}
.gx-tab.on b { color: var(--ct); }
.gx-tab-ico {
    width: 44px; height: 44px; flex: 0 0 44px; display: grid; place-items: center;
    border: 1px solid var(--ln); border-radius: 2px; color: var(--ct);
    background: linear-gradient(145deg, var(--line), transparent 70%);
}
.gx-tab-ico svg { width: 22px; height: 22px; }

/* STAGE */
.gx-stage {
    position: relative; display: grid; grid-template-columns: minmax(0, 1fr) 290px;
    min-height: 520px; overflow: hidden;
    border: 1px solid var(--ln); border-radius: 6px;
    background: radial-gradient(ellipse at 40% 25%, var(--glow) 0%, transparent 62%), var(--panel);
    box-shadow: var(--shadow);
}
.gx-stage::before {
    content: ''; position: absolute; top: 0; left: 1.5rem; right: 1.5rem; height: 2px; z-index: 4;
    background: linear-gradient(90deg, transparent, var(--cyan), transparent); opacity: .8;
}
.gx-hero { position: relative; padding: 28px 30px; min-height: 520px; }

.gx-badge {
    display: inline-flex; align-items: center; gap: .6rem;
    padding: .5rem .9rem .5rem .75rem;
    border: 1px solid var(--ln); border-radius: 999px; background: var(--panel);
    color: var(--muted); font-size: .64rem; letter-spacing: .18em; text-transform: uppercase; white-space: nowrap;
}
.gx-badge::before {
    content: ''; width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0;
    background: var(--cyan); box-shadow: 0 0 10px var(--cyan);
}
.gx-hero-title {
    position: relative; z-index: 3; margin: 1.3rem 0 0;
    font-size: clamp(2.5rem, 5.2vw, 4.2rem); line-height: 1.05; letter-spacing: -.015em;
}
.gx-hero-title span { color: var(--ct); }
.gx-hero-title i { font-style: normal; font-size: .4em; margin-left: 8px; vertical-align: top; color: var(--ct); }
.gx-hero-text {
    position: relative; z-index: 3; margin: 1.3rem 0 0; max-width: 300px;
    font-size: .98rem; line-height: 1.75; color: var(--muted);
}

.gx-hero-art { position: absolute; right: 6%; top: 8%; width: 58%; height: 78%; display: grid; place-items: center; pointer-events: none; }
.gx-moon {
    position: absolute; width: 300px; height: 300px; border-radius: 50%;
    border: 1px dashed var(--ln2); animation: orbitRotate 44s linear infinite;
}
.gx-moon::after {
    content: ''; position: absolute; top: -4px; left: 50%; width: 7px; height: 7px; margin-left: -3.5px;
    border-radius: 50%; background: var(--cyan); box-shadow: 0 0 12px var(--cyan);
}
.gx-crystal {
    position: relative; width: clamp(170px, 20vw, 250px); max-height: 100%; height: auto; object-fit: contain;
    filter: drop-shadow(0 0 28px rgba(34, 211, 238, .45)); animation: crystalFloat 3.5s ease-in-out infinite;
}
.gx-stage.is-active .gx-crystal, .gx-stage.is-active .gx-moon { animation-play-state: paused; }
.gx-spark {
    position: absolute; width: 6px; height: 6px; background: var(--cyan); box-shadow: 0 0 12px var(--cyan);
    transform: rotate(45deg); animation: particleFloat 3s ease-in-out infinite;
}
.gx-spark.s1 { left: 18%; top: 28%; } .gx-spark.s2 { right: 12%; top: 40%; animation-delay: .8s; } .gx-spark.s3 { left: 30%; bottom: 18%; animation-delay: 1.5s; }

.gx-featured {
    position: absolute; left: 24px; bottom: 22px; z-index: 3; display: flex; align-items: center; gap: 14px;
    padding: .6rem 1.2rem .6rem .6rem; background: var(--bg-soft);
    border: 1px solid var(--ln2); border-radius: 4px; box-shadow: var(--shadow);
}
[data-theme="light"] .gx-featured { background: #FFFFFF; }
.gx-featured-ico { width: 48px; height: 48px; display: grid; place-items: center; border: 1px solid var(--ln2); border-radius: 2px; color: var(--ct); }
.gx-featured-ico svg { width: 24px; height: 24px; }
.gx-featured b { display: block; font-size: 1.1rem; line-height: 1.1; }
.gx-stars { color: var(--gold); letter-spacing: 2px; font-size: .85rem; margin: .2rem 0; }
.gx-featured small { display: block; font-size: .7rem; color: var(--muted); }

/* PANEL KANAN */
.gx-right {
    position: relative; z-index: 3; display: flex; flex-direction: column; padding: 18px;
    border-left: 1px dashed var(--ln2);
}
.gx-rarity-list { display: grid; gap: 10px; }
.gx-rarity {
    --rc: var(--ct);
    height: 80px; padding: 12px 18px; display: flex; flex-direction: column; justify-content: flex-end; gap: .3rem;
    border: 1px solid var(--ln); border-left: 3px solid var(--rc); border-radius: 2px;
    background: linear-gradient(90deg, color-mix(in srgb, var(--rc) 14%, transparent), var(--panel));
    transition: transform .2s, border-color .2s;
}
.gx-rarity span { font-size: .7rem; letter-spacing: .2em; color: var(--rc); }
.gx-rarity i { font-style: normal; font-size: .8rem; letter-spacing: 2px; color: var(--rc); }
.gx-rarity:hover { transform: translateX(-4px); border-color: var(--rc); }
.r-legendary { --rc: var(--gold); }
.r-epic { --rc: var(--epic); }
.r-rare { --rc: var(--ct); }
.r-common { --rc: var(--muted); }

.gx-info { margin-top: auto; padding-top: 14px; }
.gx-info-row { display: flex; align-items: center; gap: 12px; padding: 12px 4px; border-top: 1px dashed var(--ln2); font-size: .85rem; color: var(--muted); }
.gx-info-row svg { width: 22px; height: 22px; flex: 0 0 22px; color: var(--ct); }
.gx-info-row b { margin-left: auto; font-weight: 400; color: var(--text); font-size: .85rem; }
.gx-info-note { font-size: .78rem; line-height: 1.5; }

/* ACTIONS */
.gx-actions {
    display: flex; justify-content: space-between; align-items: flex-end; gap: 20px;
    margin-top: 18px; padding-bottom: 28px; flex-wrap: wrap;
}
.gx-actions-left, .gx-actions-right { display: flex; gap: 14px; align-items: stretch; }

.gx-pill {
    display: inline-flex; align-items: center; justify-content: center; gap: .6rem;
    min-width: 150px; padding: .95rem 1.4rem;
    border: 1px solid var(--ln2); border-radius: 2px; background: transparent; color: var(--text);
    font-size: .76rem; letter-spacing: .1em; text-transform: uppercase; text-decoration: none; cursor: pointer;
    transition: transform .18s, border-color .18s, color .18s;
}
[data-theme="light"] .gx-pill { background: #FFFFFF; }
.gx-pill svg { width: 20px; height: 20px; }
.gx-pill:hover { transform: translateY(-2px); border-color: var(--cyan); color: var(--ct); }

.gx-pull-wrap { position: relative; text-align: center; min-width: 210px; display: flex; flex-direction: column; justify-content: flex-end; }
.gx-cost {
    display: inline-flex; align-items: center; gap: .5rem; align-self: center;
    margin: 0 auto -1px; padding: .3rem 1.4rem; position: relative; z-index: 1;
    font-size: 1rem; line-height: 1; background: var(--bg-soft);
    border: 1px solid var(--ln2); border-bottom: none; border-radius: 2px 2px 0 0;
}
[data-theme="light"] .gx-cost { background: #FFFFFF; }
.gx-cost svg { width: 16px; height: 16px; color: var(--ct); }

.gx-pull {
    display: block; width: 100%; min-height: 58px; cursor: pointer;
    font-family: 'Anta', sans-serif; font-size: .9rem; letter-spacing: .14em; text-transform: uppercase;
    background: transparent; color: var(--text); border: 1px solid var(--ln2); border-radius: 2px;
    transition: transform .18s, box-shadow .18s, border-color .18s, color .18s;
}
[data-theme="light"] .gx-pull { background: #FFFFFF; }
.gx-pull:hover:not(:disabled) { transform: translateY(-2px); border-color: var(--cyan); color: var(--ct); }
.gx-pull-10, [data-theme="light"] .gx-pull-10 { background: var(--cyan); color: var(--cyan-ink); border-color: var(--cyan); }
.gx-pull-10:hover:not(:disabled) { color: var(--cyan-ink); box-shadow: 0 14px 34px -14px var(--cyan); }
.gx-pull:active:not(:disabled) { transform: translateY(0); }
.gx-pull:disabled { opacity: .45; cursor: not-allowed; }

.gx-sale {
    position: absolute; right: -10px; top: -8px; z-index: 4; padding: .25rem .65rem;
    font-size: .62rem; letter-spacing: .14em; text-transform: uppercase;
    color: #1A1200; background: var(--gold); border-radius: 2px; transform: rotate(-6deg);
}
.gx-lack { position: absolute; bottom: -24px; left: 0; right: 0; margin: 0; font-size: .7rem; letter-spacing: .06em; color: var(--muted); text-align: center; }

/* HINT BARU DI SINI */
.gx-first-hint {
    display: inline-flex; align-items: center; gap: 10px; margin-top: 16px; padding: .6rem 1.1rem;
    border: 1px dashed var(--ln2); border-radius: 999px; background: var(--panel);
    color: var(--muted); font-size: .76rem; letter-spacing: .04em; cursor: pointer;
    transition: color .2s, border-color .2s, transform .2s;
}
.gx-first-hint:hover { color: var(--ct); border-color: var(--cyan); transform: translateX(2px); }
.gx-first-hint-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--cyan); box-shadow: 0 0 10px var(--cyan); animation: pulseDot 1.6s ease-in-out infinite; }
@keyframes pulseDot { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: .5; transform: scale(.75); } }

/* KOLEKSI */
.gx-collection {
    position: relative; padding: 24px; border: 1px solid var(--ln); border-radius: 6px;
    background: var(--panel); box-shadow: var(--shadow);
}
.gx-collection::before {
    content: ''; position: absolute; top: 0; left: 1.5rem; right: 1.5rem; height: 2px;
    background: linear-gradient(90deg, transparent, var(--cyan), transparent); opacity: .8;
}
.gx-col-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
.gx-col-head h2 { margin: 0; font-size: 1.7rem; line-height: 1.05; letter-spacing: -.01em; }
.gx-col-head p { margin: .5rem 0 0; color: var(--muted); font-size: .8rem; }
.gx-col-head strong { font-size: 2rem; color: var(--ct); }
.gx-bar { height: 4px; margin-bottom: 22px; border-radius: 999px; background: var(--ln); overflow: hidden; }
.gx-bar div { height: 100%; border-radius: 999px; background: linear-gradient(90deg, var(--cyan), var(--ct)); transition: width .8s; }

/* GRID ITEM KOLEKSI */
.item-grid { display: grid; grid-template-columns: repeat(6, minmax(0, 1fr)); gap: 12px; }
.item-cell {
    min-width: 0; position: relative; padding: 4px;
    border: 1px solid var(--ln); border-radius: 2px; background: var(--panel);
    transition: translate .3s cubic-bezier(.22, 1, .36, 1), border-color .2s, box-shadow .3s;
    animation: cellIn .55s cubic-bezier(.22, 1, .36, 1) backwards;
    animation-delay: min(calc(var(--i, 0) * 35ms + .2s), 1.2s);
}
.item-cell:hover { border-color: var(--cyan); translate: 0 -3px; box-shadow: 0 14px 30px -20px var(--cyan); }
.item-cell > * { max-width: 100%; }

.empty-collection { grid-column: 1 / -1; padding: 35px 15px; text-align: center; color: var(--muted); font-size: .8rem; }
.empty-symbol {
    display: flex; justify-content: center; align-items: center; width: 40px; height: 40px; margin: 0 auto 12px;
    border: 1px dashed var(--ln2); border-radius: 50%; color: var(--ct); font-size: 1.2rem;
    animation: crystalFloat 3s ease-in-out infinite;
}

/* =========================================================
   OVERLAYS
   ========================================================= */
.gacha-overlay {
    position: fixed; inset: 0; z-index: 1000; display: flex; align-items: center; justify-content: center;
    padding: 20px; overflow-y: auto; color: var(--text);
    background-color: var(--bg);
    background-image:
        radial-gradient(ellipse at 50% 0%, var(--glow) 0%, transparent 62%),
        linear-gradient(var(--grid) 1px, transparent 1px),
        linear-gradient(90deg, var(--grid) 1px, transparent 1px);
    background-size: auto, 72px 72px, 72px 72px;
}

.confirm-modal, .tutorial-modal {
    position: relative; background: var(--surface);
    border: 1px solid var(--ln2); border-radius: 6px; box-shadow: var(--shadow);
}
.confirm-modal { width: min(100%, 430px); padding: 30px; }

.modal-top-line, .result-top-line {
    position: absolute; top: 0; left: 1.5rem; right: 1.5rem; height: 2px;
    background: linear-gradient(90deg, transparent, var(--cyan), transparent); opacity: .85;
}
.modal-kicker { color: var(--ct); font-size: .6rem; letter-spacing: .2em; text-transform: uppercase; }

.modal-close {
    position: absolute; top: 12px; right: 12px; width: 2rem; height: 2rem;
    display: flex; align-items: center; justify-content: center;
    border: 1px solid var(--ln); border-radius: 999px; background: transparent;
    color: var(--muted); font-size: 1.2rem; line-height: 1; cursor: pointer;
    transition: color .2s, border-color .2s, transform .25s;
}
.modal-close:hover:not(:disabled) { color: var(--ct); border-color: var(--cyan); transform: rotate(90deg); }
.modal-close:disabled { opacity: .4; cursor: not-allowed; }

.confirm-crystal {
    position: relative; display: flex; justify-content: center; align-items: center;
    width: 85px; height: 85px; margin: 25px auto 18px;
    border: 1px dashed var(--ln2); border-radius: 50%;
    background: radial-gradient(circle, var(--glow), transparent 72%);
    animation: crystalFloat 3s ease-in-out infinite;
}
.confirm-crystal::before { content: ''; position: absolute; inset: -1px; border: 1px solid var(--cyan); border-radius: 50%; animation: ringPulse 2.6s ease-out infinite; }
.confirm-crystal svg { width: 56px; height: 56px; filter: drop-shadow(0 0 14px rgba(34, 211, 238, .5)); }
.confirm-title { margin: 0; text-align: center; font-size: 1.6rem; line-height: 1.05; letter-spacing: -.01em; }
.confirm-description { margin: 10px auto 22px; max-width: 300px; color: var(--muted); text-align: center; font-size: .85rem; line-height: 1.75; }

.transaction-box { padding: 6px 15px; border: 1px solid var(--ln); border-radius: 4px; background: var(--panel); }
.transaction-row { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 11px 0; color: var(--muted); font-size: .8rem; }
.transaction-row + .transaction-row { border-top: 1px dashed var(--ln2); }
.transaction-row strong { color: var(--text); font-size: 1rem; font-weight: 400; font-family: 'Iceberg', serif; -webkit-text-stroke: .3px currentColor; }
.transaction-cost { display: flex; align-items: center; gap: 6px; color: var(--ct) !important; font-size: 1.15rem !important; }
.transaction-cost svg { fill: var(--ct); width: 16px; height: 16px; }
.transaction-cost small { color: var(--faint); font-size: .5rem; letter-spacing: .2em; font-family: 'Anta', sans-serif; -webkit-text-stroke: 0; }
.transaction-balance strong { color: var(--ct); }

.confirm-warning { margin-top: 15px; padding-left: 10px; border-left: 2px solid var(--cyan); color: var(--muted); font-size: .72rem; line-height: 1.7; }
.confirm-actions { display: grid; grid-template-columns: 1fr 1.4fr; gap: 10px; margin-top: 24px; }

.cancel-button, .confirm-button, .tutorial-btn, .result-button {
    position: relative; overflow: hidden; min-height: 46px; padding: .8rem 1.1rem;
    font-family: 'Anta', sans-serif; font-size: .72rem; letter-spacing: .12em; text-transform: uppercase;
    border: 1px solid transparent; border-radius: 2px; cursor: pointer;
    transition: transform .18s, box-shadow .18s, border-color .18s, color .18s, background-color .18s;
}
.cancel-button, .tutorial-btn-ghost, .secondary-result-button { background: transparent; color: var(--muted); border-color: var(--ln2); }
[data-theme="light"] .cancel-button, [data-theme="light"] .tutorial-btn-ghost, [data-theme="light"] .secondary-result-button { background: #FFFFFF; }
.cancel-button:hover:not(:disabled), .tutorial-btn-ghost:hover:not(:disabled), .secondary-result-button:hover { color: var(--ct); border-color: var(--cyan); transform: translateY(-2px); }
.confirm-button, .tutorial-btn-primary, .primary-result-button { background: var(--cyan); color: var(--cyan-ink); border-color: var(--cyan); }
.confirm-button::after, .tutorial-btn-primary::after, .primary-result-button::after {
    content: ''; position: absolute; top: 0; bottom: 0; left: -60%; width: 40%; pointer-events: none;
    background: linear-gradient(100deg, transparent, rgba(255, 255, 255, .45), transparent); transform: skewX(-20deg);
}
.confirm-button:hover:not(:disabled), .tutorial-btn-primary:hover, .primary-result-button:hover { transform: translateY(-2px); box-shadow: 0 14px 34px -14px var(--cyan); }
.confirm-button:hover:not(:disabled)::after, .tutorial-btn-primary:hover::after, .primary-result-button:hover::after { animation: btnShine 1.1s ease; }
.cancel-button:disabled, .confirm-button:disabled, .tutorial-btn-ghost:disabled { opacity: .4; cursor: not-allowed; }

/* =========================================================
   TUTORIAL MODAL
   ========================================================= */
.tutorial-overlay { z-index: 1300; }
.tutorial-modal { width: min(100%, 460px); padding: 30px 28px 24px; }

.tutorial-progress { display: flex; justify-content: center; gap: 8px; margin-bottom: 18px; }
.tutorial-dot { width: 28px; height: 3px; border-radius: 99px; background: var(--ln2); transition: background .3s ease, width .3s ease; }
.tutorial-dot.on { width: 42px; background: var(--cyan); box-shadow: 0 0 10px var(--cyan); }
.tutorial-dot.done { background: var(--ct); opacity: .6; }

.tutorial-content { text-align: center; }
.tutorial-visual {
    width: 140px; height: 140px; margin: 0 auto 16px; display: grid; place-items: center; position: relative;
    border: 1px dashed var(--ln2); border-radius: 50%;
    background: radial-gradient(circle, var(--glow), transparent 72%);
    animation: crystalFloat 3.5s ease-in-out infinite;
}
.tv-orb svg { width: 74px; height: 74px; filter: drop-shadow(0 0 18px rgba(34, 211, 238, .6)); }

.tv-coins { position: relative; width: 90px; height: 90px; }
.tv-coin {
    position: absolute; display: grid; place-items: center; width: 46px; height: 46px; border-radius: 50%;
    color: #1A1200; font-size: 1.2rem; -webkit-text-stroke: 0;
    background: radial-gradient(circle at 30% 30%, #FFFFFF, var(--gold) 55%, #C8952A);
    box-shadow: 0 0 18px rgba(255, 215, 106, .5); animation: coinBounce 2.2s ease-in-out infinite;
}
.tv-coin.c1 { left: 0; top: 20px; }
.tv-coin.c2 { left: 22px; top: 0; animation-delay: .25s; }
.tv-coin.c3 { right: 0; top: 22px; animation-delay: .5s; }

.tv-pull { display: flex; flex-direction: column; align-items: center; gap: 6px; }
.tv-pull-btn {
    display: inline-block; padding: .55rem 1.5rem; border-radius: 2px;
    color: var(--cyan-ink); font-size: .8rem; letter-spacing: .14em; background: var(--cyan);
    animation: pullPulse 1.8s ease-in-out infinite;
}
.tv-pull-arrow { color: var(--ct); font-size: 1.2rem; animation: arrowDown 1.4s ease-in-out infinite; }
.tv-pull-coin { color: var(--gold); font-size: .85rem; letter-spacing: .1em; }

.tv-rarity { display: flex; gap: 6px; }
.tv-rarity .tr {
    display: grid; place-items: center; width: 28px; height: 40px; font-size: .85rem;
    border: 1px solid; border-radius: 2px; animation: rarityGlow 2.4s ease-in-out infinite;
}
.tr-c { color: var(--muted); border-color: var(--ln2); background: var(--panel); }
.tr-r { color: var(--ct); border-color: var(--cyan); background: var(--glow); animation-delay: .15s; }
.tr-e { color: var(--epic); border-color: var(--epic); background: color-mix(in srgb, var(--epic) 14%, transparent); animation-delay: .3s; }
.tr-l { color: var(--gold); border-color: var(--gold); background: color-mix(in srgb, var(--gold) 14%, transparent); animation-delay: .45s; }

.tutorial-kicker { color: var(--ct); font-size: .6rem; letter-spacing: .2em; margin: 0 0 6px; }
.tutorial-title { margin: 0 0 10px; font-size: 1.45rem; line-height: 1.1; letter-spacing: -.01em; }
.tutorial-desc { color: var(--muted); font-size: .85rem; line-height: 1.75; max-width: 340px; margin: 0 auto; }

.tutorial-tip {
    display: inline-flex; align-items: flex-start; gap: 8px; margin: 14px auto 0; padding: 10px 14px; text-align: left;
    border: 1px solid var(--ln); border-radius: 4px; background: var(--panel);
    color: var(--muted); font-size: .76rem; line-height: 1.6;
}
.tutorial-tip-badge {
    flex-shrink: 0; padding: 2px 7px; background: var(--cyan); color: var(--cyan-ink);
    font-size: .55rem; letter-spacing: .14em; border-radius: 2px;
}
.tutorial-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 22px; padding-top: 16px; border-top: 1px dashed var(--ln2); }

/* =========================================================
   OPENING ANIMATION
   ========================================================= */
.gacha-page.is-busy .gx-crystal, .gacha-page.is-busy .gx-moon, .gacha-page.is-busy .gx-spark { animation-play-state: paused; }

.opening-overlay { z-index: 1100; }
.opening-content { --burst: #22D3EE; --charge: 0; width: min(100%, 400px); text-align: center; position: relative; }
.opening-content.burst-epic, .opening-flash.burst-epic { --burst: #8A8CFF; }
.opening-content.burst-legendary, .opening-flash.burst-legendary { --burst: #FFD76A; }

.opening-rings { width: 240px; height: 240px; position: relative; display: flex; align-items: center; justify-content: center; margin: 0 auto 28px; }
.opening-ring { position: absolute; border: 1px solid var(--ln2); border-radius: 50%; opacity: calc(.55 + var(--charge) * .45); transform: scale(calc(1 - var(--charge) * .07)); will-change: transform, opacity; transition: opacity .45s ease; }
.ring-a { inset: 5px; border-top-color: var(--cyan); animation: orbitRotate 2.2s linear infinite; }
.ring-b { inset: 23px; border-color: var(--ln2); border-bottom-color: var(--cyan); animation: orbitRotate 1.6s linear infinite reverse; }
.ring-c { inset: 42px; border-style: dashed; animation: orbitRotate 6s linear infinite; }
.phase-burst .opening-ring { opacity: 0; }

.core-glow { position: absolute; z-index: 2; width: 220px; height: 220px; border-radius: 50%; pointer-events: none; background: radial-gradient(circle, var(--burst) 0%, transparent 68%); opacity: calc(.14 + var(--charge) * .55); transform: scale(calc(.7 + var(--charge) * .5)); will-change: transform, opacity; }
.core-glow::after { content: ''; position: absolute; inset: 22%; border-radius: 50%; background: radial-gradient(circle, #FFFFFF 0%, transparent 70%); opacity: 0; }
.phase-crack .core-glow { animation: glowCrack .75s cubic-bezier(.5, 0, .9, .6) forwards; }
.phase-crack .core-glow::after { animation: glowWhite .75s ease-in forwards; }
.phase-burst .core-glow { animation: glowBurst .7s cubic-bezier(.2, .8, .2, 1) forwards; }
.phase-burst .core-glow::after { opacity: 1; }

.opening-core { position: relative; z-index: 3; width: 150px; height: 150px; display: flex; align-items: center; justify-content: center; will-change: transform, opacity; animation: coreCharge .17s ease-in-out infinite alternate; }
.opening-core svg { width: 145px; height: 145px; overflow: visible; fill: initial; filter: drop-shadow(0 0 calc(10px + var(--charge) * 22px) var(--burst)); animation: coreWobble 2.6s ease-in-out infinite; will-change: transform; }
.phase-crack .opening-core svg { animation: coreWobble .45s ease-in-out infinite; }

.open-inner { fill: #FFFFFF; opacity: calc(var(--charge) * .45); animation: innerPulse .55s ease-in-out infinite alternate; }
.open-shine { animation: openShine 1.6s ease-in-out infinite; }
.phase-charge .core-glow { animation: glowBeat .8s ease-in-out infinite alternate; }
.phase-crack .opening-core { animation: coreCrack .75s ease-in-out forwards; }
.phase-burst .opening-core { animation: coreBurst .55s cubic-bezier(.2, .8, .2, 1) forwards; }

.crystal-cracks { filter: drop-shadow(0 0 3px var(--burst)) drop-shadow(0 0 8px var(--burst)); }
.crystal-cracks path {
    stroke-dasharray: 100;
    stroke-dashoffset: 100;
    stroke-dashoffset: calc(100 - 100 * clamp(0, (var(--charge) - var(--c0, .3)) / .2, 1));
}
.crystal-cracks path:nth-child(1) { --c0: .28; }
.crystal-cracks path:nth-child(2) { --c0: .46; }
.crystal-cracks path:nth-child(3) { --c0: .62; }
.crystal-cracks path:nth-child(4) { --c0: .74; }
.phase-crack .crystal-cracks path, .phase-burst .crystal-cracks path { animation: crackDraw .5s cubic-bezier(.3, .7, .2, 1) forwards; }
.phase-crack .crystal-cracks path:nth-child(2) { animation-delay: .1s; }
.phase-crack .crystal-cracks path:nth-child(3) { animation-delay: .18s; }
.phase-crack .crystal-cracks path:nth-child(4) { animation-delay: .26s; }

.energy-field, .shard-field { position: absolute; inset: 0; pointer-events: none; }
.energy-dot { position: absolute; left: 50%; top: 50%; width: 5px; height: 5px; margin: -2.5px; background: var(--burst); box-shadow: 0 0 8px var(--burst); opacity: 0; will-change: transform, opacity; animation: energyConverge 1.3s cubic-bezier(.5, 0, .9, .5) infinite; animation-delay: var(--delay); }
.phase-crack .energy-dot, .phase-burst .energy-dot { animation: none; opacity: 0; }

.burst-shard { position: absolute; left: 50%; top: 50%; width: 7px; height: 24px; margin: -12px -3.5px; background: linear-gradient(180deg, #FFFFFF, var(--burst)); clip-path: polygon(50% 0, 100% 100%, 0 100%); opacity: 0; will-change: transform, opacity; }
.phase-burst .burst-shard { animation: shardFly .95s cubic-bezier(.1, .75, .25, 1) forwards; }

.shockwave { position: absolute; inset: 50%; width: 60px; height: 60px; margin: -30px; border: 2px solid var(--burst); border-radius: 50%; opacity: 0; pointer-events: none; will-change: transform, opacity; }
.phase-burst .shockwave { animation: shockwave 1s cubic-bezier(.1, .7, .3, 1) forwards; }
.phase-burst .shockwave-2 { animation-delay: .14s; border-color: #FFFFFF; }

.opening-flash { --burst: #22D3EE; position: fixed; inset: 0; z-index: 5; pointer-events: none; background: radial-gradient(circle at center, #FFFFFF 0%, var(--burst) 35%, transparent 75%); will-change: opacity; animation: screenFlash .9s cubic-bezier(.2, .7, .3, 1) forwards; }
.phase-burst { animation: screenShake .55s cubic-bezier(.36, .07, .19, .97); }

.opening-status { color: var(--text); font-size: 1.15rem; letter-spacing: .12em; min-height: 24px; transition: color .4s ease, text-shadow .4s ease; }
.phase-crack .opening-status, .phase-burst .opening-status { color: var(--burst); text-shadow: 0 0 14px var(--burst); }

.opening-progress { width: min(100%, 260px); height: 4px; margin: 18px auto 12px; border-radius: 999px; background: var(--ln); overflow: hidden; }
.opening-progress-fill { width: 100%; height: 100%; transform-origin: left center; transform: scaleX(0); border-radius: 999px; background: linear-gradient(90deg, var(--cyan), var(--ct)); box-shadow: 0 0 14px var(--cyan); will-change: transform; }
.opening-subtitle { color: var(--faint); font-size: .55rem; letter-spacing: .2em; }

/* =========================================================
   RESULT MODAL
   ========================================================= */
.result-overlay { z-index: 1200; --rc: #22D3EE; }
.result-overlay.tone-epic { --rc: #8A8CFF; }
.result-overlay.tone-legendary { --rc: #FFD76A; }

.result-rays {
    position: fixed; left: 50%; top: 50%; width: 1100px; height: 1100px; margin: -550px 0 0 -550px;
    pointer-events: none; border-radius: 50%;
    background: repeating-conic-gradient(color-mix(in srgb, var(--rc) 22%, transparent) 0deg 6deg, transparent 6deg 24deg);
    -webkit-mask-image: radial-gradient(circle, #000 0%, transparent 68%);
            mask-image: radial-gradient(circle, #000 0%, transparent 68%);
    opacity: 0;
    animation: raysIn 1s cubic-bezier(.2, .8, .2, 1) .05s forwards, orbitRotate 28s linear infinite;
}
.result-sparks { position: fixed; left: 50%; top: 50%; width: 0; height: 0; pointer-events: none; z-index: 0; }
.result-spark { position: absolute; left: -3px; top: -3px; width: 6px; height: 6px; background: var(--rc); box-shadow: 0 0 12px var(--rc); opacity: 0; transform: rotate(45deg); will-change: transform, opacity; animation: sparkFly 1.1s cubic-bezier(.1, .75, .25, 1) var(--d) forwards; }

.result-modal {
    width: min(100%, 920px); max-height: min(90vh, 900px); overflow-y: auto; position: relative; z-index: 1;
    border: 1px solid var(--ln2); border-radius: 6px; background: var(--surface); box-shadow: var(--shadow);
    scrollbar-width: thin; scrollbar-color: var(--ln2) transparent;
    animation: modalPop .5s cubic-bezier(.22, 1, .36, 1) backwards, modalGlow 1.2s ease-out .1s backwards;
}
.result-modal::-webkit-scrollbar { width: 6px; }
.result-modal::-webkit-scrollbar-thumb { background: var(--ln2); border-radius: 99px; }

.result-heading { padding: 28px 28px 17px; text-align: center; }
.result-heading .modal-kicker { animation: fadeDown .6s ease-out .1s backwards; }
.result-title { margin: 12px 0 0; font-size: clamp(1.8rem, 4vw, 2.6rem); line-height: 1.05; letter-spacing: -.015em; animation: titleIn .9s cubic-bezier(.2, .8, .2, 1) .15s backwards; }
.result-title span { color: var(--ct); }
.result-description { margin: 8px 0 0; color: var(--muted); font-size: .85rem; line-height: 1.7; animation: fadeDown .6s ease-out .35s backwards; }

.duplicate-refund { display: inline-flex; align-items: center; gap: 12px; margin-top: 13px; padding: .5rem .8rem; border: 1px solid var(--ln); border-radius: 999px; background: var(--panel); font-size: .6rem; color: var(--muted); letter-spacing: .14em; }
.duplicate-refund strong { color: var(--ct); font-weight: 400; }

.result-grid { display: grid; gap: 12px; padding: 15px 25px 25px; justify-content: center; }
.multi-result { grid-template-columns: repeat(5, minmax(0, 1fr)); }
.single-result { grid-template-columns: minmax(120px, 180px); width: 100%; }

.result-item {
    min-width: 0; position: relative; text-align: center; padding: 11px 7px; overflow: hidden;
    border: 1px solid var(--ln); border-radius: 2px; background: var(--panel);
    transform-style: preserve-3d; will-change: transform, opacity;
    animation: itemFlip .75s cubic-bezier(.22, 1, .36, 1) both; animation-delay: var(--reveal-delay, 0ms);
    transition: translate .3s cubic-bezier(.22, 1, .36, 1), border-color .25s, box-shadow .3s;
}
.result-item::after { content: ''; position: absolute; inset: 0; z-index: 4; pointer-events: none; background: #FFFFFF; opacity: 0; animation: itemFlash .6s ease-out both; animation-delay: var(--reveal-delay, 0ms); }
.result-item:hover { translate: 0 -4px; border-color: var(--cyan); box-shadow: 0 14px 30px -18px var(--cyan); }

.reveal-ring { position: absolute; left: 50%; top: 40%; width: 70px; height: 70px; margin: -35px; border: 2px solid var(--cyan); border-radius: 50%; opacity: 0; pointer-events: none; z-index: 5; animation: itemRing .9s cubic-bezier(.1, .7, .3, 1) both; animation-delay: calc(var(--reveal-delay, 0ms) + 120ms); }
.reveal-ring.ring-common { border-color: var(--faint); animation-name: none; }
.reveal-ring.ring-rare { border-color: var(--cyan); }
.reveal-ring.ring-epic { border-color: var(--epic); border-width: 3px; }
.reveal-ring.ring-legendary { border-color: var(--gold); border-width: 3px; animation-iteration-count: 2; }

.result-item-glow { position: absolute; inset: 0; opacity: .12; pointer-events: none; }
.glow-common { background: var(--faint); }
.glow-rare { background: var(--cyan); }
.glow-epic { background: var(--epic); }
.glow-legendary { background: linear-gradient(135deg, var(--gold), #FFFFFF, var(--epic)); opacity: .2; animation: glowShift 2.6s ease-in-out infinite alternate; }

.result-item-art { width: 100%; aspect-ratio: 1; max-width: 112px; margin: 0 auto 10px; position: relative; display: flex; align-items: center; justify-content: center; border: 1px solid; border-radius: 2px; background: var(--bg-soft); overflow: hidden; }
[data-theme="light"] .result-item-art { background: #FFFFFF; }
.result-item-art::before { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, rgba(255, 255, 255, .09), transparent 60%); }

.rarity-epic::after, .rarity-legendary::after { content: ''; position: absolute; top: 0; bottom: 0; left: -60%; width: 40%; z-index: 3; pointer-events: none; background: linear-gradient(100deg, transparent, rgba(255, 255, 255, .28), transparent); transform: skewX(-20deg); animation: btnShine 2.8s ease-in-out infinite; }

.rarity-common { border-color: var(--ln2); }
.rarity-rare { border-color: var(--cyan); box-shadow: inset 0 0 22px var(--glow); }
.rarity-epic { border-color: var(--epic); box-shadow: inset 0 0 22px color-mix(in srgb, var(--epic) 28%, transparent); }
.rarity-legendary { border-color: var(--gold); box-shadow: inset 0 0 25px rgba(255, 215, 106, .3), 0 0 14px rgba(255, 215, 106, .25); animation: legendaryPulse 1.8s ease-in-out infinite alternate; }

.result-rarity-mark { position: absolute; top: 5px; left: 6px; z-index: 2; color: var(--ct); font-size: .6rem; }
.rarity-legendary .result-rarity-mark { color: var(--gold); }
.rarity-epic .result-rarity-mark { color: var(--epic); }

.new-tag { position: absolute; top: 5px; right: 5px; z-index: 2; padding: 2px 5px; color: var(--cyan-ink); background: var(--cyan); border-radius: 2px; font-size: .5rem; letter-spacing: .12em; animation: tagPop .6s cubic-bezier(.3, 1.6, .5, 1) .5s backwards; }

.result-item-img { position: relative; z-index: 1; width: 100%; height: 100%; padding: 10px; object-fit: contain; filter: drop-shadow(0 4px 10px rgba(0, 0, 0, .45)); }
.result-type-icon { position: relative; z-index: 1; color: var(--text); font-family: 'Iceberg', serif; font-size: 1.7rem; letter-spacing: -.02em; }
.rarity-rare .result-type-icon { color: var(--ct); }
.rarity-epic .result-type-icon { color: var(--epic); }
.rarity-legendary .result-type-icon { color: var(--gold); text-shadow: 0 0 18px var(--gold); }

.duplicate-tag { position: absolute; bottom: 0; left: 0; right: 0; z-index: 2; padding: 4px 2px; background: var(--bg-soft); border-top: 1px solid var(--ln2); color: var(--muted); font-size: .5rem; letter-spacing: .14em; }

.result-item-name { position: relative; font-size: .8rem; line-height: 1.5; overflow-wrap: anywhere; }
.result-rarity-label { position: relative; margin-top: 5px; font-size: .6rem; letter-spacing: .16em; }
.label-common { color: var(--muted); }
.label-rare { color: var(--ct); }
.label-epic { color: var(--epic); }
.label-legendary { color: var(--gold); }

.result-refund { position: relative; margin-top: 6px; font-size: .55rem; letter-spacing: .1em; color: var(--ct); animation: refundPulse 1.4s ease-in-out .6s 2; }

.result-footer { padding: 18px 25px 24px; border-top: 1px dashed var(--ln2); }
.result-summary { display: flex; align-items: center; justify-content: space-between; gap: 12px; color: var(--faint); font-size: .58rem; letter-spacing: .18em; margin-bottom: 16px; }
.result-summary strong { color: var(--ct); font-size: 1.1rem; letter-spacing: .02em; font-variant-numeric: tabular-nums; }
.result-actions { display: flex; justify-content: center; gap: 10px; }
.result-button { display: inline-flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; }
.primary-result-button svg { width: 15px; height: 15px; transition: transform .25s; }
.primary-result-button:hover svg { transform: translateX(4px); }

/* =========================================================
   KEYFRAMES
   ========================================================= */
@keyframes crystalFloat { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-9px); } }
@keyframes orbitRotate { from { rotate: 0deg; } to { rotate: 360deg; } }
@keyframes particleFloat { 0%, 100% { opacity: .3; translate: 0 0; } 50% { opacity: 1; translate: 0 -12px; } }
@keyframes riseIn { from { opacity: 0; transform: translateY(22px); } to { opacity: 1; transform: none; } }
@keyframes cellIn { from { opacity: 0; transform: translateY(14px) scale(.94); } to { opacity: 1; transform: translateY(0) scale(1); } }
@keyframes modalPop { from { opacity: 0; transform: translateY(18px) scale(.97); } to { opacity: 1; transform: translateY(0) scale(1); } }
@keyframes ringPulse { 0% { transform: scale(1); opacity: .7; } 100% { transform: scale(1.5); opacity: 0; } }
@keyframes btnShine { 0%, 55% { translate: 0 0; } 100% { translate: 520% 0; } }
@keyframes glowShift { from { opacity: .12; } to { opacity: .28; } }
@keyframes coreCharge { 0% { transform: translate3d(calc(var(--charge) * -4px), calc(var(--charge) * 1.5px), 0) rotate(calc(var(--charge) * -2deg)) scale(calc(1 + var(--charge) * .05)); } 100% { transform: translate3d(calc(var(--charge) * 4px), calc(var(--charge) * -1.5px), 0) rotate(calc(var(--charge) * 2deg)) scale(calc(1 + var(--charge) * .1)); } }
@keyframes coreCrack { 0% { transform: translate3d(0, 0, 0) rotate(0deg) scale(1.09); } 20% { transform: translate3d(-5px, 2px, 0) rotate(-2.5deg) scale(1.13); } 40% { transform: translate3d(5px, -2px, 0) rotate(2.5deg) scale(1.17); } 60% { transform: translate3d(-4px, 2px, 0) rotate(-2deg) scale(1.2); } 80% { transform: translate3d(3px, -1px, 0) rotate(1.5deg) scale(1.24); } 100% { transform: translate3d(0, 0, 0) rotate(0deg) scale(1.28); } }
@keyframes coreBurst { 0% { transform: scale(1.28); opacity: 1; } 100% { transform: scale(1.9); opacity: 0; } }
@keyframes glowCrack { 0% { opacity: .7; transform: scale(1.15); } 100% { opacity: 1; transform: scale(1.7); } }
@keyframes glowWhite { 0% { opacity: 0; } 100% { opacity: 1; } }
@keyframes glowBurst { 0% { opacity: 1; transform: scale(1.7); } 100% { opacity: 0; transform: scale(3); } }
@keyframes crackDraw { to { stroke-dashoffset: 0; } }
@keyframes energyConverge { 0% { opacity: 0; transform: rotate(var(--a)) translateX(135px) scale(1); } 20% { opacity: 1; } 100% { opacity: 0; transform: rotate(var(--a)) translateX(8px) scale(.2); } }
@keyframes shardFly { 0% { opacity: 1; transform: rotate(var(--a)) translateY(-20px) scale(.6); } 70% { opacity: .9; } 100% { opacity: 0; transform: rotate(var(--a)) translateY(calc(var(--far) * -1)) scale(1.1); } }
@keyframes shockwave { 0% { opacity: .9; transform: scale(.3); } 100% { opacity: 0; transform: scale(5.5); } }
@keyframes screenFlash { 0% { opacity: 0; } 10% { opacity: .95; } 100% { opacity: 0; } }
@keyframes screenShake { 10%, 90% { transform: translate3d(-1px, 0, 0); } 20%, 80% { transform: translate3d(3px, 1px, 0); } 30%, 50%, 70% { transform: translate3d(-7px, -3px, 0); } 40%, 60% { transform: translate3d(7px, 3px, 0); } }
@keyframes legendaryPulse { from { box-shadow: inset 0 0 20px rgba(255, 215, 106, .25), 0 0 10px rgba(255, 215, 106, .2); } to { box-shadow: inset 0 0 28px rgba(255, 215, 106, .45), 0 0 20px rgba(255, 215, 106, .4); } }
@keyframes raysIn { from { opacity: 0; scale: .3; } to { opacity: 1; scale: 1; } }
@keyframes sparkFly { 0% { opacity: 1; transform: rotate(var(--a)) translateY(0) rotate(45deg) scale(1.2); } 100% { opacity: 0; transform: rotate(var(--a)) translateY(calc(var(--far) * -1)) rotate(45deg) scale(.2); } }
@keyframes modalGlow { 0% { box-shadow: 0 0 0 0 transparent, 0 25px 90px rgba(0, 0, 0, .7); } 30% { box-shadow: 0 0 90px 10px var(--rc), 0 25px 90px rgba(0, 0, 0, .7); } 100% { box-shadow: var(--shadow); } }
@keyframes titleIn { from { opacity: 0; letter-spacing: 14px; filter: blur(6px); } to { opacity: 1; letter-spacing: -.015em; filter: blur(0); } }
@keyframes fadeDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
@keyframes itemFlip { 0% { opacity: 0; transform: perspective(700px) rotateY(100deg) translateY(20px) scale(.7); } 55% { opacity: 1; transform: perspective(700px) rotateY(-14deg) translateY(-4px) scale(1.06); } 100% { opacity: 1; transform: perspective(700px) rotateY(0) translateY(0) scale(1); } }
@keyframes itemFlash { 0% { opacity: 0; } 25% { opacity: .75; } 100% { opacity: 0; } }
@keyframes itemRing { 0% { opacity: .9; transform: scale(.4); } 100% { opacity: 0; transform: scale(3.4); } }
@keyframes tagPop { 0% { opacity: 0; transform: scale(0) rotate(-20deg); } 100% { opacity: 1; transform: scale(1) rotate(0); } }
@keyframes refundPulse { 0%, 100% { text-shadow: none; } 50% { text-shadow: 0 0 12px var(--cyan); } }
@keyframes coreWobble {
    0%   { transform: perspective(520px) translateY(0)    rotateY(calc(-12deg - var(--charge) * 26deg)) rotateZ(-2deg) scale(1); }
    25%  { transform: perspective(520px) translateY(-9px) rotateY(0deg)                                 rotateZ(1deg)  scale(1.04); }
    50%  { transform: perspective(520px) translateY(0)    rotateY(calc(12deg + var(--charge) * 26deg))  rotateZ(2deg)  scale(1); }
    75%  { transform: perspective(520px) translateY(-9px) rotateY(0deg)                                 rotateZ(-1deg) scale(1.04); }
    100% { transform: perspective(520px) translateY(0)    rotateY(calc(-12deg - var(--charge) * 26deg)) rotateZ(-2deg) scale(1); }
}
@keyframes innerPulse { from { fill-opacity: .25; } to { fill-opacity: 1; } }
@keyframes openShine { 0%, 35% { translate: 0 0; } 100% { translate: 340px 0; } }
@keyframes glowBeat { from { scale: .92; } to { scale: 1.1; } }
@keyframes coinBounce { 0%, 100% { transform: translateY(0) rotate(0deg); } 50% { transform: translateY(-10px) rotate(15deg); } }
@keyframes pullPulse { 0%, 100% { box-shadow: 0 0 18px -4px var(--cyan); transform: scale(1); } 50% { box-shadow: 0 0 30px -2px var(--cyan); transform: scale(1.05); } }
@keyframes arrowDown { 0%, 100% { transform: translateY(0); opacity: .6; } 50% { transform: translateY(4px); opacity: 1; } }
@keyframes rarityGlow { 0%, 100% { transform: translateY(0) scale(1); } 50% { transform: translateY(-3px) scale(1.06); } }

/* =========================================================
   RESPONSIVE (DIPERBAIKI UNTUK HP)
   ========================================================= */
@media (max-width: 1100px) {
    .item-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
}

@media (max-width: 1000px) {
    .gx-body { grid-template-columns: 1fr; gap: 16px; }
    .gx-side { grid-template-columns: repeat(3, 1fr); gap: 8px; }
    .gx-tab { flex-direction: column; text-align: center; justify-content: center; padding: 12px 8px; min-height: auto; }
    .gx-tab-ico { width: 32px; height: 32px; flex: 0 0 32px; border: none; background: transparent; }
    .gx-tab-ico svg { width: 20px; height: 20px; }
    .gx-tab b { font-size: 1rem; margin-top: 6px; }
    .gx-tab small { display: none; }
    
    .gx-stage { grid-template-columns: 1fr; min-height: auto; }
    .gx-hero { padding: 24px; min-height: auto; }
    .gx-right { padding: 20px; border-left: none; border-top: 1px dashed var(--ln2); }
    .gx-rarity-list { grid-template-columns: repeat(2, 1fr); }
    .gx-featured { position: relative; left: 0; bottom: 0; margin-top: 20px; width: 100%; }
}

@media (max-width: 640px) {
    .gacha-page { padding-bottom: 20px; }
    
    .gx-top { gap: 12px; margin-bottom: 14px; }
    .gx-back { width: 2.2rem; height: 2.2rem; }
    .gx-title { font-size: 1.6rem; }
    .gx-currency { gap: 6px; }
    .gx-chip { min-width: 0; padding: 0.4rem 0.6rem; font-size: 0.9rem; }
    .gx-chip span { font-size: 0.95rem; }
    .gx-help { width: 2.2rem; height: 2.2rem; font-size: 0.95rem; }
    
    .gx-side { grid-template-columns: repeat(3, 1fr); overflow-x: auto; padding-bottom: 4px; }
    .gx-tab b { font-size: 0.85rem; }
    
    .gx-hero { padding: 20px; }
    .gx-hero-title { font-size: 2.2rem; }
    .gx-hero-text { font-size: 0.85rem; margin-top: 10px; }
    
    /* Gambar Kristal diposisikan lebih kecil dan agak ke kanan agar tidak menutupi teks */
    .gx-hero-art { 
        position: relative; right: auto; top: auto; width: 100%; height: 200px; margin-top: 15px; 
        opacity: 1;
    }
    .gx-moon { width: 200px; height: 200px; }
    .gx-crystal { width: 140px; }
    
    .gx-featured { margin-top: 15px; padding: .5rem .8rem .5rem .5rem; gap: 10px; }
    .gx-featured b { font-size: 0.95rem; }
    .gx-stars { font-size: 0.75rem; }
    .gx-featured small { font-size: 0.65rem; }
    
    .gx-right { padding: 15px; }
    .gx-rarity { height: 60px; padding: 8px 12px; }
    .gx-rarity span { font-size: 0.65rem; }
    .gx-info-row { font-size: 0.75rem; padding: 10px 0; }
    
    .gx-actions { flex-direction: column; align-items: stretch; gap: 15px; margin-top: 15px; padding-bottom: 20px; }
    .gx-actions-left, .gx-actions-right { flex-direction: column; gap: 10px; width: 100%; }
    .gx-pill { width: 100%; padding: 0.8rem; font-size: 0.7rem; }
    .gx-pull-wrap { width: 100%; min-width: 0; }
    .gx-pull { padding: 0.9rem; min-height: 50px; }
    
    .gx-sale { right: 0; top: -10px; transform: scale(0.9); }
    
    .gx-first-hint { width: 100%; justify-content: center; font-size: 0.7rem; padding: 0.5rem; }

    .gx-collection { padding: 15px; }
    .gx-col-head { flex-direction: column; align-items: flex-start; gap: 10px; }
    .gx-col-head h2 { font-size: 1.4rem; }
    .gx-col-head p { font-size: 0.75rem; }
    .gx-col-head strong { font-size: 1.6rem; }
    .item-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 8px; }
    .item-cell { padding: 2px; }

    .confirm-modal, .tutorial-modal { padding: 24px 18px; }
    
    .result-heading { padding: 20px 15px 10px; }
    .result-title { font-size: 1.6rem; }
    .result-description { font-size: 0.75rem; }
    .result-grid { padding: 10px; gap: 8px; }
    .multi-result { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .multi-result .result-item-art { max-width: 90px; }
    .result-item { padding: 8px 5px; }
    .result-footer { padding: 15px; }
    .result-actions { flex-direction: column; }
    .result-button { width: 100%; }
    
    .opening-rings { width: 200px; height: 200px; }
    .opening-core { width: 120px; height: 120px; }
    .opening-core svg { width: 115px; height: 115px; }
    .opening-status { font-size: 1rem; }
    .opening-progress { width: 80%; }
    
    .tutorial-visual { width: 110px; height: 110px; }
    .tutorial-title { font-size: 1.2rem; }
    .tutorial-desc { font-size: 0.8rem; }
    .tutorial-actions { flex-direction: column-reverse; gap: 8px; }
    .tutorial-btn { width: 100%; }
}

@media (prefers-reduced-motion: reduce) {
    .gacha-page *, .gacha-page *::before, .gacha-page *::after, .gacha-overlay *, .gacha-overlay *::before, .gacha-overlay *::after {
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
        scroll-behavior: auto !important;
        transition-duration: .01ms !important;
    }
}
</style>

<script>
function gachaApp() {
    return {
        /* ============ STATE UTAMA ============ */
        points: {{ (int) auth()->user()->total_points }},

        pulling: false,
        showConfirm: false,
        showOpening: false,
        showResult: false,

        selectedCount: 1,
        selectedCost: 50,

        results: [],
        revealed: 0,
        duplicatePoints: 0,

        openingStatus: 'CRYSTAL INITIALIZING',
        openingProgress: 0,
        openingBurst: false,
        openingPhase: 'charge',
        bestRarity: 'common',

        /* ============ STATE TUTORIAL ============ */
        showTutorial: false,
        tutorialStep: 0,
        tutorialSeen: false,

        tutorialSteps: [
            {
                key: 'welcome',
                title: 'Selamat Datang di Gacha!',
                desc: 'Di sini kamu bisa membuka kristal untuk mendapatkan item kosmetik eksklusif — skin, emote, dan badge.',
                tip: 'Tutorial singkat ini akan memandumu melakukan spin pertamamu.'
            },
            {
                key: 'points',
                title: 'Kumpulkan Poin Dulu',
                desc: 'Setiap pull membutuhkan poin. Poin bisa didapat dari battle, misi harian, atau refund item duplikat.',
                tip: 'Cek jumlah poinmu di pojok kanan atas. Kalau kurang, selesaikan misi dulu!'
            },
            {
                key: 'pull',
                title: 'Cara Spin Kristal',
                desc: 'Klik tombol "1x Pull" (50 poin) untuk satu item, atau "10x Pull" (450 poin — diskon!) untuk sepuluh item sekaligus.',
                tip: '10x Pull lebih hemat 50 poin. Konfirmasi dulu sebelum poin dipotong.'
            },
            {
                key: 'rarity',
                title: 'Kenali Tingkat Kelangkaan',
                desc: 'Item punya 4 rarity: Common, Rare, Epic, dan Legendary. Semakin tinggi rarity, semakin langka dan berharga.',
                tip: 'Item duplikat otomatis dikonversi jadi poin, jadi tidak ada yang terbuang!'
            }
        ],

        /* ============ INIT ============ */
        init() {
            try {
                this.tutorialSeen = localStorage.getItem('navo_gacha_tutorial_v1') === '1';
            } catch (e) {
                this.tutorialSeen = false;
            }

            if (!this.tutorialSeen) {
                setTimeout(() => this.openTutorial(), 700);
            }
        },

        /* ============ TUTORIAL HANDLERS ============ */
        openTutorial() {
            this.tutorialStep = 0;
            this.showTutorial = true;
        },

        closeTutorial() {
            this.showTutorial = false;
            this.tutorialSeen = true;
            try {
                localStorage.setItem('navo_gacha_tutorial_v1', '1');
            } catch (e) { /* ignore */ }
        },

        nextTutorialStep() {
            if (this.tutorialStep < this.tutorialSteps.length - 1) {
                this.tutorialStep++;
            } else {
                this.closeTutorial();
            }
        },

        prevTutorialStep() {
            if (this.tutorialStep > 0) this.tutorialStep--;
        },

        /* ============ HELPERS ============ */
        sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        },

        spot(event) {
            const el = event.currentTarget;
            const rect = el.getBoundingClientRect();
            el.style.setProperty('--mx', (event.clientX - rect.left) + 'px');
            el.style.setProperty('--my', (event.clientY - rect.top) + 'px');
        },

        itemImage(item) {
            const src =
                item.image_url ||
                item.image ||
                item.image_path ||
                item.icon ||
                item.thumbnail ||
                item.preview ||
                '';

            if (!src) return '';

            if (/^(https?:)?\/\//.test(src) || src.startsWith('/') || src.startsWith('data:')) {
                return src;
            }

            return '/storage/' + src.replace(/^storage\//, '');
        },

        typeMark(type) {
            const marks = { skin: 'SK', emote: 'EM', badge: 'BD' };
            return marks[type] || 'IT';
        },

        rarityMark(rarity) {
            const marks = { common: 'C', rare: 'R', epic: 'E', legendary: 'L' };
            return marks[rarity] || 'C';
        },

        getBestRarity(results) {
            const order = ['common', 'rare', 'epic', 'legendary'];
            let best = 0;

            (results || []).forEach(r => {
                const idx = order.indexOf(String(r.rarity || 'common').toLowerCase());
                if (idx > best) best = idx;
            });

            return order[best];
        },

        /* ============ CONFIRM ============ */
        openConfirm(count) {
            if (this.pulling) return;

            const cost = count === 10 ? 450 : 50;

            if (this.points < cost) {
                alert('Poin kamu tidak mencukupi.');
                return;
            }

            this.selectedCount = count;
            this.selectedCost = cost;
            this.showConfirm = true;
        },

        /* ============ ANIMATION ============ */
        animateProgress(to, duration, easing = t => t) {
            const from = this.openingProgress;
            const startTime = performance.now();

            return new Promise(resolve => {
                const step = now => {
                    const t = Math.min(1, (now - startTime) / duration);
                    this.openingProgress = +(from + (to - from) * easing(t)).toFixed(1);

                    if (t < 1) requestAnimationFrame(step);
                    else resolve();
                };
                requestAnimationFrame(step);
            });
        },

        async runOpeningAnimation(request) {
            this.showOpening = true;
            this.openingBurst = false;
            this.openingPhase = 'charge';
            this.bestRarity = 'common';
            this.openingProgress = 0;
            this.openingStatus = 'CRYSTAL INITIALIZING';

            const stages = [
                { to: 25, status: 'CHARGING CRYSTAL',  duration: 550 },
                { to: 50, status: 'ENERGY DETECTED',   duration: 550 },
                { to: 75, status: 'ENERGY CRITICAL',   duration: 550 },
                { to: 92, status: 'BREAKING THE SEAL', duration: 450 }
            ];

            for (const stage of stages) {
                this.openingStatus = stage.status;
                await this.animateProgress(stage.to, stage.duration);
            }

            const res = await request;
            if (!res.ok || !res.data.success) return res;

            this.bestRarity = this.getBestRarity(res.data.results);

            this.openingPhase = 'crack';
            this.openingStatus = 'SEAL BROKEN';
            await Promise.all([this.animateProgress(100, 300), this.sleep(750)]);

            this.openingPhase = 'burst';
            this.openingBurst = true;
            this.openingStatus =
                this.bestRarity === 'legendary' ? 'LEGENDARY DETECTED' :
                this.bestRarity === 'epic'      ? 'EPIC REWARD' :
                                                  'CRYSTAL OPENED';

            await this.sleep(950);
            return res;
        },

        /* ============ PULL ============ */
        async pull(count) {
            if (this.pulling) return;

            const cost = count === 10 ? 450 : 50;

            if (this.points < cost) {
                alert('Poin kamu tidak mencukupi.');
                this.showConfirm = false;
                return;
            }

            this.pulling = true;
            this.showConfirm = false;
            this.results = [];
            this.revealed = 0;
            this.duplicatePoints = 0;

            try {
                const request = fetch('{{ route('play.gacha.pull') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ count })
                })
                    .then(async response => ({ ok: response.ok, data: await response.json() }))
                    .catch(() => ({ ok: false, data: { success: false, error: 'Koneksi bermasalah.' } }));

                const { ok, data } = await this.runOpeningAnimation(request);

                if (!ok || !data.success) {
                    this.showOpening = false;
                    alert(data.error || 'Gagal melakukan pull.');
                    return;
                }

                this.results = Array.isArray(data.results) ? data.results : [];
                this.duplicatePoints = Number(data.duplicate_points || 0);
                this.points = Number(data.new_points ?? this.points);

                this.showOpening = false;
                this.showResult = true;

                await this.sleep(450);

                for (let i = 0; i < this.results.length; i++) {
                    await this.sleep(count === 1 ? 180 : 130);
                    this.revealed = i + 1;
                }

            } catch (error) {
                this.showOpening = false;
                alert('Terjadi kesalahan saat pull. Silakan coba lagi.');
                console.error('Gacha pull error:', error);
            } finally {
                this.pulling = false;
            }
        },

        closeResult() {
            this.showResult = false;
            setTimeout(() => window.location.reload(), 200);
        }
    };
}
</script>

@endsection