@extends('layouts.public')

@section('title', 'Daftar ' . $event->title . ' — DIGANOVA')

@section('content')

@php
    /* ====== Icon library ====== */
    $icons = [
        'ticket'         => '<path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><path d="M13 5v2M13 17v2M13 11v2"/>',
        'user'           => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
        'mail'           => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/>',
        'at-sign'        => '<circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8"/>',
        'upload-cloud'   => '<path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"/><path d="M12 12v9"/><path d="m16 16-4-4-4 4"/>',
        'file-image'     => '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>',
        'info'           => '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>',
        'check-circle'   => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/>',
        'credit-card'    => '<rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/>',
        'banknote'       => '<rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/>',
        'wallet'         => '<path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/>',
        'alert'          => '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4M12 17h.01"/>',
        'arrow-left'     => '<path d="m12 19-7-7 7-7M5 12h14"/>',
        'chevron-right'  => '<path d="m9 18 6-6-6-6"/>',
        'calendar'       => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
        'percent'        => '<path d="M19 5 5 19"/><circle cx="6.5" cy="6.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/>',
        'sparkles'       => '<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/><path d="M20 3v4M22 5h-4M4 17v2M5 18H3"/>',
    ];

    $icon = fn (string $name, string $class = 'w-6 h-6') =>
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';
@endphp

<style>
    :where(#register-page) { --c-indigo: #4647AE; --c-blue: #4382DF; --c-deep: #112E81; --c-navy: #090E39; }
    .font-display { font-family: 'Bricolage Grotesque', system-ui, sans-serif; }

    .ex-w { --ec: #FFFFFF; } .ex-b { --ec: #4382DF; } .ex-i { --ec: #4647AE; } .ex-d { --ec: #112E81; } .ex-k { --ec: #000000; }
    .extrude {
        box-shadow:
            1px 1px 0 var(--ec), 2px 2px 0 var(--ec), 3px 3px 0 var(--ec), 4px 4px 0 var(--ec),
            5px 5px 0 var(--ec), 6px 6px 0 var(--ec), 7px 7px 0 var(--ec), 8px 8px 0 var(--ec),
            14px 20px 30px rgba(0,0,0,.5);
    }
    .extrude-sm {
        box-shadow:
            1px 1px 0 var(--ec), 2px 2px 0 var(--ec), 3px 3px 0 var(--ec), 4px 4px 0 var(--ec),
            8px 12px 18px rgba(0,0,0,.45);
    }

    .btn-3d {
        --ec: #4382DF;
        transform: translate(var(--tx, 0px), var(--ty, 0px));
        box-shadow: 6px 6px 0 var(--ec), 6px 14px 24px rgba(0,0,0,.45);
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .btn-3d:hover {
        transform: translate(calc(var(--tx, 0px) + 3px), calc(var(--ty, 0px) + 3px));
        box-shadow: 3px 3px 0 var(--ec), 3px 8px 14px rgba(0,0,0,.4);
    }
    .btn-3d:active {
        transform: translate(calc(var(--tx, 0px) + 6px), calc(var(--ty, 0px) + 6px));
        box-shadow: 0 0 0 var(--ec);
    }

    .home-grid {
        background-image:
            linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
        background-size: 48px 48px;
        mask-image: radial-gradient(ellipse at 50% 25%, #000 25%, transparent 75%);
        -webkit-mask-image: radial-gradient(ellipse at 50% 25%, #000 25%, transparent 75%);
    }

    .reveal {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity .7s ease, transform .7s cubic-bezier(.2,.8,.2,1);
    }
    .reveal.in { opacity: 1; transform: none; }

    /* Brutal input */
    .input-brutal {
        background: #fff;
        color: #090E39;
        border: 2px solid #fff;
        border-radius: .9rem;
        padding: .8rem 1rem;
        font-weight: 500;
        transition: box-shadow .2s;
        outline: none;
    }
    .input-brutal::placeholder { color: rgba(17,46,129,.5); }
    .input-brutal:focus { box-shadow: 4px 4px 0 #4382DF; }

    /* Ticket radio card */
    .ticket-option {
        display: block;
        background: #fff;
        color: #090E39;
        border: 2px solid #fff;
        border-radius: 1.1rem;
        padding: 1rem 1.1rem;
        cursor: pointer;
        transition: transform .2s, box-shadow .2s, border-color .2s;
        position: relative;
    }
    .ticket-option:hover {
        transform: translateY(-2px);
        box-shadow: 3px 3px 0 #4382DF;
    }
    .ticket-option.is-selected {
        background: #fff;
        border-color: #4382DF;
        box-shadow: 5px 5px 0 #4382DF, 8px 12px 18px rgba(0,0,0,.35);
        transform: translateY(-2px);
    }
    .ticket-option.is-selected::before {
        content: '';
        position: absolute;
        top: -6px; right: -6px;
        width: 26px; height: 26px;
        border-radius: 9999px;
        background: #4382DF;
        border: 3px solid #fff;
        box-shadow: 0 0 0 2px #090E39;
    }
    .ticket-option.is-selected::after {
        content: '';
        position: absolute;
        top: 3px; right: 3px;
        width: 12px; height: 7px;
        border-left: 3px solid #090E39;
        border-bottom: 3px solid #090E39;
        transform: rotate(-45deg);
        z-index: 1;
    }

    /* Native file input restyling */
    .file-brutal {
        display: block;
        width: 100%;
        padding: .7rem;
        background: #fff;
        border: 2px dashed #112E81;
        border-radius: .9rem;
        color: #112E81;
        font-weight: 600;
        font-size: .875rem;
        cursor: pointer;
        transition: border-color .2s, background .2s;
    }
    .file-brutal::file-selector-button {
        background: #4382DF;
        color: #090E39;
        border: 2px solid #090E39;
        border-radius: .6rem;
        padding: .45rem .9rem;
        margin-right: .85rem;
        font-weight: 800;
        font-size: .75rem;
        cursor: pointer;
        transition: background .2s;
    }
    .file-brutal::file-selector-button:hover { background: #4647AE; color: #fff; }
    .file-brutal:hover { border-color: #4382DF; background: #f8fafc; }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after { transition-duration: .01ms !important; animation-duration: .01ms !important; }
        .reveal { opacity: 1; transform: none; }
    }
</style>

<div id="register-page" class="relative bg-[#090E39] text-white min-h-screen overflow-hidden">
    {{-- Background decor --}}
    <div class="absolute inset-0 home-grid opacity-50 pointer-events-none" aria-hidden="true"></div>
    <div class="absolute -top-40 -left-40 w-[38rem] h-[38rem] bg-[#4647AE]/40 rounded-full blur-[120px] pointer-events-none" aria-hidden="true"></div>
    <div class="absolute top-1/3 -right-40 w-[38rem] h-[38rem] bg-[#4382DF]/25 rounded-full blur-[120px] pointer-events-none" aria-hidden="true"></div>

    <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">

        {{-- ============ BREADCRUMB ============ --}}
        <nav class="flex items-center flex-wrap gap-1.5 text-sm text-white/50 mb-5">
            <a href="{{ route('event.events.index') }}"
               class="inline-flex items-center gap-1 hover:text-white transition font-semibold">
                {!! $icon('ticket', 'w-3.5 h-3.5') !!}
                Events
            </a>
            {!! $icon('chevron-right', 'w-3.5 h-3.5') !!}
            <a href="{{ route('event.events.show', $event->slug) }}"
               class="hover:text-white transition font-semibold truncate max-w-[12rem] sm:max-w-[18rem]">
                {{ $event->title }}
            </a>
            {!! $icon('chevron-right', 'w-3.5 h-3.5') !!}
            <span class="text-white font-extrabold">Daftar</span>
        </nav>

        {{-- ============ HEADER ============ --}}
        <div class="reveal flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-7">
            <div>
                <span class="hov-pop inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white text-[#090E39] text-xs font-extrabold uppercase tracking-widest extrude-sm ex-b mb-4">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#4382DF] opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-[#4647AE]"></span>
                    </span>
                    Registrasi
                </span>
                <h1 class="font-display text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight">
                    Daftar
                    <span class="relative inline-block mx-1 my-1">
                        <span class="absolute inset-0 bg-[#4647AE] -rotate-2 rounded-2xl extrude-sm ex-k" aria-hidden="true"></span>
                        <span class="relative px-3">Event</span>
                    </span>
                </h1>
                <p class="text-white/70 mt-2.5 flex flex-wrap items-center gap-x-2 gap-y-1 text-sm">
                    <span class="font-semibold text-white">{{ $event->title }}</span>
                    <span class="text-white/30">·</span>
                    <span class="inline-flex items-center gap-1">
                        {!! $icon('calendar', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                        {{ $event->start_date->format('d M Y') }}
                    </span>
                </p>
                <p class="text-xs text-white/50 mt-1 font-semibold">
                    Registrasi ke-<span class="text-[#4382DF] font-extrabold">{{ $myRegistrationsCount + 1 }}</span> dari maksimal 3x
                </p>
            </div>

            <a href="{{ route('event.events.show', $event->slug) }}"
               class="btn-3d ex-k self-start inline-flex items-center gap-2 bg-white text-[#090E39] px-4 py-2.5 rounded-xl font-extrabold text-sm">
                {!! $icon('arrow-left', 'w-4 h-4') !!}
                Kembali
            </a>
        </div>

        {{-- ============ FORM ============ --}}
        <form method="POST" action="{{ route('event.registrations.store', $event->slug) }}"
              enctype="multipart/form-data" class="space-y-5 sm:space-y-6"
              x-data="{ selectedTicket: null, tickets: {{ $event->availableTickets()->toJson() }} }">
            @csrf

            {{-- ============ DATA PEMESAN ============ --}}
            <div class="reveal bg-[#112E81]/40 backdrop-blur border-2 border-white/15 rounded-[2rem] p-5 sm:p-6">
                <h2 class="font-display font-extrabold text-lg mb-4 inline-flex items-center gap-2">
                    <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-[#090E39]">
                        {!! $icon('user', 'w-4 h-4') !!}
                    </span>
                    Data Pemesan
                </h2>

                <div class="space-y-2.5">
                    <div class="flex items-center justify-between gap-3 bg-white/5 border border-white/10 rounded-xl px-4 py-3">
                        <span class="inline-flex items-center gap-2 text-white/60 text-sm font-semibold">
                            {!! $icon('user', 'w-4 h-4 text-[#4382DF]') !!}
                            Nama
                        </span>
                        <span class="font-extrabold text-white text-sm text-right truncate">{{ auth()->user()->name }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-3 bg-white/5 border border-white/10 rounded-xl px-4 py-3">
                        <span class="inline-flex items-center gap-2 text-white/60 text-sm font-semibold">
                            {!! $icon('mail', 'w-4 h-4 text-[#4382DF]') !!}
                            Email
                        </span>
                        <span class="font-extrabold text-white text-sm text-right truncate">{{ auth()->user()->email }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-3 bg-white/5 border border-white/10 rounded-xl px-4 py-3">
                        <span class="inline-flex items-center gap-2 text-white/60 text-sm font-semibold">
                            {!! $icon('at-sign', 'w-4 h-4 text-[#4382DF]') !!}
                            Username
                        </span>
                        <span class="font-extrabold text-white text-sm text-right truncate">{{ '@' . auth()->user()->username }}</span>
                    </div>
                </div>
            </div>

            {{-- ============ PILIH TIKET ============ --}}
            <div class="reveal bg-[#112E81]/40 backdrop-blur border-2 border-white/15 rounded-[2rem] p-5 sm:p-6">
                <h2 class="font-display font-extrabold text-lg mb-4 inline-flex items-center gap-2">
                    <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-[#090E39]">
                        {!! $icon('ticket', 'w-4 h-4') !!}
                    </span>
                    Pilih Kategori Tiket
                    <span class="text-red-400 text-base font-extrabold">*</span>
                </h2>

                <div class="space-y-3">
                    @foreach ($event->availableTickets() as $ticket)
                        <label class="block cursor-pointer">
                            <input type="radio" name="ticket_category_id" value="{{ $ticket->id }}"
                                   class="peer sr-only"
                                   @change="selectedTicket = tickets.find(t => t.id == {{ $ticket->id }})"
                                   @checked(old('ticket_category_id') == $ticket->id)>

                            <div class="ticket-option peer-checked:is-selected">
                                <div class="flex justify-between items-start gap-3">
                                    <div class="flex-1 min-w-0">
                                        <p class="font-display font-extrabold text-base text-[#090E39]">{{ $ticket->name }}</p>
                                        @if ($ticket->description)
                                            <p class="text-xs text-[#112E81]/70 mt-1 leading-relaxed">{{ $ticket->description }}</p>
                                        @endif
                                        <p class="inline-flex items-center gap-1 text-[11px] font-extrabold uppercase tracking-widest text-[#112E81]/60 mt-2">
                                            {!! $icon('users', 'w-3 h-3') !!}
                                            Sisa {{ $ticket->quota - $ticket->sold_count }} / {{ $ticket->quota }}
                                        </p>
                                    </div>

                                    <div class="text-right shrink-0">
                                        <p class="font-display font-extrabold text-xl text-[#4647AE]">
                                            @if ($ticket->price == 0)
                                                GRATIS
                                            @else
                                                Rp {{ number_format($ticket->price, 0, ',', '.') }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>

                @error('ticket_category_id')
                    <p class="inline-flex items-center gap-1.5 text-sm text-red-300 mt-3 font-semibold bg-red-500/20 border border-red-400/40 rounded-lg px-3 py-2">
                        {!! $icon('alert', 'w-4 h-4') !!}
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- ============ UPLOAD BUKTI BAYAR (jika tiket berbayar) ============ --}}
            <div class="reveal bg-[#112E81]/40 backdrop-blur border-2 border-white/15 rounded-[2rem] p-5 sm:p-6"
                 x-show="selectedTicket && selectedTicket.price > 0"
                 x-cloak>
                <h2 class="font-display font-extrabold text-lg mb-3 inline-flex items-center gap-2">
                    <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-[#090E39]">
                        {!! $icon('upload-cloud', 'w-4 h-4') !!}
                    </span>
                    Upload Bukti Pembayaran
                    <span class="text-red-400 text-base font-extrabold">*</span>
                </h2>

                <div class="bg-[#090E39]/60 border-2 border-dashed border-[#4382DF]/60 rounded-2xl p-4 mb-4 text-sm">
                    <p class="inline-flex items-center gap-2 text-[#4382DF] font-extrabold uppercase tracking-widest text-xs mb-2.5">
                        {!! $icon('info', 'w-4 h-4') !!}
                        Instruksi Pembayaran
                    </p>
                    <ul class="text-white/80 text-xs space-y-1.5">
                        <li class="flex items-start gap-2">
                            <span class="shrink-0 mt-1 w-1.5 h-1.5 rounded-full bg-[#4382DF]"></span>
                            <span>Transfer ke <strong class="text-white">BCA 1234567890</strong> a/n PT DIGANOVA</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="shrink-0 mt-1 w-1.5 h-1.5 rounded-full bg-[#4382DF]"></span>
                            <span>Jumlah: <strong class="text-white" x-text="'Rp ' + (selectedTicket?.price || 0).toLocaleString('id-ID')"></strong></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="shrink-0 mt-1 w-1.5 h-1.5 rounded-full bg-[#4382DF]"></span>
                            <span>Berita transfer: <strong class="text-white">{{ auth()->user()->username }}</strong></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="shrink-0 mt-1 w-1.5 h-1.5 rounded-full bg-[#4382DF]"></span>
                            <span>Upload screenshot/foto bukti transfer di bawah</span>
                        </li>
                    </ul>
                </div>

                <input type="file" name="payment_proof" accept="image/*" class="file-brutal">
                <p class="inline-flex items-center gap-1.5 text-xs text-white/50 mt-2 font-semibold">
                    {!! $icon('file-image', 'w-3.5 h-3.5') !!}
                    JPG, PNG, WebP. Maks 3MB.
                </p>

                @error('payment_proof')
                    <p class="inline-flex items-center gap-1.5 text-sm text-red-300 mt-3 font-semibold bg-red-500/20 border border-red-400/40 rounded-lg px-3 py-2">
                        {!! $icon('alert', 'w-4 h-4') !!}
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- ============ FREE TICKET INFO ============ --}}
            <div class="reveal bg-emerald-500/15 backdrop-blur border-2 border-emerald-400/50 rounded-[2rem] p-5 sm:p-6"
                 x-show="selectedTicket && selectedTicket.price == 0"
                 x-cloak>
                <div class="flex items-start gap-3">
                    <span class="shrink-0 w-10 h-10 grid place-items-center rounded-xl bg-emerald-400 text-[#090E39] border-2 border-[#090E39]">
                        {!! $icon('check-circle', 'w-5 h-5') !!}
                    </span>
                    <div class="min-w-0">
                        <p class="font-display font-extrabold text-white text-base">Tiket Gratis!</p>
                        <p class="text-sm text-white/70 mt-1 leading-relaxed">
                            Kamu tidak perlu upload bukti pembayaran. Tiket akan langsung aktif setelah submit.
                        </p>
                    </div>
                </div>
            </div>

            {{-- ============ RINGKASAN ============ --}}
            <div class="reveal bg-white text-[#090E39] border-2 border-white rounded-[2rem] extrude ex-b p-5 sm:p-6">
                <div class="flex items-center justify-between gap-3">
                    <div class="inline-flex items-center gap-3">
                        <span class="shrink-0 w-11 h-11 grid place-items-center rounded-xl bg-[#4382DF] text-white">
                            {!! $icon('wallet', 'w-5 h-5') !!}
                        </span>
                        <div>
                            <p class="text-[10px] font-extrabold uppercase tracking-widest text-[#112E81]/60">Total Bayar</p>
                            <p class="text-xs font-semibold text-[#112E81]/70">Termasuk semua biaya</p>
                        </div>
                    </div>
                    <p class="font-display font-extrabold text-2xl sm:text-3xl text-[#4647AE] text-right"
                       x-text="selectedTicket ? (selectedTicket.price == 0 ? 'GRATIS' : 'Rp ' + selectedTicket.price.toLocaleString('id-ID')) : '-'"></p>
                </div>
            </div>

            {{-- ============ SUBMIT ============ --}}
            <div class="flex flex-col-reverse sm:flex-row gap-3">
                <a href="{{ route('event.events.show', $event->slug) }}"
                   class="inline-flex items-center justify-center gap-2 bg-white/5 hover:bg-white/10 text-white border-2 border-white/25 px-6 py-3 rounded-2xl font-extrabold text-sm transition">
                    {!! $icon('arrow-left', 'w-4 h-4') !!}
                    Batal
                </a>
                <button type="submit"
                        class="btn-3d ex-k flex-1 inline-flex items-center justify-center gap-2 bg-[#4647AE] text-white px-6 py-3 rounded-2xl font-extrabold text-base border-2 border-[#4647AE] hover:bg-[#4382DF] hover:border-[#4382DF] transition">
                    {!! $icon('check-circle', 'w-5 h-5') !!}
                    Konfirmasi Pendaftaran
                </button>
            </div>

            @error('general')
                <p class="inline-flex items-center gap-1.5 text-sm text-red-300 font-semibold bg-red-500/20 border border-red-400/40 rounded-lg px-3 py-2 justify-center w-full">
                    {!! $icon('alert', 'w-4 h-4') !!}
                    {{ $message }}
                </p>
            @enderror
        </form>
    </div>
</div>

<style>[x-cloak]{display:none!important}</style>

{{-- Reveal on scroll --}}
<script>
(function () {
    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const reveals = document.querySelectorAll('.reveal');
    if (!('IntersectionObserver' in window) || reduce) {
        reveals.forEach(el => el.classList.add('in'));
    } else {
        const io = new IntersectionObserver((entries) => {
            entries.forEach(en => {
                if (en.isIntersecting) {
                    en.target.classList.add('in');
                    io.unobserve(en.target);
                }
            });
        }, { threshold: 0.12 });
        reveals.forEach(el => io.observe(el));
    }
})();
</script>

@endsection