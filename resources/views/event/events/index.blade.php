@extends('layouts.public')

@section('title', 'Events — DIGANOVA')

@section('content')

<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Jelajahi Events</h1>
        <p class="text-gray-600 mt-2">Temukan festival, konser, cosplay, dan turnamen game terbaik</p>
    </div>

    {{-- Filter Bar --}}
    <form method="GET" action="{{ route('event.events.index') }}"
          class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6 flex flex-col sm:flex-row gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari event berdasarkan judul..."
               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nova-500 outline-none">
        <select name="category"
                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nova-500 outline-none">
            <option value="">Semua Kategori</option>
            @foreach (['music' => 'Musik', 'cosplay' => 'Cosplay', 'art' => 'Seni', 'game_tournament' => 'Turnamen Game', 'festival' => 'Festival', 'other' => 'Lainnya'] as $val => $label)
                <option value="{{ $val }}" @selected(request('category') === $val)>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit"
                class="bg-nova-600 hover:bg-nova-700 text-white px-6 py-2 rounded-lg font-medium transition">
            Cari
        </button>
        @if (request()->hasAny(['search', 'category']))
            <a href="{{ route('event.events.index') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium transition text-center">
                Reset
            </a>
        @endif
    </form>

    {{-- Result count --}}
    <p class="text-sm text-gray-500 mb-4">
        Menampilkan {{ $events->count() }} dari {{ $events->total() }} event
    </p>

    {{-- Grid --}}
    @if ($events->isEmpty())
        <div class="bg-white rounded-xl border border-gray-100 p-12 text-center">
            <div class="text-5xl mb-4">🔍</div>
            <p class="text-gray-700 font-medium">Belum ada event dengan filter ini</p>
            @if (request()->hasAny(['search', 'category']))
                <a href="{{ route('event.events.index') }}" class="inline-block mt-4 text-nova-600 hover:underline text-sm">
                    Reset filter & lihat semua event
                </a>
            @endif
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($events as $event)
                @php
                    $minPrice = $event->ticketCategories->min('price');
                    $isAlmostSold = $event->registration_deadline && $event->registration_deadline->diffInDays(now()) <= 7 && $event->registration_deadline->isFuture();
                @endphp

                <a href="{{ route('event.events.show', $event->slug) }}"
                   class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 group flex flex-col">

                    {{-- Banner --}}
                    <div class="h-44 bg-gradient-to-br from-nova-600 via-violet-500 to-crystal-400 relative overflow-hidden">
                        @if ($event->banner_image)
                            <img src="{{ Storage::url($event->banner_image) }}"
                                 class="w-full h-full object-cover" alt="{{ $event->title }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-white text-5xl">
                                {{ match($event->category) {
                                    'music' => '🎵',
                                    'cosplay' => '🎭',
                                    'art' => '🎨',
                                    'game_tournament' => '🎮',
                                    'festival' => '🎉',
                                    default => '✨',
                                } }}
                            </div>
                        @endif
                        <span class="absolute top-2 left-2 text-xs bg-white/95 text-gray-800 px-2.5 py-1 rounded-full capitalize font-medium">
                            {{ $event->category }}
                        </span>
                        @if ($isAlmostSold)
                            <span class="absolute top-2 right-2 text-xs bg-red-500 text-white px-2.5 py-1 rounded-full font-semibold animate-pulse">
                                Segera Tutup
                            </span>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="p-5 flex-1 flex flex-col">
                        <h3 class="font-bold text-gray-900 text-lg group-hover:text-nova-600 transition line-clamp-2">
                            {{ $event->title }}
                        </h3>

                        <div class="mt-2 space-y-1 text-sm text-gray-600">
                            <p class="flex items-center gap-2">
                                <span>📍</span>
                                <span class="truncate">{{ $event->location }}</span>
                            </p>
                            <p class="flex items-center gap-2">
                                <span>📅</span>
                                <span>{{ $event->start_date->format('d M Y') }}</span>
                            </p>
                        </div>

                        <div class="mt-4 pt-3 border-t border-gray-100 flex justify-between items-center mt-auto">
                            <span class="text-xs text-gray-500">
                                {{ $event->ticketCategories->count() }} tiket
                            </span>
                            @if ($minPrice !== null)
                                <span class="text-sm font-bold text-nova-600">
                                    @if ($minPrice == 0)
                                        GRATIS
                                    @else
                                        Mulai Rp {{ number_format($minPrice, 0, ',', '.') }}
                                    @endif
                                </span>
                            @else
                                <span class="text-xs text-gray-500">Tiket belum tersedia</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $events->links() }}
        </div>
    @endif
</div>

@endsection