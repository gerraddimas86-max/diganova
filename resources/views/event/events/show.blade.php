@extends('layouts.public')

@section('title', $event->title . ' — DIGANOVA')

@section('content')

{{-- Hero Banner --}}
<div class="relative h-64 md:h-96 bg-gradient-to-br from-nova-600 via-violet-500 to-crystal-400 overflow-hidden">
    @if ($event->banner_image)
        <img src="{{ Storage::url($event->banner_image) }}"
             class="w-full h-full object-cover" alt="{{ $event->title }}">
    @endif
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
    <div class="absolute inset-0 flex items-end">
        <div class="max-w-7xl mx-auto px-4 w-full pb-8">
            <span class="inline-block bg-white/20 backdrop-blur text-white text-xs px-3 py-1 rounded-full uppercase tracking-wider mb-3 capitalize">
                {{ $event->category }}
            </span>
            <h1 class="text-3xl md:text-5xl font-bold text-white mb-2 drop-shadow-lg">
                {{ $event->title }}
            </h1>
            <div class="flex flex-wrap gap-4 text-white/90 text-sm">
                <span>📍 {{ $event->location }}</span>
                <span>📅 {{ $event->start_date->format('d M Y') }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Breadcrumb --}}
<div class="max-w-7xl mx-auto px-4 pt-4">
    <nav class="text-sm text-gray-500">
        <a href="{{ route('event.events.index') }}" class="hover:text-nova-600">Events</a>
        <span class="mx-2">/</span>
        <span class="text-gray-700">{{ $event->title }}</span>
    </nav>
</div>

<div class="max-w-7xl mx-auto px-4 py-6 grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- MAIN CONTENT --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Info Grid --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Mulai</p>
                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $event->start_date->format('d M Y') }}</p>
                    <p class="text-xs text-gray-500">{{ $event->start_date->format('H:i') }} WIB</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Selesai</p>
                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $event->end_date->format('d M Y') }}</p>
                    <p class="text-xs text-gray-500">{{ $event->end_date->format('H:i') }} WIB</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Peserta</p>
                    <p class="text-sm font-semibold text-gray-900 mt-1">
                        {{ $event->registrations()->count() }}
                        @if ($event->max_participants)
                            / {{ number_format($event->max_participants, 0, ',', '.') }}
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Status</p>
                    <p class="text-sm font-semibold text-gray-900 mt-1 capitalize">{{ $event->status }}</p>
                </div>
            </div>
        </div>

        {{-- Description --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-3">Tentang Event</h2>
            <div class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $event->description }}</div>

            @if ($event->address)
                <div class="mt-6 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Alamat Lengkap</p>
                    <p class="text-sm text-gray-700">{{ $event->address }}</p>
                </div>
            @endif
        </div>

        {{-- Schedules --}}
        @if ($event->schedules->isNotEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Jadwal Acara</h2>
                <div class="space-y-4">
                    @foreach ($event->schedules as $schedule)
                        <div class="flex gap-4 pb-4 border-b border-gray-100 last:border-0 last:pb-0">
                            <div class="flex-shrink-0 w-20">
                                <p class="text-sm font-bold text-nova-600">{{ $schedule->start_time->format('H:i') }}</p>
                                <p class="text-xs text-gray-500">{{ $schedule->start_time->format('d M') }}</p>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900">{{ $schedule->title }}</p>
                                @if ($schedule->description)
                                    <p class="text-xs text-gray-600 mt-1">{{ $schedule->description }}</p>
                                @endif
                                @if ($schedule->location)
                                    <p class="text-xs text-gray-500 mt-1">📍 {{ $schedule->location }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Venue Map (denah) --}}
        @if ($event->venueMaps->isNotEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Denah Venue</h2>
                @foreach ($event->venueMaps as $map)
                    <div class="mb-4 last:mb-0">
                        <img src="{{ Storage::url($map->image_path) }}"
                             class="w-full rounded-lg border border-gray-200" alt="Denah venue">
                        @if ($map->description)
                            <p class="text-xs text-gray-500 mt-2">{{ $map->description }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Sponsors --}}
        @if ($event->sponsors->isNotEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Sponsor & Partner</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach ($event->sponsors as $sponsor)
                        <div class="border border-gray-200 rounded-lg p-3 text-center">
                            @if ($sponsor->logo_path)
                                <img src="{{ Storage::url($sponsor->logo_path) }}"
                                     class="h-12 mx-auto object-contain mb-2" alt="{{ $sponsor->name }}">
                            @endif
                            <p class="text-sm font-medium text-gray-900">{{ $sponsor->name }}</p>
                            <p class="text-xs text-gray-500 capitalize">{{ $sponsor->tier }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    {{-- SIDEBAR --}}
    <div class="space-y-6">

        {{-- Tiket Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 lg:sticky lg:top-20">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Tiket</h2>

            @if ($event->ticketCategories->isEmpty())
                <p class="text-sm text-gray-500 mb-4">Tiket belum tersedia.</p>
            @else
                <div class="space-y-3 mb-4">
                    @foreach ($event->ticketCategories as $ticket)
                        @php $soldOut = $ticket->sold_count >= $ticket->quota; @endphp
                        <div class="border {{ $soldOut ? 'border-gray-200 bg-gray-50' : 'border-gray-200' }} rounded-lg p-3">
                            <div class="flex justify-between items-start gap-2">
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-900 text-sm">{{ $ticket->name }}</p>
                                    @if ($ticket->description)
                                        <p class="text-xs text-gray-500 mt-1">{{ $ticket->description }}</p>
                                    @endif
                                    <p class="text-xs text-gray-500 mt-1">
                                        @if ($soldOut)
                                            <span class="text-red-600 font-medium">Habis</span>
                                        @else
                                            Sisa {{ $ticket->quota - $ticket->sold_count }} dari {{ $ticket->quota }}
                                        @endif
                                    </p>
                                </div>
                                <p class="text-sm font-bold text-nova-600 whitespace-nowrap">
                                    @if ($ticket->price == 0) GRATIS @else Rp {{ number_format($ticket->price, 0, ',', '.') }} @endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- State handling --}}
                @if ($myRegistrations->isNotEmpty())
                    <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-3">
                        <p class="text-sm text-green-800 font-semibold">✅ Kamu sudah terdaftar</p>
                        @foreach ($myRegistrations as $reg)
                            <p class="text-xs text-green-700 mt-1">
                                Kode: <span class="font-mono font-semibold">{{ $reg->order_code }}</span>
                                — Status: <span class="capitalize">{{ $reg->payment_status }}</span>
                            </p>
                        @endforeach
                    </div>
                    <a href="#" class="block w-full bg-gray-100 text-gray-500 text-center px-4 py-2.5 rounded-lg font-medium cursor-not-allowed">
                        Lihat E-Ticket (Step 14)
                    </a>

                @elseif ($event->registration_deadline && $event->registration_deadline->isPast())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-center">
                        <p class="text-sm text-red-800 font-semibold">⏰ Pendaftaran Ditutup</p>
                        <p class="text-xs text-red-600 mt-1">
                            Deadline: {{ $event->registration_deadline->format('d M Y H:i') }}
                        </p>
                    </div>

                @elseif ($event->status === 'completed' || $event->status === 'cancelled')
                    <div class="bg-gray-100 border border-gray-200 rounded-lg p-3 text-center">
                        <p class="text-sm text-gray-700 font-semibold capitalize">Event {{ $event->status }}</p>
                    </div>

                @else
                    @auth
                        <a href="#"
                           onclick="event.preventDefault(); alert('Registrasi akan hadir di Step 12!');"
                           class="block w-full bg-nova-600 hover:bg-nova-700 text-white text-center px-4 py-3 rounded-lg font-semibold transition">
                            🎟️ Daftar Sekarang
                        </a>
                    @else
                        <a href="{{ route('login') }}?redirect={{ urlencode(url()->current()) }}"
                           class="block w-full bg-nova-600 hover:bg-nova-700 text-white text-center px-4 py-3 rounded-lg font-semibold transition">
                            🎟️ Login untuk Daftar
                        </a>
                    @endauth

                    @if ($event->registration_deadline)
                        <p class="text-xs text-center text-gray-500 mt-3">
                            Deadline: <strong>{{ $event->registration_deadline->format('d M Y H:i') }}</strong>
                        </p>
                    @endif
                @endif
            @endif
        </div>

        {{-- Group Info --}}
        @if ($event->group)
            <div class="bg-gradient-to-br from-nova-50 to-crystal-50 rounded-xl border border-nova-100 p-6">
                <h3 class="font-bold text-gray-900 mb-2">💬 Komunitas Event</h3>
                <p class="text-sm text-gray-700 font-medium">{{ $event->group->name }}</p>
                <p class="text-xs text-gray-500 mt-2">
                    Kamu akan otomatis bergabung grup setelah pembayaran terverifikasi.
                </p>
            </div>
        @endif

        {{-- Creator Info --}}
        @if ($event->creator)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Diselenggarakan oleh</p>
                <p class="text-sm font-semibold text-gray-900">{{ $event->creator->name }}</p>
            </div>
        @endif

    </div>
</div>

@endsection