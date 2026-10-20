@extends('admin.layouts.app')

@section('title', $event->title . ' — Admin')

@section('content')

@php
    $pendingCount = $event->registrations()->where('payment_status', 'pending')->count();
    $verifiedCount = $event->registrations()->where('payment_status', 'verified')->count();
    $rejectedCount = $event->registrations()->where('payment_status', 'rejected')->count();
    $totalRegistrations = $pendingCount + $verifiedCount + $rejectedCount;

    $recentRegistrations = $event->registrations()
        ->with(['user', 'ticketCategory'])
        ->latest()
        ->take(5)
        ->get();

    $totalSold = $event->ticketCategories->sum('sold_count');
    $totalQuota = $event->ticketCategories->sum('quota');
@endphp

{{-- Header --}}
<div class="mb-6 flex justify-between items-start flex-wrap gap-3">
    <div>
        <a href="{{ route('admin.events.index') }}" class="text-sm text-nova-600 hover:underline">← Kembali</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $event->title }}</h1>
        <p class="text-sm text-gray-500 mt-1">
            {{ $event->start_date->format('d M Y H:i') }} · {{ $event->location }}
        </p>
    </div>
    <div class="flex gap-2 flex-wrap">
        <a href="{{ route('event.events.show', $event->slug) }}" target="_blank"
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium transition">
            🌐 Lihat Publik
        </a>
        <a href="{{ route('admin.events.edit', $event) }}"
           class="bg-nova-600 hover:bg-nova-700 text-white px-4 py-2 rounded-lg font-medium transition">
            Edit Event
        </a>
    </div>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <p class="text-xs text-gray-500 uppercase tracking-wide">Total Registrasi</p>
        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalRegistrations }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-yellow-100 p-4">
        <p class="text-xs text-yellow-600 uppercase tracking-wide">Menunggu Verifikasi</p>
        <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $pendingCount }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-green-100 p-4">
        <p class="text-xs text-green-600 uppercase tracking-wide">Terverifikasi</p>
        <p class="text-2xl font-bold text-green-600 mt-1">{{ $verifiedCount }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-red-100 p-4">
        <p class="text-xs text-red-600 uppercase tracking-wide">Ditolak</p>
        <p class="text-2xl font-bold text-red-600 mt-1">{{ $rejectedCount }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Main Info --}}
    <div class="lg:col-span-2 space-y-6">
        @if ($event->banner_image)
            <img src="{{ Storage::url($event->banner_image) }}"
                 class="w-full h-48 object-cover rounded-xl border border-gray-200" alt="Banner">
        @endif

        {{-- Deskripsi --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-900 mb-3">Deskripsi</h2>
            <div class="text-sm text-gray-700 whitespace-pre-line">{{ $event->description }}</div>
        </div>

        {{-- Registrasi Terbaru --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-3">
                <h2 class="font-bold text-gray-900">Registrasi Terbaru</h2>
                @if ($totalRegistrations > 0)
                    <a href="#" onclick="event.preventDefault(); alert('Kelola registrasi lengkap akan hadir di Step 13.');"
                       class="text-xs text-nova-600 hover:underline">
                        Lihat Semua ({{ $totalRegistrations }}) →
                    </a>
                @endif
            </div>

            @if ($recentRegistrations->isEmpty())
                <p class="text-sm text-gray-500">Belum ada registrasi.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-xs text-gray-500 uppercase border-b border-gray-100">
                            <tr>
                                <th class="text-left py-2 font-medium">Peserta</th>
                                <th class="text-left py-2 font-medium">Tiket</th>
                                <th class="text-left py-2 font-medium">Status</th>
                                <th class="text-right py-2 font-medium">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($recentRegistrations as $reg)
                                @php
                                    $statusColor = match($reg->payment_status) {
                                        'verified' => 'bg-green-100 text-green-700',
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                        default => 'bg-gray-100 text-gray-700',
                                    };
                                @endphp
                                <tr>
                                    <td class="py-2">
                                        <p class="font-medium text-gray-900">{{ $reg->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ '@' . $reg->user->username }}</p>
                                    </td>
                                    <td class="py-2 text-gray-700">{{ $reg->ticketCategory->name }}</td>
                                    <td class="py-2">
                                        <span class="text-xs px-2 py-0.5 rounded capitalize {{ $statusColor }}">
                                            {{ $reg->payment_status }}
                                        </span>
                                    </td>
                                    <td class="py-2 text-right text-xs text-gray-500">
                                        {{ $reg->created_at->format('d M H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Jadwal --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-900 mb-3">Jadwal ({{ $event->schedules->count() }})</h2>
            @if ($event->schedules->isEmpty())
                <p class="text-sm text-gray-500">Belum ada jadwal.</p>
            @else
                <ul class="space-y-2">
                    @foreach ($event->schedules as $s)
                        <li class="text-sm flex justify-between border-b border-gray-100 pb-2 last:border-0 last:pb-0">
                            <span class="font-medium">{{ $s->title }}</span>
                            <span class="text-gray-500">{{ $s->start_time->format('d M H:i') }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Tiket --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-3">
                <h2 class="font-bold text-gray-900">Tiket ({{ $event->ticketCategories->count() }})</h2>
                <span class="text-xs text-gray-500">
                    Terjual: {{ $totalSold }} / {{ $totalQuota }}
                </span>
            </div>
            @if ($event->ticketCategories->isEmpty())
                <p class="text-sm text-gray-500">Belum ada kategori tiket.</p>
            @else
                <ul class="space-y-3">
                    @foreach ($event->ticketCategories as $t)
                        @php
                            $percent = $t->quota > 0 ? round(($t->sold_count / $t->quota) * 100) : 0;
                            $isFull = $t->sold_count >= $t->quota;
                        @endphp
                        <li class="border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                            <div class="flex justify-between items-start mb-1">
                                <div>
                                    <span class="font-medium text-sm">{{ $t->name }}</span>
                                    @if (!$t->is_active)
                                        <span class="text-xs bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded ml-1">Nonaktif</span>
                                    @endif
                                </div>
                                <span class="font-medium text-sm">
                                    Rp {{ number_format($t->price, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2 mt-1">
                                <div class="flex-1 bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                    <div class="h-full {{ $isFull ? 'bg-red-500' : 'bg-nova-500' }}"
                                         style="width: {{ $percent }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500 whitespace-nowrap">
                                    {{ $t->sold_count }}/{{ $t->quota }} ({{ $percent }}%)
                                </span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">

        {{-- Info --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-900 mb-3">Info</h2>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between gap-2">
                    <dt class="text-gray-500">Status</dt>
                    <dd class="font-medium capitalize">{{ $event->status }}</dd>
                </div>
                <div class="flex justify-between gap-2">
                    <dt class="text-gray-500">Kategori</dt>
                    <dd class="font-medium capitalize">{{ $event->category }}</dd>
                </div>
                <div class="flex justify-between gap-2">
                    <dt class="text-gray-500">Lokasi</dt>
                    <dd class="font-medium text-right">{{ $event->location }}</dd>
                </div>
                <div class="flex justify-between gap-2">
                    <dt class="text-gray-500">Mulai</dt>
                    <dd class="font-medium text-right">{{ $event->start_date->format('d M Y H:i') }}</dd>
                </div>
                <div class="flex justify-between gap-2">
                    <dt class="text-gray-500">Selesai</dt>
                    <dd class="font-medium text-right">{{ $event->end_date->format('d M Y H:i') }}</dd>
                </div>
                @if ($event->registration_deadline)
                    <div class="flex justify-between gap-2">
                        <dt class="text-gray-500">Deadline Daftar</dt>
                        <dd class="font-medium text-right">{{ $event->registration_deadline->format('d M Y H:i') }}</dd>
                    </div>
                @endif
                <div class="flex justify-between gap-2">
                    <dt class="text-gray-500">Dibuat oleh</dt>
                    <dd class="font-medium">{{ $event->creator->name ?? '-' }}</dd>
                </div>
                <div class="flex justify-between gap-2">
                    <dt class="text-gray-500">Grup</dt>
                    <dd class="font-medium text-right">{{ $event->group->name ?? 'Belum ada' }}</dd>
                </div>
            </dl>
        </div>

        {{-- Peserta --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-900 mb-3">Peserta</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Total terdaftar</span>
                    <span class="font-medium">{{ $totalRegistrations }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Terverifikasi</span>
                    <span class="font-medium text-green-600">{{ $verifiedCount }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Menunggu</span>
                    <span class="font-medium text-yellow-600">{{ $pendingCount }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Ditolak</span>
                    <span class="font-medium text-red-600">{{ $rejectedCount }}</span>
                </div>
                @if ($event->max_participants)
                    <div class="flex justify-between pt-2 mt-2 border-t border-gray-100">
                        <span class="text-gray-500">Kuota event</span>
                        <span class="font-medium">{{ $event->max_participants }}</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Danger Zone --}}
        <div class="bg-white rounded-xl shadow-sm border border-red-100 p-6">
            <h2 class="font-bold text-red-700 mb-2">Danger Zone</h2>
            <p class="text-xs text-gray-500 mb-3">
                Hapus event akan menghapus semua data terkait: registrasi, grup, jadwal, tiket.
            </p>
            <form method="POST" action="{{ route('admin.events.destroy', $event) }}"
                  onsubmit="return confirm('Yakin hapus event ini? Tindakan tidak bisa dibatalkan.');">
                @csrf
                @method('DELETE')
                <button class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition">
                    Hapus Event
                </button>
            </form>
        </div>
    </div>
</div>

@endsection