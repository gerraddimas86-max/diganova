@extends('admin.layouts.app')

@section('title', 'Registrasi ' . $registration->order_code . ' — Admin')

@section('content')

@php
    $statusConfig = match($registration->payment_status) {
        'verified' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'border' => 'border-green-200', 'icon' => '✅', 'label' => 'Terverifikasi'],
        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-200', 'icon' => '⏳', 'label' => 'Menunggu Verifikasi'],
        'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-200', 'icon' => '❌', 'label' => 'Ditolak'],
        default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'border' => 'border-gray-200', 'icon' => '❓', 'label' => 'Unknown'],
    };
@endphp

{{-- Header --}}
<div class="mb-6">
    <a href="{{ route('admin.registrations.index') }}" class="text-sm text-nova-600 hover:underline">
        ← Kembali ke daftar registrasi
    </a>
    <div class="flex justify-between items-start mt-2 flex-wrap gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                <span class="font-mono">{{ $registration->order_code }}</span>
                <span class="text-xs px-2 py-1 rounded {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">
                    {{ $statusConfig['icon'] }} {{ $statusConfig['label'] }}
                </span>
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Didaftarkan {{ $registration->created_at->format('d M Y H:i') }}
            </p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- MAIN --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Peserta --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-900 mb-4">Peserta</h2>
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-nova-500 to-crystal-400 flex items-center justify-center text-white text-xl font-bold flex-shrink-0">
                    {{ strtoupper(substr($registration->user->name, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <p class="font-bold text-gray-900">{{ $registration->user->name }}</p>
                    <p class="text-sm text-gray-500">{{ '@' . $registration->user->username }}</p>
                    <p class="text-sm text-gray-500">{{ $registration->user->email }}</p>
                    <div class="mt-2 flex gap-2 text-xs">
                        <span class="bg-nova-50 text-nova-700 px-2 py-0.5 rounded capitalize">
                            Rank {{ $registration->user->rank }}
                        </span>
                        <span class="bg-crystal-50 text-crystal-700 px-2 py-0.5 rounded">
                            {{ $registration->user->total_points }} poin
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Event --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-900 mb-4">Event</h2>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-500">Judul</span>
                    <a href="{{ route('event.events.show', $registration->event->slug) }}" target="_blank"
                       class="font-medium text-nova-600 hover:underline text-right">
                        {{ $registration->event->title }}
                    </a>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-500">Tanggal</span>
                    <span class="font-medium">{{ $registration->event->start_date->format('d M Y H:i') }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-500">Lokasi</span>
                    <span class="font-medium text-right">{{ $registration->event->location }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Kategori Tiket</span>
                    <span class="font-medium">{{ $registration->ticketCategory->name }}</span>
                </div>
            </div>
        </div>

        {{-- Bukti Pembayaran --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-900 mb-4">Bukti Pembayaran</h2>

            @if ($registration->ticketCategory->price == 0)
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-sm text-green-800">
                    ✅ Tiket gratis — tidak memerlukan bukti pembayaran
                </div>
            @elseif ($registration->payment_proof_path)
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <img src="{{ Storage::url($registration->payment_proof_path) }}"
                         class="w-full max-h-96 object-contain bg-gray-50" alt="Bukti transfer">
                </div>
                <div class="mt-3 flex justify-between text-xs text-gray-500">
                    <a href="{{ Storage::url($registration->payment_proof_path) }}" target="_blank"
                       class="text-nova-600 hover:underline">🔍 Buka gambar penuh</a>
                    <span>Nominal: <strong class="text-gray-700">Rp {{ number_format($registration->ticketCategory->price, 0, ',', '.') }}</strong></span>
                </div>
            @else
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm text-gray-600">
                    ⚠️ User belum upload bukti pembayaran
                </div>
            @endif

            @if ($registration->payment_verified_at)
                <div class="mt-4 pt-4 border-t border-gray-100 text-xs text-gray-500">
                    Diproses {{ $registration->payment_verified_at->format('d M Y H:i') }}
                    @if ($registration->verifier)
                        oleh <strong class="text-gray-700">{{ $registration->verifier->name }}</strong>
                    @endif
                    @if ($registration->payment_notes)
                        <div class="mt-2 bg-red-50 border border-red-100 rounded p-2 text-red-800">
                            <p class="font-semibold">Catatan:</p>
                            <p>{{ $registration->payment_notes }}</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        {{-- Check-in History --}}
        @if ($registration->checkinLogs->isNotEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="font-bold text-gray-900 mb-3">Riwayat Check-in</h2>
                <ul class="space-y-2 text-sm">
                    @foreach ($registration->checkinLogs as $log)
                        <li class="flex justify-between border-b border-gray-100 pb-2 last:border-0">
                            <div>
                                <p class="font-medium">{{ ucfirst($log->method) }}</p>
                                <p class="text-xs text-gray-500">oleh {{ $log->scanner->name ?? 'sistem' }}</p>
                            </div>
                            <span class="text-xs text-gray-500">{{ $log->check_in_at->format('d M Y H:i') }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    {{-- SIDEBAR --}}
    <div class="space-y-6">

        {{-- Actions --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6"
             x-data="{ showRejectModal: false }">

            <h2 class="font-bold text-gray-900 mb-4">Aksi</h2>

            @if ($registration->payment_status === 'pending')
                {{-- Approve --}}
                <form method="POST" action="{{ route('admin.registrations.approve', $registration) }}"
                      onsubmit="return confirm('Verifikasi pembayaran {{ $registration->order_code }}? User akan otomatis masuk grup & dapat achievement.');"
                      class="mb-3">
                    @csrf
                    <button class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg font-semibold transition flex items-center justify-center gap-2">
                        ✅ Approve Pembayaran
                    </button>
                </form>

                {{-- Reject --}}
                <button type="button" @click="showRejectModal = true"
                        class="w-full bg-red-100 hover:bg-red-200 text-red-700 px-4 py-3 rounded-lg font-semibold transition flex items-center justify-center gap-2">
                    ❌ Tolak Pembayaran
                </button>

                {{-- Modal Reject --}}
                <div x-show="showRejectModal" x-cloak
                     class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
                     @click.self="showRejectModal = false">
                    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
                        <h3 class="font-bold text-gray-900 mb-2">Tolak Registrasi</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Order: <span class="font-mono">{{ $registration->order_code }}</span><br>
                            Peserta: <strong>{{ $registration->user->name }}</strong>
                        </p>

                        <form method="POST" action="{{ route('admin.registrations.reject', $registration) }}">
                            @csrf

                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Alasan Penolakan *
                            </label>
                            <textarea name="notes" rows="4" required minlength="5" maxlength="500"
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 outline-none"
                                      placeholder="Contoh: Bukti transfer tidak jelas, nominal tidak sesuai, dst..."></textarea>
                            @error('notes')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror

                            <div class="mt-4 flex gap-2">
                                <button type="submit"
                                        class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2.5 rounded-lg font-semibold">
                                    Tolak Registrasi
                                </button>
                                <button type="button" @click="showRejectModal = false"
                                        class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @elseif ($registration->payment_status === 'verified')
                <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-sm text-green-800">
                    ✅ Sudah diverifikasi
                    <p class="text-xs mt-1">E-ticket: <span class="font-mono">{{ $registration->e_ticket_code }}</span></p>
                </div>
            @else
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-800">
                    ❌ Registrasi ditolak
                    @if ($registration->payment_notes)
                        <p class="text-xs mt-1">{{ $registration->payment_notes }}</p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Detail --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-900 mb-3">Detail</h2>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Order Code</dt>
                    <dd class="font-mono text-xs">{{ $registration->order_code }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">E-Ticket</dt>
                    <dd class="font-mono text-xs">{{ $registration->e_ticket_code }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Status Bayar</dt>
                    <dd class="font-medium capitalize">{{ $registration->payment_status }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Check-in</dt>
                    <dd class="font-medium">{{ $registration->checked_in ? '✅ Sudah' : 'Belum' }}</dd>
                </div>
            </dl>
        </div>

        {{-- Danger Zone --}}
        <div class="bg-white rounded-xl shadow-sm border border-red-100 p-6">
            <h2 class="font-bold text-red-700 mb-2">Danger Zone</h2>
            <p class="text-xs text-gray-500 mb-3">Hapus registrasi permanen?</p>
            <form method="POST"
                  action="#" {{-- nanti di batch berikut --}}
                  onsubmit="event.preventDefault(); alert('Hapus registrasi akan hadir nanti.');">
                @csrf
                @method('DELETE')
                <button class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium text-sm">
                    Hapus Registrasi
                </button>
            </form>
        </div>
    </div>
</div>

<style>[x-cloak]{display:none!important}</style>

@endsection