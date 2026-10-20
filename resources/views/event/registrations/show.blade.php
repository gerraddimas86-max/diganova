@extends('layouts.public')

@section('title', 'Registrasi ' . $registration->order_code . ' — DIGANOVA')

@section('content')

<div class="max-w-3xl mx-auto px-4 py-8">

    <div class="mb-4">
        <a href="{{ route('event.registrations.index') }}" class="text-sm text-nova-600 hover:underline">
            ← Kembali ke Registrasi Saya
        </a>
    </div>

    @php
        $statusConfig = match($registration->payment_status) {
            'verified' => [
                'bg' => 'from-green-500 to-emerald-600',
                'border' => 'border-green-200',
                'icon' => '✅',
                'title' => 'Pembayaran Terverifikasi',
                'desc' => 'Tiket kamu sudah aktif. Tunjukkan E-Ticket saat check-in di venue.',
            ],
            'pending' => [
                'bg' => 'from-yellow-500 to-orange-500',
                'border' => 'border-yellow-200',
                'icon' => '⏳',
                'title' => 'Menunggu Verifikasi',
                'desc' => 'Admin akan memverifikasi pembayaran kamu dalam 1x24 jam.',
            ],
            'rejected' => [
                'bg' => 'from-red-500 to-rose-600',
                'border' => 'border-red-200',
                'icon' => '❌',
                'title' => 'Pembayaran Ditolak',
                'desc' => $registration->payment_notes ?: 'Hubungi admin untuk info lebih lanjut.',
            ],
            default => [
                'bg' => 'from-gray-500 to-gray-600',
                'border' => 'border-gray-200',
                'icon' => '❓',
                'title' => 'Status Tidak Diketahui',
                'desc' => '',
            ],
        };
    @endphp

    {{-- Status Banner --}}
    <div class="bg-gradient-to-r {{ $statusConfig['bg'] }} rounded-xl p-6 text-white mb-6">
        <div class="flex items-center gap-4">
            <div class="text-4xl">{{ $statusConfig['icon'] }}</div>
            <div class="flex-1">
                <h1 class="text-xl font-bold">{{ $statusConfig['title'] }}</h1>
                <p class="text-sm text-white/90 mt-1">{{ $statusConfig['desc'] }}</p>
            </div>
        </div>
    </div>

    {{-- E-Ticket Action (kalau verified) --}}
    @if ($registration->payment_status === 'verified')
        <div class="bg-gradient-to-br from-nova-50 to-crystal-50 rounded-xl border-2 border-nova-200 p-6 mb-6">
            <div class="flex items-start gap-4">
                <div class="text-4xl">🎟️</div>
                <div class="flex-1">
                    <h2 class="font-bold text-gray-900">E-Ticket Kamu Siap!</h2>
                    <p class="text-sm text-gray-600 mt-1">
                        Kode: <span class="font-mono font-semibold text-gray-900">{{ $registration->e_ticket_code }}</span>
                    </p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <a href="{{ route('event.registrations.ticket', $registration->order_code) }}"
                           class="inline-flex items-center gap-2 bg-nova-600 hover:bg-nova-700 text-white px-5 py-2.5 rounded-lg font-semibold transition text-sm">
                            🎫 Lihat E-Ticket
                        </a>
                        <a href="{{ route('event.registrations.ticket.download', $registration->order_code) }}"
                           class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-nova-700 border border-nova-300 px-5 py-2.5 rounded-lg font-medium transition text-sm">
                            📥 Download PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Order Info --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-4">
        <h2 class="font-bold text-gray-900 mb-4">Detail Pesanan</h2>

        <dl class="space-y-3 text-sm">
            <div class="flex justify-between border-b border-gray-100 pb-2">
                <dt class="text-gray-500">Kode Order</dt>
                <dd class="font-mono font-semibold">{{ $registration->order_code }}</dd>
            </div>
            <div class="flex justify-between border-b border-gray-100 pb-2">
                <dt class="text-gray-500">Event</dt>
                <dd class="font-medium text-right">
                    <a href="{{ route('event.events.show', $registration->event->slug) }}"
                       class="text-nova-600 hover:underline">
                        {{ $registration->event->title }}
                    </a>
                </dd>
            </div>
            <div class="flex justify-between border-b border-gray-100 pb-2">
                <dt class="text-gray-500">Tanggal Event</dt>
                <dd class="font-medium">{{ $registration->event->start_date->format('d M Y H:i') }}</dd>
            </div>
            <div class="flex justify-between border-b border-gray-100 pb-2">
                <dt class="text-gray-500">Lokasi</dt>
                <dd class="font-medium text-right">{{ $registration->event->location }}</dd>
            </div>
            <div class="flex justify-between border-b border-gray-100 pb-2">
                <dt class="text-gray-500">Kategori Tiket</dt>
                <dd class="font-medium">{{ $registration->ticketCategory->name }}</dd>
            </div>
            <div class="flex justify-between border-b border-gray-100 pb-2">
                <dt class="text-gray-500">Total Bayar</dt>
                <dd class="font-bold text-nova-600 text-base">
                    @if ($registration->ticketCategory->price == 0)
                        GRATIS
                    @else
                        Rp {{ number_format($registration->ticketCategory->price, 0, ',', '.') }}
                    @endif
                </dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">Tanggal Daftar</dt>
                <dd class="font-medium">{{ $registration->created_at->format('d M Y H:i') }}</dd>
            </div>
        </dl>
    </div>

    {{-- Bukti Pembayaran --}}
    @if ($registration->payment_proof_path)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-4">
            <h2 class="font-bold text-gray-900 mb-3">Bukti Pembayaran</h2>
            <img src="{{ Storage::url($registration->payment_proof_path) }}"
                 class="w-full max-w-md rounded-lg border border-gray-200" alt="Bukti transfer">
            @if ($registration->payment_verified_at)
                <p class="text-xs text-gray-500 mt-2">
                    Diproses pada: {{ $registration->payment_verified_at->format('d M Y H:i') }}
                    @if ($registration->verifier)
                        oleh {{ $registration->verifier->name }}
                    @endif
                </p>
            @endif
        </div>
    @endif

    {{-- Catatan Penolakan --}}
    @if ($registration->payment_status === 'rejected' && $registration->payment_notes)
        <div class="bg-red-50 border border-red-200 rounded-xl p-6">
            <h2 class="font-bold text-red-800 mb-2">Alasan Penolakan</h2>
            <p class="text-sm text-red-700">{{ $registration->payment_notes }}</p>
            <p class="text-xs text-red-600 mt-3">
                Hubungi admin atau daftar ulang dengan bukti pembayaran yang benar.
            </p>
        </div>
    @endif

</div>

@endsection