@extends('admin.layouts.app')

@section('title', $event->title . ' — Admin')

@section('content')

@php
    $pendingCount = $event->registrations()->where('payment_status', 'pending')->count();
    $verifiedCount = $event->registrations()->where('payment_status', 'verified')->count();
    $rejectedCount = $event->registrations()->where('payment_status', 'rejected')->count();
    $totalRegistrations = $pendingCount + $verifiedCount + $rejectedCount;

    $recentRegistrations = $event->registrations()
        ->with(['user', 'ticketCategory'])
        ->latest()
        ->take(5)
        ->get();

    $totalSold = $event->ticketCategories->sum('sold_count');
    $totalQuota = $event->ticketCategories->sum('quota');

    /* ====== Icon library ====== */
    $icons = [
        'arrow-left'      => '<path d="m12 19-7-7 7-7M5 12h14"/>',
        'external-link'   => '<path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>',
        'edit'            => '<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4Z"/>',
        'file-text'       => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4M10 9H8M16 13H8M16 17H8"/>',
        'ticket'          => '<path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><path d="M13 5v2M13 17v2M13 11v2"/>',
        'calendar'        => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
        'calendar-clock'  => '<path d="M21 7.5V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h3.5"/><path d="M16 2v4M8 2v4M3 10h5"/><path d="M17.5 17.5 16 16.3V14"/><circle cx="16" cy="16" r="6"/>',
        'map-pin'         => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
        'user'            => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
        'users'           => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',
        'at-sign'         => '<circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8"/>',
        'clock'           => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
        'check-circle'    => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/>',
        'x-circle'        => '<circle cx="12" cy="12" r="10"/><path d="m15 9-6 6M9 9l6 6"/>',
        'list'            => '<path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>',
        'activity'        => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>',
        'message-circle'  => '<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22z"/>',
        'tag'             => '<path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"/><circle cx="7.5" cy="7.5" r=".5" fill="currentColor"/>',
        'info'            => '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>',
        'alert-triangle'  => '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4M12 17h.01"/>',
        'trash'           => '<path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>',
        'arrow-right'     => '<path d="M5 12h14M12 5l7 7-7 7"/>',
        'hash'            => '<path d="M4 9h16M4 15h16M10 3 8 21M16 3l-2 18"/>',
        'image'           => '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>',
        'banknote'        => '<rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/>',
        'zap'             => '<path d="M13 2 3 14h9l-1 8 10-12h-9z"/>',
    ];
    $icon = fn (string $name, string $class = 'w-5 h-5') =>
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';

    $statCards = [
        [
            'label'  => 'Total Registrasi',
            'value'  => $totalRegistrations,
            'icon'   => 'list',
            'bg'     => 'bg-[#4382DF]',
            'border' => 'border-[#090E39]',
            'text'   => 'text-[#090E39]',
            'shadow' => 'shadow-[4px_4px_0_#090E39]',
        ],
        [
            'label'  => 'Menunggu',
            'value'  => $pendingCount,
            'icon'   => 'clock',
            'bg'     => 'bg-amber-100',
            'border' => 'border-amber-400',
            'text'   => 'text-amber-900',
            'shadow' => 'shadow-[4px_4px_0_#B45309]',
        ],
        [
            'label'  => 'Terverifikasi',
            'value'  => $verifiedCount,
            'icon'   => 'check-circle',
            'bg'     => 'bg-emerald-100',
            'border' => 'border-emerald-400',
            'text'   => 'text-emerald-900',
            'shadow' => 'shadow-[4px_4px_0_#047857]',
        ],
        [
            'label'  => 'Ditolak',
            'value'  => $rejectedCount,
            'icon'   => 'x-circle',
            'bg'     => 'bg-red-100',
            'border' => 'border-red-400',
            'text'   => 'text-red-900',
            'shadow' => 'shadow-[4px_4px_0_#991B1B]',
        ],
    ];
@endphp

<style>
    :where(#admin-event-detail) { --c-indigo: #4647AE; --c-blue: #4382DF; --c-navy: #090E39; }
    .font-display { font-family: 'Bricolage Grotesque', system-ui, sans-serif; }

    .brutal-card {
        background: #fff;
        border: 2px solid #090E39;
        border-radius: 1.25rem;
        box-shadow: 4px 4px 0 #090E39;
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
        padding: .65rem 0;
        border-bottom: 1.5px dashed rgba(9,14,57,.12);
    }
    .detail-row:last-child { border-bottom: 0; padding-bottom: 0; }
    .detail-row:first-child { padding-top: 0; }
    .detail-row .label {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        color: rgba(9,14,57,.55);
        font-weight: 700;
        font-size: .75rem;
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

<div id="admin-event-detail">

    {{-- ============ HEADER ============ --}}
    <div class="mb-6 flex justify-between items-start flex-wrap gap-3">
        <div class="min-w-0">
            <a href="{{ route('admin.events.index') }}"
               class="inline-flex items-center gap-1.5 text-sm font-extrabold text-[#4647AE] hover:text-[#4382DF] transition group">
                <span class="w-7 h-7 grid place-items-center rounded-lg bg-white border-2 border-[#4647AE] group-hover:bg-[#4382DF] group-hover:text-white group-hover:border-[#4382DF] transition">
                    {!! $icon('arrow-left', 'w-3.5 h-3.5') !!}
                </span>
                Kembali
            </a>

            <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-[#090E39] mt-3 leading-tight">
                {{ $event->title }}
            </h1>
            <div class="mt-2 flex flex-wrap items-center gap-2 text-xs">
                <span class="inline-flex items-center gap-1.5 bg-[#4382DF]/10 text-[#112E81] border-2 border-[#4382DF]/30 px-2.5 py-1 rounded-full font-extrabold">
                    {!! $icon('calendar', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                    {{ $event->start_date->format('d M Y H:i') }}
                </span>
                <span class="inline-flex items-center gap-1.5 bg-[#4647AE]/10 text-[#112E81] border-2 border-[#4647AE]/30 px-2.5 py-1 rounded-full font-extrabold">
                    {!! $icon('map-pin', 'w-3.5 h-3.5 text-[#4647AE]') !!}
                    {{ $event->location }}
                </span>
            </div>
        </div>

        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('event.events.show', $event->slug) }}" target="_blank"
               class="btn-3d inline-flex items-center gap-2 bg-white text-[#090E39] px-4 py-2 rounded-xl font-extrabold text-sm border-2 border-[#090E39]">
                {!! $icon('external-link', 'w-4 h-4') !!}
                Lihat Publik
            </a>
            <a href="{{ route('admin.events.edit', $event) }}"
               class="btn-3d inline-flex items-center gap-2 bg-[#4647AE] text-white px-4 py-2 rounded-xl font-extrabold text-sm border-2 border-[#090E39] hover:bg-[#4382DF]">
                {!! $icon('edit', 'w-4 h-4') !!}
                Edit Event
            </a>
        </div>
    </div>

    {{-- ============ STATS CARDS ============ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach ($statCards as $card)
            <div class="bg-white {{ $card['border'] }} border-2 rounded-2xl p-4 {{ $card['shadow'] }} hover:-translate-y-1 transition-transform">
                <div class="flex justify-between items-start gap-2">
                    <div class="min-w-0">
                        <p class="inline-flex items-center gap-1.5 text-[10px] {{ $card['text'] }} uppercase tracking-widest font-extrabold">
                            {!! $icon($card['icon'], 'w-3 h-3') !!}
                            {{ $card['label'] }}
                        </p>
                        <p class="font-display text-3xl sm:text-4xl font-extrabold text-[#090E39] mt-1.5 leading-none">
                            {{ $card['value'] }}
                        </p>
                    </div>
                    <span class="shrink-0 w-10 h-10 grid place-items-center rounded-xl {{ $card['bg'] }} {{ $card['text'] }} border-2 {{ $card['border'] }}">
                        {!! $icon($card['icon'], 'w-5 h-5') !!}
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ==================================================
             MAIN
             ================================================== --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Banner --}}
            @if ($event->banner_image)
                <div class="brutal-card overflow-hidden p-2">
                    <img src="{{ Storage::url($event->banner_image) }}"
                         class="w-full h-48 object-cover rounded-xl" alt="Banner">
                </div>
            @endif

            {{-- ============ DESKRIPSI ============ --}}
            <div class="brutal-card p-5 sm:p-6">
                <h2 class="font-display font-extrabold text-lg text-[#090E39] mb-3 inline-flex items-center gap-2">
                    <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-white border-2 border-[#090E39]">
                        {!! $icon('file-text', 'w-4 h-4') !!}
                    </span>
                    Deskripsi
                </h2>
                <div class="text-sm text-[#090E39]/80 whitespace-pre-line leading-relaxed font-medium">{{ $event->description }}</div>
            </div>

            {{-- ============ REGISTRASI TERBARU ============ --}}
            <div class="brutal-card p-5 sm:p-6">
                <div class="flex justify-between items-center mb-4 flex-wrap gap-2">
                    <h2 class="font-display font-extrabold text-lg text-[#090E39] inline-flex items-center gap-2">
                        <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-white border-2 border-[#090E39]">
                            {!! $icon('users', 'w-4 h-4') !!}
                        </span>
                        Registrasi Terbaru
                    </h2>

                    @if ($totalRegistrations > 0)
                        <a href="#" onclick="event.preventDefault(); alert('Kelola registrasi lengkap akan hadir di Step 13.');"
                           class="inline-flex items-center gap-1 text-xs font-extrabold text-[#4647AE] hover:text-[#4382DF] underline underline-offset-2">
                            Lihat Semua ({{ $totalRegistrations }})
                            {!! $icon('arrow-right', 'w-3 h-3') !!}
                        </a>
                    @endif
                </div>

                @if ($recentRegistrations->isEmpty())
                    <div class="text-center py-8">
                        <div class="mx-auto mb-2 w-12 h-12 grid place-items-center rounded-xl bg-[#4382DF]/10 text-[#4382DF] border-2 border-[#4382DF]/30">
                            {!! $icon('users', 'w-6 h-6') !!}
                        </div>
                        <p class="text-sm text-[#090E39]/50 font-bold">Belum ada registrasi.</p>
                    </div>
                @else
                    <div class="overflow-x-auto -mx-2">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b-2 border-dashed border-[#090E39]/15">
                                    <th class="text-left py-2.5 px-2 font-extrabold text-[10px] uppercase tracking-widest text-[#090E39]/50">
                                        <span class="inline-flex items-center gap-1.5">{!! $icon('user', 'w-3 h-3 text-[#4382DF]') !!} Peserta</span>
                                    </th>
                                    <th class="text-left py-2.5 px-2 font-extrabold text-[10px] uppercase tracking-widest text-[#090E39]/50">
                                        <span class="inline-flex items-center gap-1.5">{!! $icon('ticket', 'w-3 h-3 text-[#4382DF]') !!} Tiket</span>
                                    </th>
                                    <th class="text-left py-2.5 px-2 font-extrabold text-[10px] uppercase tracking-widest text-[#090E39]/50">
                                        Status
                                    </th>
                                    <th class="text-right py-2.5 px-2 font-extrabold text-[10px] uppercase tracking-widest text-[#090E39]/50">
                                        <span class="inline-flex items-center gap-1.5 justify-end">{!! $icon('calendar', 'w-3 h-3 text-[#4382DF]') !!} Tanggal</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y-2 divide-dashed divide-[#090E39]/8">
                                @foreach ($recentRegistrations as $reg)
                                    @php
                                        $statusMeta = match($reg->payment_status) {
                                            'verified' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'border' => 'border-emerald-400', 'icon' => 'check-circle'],
                                            'pending'  => ['bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'border' => 'border-amber-400', 'icon' => 'clock'],
                                            'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-400', 'icon' => 'x-circle'],
                                            default    => ['bg' => 'bg-slate-100', 'text' => 'text-slate-800', 'border' => 'border-slate-400', 'icon' => 'list'],
                                        };
                                    @endphp
                                    <tr class="hover:bg-[#4382DF]/5 transition-colors">
                                        <td class="py-3 px-2">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <span class="w-8 h-8 rounded-full bg-gradient-to-br from-[#4647AE] to-[#4382DF] text-white grid place-items-center text-xs font-extrabold border-2 border-[#090E39] shrink-0">
                                                    {{ strtoupper(substr($reg->user->name, 0, 1)) }}
                                                </span>
                                                <div class="min-w-0">
                                                    <p class="font-extrabold text-[#090E39] text-sm truncate">{{ $reg->user->name }}</p>
                                                    <p class="text-[11px] text-[#090E39]/55 font-semibold truncate inline-flex items-center gap-0.5">
                                                        {!! $icon('at-sign', 'w-2.5 h-2.5 text-[#4382DF]') !!}
                                                        {{ $reg->user->username }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-2">
                                            <span class="inline-flex items-center gap-1 bg-[#4647AE]/10 text-[#4647AE] border border-[#4647AE]/30 px-2 py-0.5 rounded-lg text-xs font-extrabold">
                                                {!! $icon('tag', 'w-3 h-3') !!}
                                                {{ $reg->ticketCategory->name }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-2">
                                            <span class="inline-flex items-center gap-1 {{ $statusMeta['bg'] }} {{ $statusMeta['text'] }} {{ $statusMeta['border'] }} border-2 px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider">
                                                {!! $icon($statusMeta['icon'], 'w-3 h-3') !!}
                                                {{ $reg->payment_status }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-2 text-right text-xs text-[#090E39]/60 font-bold whitespace-nowrap">
                                            {{ $reg->created_at->format('d M H:i') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- ============ JADWAL ============ --}}
            <div class="brutal-card p-5 sm:p-6">
                <h2 class="font-display font-extrabold text-lg text-[#090E39] mb-4 inline-flex items-center gap-2">
                    <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-white border-2 border-[#090E39]">
                        {!! $icon('calendar-clock', 'w-4 h-4') !!}
                    </span>
                    Jadwal
                    <span class="text-xs font-extrabold text-[#090E39]/50 bg-slate-100 border border-[#090E39]/15 rounded-full px-2 py-0.5">
                        {{ $event->schedules->count() }}
                    </span>
                </h2>

                @if ($event->schedules->isEmpty())
                    <p class="text-sm text-[#090E39]/50 font-bold">Belum ada jadwal.</p>
                @else
                    <ul class="space-y-2">
                        @foreach ($event->schedules as $s)
                            <li class="flex justify-between items-center gap-3 border-2 border-[#090E39]/10 rounded-xl p-3 hover:border-[#4382DF]/40 transition-colors">
                                <span class="font-extrabold text-[#090E39] text-sm min-w-0 truncate">{{ $s->title }}</span>
                                <span class="inline-flex items-center gap-1 text-xs text-[#090E39]/60 font-bold shrink-0">
                                    {!! $icon('clock', 'w-3 h-3 text-[#4382DF]') !!}
                                    {{ $s->start_time->format('d M H:i') }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- ============ TIKET ============ --}}
            <div class="brutal-card p-5 sm:p-6">
                <div class="flex justify-between items-center mb-4 flex-wrap gap-2">
                    <h2 class="font-display font-extrabold text-lg text-[#090E39] inline-flex items-center gap-2">
                        <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-white border-2 border-[#090E39]">
                            {!! $icon('ticket', 'w-4 h-4') !!}
                        </span>
                        Tiket
                        <span class="text-xs font-extrabold text-[#090E39]/50 bg-slate-100 border border-[#090E39]/15 rounded-full px-2 py-0.5">
                            {{ $event->ticketCategories->count() }}
                        </span>
                    </h2>
                    <span class="inline-flex items-center gap-1.5 text-xs text-[#090E39]/70 font-extrabold bg-[#4382DF]/10 border border-[#4382DF]/30 rounded-full px-2.5 py-1">
                        {!! $icon('banknote', 'w-3 h-3 text-[#4382DF]') !!}
                        Terjual: {{ $totalSold }} / {{ $totalQuota }}
                    </span>
                </div>

                @if ($event->ticketCategories->isEmpty())
                    <p class="text-sm text-[#090E39]/50 font-bold">Belum ada kategori tiket.</p>
                @else
                    <ul class="space-y-3">
                        @foreach ($event->ticketCategories as $t)
                            @php
                                $percent = $t->quota > 0 ? round(($t->sold_count / $t->quota) * 100) : 0;
                                $isFull = $t->sold_count >= $t->quota;
                            @endphp
                            <li class="border-2 border-[#090E39]/10 rounded-xl p-3 hover:border-[#4382DF]/40 transition-colors">
                                <div class="flex justify-between items-start gap-2 mb-2">
                                    <div class="min-w-0">
                                        <span class="font-extrabold text-[#090E39] text-sm">{{ $t->name }}</span>
                                        @if (!$t->is_active)
                                            <span class="inline-flex items-center gap-1 text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded-md font-extrabold uppercase tracking-wide ml-1 border border-slate-300">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </div>
                                    <span class="font-display font-extrabold text-sm text-[#4647AE] whitespace-nowrap">
                                        Rp {{ number_format($t->price, 0, ',', '.') }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-slate-100 rounded-full h-2 overflow-hidden border border-[#090E39]/10">
                                        <div class="h-full rounded-full transition-all {{ $isFull ? 'bg-red-500' : 'bg-[#4382DF]' }}"
                                             style="width: {{ $percent }}%"></div>
                                    </div>
                                    <span class="text-[11px] text-[#090E39]/60 font-extrabold whitespace-nowrap">
                                        {{ $t->sold_count }}/{{ $t->quota }} ({{ $percent }}%)
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        {{-- ==================================================
             SIDEBAR
             ================================================== --}}
        <div class="space-y-6">

            {{-- ============ INFO ============ --}}
            <div class="brutal-card p-5 sm:p-6">
                <h2 class="font-display font-extrabold text-lg text-[#090E39] mb-3 inline-flex items-center gap-2">
                    <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-white border-2 border-[#090E39]">
                        {!! $icon('info', 'w-4 h-4') !!}
                    </span>
                    Info
                </h2>

                <dl>
                    <div class="detail-row">
                        <dt class="label">{!! $icon('activity', 'w-3.5 h-3.5 text-[#4382DF]') !!} Status</dt>
                        <dd class="value capitalize">{{ $event->status }}</dd>
                    </div>
                    <div class="detail-row">
                        <dt class="label">{!! $icon('tag', 'w-3.5 h-3.5 text-[#4382DF]') !!} Kategori</dt>
                        <dd class="value capitalize">{{ $event->category }}</dd>
                    </div>
                    <div class="detail-row">
                        <dt class="label">{!! $icon('map-pin', 'w-3.5 h-3.5 text-[#4382DF]') !!} Lokasi</dt>
                        <dd class="value max-w-[60%]">{{ $event->location }}</dd>
                    </div>
                    <div class="detail-row">
                        <dt class="label">{!! $icon('calendar', 'w-3.5 h-3.5 text-[#4382DF]') !!} Mulai</dt>
                        <dd class="value">{{ $event->start_date->format('d M Y H:i') }}</dd>
                    </div>
                    <div class="detail-row">
                        <dt class="label">{!! $icon('calendar', 'w-3.5 h-3.5 text-[#4382DF]') !!} Selesai</dt>
                        <dd class="value">{{ $event->end_date->format('d M Y H:i') }}</dd>
                    </div>
                    @if ($event->registration_deadline)
                        <div class="detail-row">
                            <dt class="label">{!! $icon('clock', 'w-3.5 h-3.5 text-[#4382DF]') !!} Deadline</dt>
                            <dd class="value">{{ $event->registration_deadline->format('d M Y H:i') }}</dd>
                        </div>
                    @endif
                    <div class="detail-row">
                        <dt class="label">{!! $icon('user', 'w-3.5 h-3.5 text-[#4382DF]') !!} Dibuat</dt>
                        <dd class="value">{{ $event->creator->name ?? '-' }}</dd>
                    </div>
                    <div class="detail-row">
                        <dt class="label">{!! $icon('message-circle', 'w-3.5 h-3.5 text-[#4382DF]') !!} Grup</dt>
                        <dd class="value max-w-[60%]">{{ $event->group->name ?? 'Belum ada' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- ============ PESERTA ============ --}}
            <div class="brutal-card p-5 sm:p-6">
                <h2 class="font-display font-extrabold text-lg text-[#090E39] mb-3 inline-flex items-center gap-2">
                    <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-white border-2 border-[#090E39]">
                        {!! $icon('users', 'w-4 h-4') !!}
                    </span>
                    Peserta
                </h2>

                <dl class="space-y-2.5 text-sm">
                    <div class="flex justify-between items-center">
                        <dt class="inline-flex items-center gap-1.5 text-[#090E39]/55 font-bold text-xs uppercase tracking-wide">
                            {!! $icon('list', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                            Total terdaftar
                        </dt>
                        <dd class="font-extrabold text-[#090E39]">{{ $totalRegistrations }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="inline-flex items-center gap-1.5 text-[#090E39]/55 font-bold text-xs uppercase tracking-wide">
                            {!! $icon('check-circle', 'w-3.5 h-3.5 text-emerald-600') !!}
                            Terverifikasi
                        </dt>
                        <dd class="font-extrabold text-emerald-700">{{ $verifiedCount }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="inline-flex items-center gap-1.5 text-[#090E39]/55 font-bold text-xs uppercase tracking-wide">
                            {!! $icon('clock', 'w-3.5 h-3.5 text-amber-600') !!}
                            Menunggu
                        </dt>
                        <dd class="font-extrabold text-amber-700">{{ $pendingCount }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="inline-flex items-center gap-1.5 text-[#090E39]/55 font-bold text-xs uppercase tracking-wide">
                            {!! $icon('x-circle', 'w-3.5 h-3.5 text-red-600') !!}
                            Ditolak
                        </dt>
                        <dd class="font-extrabold text-red-700">{{ $rejectedCount }}</dd>
                    </div>

                    @if ($event->max_participants)
                        <div class="flex justify-between items-center pt-2.5 mt-1 border-t-2 border-dashed border-[#090E39]/15">
                            <dt class="inline-flex items-center gap-1.5 text-[#090E39]/55 font-bold text-xs uppercase tracking-wide">
                                {!! $icon('zap', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                                Kuota event
                            </dt>
                            <dd class="font-extrabold text-[#090E39]">{{ $event->max_participants }}</dd>
                        </div>
                    @endif
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
                <p class="text-xs text-[#090E39]/60 font-semibold mb-3 leading-relaxed">
                    Hapus event akan menghapus semua data terkait: registrasi, grup, jadwal, tiket.
                </p>

                <form method="POST" action="{{ route('admin.events.destroy', $event) }}"
                      onsubmit="return confirm('Yakin hapus event ini? Tindakan tidak bisa dibatalkan.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="btn-3d w-full inline-flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 text-white px-4 py-2.5 rounded-xl font-extrabold text-sm border-2 border-[#090E39]">
                        {!! $icon('trash', 'w-4 h-4') !!}
                        Hapus Event
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection