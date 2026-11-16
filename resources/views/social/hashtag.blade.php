@extends('social.layouts.app')

@section('title', '#' . $hashtag->tag . ' — DIGANOVA Social')

@section('content')

<style>
    /* 🌗 LIGHT MODE OVERRIDES */
    html.light-mode body { background-color: #F8FAFC !important; }
    html.light-mode .bg-\[\#090E39\]\/80 { background-color: #FFFFFF !important; border-color: #CBD5E1 !important; }
    html.light-mode .bg-\[\#112E81\] { background-color: #F1F5F9 !important; }
    html.light-mode .text-white, html.light-mode h1, html.light-mode h3 { color: #0F172A !important; }
    html.light-mode .text-slate-400 { color: #475569 !important; }
    html.light-mode .text-transparent { background-image: linear-gradient(to right, #0F172A, #475569) !important; -webkit-background-clip: text !important; }
    html.light-mode .bg-\[\#090E39\]\/80 { background-color: #FFFFFF !important; border-color: #E2E8F0 !important; }
    html.light-mode .dark-card-wrapper article { background: #FFFFFF !important; }
    html.light-mode .dark-card-wrapper article * { color: #0F172A !important; }
    html.light-mode .dark-card-wrapper article .text-gray-500,
    html.light-mode .dark-card-wrapper article .text-gray-600 { color: #64748B !important; }
</style>

<!-- Floating Theme Toggle -->
<div class="fixed bottom-6 right-6 z-50">
    <button id="theme-toggle" class="bg-white p-4 rounded-full shadow-lg border-2 border-[#3B82F6] hover:scale-110 transition-transform">
        <svg class="w-6 h-6 text-yellow-500 sun-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        <svg class="w-6 h-6 text-cyan-400 moon-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
    </button>
</div>

<div class="mb-6">
    <a href="{{ route('social.posts.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-400 hover:text-[#4382DF] transition-colors group">
        <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Feed
    </a>
</div>

<div class="bg-[#090E39]/80 backdrop-blur-xl border border-[#4647AE]/30 rounded-3xl p-6 sm:p-8 mb-8 relative overflow-hidden shadow-lg">
    <div class="relative z-10 flex items-center gap-5">
        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-br from-[#112E81] to-[#4382DF] flex items-center justify-center text-white font-black text-3xl sm:text-4xl shadow-xl shadow-[#4382DF]/20 transform rotate-3 hover:rotate-0 transition-transform duration-300">#</div>
        <div>
            <h1 class="text-3xl sm:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 tracking-tight">#{{ $hashtag->tag }}</h1>
            <p class="text-sm font-medium text-[#4382DF] mt-1.5 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                {{ number_format($hashtag->post_count, 0, ',', '.') }} postingan
            </p>
        </div>
    </div>
</div>

@if ($posts->isEmpty())
    <div class="bg-[#090E39]/60 border border-[#4647AE]/30 backdrop-blur-xl rounded-3xl p-12 text-center">
        <h3 class="text-white font-bold text-lg mb-1">Belum ada postingan</h3>
        <p class="text-slate-400 text-sm max-w-xs mx-auto">Jadilah yang pertama menggunakan hashtag #{{ $hashtag->tag }} untuk memulai tren!</p>
    </div>
@else
    <div class="space-y-4">
        @foreach ($posts as $post)
            <div class="bg-[#090E39]/80 border border-[#4647AE]/30 rounded-2xl overflow-hidden hover:border-[#4382DF]/50 transition-colors duration-300 shadow-lg">
                @include('social.posts._card', ['post' => $post])
            </div>
        @endforeach
    </div>
    <div class="mt-8"><div class="pagination-dark">{{ $posts->links() }}</div></div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggle = document.getElementById('theme-toggle');
        const sunIcon = document.querySelector('.sun-icon');
        const moonIcon = document.querySelector('.moon-icon');
        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.add('light-mode');
            if(sunIcon) sunIcon.classList.remove('hidden');
            if(moonIcon) moonIcon.classList.add('hidden');
        }
        if(toggle) {
            toggle.addEventListener('click', () => {
                document.documentElement.classList.toggle('light-mode');
                if (document.documentElement.classList.contains('light-mode')) {
                    localStorage.setItem('theme', 'light');
                    if(sunIcon) sunIcon.classList.remove('hidden');
                    if(moonIcon) moonIcon.classList.add('hidden');
                } else {
                    localStorage.setItem('theme', 'dark');
                    if(sunIcon) sunIcon.classList.add('hidden');
                    if(moonIcon) moonIcon.classList.remove('hidden');
                }
            });
        }
    });
</script>
@endsection