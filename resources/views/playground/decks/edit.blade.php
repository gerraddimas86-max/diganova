
@extends('playground.layouts.app')

@section('title', 'Edit Deck — DIGANOVA Playground')

@section('content')

<style>
    .deck-page {
        color: #eaf0ff;
    }

    .deck-page .deck-panel {
        background: rgba(17, 46, 129, 0.22);
        border: 1px solid rgba(67, 130, 223, 0.24);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 20px;
    }

    .deck-page .deck-muted {
        color: #aab9e5;
    }

    .deck-page .deck-heading {
        color: #ffffff;
        letter-spacing: -0.025em;
    }

    .deck-page .deck-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #b9d6ff;
        transition: color 0.2s ease;
    }

    .deck-page .deck-back:hover {
        color: #ffffff;
    }

    .deck-page .deck-guide-number {
        display: flex;
        width: 36px;
        height: 36px;
        flex-shrink: 0;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: rgba(70, 71, 174, 0.35);
        border: 1px solid rgba(119, 143, 255, 0.3);
        color: #c8d9ff;
        font-weight: 700;
    }

    .deck-page .deck-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        border: 1px solid rgba(74, 222, 128, 0.25);
        background: rgba(22, 163, 74, 0.12);
        color: #86efac;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
    }

    .deck-page .deck-status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #4ade80;
    }

    .deck-page .deck-divider {
        height: 1px;
        background: linear-gradient(
            90deg,
            transparent,
            rgba(67, 130, 223, 0.4),
            transparent
        );
    }

    .deck-page .deck-guide-card {
        height: 100%;
        border: 1px solid rgba(67, 130, 223, 0.18);
        border-radius: 16px;
        background: rgba(9, 14, 57, 0.38);
        padding: 18px;
        transition:
            border-color 0.2s ease,
            background 0.2s ease,
            transform 0.2s ease;
    }

    .deck-page .deck-guide-card:hover {
        border-color: rgba(67, 130, 223, 0.42);
        background: rgba(17, 46, 129, 0.24);
        transform: translateY(-2px);
    }

    @media (max-width: 640px) {
        .deck-page .deck-panel {
            border-radius: 16px;
        }
    }
</style>

{{-- FULL SCREEN GRID BACKGROUND --}}
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

    {{-- Overlay --}}
    <div
        class="absolute inset-0 bg-gradient-to-b from-[#090E39]/35 via-[#090E39]/65 to-[#090E39]/90"
    ></div>

    {{-- Glow kiri --}}
    <div
        class="absolute -left-32 top-20 h-96 w-96 rounded-full bg-[#4647AE]/20 blur-[130px]"
    ></div>

    {{-- Glow kanan --}}
    <div
        class="absolute -right-24 top-1/3 h-96 w-96 rounded-full bg-[#4382DF]/15 blur-[140px]"
    ></div>

    {{-- Glow bawah --}}
    <div
        class="absolute bottom-0 left-1/3 h-72 w-72 rounded-full bg-indigo-500/[0.08] blur-[120px]"
    ></div>
</div>

<div class="deck-page relative isolate space-y-6">

    {{-- NAVIGATION --}}
    <div>
        <a
            href="{{ route('play.decks.index') }}"
            class="deck-back text-sm font-medium"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="18"
                height="18"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
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
    <section class="deck-panel relative overflow-hidden p-5 sm:p-7">

        {{-- Header decoration --}}
        <div
            class="pointer-events-none absolute -right-10 -top-16 h-48 w-48 rounded-full bg-[#4647AE]/20 blur-3xl"
            aria-hidden="true"
        ></div>

        <div class="relative flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

            <div class="min-w-0">
                <div class="mb-3 flex items-center gap-2">
                    <span
                        class="inline-flex items-center rounded-full border border-blue-400/20 bg-blue-400/10 px-3 py-1 text-xs font-semibold tracking-wide text-blue-200"
                    >
                        DIGANOVA PLAYGROUND
                    </span>
                </div>

                <h1 class="deck-heading break-words text-2xl font-bold sm:text-3xl">
                    Edit Deck
                </h1>

                <p class="deck-muted mt-2 max-w-2xl text-sm leading-6">
                    Perbarui informasi deck dan sesuaikan kartu yang ingin kamu gunakan.
                </p>

                @if(isset($deck) && $deck->name)
                    <p class="mt-3 break-words text-sm text-blue-200/90">
                        Deck:
                        <span class="font-semibold text-white">
                            {{ $deck->name }}
                        </span>
                    </p>
                @endif
            </div>

            @if(isset($deck) && isset($deck->is_active) && $deck->is_active)
                <div class="shrink-0">
                    <span class="deck-status">
                        <span class="deck-status-dot"></span>
                        Deck Aktif
                    </span>
                </div>
            @endif

        </div>
    </section>

    {{-- FORM SECTION --}}
    <section class="deck-panel overflow-hidden">

        <div class="p-5 sm:p-7">

            <div class="mb-6">
                <h2 class="text-lg font-bold text-white sm:text-xl">
                    Informasi Deck
                </h2>

                <p class="deck-muted mt-2 text-sm leading-6">
                    Ubah nama, deskripsi, atau pilihan kartu melalui formulir di bawah ini.
                </p>
            </div>

            <div class="deck-divider mb-6"></div>

            @include('playground.decks._form', [
                'action' => route('play.decks.update', $deck),
                'method' => 'PUT',
                'deck' => $deck,
                'selectedIds' => $selectedIds
            ])

        </div>
    </section>

    {{-- GUIDE SECTION --}}
    <section class="deck-panel p-5 sm:p-7">

        <div class="mb-6">
            <div class="mb-2 flex items-center gap-2">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="19"
                    height="19"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="#93C5FD"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    aria-hidden="true"
                >
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 16v-4"/>
                    <path d="M12 8h.01"/>
                </svg>

                <span class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-200">
                    Panduan
                </span>
            </div>

            <h2 class="text-lg font-bold text-white sm:text-xl">
                Tips Mengelola Deck
            </h2>

            <p class="deck-muted mt-2 text-sm leading-6">
                Beberapa hal yang perlu diperhatikan ketika mengedit deck.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

            {{-- GUIDE 1 --}}
            <div class="deck-guide-card">
                <div class="mb-4 flex items-center gap-3">
                    <div class="deck-guide-number">
                        01
                    </div>

                    <h3 class="text-sm font-semibold text-white">
                        Nama Deck
                    </h3>
                </div>

                <p class="deck-muted text-sm leading-6">
                    Gunakan nama yang jelas agar deck mudah dikenali ketika kamu memilihnya.
                </p>
            </div>

            {{-- GUIDE 2 --}}
            <div class="deck-guide-card">
                <div class="mb-4 flex items-center gap-3">
                    <div class="deck-guide-number">
                        02
                    </div>

                    <h3 class="text-sm font-semibold text-white">
                        Pilihan Kartu
                    </h3>
                </div>

                <p class="deck-muted text-sm leading-6">
                    Periksa kembali kartu yang dipilih sebelum menyimpan perubahan deck.
                </p>
            </div>

            {{-- GUIDE 3 --}}
            <div class="deck-guide-card">
                <div class="mb-4 flex items-center gap-3">
                    <div class="deck-guide-number">
                        03
                    </div>

                    <h3 class="text-sm font-semibold text-white">
                        Simpan Perubahan
                    </h3>
                </div>

                <p class="deck-muted text-sm leading-6">
                    Pastikan perubahan sudah sesuai sebelum mengirim formulir.
                </p>
            </div>

        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="pb-4 pt-1 text-center">
        <p class="text-xs leading-6 text-blue-200/50">
            DIGANOVA Playground
            <span class="mx-1">•</span>
            Manage your deck, build your strategy.
        </p>
    </footer>

</div>

@endsection
