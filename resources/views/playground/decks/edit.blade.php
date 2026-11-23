@extends('playground.layouts.app')

@section('title', 'Edit Deck — DIGANOVA Playground')

@section('content')

<style>
    /* =========================================================
       EDIT DECK — CYBER NEON THEME
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

    /* Nomor Panduan */
    .deck-page .deck-guide-number {
        display: flex;
        width: 40px;
        height: 40px;
        flex-shrink: 0;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        background: var(--bg-soft);
        border: 1px solid var(--line-strong);
        color: var(--cyan);
        font-family: 'Iceberg', serif;
        font-size: 1.2rem;
        box-shadow: inset 0 0 0 1px var(--glow);
    }

    /* Status Badge */
    .deck-page .deck-status {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 1px solid var(--line-strong);
        background: var(--bg-soft);
        color: var(--cyan);
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 10px;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        box-shadow: 0 0 20px var(--glow);
        clip-path: polygon(10px 0, 100% 0, 100% calc(100% - 10px), calc(100% - 10px) 100%, 0 100%, 0 10px);
    }

    .deck-page .deck-status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--cyan);
        box-shadow: 0 0 10px var(--cyan);
        animation: pulse-dot 1.5s ease-in-out infinite;
    }

    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(0.8); }
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

    /* Kartu Panduan */
    .deck-page .deck-guide-card {
        height: 100%;
        border: 1px solid var(--line-strong);
        border-radius: 4px;
        background: var(--bg-soft);
        padding: 20px;
        transition:
            border-color 0.2s ease,
            background 0.2s ease,
            transform 0.2s ease,
            box-shadow 0.2s ease;
        clip-path: polygon(0 0, calc(100% - 14px) 0, 100% 14px, 100% 100%, 14px 100%, 0 calc(100% - 14px));
    }

    .deck-page .deck-guide-card:hover {
        border-color: var(--cyan);
        background: var(--panel);
        transform: translateY(-3px);
        box-shadow: 0 8px 25px -8px var(--glow);
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
        .deck-page .deck-guide-card {
            padding: 16px;
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

                <h1 class="deck-heading break-words text-3xl sm:text-4xl text-[var(--text)]">
                    Edit Deck
                </h1>

                <p class="deck-muted mt-3 max-w-2xl text-sm leading-relaxed">
                    Perbarui informasi deck dan sesuaikan kartu yang ingin kamu gunakan.
                </p>

                @if(isset($deck) && $deck->name)
                    <p class="mt-4 break-words text-sm text-[var(--muted)] flex items-center gap-2">
                        <svg class="h-4 w-4 text-[var(--cyan)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Deck: <span class="font-semibold text-[var(--text)] font-mono text-base">{{ $deck->name }}</span>
                    </p>
                @endif
            </div>

            @if(isset($deck) && isset($deck->is_active) && $deck->is_active)
                <div class="shrink-0 self-start sm:self-center">
                    <span class="deck-status">
                        <span class="deck-status-dot"></span>
                        Deck Aktif
                    </span>
                </div>
            @endif

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
                    Ubah nama, deskripsi, atau pilihan kartu melalui formulir di bawah ini.
                </p>
            </div>

            <div class="deck-divider mb-6"></div>

            <div class="deck-form-wrapper">
                @include('playground.decks._form', [
                    'action' => route('play.decks.update', $deck),
                    'method' => 'PUT',
                    'deck' => $deck,
                    'selectedIds' => $selectedIds
                ])
            </div>

        </div>
    </section>

    {{-- GUIDE SECTION --}}
    <section class="deck-panel p-6 sm:p-8">

        <div class="mb-8">
            <div class="mb-2 flex items-center gap-2">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="18"
                    height="18"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="var(--cyan)"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    aria-hidden="true"
                >
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 16v-4"/>
                    <path d="M12 8h.01"/>
                </svg>

                <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-[var(--cyan)]">
                    Panduan
                </span>
            </div>

            <h2 class="text-xl font-bold text-[var(--text)] font-iceberg tracking-wide">
                Tips Mengelola Deck
            </h2>

            <p class="deck-muted mt-2 text-sm leading-relaxed">
                Beberapa hal yang perlu diperhatikan ketika mengedit deck.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">

            {{-- GUIDE 1 --}}
            <div class="deck-guide-card">
                <div class="mb-4 flex items-center gap-3">
                    <div class="deck-guide-number">
                        01
                    </div>

                    <h3 class="text-sm font-semibold text-[var(--text)]">
                        Nama Deck
                    </h3>
                </div>

                <p class="deck-muted text-sm leading-relaxed">
                    Gunakan nama yang jelas agar deck mudah dikenali ketika kamu memilihnya di arena.
                </p>
            </div>

            {{-- GUIDE 2 --}}
            <div class="deck-guide-card">
                <div class="mb-4 flex items-center gap-3">
                    <div class="deck-guide-number">
                        02
                    </div>

                    <h3 class="text-sm font-semibold text-[var(--text)]">
                        Pilihan Kartu
                    </h3>
                </div>

                <p class="deck-muted text-sm leading-relaxed">
                    Periksa kembali komposisi kartu (Attack/Defense/Support) sebelum menyimpan perubahan.
                </p>
            </div>

            {{-- GUIDE 3 --}}
            <div class="deck-guide-card">
                <div class="mb-4 flex items-center gap-3">
                    <div class="deck-guide-number">
                        03
                    </div>

                    <h3 class="text-sm font-semibold text-[var(--text)]">
                        Simpan Perubahan
                    </h3>
                </div>

                <p class="deck-muted text-sm leading-relaxed">
                    Pastikan semua kartu sudah berjumlah 10 sebelum mengirim formulir untuk bertarung.
                </p>
            </div>

        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="pb-6 pt-2 text-center">
        <p class="text-[10px] tracking-widest uppercase text-[var(--faint)]">
            DIGANOVA Playground
            <span class="mx-2 text-[var(--cyan)]">•</span>
            Manage your deck, build your strategy.
        </p>
    </footer>

</div>

@endsection