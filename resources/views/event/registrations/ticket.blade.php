@extends('layouts.public')

@section('title', 'E-Ticket ' . $registration->order_code . ' — DIGANOVA')

@section('content')

<div class="max-w-md mx-auto px-4 py-8">

    {{-- Breadcrumb --}}
    <div class="mb-4">
        <a href="{{ route('event.registrations.show', $registration->order_code) }}"
           class="text-sm text-nova-600 hover:underline">
            ← Kembali ke detail registrasi
        </a>
    </div>

    {{-- E-Ticket Card --}}
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">

        {{-- Header (Branding) --}}
        <div class="bg-gradient-to-br from-nova-600 via-violet-500 to-crystal-400 p-6 text-white text-center">
            <p class="text-2xl font-bold tracking-tight">DIGANOVA</p>
            <p class="text-xs italic opacity-90 mt-1">"Plan It. Join It. Enjoy It."</p>
            <div class="mt-3 inline-block bg-white/20 backdrop-blur px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider">
                E-Ticket
            </div>
        </div>

        {{-- QR Code --}}
        <div class="px-6 pt-6 pb-4 flex flex-col items-center">
            <div class="bg-white p-4 rounded-xl border-2 border-gray-200">
                <div class="w-56 h-56 flex items-center justify-center">
                    {!! $qrSvg !!}
                </div>
            </div>
            <p class="mt-3 text-xs text-gray-500 uppercase tracking-wider">Kode E-Ticket</p>
            <p class="font-mono font-bold text-lg text-gray-900 tracking-wider">
                {{ $registration->e_ticket_code }}
            </p>
            <p class="mt-1 text-xs text-gray-500">Tunjukkan QR ini di pintu masuk venue</p>
        </div>

        {{-- Dashed Divider --}}
        <div class="relative">
            <div class="border-t-2 border-dashed border-gray-200"></div>
            <div class="absolute left-0 top-0 -translate-x-1/2 -translate-y-1/2 w-4 h-4 bg-gray-50 rounded-full"></div>
            <div class="absolute right-0 top-0 translate-x-1/2 -translate-y-1/2 w-4 h-4 bg-gray-50 rounded-full"></div>
        </div>

        {{-- Detail --}}
        <div class="p-6 space-y-3">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Event</p>
                <p class="font-bold text-gray-900 mt-0.5">{{ $registration->event->title }}</p>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Tanggal</p>
                    <p class="text-sm font-medium">{{ $registration->event->start_date->format('d M Y') }}</p>
                    <p class="text-xs text-gray-500">{{ $registration->event->start_date->format('H:i') }} WIB</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Kategori</p>
                    <p class="text-sm font-medium">{{ $registration->ticketCategory->name }}</p>
                </div>
            </div>

            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Lokasi</p>
                <p class="text-sm font-medium">{{ $registration->event->location }}</p>
                @if ($registration->event->address)
                    <p class="text-xs text-gray-500">{{ $registration->event->address }}</p>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-3 pt-3 border-t border-gray-100">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Atas Nama</p>
                    <p class="text-sm font-medium">{{ $registration->user->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Order Code</p>
                    <p class="text-xs font-mono font-semibold">{{ $registration->order_code }}</p>
                </div>
            </div>
        </div>

        {{-- Check-in Status --}}
        @if ($registration->checked_in)
            <div class="bg-green-50 border-t border-green-200 p-4 text-center">
                <p class="text-sm font-semibold text-green-800">✅ Sudah Check-in</p>
                <p class="text-xs text-green-700 mt-0.5">
                    {{ $registration->checked_in_at->format('d M Y H:i') }}
                </p>
            </div>
        @endif
    </div>

    {{-- Actions --}}
    <div class="mt-6 space-y-3">
        <a href="{{ route('event.registrations.ticket.download', $registration->order_code) }}"
           class="block w-full bg-nova-600 hover:bg-nova-700 text-white text-center px-4 py-3 rounded-lg font-semibold transition">
            📥 Download PDF
        </a>

        <button type="button"
                onclick="window.print()"
                class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-700 text-center px-4 py-3 rounded-lg font-medium transition">
            🖨️ Print / Save as Image
        </button>
    </div>

    {{-- Info --}}
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4 text-xs text-blue-800">
        <p class="font-semibold mb-1">💡 Tips:</p>
        <ul class="space-y-1 ml-4 list-disc">
            <li>Tingkatkan kecerahan layar saat menunjukkan QR</li>
            <li>Bisa juga tunjukkan PDF yang sudah didownload</li>
            <li>Kalau QR tidak terbaca, panitia bisa input kode manual</li>
        </ul>
    </div>

</div>

<style>
@media print {
    body * { visibility: hidden; }
    .bg-white, .bg-white * { visibility: visible; }
    .bg-white { position: absolute; left: 0; top: 0; width: 100%; }
    a, button { display: none !important; }
}
</style>

@endsection