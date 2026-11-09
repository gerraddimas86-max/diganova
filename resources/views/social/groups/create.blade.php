@extends('social.layouts.app')

@section('title', 'Buat Grup — DIGANOVA Social')

@section('content')

<div class="relative z-10 max-w-2xl mx-auto px-4 py-6 sm:py-8">

    {{-- Back link --}}
    <div class="mb-5">
        <a href="{{ route('social.groups.index') }}"
           class="inline-flex items-center gap-1.5 text-sm font-bold text-[#60A5FA] hover:text-white transition-colors group">
            <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke daftar grup
        </a>
    </div>

    {{-- Main Card (Double Border) --}}
    <div class="bg-[#0F1F3D] border-2 border-[#3B82F6] shadow-[0_0_0_4px_#1E3A8A] rounded-3xl overflow-hidden hover:shadow-[0_0_0_4px_#60A5FA,0_15px_30px_rgba(59,130,246,0.2)] transition-all duration-300">

        {{-- Header --}}
        <div class="px-6 pt-6 pb-5 border-b-2 border-[#1E3A8A] bg-[#152D54] relative overflow-hidden">
            <div class="flex items-start gap-4 relative z-10">
                <div class="w-12 h-12 rounded-xl bg-[#1E3A8A] border-2 border-[#3B82F6] flex items-center justify-center flex-shrink-0 shadow-sm">
                    <svg class="w-6 h-6 text-[#FACC15]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                              d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a3 3 0 10-2-5.83"/>
                    </svg>
                </div>
                <div>
                    <h1 class="f-display text-2xl font-bold text-white tracking-tight">Buat Grup Baru</h1>
                    <p class="text-sm text-slate-400 mt-0.5 font-medium">Kumpulkan orang-orang dengan minat sama</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form id="createGroupForm" method="POST" action="{{ route('social.groups.store') }}"
              class="px-6 py-6 space-y-6" novalidate>
            @csrf

            {{-- Nama Grup --}}
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="name" class="block text-sm font-bold text-white">
                        Nama Grup <span class="text-[#F43F5E]">*</span>
                    </label>
                    <span class="text-[11px] font-bold text-[#60A5FA] bg-[#152D54] px-2 py-0.5 rounded-md border-2 border-[#1E3A8A]">
                        <span id="nameCount">0</span>/80
                    </span>
                </div>
                <input type="text" id="name" name="name"
                       value="{{ old('name') }}"
                       required maxlength="80"
                       placeholder="Contoh: Cosplayer Jakarta"
                       class="w-full px-4 py-3 border-2 rounded-xl outline-none transition-all text-sm font-medium text-white placeholder-slate-500 bg-[#152D54]
                              @error('name') border-[#F43F5E] focus:border-[#F43F5E]
                              @else border-[#1E3A8A] focus:border-[#3B82F6] @enderror">
                @error('name')
                    <p class="flex items-center gap-1.5 text-xs font-semibold text-[#F43F5E] mt-2">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Slug --}}
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="slug" class="block text-sm font-bold text-white">
                        Slug (URL)
                    </label>
                    <button type="button" id="regenSlug"
                            class="text-[11px] font-bold text-[#FACC15] hover:text-white transition-colors hidden flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Regenerate
                    </button>
                </div>
                <div class="flex items-center border-2 rounded-xl overflow-hidden transition-all bg-[#152D54]
                            @error('slug') border-[#F43F5E] focus-within:border-[#F43F5E]
                            @else border-[#1E3A8A] focus-within:border-[#3B82F6] @enderror">
                    <span class="px-4 py-3 bg-[#1E3A8A]/50 text-slate-400 text-sm border-r-2 border-[#1E3A8A] font-mono font-medium">
                        /groups/
                    </span>
                    <input type="text" id="slug" name="slug"
                           value="{{ old('slug') }}"
                           maxlength="80"
                           placeholder="cosplayer-jakarta"
                           class="flex-1 px-4 py-3 outline-none text-sm font-mono bg-transparent text-white placeholder-slate-500">
                </div>
                <p class="text-[11px] font-medium text-slate-400 mt-2 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-[#60A5FA]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Otomatis dari nama. Bisa diedit manual.
                </p>
                @error('slug')
                    <p class="flex items-center gap-1.5 text-xs font-semibold text-[#F43F5E] mt-2">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="description" class="block text-sm font-bold text-white">Deskripsi</label>
                    <span class="text-[11px] font-bold text-[#60A5FA] bg-[#152D54] px-2 py-0.5 rounded-md border-2 border-[#1E3A8A]">
                        <span id="descCount">0</span>/1000
                    </span>
                </div>
                <textarea id="description" name="description" rows="4" maxlength="1000"
                          placeholder="Ceritakan tentang grup ini — tujuan, aturan, atau aktivitas utamanya..."
                          class="w-full px-4 py-3 border-2 rounded-xl outline-none resize-y transition-all text-sm font-medium text-white placeholder-slate-500 bg-[#152D54]
                                 @error('description') border-[#F43F5E] focus:border-[#F43F5E]
                                 @else border-[#1E3A8A] focus:border-[#3B82F6] @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="flex items-center gap-1.5 text-xs font-semibold text-[#F43F5E] mt-2">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Visibilitas --}}
            <div>
                <label class="block text-sm font-bold text-white mb-2">
                    Visibilitas <span class="text-[#F43F5E]">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    
                    {{-- Public --}}
                    <label class="cursor-pointer group">
                        <input type="radio" name="visibility" value="public" class="peer sr-only" @checked(old('visibility', 'public') === 'public')>
                        <div class="relative border-2 rounded-xl p-4 transition-all duration-300
                                    border-[#1E3A8A] bg-[#152D54]
                                    hover:border-[#3B82F6] hover:bg-[#1E3A8A]/50
                                    peer-checked:border-[#FACC15] peer-checked:bg-[#1E3A8A] peer-checked:shadow-[0_0_15px_rgba(250,204,21,0.2)]">
                            
                            <div class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 border-[#1E3A8A] flex items-center justify-center
                                        peer-checked:border-[#FACC15] peer-checked:bg-[#FACC15] transition-colors duration-200">
                                <svg class="w-3 h-3 text-[#1E3A8A] opacity-0 peer-checked:opacity-100 transition-opacity duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div class="flex items-start gap-3 pr-6">
                                <div class="w-9 h-9 rounded-lg bg-[#22D3EE]/20 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform border-2 border-[#22D3EE]/40">
                                    <span class="text-lg">🌐</span>
                                </div>
                                <div>
                                    <p class="font-bold text-white text-sm">Public</p>
                                    <p class="text-[11px] text-slate-400 mt-1 leading-relaxed font-medium">Siapa saja bisa langsung join.</p>
                                </div>
                            </div>
                        </div>
                    </label>

                    {{-- Private --}}
                    <label class="cursor-pointer group">
                        <input type="radio" name="visibility" value="private" class="peer sr-only" @checked(old('visibility') === 'private')>
                        <div class="relative border-2 rounded-xl p-4 transition-all duration-300
                                    border-[#1E3A8A] bg-[#152D54]
                                    hover:border-[#3B82F6] hover:bg-[#1E3A8A]/50
                                    peer-checked:border-[#FACC15] peer-checked:bg-[#1E3A8A] peer-checked:shadow-[0_0_15px_rgba(250,204,21,0.2)]">
                            
                            <div class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 border-[#1E3A8A] flex items-center justify-center
                                        peer-checked:border-[#FACC15] peer-checked:bg-[#FACC15] transition-colors duration-200">
                                <svg class="w-3 h-3 text-[#1E3A8A] opacity-0 peer-checked:opacity-100 transition-opacity duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div class="flex items-start gap-3 pr-6">
                                <div class="w-9 h-9 rounded-lg bg-[#F43F5E]/20 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform border-2 border-[#F43F5E]/40">
                                    <span class="text-lg">🔒</span>
                                </div>
                                <div>
                                    <p class="font-bold text-white text-sm">Private</p>
                                    <p class="text-[11px] text-slate-400 mt-1 leading-relaxed font-medium">Perlu approval owner.</p>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
                @error('visibility')
                    <p class="flex items-center gap-1.5 text-xs font-semibold text-[#F43F5E] mt-2">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-6 border-t-2 border-[#1E3A8A]">
                <a href="{{ route('social.groups.index') }}"
                   class="inline-flex items-center justify-center px-6 py-2.5 bg-[#152D54] hover:bg-[#1E3A8A]
                          text-slate-300 hover:text-white rounded-xl font-bold text-sm transition-colors duration-200 border-2 border-[#1E3A8A]">
                    Batal
                </a>
                <button type="submit" id="submitBtn"
                        class="inline-flex items-center justify-center gap-2 px-7 py-2.5
                               bg-[#FACC15] hover:bg-[#EAB308] text-[#1E3A8A]
                               rounded-xl font-black text-sm transition-all duration-300
                               border-[3px] border-[#1E3A8A] shadow-[3px_3px_0_#1E3A8A] hover:-translate-y-0.5 hover:shadow-[5px_5px_0_#1E3A8A]
                               disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0 disabled:hover:shadow-[3px_3px_0_#1E3A8A]">
                    <svg id="submitIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/>
                    </svg>
                    <svg id="submitSpinner" class="w-5 h-5 animate-spin hidden" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                    </svg>
                    <span id="submitLabel">Buat Grup</span>
                </button>
            </div>
        </form>
    </div>

    <p class="text-[11px] font-medium text-slate-500 text-center mt-6">
        Dengan membuat grup, kamu menjadi <strong class="text-[#FACC15]">owner</strong> dan bertanggung jawab atas moderasinya.
    </p>
</div>

<script>
(() => {
    const nameEl   = document.getElementById('name');
    const slugEl   = document.getElementById('slug');
    const descEl   = document.getElementById('description');
    const nameCnt  = document.getElementById('nameCount');
    const descCnt  = document.getElementById('descCount');
    const regenBtn = document.getElementById('regenSlug');
    const form     = document.getElementById('createGroupForm');
    const submitBtn  = document.getElementById('submitBtn');
    const submitIcon = document.getElementById('submitIcon');
    const submitSpin = document.getElementById('submitSpinner');
    const submitLbl  = document.getElementById('submitLabel');

    const slugify = (str) => (str || '')
        .toString().normalize('NFKD').replace(/[\u0300-\u036f]/g, '')
        .toLowerCase().trim().replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-').replace(/-+/g, '-')
        .replace(/^-+|-+$/g, '').slice(0, 80);

    const updateCounters = () => {
        nameCnt.textContent = nameEl.value.length;
        descCnt.textContent = descEl.value.length;
    };

    let slugTouched = slugEl.value.trim().length > 0;
    slugEl.addEventListener('input', () => {
        slugTouched = true;
        regenBtn.classList.toggle('hidden', !nameEl.value.trim());
    });

    nameEl.addEventListener('input', () => {
        if (!slugTouched) slugEl.value = slugify(nameEl.value);
        regenBtn.classList.toggle('hidden', !nameEl.value.trim());
        updateCounters();
    });

    regenBtn.addEventListener('click', () => {
        slugEl.value = slugify(nameEl.value);
        slugTouched = false;
    });

    descEl.addEventListener('input', updateCounters);
    updateCounters();
    regenBtn.classList.toggle('hidden', !nameEl.value.trim());

    form.addEventListener('submit', (e) => {
        if (!nameEl.value.trim()) {
            e.preventDefault();
            nameEl.focus();
            return;
        }
        submitBtn.disabled = true;
        submitIcon.classList.add('hidden');
        submitSpin.classList.remove('hidden');
        submitLbl.textContent = 'Membuat...';
    });

    window.addEventListener('pageshow', (e) => {
        if (e.persisted) {
            submitBtn.disabled = false;
            submitIcon.classList.remove('hidden');
            submitSpin.classList.add('hidden');
            submitLbl.textContent = 'Buat Grup';
        }
    });
})();
</script>

@endsection