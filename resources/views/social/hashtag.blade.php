@extends('social.layouts.app')

@section('title', '#' . $hashtag->tag . ' — DIGANOVA Social')

@section('content')

{{-- Tombol Kembali --}}
<div class="mb-6">
    <a href="{{ route('social.posts.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-400 hover:text-[#4382DF] transition-colors group">
        <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Feed
    </a>
</div>

{{-- Header Hashtag --}}
<div class="bg-[#090E39]/80 backdrop-blur-xl border border-[#4647AE]/30 rounded-3xl p-6 sm:p-8 mb-8 relative overflow-hidden shadow-lg">
    {{-- Efek Glow di Background --}}
    <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#4382DF]/10 rounded-full blur-3xl"></div>
    
    <div class="relative z-10 flex items-center gap-5">
        {{-- Ikon Hashtag --}}
        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-br from-[#112E81] to-[#4382DF] flex items-center justify-center text-white font-black text-3xl sm:text-4xl shadow-xl shadow-[#4382DF]/20 transform rotate-3 hover:rotate-0 transition-transform duration-300">
            #
        </div>
        
        {{-- Info Hashtag --}}
        <div>
            <h1 class="text-3xl sm:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 tracking-tight">
                #{{ $hashtag->tag }}
            </h1>
            <p class="text-sm font-medium text-[#4382DF] mt-1.5 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
                {{ number_format($hashtag->post_count, 0, ',', '.') }} postingan
            </p>
        </div>
    </div>
</div>

{{-- Daftar Postingan --}}
@if ($posts->isEmpty())
    {{-- Empty State --}}
    <div class="bg-[#090E39]/60 border border-[#4647AE]/30 backdrop-blur-xl rounded-3xl p-12 text-center">
        <div class="w-20 h-20 mx-auto bg-[#112E81]/30 rounded-full flex items-center justify-center mb-4">
            {{-- Icon Tag (Pengganti 🏷️) --}}
            <svg class="w-10 h-10 text-[#4382DF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
        </div>
        <h3 class="text-white font-bold text-lg mb-1">Belum ada postingan</h3>
        <p class="text-slate-400 text-sm max-w-xs mx-auto">Jadilah yang pertama menggunakan hashtag #{{ $hashtag->tag }} untuk memulai tren!</p>
        
        @auth
            <a href="{{ route('social.posts.index') }}" class="inline-flex items-center gap-2 mt-6 bg-gradient-to-r from-[#112E81] to-[#4382DF] hover:from-[#4647AE] hover:to-[#4382DF] text-white px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 shadow-lg shadow-[#4382DF]/20 hover:scale-105">
                Buat Postingan
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        @endauth
    </div>
@else
    {{-- List Post --}}
    <div class="space-y-4">
        @foreach ($posts as $post)
            {{-- Wrapper dark theme untuk post card --}}
            <div class="bg-[#090E39]/80 border border-[#4647AE]/30 rounded-2xl overflow-hidden hover:border-[#4382DF]/50 transition-colors duration-300 shadow-lg">
                @include('social.posts._card', ['post' => $post])
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        <div class="pagination-dark">
            {{ $posts->links() }}
        </div>
    </div>
@endif

{{-- Custom Styling for Pagination (Laravel Default) --}}
<style>
    .pagination-dark nav div:first-child { color: #94a3b8; }
    .pagination-dark nav div:first-child span { background-color: #090E39; border-color: #4647AE; color: #94a3b8; }
    .pagination-dark nav div:first-child a { background-color: #090E39; border-color: #4647AE; color: #cbd5e1; transition: all 0.2s; }
    .pagination-dark nav div:first-child a:hover { background-color: #112E81; color: #ffffff; border-color: #4382DF; }
    .pagination-dark nav div:first-child span[aria-current="page"] span { background-color: #4382DF !important; border-color: #4382DF !important; color: #ffffff !important; }
    .pagination-dark nav .sm\:hidden span, .pagination-dark nav .sm\:hidden a { background-color: #090E39; border-color: #4647AE; color: #cbd5e1; }
</style>

@endsection