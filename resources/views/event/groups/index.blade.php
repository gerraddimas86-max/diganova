@extends('layouts.public')

@section('title', 'Grup Saya — DIGANOVA')

@section('content')

<div class="max-w-5xl mx-auto px-4 py-8">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Grup Saya</h1>
        <p class="text-sm text-gray-500 mt-1">Komunitas event yang kamu ikuti</p>
    </div>

    @if ($groups->isEmpty())
        <div class="bg-white rounded-xl border border-gray-100 p-12 text-center">
            <div class="text-5xl mb-3">💬</div>
            <p class="text-gray-700 font-medium">Belum ada grup</p>
            <p class="text-sm text-gray-500 mt-1">
                Kamu akan otomatis bergabung grup setelah mendaftar event dan pembayaran terverifikasi.
            </p>
            <a href="{{ route('event.events.index') }}"
               class="inline-block mt-4 bg-nova-600 hover:bg-nova-700 text-white px-6 py-2.5 rounded-lg font-medium transition">
                Jelajahi Events
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($groups as $group)
                <a href="{{ route('event.groups.show', $group) }}"
                   class="block bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:border-nova-200 transition p-5 group">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-nova-500 to-crystal-400 flex items-center justify-center text-white text-lg font-bold flex-shrink-0">
                            💬
                        </div>
                        <span class="text-xs bg-nova-50 text-nova-700 px-2 py-0.5 rounded-full font-medium">
                            {{ $group->members_count }} anggota
                        </span>
                    </div>
                    <h3 class="font-bold text-gray-900 line-clamp-1 group-hover:text-nova-600 transition">
                        {{ $group->name }}
                    </h3>
                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">
                        {{ $group->description ?? 'Grup komunitas event' }}
                    </p>
                    <div class="mt-3 pt-3 border-t border-gray-100 flex justify-between items-center text-xs">
                        <span class="text-gray-500 line-clamp-1">{{ $group->event->title }}</span>
                        <span class="text-nova-600 font-medium">Buka →</span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

</div>

@endsection