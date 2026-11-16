@extends('layouts.public')

@section('title', 'Registrasi ' . $registration->order_code . ' — DIGANOVA')

@section('content')

@php
    /* ====== Icon library ====== */
    $icons = [
        'check-circle'   => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/>',
        'clock'          => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
        'x-circle'       => '<circle cx="12" cy="12" r="10"/><path d="m15 9-6 6M9 9l6 6"/>',
        'help-circle'    => '<circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/>',
        'ticket'         => '<path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><path d="M13 5v2M13 17v2M13 11v2"/>',
        'download'       => '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5"/><path d="M12 15V3"/>',
        'eye'            => '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>',
        'arrow-left'     => '<path d="m12 19-7-7 7-7M5 12h14"/>',
        'hash'           => '<path d="M4 9h16M4 15h16M10 3 8 21M16 3l-2 18"/>',
        'calendar'       => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
        'map-pin'        => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
        'tag'            => '<path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"/><circle cx="7.5" cy="7.5" r=".5" fill="currentColor"/>',
        'wallet'         => '<path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/>',
        'clock-history'  => '<path d="M12 8v4l3 3"/><path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5"/>',
        'receipt'        => '<path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><path d="M12 17.5v-11"/>',
        'image'          => '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>',
        'external-link'  => '<path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>',
        'alert-triangle' => '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4M12 17h.01"/>',
        'user'           => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
        'sparkles'       => '<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/><path d="M20 3v4M22 5h-4M4 17v2M5 18H3"/>',
    ];

    $icon = fn (string $name, string $class = 'w-6 h-6') =>
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';

    /* Status config (icon name, title, desc) */
    $statusConfig = match($registration->payment_status) {
        'verified' => [
            'tone'  => 'emerald',
            'icon'  => 'check-circle',
            'title' => 'Pembayaran Terverifikasi',
            'desc'  => 'Tiket kamu sudah aktif. Tunjukkan E-Ticket saat check-in di venue.',
        ],
        'pending' => [
            'tone'  => 'amber',
            'icon'  => 'clock',
            'title' => 'Menunggu Verifikasi',
            'desc'  => 'Admin akan memverifikasi pembayaran kamu dalam 1x24 jam.',
        ],
        'rejected' => [
            'tone'  => 'red',
            'icon'  => 'x-circle',
            'title' => 'Pembayaran Ditolak',
            'desc'  => $registration->payment_notes ?: 'Hubungi admin untuk info lebih lanjut.',
        ],
        default => [
            'tone'  => 'slate',
            'icon'  => 'help-circle',
            'title' => 'Status Tidak Diketahui',
            'desc'  => '',
        ],
    };
@endphp

<style>
    :where(#registration-detail) { --c-indigo: #4647AE; --c-blue: #4382DF; --c-deep: #112E81; --c-navy: #090E39; }
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

    /* Status tone backgrounds */
    .tone-emerald { background: linear-gradient(135deg, #10B981 0%, #059669 100%); }
    .tone-amber   { background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); }
    .tone-red     { background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%); }
    .tone-slate   { background: linear-gradient(135deg, #64748B 0%, #475569 100%); }

    /* Data rows in detail list */
    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        padding: .85rem 0;
        border-bottom: 2px dashed rgba(255,255,255,.1);
    }
    .detail-row:last-child { border-bottom: 0; }
    .detail-row dt {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        color: rgba(255,255,255,.6);
        font-weight: 600;
        font-size: .875rem;
    }
    .detail-row dd {
        color: #fff;
        font-weight: 700;
        text-align: right;
        font-size: .875rem;
    }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after { transition-duration: .01ms !important; animation-duration: .01ms !important; }
        .reveal { opacity: 1; transform: none; }
    }
</style>

<div id="registration-detail" class="relative bg-[#090E39] text-white min-h-screen overflow-hidden">
    {{-- Background decor --}}
    <div class="absolute inset-0 home-grid opacity-50 pointer-events-none" aria-hidden="true"></div>
    <div class="absolute -top-40 -left-40 w-[38rem] h-[38rem] bg-[#4647AE]/40 rounded-full blur-[120px] pointer-events-none" aria-hidden="true"></div>
    <div class="absolute top-1/3 -right-40 w-[38rem] h-[38rem] bg-[#4382DF]/25 rounded-full blur-[120px] pointer-events-none" aria-hidden="true"></div>

    <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">

        {{-- ============ BACK LINK ============ --}}
        <a href="{{ route('event.registrations.index') }}"
           class="inline-flex items-center gap-1.5 text-sm font-extrabold text-white/70 hover:text-white transition mb-5 group">
            <span class="w-7 h-7 grid place-items-center rounded-lg bg-white/10 border border-white/20 group-hover:bg-[#4382DF] group-hover:text-[#090E39] group-hover:border-[#4382DF] transition">
                {!! $icon('arrow-left', 'w-3.5 h-3.5') !!}
            </span>
            Kembali ke Registrasi Saya
        </a>

        {{-- ============ STATUS BANNER ============ --}}
        <div class="reveal relative rounded-[2rem] border-2 border-white {{ 'tone-' . $statusConfig['tone'] }} extrude ex-b p-6 sm:p-7 mb-6">
            <div class="flex items-start gap-4">
                {{-- Icon tile --}}
                <div class="relative shrink-0">
                    <div class="absolute inset-0 translate-x-1 translate-y-1 rounded-2xl bg-[#090E39]"></div>
                    <div class="relative w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-white text-[#090E39] grid place-items-center border-2 border-[#090E39]">
                        {!! $icon($statusConfig['icon'], 'w-7 h-7 sm:w-8 sm:h-8') !!}
                    </div>
                </div>

                <div class="flex-1 min-w-0">
                    <h1 class="font-display text-xl sm:text-2xl font-extrabold leading-tight">{{ $statusConfig['title'] }}</h1>
                    <p class="text-sm text-white/90 mt-1.5 leading-relaxed">{{ $statusConfig['desc'] }}</p>
                </div>
            </div>
        </div>

        {{-- ============ E-TICKET ACTION (verified only) ============ --}}
        @if ($registration->payment_status === 'verified')
            <div class="reveal relative bg-gradient-to-br from-[#4382DF] via-[#4647AE] to-[#112E81] text-white border-2 border-white rounded-[2rem] extrude ex-k p-5 sm:p-6 mb-6">
                <div class="flex items-start gap-4">
                    <div class="relative shrink-0">
                        <div class="absolute inset-0 translate-x-1 translate-y-1 rounded-2xl bg-[#090E39]"></div>
                        <div class="relative w-14 h-14 rounded-2xl bg-white text-[#4647AE] grid place-items-center border-2 border-[#090E39]">
                            {!! $icon('ticket', 'w-7 h-7') !!}
                        </div>
                    </div>

                    <div class="flex-1 min-w-0">
                        <h2 class="font-display font-extrabold text-lg sm:text-xl leading-tight">
                            E-Ticket Kamu Siap!
                        </h2>
                        <p class="text-sm text-white/80 mt-1">
                            Kode:
                            <span class="inline-flex items-center gap-1 font-mono font-extrabold text-white bg-[#090E39]/60 border border-white/25 rounded-md px-2 py-0.5 ml-1">
                                {!! $icon('hash', 'w-3 h-3') !!}
                                {{ $registration->e_ticket_code }}
                            </span>
                        </p>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <a href="{{ route('event.registrations.ticket', $registration->order_code) }}"
                               class="btn-3d ex-k inline-flex items-center gap-2 bg-white text-[#090E39] px-5 py-2.5 rounded-xl font-extrabold text-sm border-2 border-white">
                                {!! $icon('eye', 'w-4 h-4') !!}
                                Lihat E-Ticket
                            </a>
                            <a href="{{ route('event.registrations.ticket.download', $registration->order_code) }}"
                               class="btn-3d ex-b inline-flex items-center gap-2 bg-[#090E39] text-white px-5 py-2.5 rounded-xl font-extrabold text-sm border-2 border-white">
                                {!! $icon('download', 'w-4 h-4') !!}
                                Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ============ ORDER INFO ============ --}}
        <div class="reveal bg-[#112E81]/40 backdrop-blur border-2 border-white/15 rounded-[2rem] p-5 sm:p-6 mb-5">
            <h2 class="font-display font-extrabold text-lg mb-3 inline-flex items-center gap-2">
                <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-[#090E39]">
                    {!! $icon('receipt', 'w-4 h-4') !!}
                </span>
                Detail Pesanan
            </h2>

            <dl class="mt-2">
                <div class="detail-row">
                    <dt>{!! $icon('hash', 'w-4 h-4 text-[#4382DF]') !!} Kode Order</dt>
                    <dd class="font-mono text-[#4382DF]">{{ $registration->order_code }}</dd>
                </div>

                <div class="detail-row">
                    <dt>{!! $icon('ticket', 'w-4 h-4 text-[#4382DF]') !!} Event</dt>
                    <dd class="max-w-[60%]">
                        <a href="{{ route('event.events.show', $registration->event->slug) }}"
                           class="inline-flex items-center gap-1 text-[#4382DF] hover:text-white underline underline-offset-4 transition">
                            {{ $registration->event->title }}
                            {!! $icon('external-link', 'w-3 h-3 shrink-0') !!}
                        </a>
                    </dd>
                </div>

                <div class="detail-row">
                    <dt>{!! $icon('calendar', 'w-4 h-4 text-[#4382DF]') !!} Tanggal Event</dt>
                    <dd>{{ $registration->event->start_date->format('d M Y H:i') }}</dd>
                </div>

                <div class="detail-row">
                    <dt>{!! $icon('map-pin', 'w-4 h-4 text-[#4382DF]') !!} Lokasi</dt>
                    <dd class="max-w-[60%] truncate">{{ $registration->event->location }}</dd>
                </div>

                <div class="detail-row">
                    <dt>{!! $icon('tag', 'w-4 h-4 text-[#4382DF]') !!} Kategori Tiket</dt>
                    <dd>{{ $registration->ticketCategory->name }}</dd>
                </div>

                <div class="detail-row">
                    <dt>{!! $icon('wallet', 'w-4 h-4 text-[#4382DF]') !!} Total Bayar</dt>
                    <dd class="text-[#4382DF] font-display text-base">
                        @if ($registration->ticketCategory->price == 0)
                            GRATIS
                        @else
                            Rp {{ number_format($registration->ticketCategory->price, 0, ',', '.') }}
                        @endif
                    </dd>
                </div>

                <div class="detail-row">
                    <dt>{!! $icon('clock-history', 'w-4 h-4 text-[#4382DF]') !!} Tanggal Daftar</dt>
                    <dd>{{ $registration->created_at->format('d M Y H:i') }}</dd>
                </div>
            </dl>
        </div>

        {{-- ============ BUKTI PEMBAYARAN ============ --}}
        @if ($registration->payment_proof_path)
            <div class="reveal bg-[#112E81]/40 backdrop-blur border-2 border-white/15 rounded-[2rem] p-5 sm:p-6 mb-5">
                <h2 class="font-display font-extrabold text-lg mb-3 inline-flex items-center gap-2">
                    <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-[#090E39]">
                        {!! $icon('image', 'w-4 h-4') !!}
                    </span>
                    Bukti Pembayaran
                </h2>

                <div class="bg-white rounded-2xl p-2 border-2 border-white inline-block max-w-full">
                    <img src="{{ Storage::url($registration->payment_proof_path) }}"
                         class="rounded-xl max-w-full h-auto block"
                         style="max-height: 360px;"
                         alt="Bukti transfer">
                </div>

                @if ($registration->payment_verified_at)
                    <p class="mt-3 inline-flex items-start gap-1.5 text-xs text-white/60 font-semibold">
                        {!! $icon('check-circle', 'w-3.5 h-3.5 text-emerald-400 shrink-0 mt-0.5') !!}
                        <span>
                            Diproses pada: <b class="text-white">{{ $registration->payment_verified_at->format('d M Y H:i') }}</b>
                            @if ($registration->verifier)
                                oleh <b class="text-white">{{ $registration->verifier->name }}</b>
                            @endif
                        </span>
                    </p>
                @endif
            </div>
        @endif

        {{-- ============ CATATAN PENOLAKAN ============ --}}
        @if ($registration->payment_status === 'rejected' && $registration->payment_notes)
            <div class="reveal relative bg-red-500/15 backdrop-blur border-2 border-red-400 rounded-[2rem] p-5 sm:p-6">
                <div class="flex items-start gap-3">
                    <span class="shrink-0 w-10 h-10 grid place-items-center rounded-xl bg-red-500 text-white border-2 border-[#090E39]">
                        {!! $icon('alert-triangle', 'w-5 h-5') !!}
                    </span>
                    <div class="min-w-0">
                        <h2 class="font-display font-extrabold text-lg text-white">Alasan Penolakan</h2>
                        <p class="text-sm text-white/80 mt-1.5 leading-relaxed">{{ $registration->payment_notes }}</p>
                        <p class="inline-flex items-center gap-1.5 text-xs text-red-200 font-semibold mt-3">
                            {!! $icon('user', 'w-3.5 h-3.5') !!}
                            Hubungi admin atau daftar ulang dengan bukti pembayaran yang benar.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- ============ REVEAL JS ============ --}}
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