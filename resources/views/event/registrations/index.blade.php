@extends('layouts.public')

@section('title', 'Registrasi Saya — DIGANOVA')

@section('content')

<div class="max-w-5xl mx-auto px-4 py-8">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Registrasi Saya</h1>
        <p class="text-sm text-gray-500 mt-1">Total: {{ $registrations->total() }} registrasi</p>
    </div>

    @if ($registrations->isEmpty())
        <div class="bg-white rounded-xl border border-gray-100 p-12 text-center">
            <div class="text-5xl mb-4">🎟️</div>
            <p class="text-gray-700 font-medium">Belum ada registrasi</p>
            <p class="text-sm text-gray-500 mt-1">Yuk ikut event pertamamu!</p>
            <a href="{{ route('event.events.index') }}"
               class="inline-block mt-4 bg-nova-600 hover:bg-nova-700 text-white px-6 py-2.5 rounded-lg font-medium transition">
                Lihat Events
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($registrations as $reg)
                @php
                    $statusColor = match($reg->payment_status) {
                        'verified' => 'bg-green-100 text-green-700 border-green-200',
                        'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                        'rejected' => 'bg-red-100 text-red-700 border-red-200',
                        default => 'bg-gray-100 text-gray-700 border-gray-200',
                    };
                    $statusIcon = match($reg->payment_status) {
                        'verified' => '✅',
                        'pending' => '⏳',
                        'rejected' => '❌',
                        default => '❓',
                    };
                @endphp

                <a href="{{ route('event.registrations.show', $reg->order_code) }}"
                   class="block bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition p-5">
                    <div class="flex flex-col sm:flex-row justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-mono text-gray-500">{{ $reg->order_code }}</span>
                                <span class="text-xs px-2 py-0.5 rounded border {{ $statusColor }}">
                                    {{ $statusIcon }} {{ ucfirst($reg->payment_status) }}
                                </span>
                            </div>
                            <h3 class="font-bold text-gray-900">{{ $reg->event->title }}</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $reg->ticketCategory->name }} · {{ $reg->event->start_date->format('d M Y') }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                📍 {{ $reg->event->location }}
                            </p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-xs text-gray-500">Didaftarkan</p>
                            <p class="text-sm font-medium">{{ $reg->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $registrations->links() }}
        </div>
    @endif
</div>

@endsection