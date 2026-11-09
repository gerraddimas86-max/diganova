@extends('social.layouts.app')

@section('title', 'Grup — DIGANOVA Social')

@section('content')

@php
    $hasFilters = request()->filled('search') || request()->filled('visibility');
    $total = $groups->total();
@endphp

<div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 py-6">

    {{-- Header --}}
    <div class="flex justify-between items-start mb-6 flex-wrap gap-3">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <h1 class="f-display text-2xl font-bold text-white tracking-tight flex items-center gap-2">
                    <svg class="w-6 h-6 text-[#22D3EE]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Grup Komunitas
                </h1>
                <span class="text-xs font-black bg-[#22D3EE] text-[#1E3A8A] px-2.5 py-1 rounded-lg border-[2px] border-[#1E3A8A] shadow-sm">
                    {{ number_format($total) }}
                </span>
            </div>
            <p class="text-sm text-slate-400 font-medium mt-1">Temukan & buat komunitas sesuai minatmu</p>
        </div>
        @auth
            <a href="{{ route('social.groups.create') }}"
               class="inline-flex items-center gap-2 bg-[#FACC15] hover:bg-[#EAB308] text-[#1E3A8A] px-5 py-2.5 rounded-xl font-black text-sm transition-all duration-300 border-[3px] border-[#1E3A8A] shadow-[3px_3px_0_#1E3A8A] hover:-translate-y-1 hover:shadow-[5px_5px_0_#1E3A8A]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Grup Baru
            </a>
        @endauth
    </div>

    {{-- Filter --}}
    <form id="filterForm" method="GET" action="{{ route('social.groups.index') }}"
          class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-2xl shadow-sm p-4 mb-6">

        <div class="flex flex-col sm:flex-row gap-3">
            {{-- Search --}}
            <div class="relative flex-1">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500 pointer-events-none"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                </svg>
                <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                       placeholder="Cari nama atau deskripsi grup..."
                       autocomplete="off"
                       class="w-full pl-10 pr-10 py-2.5 border-2 border-[#1E3A8A] rounded-xl bg-[#152D54]
                              focus:ring-0 focus:border-[#3B82F6] outline-none text-sm font-medium text-white placeholder-slate-500 transition-all">
                @if (request()->filled('search'))
                    <a href="{{ route('social.groups.index', array_filter(request()->except('search'))) }}"
                       class="absolute right-2.5 top-1/2 -translate-y-1/2 p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-[#1E3A8A] transition-colors"
                       aria-label="Hapus pencarian">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                @endif
            </div>

            {{-- Visibility --}}
            <select name="visibility" id="visibilitySelect"
                    class="px-4 py-2.5 border-2 border-[#1E3A8A] rounded-xl bg-[#152D54]
                           focus:ring-0 focus:border-[#3B82F6]
                           outline-none text-sm font-bold text-white sm:w-48 transition-all cursor-pointer">
                <option value="" class="bg-[#0F1F3D]">Semua Visibilitas</option>
                <option value="public" @selected(request('visibility') === 'public') class="bg-[#0F1F3D]">🌐 Public</option>
                <option value="private" @selected(request('visibility') === 'private') class="bg-[#0F1F3D]">🔒 Private</option>
            </select>

            {{-- Submit --}}
            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 bg-[#3B82F6] hover:bg-[#2563EB]
                           text-white px-6 py-2.5 rounded-xl font-bold text-sm transition-all duration-300
                           disabled:opacity-60 disabled:cursor-not-allowed border-2 border-[#1E3A8A] shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M3 4a1 1 0 011-1h16a1 1 0 01.78 1.63l-6.22 7.78V20a1 1 0 01-1.55.83l-3.98-2.66A1 1 0 019 17.5v-4.31L2.22 4.63A1 1 0 013 4z"/>
                </svg>
                Filter
            </button>
        </div>

        {{-- Active filter chips --}}
        @if ($hasFilters)
            <div class="mt-3 pt-3 border-t-2 border-[#1E3A8A] flex flex-wrap items-center gap-2">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Filter aktif:</span>

                @if (request()->filled('search'))
                    <a href="{{ route('social.groups.index', array_filter(request()->except('search'))) }}"
                       class="inline-flex items-center gap-1.5 text-xs font-bold bg-[#1E3A8A] text-[#60A5FA]
                              px-3 py-1.5 rounded-lg hover:bg-[#2563EB] hover:text-white transition-colors border border-[#3B82F6]">
                        "{{ \Illuminate\Support\Str::limit(request('search'), 25) }}"
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                @endif

                @if (request()->filled('visibility'))
                    <a href="{{ route('social.groups.index', array_filter(request()->except('visibility'))) }}"
                       class="inline-flex items-center gap-1.5 text-xs font-bold bg-[#1E3A8A] text-[#60A5FA]
                              px-3 py-1.5 rounded-lg hover:bg-[#2563EB] hover:text-white transition-colors border border-[#3B82F6]">
                        {{ ucfirst(request('visibility')) }}
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                @endif

                <a href="{{ route('social.groups.index') }}"
                   class="ml-auto text-xs font-bold text-slate-400 hover:text-[#F43F5E] hover:underline transition-colors">
                    Reset semua
                </a>
            </div>
        @endif
    </form>

    {{-- Content --}}
    @if ($groups->isEmpty())

        @if ($hasFilters)
            <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-3xl p-12 text-center shadow-sm">
                <div class="w-16 h-16 mx-auto rounded-full bg-[#152D54] border-2 border-[#1E3A8A] flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-[#60A5FA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                    </svg>
                </div>
                <p class="f-display text-white font-bold text-xl">Tidak ada grup yang cocok</p>
                <p class="text-sm font-medium text-slate-400 mt-1">Coba ubah kata kunci atau hapus filter</p>
                <a href="{{ route('social.groups.index') }}"
                   class="inline-flex items-center gap-1.5 mt-5 text-sm text-[#60A5FA] hover:text-white hover:underline font-bold transition-colors">
                    Reset filter
                </a>
            </div>
        @else
            <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-3xl p-12 text-center shadow-sm">
                <div class="w-16 h-16 mx-auto rounded-full bg-[#152D54] flex items-center justify-center mb-4 border-2 border-[#1E3A8A]">
                    <svg class="w-8 h-8 text-[#FACC15]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a3 3 0 10-2-5.83"/>
                    </svg>
                </div>
                <p class="f-display text-white font-bold text-xl">Belum ada grup</p>
                <p class="text-sm font-medium text-slate-400 mt-1">Jadilah yang pertama membuat komunitas!</p>
                @auth
                    <a href="{{ route('social.groups.create') }}"
                       class="inline-flex items-center gap-2 mt-5 bg-[#FACC15] hover:bg-[#EAB308] text-[#1E3A8A] px-5 py-2.5 rounded-xl text-sm font-black transition-all duration-300 border-[3px] border-[#1E3A8A] shadow-[2px_2px_0_#1E3A8A]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/>
                        </svg>
                        Buat grup pertama
                    </a>
                @endauth
            </div>
        @endif

    @else

        @if ($hasFilters)
            <p class="text-sm font-medium text-slate-400 mb-4">
                Menampilkan <strong class="text-white">{{ $groups->count() }}</strong>
                dari <strong class="text-white">{{ number_format($total) }}</strong> grup
            </p>
        @endif

        {{-- Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($groups as $group)
                @php
                    $isJoined = in_array($group->id, $myGroupIds);
                    $initial  = mb_strtoupper(mb_substr($group->name, 0, 1));
                @endphp
                
                {{-- CARD STATIS (Tanpa efek hover terangkat/miring) --}}
                <a href="{{ route('social.groups.show', $group->slug) }}"
                   class="relative block bg-[#0F1F3D] rounded-2xl border-2 border-[#3B82F6] shadow-[0_0_0_4px_#1E3A8A] hover:border-[#60A5FA] transition-colors duration-200">
                    
                    <div class="p-5 flex flex-col h-full">
                        {{-- Header row --}}
                        <div class="flex items-start gap-3.5 mb-4">
                            {{-- Avatar --}}
                            <div class="w-12 h-12 rounded-xl p-[2px] bg-gradient-to-tr from-[#FACC15] to-[#22D3EE] flex-shrink-0">
                                <div class="w-full h-full bg-[#0F1F3D] rounded-lg flex items-center justify-center text-white text-lg font-black border-2 border-[#0F1F3D]">
                                    {{ $initial }}
                                </div>
                            </div>
                            
                            <div class="flex-1 min-w-0 pt-0.5">
                                {{-- Nama Grup selalu Kuning --}}
                                <h3 class="f-display font-bold text-[#FACC15] line-clamp-1 text-base">
                                    {{ $group->name }}
                                </h3>
                                <p class="text-[11px] font-medium text-slate-400 truncate mt-0.5">
                                    oleh <span class="text-slate-300">{{ $group->owner->name }}</span>
                                </p>
                            </div>
                            
                            @if ($group->isPrivate())
                                <span class="inline-flex items-center gap-1 text-[10px] bg-[#1E3A8A] text-[#60A5FA]
                                             border border-[#3B82F6] px-2 py-1 rounded-md font-bold uppercase tracking-wide flex-shrink-0">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    Private
                                </span>
                            @endif
                        </div>

                        {{-- Description --}}
                        <p class="text-sm line-clamp-2 flex-1 font-medium {{ $group->description ? 'text-slate-300' : 'italic text-slate-500' }}">
                            {{ $group->description ?: 'Tidak ada deskripsi' }}
                        </p>

                        {{-- Footer --}}
                        <div class="mt-4 pt-3 border-t-2 border-[#1E3A8A] flex justify-between items-center text-xs">
                            <span class="inline-flex items-center gap-1.5 text-slate-400 font-medium">
                                <svg class="w-4 h-4 text-[#22D3EE]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                          d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a3 3 0 10-2-5.83"/>
                                </svg>
                                <strong class="text-white">{{ number_format($group->members_count) }}</strong>
                                anggota
                            </span>
                            
                            @if ($isJoined)
                                <span class="inline-flex items-center gap-1 text-[#1E3A8A] font-bold bg-[#22D3EE] border-[2px] border-[#1E3A8A] px-2.5 py-1 rounded-md shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Joined
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-[#60A5FA] font-bold hover:text-white transition-colors">
                                    Lihat
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8 flex justify-center pagination-dark">
            {{ $groups->withQueryString()->links() }}
        </div>
    @endif
</div>

{{-- Loading overlay --}}
<div id="loadingOverlay" class="fixed inset-0 bg-[#060D1F]/80 backdrop-blur-sm z-50 hidden items-center justify-center transition-opacity">
    <div class="bg-[#0F1F3D] border-2 border-[#3B82F6] p-6 rounded-2xl shadow-[0_0_0_4px_#1E3A8A] flex flex-col items-center gap-3">
        <svg class="w-8 h-8 animate-spin text-[#FACC15]" viewBox="0 0 24 24" fill="none">
            <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-100" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
        </svg>
        <p class="text-sm font-bold text-white">Memuat data...</p>
    </div>
</div>

<style>
    .pagination-dark nav div:first-child { color: #94a3b8; }
    .pagination-dark nav div:first-child span { background-color: #0F1F3D; border-color: #1E3A8A; color: #94a3b8; }
    .pagination-dark nav div:first-child a { background-color: #0F1F3D; border-color: #1E3A8A; color: #cbd5e1; transition: all 0.2s; }
    .pagination-dark nav div:first-child a:hover { background-color: #1E3A8A; color: #ffffff; border-color: #3B82F6; }
    .pagination-dark nav div:first-child span[aria-current="page"] span { background-color: #FACC15 !important; border-color: #1E3A8A !important; color: #1E3A8A !important; font-weight: bold; }
</style>

<script>
(() => {
    const form = document.getElementById('filterForm');
    const search = document.getElementById('searchInput');
    const vis = document.getElementById('visibilitySelect');
    const overlay = document.getElementById('loadingOverlay');
    if (!form) return;
    let timer = null;
    if (search) {
        search.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(() => form.submit(), 600);
        });
        search.addEventListener('keydown', (e) => { if (e.key === 'Enter') clearTimeout(timer); });
    }
    if (vis) { vis.addEventListener('change', () => { clearTimeout(timer); form.submit(); }); }
    form.addEventListener('submit', () => {
        if (overlay) { overlay.classList.remove('hidden'); overlay.classList.add('flex'); }
    });
    window.addEventListener('pageshow', (e) => {
        if (e.persisted && overlay) { overlay.classList.add('hidden'); overlay.classList.remove('flex'); }
    });
})();
</script>

@endsection