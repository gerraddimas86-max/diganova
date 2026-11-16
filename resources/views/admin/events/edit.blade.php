@extends('admin.layouts.app')

@section('title', 'Edit Event — Admin')

@section('content')

@php
    /* ====== Icon library ====== */
    $icons = [
        'arrow-left' => '<path d="m12 19-7-7 7-7M5 12h14"/>',
        'edit'       => '<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4Z"/>',
        'calendar'   => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
        'map-pin'    => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
        'activity'   => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>',
        'zap'        => '<path d="M13 2 3 14h9l-1 8 10-12h-9z"/>',
    ];
    $icon = fn (string $name, string $class = 'w-5 h-5') =>
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';

    /* Status meta untuk chip header (optional info) */
    $statusMeta = match($event->status) {
        'published' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'border' => 'border-emerald-400', 'icon' => 'activity', 'label' => 'Published'],
        'draft'     => ['bg' => 'bg-slate-100',   'text' => 'text-slate-800',   'border' => 'border-slate-400',   'icon' => 'edit',     'label' => 'Draft'],
        'ongoing'   => ['bg' => 'bg-[#4382DF]/15','text' => 'text-[#112E81]',   'border' => 'border-[#4382DF]',   'icon' => 'zap',      'label' => 'Ongoing'],
        'completed' => ['bg' => 'bg-violet-100',  'text' => 'text-violet-800',  'border' => 'border-violet-400',  'icon' => 'activity', 'label' => 'Completed'],
        'cancelled' => ['bg' => 'bg-red-100',     'text' => 'text-red-800',     'border' => 'border-red-400',     'icon' => 'x-circle', 'label' => 'Cancelled'],
        default     => ['bg' => 'bg-slate-100',   'text' => 'text-slate-800',   'border' => 'border-slate-400',   'icon' => 'activity', 'label' => ucfirst($event->status)],
    };
@endphp

<style>
    :where(#admin-event-edit) { --c-indigo: #4647AE; --c-blue: #4382DF; --c-navy: #090E39; }
    .font-display { font-family: 'Bricolage Grotesque', system-ui, sans-serif; }
</style>

<div id="admin-event-edit">

    {{-- ============ HEADER ============ --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">

        <div class="min-w-0">
            <a href="{{ route('admin.events.index') }}"
               class="inline-flex items-center gap-1.5 text-sm font-extrabold text-[#4647AE] hover:text-[#4382DF] transition group">
                <span class="w-7 h-7 grid place-items-center rounded-lg bg-white border-2 border-[#4647AE] group-hover:bg-[#4382DF] group-hover:text-white group-hover:border-[#4382DF] transition">
                    {!! $icon('arrow-left', 'w-3.5 h-3.5') !!}
                </span>
                Kembali ke daftar event
            </a>

            <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-[#090E39] mt-3 inline-flex items-start gap-2">
                <span class="shrink-0 w-9 h-9 grid place-items-center rounded-xl bg-[#4382DF] text-white border-2 border-[#090E39] shadow-[2px_2px_0_#090E39] mt-0.5">
                    {!! $icon('edit', 'w-5 h-5') !!}
                </span>
                <span class="min-w-0">
                    Edit Event
                    <span class="block text-sm sm:text-base text-[#090E39]/60 font-bold mt-1 truncate">
                        {{ $event->title }}
                    </span>
                </span>
            </h1>

            {{-- Meta chips (opsional info ringkas) --}}
            <div class="mt-3 flex flex-wrap items-center gap-2 text-xs">
                <span class="inline-flex items-center gap-1.5 bg-[#4382DF]/10 text-[#112E81] border-2 border-[#4382DF]/30 px-2.5 py-1 rounded-full font-extrabold">
                    {!! $icon('calendar', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                    {{ $event->start_date->format('d M Y H:i') }}
                </span>
                <span class="inline-flex items-center gap-1.5 bg-[#4647AE]/10 text-[#112E81] border-2 border-[#4647AE]/30 px-2.5 py-1 rounded-full font-extrabold">
                    {!! $icon('map-pin', 'w-3.5 h-3.5 text-[#4647AE]') !!}
                    {{ $event->location }}
                </span>
                <span class="inline-flex items-center gap-1.5 {{ $statusMeta['bg'] }} {{ $statusMeta['text'] }} {{ $statusMeta['border'] }} border-2 px-2.5 py-1 rounded-full font-extrabold uppercase tracking-widest text-[10px]">
                    {!! $icon($statusMeta['icon'], 'w-3 h-3') !!}
                    {{ $statusMeta['label'] }}
                </span>
            </div>
        </div>
    </div>

    {{-- ============ FORM PARTIAL ============ --}}
    @include('admin.events._form', [
        'action' => route('admin.events.update', $event),
        'method' => 'PUT',
        'event' => $event,
        'submitLabel' => 'Simpan Perubahan',
    ])

</div>

@endsection