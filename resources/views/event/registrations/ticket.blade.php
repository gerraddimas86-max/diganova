@extends('layouts.public')

@section('title', 'E-Ticket ' . $registration->order_code . ' — DIGANOVA')

@section('content')

@php
    /* ====== Icon library ====== */
    $icons = [
        'arrow-left'     => '<path d="m12 19-7-7 7-7M5 12h14"/>',
        'ticket'         => '<path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><path d="M13 5v2M13 17v2M13 11v2"/>',
        'download'       => '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5"/><path d="M12 15V3"/>',
        'printer'        => '<path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>',
        'lightbulb'      => '<path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"/><path d="M9 18h6M10 22h4"/>',
        'check-circle'   => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/>',
        'calendar'       => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
        'tag'            => '<path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"/><circle cx="7.5" cy="7.5" r=".5" fill="currentColor"/>',
        'map-pin'        => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
        'user'           => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
        'hash'           => '<path d="M4 9h16M4 15h16M10 3 8 21M16 3l-2 18"/>',
        'scan-line'      => '<path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/><path d="M7 12h10"/>',
        'sparkles'       => '<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/><path d="M20 3v4M22 5h-4M4 17v2M5 18H3"/>',
    ];
    $icon = fn (string $name, string $class = 'w-6 h-6') =>
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';
@endphp

<style>
    :where(#ticket-page) { --c-indigo: #4647AE; --c-blue: #4382DF; --c-deep: #112E81; --c-navy: #090E39; }
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

    /* ============================
       TICKET (neo-brutalist)
       ============================ */
    .ticket-card {
        background: #fff;
        color: #090E39;
        border: 2px solid #090E39;
        border-radius: 2rem;
        overflow: hidden;
        position: relative;
        box-shadow:
            8px 8px 0 #4382DF,
            20px 24px 40px rgba(0,0,0,.55);
    }

    .ticket-header {
        background: #4647AE;
        background: linear-gradient(135deg, #4647AE 0%, #4382DF 50%, #112E81 100%);
        color: #fff;
        padding: 1.75rem 1.5rem;
        text-align: center;
        position: relative;
    }

    /* Ticket notch */
    .ticket-notch {
        position: relative;
        height: 28px;
        background: #fff;
    }
    .ticket-notch::before,
    .ticket-notch::after {
        content: '';
        position: absolute;
        top: 50%;
        width: 28px;
        height: 28px;
        background: #090E39;
        border-radius: 50%;
        transform: translateY(-50%);
        z-index: 3;
    }
    .ticket-notch::before { left: -16px; }
    .ticket-notch::after  { right: -16px; }
    .ticket-notch .dash {
        position: absolute;
        top: 50%;
        left: 1rem;
        right: 1rem;
        transform: translateY(-1px);
        border-top: 2px dashed rgba(9,14,57,.25);
    }

    /* QR frame */
    .qr-frame {
        display: inline-block;
        padding: 12px;
        background: #fff;
        border: 3px solid #090E39;
        border-radius: 1.25rem;
        box-shadow: 4px 4px 0 #4382DF;
    }

    /* Detail rows */
    .ticket-field {
        padding: .85rem 0;
        border-bottom: 1px dashed rgba(9,14,57,.15);
    }
    .ticket-field:last-child { border-bottom: 0; padding-bottom: 0; }
    .ticket-field:first-child { padding-top: 0; }

    .ticket-label {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: .18em;
        color: #6B7196;
        font-weight: 800;
    }
    .ticket-label svg { color: #4382DF; flex-shrink: 0; }

    .ticket-value {
        font-size: .95rem;
        font-weight: 800;
        color: #090E39;
        margin-top: .4rem;
        line-height: 1.4;
        word-break: break-word;
    }

    .ticket-sub {
        font-size: .75rem;
        color: #6B7196;
        margin-top: .2rem;
        font-weight: 600;
    }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after { transition-duration: .01ms !important; animation-duration: .01ms !important; }
        .reveal { opacity: 1; transform: none; }
    }

    /* ============================
       PRINT
       ============================ */
    @media print {
        body { background: #fff !important; }
        body * { visibility: hidden; }
        #ticket-card-print,
        #ticket-card-print * { visibility: visible; }
        #ticket-card-print {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
            box-shadow: none !important;
            border-radius: 0;
            border: 2px solid #090E39;
        }
        #ticket-page .no-print { display: none !important; }
    }
</style>

<div id="ticket-page" class="relative bg-[#090E39] text-white min-h-screen overflow-hidden">
    {{-- Background decor --}}
    <div class="absolute inset-0 home-grid opacity-50 pointer-events-none no-print" aria-hidden="true"></div>
    <div class="absolute -top-40 -left-40 w-[38rem] h-[38rem] bg-[#4647AE]/40 rounded-full blur-[120px] pointer-events-none no-print" aria-hidden="true"></div>
    <div class="absolute top-1/3 -right-40 w-[38rem] h-[38rem] bg-[#4382DF]/25 rounded-full blur-[120px] pointer-events-none no-print" aria-hidden="true"></div>

    <div class="relative max-w-md mx-auto px-4 sm:px-6 py-8 sm:py-10">

        {{-- ============ BACK LINK ============ --}}
        <a href="{{ route('event.registrations.show', $registration->order_code) }}"
           class="no-print inline-flex items-center gap-1.5 text-sm font-extrabold text-white/70 hover:text-white transition mb-5 group">
            <span class="w-7 h-7 grid place-items-center rounded-lg bg-white/10 border border-white/20 group-hover:bg-[#4382DF] group-hover:text-[#090E39] group-hover:border-[#4382DF] transition">
                {!! $icon('arrow-left', 'w-3.5 h-3.5') !!}
            </span>
            Kembali ke detail registrasi
        </a>

        {{-- ============ TICKET CARD ============ --}}
        <div id="ticket-card-print" class="ticket-card">

            {{-- Header --}}
            <div class="ticket-header">
                <p class="font-display text-3xl font-extrabold tracking-tight leading-none">
                    DIGANOVA
                </p>
                <p class="text-[11px] italic opacity-90 mt-1.5">
                    "Plan It. Join It. Enjoy It."
                </p>
                <span class="inline-flex items-center gap-1.5 mt-3.5 bg-white text-[#090E39] px-3.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest border-2 border-[#090E39] shadow-[2px_2px_0_#090E39]">
                    {!! $icon('ticket', 'w-3 h-3') !!}
                    E-Ticket · Admit One
                </span>
            </div>

            {{-- QR Section --}}
            <div class="px-6 pt-6 pb-4 flex flex-col items-center bg-white">
                <div class="qr-frame">
                    <div class="w-56 h-56 flex items-center justify-center">
                        {!! $qrSvg !!}
                    </div>
                </div>

                <p class="mt-4 inline-flex items-center gap-1 text-[10px] font-extrabold uppercase tracking-widest text-[#6B7196]">
                    {!! $icon('scan-line', 'w-3 h-3 text-[#4382DF]') !!}
                    Kode E-Ticket
                </p>
                <p class="font-mono font-extrabold text-xl text-[#090E39] tracking-widest mt-1">
                    {{ $registration->e_ticket_code }}
                </p>
                <p class="mt-1 text-[11px] text-[#6B7196] font-semibold">
                    Tunjukkan QR ini di pintu masuk venue
                </p>
            </div>

            {{-- Ticket Notch Divider --}}
            <div class="ticket-notch">
                <div class="dash"></div>
            </div>

            {{-- Details --}}
            <div class="px-6 pt-2 pb-6 bg-white">
                {{-- Event --}}
                <div class="ticket-field">
                    <p class="ticket-label">
                        {!! $icon('ticket', 'w-3 h-3') !!}
                        Event
                    </p>
                    <p class="ticket-value">{{ $registration->event->title }}</p>
                </div>

                {{-- Tanggal + Kategori --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="ticket-field">
                        <p class="ticket-label">
                            {!! $icon('calendar', 'w-3 h-3') !!}
                            Tanggal
                        </p>
                        <p class="ticket-value">{{ $registration->event->start_date->format('d M Y') }}</p>
                        <p class="ticket-sub">{{ $registration->event->start_date->format('H:i') }} WIB</p>
                    </div>
                    <div class="ticket-field">
                        <p class="ticket-label">
                            {!! $icon('tag', 'w-3 h-3') !!}
                            Kategori
                        </p>
                        <p class="ticket-value">{{ $registration->ticketCategory->name }}</p>
                    </div>
                </div>

                {{-- Lokasi --}}
                <div class="ticket-field">
                    <p class="ticket-label">
                        {!! $icon('map-pin', 'w-3 h-3') !!}
                        Lokasi
                    </p>
                    <p class="ticket-value">{{ $registration->event->location }}</p>
                    @if ($registration->event->address)
                        <p class="ticket-sub">{{ $registration->event->address }}</p>
                    @endif
                </div>

                {{-- Atas Nama + Order Code --}}
                <div class="grid grid-cols-2 gap-4 pt-1">
                    <div class="ticket-field !border-0 !pb-0">
                        <p class="ticket-label">
                            {!! $icon('user', 'w-3 h-3') !!}
                            Atas Nama
                        </p>
                        <p class="ticket-value">{{ $registration->user->name }}</p>
                    </div>
                    <div class="ticket-field !border-0 !pb-0">
                        <p class="ticket-label">
                            {!! $icon('hash', 'w-3 h-3') !!}
                            Order Code
                        </p>
                        <p class="ticket-value !font-mono !text-sm">{{ $registration->order_code }}</p>
                    </div>
                </div>
            </div>

            {{-- Check-in Status --}}
            @if ($registration->checked_in)
                <div class="bg-emerald-500/15 border-t-2 border-emerald-400 p-4 flex items-center justify-center gap-2.5">
                    <span class="shrink-0 w-9 h-9 grid place-items-center rounded-xl bg-emerald-500 text-white border-2 border-[#090E39]">
                        {!! $icon('check-circle', 'w-5 h-5') !!}
                    </span>
                    <div class="text-left">
                        <p class="text-sm font-extrabold text-emerald-300">Sudah Check-in</p>
                        <p class="text-xs text-emerald-200/80 font-semibold mt-0.5">
                            {{ $registration->checked_in_at->format('d M Y H:i') }}
                        </p>
                    </div>
                </div>
            @endif
        </div>

        {{-- ============ ACTIONS ============ --}}
        <div class="no-print mt-6 space-y-3">
            <a href="{{ route('event.registrations.ticket.download', $registration->order_code) }}"
               class="btn-3d ex-k flex items-center justify-center gap-2 w-full bg-white text-[#090E39] px-4 py-3.5 rounded-2xl font-extrabold text-sm border-2 border-white">
                {!! $icon('download', 'w-4 h-4') !!}
                Download PDF
            </a>

            <button type="button"
                    onclick="window.print()"
                    class="w-full inline-flex items-center justify-center gap-2 bg-white/5 hover:bg-white/10 text-white border-2 border-white/25 px-4 py-3.5 rounded-2xl font-extrabold text-sm transition">
                {!! $icon('printer', 'w-4 h-4') !!}
                Print / Save as Image
            </button>
        </div>

        {{-- ============ TIPS ============ --}}
        <div class="no-print mt-6 bg-[#112E81]/40 backdrop-blur border-2 border-white/15 rounded-2xl p-4">
            <p class="inline-flex items-center gap-2 text-[#4382DF] font-extrabold uppercase tracking-widest text-xs mb-2.5">
                {!! $icon('lightbulb', 'w-4 h-4') !!}
                Tips
            </p>
            <ul class="text-xs text-white/70 space-y-1.5">
                <li class="flex items-start gap-2">
                    <span class="shrink-0 mt-1 w-1.5 h-1.5 rounded-full bg-[#4382DF]"></span>
                    <span>Tingkatkan kecerahan layar saat menunjukkan QR</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="shrink-0 mt-1 w-1.5 h-1.5 rounded-full bg-[#4382DF]"></span>
                    <span>Bisa juga tunjukkan PDF yang sudah didownload</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="shrink-0 mt-1 w-1.5 h-1.5 rounded-full bg-[#4382DF]"></span>
                    <span>Kalau QR tidak terbaca, panitia bisa input kode manual</span>
                </li>
            </ul>
        </div>
    </div>
</div>

{{-- Reveal --}}
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