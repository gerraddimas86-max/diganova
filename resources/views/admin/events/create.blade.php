@extends('admin.layouts.app')

@section('title', 'Buat Event Baru — Admin')

@section('content')

@php
    /* ====== Icon library ====== */
    $icons = [
        'arrow-left' => '<path d="m12 19-7-7 7-7M5 12h14"/>',
        'plus'       => '<path d="M5 12h14M12 5v14"/>',
        'sparkles'   => '<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/><path d="M20 3v4M22 5h-4M4 17v2M5 18H3"/>',
    ];
    $icon = fn (string $name, string $class = 'w-5 h-5') =>
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';
@endphp

<style>
    :where(#admin-event-create) { --c-indigo: #4647AE; --c-blue: #4382DF; --c-navy: #090E39; }
    .font-display { font-family: 'Bricolage Grotesque', system-ui, sans-serif; }
</style>

<div id="admin-event-create">

    {{-- ============ HEADER ============ --}}
    <div class="mb-6">
        <a href="{{ route('admin.events.index') }}"
           class="inline-flex items-center gap-1.5 text-sm font-extrabold text-[#4647AE] hover:text-[#4382DF] transition group">
            <span class="w-7 h-7 grid place-items-center rounded-lg bg-white border-2 border-[#4647AE] group-hover:bg-[#4382DF] group-hover:text-white group-hover:border-[#4382DF] transition">
                {!! $icon('arrow-left', 'w-3.5 h-3.5') !!}
            </span>
            Kembali ke daftar event
        </a>

        <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-[#090E39] mt-3 inline-flex items-start gap-2">
            <span class="shrink-0 w-9 h-9 grid place-items-center rounded-xl bg-[#4382DF] text-white border-2 border-[#090E39] shadow-[2px_2px_0_#090E39] mt-0.5">
                {!! $icon('plus', 'w-5 h-5') !!}
            </span>
            <span class="min-w-0">
                Buat Event Baru
                <span class="block text-sm sm:text-base text-[#090E39]/60 font-bold mt-1 inline-flex items-center gap-1.5">
                    {!! $icon('sparkles', 'w-4 h-4 text-[#4382DF]') !!}
                    Isi detail event, tambahkan jadwal & tiket
                </span>
            </span>
        </h1>
    </div>

    {{-- ============ FORM PARTIAL ============ --}}
    @include('admin.events._form', [
        'action' => route('admin.events.store'),
        'method' => 'POST',
        'event' => null,
        'submitLabel' => 'Buat Event',
    ])

</div>

@endsection