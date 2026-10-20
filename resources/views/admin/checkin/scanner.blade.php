@extends('admin.layouts.app')

@section('title', 'Check-in: ' . $event->title . ' — Admin')

@section('content')

<div x-data="scannerApp()" x-cloak>

    {{-- Header --}}
    <div class="mb-6 flex justify-between items-start flex-wrap gap-3">
        <div>
            <a href="{{ route('admin.checkin.index') }}" class="text-sm text-nova-600 hover:underline">
                ← Pilih Event Lain
            </a>
            <h1 class="text-2xl font-bold text-gray-900 mt-2">Check-in Scanner</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $event->title }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-100 px-5 py-3">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Progress</p>
            <p class="text-lg font-bold text-gray-900 mt-0.5">
                <span class="text-nova-600" x-text="stats.checked_in">{{ $stats['checked_in'] }}</span>
                <span class="text-gray-400">/</span>
                <span x-text="stats.total_verified">{{ $stats['total_verified'] }}</span>
            </p>
            <p class="text-xs text-gray-500 mt-0.5">peserta hadir</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT: Scanner --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Camera Area --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="font-bold text-gray-900 mb-3">📷 Scan QR Code</h2>

                <div id="qr-reader"
                     class="mx-auto rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center border-2 border-dashed border-gray-300"
                     style="min-height: 300px; max-width: 420px;">
                </div>

                <div class="mt-4 flex gap-2 justify-center">
                    <button type="button" @click="startScan()"
                            x-show="!scanning"
                            class="bg-nova-600 hover:bg-nova-700 text-white px-6 py-2.5 rounded-lg font-medium transition">
                        📸 Mulai Scan
                    </button>
                    <button type="button" @click="stopScan()"
                            x-show="scanning"
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg font-medium transition">
                        ⏹ Stop Scan
                    </button>
                </div>

                <p class="text-xs text-center text-gray-500 mt-3" x-show="!scanning">
                    Klik "Mulai Scan" dan izinkan akses kamera browser
                </p>
                <p class="text-xs text-center text-green-600 font-medium mt-3" x-show="scanning">
                    📸 Kamera aktif — arahkan QR ke kotak kamera
                </p>
            </div>

            {{-- Manual Input Fallback --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="font-bold text-gray-900 mb-3">⌨️ Input Manual (Fallback)</h2>
                <p class="text-xs text-gray-500 mb-3">
                    Gunakan jika QR tidak terbaca (layar redup / QR rusak).
                </p>
                <form @submit.prevent="submitCode(manualCode, 'manual')" class="flex gap-2">
                    <input type="text" x-model="manualCode"
                           placeholder="Ketik kode E-Ticket (contoh: A1B2C3D4...)"
                           autocomplete="off"
                           class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nova-500 outline-none font-mono uppercase tracking-wider">
                    <button type="submit" :disabled="processing || !manualCode.trim()"
                            class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-2.5 rounded-lg font-medium transition disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!processing">Proses</span>
                        <span x-show="processing">...</span>
                    </button>
                </form>
            </div>

            {{-- Recent Check-ins --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="font-bold text-gray-900 mb-3">✅ Check-in Terbaru</h2>
                <ul class="space-y-2 text-sm">
                    <template x-for="(c, i) in recentCheckins" :key="i">
                        <li class="flex items-center gap-3 border-b border-gray-100 pb-2 last:border-0">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-nova-500 to-crystal-400 flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                                 x-text="c.avatar_initial"></div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 text-sm truncate" x-text="c.name"></p>
                                <p class="text-xs text-gray-500 truncate" x-text="c.ticket"></p>
                            </div>
                            <span class="text-xs text-gray-400 whitespace-nowrap" x-text="c.checked_in_at"></span>
                        </li>
                    </template>
                    <li x-show="recentCheckins.length === 0" class="text-gray-500 text-xs py-6 text-center">
                        Belum ada check-in
                    </li>
                </ul>
            </div>
        </div>

        {{-- RIGHT: Sidebar --}}
        <div class="space-y-6">

            {{-- Petunjuk --}}
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
                <h3 class="font-bold text-blue-900 mb-2 flex items-center gap-2">📋 Petunjuk</h3>
                <ol class="space-y-2 text-xs text-blue-800 ml-4 list-decimal">
                    <li>Klik <strong>Mulai Scan</strong> — izinkan akses kamera</li>
                    <li>Arahkan QR e-ticket peserta ke kotak</li>
                    <li>Ada <strong>beep suara</strong> & overlay saat berhasil</li>
                    <li>Jika QR rusak, pakai <strong>input manual</strong></li>
                    <li>Scan berikutnya bisa langsung setelah 3 detik</li>
                </ol>
            </div>

            {{-- Info Event --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="font-bold text-gray-900 mb-3">Info Event</h3>
                <dl class="space-y-2 text-xs">
                    <div>
                        <dt class="text-gray-500">Judul</dt>
                        <dd class="font-medium text-gray-900">{{ $event->title }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Tanggal</dt>
                        <dd class="font-medium">{{ $event->start_date->format('d M Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Lokasi</dt>
                        <dd class="font-medium">{{ $event->location }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Status</dt>
                        <dd class="font-medium capitalize">{{ $event->status }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Link ke detail event --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
                <a href="{{ route('admin.events.show', $event) }}"
                   class="text-sm text-nova-600 hover:underline">
                    Lihat detail event →
                </a>
            </div>
        </div>
    </div>

    {{-- RESULT OVERLAY --}}
    <div x-show="result" x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         :class="result && result.status === 'success' ? 'bg-green-500/30' : 'bg-red-500/30'"
         @click="result = null">
        <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 text-center"
             :class="result && result.status === 'success' ? 'border-4 border-green-500' : 'border-4 border-red-500'"
             @click.stop>

            <div x-show="result && result.status === 'success'" class="text-6xl mb-3">✅</div>
            <div x-show="result && result.status !== 'success'" class="text-6xl mb-3">❌</div>

            <h2 class="text-xl font-bold mb-2"
                :class="result && result.status === 'success' ? 'text-green-700' : 'text-red-700'"
                x-text="result && result.status === 'success' ? 'Check-in Berhasil!' : 'Gagal Check-in'"></h2>

            <p class="text-sm text-gray-600 mb-4" x-text="result ? result.message : ''"></p>

            <div x-show="result && result.registration" class="bg-gray-50 rounded-lg p-3 mb-4 text-left">
                <p class="text-xs text-gray-500">Peserta:</p>
                <p class="font-bold text-gray-900" x-text="result && result.registration ? result.registration.name : ''"></p>
                <p class="text-xs text-gray-500 mt-1" x-text="result && result.registration ? result.registration.ticket : ''"></p>
            </div>

            <button type="button" @click="result = null"
                    class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-2 rounded-lg font-medium text-sm">
                Tutup
            </button>
        </div>
    </div>

</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
function scannerApp() {
    return {
        scanning: false,
        html5QrCode: null,
        result: null,
        processing: false,
        manualCode: '',
        stats: {
            checked_in: {{ $stats['checked_in'] }},
            total_verified: {{ $stats['total_verified'] }},
        },
        recentCheckins: @json($recentCheckins),

        async startScan() {
            if (this.html5QrCode) return;

            this.html5QrCode = new Html5Qrcode("qr-reader");

            try {
                await this.html5QrCode.start(
                    { facingMode: "environment" },
                    { fps: 10, qrbox: { width: 250, height: 250 } },
                    (decodedText) => this.submitCode(decodedText, 'qr'),
                    () => {} // ignore scan errors
                );
                this.scanning = true;
            } catch (err) {
                alert('Tidak bisa akses kamera.\n\nPastikan:\n1. Browser mengizinkan akses kamera\n2. Membuka via HTTPS atau localhost\n\nError: ' + err);
                this.html5QrCode = null;
                this.scanning = false;
            }
        },

        async stopScan() {
            if (!this.html5QrCode) return;
            try {
                await this.html5QrCode.stop();
                this.html5QrCode.clear();
            } catch (e) {}
            this.html5QrCode = null;
            this.scanning = false;
        },

        async submitCode(code, method = 'qr') {
            code = (code || '').trim().toUpperCase();
            if (!code || this.processing) return;

            this.processing = true;

            try {
                const response = await fetch('{{ route('admin.checkin.process', $event) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ e_ticket_code: code, method: method }),
                });

                const data = await response.json();
                this.result = data;
                this.playBeep(data.status === 'success');

                if (data.status === 'success') {
                    this.stats.checked_in++;
                    if (data.registration) {
                        data.registration.checked_in_at = new Date().toLocaleTimeString('id-ID', {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                        this.recentCheckins.unshift(data.registration);
                        if (this.recentCheckins.length > 5) this.recentCheckins.pop();
                    }
                    this.manualCode = '';
                }

                setTimeout(() => { this.result = null; }, 3500);

            } catch (err) {
                this.result = {
                    status: 'error',
                    message: 'Gagal terhubung ke server. Coba lagi.',
                };
                this.playBeep(false);
            } finally {
                this.processing = false;
            }
        },

        playBeep(success) {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.frequency.value = success ? 880 : 250;
                osc.type = 'sine';
                gain.gain.setValueAtTime(0.3, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.25);
                osc.start();
                osc.stop(ctx.currentTime + 0.25);
            } catch (e) {}
        }
    };
}
</script>

<style>
[x-cloak] { display: none !important; }
#qr-reader { min-height: 300px; }
#qr-reader video { border-radius: 8px; width: 100% !important; height: auto !important; }
</style>

@endsection