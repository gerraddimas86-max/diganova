@extends('admin.layouts.app')

@section('title', 'Check-in: ' . $event->title . ' — Admin')

@section('content')

@php
    /* ====== Icon library ====== */
    $icons = [
        'arrow-left'      => '<path d="m12 19-7-7 7-7M5 12h14"/>',
        'scan'            => '<path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/><path d="M7 12h10"/>',
        'camera'          => '<path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/>',
        'video'           => '<path d="m22 8-6 4 6 4V8Z"/><rect x="2" y="6" width="14" height="12" rx="2"/>',
        'video-off'       => '<path d="M10.66 6H14a2 2 0 0 1 2 2v2.5l5.248-3.062A.5.5 0 0 1 22 7.87v8.196"/><path d="M16 16a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h2"/><path d="m2 2 20 20"/>',
        'stop-circle'     => '<circle cx="12" cy="12" r="10"/><rect x="9" y="9" width="6" height="6" rx="1"/>',
        'keyboard'        => '<rect x="2" y="6" width="20" height="12" rx="2"/><path d="M6 10h.01M10 10h.01M14 10h.01M18 10h.01M6 14h.01M18 14h.01M10 14h4"/>',
        'check-circle'    => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/>',
        'x-circle'        => '<circle cx="12" cy="12" r="10"/><path d="m15 9-6 6M9 9l6 6"/>',
        'clipboard-list'  => '<rect x="8" y="2" width="8" height="4" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4M12 16h4M8 11h.01M8 16h.01"/>',
        'info'            => '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>',
        'calendar'        => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
        'map-pin'         => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
        'activity'        => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>',
        'ticket'          => '<path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><path d="M13 5v2M13 17v2M13 11v2"/>',
        'external-link'   => '<path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>',
        'users'           => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',
        'zap'             => '<path d="M13 2 3 14h9l-1 8 10-12h-9z"/>',
        'bell'            => '<path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>',
        'list-ordered'    => '<path d="M10 12h11M10 18h11M10 6h11M4 10h2M4 6h1v4M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"/>',
        'arrow-right'     => '<path d="M5 12h14M12 5l7 7-7 7"/>',
    ];
    $icon = fn (string $name, string $class = 'w-5 h-5') =>
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';

    $statusMeta = match($event->status) {
        'published' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'border' => 'border-emerald-400', 'icon' => 'check-circle', 'label' => 'Published'],
        'ongoing'   => ['bg' => 'bg-[#4382DF]/15','text' => 'text-[#112E81]',   'border' => 'border-[#4382DF]',   'icon' => 'zap',          'label' => 'Ongoing'],
        'completed' => ['bg' => 'bg-slate-100',   'text' => 'text-slate-800',   'border' => 'border-slate-400',   'icon' => 'check-circle', 'label' => 'Completed'],
        'cancelled' => ['bg' => 'bg-red-100',     'text' => 'text-red-800',     'border' => 'border-red-400',     'icon' => 'x-circle',     'label' => 'Cancelled'],
        default     => ['bg' => 'bg-slate-100',   'text' => 'text-slate-800',   'border' => 'border-slate-400',   'icon' => 'activity',     'label' => ucfirst($event->status)],
    };
@endphp

<style>
    /* Matikan View Transitions khusus halaman ini agar kamera tidak crash */
    @view-transition { navigation: none !important; }

    :where(#admin-checkin-scanner) { --c-indigo: #4647AE; --c-blue: #4382DF; --c-navy: #090E39; }
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
    .btn-3d:hover:not(:disabled) {
        transform: translate(2px, 2px);
        box-shadow: 1px 1px 0 var(--ec);
    }
    .btn-3d:active:not(:disabled) {
        transform: translate(3px, 3px);
        box-shadow: 0 0 0 var(--ec);
    }
    .btn-3d:disabled {
        opacity: .5;
        cursor: not-allowed;
    }

    /* Brutal input */
    .input-brutal {
        background: #F5F7FF;
        color: #090E39;
        border: 2px solid rgba(9,14,57,.15);
        border-radius: .75rem;
        padding: .75rem 1rem;
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

    /* QR Reader area */
    #qr-reader {
        min-height: 300px;
        border-radius: 1rem !important;
        overflow: hidden;
        background: #F5F7FF;
        border: 2px dashed rgba(9,14,57,.25);
        transition: border-color .2s, background .2s;
    }
    #qr-reader.ready {
        border: 2px solid #4382DF;
        background: #fff;
        box-shadow: 3px 3px 0 #4382DF;
    }
    
    /* PERBAIKAN: Paksa un-mirror video preview */
    #qr-reader video {
        border-radius: 1rem;
        width: 100% !important;
        height: auto !important;
        /* Memutar balik gambar yang ter-mirror secara default */
        transform: scaleX(-1) !important;
        -webkit-transform: scaleX(-1) !important;
        -moz-transform: scaleX(-1) !important;
        -ms-transform: scaleX(-1) !important;
    }
    
    #qr-reader__scan_region { min-height: 260px; }
    #qr-reader__dashboard { display: none; }

    /* Result overlay animation */
    @keyframes pop-in {
        0%   { opacity: 0; transform: scale(.85) translateY(20px); }
        60%  { transform: scale(1.03) translateY(0); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    .overlay-pop { animation: pop-in .35s cubic-bezier(.2,.9,.3,1.3) both; }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
    }
</style>

<div id="admin-checkin-scanner" x-data="scannerApp()" x-cloak>

    {{-- ============ HEADER ============ --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">

        <div class="min-w-0">
            <a href="{{ route('admin.checkin.index') }}"
               class="inline-flex items-center gap-1.5 text-sm font-extrabold text-[#4647AE] hover:text-[#4382DF] transition group">
                <span class="w-7 h-7 grid place-items-center rounded-lg bg-white border-2 border-[#4647AE] group-hover:bg-[#4382DF] group-hover:text-white group-hover:border-[#4382DF] transition">
                    {!! $icon('arrow-left', 'w-3.5 h-3.5') !!}
                </span>
                Pilih Event Lain
            </a>

            <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-[#090E39] mt-3 inline-flex items-start gap-2">
                <span class="shrink-0 w-9 h-9 grid place-items-center rounded-xl bg-[#4382DF] text-white border-2 border-[#090E39] shadow-[2px_2px_0_#090E39] mt-0.5">
                    {!! $icon('scan', 'w-5 h-5') !!}
                </span>
                <span class="min-w-0">
                    Check-in Scanner
                    <span class="block text-sm sm:text-base text-[#090E39]/60 font-bold mt-1 truncate">
                        {{ $event->title }}
                    </span>
                </span>
            </h1>
        </div>

        {{-- Progress Card --}}
        <div class="brutal-card px-5 py-4 sm:min-w-[14rem]">
            <p class="inline-flex items-center gap-1.5 text-[10px] text-[#090E39]/55 uppercase tracking-widest font-extrabold">
                {!! $icon('activity', 'w-3 h-3 text-[#4382DF]') !!}
                Progress
            </p>
            <p class="font-display text-2xl font-extrabold text-[#090E39] mt-1">
                <span class="text-[#4382DF]" x-text="stats.checked_in">{{ $stats['checked_in'] }}</span>
                <span class="text-[#090E39]/30">/</span>
                <span x-text="stats.total_verified">{{ $stats['total_verified'] }}</span>
            </p>
            <p class="inline-flex items-center gap-1 text-[11px] text-[#090E39]/60 font-bold mt-0.5">
                {!! $icon('users', 'w-3 h-3') !!}
                peserta hadir
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ==================================================
             LEFT: Scanner Area
             ================================================== --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- ============ CAMERA AREA ============ --}}
            <div class="brutal-card p-5 sm:p-6">
                <h2 class="font-display font-extrabold text-lg text-[#090E39] mb-4 inline-flex items-center gap-2">
                    <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-white border-2 border-[#090E39]">
                        {!! $icon('camera', 'w-4 h-4') !!}
                    </span>
                    Scan QR Code
                </h2>

                {{-- Camera viewport --}}
                <div id="qr-reader"
                     class="mx-auto max-w-[420px] flex items-center justify-center"
                     :class="scanning ? 'ready' : ''"
                     style="min-height: 300px;">
                </div>

                {{-- Buttons --}}
                <div class="mt-4 flex gap-2 justify-center flex-wrap">
                    <button type="button" @click="startScan()"
                            x-show="!scanning"
                            class="btn-3d inline-flex items-center gap-2 bg-[#4647AE] hover:bg-[#4382DF] text-white px-6 py-2.5 rounded-xl font-extrabold text-sm border-2 border-[#090E39]">
                        {!! $icon('camera', 'w-4 h-4') !!}
                        Mulai Scan
                    </button>
                    <button type="button" @click="stopScan()"
                            x-show="scanning"
                            class="btn-3d inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white px-6 py-2.5 rounded-xl font-extrabold text-sm border-2 border-[#090E39]">
                        {!! $icon('stop-circle', 'w-4 h-4') !!}
                        Stop Scan
                    </button>
                </div>

                {{-- Status messages --}}
                <p class="inline-flex items-center justify-center gap-1.5 text-xs text-center text-[#090E39]/55 font-semibold mt-3 w-full" x-show="!scanning">
                    {!! $icon('info', 'w-3.5 h-3.5') !!}
                    Klik "Mulai Scan" dan izinkan akses kamera browser
                </p>
                <p class="inline-flex items-center justify-center gap-1.5 text-xs text-center text-emerald-700 font-extrabold mt-3 w-full" x-show="scanning">
                    {!! $icon('video', 'w-3.5 h-3.5') !!}
                    Kamera aktif — arahkan QR ke kotak kamera
                </p>
            </div>

            {{-- ============ MANUAL INPUT ============ --}}
            <div class="brutal-card p-5 sm:p-6">
                <h2 class="font-display font-extrabold text-lg text-[#090E39] mb-2 inline-flex items-center gap-2">
                    <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-white border-2 border-[#090E39]">
                        {!! $icon('keyboard', 'w-4 h-4') !!}
                    </span>
                    Input Manual
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#090E39]/50 bg-slate-100 border border-[#090E39]/15 rounded-full px-2 py-0.5 ml-1">
                        Fallback
                    </span>
                </h2>
                <p class="text-xs text-[#090E39]/55 font-semibold mb-3">
                    Gunakan jika QR tidak terbaca (layar redup / QR rusak).
                </p>

                <form @submit.prevent="submitCode(manualCode, 'manual')" class="flex flex-col sm:flex-row gap-2">
                    <input type="text" x-model="manualCode"
                           placeholder="Ketik kode E-Ticket (contoh: A1B2C3D4...)"
                           autocomplete="off"
                           class="input-brutal flex-1 font-mono uppercase tracking-wider">
                    <button type="submit" :disabled="processing || !manualCode.trim()"
                            class="btn-3d inline-flex items-center justify-center gap-2 bg-[#090E39] hover:bg-[#4647AE] text-white px-6 py-2.5 rounded-xl font-extrabold text-sm border-2 border-[#090E39]">
                        <span x-show="!processing" class="inline-flex items-center gap-2">
                            {!! $icon('check-circle', 'w-4 h-4') !!}
                            Proses
                        </span>
                        <span x-show="processing" class="inline-flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 12a9 9 0 1 1-6.219-8.56" stroke-linecap="round"/></svg>
                            Proses...
                        </span>
                    </button>
                </form>
            </div>

            {{-- ============ RECENT CHECK-INS ============ --}}
            <div class="brutal-card p-5 sm:p-6">
                <h2 class="font-display font-extrabold text-lg text-[#090E39] mb-3 inline-flex items-center gap-2">
                    <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-white border-2 border-[#090E39]">
                        {!! $icon('check-circle', 'w-4 h-4') !!}
                    </span>
                    Check-in Terbaru
                </h2>

                <ul class="space-y-2 text-sm">
                    <template x-for="(c, i) in recentCheckins" :key="i">
                        <li class="flex items-center gap-3 border-2 border-[#090E39]/10 rounded-xl p-2.5 hover:border-[#4382DF]/40 transition-colors">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#4647AE] to-[#4382DF] flex items-center justify-center text-white text-xs font-extrabold border-2 border-[#090E39] shrink-0"
                                 x-text="c.avatar_initial"></div>
                            <div class="flex-1 min-w-0">
                                <p class="font-extrabold text-[#090E39] text-sm truncate" x-text="c.name"></p>
                                <p class="text-xs text-[#090E39]/55 font-semibold truncate inline-flex items-center gap-1">
                                    {!! $icon('ticket', 'w-2.5 h-2.5 text-[#4382DF]') !!}
                                    <span x-text="c.ticket"></span>
                                </p>
                            </div>
                            <span class="text-xs text-[#090E39]/45 font-bold whitespace-nowrap" x-text="c.checked_in_at"></span>
                        </li>
                    </template>

                    <li x-show="recentCheckins.length === 0" class="text-center py-8">
                        <div class="mx-auto mb-2 w-12 h-12 grid place-items-center rounded-xl bg-[#4382DF]/10 text-[#4382DF] border-2 border-[#4382DF]/30">
                            {!! $icon('users', 'w-6 h-6') !!}
                        </div>
                        <p class="text-xs text-[#090E39]/50 font-bold">Belum ada check-in</p>
                    </li>
                </ul>
            </div>
        </div>

        {{-- ==================================================
             RIGHT: Sidebar
             ================================================== --}}
        <div class="space-y-5">

            {{-- ============ PETUNJUK ============ --}}
            <div class="bg-[#4382DF]/10 border-2 border-[#4382DF] rounded-2xl shadow-[4px_4px_0_#112E81] p-5">
                <h3 class="font-display font-extrabold text-[#112E81] text-base mb-3 inline-flex items-center gap-2">
                    <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-white border-2 border-[#090E39]">
                        {!! $icon('list-ordered', 'w-4 h-4') !!}
                    </span>
                    Petunjuk
                </h3>

                <ol class="space-y-2.5 text-xs text-[#112E81]/85 font-semibold counter-reset-list">
                    <li class="flex items-start gap-2">
                        <span class="shrink-0 w-5 h-5 grid place-items-center rounded-full bg-[#4382DF] text-white text-[10px] font-extrabold border border-[#090E39]">1</span>
                        <span>Klik <strong>Mulai Scan</strong> — izinkan akses kamera</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="shrink-0 w-5 h-5 grid place-items-center rounded-full bg-[#4382DF] text-white text-[10px] font-extrabold border border-[#090E39]">2</span>
                        <span>Arahkan QR e-ticket peserta ke kotak</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="shrink-0 w-5 h-5 grid place-items-center rounded-full bg-[#4382DF] text-white text-[10px] font-extrabold border border-[#090E39]">3</span>
                        <span>Ada <strong>beep suara</strong> & overlay saat berhasil</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="shrink-0 w-5 h-5 grid place-items-center rounded-full bg-[#4382DF] text-white text-[10px] font-extrabold border border-[#090E39]">4</span>
                        <span>Jika QR rusak, pakai <strong>input manual</strong></span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="shrink-0 w-5 h-5 grid place-items-center rounded-full bg-[#4382DF] text-white text-[10px] font-extrabold border border-[#090E39]">5</span>
                        <span>Scan berikutnya bisa langsung setelah 3 detik</span>
                    </li>
                </ol>
            </div>

            {{-- ============ INFO EVENT ============ --}}
            <div class="brutal-card p-5">
                <h3 class="font-display font-extrabold text-base text-[#090E39] mb-3 inline-flex items-center gap-2">
                    <span class="w-8 h-8 grid place-items-center rounded-lg bg-[#4382DF] text-white border-2 border-[#090E39]">
                        {!! $icon('info', 'w-4 h-4') !!}
                    </span>
                    Info Event
                </h3>

                <dl class="space-y-2.5 text-xs">
                    <div class="flex justify-between items-start gap-2">
                        <dt class="inline-flex items-center gap-1 text-[#090E39]/55 font-extrabold uppercase tracking-widest text-[10px]">
                            {!! $icon('ticket', 'w-3 h-3 text-[#4382DF]') !!}
                            Judul
                        </dt>
                        <dd class="font-extrabold text-[#090E39] text-right max-w-[60%]">{{ $event->title }}</dd>
                    </div>
                    <div class="flex justify-between items-center gap-2">
                        <dt class="inline-flex items-center gap-1 text-[#090E39]/55 font-extrabold uppercase tracking-widest text-[10px]">
                            {!! $icon('calendar', 'w-3 h-3 text-[#4382DF]') !!}
                            Tanggal
                        </dt>
                        <dd class="font-extrabold text-[#090E39] text-right">{{ $event->start_date->format('d M Y H:i') }}</dd>
                    </div>
                    <div class="flex justify-between items-center gap-2">
                        <dt class="inline-flex items-center gap-1 text-[#090E39]/55 font-extrabold uppercase tracking-widest text-[10px]">
                            {!! $icon('map-pin', 'w-3 h-3 text-[#4382DF]') !!}
                            Lokasi
                        </dt>
                        <dd class="font-extrabold text-[#090E39] text-right max-w-[60%]">{{ $event->location }}</dd>
                    </div>
                    <div class="flex justify-between items-center gap-2">
                        <dt class="inline-flex items-center gap-1 text-[#090E39]/55 font-extrabold uppercase tracking-widest text-[10px]">
                            {!! $icon('activity', 'w-3 h-3 text-[#4382DF]') !!}
                            Status
                        </dt>
                        <dd>
                            <span class="inline-flex items-center gap-1 {{ $statusMeta['bg'] }} {{ $statusMeta['text'] }} {{ $statusMeta['border'] }} border-2 px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-widest">
                                {!! $icon($statusMeta['icon'], 'w-2.5 h-2.5') !!}
                                {{ $statusMeta['label'] }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- ============ LINK DETAIL ============ --}}
            <a href="{{ route('admin.events.show', $event) }}"
               class="brutal-card p-4 flex items-center justify-between gap-2 hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-[2px_2px_0_#090E39] transition-all group">
                <span class="inline-flex items-center gap-2 text-sm font-extrabold text-[#4647AE]">
                    {!! $icon('external-link', 'w-4 h-4') !!}
                    Lihat detail event
                </span>
                <span class="w-8 h-8 grid place-items-center rounded-full bg-[#4382DF]/10 text-[#4382DF] border-2 border-[#4382DF]/30 group-hover:bg-[#4382DF] group-hover:text-white transition-colors">
                    {!! $icon('arrow-right', 'w-4 h-4') !!}
                </span>
            </a>
        </div>
    </div>

    {{-- ============================================================
         RESULT OVERLAY
         ============================================================ --}}
    <div x-show="result" x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-sm"
         :class="result && result.status === 'success' ? 'bg-emerald-500/25' : 'bg-red-500/25'"
         @click="result = null">

        <div class="overlay-pop bg-white border-4 rounded-2xl max-w-sm w-full p-6 text-center shadow-[8px_8px_0_rgba(9,14,57,0.9)]"
             :class="result && result.status === 'success' ? 'border-emerald-500' : 'border-red-500'"
             @click.stop>

            {{-- Status icon --}}
            <div class="mx-auto mb-4 w-20 h-20 grid place-items-center rounded-full border-4"
                 :class="result && result.status === 'success'
                     ? 'bg-emerald-100 text-emerald-600 border-emerald-500'
                     : 'bg-red-100 text-red-600 border-red-500'">
                <template x-if="result && result.status === 'success'">
                    {!! $icon('check-circle', 'w-10 h-10') !!}
                </template>
                <template x-if="result && result.status !== 'success'">
                    {!! $icon('x-circle', 'w-10 h-10') !!}
                </template>
            </div>

            {{-- Title --}}
            <h2 class="font-display text-xl font-extrabold mb-2"
                :class="result && result.status === 'success' ? 'text-emerald-700' : 'text-red-700'"
                x-text="result && result.status === 'success' ? 'Check-in Berhasil!' : 'Gagal Check-in'"></h2>

            {{-- Message --}}
            <p class="text-sm text-[#090E39]/70 mb-4 font-semibold" x-text="result ? result.message : ''"></p>

            {{-- Registration info --}}
            <div x-show="result && result.registration"
                 class="bg-[#F5F7FF] border-2 border-[#090E39]/15 rounded-xl p-3 mb-4 text-left">
                <p class="inline-flex items-center gap-1 text-[10px] text-[#090E39]/55 uppercase tracking-widest font-extrabold mb-1">
                    {!! $icon('users', 'w-3 h-3 text-[#4382DF]') !!}
                    Peserta
                </p>
                <p class="font-display font-extrabold text-[#090E39] text-base"
                   x-text="result && result.registration ? result.registration.name : ''"></p>
                <p class="inline-flex items-center gap-1 text-xs text-[#090E39]/60 font-semibold mt-1"
                   x-show="result && result.registration">
                    {!! $icon('ticket', 'w-3 h-3 text-[#4382DF]') !!}
                    <span x-text="result && result.registration ? result.registration.ticket : ''"></span>
                </p>
            </div>

            {{-- Close button --}}
            <button type="button" @click="result = null"
                    class="btn-3d inline-flex items-center justify-center gap-2 bg-[#090E39] hover:bg-[#4647AE] text-white px-6 py-2.5 rounded-xl font-extrabold text-sm border-2 border-[#090E39]">
                {!! $icon('check-circle', 'w-4 h-4') !!}
                Tutup
            </button>
        </div>
    </div>

</div>

<style>
    [x-cloak] { display: none !important; }
</style>

{{-- ============================================================
     LIBRARIES & ALPINE LOGIC
     ============================================================ --}}
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
function scannerApp() {
    return {
        scanning: false,
        html5QrCode: null,
        result: null,
        processing: false,
        manualCode: '',
        stats: {
            checked_in: {{ $stats['checked_in'] }},
            total_verified: {{ $stats['total_verified'] }},
        },
        recentCheckins: @json($recentCheckins),

        async startScan() {
            if (this.html5QrCode) return;

            await this.$nextTick();

            try {
                // Cukup gunakan facingMode environment (lebih stabil)
                this.html5QrCode = new Html5Qrcode("qr-reader");
                
                await this.html5QrCode.start(
                    { facingMode: "environment" },
                    { fps: 10, qrbox: { width: 250, height: 250 } },
                    (decodedText) => this.submitCode(decodedText, 'qr'),
                    () => {} 
                );
                
                this.scanning = true;

                // Cadangan JS jika CSS tidak bekerja
                this.$nextTick(() => {
                    const video = document.querySelector('#qr-reader video');
                    if (video) {
                        video.style.transform = 'scaleX(-1)';
                        video.style.webkitTransform = 'scaleX(-1)';
                    }
                });

            } catch (err) {
                let errorMsg = err.toString();
                if (errorMsg.includes('NotAllowedError')) {
                    errorMsg = 'Akses kamera ditolak. Pastikan kamu mengizinkan kamera di browser, dan gunakan HTTPS atau localhost.';
                } else if (errorMsg.includes('NotFoundError')) {
                    errorMsg = 'Kamera tidak ditemukan di perangkat ini.';
                } else if (errorMsg.includes('NotReadableError')) {
                    errorMsg = 'Kamera sedang dipakai aplikasi lain. Tutup aplikasi tersebut dan coba lagi.';
                }
                
                alert('Gagal menyalakan kamera:\n\n' + errorMsg);
                this.html5QrCode = null;
                this.scanning = false;
            }
        },

        async stopScan() {
            if (!this.html5QrCode) return;
            
            try {
                // Beri jeda sebelum stop untuk memastikan stream stabil
                await new Promise(resolve => setTimeout(resolve, 300));
                await this.html5QrCode.stop();
                this.html5QrCode.clear();
            } catch (e) {
                console.error(e);
            } finally {
                this.html5QrCode = null;
                this.scanning = false;
            }
        },

        async submitCode(code, method = 'qr') {
            code = (code || '').trim().toUpperCase();
            if (!code || this.processing) return;

            this.processing = true;

            try {
                const response = await fetch('{{ route('admin.checkin.process', $event) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ e_ticket_code: code, method: method }),
                });

                const data = await response.json();
                this.result = data;
                this.playBeep(data.status === 'success');

                if (data.status === 'success') {
                    this.stats.checked_in++;
                    if (data.registration) {
                        data.registration.checked_in_at = new Date().toLocaleTimeString('id-ID', {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                        this.recentCheckins.unshift(data.registration);
                        if (this.recentCheckins.length > 5) this.recentCheckins.pop();
                    }
                    this.manualCode = '';
                }

                setTimeout(() => { this.result = null; }, 3500);

            } catch (err) {
                this.result = {
                    status: 'error',
                    message: 'Gagal terhubung ke server. Coba lagi.',
                };
                this.playBeep(false);
            } finally {
                this.processing = false;
            }
        },

        playBeep(success) {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.frequency.value = success ? 880 : 250;
                osc.type = 'sine';
                gain.gain.setValueAtTime(0.3, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.25);
                osc.start();
                osc.stop(ctx.currentTime + 0.25);
            } catch (e) {}
        }
    };
}
</script>

@endsection