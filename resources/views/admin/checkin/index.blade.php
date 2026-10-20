@extends('admin.layouts.app')

@section('title', 'Check-in — Admin')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Check-in Peserta</h1>
    <p class="text-sm text-gray-500 mt-1">Pilih event untuk mulai scan QR peserta</p>
</div>

@if ($events->isEmpty())
    <div class="bg-white rounded-xl border border-gray-100 p-12 text-center">
        <div class="text-5xl mb-2">📭</div>
        <p class="text-gray-700 font-medium">Belum ada event</p>
        <a href="{{ route('admin.events.create') }}" class="inline-block mt-3 text-nova-600 hover:underline text-sm">
            Buat event pertama →
        </a>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($events as $event)
            @php
                $percent = $event->verified_count > 0
                    ? round(($event->checked_in_count / $event->verified_count) * 100)
                    : 0;
                $statusColor = match($event->status) {
                    'published' => 'bg-green-100 text-green-700',
                    'ongoing' => 'bg-blue-100 text-blue-700',
                    'completed' => 'bg-gray-100 text-gray-600',
                    'cancelled' => 'bg-red-100 text-red-700',
                    default => 'bg-gray-100 text-gray-600',
                };
            @endphp

            <a href="{{ route('admin.checkin.scanner', $event) }}"
               class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md hover:border-nova-200 transition group">
                <div class="flex justify-between items-start mb-2">
                    <span class="text-xs px-2 py-0.5 rounded capitalize font-medium {{ $statusColor }}">
                        {{ $event->status }}
                    </span>
                    <span class="text-xs text-gray-500">{{ $event->start_date->format('d M Y') }}</span>
                </div>

                <h3 class="font-bold text-gray-900 line-clamp-2 group-hover:text-nova-600 transition">
                    {{ $event->title }}
                </h3>
                <p class="text-xs text-gray-500 mt-1">📍 {{ $event->location }}</p>

                <div class="mt-4">
                    <div class="flex justify-between text-xs text-gray-600 mb-1">
                        <span>Check-in Progress</span>
                        <span class="font-semibold text-gray-900">
                            {{ $event->checked_in_count }} / {{ $event->verified_count }}
                        </span>
                    </div>
                    <div class="bg-gray-100 rounded-full h-2 overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-nova-500 to-crystal-500 transition-all"
                             style="width: {{ $percent }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ $percent }}% hadir</p>
                </div>

                <div class="mt-4 pt-3 border-t border-gray-100 flex justify-between items-center">
                    <span class="text-xs text-nova-600 font-semibold">Buka Scanner</span>
                    <span class="text-nova-600 group-hover:translate-x-1 transition-transform">→</span>
                </div>
            </a>
        @endforeach
    </div>
@endif

@endsection