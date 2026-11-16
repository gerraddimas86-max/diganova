@extends('admin.layouts.app')

@section('title', 'Registrasi — Admin')

@section('content')

@php
    /* ====== Icon library ====== */
    $icons = [
        'arrow-left'      => '<path d="m12 19-7-7 7-7M5 12h14"/>',
        'ticket'          => '<path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><path d="M13 5v2M13 17v2M13 11v2"/>',
        'clock'           => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
        'check-circle'    => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/>',
        'x-circle'        => '<circle cx="12" cy="12" r="10"/><path d="m15 9-6 6M9 9l6 6"/>',
        'list'            => '<path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>',
        'inbox'           => '<polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>',
        'search'          => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>',
        'filter'          => '<path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z"/>',
        'hash'            => '<path d="M4 9h16M4 15h16M10 3 8 21M16 3l-2 18"/>',
        'user'            => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
        'at-sign'         => '<circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8"/>',
        'calendar'        => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
        'tag'             => '<path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"/><circle cx="7.5" cy="7.5" r=".5" fill="currentColor"/>',
        'banknote'        => '<rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/>',
        'check'           => '<path d="M20 6 9 17l-5-5"/>',
        'arrow-right'     => '<path d="M5 12h14M12 5l7 7-7 7"/>',
        'eye'             => '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>',
        'gift'            => '<rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"/><path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"/>',
    ];

    $icon = fn (string $name, string $class = 'w-5 h-5') =>
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';

    $statCards = [
        [
            'key'    => 'pending',
            'label'  => 'Pending',
            'desc'   => 'Menunggu verifikasi',
            'icon'   => 'clock',
            'bg'     => 'bg-amber-100',
            'border' => 'border-amber-400',
            'text'   => 'text-amber-900',
            'shadow' => 'shadow-[4px_4px_0_#B45309]',
        ],
        [
            'key'    => 'verified_today',
            'label'  => 'Verified Hari Ini',
            'desc'   => 'Sudah diverifikasi',
            'icon'   => 'check-circle',
            'bg'     => 'bg-emerald-100',
            'border' => 'border-emerald-400',
            'text'   => 'text-emerald-900',
            'shadow' => 'shadow-[4px_4px_0_#047857]',
        ],
        [
            'key'    => 'rejected_today',
            'label'  => 'Rejected Hari Ini',
            'desc'   => 'Ditolak',
            'icon'   => 'x-circle',
            'bg'     => 'bg-red-100',
            'border' => 'border-red-400',
            'text'   => 'text-red-900',
            'shadow' => 'shadow-[4px_4px_0_#991B1B]',
        ],
        [
            'key'    => 'total',
            'label'  => 'Total',
            'desc'   => 'Semua registrasi',
            'icon'   => 'list',
            'bg'     => 'bg-[#4382DF]',
            'border' => 'border-[#090E39]',
            'text'   => 'text-[#090E39]',
            'shadow' => 'shadow-[4px_4px_0_#090E39]',
        ],
    ];
@endphp

<style>
    :where(#admin-registrations) { --c-indigo: #4647AE; --c-blue: #4382DF; --c-navy: #090E39; }
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

    /* Brutal input */
    .input-brutal {
        background: #F5F7FF;
        color: #090E39;
        border: 2px solid rgba(9,14,57,.15);
        border-radius: .75rem;
        padding: .65rem 1rem;
        font-weight: 600;
        font-size: .875rem;
        transition: border-color .2s, background .2s, box-shadow .2s;
        outline: none;
        width: 100%;
    }
    .input-brutal::placeholder { color: rgba(9,14,57,.4); font-weight: 500; }
    .input-brutal:focus {
        background: #fff;
        border-color: #4382DF;
        box-shadow: 3px 3px 0 #4382DF;
    }
    .input-brutal.has-left-icon { padding-left: 2.75rem; }

    select.input-brutal {
        appearance: none;
        background-image:
            linear-gradient(45deg, transparent 50%, #090E39 50%),
            linear-gradient(135deg, #090E39 50%, transparent 50%);
        background-position: calc(100% - 20px) calc(50% - 2px), calc(100% - 14px) calc(50% - 2px);
        background-size: 6px 6px, 6px 6px;
        background-repeat: no-repeat;
        padding-right: 2.5rem;
    }

    /* Table row hover */
    tbody tr {
        transition: background .15s;
    }
    tbody tr:hover {
        background: #F5F7FF;
    }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after { transition-duration: .01ms !important; }
    }
</style>

<div id="admin-registrations">

    {{-- ============ HEADER ============ --}}
    <div class="flex flex-wrap justify-between items-start gap-3 mb-6">
        <div>
            <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-[#090E39] flex items-center gap-2">
                <span class="w-9 h-9 grid place-items-center rounded-xl bg-[#4382DF] text-white border-2 border-[#090E39] shadow-[2px_2px_0_#090E39]">
                    {!! $icon('ticket', 'w-5 h-5') !!}
                </span>
                Manajemen Registrasi
            </h1>
            <p class="text-sm text-[#090E39]/60 mt-2 inline-flex items-center gap-1.5 font-semibold">
                {!! $icon('check-circle', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                Kelola verifikasi pembayaran peserta event
            </p>
        </div>

        <a href="{{ route('admin.events.index') }}"
           class="btn-3d inline-flex items-center gap-2 bg-white text-[#090E39] px-4 py-2 rounded-xl font-extrabold text-sm border-2 border-[#090E39]">
            {!! $icon('arrow-left', 'w-4 h-4') !!}
            Events
        </a>
    </div>

    {{-- ============ STATS CARDS ============ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach ($statCards as $card)
            <div class="bg-white {{ $card['border'] }} border-2 rounded-2xl p-4 sm:p-5 {{ $card['shadow'] }} hover:-translate-y-1 transition-transform">
                <div class="flex justify-between items-start gap-2">
                    <div class="min-w-0">
                        <p class="inline-flex items-center gap-1.5 text-[10px] {{ $card['text'] }} uppercase tracking-widest font-extrabold">
                            {!! $icon($card['icon'], 'w-3 h-3') !!}
                            {{ $card['label'] }}
                        </p>
                        <p class="font-display text-3xl sm:text-4xl font-extrabold text-[#090E39] mt-1.5 leading-none">
                            {{ $stats[$card['key']] }}
                        </p>
                    </div>
                    <span class="shrink-0 w-10 h-10 grid place-items-center rounded-xl {{ $card['bg'] }} {{ $card['text'] }} border-2 {{ $card['border'] }}">
                        {!! $icon($card['icon'], 'w-5 h-5') !!}
                    </span>
                </div>
                <p class="text-[11px] text-[#090E39]/50 mt-2 font-bold">{{ $card['desc'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- ============ FILTER BAR ============ --}}
    <form method="GET" action="{{ route('admin.registrations.index') }}"
          class="brutal-card p-4 mb-6 flex flex-col lg:flex-row lg:items-center gap-3">

        {{-- Search --}}
        <div class="relative flex-1">
            <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-[#4382DF]">
                {!! $icon('search', 'w-4 h-4') !!}
            </span>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari order code, nama, atau email..."
                   class="input-brutal has-left-icon">
        </div>

        {{-- Status --}}
        <select name="status" class="input-brutal lg:w-44">
            <option value="">Semua Status</option>
            <option value="pending" @selected(request('status') === 'pending')>Pending</option>
            <option value="verified" @selected(request('status') === 'verified')>Verified</option>
            <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
        </select>

        {{-- Event --}}
        <select name="event_id" class="input-brutal lg:w-56">
            <option value="">Semua Event</option>
            @foreach ($events as $ev)
                <option value="{{ $ev->id }}" @selected(request('event_id') == $ev->id)>{{ $ev->title }}</option>
            @endforeach
        </select>

        {{-- Filter button --}}
        <button type="submit"
                class="btn-3d inline-flex items-center justify-center gap-2 bg-[#4647AE] text-white px-5 py-2.5 rounded-xl font-extrabold text-sm border-2 border-[#090E39] hover:bg-[#4382DF]">
            {!! $icon('filter', 'w-4 h-4') !!}
            Filter
        </button>

        {{-- Reset --}}
        @if (request()->hasAny(['search', 'status', 'event_id']))
            <a href="{{ route('admin.registrations.index') }}"
               class="inline-flex items-center justify-center px-3 py-2.5 rounded-xl bg-red-50 hover:bg-red-100 text-red-700 font-extrabold text-sm border-2 border-red-300 transition">
                Reset
            </a>
        @endif
    </form>

    {{-- ============ TABLE ============ --}}
    <div class="brutal-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#090E39] text-white">
                    <tr>
                        <th class="text-left px-4 py-3.5 font-extrabold text-xs uppercase tracking-widest">
                            <span class="inline-flex items-center gap-1.5">{!! $icon('hash', 'w-3.5 h-3.5 text-[#4382DF]') !!} Order Code</span>
                        </th>
                        <th class="text-left px-4 py-3.5 font-extrabold text-xs uppercase tracking-widest">
                            <span class="inline-flex items-center gap-1.5">{!! $icon('user', 'w-3.5 h-3.5 text-[#4382DF]') !!} Peserta</span>
                        </th>
                        <th class="text-left px-4 py-3.5 font-extrabold text-xs uppercase tracking-widest">
                            <span class="inline-flex items-center gap-1.5">{!! $icon('calendar', 'w-3.5 h-3.5 text-[#4382DF]') !!} Event</span>
                        </th>
                        <th class="text-left px-4 py-3.5 font-extrabold text-xs uppercase tracking-widest">
                            <span class="inline-flex items-center gap-1.5">{!! $icon('tag', 'w-3.5 h-3.5 text-[#4382DF]') !!} Tiket</span>
                        </th>
                        <th class="text-right px-4 py-3.5 font-extrabold text-xs uppercase tracking-widest">
                            <span class="inline-flex items-center gap-1.5 justify-end">{!! $icon('banknote', 'w-3.5 h-3.5 text-[#4382DF]') !!} Harga</span>
                        </th>
                        <th class="text-left px-4 py-3.5 font-extrabold text-xs uppercase tracking-widest">Status</th>
                        <th class="text-right px-4 py-3.5 font-extrabold text-xs uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-dashed divide-[#090E39]/10">
                    @forelse ($registrations as $reg)
                        @php
                            $statusMeta = match($reg->payment_status) {
                                'verified' => [
                                    'bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'border' => 'border-emerald-400',
                                    'icon' => 'check-circle', 'label' => 'Verified',
                                ],
                                'pending' => [
                                    'bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'border' => 'border-amber-400',
                                    'icon' => 'clock', 'label' => 'Pending',
                                ],
                                'rejected' => [
                                    'bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-400',
                                    'icon' => 'x-circle', 'label' => 'Rejected',
                                ],
                                default => [
                                    'bg' => 'bg-slate-100', 'text' => 'text-slate-800', 'border' => 'border-slate-400',
                                    'icon' => 'help-circle', 'label' => ucfirst($reg->payment_status),
                                ],
                            };
                        @endphp

                        <tr class="align-top">
                            {{-- Order Code --}}
                            <td class="px-4 py-4">
                                <p class="inline-flex items-center gap-1.5 font-mono text-xs font-extrabold text-[#090E39] bg-[#4382DF]/10 border border-[#4382DF]/30 rounded-md px-2 py-1">
                                    {!! $icon('hash', 'w-3 h-3 text-[#4382DF]') !!}
                                    {{ $reg->order_code }}
                                </p>
                                <p class="text-[10px] text-[#090E39]/50 mt-1 font-bold inline-flex items-center gap-1">
                                    {!! $icon('clock', 'w-2.5 h-2.5') !!}
                                    {{ $reg->created_at->format('d M H:i') }}
                                </p>
                            </td>

                            {{-- Peserta --}}
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="w-8 h-8 rounded-full bg-gradient-to-br from-[#4647AE] to-[#4382DF] text-white grid place-items-center text-xs font-extrabold border-2 border-[#090E39] shrink-0">
                                        {{ strtoupper(substr($reg->user->name, 0, 1)) }}
                                    </span>
                                    <div class="min-w-0">
                                        <p class="font-extrabold text-[#090E39] text-sm truncate">{{ $reg->user->name }}</p>
                                        <p class="text-[11px] text-[#090E39]/60 font-semibold inline-flex items-center gap-0.5">
                                            {!! $icon('at-sign', 'w-2.5 h-2.5 text-[#4382DF]') !!}
                                            {{ $reg->user->username }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- Event --}}
                            <td class="px-4 py-4">
                                <p class="text-[#090E39] line-clamp-1 max-w-xs font-bold">{{ $reg->event->title }}</p>
                                <p class="text-[11px] text-[#090E39]/60 font-semibold inline-flex items-center gap-1 mt-0.5">
                                    {!! $icon('calendar', 'w-3 h-3 text-[#4382DF]') !!}
                                    {{ $reg->event->start_date->format('d M Y') }}
                                </p>
                            </td>

                            {{-- Tiket --}}
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center gap-1 bg-[#4647AE]/10 text-[#4647AE] border border-[#4647AE]/30 px-2 py-0.5 rounded-lg text-xs font-extrabold">
                                    {!! $icon('tag', 'w-3 h-3') !!}
                                    {{ $reg->ticketCategory->name }}
                                </span>
                            </td>

                            {{-- Harga --}}
                            <td class="px-4 py-4 text-right font-extrabold text-[#090E39]">
                                @if ($reg->ticketCategory->price == 0)
                                    <span class="inline-flex items-center gap-1 text-emerald-700 bg-emerald-50 border border-emerald-300 px-2 py-0.5 rounded-lg text-xs font-extrabold">
                                        {!! $icon('gift', 'w-3 h-3') !!}
                                        GRATIS
                                    </span>
                                @else
                                    <span class="font-mono text-sm">Rp {{ number_format($reg->ticketCategory->price, 0, ',', '.') }}</span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center gap-1.5 {{ $statusMeta['bg'] }} {{ $statusMeta['text'] }} {{ $statusMeta['border'] }} border-2 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest">
                                    {!! $icon($statusMeta['icon'], 'w-3 h-3') !!}
                                    {{ $statusMeta['label'] }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-4 text-right">
                                @if ($reg->payment_status === 'pending')
                                    <div class="inline-flex flex-wrap justify-end gap-2">
                                        <form method="POST" action="{{ route('admin.registrations.approve', $reg) }}"
                                              onsubmit="return confirm('Verifikasi registrasi {{ $reg->order_code }}?');"
                                              class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="btn-3d inline-flex items-center gap-1 bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1.5 rounded-lg font-extrabold text-xs border-2 border-[#090E39]">
                                                {!! $icon('check', 'w-3 h-3') !!}
                                                Approve
                                            </button>
                                        </form>

                                        <a href="{{ route('admin.registrations.show', $reg) }}"
                                           class="inline-flex items-center gap-1 bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1.5 rounded-lg font-extrabold text-xs border-2 border-red-400 transition">
                                            {!! $icon('eye', 'w-3 h-3') !!}
                                            Review
                                        </a>
                                    </div>
                                @else
                                    <a href="{{ route('admin.registrations.show', $reg) }}"
                                       class="inline-flex items-center gap-1 text-[#4647AE] hover:text-[#4382DF] font-extrabold text-xs transition group">
                                        Detail
                                        <span class="inline-flex group-hover:translate-x-0.5 transition-transform">
                                            {!! $icon('arrow-right', 'w-3 h-3') !!}
                                        </span>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-16 text-center">
                                <div class="mx-auto mb-3 w-16 h-16 grid place-items-center rounded-2xl bg-[#4382DF]/10 text-[#4382DF] border-2 border-[#4382DF]/30">
                                    {!! $icon('inbox', 'w-8 h-8') !!}
                                </div>
                                <p class="font-display font-extrabold text-[#090E39] text-lg">Belum ada registrasi</p>
                                <p class="text-sm text-[#090E39]/60 mt-1 font-semibold">Coba ubah filter atau tambahkan event baru</p>
                                @if (request()->hasAny(['search', 'status', 'event_id']))
                                    <a href="{{ route('admin.registrations.index') }}"
                                       class="inline-flex items-center gap-1.5 mt-4 bg-[#4382DF] hover:bg-[#4647AE] text-white px-4 py-2 rounded-xl font-extrabold text-sm border-2 border-[#090E39] shadow-[3px_3px_0_#090E39] transition">
                                        Reset filter
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ============ PAGINATION ============ --}}
    @if ($registrations->hasPages())
        <div class="mt-5">
            {{ $registrations->links() }}
        </div>
    @endif

</div>

@endsection