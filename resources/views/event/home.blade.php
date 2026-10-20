@extends('layouts.public')

@section('title', 'Event Home — DIGANOVA')

@section('content')

{{-- Hero Section --}}
<div class="relative bg-gradient-to-br from-nova-900 via-violet-800 to-crystal-700 overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24 flex flex-col md:flex-row items-center gap-10">
        <div class="flex-1 text-center md:text-left">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 tracking-tight">
                Halo, {{ auth()->user()->name }}! 👋
            </h1>
            <p class="text-lg text-white/80 max-w-xl mx-auto md:mx-0">
                Siap untuk petualangan berikutnya? Jelajahi festival, turnamen, dan event seru lainnya minggu ini.
            </p>
            
            <div class="mt-8 flex flex-wrap justify-center md:justify-start gap-4">
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl px-6 py-3 flex items-center gap-3">
                    <div class="text-2xl">🎟️</div>
                    <div class="text-left">
                        <p class="text-white font-bold text-xl">{{ $myRegistrationsCount }}</p>
                        <p class="text-white/70 text-xs uppercase tracking-wider">Tiket Saya</p>
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl px-6 py-3 flex items-center gap-3">
                    <div class="text-2xl">🎉</div>
                    <div class="text-left">
                        <p class="text-white font-bold text-xl">{{ $activeEvents }}</p>
                        <p class="text-white/70 text-xs uppercase tracking-wider">Event Aktif</p>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Hero Mascot / Illustration --}}
        <div class="hidden md:block w-64 opacity-90 drop-shadow-2xl">
            <img src="{{ asset('images/navo-mascot.png') }}" alt="Navo" class="w-full hover:scale-105 transition-transform duration-500">
        </div>
    </div>
</div>

{{-- Categories --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-10">
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
        @foreach ($categories as $cat)
            <a href="{{ route('event.events.index') }}?category={{ $cat['slug'] }}" 
               class="bg-white rounded-xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 p-4 flex flex-col items-center justify-center gap-2 border border-gray-100 group">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br {{ $cat['color'] }} flex items-center justify-center text-2xl shadow-inner group-hover:scale-110 transition-transform">
                    {{ $cat['icon'] }}
                </div>
                <span class="font-semibold text-gray-800 text-sm">{{ $cat['name'] }}</span>
            </a>
        @endforeach
    </div>
</div>

{{-- Featured Events --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Event Mendatang</h2>
            <p class="text-gray-500 mt-1">Jangan sampai ketinggalan event paling seru.</p>
        </div>
        <a href="{{ route('event.events.index') }}" class="text-nova-600 font-semibold hover:text-nova-700 flex items-center gap-1">
            Lihat Semua <span>→</span>
        </a>
    </div>

    @if ($featuredEvents->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center shadow-sm">
            <div class="text-5xl mb-4">🏜️</div>
            <h3 class="text-xl font-bold text-gray-900">Belum ada event mendatang</h3>
            <p class="text-gray-500 mt-2">Cek kembali nanti untuk update terbaru!</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($featuredEvents as $event)
                @php
                    $minPrice = $event->ticketCategories->min('price');
                @endphp
                <a href="{{ route('event.events.show', $event->slug) }}" 
                   class="group bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl hover:border-nova-200 transition-all duration-300 flex flex-col">
                    
                    {{-- Banner --}}
                    <div class="relative h-48 overflow-hidden bg-gray-200">
                        @if ($event->banner_image)
                            <img src="{{ Storage::url($event->banner_image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $event->title }}">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-nova-500 to-violet-600 flex items-center justify-center text-5xl">
                                {{ match($event->category) {
                                    'music' => '🎵', 'cosplay' => '🎭', 'art' => '🎨', 'game_tournament' => '🎮', 'festival' => '🎉', default => '✨'
                                } }}
                            </div>
                        @endif
                        
                        <div class="absolute top-3 left-3 bg-white/90 backdrop-blur text-gray-800 text-xs font-bold px-3 py-1 rounded-full capitalize shadow-sm">
                            {{ str_replace('_', ' ', $event->category) }}
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-5 flex-1 flex flex-col">
                        <h3 class="font-bold text-gray-900 text-lg mb-2 line-clamp-2 group-hover:text-nova-600 transition-colors">
                            {{ $event->title }}
                        </h3>
                        
                        <div class="space-y-2 text-sm text-gray-600 mb-4 flex-1">
                            <div class="flex items-center gap-2">
                                <span class="text-nova-500">📅</span>
                                <span>{{ $event->start_date->format('d M Y, H:i') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-nova-500">📍</span>
                                <span class="truncate">{{ $event->location }}</span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100 flex justify-between items-center">
                            <span class="text-xs text-gray-500 font-medium">Mulai dari</span>
                            <span class="font-bold text-nova-600">
                                @if ($minPrice === null) -
                                @elseif ($minPrice == 0) GRATIS
                                @else Rp {{ number_format($minPrice, 0, ',', '.') }}
                                @endif
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>

@endsection