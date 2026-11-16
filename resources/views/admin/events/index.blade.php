@extends('admin.layouts.app')

@section('title', 'Manage Events — Admin')

@section('content')

@php
    /* ====== Icon library ====== */
    $icons = [
        'plus'          => '<path d="M5 12h14M12 5v14"/>',
        'search'        => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>',
        'filter'        => '<path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z"/>',
        'calendar'      => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
        'map-pin'       => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
        'tag'           => '<path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"/><circle cx="7.5" cy="7.5" r=".5" fill="currentColor"/>',
        'activity'      => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>',
        'edit'          => '<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4Z"/>',
        'trash'         => '<path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>',
        'eye'           => '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>',
        'inbox'         => '<polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>',
        'arrow-right'   => '<path d="M5 12h14M12 5l7 7-7 7"/>',
        'ticket'        => '<path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><path d="M13 5v2M13 17v2M13 11v2"/>',
        'zap'           => '<path d="M13 2 3 14h9l-1 8 10-12h-9z"/>',
    ];
    $icon = fn (string $name, string $class = 'w-5 h-5') =>
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';

    $statusMeta = fn (string $status) => match($status) {
        'published' => [
            'bg'     => 'bg-emerald-100',
            'text'   => 'text-emerald-800',
            'border' => 'border-emerald-400',
            'icon'   => 'check-circle',
            'label'  => 'Published',
        ],
        'draft'     => [
            'bg'     => 'bg-slate-100',
            'text'   => 'text-slate-800',
            'border' => 'border-slate-400',
            'icon'   => 'edit',
            'label'  => 'Draft',
        ],
        'ongoing'   => [
            'bg'     => 'bg-[#4382DF]/15',
            'text'   => 'text-[#112E81]',
            'border' => 'border-[#4382DF]',
            'icon'   => 'zap',
            'label'  => 'Ongoing',
        ],
        'completed' => [
            'bg'     => 'bg-violet-100',
            'text'   => 'text-violet-800',
            'border' => 'border-violet-400',
            'icon'   => 'check-circle',
            'label'  => 'Completed',
        ],
        'cancelled' => [
            'bg'     => 'bg-red-100',
            'text'   => 'text-red-800',
            'border' => 'border-red-400',
            'icon'   => 'x-circle',
            'label'  => 'Cancelled',
        ],
        default     => [
            'bg'     => 'bg-slate-100',
            'text'   => 'text-slate-800',
            'border' => 'border-slate-400',
            'icon'   => 'activity',
            'label'  => ucfirst($status),
        ],
    };
@endphp

<style>
    :where(#admin-events) { --c-indigo: #4647AE; --c-blue: #4382DF; --c-navy: #090E39; }
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

    /* Table hover */
    tbody tr { transition: background .15s; }
    tbody tr:hover { background: #F5F7FF; }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after { transition-duration: .01ms !important; }
    }
</style>

<div id="admin-events">

    {{-- ============ HEADER ============ --}}
    <div class="flex flex-wrap justify-between items-start gap-3 mb-6">
        <div>
            <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-[#090E39] flex items-center gap-2">
                <span class="w-9 h-9 grid place-items-center rounded-xl bg-[#4382DF] text-white border-2 border-[#090E39] shadow-[2px_2px_0_#090E39]">
                    {!! $icon('calendar', 'w-5 h-5') !!}
                </span>
                Manage Events
            </h1>
            <p class="text-sm text-[#090E39]/60 mt-2 inline-flex items-center gap-1.5 font-semibold">
                {!! $icon('ticket', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                Total: <b class="text-[#090E39]">{{ $events->total() }}</b> event
            </p>
        </div>

        <a href="{{ route('admin.events.create') }}"
           class="btn-3d inline-flex items-center gap-2 bg-[#4647AE] text-white px-5 py-2.5 rounded-xl font-extrabold text-sm border-2 border-[#090E39] hover:bg-[#4382DF]">
            {!! $icon('plus', 'w-4 h-4') !!}
            Buat Event Baru
        </a>
    </div>

    {{-- ============ FILTER BAR ============ --}}
    <form method="GET" class="brutal-card p-4 mb-6 flex flex-col sm:flex-row gap-3">

        {{-- Search --}}
        <div class="relative flex-1">
            <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-[#4382DF]">
                {!! $icon('search', 'w-4 h-4') !!}
            </span>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari event..."
                   class="input-brutal has-left-icon">
        </div>

        {{-- Status --}}
        <select name="status" class="input-brutal sm:w-44">
            <option value="">Semua Status</option>
            <option value="draft" @selected(request('status') === 'draft')>Draft</option>
            <option value="published" @selected(request('status') === 'published')>Published</option>
            <option value="ongoing" @selected(request('status') === 'ongoing')>Ongoing</option>
            <option value="completed" @selected(request('status') === 'completed')>Completed</option>
            <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
        </select>

        {{-- Filter button --}}
        <button type="submit"
                class="btn-3d inline-flex items-center justify-center gap-2 bg-[#090E39] text-white px-5 py-2.5 rounded-xl font-extrabold text-sm border-2 border-[#090E39]">
            {!! $icon('filter', 'w-4 h-4') !!}
            Filter
        </button>
    </form>

    {{-- ============ TABLE ============ --}}
    <div class="brutal-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#090E39] text-white">
                    <tr>
                        <th class="text-left px-4 py-3.5 font-extrabold text-xs uppercase tracking-widest">
                            <span class="inline-flex items-center gap-1.5">{!! $icon('ticket', 'w-3.5 h-3.5 text-[#4382DF]') !!} Event</span>
                        </th>
                        <th class="text-left px-4 py-3.5 font-extrabold text-xs uppercase tracking-widest">
                            <span class="inline-flex items-center gap-1.5">{!! $icon('tag', 'w-3.5 h-3.5 text-[#4382DF]') !!} Kategori</span>
                        </th>
                        <th class="text-left px-4 py-3.5 font-extrabold text-xs uppercase tracking-widest">
                            <span class="inline-flex items-center gap-1.5">{!! $icon('calendar', 'w-3.5 h-3.5 text-[#4382DF]') !!} Tanggal</span>
                        </th>
                        <th class="text-left px-4 py-3.5 font-extrabold text-xs uppercase tracking-widest">
                            <span class="inline-flex items-center gap-1.5">{!! $icon('activity', 'w-3.5 h-3.5 text-[#4382DF]') !!} Status</span>
                        </th>
                        <th class="text-right px-4 py-3.5 font-extrabold text-xs uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-dashed divide-[#090E39]/10">
                    @forelse ($events as $event)
                        @php $meta = $statusMeta($event->status); @endphp

                        <tr class="align-top">
                            {{-- Event --}}
                            <td class="px-4 py-4">
                                <div class="font-display font-extrabold text-[#090E39] text-sm leading-snug">{{ $event->title }}</div>
                                <div class="text-xs text-[#090E39]/60 font-semibold mt-1 inline-flex items-center gap-1">
                                    {!! $icon('map-pin', 'w-3 h-3 text-[#4382DF]') !!}
                                    {{ $event->location }}
                                </div>
                            </td>

                            {{-- Kategori --}}
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center gap-1 bg-[#4647AE]/10 text-[#4647AE] border border-[#4647AE]/30 px-2 py-0.5 rounded-lg text-xs font-extrabold capitalize">
                                    {!! $icon('tag', 'w-3 h-3') !!}
                                    {{ $event->category }}
                                </span>
                            </td>

                            {{-- Tanggal --}}
                            <td class="px-4 py-4 text-[#090E39] font-bold whitespace-nowrap">
                                {{ $event->start_date->format('d M Y') }}
                            </td>

                            {{-- Status --}}
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center gap-1.5 {{ $meta['bg'] }} {{ $meta['text'] }} {{ $meta['border'] }} border-2 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest">
                                    {!! $icon($meta['icon'], 'w-3 h-3') !!}
                                    {{ $meta['label'] }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-4 text-right">
                                <div class="inline-flex flex-wrap justify-end gap-2">
                                    <a href="{{ route('admin.events.show', $event) }}"
                                       class="inline-flex items-center gap-1 bg-[#4382DF]/10 hover:bg-[#4382DF] text-[#112E81] hover:text-white border-2 border-[#4382DF] px-2.5 py-1 rounded-lg text-xs font-extrabold transition-colors">
                                        {!! $icon('eye', 'w-3 h-3') !!}
                                        Detail
                                    </a>

                                    <a href="{{ route('admin.events.edit', $event) }}"
                                       class="inline-flex items-center gap-1 bg-[#4647AE]/10 hover:bg-[#4647AE] text-[#4647AE] hover:text-white border-2 border-[#4647AE] px-2.5 py-1 rounded-lg text-xs font-extrabold transition-colors">
                                        {!! $icon('edit', 'w-3 h-3') !!}
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('admin.events.destroy', $event) }}"
                                          onsubmit="return confirm('Yakin hapus event ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 bg-red-50 hover:bg-red-500 text-red-700 hover:text-white border-2 border-red-400 px-2.5 py-1 rounded-lg text-xs font-extrabold transition-colors">
                                            {!! $icon('trash', 'w-3 h-3') !!}
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-16 text-center">
                                <div class="mx-auto mb-3 w-16 h-16 grid place-items-center rounded-2xl bg-[#4382DF]/10 text-[#4382DF] border-2 border-[#4382DF]/30">
                                    {!! $icon('inbox', 'w-8 h-8') !!}
                                </div>
                                <p class="font-display font-extrabold text-[#090E39] text-lg">Belum ada event</p>
                                <p class="text-sm text-[#090E39]/60 mt-1 font-semibold">Mulai dengan membuat event pertamamu</p>
                                <a href="{{ route('admin.events.create') }}"
                                   class="inline-flex items-center gap-1.5 mt-4 bg-[#4382DF] hover:bg-[#4647AE] text-white px-4 py-2 rounded-xl font-extrabold text-sm border-2 border-[#090E39] shadow-[3px_3px_0_#090E39] transition">
                                    {!! $icon('plus', 'w-4 h-4') !!}
                                    Buat event pertama
                                    {!! $icon('arrow-right', 'w-3.5 h-3.5') !!}
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ============ PAGINATION ============ --}}
    <div class="mt-5">
        {{ $events->links() }}
    </div>

</div>

@endsection