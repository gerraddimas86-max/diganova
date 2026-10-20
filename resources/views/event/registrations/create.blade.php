@extends('layouts.public')

@section('title', 'Daftar ' . $event->title . ' — DIGANOVA')

@section('content')

<div class="max-w-3xl mx-auto px-4 py-8">

    {{-- Breadcrumb --}}
    <nav class="text-sm text-gray-500 mb-4">
        <a href="{{ route('event.events.index') }}" class="hover:text-nova-600">Events</a>
        <span class="mx-2">/</span>
        <a href="{{ route('event.events.show', $event->slug) }}" class="hover:text-nova-600">{{ $event->title }}</a>
        <span class="mx-2">/</span>
        <span class="text-gray-700">Daftar</span>
    </nav>

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Daftar Event</h1>
        <p class="text-gray-600 mt-1">{{ $event->title }} · {{ $event->start_date->format('d M Y') }}</p>
        <p class="text-xs text-gray-500 mt-1">Registrasi ke-{{ $myRegistrationsCount + 1 }} dari maksimal 3x</p>
    </div>

    <form method="POST" action="{{ route('event.registrations.store', $event->slug) }}"
          enctype="multipart/form-data" class="space-y-6"
          x-data="{ selectedTicket: null, tickets: {{ $event->availableTickets()->toJson() }} }">
        @csrf

        {{-- Info User --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-900 mb-4">Data Pemesan</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Nama</span>
                    <span class="font-medium">{{ auth()->user()->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Email</span>
                    <span class="font-medium">{{ auth()->user()->email }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Username</span>
                    <span class="font-medium">{{ '@' . auth()->user()->username }}</span>
                </div>
            </div>
        </div>

        {{-- Pilih Tiket --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-900 mb-4">Pilih Kategori Tiket *</h2>

            <div class="space-y-3">
                @foreach ($event->availableTickets() as $ticket)
                    <label class="block cursor-pointer">
                        <input type="radio" name="ticket_category_id" value="{{ $ticket->id }}"
                               class="peer sr-only"
                               @change="selectedTicket = tickets.find(t => t.id == {{ $ticket->id }})"
                               @checked(old('ticket_category_id') == $ticket->id)>
                        <div class="border-2 border-gray-200 rounded-lg p-4 peer-checked:border-nova-600 peer-checked:bg-nova-50 transition">
                            <div class="flex justify-between items-start gap-3">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900">{{ $ticket->name }}</p>
                                    @if ($ticket->description)
                                        <p class="text-xs text-gray-500 mt-1">{{ $ticket->description }}</p>
                                    @endif
                                    <p class="text-xs text-gray-500 mt-2">
                                        Sisa {{ $ticket->quota - $ticket->sold_count }} dari {{ $ticket->quota }} tiket
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-nova-600 text-lg">
                                        @if ($ticket->price == 0)
                                            GRATIS
                                        @else
                                            Rp {{ number_format($ticket->price, 0, ',', '.') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>

            @error('ticket_category_id')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- Upload Bukti Bayar (muncul kalau tiket berbayar) --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6"
             x-show="selectedTicket && selectedTicket.price > 0"
             x-cloak>
            <h2 class="font-bold text-gray-900 mb-2">Upload Bukti Pembayaran *</h2>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4 text-sm">
                <p class="text-blue-800 font-medium">Instruksi Pembayaran:</p>
                <ul class="text-blue-700 text-xs mt-1 space-y-1 ml-4 list-disc">
                    <li>Transfer ke <strong>BCA 1234567890</strong> a/n PT DIGANOVA</li>
                    <li>Jumlah: <strong x-text="'Rp ' + (selectedTicket?.price || 0).toLocaleString('id-ID')"></strong></li>
                    <li>Berita transfer: <strong>{{ auth()->user()->username }}</strong></li>
                    <li>Upload screenshot/foto bukti transfer di bawah</li>
                </ul>
            </div>

            <input type="file" name="payment_proof" accept="image/*"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nova-500 outline-none bg-white">
            <p class="text-xs text-gray-500 mt-1">JPG, PNG, WebP. Maks 3MB.</p>

            @error('payment_proof')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- Info: kalau tiket gratis --}}
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-sm text-green-800"
             x-show="selectedTicket && selectedTicket.price == 0"
             x-cloak>
            ✅ <strong>Tiket Gratis!</strong> Kamu tidak perlu upload bukti pembayaran. Tiket akan langsung aktif setelah submit.
        </div>

        {{-- Ringkasan --}}
        <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 text-sm">
            <div class="flex justify-between mb-1">
                <span class="text-gray-600">Total Bayar</span>
                <span class="font-bold text-lg text-nova-600" x-text="selectedTicket ? (selectedTicket.price == 0 ? 'GRATIS' : 'Rp ' + selectedTicket.price.toLocaleString('id-ID')) : '-'"></span>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex gap-3">
            <button type="submit"
                    class="flex-1 bg-nova-600 hover:bg-nova-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                Konfirmasi Pendaftaran
            </button>
            <a href="{{ route('event.events.show', $event->slug) }}"
               class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition">
                Batal
            </a>
        </div>

        @error('general')
            <p class="text-sm text-red-600 text-center">{{ $message }}</p>
        @enderror
    </form>
</div>

<style>[x-cloak]{display:none!important}</style>

@endsection