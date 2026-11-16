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
                <div class="gx-chip gx-chip-dim">PULL <b>{{ $stats['total_pulls'] }}</b></div>

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

                                {{-- LOGO: ganti file di public/images/gacha-logo.png --}}
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
                                <polygon points="15,22 29,9 51,9 65,22 40,70" fill="#112E81"/>
                                <polygon points="15,22 40,22 40,70" fill="#4382DF"/>
                                <polygon points="40,22 65,22 40,70" fill="#4647AE"/>
                                <polygon points="29,9 51,9 65,22 15,22" fill="#4382DF"/>
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
                    <polygon points="15,22 29,9 51,9 65,22 40,70" fill="#112E81"/>
                    <polygon points="15,22 40,22 40,70" fill="#4382DF"/>
                    <polygon points="40,22 65,22 40,70" fill="#4647AE"/>
                    <polygon points="29,9 51,9 65,22 15,22" fill="#4382DF"/>
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

                {{-- Partikel energi tersedot ke kristal --}}
                <div class="energy-field">
                    <template x-for="n in 16" :key="'e' + n">
                        <span class="energy-dot"
                              :style="'--a:' + (n * 22.5) + 'deg; --delay:' + ((n % 8) * 0.14) + 's'"></span>
                    </template>
                </div>

                <div class="opening-ring ring-a"></div>
                <div class="opening-ring ring-b"></div>
                <div class="opening-ring ring-c"></div>

                {{-- Shockwave --}}
                <div class="shockwave"></div>
                <div class="shockwave shockwave-2"></div>

                {{-- Pecahan kristal --}}
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
                                <stop offset="0%" stop-color="#8FB8FF"/>
                                <stop offset="100%" stop-color="#4647AE"/>
                            </linearGradient>
                            <linearGradient id="oLeft" x1="0" y1="0" x2="1" y2="1">
                                <stop offset="0%" stop-color="#112E81"/>
                                <stop offset="100%" stop-color="#4382DF"/>
                            </linearGradient>
                            <linearGradient id="oRight" x1="0" y1="0" x2="1" y2="1">
                                <stop offset="0%" stop-color="#4647AE"/>
                                <stop offset="100%" stop-color="#0B1450"/>
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

                        <polygon points="35,42 65,18 115,18 145,42 90,155" fill="#112E81"/>
                        <polygon points="35,42 90,42 90,155" fill="url(#oLeft)"/>
                        <polygon points="90,42 145,42 90,155" fill="url(#oRight)"/>
                        <polygon points="65,18 115,18 145,42 35,42" fill="url(#oTop)"/>
                        <polygon points="35,42 65,18 90,42" fill="#8FB8FF" opacity=".5"/>
                        <polygon points="90,42 115,18 145,42" fill="#4647AE" opacity=".5"/>
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
@import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700;900&family=Rajdhani:wght@500;600;700&family=Playfair+Display:ital,wght@1,700;1,800&family=Cinzel:wght@600;700&display=swap');

[x-cloak] { display: none !important; }

/* =========================================================
   BASE
   ========================================================= */
.gacha-page {
    --g-navy: #090E39;
    --g-blue: #112E81;
    --g-purple: #4647AE;
    --g-bright: #4382DF;
    --g-white: #FFFFFF;
    --g-muted: #8793B7;
    --g-border: rgba(67, 130, 223, .22);
    --g-cyan: #22E4FF;
    --g-gold: #FFD76A;
    position: relative;
    isolation: isolate;
    color: var(--g-white);
    padding-bottom: 40px;
    overflow-x: hidden;
    width: 100%;
    background: transparent;
    font-family: 'Rajdhani', 'Segoe UI', system-ui, sans-serif;
    font-weight: 600;
}

.gacha-page, .gacha-page * { box-sizing: border-box; }
.gacha-page button, .gacha-page a, .gacha-page input { font-family: inherit; }

.gacha-page::before {
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

.confirm-title, .result-title, .opening-status, .confirm-button, .cancel-button,
.result-button, .result-summary strong, .tutorial-title, .tutorial-btn {
    font-family: 'Orbitron', 'Rajdhani', sans-serif;
}

/* =========================================================
   BACKGROUND DECOR
   ========================================================= */
.bg-decor { position: fixed; inset: 0; z-index: -1; overflow: hidden; pointer-events: none; }

.bg-grid {
    position: absolute;
    inset: -60px;
    background-image:
        linear-gradient(rgba(67, 130, 223, .07) 1px, transparent 1px),
        linear-gradient(90deg, rgba(67, 130, 223, .07) 1px, transparent 1px);
    background-size: 56px 56px;
    -webkit-mask-image: radial-gradient(ellipse at 50% 30%, #000 0%, transparent 72%);
            mask-image: radial-gradient(ellipse at 50% 30%, #000 0%, transparent 72%);
    animation: gridDrift 14s linear infinite;
}

.bg-stars {
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
    animation: twinkle 5s ease-in-out infinite alternate;
}

.bg-orb { position: absolute; border-radius: 50%; filter: blur(80px); will-change: translate; }
.orb-1 { width: 420px; height: 420px; left: -120px; top: -100px; background: rgba(70, 71, 174, .38); animation: orbDrift 16s ease-in-out infinite alternate; }
.orb-2 { width: 380px; height: 380px; right: -100px; top: 30%; background: rgba(67, 130, 223, .26); animation: orbDrift 20s ease-in-out -6s infinite alternate-reverse; }
.orb-3 { width: 340px; height: 340px; left: 30%; bottom: -160px; background: rgba(17, 46, 129, .5); animation: orbDrift 18s ease-in-out -3s infinite alternate; }

.bg-decor::after {
    content: "";
    position: absolute;
    inset: 0;
    background: repeating-linear-gradient(0deg, rgba(255, 255, 255, .025) 0 1px, transparent 1px 3px);
}

/* =========================================================
   LAYOUT UTAMA (GX)
   ========================================================= */
.gx {
    --gx-line: rgba(95, 140, 255, .45);
    --gx-glow: #6C8BFF;
    --gx-pale: #CFE0FF;
    font-family: 'Segoe UI', system-ui, sans-serif;
    color: #fff;
    animation: riseIn .8s cubic-bezier(.22, 1, .36, 1) backwards;
}
.gx svg { fill: currentColor; }

/* TOP BAR */
.gx-top { display: flex; align-items: center; gap: 18px; margin-bottom: 18px; }
.gx-back { width: 44px; height: 44px; color: var(--gx-pale); display: grid; place-items: center; filter: drop-shadow(0 0 10px var(--gx-glow)); }
.gx-back svg { width: 36px; height: 36px; fill: none; }
.gx-title { font: italic 800 clamp(30px, 4vw, 44px)/1 'Playfair Display', serif; color: var(--gx-pale); text-shadow: 0 0 22px rgba(108,139,255,.8); }
.gx-title i { font-size: .6em; font-style: normal; vertical-align: super; }
.gx-currency { margin-left: auto; display: flex; gap: 12px; align-items: center; }
.gx-chip { display: flex; align-items: center; gap: 10px; min-width: 130px; padding: 8px 18px; border-radius: 999px; background: rgba(9,14,57,.85); border: 1px solid var(--gx-line); font: 700 18px 'Segoe UI', sans-serif; box-shadow: 0 0 18px rgba(108,139,255,.2); }
.gx-chip svg { width: 26px; height: 26px; color: #8FB8FF; filter: drop-shadow(0 0 8px #6C8BFF); }
.gx-chip-dim { min-width: 0; font-size: 12px; letter-spacing: 1.5px; color: #8793B7; }
.gx-chip-dim b { color: #fff; font-size: 16px; }

/* TOMBOL BANTUAN */
.gx-help {
    width: 42px; height: 42px; display: grid; place-items: center;
    border-radius: 50%; cursor: pointer;
    background: rgba(9, 14, 57, .85);
    border: 1px solid var(--gx-line);
    color: #BFD4FF; font: 900 18px 'Orbitron', sans-serif;
    box-shadow: 0 0 14px rgba(108, 139, 255, .25);
    transition: .25s ease;
}
.gx-help:hover {
    color: #fff; background: #2D6BFF; border-color: #9DBEFF;
    box-shadow: 0 0 22px rgba(70, 120, 255, .6);
    transform: translateY(-2px) rotate(8deg);
}

/* BODY */
.gx-body { display: grid; grid-template-columns: 250px minmax(0, 1fr); gap: 20px; align-items: start; }
.gx-side { display: grid; gap: 14px; }
.gx-tab { display: flex; align-items: center; gap: 14px; padding: 12px; min-height: 84px; text-align: left; color: #fff; text-decoration: none; cursor: pointer; border: 1px solid var(--gx-line); border-radius: 8px; background: linear-gradient(120deg, rgba(17,46,129,.35), rgba(9,14,57,.85)); font-family: inherit; transition: .25s; }
.gx-tab b { display: block; font: 700 19px 'Cinzel', serif; letter-spacing: .5px; }
.gx-tab small { color: #8793B7; font-size: 14px; }
.gx-tab:hover { border-color: #8FB8FF; transform: translateX(3px); }
.gx-tab.on { background: linear-gradient(100deg, #2D6BFF, #1A2FA8); border-color: #9DBEFF; box-shadow: 0 0 26px rgba(70,120,255,.55), inset 0 0 20px rgba(255,255,255,.12); clip-path: polygon(0 0, calc(100% - 16px) 0, 100% 50%, calc(100% - 16px) 100%, 0 100%); }
.gx-tab.on small { color: #DCE8FF; }
.gx-tab-ico { width: 52px; height: 52px; flex: 0 0 52px; display: grid; place-items: center; border-radius: 6px; background: radial-gradient(circle, rgba(108,139,255,.4), rgba(9,14,57,.7)); color: #BFD4FF; }
.gx-tab-ico svg { width: 28px; height: 28px; }

/* STAGE */
.gx-stage { position: relative; display: grid; grid-template-columns: minmax(0, 1fr) 290px; min-height: 520px; overflow: hidden; border: 1px solid var(--gx-line); border-radius: 6px; background: radial-gradient(ellipse at 40% 30%, #1B2F9E 0%, #0A1352 45%, #050A2E 100%); box-shadow: 0 0 40px rgba(40,70,200,.3); }
.gx-hero { position: relative; padding: 28px 30px; min-height: 520px; }
.gx-badge { display: inline-block; padding: 5px 22px; font-size: 14px; font-weight: 600; background: rgba(9,14,57,.8); border: 1px solid var(--gx-glow); clip-path: polygon(10px 0, calc(100% - 10px) 0, 100% 50%, calc(100% - 10px) 100%, 10px 100%, 0 50%); }
.gx-hero-title { position: relative; z-index: 3; margin-top: 18px; font: italic 800 clamp(40px, 5.4vw, 68px)/.95 'Playfair Display', serif; text-shadow: 0 0 30px rgba(108,139,255,.9); }
.gx-hero-title span { color: var(--gx-pale); }
.gx-hero-title i { font-style: normal; font-size: .4em; margin-left: 8px; vertical-align: top; }
.gx-hero-text { position: relative; z-index: 3; margin-top: 16px; max-width: 290px; font-size: 16px; line-height: 1.5; font-weight: 500; text-shadow: 0 2px 8px #000; }

.gx-hero-art { position: absolute; right: 6%; top: 8%; width: 58%; height: 78%; display: grid; place-items: center; pointer-events: none; }
.gx-moon { position: absolute; width: 300px; height: 300px; border-radius: 50%; border: 18px solid rgba(143,184,255,.18); box-shadow: 0 0 80px rgba(108,139,255,.45), inset 0 0 60px rgba(108,139,255,.3); animation: orbitRotate 40s linear infinite; }
.gx-crystal { position: relative; width: clamp(170px, 20vw, 250px); max-height: 100%; height: auto; object-fit: contain; filter: drop-shadow(0 0 30px #4B7BFF) drop-shadow(0 0 70px rgba(138,140,255,.6)); animation: crystalFloat 3.5s ease-in-out infinite; }
.gx-stage.is-active .gx-crystal, .gx-stage.is-active .gx-moon { animation-play-state: paused; }
.gx-spark { position: absolute; width: 7px; height: 7px; background: #CFE0FF; box-shadow: 0 0 12px #6C8BFF; transform: rotate(45deg); animation: particleFloat 3s ease-in-out infinite; }
.gx-spark.s1 { left: 18%; top: 28%; } .gx-spark.s2 { right: 12%; top: 40%; animation-delay: .8s; } .gx-spark.s3 { left: 30%; bottom: 18%; animation-delay: 1.5s; }

.gx-featured { position: absolute; left: 24px; bottom: 22px; z-index: 3; display: flex; align-items: center; gap: 14px; padding: 8px 22px 8px 8px; background: linear-gradient(90deg, rgba(9,14,57,.92), rgba(9,14,57,.4)); border: 1px solid var(--gx-line); clip-path: polygon(0 0, 100% 0, calc(100% - 14px) 100%, 0 100%); }
.gx-featured-ico { width: 54px; height: 54px; display: grid; place-items: center; background: linear-gradient(145deg, #2D6BFF, #1A2FA8); clip-path: polygon(50% 0, 100% 25%, 100% 75%, 50% 100%, 0 75%, 0 25%); }
.gx-featured-ico svg { width: 28px; height: 28px; }
.gx-featured b { font: 700 20px 'Cinzel', serif; }
.gx-stars { color: #FFD76A; letter-spacing: 2px; font-size: 16px; }
.gx-featured small { display: block; font-size: 12px; color: #B9C6EE; }

/* PANEL KANAN */
.gx-right { position: relative; z-index: 3; display: flex; flex-direction: column; padding: 18px 18px 18px 0; background: linear-gradient(90deg, transparent, rgba(5,10,46,.55)); }
.gx-rarity-list { display: grid; gap: 10px; }
.gx-rarity { height: 88px; padding: 14px 24px; display: flex; flex-direction: column; justify-content: flex-end; clip-path: polygon(9% 0, 100% 0, 91% 100%, 0 100%); border: 1px solid transparent; transition: .25s; }
.gx-rarity span { font: 700 14px 'Cinzel', serif; letter-spacing: 2px; }
.gx-rarity i { font-style: normal; font-size: 13px; letter-spacing: 2px; }
.gx-rarity:hover { transform: translateX(-5px) scale(1.02); filter: brightness(1.2); }
.r-legendary { background: linear-gradient(135deg, rgba(255,215,106,.55), rgba(70,71,174,.7)); color: #FFE9A8; box-shadow: inset 0 0 0 2px #FFD76A; }
.r-epic { background: linear-gradient(135deg, rgba(138,140,255,.55), rgba(20,20,90,.8)); color: #C9CBFF; box-shadow: inset 0 0 0 2px #8A8CFF; }
.r-rare { background: linear-gradient(135deg, rgba(67,130,223,.55), rgba(10,20,80,.8)); color: #A8CBFF; box-shadow: inset 0 0 0 2px #4382DF; }
.r-common { background: linear-gradient(135deg, rgba(135,147,183,.4), rgba(10,20,70,.8)); color: #B9C2DA; box-shadow: inset 0 0 0 2px #8793B7; }

.gx-info { margin-top: auto; padding-top: 14px; }
.gx-info-row { display: flex; align-items: center; gap: 12px; padding: 12px 8px; border-top: 1px solid var(--gx-line); font-size: 15px; }
.gx-info-row svg { width: 24px; height: 24px; flex: 0 0 24px; fill: none; color: #BFD4FF; }
.gx-info-row b { margin-left: auto; font-size: 15px; }
.gx-info-note { font-size: 14px; line-height: 1.4; color: #DCE8FF; }

/* ACTIONS */
.gx-actions { 
    display: flex; 
    justify-content: space-between; 
    align-items: flex-end; /* Menjaga tombol sejajar di bawah */
    gap: 20px; 
    margin-top: 18px; 
    padding-bottom: 28px; /* Ruang untuk teks peringatan absolute */
    flex-wrap: wrap; 
}
.gx-actions-left, .gx-actions-right { 
    display: flex; 
    gap: 16px; 
    align-items: stretch; /* Memaksa kedua pull-wrap sama tinggi */
}
.gx-pill { display: inline-flex; align-items: center; gap: 12px; min-width: 160px; padding: 14px 26px; border-radius: 999px; border: 1px solid var(--gx-line); background: rgba(9,14,57,.85); color: #fff; font: 600 18px 'Segoe UI', sans-serif; text-decoration: none; cursor: pointer; transition: .2s; }
.gx-pill svg { width: 26px; height: 26px; fill: none; }
.gx-pill:hover { border-color: #9DBEFF; box-shadow: 0 0 20px rgba(108,139,255,.4); }

.gx-pull-wrap { 
    position: relative; 
    text-align: center; 
    min-width: 210px;
    display: flex;
    flex-direction: column;
    justify-content: flex-end; /* Tombol selalu nempel ke bawah */
}
.gx-cost { 
    display: inline-flex; 
    align-items: center; 
    gap: 8px; 
    margin: 0 auto -6px; 
    padding: 5px 30px; 
    position: relative; 
    z-index: 1; 
    font-weight: 700; 
    background: rgba(9,14,57,.95); 
    border: 1px solid var(--gx-line); 
    clip-path: polygon(14px 0, calc(100% - 14px) 0, 100% 100%, 0 100%); 
    align-self: center;
}
.gx-cost svg { width: 20px; height: 20px; color: #8FB8FF; }
.gx-pull { display: block; width: 100%; min-height: 62px; border: none; cursor: pointer; font: 700 25px 'Segoe UI', sans-serif; color: #0B1450; background: linear-gradient(180deg, #CFE0FF, #7FA5FF); clip-path: polygon(8% 0, 92% 0, 100% 50%, 92% 100%, 8% 100%, 0 50%); box-shadow: inset 0 0 0 3px rgba(255,255,255,.55); transition: filter .2s, transform .2s; }
.gx-pull-10 { background: linear-gradient(180deg, #D4D9FF, #A58BFF); }
.gx-pull:hover:not(:disabled) { filter: brightness(1.12); transform: translateY(-2px); }
.gx-pull:active:not(:disabled) { transform: translateY(1px); }
.gx-pull:disabled { opacity: .45; cursor: not-allowed; }
.gx-sale { position: absolute; right: -14px; top: -4px; z-index: 4; padding: 4px 14px; font-weight: 800; font-size: 14px; color: #1A1200; background: linear-gradient(100deg, #E7B02A, #FFE08A); transform: rotate(-8deg); clip-path: polygon(8px 0, 100% 0, calc(100% - 8px) 100%, 0 100%); }
.gx-lack { 
    position: absolute;
    bottom: -24px; /* Menggantung di bawah tombol */
    left: 0;
    right: 0;
    margin: 0;
    font-size: 12px; 
    color: #9BAFE7;
    text-align: center;
}

/* HINT BARU DI SINI */
.gx-first-hint {
    display: inline-flex; align-items: center; gap: 10px;
    margin-top: 16px; padding: 10px 18px;
    border: 1px dashed rgba(67, 130, 223, .5);
    border-radius: 999px;
    background: rgba(9, 14, 57, .55);
    color: #CFE0FF; font-size: 13px; font-weight: 600;
    cursor: pointer; transition: .25s ease;
}
.gx-first-hint:hover {
    color: #fff; border-color: #8FB8FF;
    background: rgba(45, 107, 255, .18);
    transform: translateX(2px);
}
.gx-first-hint-dot {
    width: 8px; height: 8px; border-radius: 50%;
    background: #4382DF; box-shadow: 0 0 10px #4382DF;
    animation: pulseDot 1.6s ease-in-out infinite;
}

@keyframes pulseDot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: .5; transform: scale(.75); }
}

/* KOLEKSI */
.gx-collection { padding: 24px; border: 1px solid var(--gx-line); border-radius: 6px; background: linear-gradient(145deg, rgba(17,46,129,.3), rgba(5,10,46,.9)); }
.gx-col-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
.gx-col-head h2 { font: 700 26px 'Cinzel', serif; }
.gx-col-head p { margin-top: 4px; color: #8793B7; font-size: 14px; }
.gx-col-head strong { font: 800 30px 'Cinzel', serif; color: #8FB8FF; }
.gx-bar { height: 8px; margin-bottom: 22px; border-radius: 99px; background: #111A45; overflow: hidden; }
.gx-bar div { height: 100%; background: linear-gradient(90deg, #4382DF, #8A8CFF); box-shadow: 0 0 14px #4382DF; transition: width .8s; }

/* GRID ITEM KOLEKSI */
.item-grid { display: grid; grid-template-columns: repeat(6, minmax(0, 1fr)); gap: 13px; }

.item-cell {
    --c: 10px;
    --edge: rgba(67, 130, 223, .5);
    min-width: 0;
    position: relative;
    padding: 4px;
    border: 1px solid rgba(67, 130, 223, .16);
    clip-path: polygon(0 0, 100% 0, 100% calc(100% - var(--c)), calc(100% - var(--c)) 100%, 0 100%);
    background:
        linear-gradient(to top left, transparent 50%, var(--edge) 50%, var(--edge) calc(50% + 1.5px), transparent calc(50% + 1.5px)) right bottom / var(--c) var(--c) no-repeat,
        linear-gradient(160deg, rgba(17, 46, 129, .16), rgba(9, 14, 57, .4));
    transition: translate .3s cubic-bezier(.22, 1, .36, 1), box-shadow .3s;
    animation: cellIn .55s cubic-bezier(.22, 1, .36, 1) backwards;
    animation-delay: min(calc(var(--i, 0) * 35ms + .2s), 1.2s);
}

.item-cell:nth-child(even) {
    clip-path: polygon(0 0, calc(100% - var(--c)) 0, 100% var(--c), 100% 100%, 0 100%);
    background:
        linear-gradient(to bottom left, transparent 50%, var(--edge) 50%, var(--edge) calc(50% + 1.5px), transparent calc(50% + 1.5px)) right top / var(--c) var(--c) no-repeat,
        linear-gradient(160deg, rgba(17, 46, 129, .16), rgba(9, 14, 57, .4));
}

.item-cell:hover { --edge: var(--g-cyan); translate: 0 -3px; box-shadow: inset 0 0 26px rgba(67, 130, 223, .22); }
.item-cell > * { max-width: 100%; }

.empty-collection { grid-column: 1 / -1; padding: 35px 15px; text-align: center; color: var(--g-muted); font-size: 12px; }
.empty-symbol { display: flex; justify-content: center; align-items: center; width: 40px; height: 40px; margin: 0 auto 12px; border: 1px solid var(--g-border); color: var(--g-bright); font-size: 20px; animation: crystalFloat 3s ease-in-out infinite; }

/* =========================================================
   OVERLAYS
   ========================================================= */
.gacha-overlay { position: fixed; inset: 0; z-index: 1000; display: flex; align-items: center; justify-content: center; padding: 20px; overflow-y: auto; background: #050505; }

.confirm-modal {
    width: min(100%, 430px);
    position: relative;
    padding: 30px;
    border: 1px solid rgba(67, 130, 223, .65);
    background: radial-gradient(ellipse at 50% 0%, rgba(70, 71, 174, .26), transparent 50%), #090E39;
    box-shadow: 0 0 0 1px rgba(17, 46, 129, .3), 0 25px 90px rgba(0, 0, 0, .6), 0 0 45px rgba(17, 46, 129, .22);
}

.modal-top-line, .result-top-line {
    position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, var(--g-blue), var(--g-bright), var(--g-purple), var(--g-bright), var(--g-blue));
    background-size: 200% 100%;
    animation: lineFlow 4s linear infinite;
}

.modal-kicker { color: var(--g-bright); font-size: 12px; font-weight: 900; letter-spacing: 1.8px; }

.modal-close {
    position: absolute; top: 13px; right: 14px; width: 31px; height: 31px;
    display: flex; align-items: center; justify-content: center;
    border: 1px solid var(--g-border); background: rgba(17, 46, 129, .2);
    color: var(--g-muted); font-size: 22px; cursor: pointer;
    transition: color .2s, background .2s, transform .25s;
}
.modal-close:hover:not(:disabled) { color: var(--g-white); background: rgba(67, 130, 223, .2); transform: rotate(90deg); }
.modal-close:disabled { opacity: .4; cursor: not-allowed; }

.confirm-crystal {
    position: relative; display: flex; justify-content: center; align-items: center;
    width: 85px; height: 85px; margin: 25px auto 18px;
    border: 1px solid var(--g-border);
    background: radial-gradient(circle, rgba(67, 130, 223, .17), transparent 72%);
    animation: crystalFloat 3s ease-in-out infinite;
}
.confirm-crystal::before { content: ""; position: absolute; inset: -1px; border: 1px solid var(--g-bright); animation: ringPulse 2.6s ease-out infinite; }
.confirm-crystal svg { width: 67px; height: 67px; filter: drop-shadow(0 0 14px rgba(67, 130, 223, .5)); }
.confirm-title { text-align: center; font-size: 20px; font-weight: 900; letter-spacing: 1.4px; }
.confirm-description { margin: 10px auto 22px; max-width: 300px; color: var(--g-muted); text-align: center; font-size: 13px; line-height: 1.8; }

.transaction-box { padding: 15px; border: 1px solid var(--g-border); background: rgba(17, 46, 129, .12); box-shadow: inset 0 1px 0 rgba(255, 255, 255, .04); }
.transaction-row { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 10px 0; color: var(--g-muted); font-size: 13px; }
.transaction-row + .transaction-row { border-top: 1px solid rgba(67, 130, 223, .12); }
.transaction-row strong { color: var(--g-white); font-size: 12px; }
.transaction-cost { display: flex; align-items: center; gap: 6px; color: var(--g-bright) !important; font-size: 17px !important; }
.transaction-cost svg { fill: var(--g-bright); width: 17px; height: 17px; }
.transaction-cost small { color: var(--g-muted); font-size: 8px; letter-spacing: .8px; }
.transaction-balance strong { color: var(--g-bright); }

.confirm-warning { margin-top: 15px; padding-left: 10px; border-left: 2px solid var(--g-purple); color: var(--g-muted); font-size: 10px; line-height: 1.7; }
.confirm-actions { display: grid; grid-template-columns: 1fr 1.4fr; gap: 10px; margin-top: 24px; }

.cancel-button, .confirm-button {
    position: relative; overflow: hidden; min-height: 43px; padding: 11px;
    font-size: 10px; font-weight: 900; letter-spacing: 1px; cursor: pointer;
    transition: filter .2s, opacity .2s, transform .2s, box-shadow .2s, background .2s;
}
.cancel-button { border: 1px solid var(--g-border); background: transparent; color: var(--g-muted); }
.cancel-button:hover:not(:disabled) { background: rgba(67, 130, 223, .08); }
.confirm-button { border: 1px solid var(--g-bright); background: linear-gradient(100deg, var(--g-blue), var(--g-purple)); color: var(--g-white); }
.confirm-button::after { content: ""; position: absolute; top: 0; bottom: 0; left: -60%; width: 40%; pointer-events: none; background: linear-gradient(100deg, transparent, rgba(255, 255, 255, .28), transparent); transform: skewX(-20deg); }
.confirm-button:not(:disabled)::after { animation: btnShine 3.5s ease-in-out infinite; }
.cancel-button:hover:not(:disabled), .confirm-button:hover:not(:disabled) { filter: brightness(1.2); }
.confirm-button:hover:not(:disabled) { box-shadow: 0 6px 22px rgba(67, 130, 223, .3); }
.cancel-button:disabled, .confirm-button:disabled { opacity: .4; cursor: not-allowed; }

/* =========================================================
   TUTORIAL MODAL
   ========================================================= */
.tutorial-overlay { z-index: 1300; background: radial-gradient(ellipse at 50% 30%, rgba(17, 46, 129, .55) 0%, #050505 70%); }

.tutorial-modal {
    width: min(100%, 460px);
    position: relative;
    padding: 30px 28px 24px;
    border: 1px solid rgba(67, 130, 223, .7);
    background: radial-gradient(ellipse at 50% 0%, rgba(70, 71, 174, .3), transparent 55%), #090E39;
    box-shadow: 0 0 0 1px rgba(17, 46, 129, .35), 0 25px 90px rgba(0, 0, 0, .7), 0 0 60px rgba(67, 130, 223, .28);
}

.tutorial-progress { display: flex; justify-content: center; gap: 8px; margin-bottom: 18px; }
.tutorial-dot {
    width: 28px; height: 4px; border-radius: 99px;
    background: rgba(67, 130, 223, .2);
    transition: background .3s ease, width .3s ease;
}
.tutorial-dot.on { width: 42px; background: linear-gradient(90deg, #4382DF, #8A8CFF); box-shadow: 0 0 12px rgba(67, 130, 223, .7); }
.tutorial-dot.done { background: rgba(67, 130, 223, .65); }

.tutorial-content { text-align: center; }

.tutorial-visual {
    width: 140px; height: 140px; margin: 0 auto 16px;
    display: grid; place-items: center;
    border: 1px solid var(--g-border);
    background: radial-gradient(circle, rgba(67, 130, 223, .18), transparent 72%);
    position: relative;
    animation: crystalFloat 3.5s ease-in-out infinite;
}

/* Ikon per step */
.tv-orb svg { width: 82px; height: 82px; filter: drop-shadow(0 0 18px rgba(67, 130, 223, .7)); }

.tv-coins { position: relative; width: 90px; height: 90px; }
.tv-coin {
    position: absolute; display: grid; place-items: center;
    width: 46px; height: 46px; border-radius: 50%;
    color: #0B1450; font: 900 20px 'Orbitron', sans-serif;
    background: radial-gradient(circle at 30% 30%, #FFFFFF, #FFD76A 55%, #C8952A);
    box-shadow: 0 0 18px rgba(255, 215, 106, .6);
    animation: coinBounce 2.2s ease-in-out infinite;
}
.tv-coin.c1 { left: 0; top: 20px; animation-delay: 0s; }
.tv-coin.c2 { left: 22px; top: 0; animation-delay: .25s; transform: scale(1.1); }
.tv-coin.c3 { right: 0; top: 22px; animation-delay: .5s; }

.tv-pull { display: flex; flex-direction: column; align-items: center; gap: 6px; }
.tv-pull-btn {
    display: inline-block; padding: 8px 24px;
    color: #0B1450; font: 900 15px 'Orbitron', sans-serif; letter-spacing: 1.5px;
    background: linear-gradient(180deg, #CFE0FF, #7FA5FF);
    clip-path: polygon(8% 0, 92% 0, 100% 50%, 92% 100%, 8% 100%, 0 50%);
    box-shadow: 0 0 20px rgba(127, 165, 255, .6);
    animation: pullPulse 1.8s ease-in-out infinite;
}
.tv-pull-arrow { color: #8FB8FF; font-size: 20px; animation: arrowDown 1.4s ease-in-out infinite; }
.tv-pull-coin { color: #FFD76A; font: 800 13px 'Orbitron', sans-serif; letter-spacing: 1px; }

.tv-rarity { display: flex; gap: 6px; }
.tv-rarity .tr {
    display: grid; place-items: center;
    width: 28px; height: 40px;
    font: 900 14px 'Orbitron', sans-serif;
    border: 1px solid; border-radius: 4px;
    animation: rarityGlow 2.4s ease-in-out infinite;
}
.tr-c { color: #B9C2DA; border-color: #8793B7; background: rgba(135, 147, 183, .15); }
.tr-r { color: #A8CBFF; border-color: #4382DF; background: rgba(67, 130, 223, .15); animation-delay: .15s; }
.tr-e { color: #C9CBFF; border-color: #8A8CFF; background: rgba(138, 140, 255, .15); animation-delay: .3s; }
.tr-l { color: #FFD76A; border-color: #FFD76A; background: rgba(255, 215, 106, .15); animation-delay: .45s; }

.tutorial-kicker {
    color: var(--g-bright); font-size: 10px; font-weight: 900; letter-spacing: 2px;
    margin-bottom: 6px;
}
.tutorial-title {
    font-size: 18px; font-weight: 900; letter-spacing: 1px;
    color: var(--g-white); margin-bottom: 10px; line-height: 1.3;
}
.tutorial-desc {
    color: #B9C6EE; font-size: 13.5px; line-height: 1.7;
    max-width: 340px; margin: 0 auto;
}

.tutorial-tip {
    display: inline-flex; align-items: flex-start; gap: 8px;
    margin: 14px auto 0; padding: 10px 14px;
    text-align: left;
    border: 1px solid rgba(67, 130, 223, .3);
    background: rgba(17, 46, 129, .25);
    color: #DCE8FF; font-size: 12px; line-height: 1.6;
}
.tutorial-tip-badge {
    flex-shrink: 0;
    padding: 2px 7px;
    background: var(--g-bright); color: #050A2A;
    font-size: 9px; font-weight: 900; letter-spacing: 1px;
    border-radius: 3px;
}

.tutorial-actions {
    display: flex; justify-content: flex-end; gap: 10px;
    margin-top: 22px; padding-top: 16px;
    border-top: 1px solid var(--g-border);
}

.tutorial-btn {
    min-height: 42px; padding: 10px 20px;
    font-size: 11px; font-weight: 900; letter-spacing: 1.2px;
    cursor: pointer; transition: .22s ease;
}
.tutorial-btn-ghost {
    border: 1px solid var(--g-border); background: transparent; color: var(--g-muted);
}
.tutorial-btn-ghost:hover:not(:disabled) { color: #fff; background: rgba(67, 130, 223, .08); }
.tutorial-btn-ghost:disabled { opacity: .35; cursor: not-allowed; }
.tutorial-btn-primary {
    border: 1px solid var(--g-bright); color: #fff;
    background: linear-gradient(100deg, var(--g-blue), var(--g-purple));
    position: relative; overflow: hidden;
}
.tutorial-btn-primary::after {
    content: ""; position: absolute; top: 0; bottom: 0; left: -60%;
    width: 40%; background: linear-gradient(100deg, transparent, rgba(255,255,255,.32), transparent);
    transform: skewX(-20deg); pointer-events: none;
    animation: btnShine 3s ease-in-out infinite;
}
.tutorial-btn-primary:hover { filter: brightness(1.15); box-shadow: 0 6px 22px rgba(67, 130, 223, .35); }

/* =========================================================
   OPENING ANIMATION
   ========================================================= */
.gacha-page.is-busy .gx-crystal, .gacha-page.is-busy .gx-moon, .gacha-page.is-busy .gx-spark { animation-play-state: paused; }

.opening-overlay { z-index: 1100; background: radial-gradient(ellipse at 50% 40%, rgba(17, 46, 129, .4) 0%, #050505 62%); }

.opening-content { --burst: #4382DF; --charge: 0; width: min(100%, 400px); text-align: center; position: relative; }
.opening-content.burst-epic, .opening-flash.burst-epic { --burst: #8A8CFF; }
.opening-content.burst-legendary, .opening-flash.burst-legendary { --burst: #FFD76A; }

.opening-rings { width: 240px; height: 240px; position: relative; display: flex; align-items: center; justify-content: center; margin: 0 auto 28px; }

.opening-ring { position: absolute; border: 1px solid rgba(67, 130, 223, .5); border-radius: 50%; opacity: calc(.55 + var(--charge) * .45); transform: scale(calc(1 - var(--charge) * .07)); will-change: transform, opacity; transition: opacity .45s ease; }
.ring-a { inset: 5px; border-top-color: #FFFFFF; animation: orbitRotate 2.2s linear infinite; }
.ring-b { inset: 23px; border-color: rgba(70, 71, 174, .9); border-bottom-color: #FFFFFF; animation: orbitRotate 1.6s linear infinite reverse; }
.ring-c { inset: 42px; border-style: dashed; animation: orbitRotate 6s linear infinite; }
.phase-burst .opening-ring { opacity: 0; }

.core-glow { position: absolute; z-index: 2; width: 220px; height: 220px; border-radius: 50%; pointer-events: none; background: radial-gradient(circle, var(--burst) 0%, transparent 68%); opacity: calc(.14 + var(--charge) * .55); transform: scale(calc(.7 + var(--charge) * .5)); will-change: transform, opacity; }
.core-glow::after { content: ""; position: absolute; inset: 22%; border-radius: 50%; background: radial-gradient(circle, #FFFFFF 0%, transparent 70%); opacity: 0; }
.phase-crack .core-glow { animation: glowCrack .75s cubic-bezier(.5, 0, .9, .6) forwards; }
.phase-crack .core-glow::after { animation: glowWhite .75s ease-in forwards; }
.phase-burst .core-glow { animation: glowBurst .7s cubic-bezier(.2, .8, .2, 1) forwards; }
.phase-burst .core-glow::after { opacity: 1; }

.opening-core { position: relative; z-index: 3; width: 150px; height: 150px; display: flex; align-items: center; justify-content: center; will-change: transform, opacity; animation: coreCharge .17s ease-in-out infinite alternate; }
.opening-core svg { width: 145px; height: 145px; overflow: visible; filter: drop-shadow(0 0 calc(10px + var(--charge) * 22px) var(--burst)); animation: coreWobble 2.6s ease-in-out infinite; will-change: transform; }
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

.opening-flash { --burst: #4382DF; position: fixed; inset: 0; z-index: 5; pointer-events: none; background: radial-gradient(circle at center, #FFFFFF 0%, var(--burst) 35%, transparent 75%); will-change: opacity; animation: screenFlash .9s cubic-bezier(.2, .7, .3, 1) forwards; }
.phase-burst { animation: screenShake .55s cubic-bezier(.36, .07, .19, .97); }

.opening-status { color: var(--g-white); font-size: 14px; font-weight: 900; letter-spacing: 2px; min-height: 20px; transition: color .4s ease, text-shadow .4s ease; }
.phase-crack .opening-status, .phase-burst .opening-status { color: var(--burst); text-shadow: 0 0 14px var(--burst); }

.opening-progress { width: min(100%, 260px); height: 4px; margin: 18px auto 12px; background: rgba(67, 130, 223, .15); overflow: hidden; }
.opening-progress-fill { width: 100%; height: 100%; transform-origin: left center; transform: scaleX(0); background: linear-gradient(90deg, var(--g-blue), var(--g-bright), #FFFFFF); box-shadow: 0 0 14px rgba(67, 130, 223, .7); will-change: transform; }
.opening-subtitle { color: var(--g-muted); font-size: 8px; letter-spacing: 1.5px; }

/* =========================================================
   RESULT MODAL
   ========================================================= */
.result-overlay { z-index: 1200; --rc: #4382DF; }
.result-overlay.tone-epic { --rc: #8A8CFF; }
.result-overlay.tone-legendary { --rc: #FFD76A; }

.result-rays {
    position: fixed; left: 50%; top: 50%; width: 1100px; height: 1100px; margin: -550px 0 0 -550px;
    pointer-events: none; border-radius: 50%;
    background: repeating-conic-gradient(color-mix(in srgb, var(--rc) 26%, transparent) 0deg 6deg, transparent 6deg 24deg);
    -webkit-mask-image: radial-gradient(circle, #000 0%, transparent 68%);
            mask-image: radial-gradient(circle, #000 0%, transparent 68%);
    opacity: 0;
    animation: raysIn 1s cubic-bezier(.2, .8, .2, 1) .05s forwards, orbitRotate 28s linear infinite;
}

.result-sparks { position: fixed; left: 50%; top: 50%; width: 0; height: 0; pointer-events: none; z-index: 0; }
.result-spark { position: absolute; left: -3px; top: -3px; width: 6px; height: 6px; background: var(--rc); box-shadow: 0 0 12px var(--rc); opacity: 0; transform: rotate(45deg); will-change: transform, opacity; animation: sparkFly 1.1s cubic-bezier(.1, .75, .25, 1) var(--d) forwards; }

.result-modal {
    width: min(100%, 920px); max-height: min(90vh, 900px); overflow-y: auto; position: relative; z-index: 1;
    border: 1px solid rgba(67, 130, 223, .5);
    background: radial-gradient(ellipse at 50% 0%, rgba(70, 71, 174, .24), transparent 50%), #090E39;
    box-shadow: 0 25px 90px rgba(0, 0, 0, .7), 0 0 60px rgba(17, 46, 129, .25);
    scrollbar-width: thin; scrollbar-color: var(--g-blue) transparent;
    animation: modalPop .5s cubic-bezier(.22, 1, .36, 1) backwards, modalGlow 1.2s ease-out .1s backwards;
}
.result-modal::-webkit-scrollbar { width: 6px; }
.result-modal::-webkit-scrollbar-thumb { background: var(--g-blue); }

.result-heading { padding: 28px 28px 17px; text-align: center; }
.result-heading .modal-kicker { animation: fadeDown .6s ease-out .1s backwards; }
.result-title { margin-top: 12px; font-size: clamp(23px, 4vw, 32px); font-weight: 900; letter-spacing: 1.5px; animation: titleIn .9s cubic-bezier(.2, .8, .2, 1) .15s backwards; }
.result-title span {
    color: var(--g-bright);
    background: linear-gradient(100deg, var(--g-bright) 30%, #FFFFFF 50%, var(--g-bright) 70%);
    background-size: 220% 100%;
    -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;
    animation: titleShimmer 4s ease-in-out infinite;
}
.result-description { margin-top: 8px; color: var(--g-muted); font-size: 13px; line-height: 1.7; animation: fadeDown .6s ease-out .35s backwards; }

.duplicate-refund { display: inline-flex; align-items: center; gap: 12px; margin-top: 13px; padding: 8px 12px; border: 1px solid rgba(67, 130, 223, .3); background: rgba(17, 46, 129, .2); font-size: 9px; color: var(--g-muted); letter-spacing: .7px; }
.duplicate-refund strong { color: var(--g-bright); }

.result-grid { display: grid; gap: 13px; padding: 15px 25px 25px; justify-content: center; }
.multi-result { grid-template-columns: repeat(5, minmax(0, 1fr)); }
.single-result { grid-template-columns: minmax(120px, 180px); width: 100%; }

.result-item {
    min-width: 0; position: relative; text-align: center; padding: 11px 7px;
    border: 1px solid rgba(67, 130, 223, .14); background: rgba(17, 46, 129, .1);
    transform-style: preserve-3d; will-change: transform, opacity;
    animation: itemFlip .75s cubic-bezier(.22, 1, .36, 1) both; animation-delay: var(--reveal-delay, 0ms);
    transition: translate .3s cubic-bezier(.22, 1, .36, 1), border-color .25s, box-shadow .3s;
}
.result-item::after { content: ""; position: absolute; inset: 0; z-index: 4; pointer-events: none; background: #FFFFFF; opacity: 0; animation: itemFlash .6s ease-out both; animation-delay: var(--reveal-delay, 0ms); }
.result-item:hover { translate: 0 -4px; border-color: rgba(67, 130, 223, .5); box-shadow: 0 10px 24px rgba(0, 0, 0, .35); }

.reveal-ring { position: absolute; left: 50%; top: 40%; width: 70px; height: 70px; margin: -35px; border: 2px solid #4382DF; border-radius: 50%; opacity: 0; pointer-events: none; z-index: 5; animation: itemRing .9s cubic-bezier(.1, .7, .3, 1) both; animation-delay: calc(var(--reveal-delay, 0ms) + 120ms); }
.reveal-ring.ring-common { border-color: #8793B7; animation-name: none; }
.reveal-ring.ring-rare { border-color: #4382DF; }
.reveal-ring.ring-epic { border-color: #8A8CFF; border-width: 3px; }
.reveal-ring.ring-legendary { border-color: #FFD76A; border-width: 3px; animation-iteration-count: 2; }

.result-item-glow { position: absolute; inset: 0; opacity: .12; pointer-events: none; }
.glow-common { background: #112E81; }
.glow-rare { background: #4382DF; }
.glow-epic { background: #4647AE; }
.glow-legendary { background: linear-gradient(135deg, #FFD76A, #FFFFFF, #4647AE); opacity: .2; animation: glowShift 2.6s ease-in-out infinite alternate; }

.result-item-art { width: 100%; aspect-ratio: 1; max-width: 112px; margin: 0 auto 10px; position: relative; display: flex; align-items: center; justify-content: center; border: 1px solid; background: #090E39; overflow: hidden; }
.result-item-art::before { content: ""; position: absolute; inset: 0; background: linear-gradient(135deg, rgba(255, 255, 255, .09), transparent 60%); }

.rarity-epic::after, .rarity-legendary::after { content: ""; position: absolute; top: 0; bottom: 0; left: -60%; width: 40%; z-index: 3; pointer-events: none; background: linear-gradient(100deg, transparent, rgba(255, 255, 255, .28), transparent); transform: skewX(-20deg); animation: btnShine 2.8s ease-in-out infinite; }

.rarity-common { border-color: rgba(135, 147, 183, .6); box-shadow: inset 0 0 18px rgba(17, 46, 129, .2); }
.rarity-rare { border-color: rgba(67, 130, 223, .85); box-shadow: inset 0 0 25px rgba(67, 130, 223, .18); }
.rarity-epic { border-color: rgba(70, 71, 174, .95); box-shadow: inset 0 0 25px rgba(70, 71, 174, .3); }
.rarity-legendary { border-color: #FFD76A; box-shadow: inset 0 0 25px rgba(255, 215, 106, .3), 0 0 14px rgba(255, 215, 106, .25); animation: legendaryPulse 1.8s ease-in-out infinite alternate; }

.result-rarity-mark { position: absolute; top: 5px; left: 6px; z-index: 2; color: var(--g-bright); font-size: 9px; font-weight: 900; }
.rarity-legendary .result-rarity-mark { color: #FFD76A; }

.new-tag { position: absolute; top: 5px; right: 5px; z-index: 2; padding: 2px 5px; color: #FFFFFF; background: linear-gradient(100deg, var(--g-blue), var(--g-purple)); border: 1px solid rgba(67, 130, 223, .5); font-size: 7px; font-weight: 900; letter-spacing: 1px; animation: tagPop .6s cubic-bezier(.3, 1.6, .5, 1) .5s backwards; }

.result-item-img { position: relative; z-index: 1; width: 100%; height: 100%; padding: 10px; object-fit: contain; filter: drop-shadow(0 4px 10px rgba(0, 0, 0, .45)); }
.result-type-icon { position: relative; z-index: 1; color: var(--g-white); font-size: 27px; font-weight: 900; letter-spacing: -1px; }
.rarity-rare .result-type-icon { color: var(--g-bright); }
.rarity-epic .result-type-icon { color: #A6A8FF; }
.rarity-legendary .result-type-icon { color: #FFFFFF; text-shadow: 0 0 18px #FFD76A; }

.duplicate-tag { position: absolute; bottom: 0; left: 0; right: 0; z-index: 2; padding: 4px 2px; background: rgba(17, 46, 129, .95); color: #FFFFFF; font-size: 7px; font-weight: 900; letter-spacing: 1px; }

.result-item-name { position: relative; font-size: 12px; line-height: 1.5; font-weight: 800; overflow-wrap: anywhere; }
.result-rarity-label { position: relative; margin-top: 5px; font-size: 10px; font-weight: 900; letter-spacing: .8px; }
.label-common { color: var(--g-muted); }
.label-rare { color: var(--g-bright); }
.label-epic { color: #A6A8FF; }
.label-legendary { color: #FFD76A; }

.result-refund { position: relative; margin-top: 6px; font-size: 8px; font-weight: 800; color: var(--g-bright); animation: refundPulse 1.4s ease-in-out .6s 2; }

.result-footer { padding: 18px 25px 24px; border-top: 1px solid var(--g-border); background: rgba(0, 0, 0, .12); }
.result-summary { display: flex; align-items: center; justify-content: space-between; gap: 12px; color: var(--g-muted); font-size: 9px; letter-spacing: 1px; margin-bottom: 16px; }
.result-summary strong { color: var(--g-bright); font-size: 13px; font-variant-numeric: tabular-nums; }
.result-actions { display: flex; justify-content: center; gap: 10px; }

.result-button { position: relative; overflow: hidden; min-height: 42px; display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 12px 18px; font-size: 9px; font-weight: 900; letter-spacing: 1px; text-decoration: none; cursor: pointer; transition: filter .2s, background .2s, box-shadow .2s, transform .2s; }
.result-button:hover { filter: brightness(1.2); }
.secondary-result-button { border: 1px solid var(--g-border); background: transparent; color: var(--g-muted); }
.primary-result-button { border: 1px solid var(--g-bright); background: linear-gradient(100deg, var(--g-blue), var(--g-purple)); color: var(--g-white); }
.primary-result-button::after { content: ""; position: absolute; top: 0; bottom: 0; left: -60%; width: 40%; pointer-events: none; background: linear-gradient(100deg, transparent, rgba(255, 255, 255, .28), transparent); transform: skewX(-20deg); animation: btnShine 3.5s ease-in-out infinite; }
.primary-result-button:hover { box-shadow: 0 6px 22px rgba(67, 130, 223, .3); }
.primary-result-button svg { width: 15px; height: 15px; transition: transform .25s; }
.primary-result-button:hover svg { transform: translateX(4px); }

/* =========================================================
   KEYFRAMES
   ========================================================= */
@keyframes crystalFloat { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-9px); } }
@keyframes orbitRotate { from { rotate: 0deg; } to { rotate: 360deg; } }
@keyframes particleFloat { 0%, 100% { opacity: .3; translate: 0 0; } 50% { opacity: 1; translate: 0 -12px; } }
@keyframes riseIn { from { opacity: 0; transform: translateY(22px); } to { opacity: 1; transform: translateY(0); } }
@keyframes cellIn { from { opacity: 0; transform: translateY(14px) scale(.94); } to { opacity: 1; transform: translateY(0) scale(1); } }
@keyframes modalPop { from { opacity: 0; transform: translateY(18px) scale(.97); } to { opacity: 1; transform: translateY(0) scale(1); } }
@keyframes ringPulse { 0% { transform: scale(1); opacity: .7; } 100% { transform: scale(1.5); opacity: 0; } }
@keyframes titleShimmer { 0%, 20% { background-position: 120% 0; } 80%, 100% { background-position: -20% 0; } }
@keyframes btnShine { 0%, 55% { translate: 0 0; } 100% { translate: 520% 0; } }
@keyframes lineFlow { from { background-position: 0 0; } to { background-position: 200% 0; } }
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
@keyframes modalGlow { 0% { box-shadow: 0 0 0 0 transparent, 0 25px 90px rgba(0, 0, 0, .7); } 30% { box-shadow: 0 0 90px 10px var(--rc), 0 25px 90px rgba(0, 0, 0, .7); } 100% { box-shadow: 0 25px 90px rgba(0, 0, 0, .7), 0 0 60px rgba(17, 46, 129, .25); } }
@keyframes titleIn { from { opacity: 0; letter-spacing: 14px; filter: blur(6px); } to { opacity: 1; letter-spacing: 1.5px; filter: blur(0); } }
@keyframes fadeDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
@keyframes itemFlip { 0% { opacity: 0; transform: perspective(700px) rotateY(100deg) translateY(20px) scale(.7); } 55% { opacity: 1; transform: perspective(700px) rotateY(-14deg) translateY(-4px) scale(1.06); } 100% { opacity: 1; transform: perspective(700px) rotateY(0) translateY(0) scale(1); } }
@keyframes itemFlash { 0% { opacity: 0; } 25% { opacity: .75; } 100% { opacity: 0; } }
@keyframes itemRing { 0% { opacity: .9; transform: scale(.4); } 100% { opacity: 0; transform: scale(3.4); } }
@keyframes tagPop { 0% { opacity: 0; transform: scale(0) rotate(-20deg); } 100% { opacity: 1; transform: scale(1) rotate(0); } }
@keyframes refundPulse { 0%, 100% { text-shadow: none; } 50% { text-shadow: 0 0 12px #4382DF; } }
@keyframes gridDrift { from { transform: translateY(0); } to { transform: translateY(56px); } }
@keyframes twinkle { from { opacity: .25; } to { opacity: .7; } }
@keyframes orbDrift { from { translate: 0 0; } to { translate: 70px 50px; } }
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

/* Tutorial-specific */
@keyframes coinBounce {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-10px) rotate(15deg); }
}
@keyframes pullPulse {
    0%, 100% { box-shadow: 0 0 20px rgba(127, 165, 255, .5); transform: scale(1); }
    50% { box-shadow: 0 0 30px rgba(127, 165, 255, .9); transform: scale(1.05); }
}
@keyframes arrowDown {
    0%, 100% { transform: translateY(0); opacity: .6; }
    50% { transform: translateY(4px); opacity: 1; }
}
@keyframes rarityGlow {
    0%, 100% { transform: translateY(0) scale(1); }
    50% { transform: translateY(-3px) scale(1.06); }
}

/* =========================================================
   RESPONSIVE
   ========================================================= */
@media (max-width: 1100px) {
    .item-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
}

@media (max-width: 1000px) {
    .gx-body { grid-template-columns: 1fr; }
    .gx-side { grid-template-columns: repeat(3, 1fr); }
    .gx-tab.on { clip-path: none; }
    .gx-stage { grid-template-columns: 1fr; }
    .gx-right { padding: 0 18px 18px; }
    .gx-rarity-list { grid-template-columns: repeat(2, 1fr); }
    .gx-info { margin-top: 14px; }
}

@media (max-width: 640px) {
    .orb-1, .orb-2, .orb-3 { filter: blur(60px); opacity: .7; }
    .gx-top { flex-wrap: wrap; }
    .gx-currency { margin-left: 0; width: 100%; }
    .gx-side { grid-template-columns: 1fr; }
    .gx-hero { min-height: 440px; padding: 20px; }
    .gx-hero-art { right: -10%; width: 80%; top: 22%; opacity: .75; }
    .gx-featured { left: 12px; right: 12px; }
    .gx-actions, .gx-actions-left, .gx-actions-right { flex-direction: column; align-items: stretch; width: 100%; }
    .gx-pull-wrap { min-width: 0; }
    .gx-collection { padding: 17px; }
    .item-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 8px; }
    .confirm-modal, .tutorial-modal { padding: 24px 18px; }
    .result-modal { max-height: 92vh; }
    .result-heading { padding: 25px 15px 12px; }
    .result-grid { padding: 12px; gap: 8px; }
    .multi-result { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .multi-result .result-item-art { max-width: 100px; }
    .single-result { grid-template-columns: minmax(120px, 180px); }
    .result-footer { padding: 15px; }
    .result-actions { flex-direction: column; }
    .result-button { width: 100%; }
    .result-rays { width: 700px; height: 700px; margin: -350px 0 0 -350px; }
    .opening-rings { width: 210px; height: 210px; }
    .opening-core { width: 130px; height: 130px; }
    .opening-core svg { width: 125px; height: 125px; }

    .tutorial-visual { width: 120px; height: 120px; }
    .tutorial-title { font-size: 16px; }
    .tutorial-desc { font-size: 12.5px; }
    .tutorial-actions { flex-direction: column-reverse; }
    .tutorial-btn { width: 100%; }
    .gx-first-hint { width: 100%; justify-content: center; }
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
        tutorialSeen: false, // diisi dari localStorage saat init

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

            // Tampilkan tutorial otomatis untuk pengguna baru
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

                // Jeda singkat agar sinar + percikan tampil dulu sebelum item muncul
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