@extends('admin.layouts.app')

@section('title', 'Registrasi — Admin')

@section('content')

{{-- Header --}}
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Registrasi</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola verifikasi pembayaran peserta event</p>
    </div>
    <a href="{{ route('admin.events.index') }}"
       class="text-sm text-nova-600 hover:underline">
        ← Events
    </a>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-yellow-100 p-5">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs text-yellow-600 uppercase tracking-wide font-semibold">Pending</p>
                <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $stats['pending'] }}</p>
            </div>
            <div class="text-3xl">⏳</div>
        </div>
        <p class="text-xs text-gray-500 mt-2">Menunggu verifikasi</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-green-100 p-5">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs text-green-600 uppercase tracking-wide font-semibold">Verified Hari Ini</p>
                <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['verified_today'] }}</p>
            </div>
            <div class="text-3xl">✅</div>
        </div>
        <p class="text-xs text-gray-500 mt-2">Sudah diverifikasi</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-red-100 p-5">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs text-red-600 uppercase tracking-wide font-semibold">Rejected Hari Ini</p>
                <p class="text-3xl font-bold text-red-600 mt-2">{{ $stats['rejected_today'] }}</p>
            </div>
            <div class="text-3xl">❌</div>
        </div>
        <p class="text-xs text-gray-500 mt-2">Ditolak</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs text-gray-600 uppercase tracking-wide font-semibold">Total</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total'] }}</p>
            </div>
            <div class="text-3xl">📋</div>
        </div>
        <p class="text-xs text-gray-500 mt-2">Semua registrasi</p>
    </div>
</div>

{{-- Filter Bar --}}
<form method="GET" action="{{ route('admin.registrations.index') }}"
      class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6 space-y-3 lg:space-y-0 lg:flex lg:items-center lg:gap-3">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Cari order code, nama, atau email..."
           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nova-500 outline-none">

    <select name="status"
            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nova-500 outline-none">
        <option value="">Semua Status</option>
        <option value="pending" @selected(request('status') === 'pending')>Pending</option>
        <option value="verified" @selected(request('status') === 'verified')>Verified</option>
        <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
    </select>

    <select name="event_id"
            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nova-500 outline-none">
        <option value="">Semua Event</option>
        @foreach ($events as $ev)
            <option value="{{ $ev->id }}" @selected(request('event_id') == $ev->id)>{{ $ev->title }}</option>
        @endforeach
    </select>

    <button type="submit"
            class="bg-nova-600 hover:bg-nova-700 text-white px-6 py-2 rounded-lg font-medium transition">
        Filter
    </button>

    @if (request()->hasAny(['search', 'status', 'event_id']))
        <a href="{{ route('admin.registrations.index') }}"
           class="text-sm text-gray-500 hover:text-red-600 px-3">
            Reset
        </a>
    @endif
</form>

{{-- Table --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-4 py-3 font-semibold text-gray-700">Order Code</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-700">Peserta</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-700">Event</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-700">Tiket</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-700">Harga</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-700">Status</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($registrations as $reg)
                    @php
                        $statusColor = match($reg->payment_status) {
                            'verified' => 'bg-green-100 text-green-700',
                            'pending' => 'bg-yellow-100 text-yellow-700',
                            'rejected' => 'bg-red-100 text-red-700',
                            default => 'bg-gray-100 text-gray-700',
                        };
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <p class="font-mono text-xs font-medium text-gray-900">{{ $reg->order_code }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $reg->created_at->format('d M H:i') }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-900">{{ $reg->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ '@' . $reg->user->username }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-gray-900 line-clamp-1 max-w-xs">{{ $reg->event->title }}</p>
                            <p class="text-xs text-gray-500">{{ $reg->event->start_date->format('d M Y') }}</p>
                        </td>
                        <td class="px-4 py-3 text-gray-700">{{ $reg->ticketCategory->name }}</td>
                        <td class="px-4 py-3 text-right font-medium text-gray-900">
                            @if ($reg->ticketCategory->price == 0)
                                <span class="text-xs text-green-600">GRATIS</span>
                            @else
                                Rp {{ number_format($reg->ticketCategory->price, 0, ',', '.') }}
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-1 rounded capitalize {{ $statusColor }}">
                                {{ $reg->payment_status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            @if ($reg->payment_status === 'pending')
                                <div class="inline-flex gap-2">
                                    <form method="POST" action="{{ route('admin.registrations.approve', $reg) }}"
                                          onsubmit="return confirm('Verifikasi registrasi {{ $reg->order_code }}?');">
                                        @csrf
                                        <button class="text-xs bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded font-medium">
                                            ✓ Approve
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.registrations.show', $reg) }}"
                                       class="text-xs bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1 rounded font-medium">
                                        Review
                                    </a>
                                </div>
                            @else
                                <a href="{{ route('admin.registrations.show', $reg) }}"
                                   class="text-xs text-nova-600 hover:text-nova-700 font-medium">
                                    Detail →
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-gray-500">
                            <div class="text-5xl mb-2">📭</div>
                            <p class="font-medium">Belum ada registrasi</p>
                            @if (request()->hasAny(['search', 'status', 'event_id']))
                                <a href="{{ route('admin.registrations.index') }}" class="inline-block mt-2 text-sm text-nova-600 hover:underline">
                                    Reset filter
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Pagination --}}
@if ($registrations->hasPages())
    <div class="mt-4">
        {{ $registrations->links() }}
    </div>
@endif

@endsection