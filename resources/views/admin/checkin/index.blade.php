@extends('admin.layouts.app')

@section('title', 'Check-in — Admin')

@section('content')

@php
    /* ====== Icon library ====== */
    $icons = [
        'scan'          => '<path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/><path d="M7 12h10"/>',
        'inbox'         => '<polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>',
        'plus'          => '<path d="M5 12h14M12 5v14"/>',
        'arrow-right'   => '<path d="M5 12h14M12 5l7 7-7 7"/>',
        'calendar'      => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
        'map-pin'       => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
        'users'         => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',
        'check-circle'  => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/>',
        'activity'      => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>',
        'zap'           => '<path d="M13 2 3 14h9l-1 8 10-12h-9z"/>',
        'x-circle'      => '<circle cx="12" cy="12" r="10"/><path d="m15 9-6 6M9 9l6 6"/>',
        'edit'          => '<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4Z"/>',
    ];
    $icon = fn (string $name, string $class = 'w-5 h-5') =>
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';

    /* Status tone mapping */
    $statusMeta = fn (string $status) => match($status) {
        'published' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'border' => 'border-emerald-400', 'icon' => 'check-circle', 'label' => 'Published'],
        'ongoing'   => ['bg' => 'bg-[#4382DF]/15','text' => 'text-[#112E81]',   'border' => 'border-[#4382DF]',   'icon' => 'zap',          'label' => 'Ongoing'],
        'completed' => ['bg' => 'bg-slate-100',   'text' => 'text-slate-800',   'border' => 'border-slate-400',   'icon' => 'check-circle', 'label' => 'Completed'],
        'cancelled' => ['bg' => 'bg-red-100',     'text' => 'text-red-800',     'border' => 'border-red-400',     'icon' => 'x-circle',     'label' => 'Cancelled'],
        default     => ['bg' => 'bg-slate-100',   'text' => 'text-slate-800',   'border' => 'border-slate-400',   'icon' => 'activity',     'label' => ucfirst($status)],
    };
@endphp

<style>
    :where(#admin-checkin) { --c-indigo: #4647AE; --c-blue: #4382DF; --c-navy: #090E39; }
    .font-display { font-family: 'Bricolage Grotesque', system-ui, sans-serif; }

    /* Hover pop */
    .hov-pop {
        transition: scale .3s cubic-bezier(.3,1.8,.5,1), box-shadow .2s, border-color .2s, transform .3s cubic-bezier(.3,1.8,.5,1);
    }
    .hov-pop:hover { scale: 1.015; }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after { transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }
</style>

<div id="admin-checkin">

    {{-- ============ HEADER ============ --}}
    <div class="mb-6">
        <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-[#090E39] flex items-center gap-2">
            <span class="w-9 h-9 grid place-items-center rounded-xl bg-[#4382DF] text-white border-2 border-[#090E39] shadow-[2px_2px_0_#090E39]">
                {!! $icon('scan', 'w-5 h-5') !!}
            </span>
            Check-in Peserta
        </h1>
        <p class="text-sm text-[#090E39]/60 mt-2 inline-flex items-center gap-1.5 font-semibold">
            {!! $icon('users', 'w-3.5 h-3.5 text-[#4382DF]') !!}
            Pilih event untuk mulai scan QR peserta
        </p>
    </div>

    {{-- ============ EMPTY STATE ============ --}}
    @if ($events->isEmpty())
        <div class="bg-white border-2 border-dashed border-[#090E39]/25 rounded-2xl shadow-[4px_4px_0_#090E39]/10 p-10 sm:p-16 text-center">
            <div class="mx-auto mb-4 w-20 h-20 grid place-items-center rounded-2xl bg-[#4382DF]/10 text-[#4382DF] border-2 border-[#4382DF]/30">
                {!! $icon('inbox', 'w-10 h-10') !!}
            </div>
            <p class="font-display font-extrabold text-[#090E39] text-xl">Belum ada event</p>
            <p class="text-sm text-[#090E39]/60 mt-1 font-semibold max-w-md mx-auto">
                Buat event pertama supaya bisa mulai scan QR peserta
            </p>
            <a href="{{ route('admin.events.create') }}"
               class="inline-flex items-center gap-2 mt-5 bg-[#4382DF] hover:bg-[#4647AE] text-white px-5 py-2.5 rounded-xl font-extrabold text-sm border-2 border-[#090E39] shadow-[3px_3px_0_#090E39] transition">
                {!! $icon('plus', 'w-4 h-4') !!}
                Buat event pertama
                {!! $icon('arrow-right', 'w-3.5 h-3.5') !!}
            </a>
        </div>
    @else
        {{-- ============ EVENT CARDS ============ --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
            @foreach ($events as $event)
                @php
                    $percent = $event->verified_count > 0
                        ? round(($event->checked_in_count / $event->verified_count) * 100)
                        : 0;
                    $meta = $statusMeta($event->status);
                @endphp

                <a href="{{ route('admin.checkin.scanner', $event) }}"
                   class="hov-pop group relative bg-white border-2 border-[#090E39] rounded-2xl shadow-[4px_4px_0_#090E39] p-5 hover:shadow-[2px_2px_0_#090E39] hover:translate-x-[2px] hover:translate-y-[2px] transition-all flex flex-col h-full">

                    {{-- Top row: status + date --}}
                    <div class="flex justify-between items-start gap-2 mb-3">
                        <span class="inline-flex items-center gap-1.5 {{ $meta['bg'] }} {{ $meta['text'] }} {{ $meta['border'] }} border-2 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest">
                            {!! $icon($meta['icon'], 'w-3 h-3') !!}
                            {{ $meta['label'] }}
                        </span>

                        <span class="inline-flex items-center gap-1 text-xs text-[#090E39]/60 font-extrabold">
                            {!! $icon('calendar', 'w-3 h-3 text-[#4382DF]') !!}
                            {{ $event->start_date->format('d M Y') }}
                        </span>
                    </div>

                    {{-- Title --}}
                    <h3 class="font-display font-extrabold text-[#090E39] text-lg leading-snug line-clamp-2 group-hover:text-[#4382DF] transition-colors">
                        {{ $event->title }}
                    </h3>

                    {{-- Location --}}
                    <p class="inline-flex items-center gap-1.5 text-xs text-[#090E39]/60 mt-2 font-semibold min-w-0">
                        {!! $icon('map-pin', 'w-3.5 h-3.5 text-[#4382DF] shrink-0') !!}
                        <span class="truncate">{{ $event->location }}</span>
                    </p>

                    {{-- Progress --}}
                    <div class="mt-4 flex-1 flex flex-col justify-end">
                        <div class="flex justify-between items-center text-xs mb-1.5">
                            <span class="inline-flex items-center gap-1 text-[#090E39]/55 font-extrabold uppercase tracking-widest text-[10px]">
                                {!! $icon('check-circle', 'w-3 h-3 text-[#4382DF]') !!}
                                Check-in Progress
                            </span>
                            <span class="font-display font-extrabold text-[#090E39]">
                                {{ $event->checked_in_count }} / {{ $event->verified_count }}
                            </span>
                        </div>

                        <div class="bg-[#090E39]/10 rounded-full h-2.5 overflow-hidden border border-[#090E39]/15">
                            <div class="h-full rounded-full transition-all
                                        {{ $percent === 100 ? 'bg-emerald-500' : 'bg-[#4382DF]' }}"
                                 style="width: {{ $percent }}%"></div>
                        </div>

                        <p class="mt-1.5 inline-flex items-center gap-1 text-[11px] text-[#090E39]/55 font-extrabold">
                            {!! $icon('activity', 'w-3 h-3') !!}
                            {{ $percent }}% hadir
                        </p>
                    </div>

                    {{-- Footer CTA --}}
                    <div class="mt-4 pt-4 border-t-2 border-dashed border-[#090E39]/10 flex justify-between items-center">
                        <span class="inline-flex items-center gap-1.5 text-xs font-extrabold text-[#4647AE] uppercase tracking-widest">
                            {!! $icon('scan', 'w-3.5 h-3.5') !!}
                            Buka Scanner
                        </span>
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#4382DF]/10 text-[#4382DF] border-2 border-[#4382DF]/30 group-hover:bg-[#4382DF] group-hover:text-white group-hover:border-[#4382DF] group-hover:translate-x-0.5 transition-all">
                            {!! $icon('arrow-right', 'w-4 h-4') !!}
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

</div>

@endsection