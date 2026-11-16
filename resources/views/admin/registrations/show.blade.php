@extends('admin.layouts.app')

@section('title', 'Registrasi ' . $registration->order_code . ' — Admin')

@section('content')

@php
    /* ====== Icon library ====== */
    $icons = [
        'arrow-left'       => '<path d="m12 19-7-7 7-7M5 12h14"/>',
        'check-circle'     => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/>',
        'clock'            => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
        'x-circle'         => '<circle cx="12" cy="12" r="10"/><path d="m15 9-6 6M9 9l6 6"/>',
        'help-circle'      => '<circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/>',
        'user'             => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
        'mail'             => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/>',
        'at-sign'          => '<circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8"/>',
        'trophy'           => '<path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6M18 9h1.5a2.5 2.5 0 0 0 0-5H18M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2z"/>',
        'star'             => '<path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/>',
        'ticket'           => '<path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><path d="M13 5v2M13 17v2M13 11v2"/>',
        'calendar'         => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
        'map-pin'          => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
        'tag'              => '<path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"/><circle cx="7.5" cy="7.5" r=".5" fill="currentColor"/>',
        'image'            => '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>',
        'external-link'    => '<path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>',
        'shield-check'     => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>',
        'check'            => '<path d="M20 6 9 17l-5-5"/>',
        'x'                => '<path d="M18 6 6 18M6 6l12 12"/>',
        'trash'            => '<path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>',
        'alert-triangle'   => '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4M12 17h.01"/>',
        'banknote'         => '<rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/>',
        'history'          => '<path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M12 7v5l4 2"/>',
        'scan'             => '<path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/><path d="M7 12h10"/>',
        'info'             => '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>',
        'hash'             => '<path d="M4 9h16M4 15h16M10 3 8 21M16 3l-2 18"/>',
        'qr-code'          => '<rect x="3" y="3" width="5" height="5" rx="1"/><rect x="16" y="3" width="5" height="5" rx="1"/><rect x="3" y="16" width="5" height="5" rx="1"/><path d="M21 16h-3a2 2 0 0 0-2 2v3M21 21v.01M12 7v3a2 2 0 0 1-2 2H7M3 12h.01M12 3h.01M12 16v.01M16 12h1M21 12v.01M12 21v-1"/>',
        'gift'             => '<rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"/><path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"/>',
    ];

    $icon = fn (string $name, string $class = 'w-5 h-5') =>
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';

    /* ====== Status config ====== */
    $statusConfig = match($registration->payment_status) {
        'verified' => [
            'tone'  => 'emerald',
            'bg'    => 'bg-emerald-100',
            'text'  => 'text-emerald-800',
            'border'=> 'border-emerald-400',
            'shadow'=> 'shadow-[3px_3px_0_#047857]',
            'icon'  => 'check-circle',
            'label' => 'Terverifikasi',
        ],
        'pending' => [
            'tone'  => 'amber',
            'bg'    => 'bg-amber-100',
            'text'  => 'text-amber-800',
            'border'=> 'border-amber-400',
            'shadow'=> 'shadow-[3px_3px_0_#B45309]',
            'icon'  => 'clock',
            'label' => 'Menunggu Verifikasi',
        ],
        'rejected' => [
            'tone'  => 'red',
            'bg'    => 'bg-red-100',
            'text'  => 'text-red-800',
            'border'=> 'border-red-400',
            'shadow'=> 'shadow-[3px_3px_0_#991B1B]',
            'icon'  => 'x-circle',
            'label' => 'Ditolak',
        ],
        default => [
            'tone'  => 'slate',
            'bg'    => 'bg-slate-100',
            'text'  => 'text-slate-800',
            'border'=> 'border-slate-400',
            'shadow'=> 'shadow-[3px_3px_0_#334155]',
            'icon'  => 'help-circle',
            'label' => 'Unknown',
        ],
    };
@endphp

<style>
    /* Admin light neo-brutalist */
    :where(#admin-registration-detail) {
        --c-indigo: #4647AE; --c-blue: #4382DF; --c-deep: #112E81; --c-navy: #090E39;
    }
    .font-display { font-family: 'Bricolage Grotesque', system-ui, sans-serif; }

    .brutal-card {
        background: #fff;
        border: 2px solid #090E39;
        border-radius: 1.25rem;
        box-shadow: 4px 4px 0 #090E39;
    }
    .brutal-card-sm {
        background: #fff;
        border: 2px solid #090E39;
        border-radius: 1rem;
        box-shadow: 3px 3px 0 #090E39;
    }

    .btn-3d {
        --ec: #090E39;
        transform: translate(0, 0);
        box-shadow: 3px 3px 0 var(--ec);
        transition: transform .15s ease, box-shadow .15s ease, background .2s, color .2s;
    }
    .btn-3d:hover {
        transform: translate(2px, 2px);
        box-shadow: 1px 1px 0 var(--ec);
    }
    .btn-3d:active {
        transform: translate(3px, 3px);
        box-shadow: 0 0 0 var(--ec);
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        padding: .7rem 0;
        border-bottom: 1.5px dashed rgba(9,14,57,.15);
    }
    .detail-row:last-child { border-bottom: 0; padding-bottom: 0; }
    .detail-row:first-child { padding-top: 0; }
    .detail-row .label {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        color: rgba(9,14,57,.55);
        font-weight: 700;
        font-size: .8rem;
        text-transform: uppercase;
        letter-spacing: .05em;
    }
    .detail-row .value {
        color: #090E39;
        font-weight: 800;
        text-align: right;
        font-size: .875rem;
        word-break: break-word;
    }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after { transition-duration: .01ms !important; }
    }
</style>

<div id="admin-registration-detail">

    {{-- ============ HEADER ============ --}}
    <div class="mb-6">
        <a href="{{ route('admin.registrations.index') }}"
           class="inline-flex items-center gap-1.5 text-sm font-extrabold text-[#4647AE] hover:text-[#4382DF] transition group">
            <span class="w-7 h-7 grid place-items-center rounded-lg bg-white border-2 border-[#4647AE] group-hover:bg-[#4382DF] group-hover:text-white group-hover:border-[#4382DF] transition">
                {!! $icon('arrow-left', 'w-3.5 h-3.5') !!}
            </span>
            Kembali ke daftar registrasi
        </a>

        <div class="flex justify-between items-start mt-3 flex-wrap gap-3">
            <div class="min-w-0">
                <div class="flex items-center gap-2.5 flex-wrap">
                    <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-[#090E39] flex items-center gap-2">
                        {!! $icon('hash', 'w-6 h-6 text-[#4382DF]') !!}
                        <span class="font-mono tracking-tight">{{ $registration->order_code }}</span>
                    </h1>

                    <span class="inline-flex items-center gap-1.5 {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} {{ $statusConfig['border'] }} {{ $statusConfig['shadow'] }} border-2 px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider">
                        {!! $icon($statusConfig['icon'], 'w-3.5 h-3.5') !!}
                        {{ $statusConfig['label'] }}
                    </span>
                </div>

                <p class="text-sm text-[#090E39]/60 mt-2 inline-flex items-center gap-1.5 font-semibold">
                    {!! $icon('calendar', 'w-3.5 h-3.5') !!}
                    Didaftarkan {{ $registration->created_at->format('d M Y H:i') }}
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ==================================================
             MAIN
             ================================================== --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- ============ PESERTA ============ --}}
            <div class="brutal-card p-5 sm:p-6">
                <h2 class="font-display font-extrabold text-lg text-[#090E39] mb-4 inline-flex items-center gap-2">
                    <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-white border-2 border-[#090E39]">
                        {!! $icon('user', 'w-4 h-4') !!}
                    </span>
                    Peserta
                </h2>

                <div class="flex items-start gap-4">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-[#4647AE] to-[#4382DF] flex items-center justify-center text-white text-xl font-extrabold flex-shrink-0 border-2 border-[#090E39] shadow-[2px_2px_0_#090E39]">
                        {{ strtoupper(substr($registration->user->name, 0, 1)) }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="font-display font-extrabold text-[#090E39] text-lg">{{ $registration->user->name }}</p>
                        <p class="text-sm text-[#090E39]/60 font-semibold inline-flex items-center gap-1.5 mt-0.5">
                            {!! $icon('at-sign', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                            {{ $registration->user->username }}
                        </p>
                        <p class="text-sm text-[#090E39]/60 font-semibold inline-flex items-center gap-1.5 mt-0.5">
                            {!! $icon('mail', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                            {{ $registration->user->email }}
                        </p>

                        <div class="mt-3 flex gap-2 flex-wrap">
                            <span class="inline-flex items-center gap-1 bg-[#4647AE] text-white text-xs font-extrabold px-2.5 py-1 rounded-lg border-2 border-[#090E39] capitalize">
                                {!! $icon('trophy', 'w-3 h-3') !!}
                                Rank {{ $registration->user->rank }}
                            </span>
                            <span class="inline-flex items-center gap-1 bg-[#4382DF] text-[#090E39] text-xs font-extrabold px-2.5 py-1 rounded-lg border-2 border-[#090E39]">
                                {!! $icon('star', 'w-3 h-3') !!}
                                {{ $registration->user->total_points }} poin
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============ EVENT ============ --}}
            <div class="brutal-card p-5 sm:p-6">
                <h2 class="font-display font-extrabold text-lg text-[#090E39] mb-3 inline-flex items-center gap-2">
                    <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-white border-2 border-[#090E39]">
                        {!! $icon('ticket', 'w-4 h-4') !!}
                    </span>
                    Event
                </h2>

                <dl>
                    <div class="detail-row">
                        <dt class="label">
                            {!! $icon('ticket', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                            Judul
                        </dt>
                        <dd class="value max-w-[65%]">
                            <a href="{{ route('event.events.show', $registration->event->slug) }}" target="_blank"
                               class="inline-flex items-center gap-1 text-[#4647AE] hover:text-[#4382DF] underline underline-offset-2">
                                {{ $registration->event->title }}
                                {!! $icon('external-link', 'w-3 h-3 shrink-0') !!}
                            </a>
                        </dd>
                    </div>

                    <div class="detail-row">
                        <dt class="label">
                            {!! $icon('calendar', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                            Tanggal
                        </dt>
                        <dd class="value">{{ $registration->event->start_date->format('d M Y H:i') }}</dd>
                    </div>

                    <div class="detail-row">
                        <dt class="label">
                            {!! $icon('map-pin', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                            Lokasi
                        </dt>
                        <dd class="value max-w-[65%]">{{ $registration->event->location }}</dd>
                    </div>

                    <div class="detail-row">
                        <dt class="label">
                            {!! $icon('tag', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                            Kategori Tiket
                        </dt>
                        <dd class="value">{{ $registration->ticketCategory->name }}</dd>
                    </div>
                </dl>
            </div>

            {{-- ============ BUKTI PEMBAYARAN ============ --}}
            <div class="brutal-card p-5 sm:p-6">
                <h2 class="font-display font-extrabold text-lg text-[#090E39] mb-4 inline-flex items-center gap-2">
                    <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-white border-2 border-[#090E39]">
                        {!! $icon('image', 'w-4 h-4') !!}
                    </span>
                    Bukti Pembayaran
                </h2>

                @if ($registration->ticketCategory->price == 0)
                    <div class="flex items-start gap-3 bg-emerald-50 border-2 border-emerald-400 rounded-xl p-4 shadow-[3px_3px_0_#047857]">
                        <span class="shrink-0 w-9 h-9 grid place-items-center rounded-lg bg-emerald-500 text-white border-2 border-[#090E39]">
                            {!! $icon('gift', 'w-4 h-4') !!}
                        </span>
                        <div>
                            <p class="font-display font-extrabold text-emerald-900 text-sm">Tiket Gratis</p>
                            <p class="text-xs text-emerald-800 font-semibold mt-0.5">Tidak memerlukan bukti pembayaran.</p>
                        </div>
                    </div>
                @elseif ($registration->payment_proof_path)
                    <div class="border-2 border-[#090E39] rounded-xl overflow-hidden shadow-[3px_3px_0_#090E39]">
                        <img src="{{ Storage::url($registration->payment_proof_path) }}"
                             class="w-full max-h-96 object-contain bg-slate-50" alt="Bukti transfer">
                    </div>

                    <div class="mt-3 flex justify-between items-center gap-3 flex-wrap text-xs">
                        <a href="{{ Storage::url($registration->payment_proof_path) }}" target="_blank"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#4382DF] text-white border-2 border-[#090E39] font-extrabold hover:bg-[#4647AE] transition shadow-[2px_2px_0_#090E39]">
                            {!! $icon('external-link', 'w-3.5 h-3.5') !!}
                            Buka Gambar Penuh
                        </a>

                        <span class="inline-flex items-center gap-1.5 text-[#090E39]/70 font-bold">
                            {!! $icon('banknote', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                            Nominal:
                            <strong class="text-[#090E39] text-sm font-extrabold">
                                Rp {{ number_format($registration->ticketCategory->price, 0, ',', '.') }}
                            </strong>
                        </span>
                    </div>
                @else
                    <div class="flex items-start gap-3 bg-amber-50 border-2 border-amber-400 rounded-xl p-4 shadow-[3px_3px_0_#B45309]">
                        <span class="shrink-0 w-9 h-9 grid place-items-center rounded-lg bg-amber-500 text-white border-2 border-[#090E39]">
                            {!! $icon('alert-triangle', 'w-4 h-4') !!}
                        </span>
                        <div>
                            <p class="font-display font-extrabold text-amber-900 text-sm">Belum Upload Bukti</p>
                            <p class="text-xs text-amber-800 font-semibold mt-0.5">User belum mengunggah bukti pembayaran.</p>
                        </div>
                    </div>
                @endif

                @if ($registration->payment_verified_at)
                    <div class="mt-4 pt-4 border-t-2 border-dashed border-[#090E39]/15 text-xs">
                        <p class="inline-flex items-center gap-1.5 text-[#090E39]/70 font-bold">
                            {!! $icon('shield-check', 'w-4 h-4 text-emerald-600') !!}
                            Diproses {{ $registration->payment_verified_at->format('d M Y H:i') }}
                            @if ($registration->verifier)
                                oleh <strong class="text-[#090E39]">{{ $registration->verifier->name }}</strong>
                            @endif
                        </p>

                        @if ($registration->payment_notes)
                            <div class="mt-2.5 bg-red-50 border-2 border-red-300 rounded-lg p-3 shadow-[2px_2px_0_#991B1B]">
                                <p class="inline-flex items-center gap-1 font-extrabold text-red-900 text-xs uppercase tracking-widest mb-1">
                                    {!! $icon('alert-triangle', 'w-3 h-3') !!}
                                    Catatan
                                </p>
                                <p class="text-xs text-red-800 font-semibold">{{ $registration->payment_notes }}</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- ============ CHECK-IN HISTORY ============ --}}
            @if ($registration->checkinLogs->isNotEmpty())
                <div class="brutal-card p-5 sm:p-6">
                    <h2 class="font-display font-extrabold text-lg text-[#090E39] mb-3 inline-flex items-center gap-2">
                        <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-white border-2 border-[#090E39]">
                            {!! $icon('history', 'w-4 h-4') !!}
                        </span>
                        Riwayat Check-in
                    </h2>

                    <ul class="space-y-2">
                        @foreach ($registration->checkinLogs as $log)
                            <li class="flex justify-between items-center gap-3 border-2 border-[#090E39]/10 rounded-xl p-3 hover:border-[#4382DF]/40 transition-colors">
                                <div class="min-w-0">
                                    <p class="inline-flex items-center gap-1.5 font-extrabold text-[#090E39] text-sm capitalize">
                                        {!! $icon('scan', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                                        {{ $log->method }}
                                    </p>
                                    <p class="text-xs text-[#090E39]/60 font-semibold mt-0.5">
                                        oleh {{ $log->scanner->name ?? 'sistem' }}
                                    </p>
                                </div>
                                <span class="text-xs text-[#090E39]/60 font-bold shrink-0">
                                    {{ $log->check_in_at->format('d M Y H:i') }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        {{-- ==================================================
             SIDEBAR
             ================================================== --}}
        <div class="space-y-6">

            {{-- ============ ACTIONS ============ --}}
            <div class="brutal-card p-5 sm:p-6" x-data="{ showRejectModal: false }">
                <h2 class="font-display font-extrabold text-lg text-[#090E39] mb-4 inline-flex items-center gap-2">
                    <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-white border-2 border-[#090E39]">
                        {!! $icon('shield-check', 'w-4 h-4') !!}
                    </span>
                    Aksi
                </h2>

                @if ($registration->payment_status === 'pending')
                    {{-- Approve --}}
                    <form method="POST" action="{{ route('admin.registrations.approve', $registration) }}"
                          onsubmit="return confirm('Verifikasi pembayaran {{ $registration->order_code }}? User akan otomatis masuk grup & dapat achievement.');"
                          class="mb-3">
                        @csrf
                        <button type="submit"
                                class="btn-3d w-full inline-flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-3 rounded-xl font-extrabold text-sm border-2 border-[#090E39]">
                            {!! $icon('check-circle', 'w-4 h-4') !!}
                            Approve Pembayaran
                        </button>
                    </form>

                    {{-- Reject --}}
                    <button type="button" @click="showRejectModal = true"
                            class="btn-3d w-full inline-flex items-center justify-center gap-2 bg-red-100 hover:bg-red-200 text-red-700 px-4 py-3 rounded-xl font-extrabold text-sm border-2 border-red-500">
                        {!! $icon('x-circle', 'w-4 h-4') !!}
                        Tolak Pembayaran
                    </button>

                    {{-- ============ MODAL REJECT ============ --}}
                    <div x-show="showRejectModal" x-cloak
                         class="fixed inset-0 bg-[#090E39]/70 backdrop-blur-sm z-50 flex items-center justify-center p-4"
                         @click.self="showRejectModal = false">
                        <div class="bg-white border-2 border-[#090E39] rounded-2xl shadow-[6px_6px_0_#090E39] max-w-md w-full p-6">

                            <div class="flex items-start gap-3 mb-4">
                                <span class="shrink-0 w-11 h-11 grid place-items-center rounded-xl bg-red-100 text-red-600 border-2 border-red-400">
                                    {!! $icon('x-circle', 'w-5 h-5') !!}
                                </span>
                                <div>
                                    <h3 class="font-display font-extrabold text-[#090E39] text-lg">Tolak Registrasi</h3>
                                    <p class="text-xs text-[#090E39]/60 font-semibold mt-0.5">
                                        Order: <span class="font-mono font-extrabold text-[#090E39]">{{ $registration->order_code }}</span>
                                    </p>
                                    <p class="text-xs text-[#090E39]/60 font-semibold">
                                        Peserta: <strong class="text-[#090E39]">{{ $registration->user->name }}</strong>
                                    </p>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('admin.registrations.reject', $registration) }}">
                                @csrf

                                <label class="block text-xs font-extrabold uppercase tracking-widest text-[#090E39]/70 mb-1.5">
                                    Alasan Penolakan <span class="text-red-600">*</span>
                                </label>
                                <textarea name="notes" rows="4" required minlength="5" maxlength="500"
                                          class="w-full px-3.5 py-2.5 bg-slate-50 border-2 border-[#090E39]/20 rounded-xl focus:border-red-500 focus:bg-white focus:ring-0 outline-none transition text-sm font-semibold text-[#090E39]"
                                          placeholder="Contoh: Bukti transfer tidak jelas, nominal tidak sesuai, dst..."></textarea>

                                @error('notes')
                                    <p class="inline-flex items-center gap-1.5 text-xs text-red-600 mt-1.5 font-bold">
                                        {!! $icon('alert-triangle', 'w-3.5 h-3.5') !!}
                                        {{ $message }}
                                    </p>
                                @enderror

                                <div class="mt-4 flex gap-2">
                                    <button type="submit"
                                            class="btn-3d flex-1 bg-red-500 hover:bg-red-600 text-white px-4 py-2.5 rounded-xl font-extrabold text-sm border-2 border-[#090E39]">
                                        Tolak Registrasi
                                    </button>
                                    <button type="button" @click="showRejectModal = false"
                                            class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-[#090E39] rounded-xl font-extrabold text-sm border-2 border-[#090E39]/20 transition">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                @elseif ($registration->payment_status === 'verified')
                    <div class="bg-emerald-50 border-2 border-emerald-400 rounded-xl p-3 shadow-[3px_3px_0_#047857]">
                        <p class="inline-flex items-center gap-1.5 font-extrabold text-emerald-900 text-sm">
                            {!! $icon('check-circle', 'w-4 h-4') !!}
                            Sudah diverifikasi
                        </p>
                        <p class="text-xs text-emerald-800 font-semibold mt-1.5">
                            E-ticket:
                            <span class="font-mono font-extrabold text-emerald-900">{{ $registration->e_ticket_code }}</span>
                        </p>
                    </div>

                @else
                    <div class="bg-red-50 border-2 border-red-400 rounded-xl p-3 shadow-[3px_3px_0_#991B1B]">
                        <p class="inline-flex items-center gap-1.5 font-extrabold text-red-900 text-sm">
                            {!! $icon('x-circle', 'w-4 h-4') !!}
                            Registrasi ditolak
                        </p>
                        @if ($registration->payment_notes)
                            <p class="text-xs text-red-800 font-semibold mt-1.5">{{ $registration->payment_notes }}</p>
                        @endif
                    </div>
                @endif
            </div>

            {{-- ============ DETAIL ============ --}}
            <div class="brutal-card p-5 sm:p-6">
                <h2 class="font-display font-extrabold text-lg text-[#090E39] mb-3 inline-flex items-center gap-2">
                    <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-white border-2 border-[#090E39]">
                        {!! $icon('info', 'w-4 h-4') !!}
                    </span>
                    Detail
                </h2>

                <dl class="space-y-2.5 text-sm">
                    <div class="flex justify-between items-center gap-2">
                        <dt class="inline-flex items-center gap-1.5 text-[#090E39]/55 font-bold text-xs uppercase tracking-wide">
                            {!! $icon('hash', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                            Order Code
                        </dt>
                        <dd class="font-mono text-xs font-extrabold text-[#090E39] text-right break-all">{{ $registration->order_code }}</dd>
                    </div>

                    <div class="flex justify-between items-center gap-2">
                        <dt class="inline-flex items-center gap-1.5 text-[#090E39]/55 font-bold text-xs uppercase tracking-wide">
                            {!! $icon('qr-code', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                            E-Ticket
                        </dt>
                        <dd class="font-mono text-xs font-extrabold text-[#090E39] text-right break-all">{{ $registration->e_ticket_code }}</dd>
                    </div>

                    <div class="flex justify-between items-center gap-2">
                        <dt class="inline-flex items-center gap-1.5 text-[#090E39]/55 font-bold text-xs uppercase tracking-wide">
                            {!! $icon('banknote', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                            Status Bayar
                        </dt>
                        <dd class="font-extrabold text-[#090E39] text-right capitalize">{{ $registration->payment_status }}</dd>
                    </div>

                    <div class="flex justify-between items-center gap-2">
                        <dt class="inline-flex items-center gap-1.5 text-[#090E39]/55 font-bold text-xs uppercase tracking-wide">
                            {!! $icon('scan', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                            Check-in
                        </dt>
                        <dd class="text-right">
                            @if ($registration->checked_in)
                                <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-800 border-2 border-emerald-400 px-2 py-0.5 rounded-lg text-xs font-extrabold">
                                    {!! $icon('check', 'w-3 h-3') !!}
                                    Sudah
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-700 border-2 border-slate-400 px-2 py-0.5 rounded-lg text-xs font-extrabold">
                                    Belum
                                </span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- ============ DANGER ZONE ============ --}}
            <div class="bg-white border-2 border-red-500 rounded-2xl shadow-[4px_4px_0_#991B1B] p-5 sm:p-6">
                <h2 class="font-display font-extrabold text-lg text-red-700 mb-1 inline-flex items-center gap-2">
                    <span class="w-8 h-8 grid place-items-center rounded-lg bg-red-500 text-white border-2 border-[#090E39]">
                        {!! $icon('alert-triangle', 'w-4 h-4') !!}
                    </span>
                    Danger Zone
                </h2>
                <p class="text-xs text-[#090E39]/60 font-semibold mb-3">Hapus registrasi permanen?</p>

                <form method="POST"
                      action="#" {{-- nanti di batch berikut --}}
                      onsubmit="event.preventDefault(); alert('Hapus registrasi akan hadir nanti.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="btn-3d w-full inline-flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 text-white px-4 py-2.5 rounded-xl font-extrabold text-sm border-2 border-[#090E39]">
                        {!! $icon('trash', 'w-4 h-4') !!}
                        Hapus Registrasi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>[x-cloak]{display:none!important}</style>

@endsection