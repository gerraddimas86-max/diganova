@extends('playground.layouts.app')

@section('title', 'Buat Deck — DIGANOVA Playground')

@section('content')

<style>
    /* =========================================================
       CREATE DECK — CYBER NEON THEME
       ========================================================= */
    .deck-page {
        color: var(--text);
        font-family: 'Anta', system-ui, sans-serif;
    }

    /* Panel Utama */
    .deck-page .deck-panel {
        position: relative;
        background: var(--panel);
        border: 1px solid var(--line-strong);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 8px;
        box-shadow: inset 0 0 0 1px var(--line), var(--shadow);
        overflow: hidden;
    }

    /* Aksen garis atas pada panel */
    .deck-page .deck-panel::before {
        content: "";
        position: absolute;
        top: 0;
        left: 2rem;
        right: 2rem;
        height: 2px;
        background: linear-gradient(90deg, transparent, var(--cyan), transparent);
        opacity: 0.8;
    }

    .deck-page .deck-muted {
        color: var(--muted);
    }

    .deck-page .deck-heading {
        color: var(--text);
        letter-spacing: -0.025em;
        font-family: 'Iceberg', serif;
        font-weight: 400;
        -webkit-text-stroke: .4px currentColor;
    }

    /* Tombol Kembali */
    .deck-page .deck-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--cyan);
        transition: color 0.2s ease, transform 0.2s ease;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-size: 0.7rem;
    }

    .deck-page .deck-back:hover {
        color: var(--text);
        transform: translateX(-4px);
    }

    /* Garis Pemisah */
    .deck-page .deck-divider {
        height: 1px;
        background: linear-gradient(
            90deg,
            transparent,
            var(--cyan),
            transparent
        );
        opacity: 0.3;
    }

    /* Kustomisasi area form (jika ada input/select di dalamnya) */
    .deck-page .deck-form-wrapper {
        padding: 1.5rem;
        background: var(--bg-soft);
        border: 1px dashed var(--line-strong);
        border-radius: 6px;
    }

    @media (max-width: 640px) {
        .deck-page .deck-panel::before {
            left: 1rem;
            right: 1rem;
        }
    }
</style>

<div class="deck-page relative isolate space-y-6">

    {{-- NAVIGATION --}}
    <div>
        <a
            href="{{ route('play.decks.index') }}"
            class="deck-back font-medium"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2.4"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
            >
                <path d="m15 18-6-6 6-6"/>
                <path d="M20 12H9"/>
            </svg>

            Kembali ke My Decks
        </a>
    </div>

    {{-- PAGE HEADER --}}
    <section class="deck-panel relative p-6 sm:p-8">

        {{-- Header decoration --}}
        <div
            class="pointer-events-none absolute -right-10 -top-16 h-48 w-48 rounded-full bg-[var(--glow)] blur-3xl"
            aria-hidden="true"
        ></div>

        <div class="relative flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

            <div class="min-w-0">
                <div class="mb-3 flex items-center gap-2">
                    <span
                        class="inline-flex items-center rounded-full border border-[var(--line-strong)] bg-[var(--bg-soft)] px-3 py-1 text-[9px] tracking-[0.2em] text-[var(--cyan)] uppercase"
                    >
                        DIGANOVA PLAYGROUND
                    </span>
                </div>

                <h1 class="deck-heading break-words text-3xl sm:text-4xl text-[var(--text)] flex items-center gap-3">
                    <svg class="w-8 h-8 text-[var(--cyan)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Buat Deck Baru
                </h1>

                <p class="deck-muted mt-3 max-w-2xl text-sm leading-relaxed">
                    Pilih 10 kartu untuk menyusun strategi dan mulai bertarung di arena.
                </p>
            </div>

        </div>
    </section>

    {{-- FORM SECTION --}}
    <section class="deck-panel">

        <div class="p-6 sm:p-8">

            <div class="mb-6">
                <h2 class="text-xl font-bold text-[var(--text)] font-iceberg tracking-wide">
                    Informasi Deck
                </h2>

                <p class="deck-muted mt-2 text-sm leading-relaxed">
                    Beri nama deck barumu dan pilih kombinasi kartu terbaik.
                </p>
            </div>

            <div class="deck-divider mb-6"></div>

            <div class="deck-form-wrapper">
                @include('playground.decks._form', [
                    'action' => route('play.decks.store'),
                    'method' => 'POST',
                    'deck' => null,
                    'selectedIds' => [],
                ])
            </div>

        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="pb-6 pt-2 text-center">
        <p class="text-[10px] tracking-widest uppercase text-[var(--faint)]">
            DIGANOVA Playground
            <span class="mx-2 text-[var(--cyan)]">•</span>
            Build your ultimate deck.
        </p>
    </footer>

</div>

@endsection