@extends('playground.layouts.app')

@section('title', 'Mencari Lawan — DIGANOVA Playground')

@section('content')

<div class="max-w-md mx-auto py-12"
     x-data="queuePage()"
     x-init="init()">

    <div class="bg-slate-900 border border-cyan-500/30 rounded-2xl p-8 text-center">

        {{-- Animated Icon --}}
        <div class="relative mb-6">
            <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-br from-cyan-500 to-violet-600 flex items-center justify-center text-5xl animate-pulse">
                ⚔️
            </div>
            <div class="absolute inset-0 w-24 h-24 mx-auto rounded-full border-4 border-cyan-500/30 animate-ping"></div>
        </div>

        <h1 class="text-2xl font-bold text-white mb-2">Mencari Lawan...</h1>
        <p class="text-sm text-slate-400">Matchmaking berdasarkan rank <strong class="text-cyan-400 capitalize">{{ auth()->user()->rank }}</strong></p>

        {{-- Timer --}}
        <div class="mt-6 bg-slate-800 rounded-xl p-4">
            <p class="text-xs text-slate-500 uppercase tracking-wide">Waktu Menunggu</p>
            <p class="text-3xl font-bold text-cyan-400 font-mono mt-1" x-text="formatTime(seconds)"></p>
            <p class="text-xs text-slate-500 mt-1">Posisi: <span class="text-cyan-400 font-semibold">#<span x-text="position"></span></span></p>
        </div>

        {{-- Dots animation --}}
        <div class="flex justify-center gap-1.5 mt-4">
            <div class="w-2 h-2 rounded-full bg-cyan-500 animate-bounce" style="animation-delay: 0ms"></div>
            <div class="w-2 h-2 rounded-full bg-cyan-500 animate-bounce" style="animation-delay: 150ms"></div>
            <div class="w-2 h-2 rounded-full bg-cyan-500 animate-bounce" style="animation-delay: 300ms"></div>
        </div>

        {{-- Cancel --}}
        <form method="POST" action="{{ route('play.battle.pvp.cancel') }}" class="mt-8">
            @csrf
            <button type="submit"
                    class="w-full bg-red-500/20 hover:bg-red-500/30 text-red-400 border border-red-500/30 px-6 py-2.5 rounded-lg font-medium text-sm transition">
                Batal Cari Lawan
            </button>
        </form>

        {{-- Info --}}
        <div class="mt-6 text-xs text-slate-500">
            <p>💡 Tip: Semakin lama menunggu, semakin besar kemungkinan ketemu lawan</p>
        </div>
    </div>
</div>

<script>
function queuePage() {
    return {
        seconds: 0,
        position: 1,
        pollInterval: null,
        timerInterval: null,
        stopped: false,

        init() {
            this.timerInterval = setInterval(() => {
                if (!this.stopped) this.seconds++;
            }, 1000);

            this.poll();
            this.pollInterval = setInterval(() => this.poll(), 3000);
        },

        async poll() {
            if (this.stopped) return;
            try {
                const res = await fetch('{{ route('play.battle.pvp.status') }}', {
                    headers: { 'Accept': 'application/json' },
                });
                const data = await res.json();

                if (data.status === 'matched' && data.battle_id) {
                    this.stopped = true;
                    clearInterval(this.pollInterval);
                    clearInterval(this.timerInterval);
                    window.location.href = '/battle/pvp/' + data.battle_id;
                } else if (data.status === 'waiting') {
                    this.position = data.position || 1;
                    if (data.wait_seconds) this.seconds = data.wait_seconds;
                } else if (data.status === 'not_queued') {
                    window.location.href = '{{ route('play.home') }}';
                }
            } catch (e) {}
        },

        formatTime(s) {
            const m = Math.floor(s / 60);
            const sec = s % 60;
            return `${String(m).padStart(2, '0')}:${String(sec).padStart(2, '0')}`;
        },
    };
}
</script>

@endsection