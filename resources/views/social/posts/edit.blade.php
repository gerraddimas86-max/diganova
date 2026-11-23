@extends('social.layouts.app')

@section('title', 'Edit Post — DIGANOVA Social')

@section('content')

<style>
    /* ===================== THEME VARIABLES ===================== */
    html {
        --bg-page: #000000;
        --bg-card: #0D0D0D;
        --bg-card-alt: #171717;
        --bg-inner: #050505;
        --border-card: #262626;
        --border-strong: #3F3F3F;
        
        --text-primary: #FFFFFF;
        --text-secondary: #E2E8F0;
        --text-muted: #94A3B8;
        
        --accent-blue: #3B82F6;
        --accent-blue-soft: #60A5FA;
        --shadow-color: #1F1F1F;
    }
    
    html.light-mode {
        --bg-page: #FFFFFF;
        --bg-card: #EFF6FF;
        --bg-card-alt: #DBEAFE;
        --bg-inner: #FFFFFF;
        --border-card: #BFDBFE;
        --border-strong: #93C5FD;
        
        --text-primary: #000000;
        --text-secondary: #334155;
        --text-muted: #64748B;
        
        --accent-blue: #2563EB; 
        --accent-blue-soft: #3B82F6;
        --shadow-color: #BFDBFE;
    }

    body { background-color: var(--bg-page) !important; color: var(--text-primary); }

    /* Utilitas Warna */
    html .bg-\[\#0F1F3D\] { background-color: var(--bg-card) !important; }
    html .bg-\[\#152D54\] { background-color: var(--bg-card-alt) !important; }
    html .bg-\[\#060D1F\] { background-color: var(--bg-inner) !important; }
    
    html .border-\[\#1E3A8A\] { border-color: var(--border-card) !important; }
    html .border-\[\#3B82F6\] { border-color: var(--accent-blue) !important; }
    
    html .text-white, html .f-display, html h1, html h2, html h3, html h4, html p { color: var(--text-primary) !important; }
    html .text-slate-300, html .text-slate-400 { color: var(--text-muted) !important; }

    /* Shadow 3D Neobrutalism */
    html .shadow-\[5px_5px_0_\#060D1F\],
    html .shadow-\[3px_3px_0_\#1E3A8A\] { box-shadow: 5px 5px 0 var(--shadow-color) !important; }
</style>

{{-- WRAPPER UTAMA --}}
<div class="relative w-full min-h-screen -mt-[1px] overflow-hidden">
    
    <div class="relative z-10 max-w-2xl mx-auto px-4 py-8 sm:py-12">

        {{-- Back link --}}
        <div class="mb-6">
            <a href="{{ route('social.posts.index') }}"
               class="inline-flex items-center gap-1.5 text-sm font-bold text-[#3B82F6] hover:opacity-80 transition-opacity group">
                <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Feed
            </a>
        </div>

        {{-- Main Card --}}
        <div class="bg-[#0F1F3D] border-[3px] border-[#1E3A8A] rounded-[2rem] shadow-[5px_5px_0_#060D1F] overflow-hidden relative group">

            {{-- Header --}}
            <div class="px-6 pt-6 pb-5 border-b-[3px] border-[#1E3A8A] bg-[#152D54] relative overflow-hidden">
                <div class="flex items-start gap-4 relative z-10">
                    <div class="w-12 h-12 rounded-xl bg-[#3B82F6] border-[3px] border-[#1E3A8A] flex items-center justify-center flex-shrink-0 shadow-[2px_2px_0_#1E3A8A]">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="f-display text-xl font-extrabold text-white tracking-tight">Edit Post</h1>
                        <p class="text-sm text-slate-400 mt-1 font-medium">Perbarui isi postingan Anda</p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('social.posts.update', $post) }}"
                  class="px-6 py-6"
                  x-data="{ 
                      body: {{ json_encode(old('body', $post->body)) }}, 
                      count: {{ strlen(old('body', $post->body)) }},
                      submitting: false
                  }"
                  @submit="submitting = true">
                @csrf
                @method('PUT')

                <div>
                    <div class="flex items-center justify-between mb-3">
                        <label for="body" class="block text-sm font-bold text-white">Isi Postingan</label>
                        <span class="text-[11px] font-bold text-slate-400 bg-[#060D1F] px-2.5 py-1 rounded-lg border-2 border-[#1E3A8A]">
                            <span x-text="count">0</span>/500
                        </span>
                    </div>

                    <textarea name="body" id="body" x-model="body"
                              @input="count = body.length"
                              maxlength="500" rows="6" required
                              class="w-full px-4 py-3 border-[3px] rounded-xl outline-none resize-y transition-all text-sm font-medium text-white placeholder-slate-500 bg-[#060D1F]
                                     @error('body') border-red-400 focus:ring-0 focus:border-red-500
                                     @else border-[#1E3A8A] focus:ring-0 focus:border-[#3B82F6] @enderror"></textarea>
                    
                    @error('body')
                        <p class="flex items-center gap-1.5 text-xs font-semibold text-red-500 mt-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10A8 8 0 11.001 10 8 8 0 0118 10zm-9-4a1 1 0 012 0v4a1 1 0 11-2 0V6zm1 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-8 pt-5 border-t-[3px] border-[#1E3A8A]">
                    <a href="{{ route('social.posts.index') }}"
                       class="inline-flex items-center justify-center px-6 py-2.5 bg-[#152D54] hover:bg-[#1E3A8A]
                              text-slate-300 hover:text-white rounded-xl font-bold text-sm transition-colors duration-200 border-[3px] border-[#1E3A8A]">
                        Batal
                    </a>
                    <button type="submit" 
                            :disabled="submitting || !body.trim()"
                            class="inline-flex items-center justify-center gap-2 px-7 py-2.5
                                   bg-[#3B82F6] hover:bg-[#2563EB]
                                   text-white rounded-xl font-black text-sm transition-all duration-300
                                   border-[3px] border-[#1E3A8A] shadow-[3px_3px_0_#1E3A8A] hover:-translate-y-1
                                   disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0 disabled:shadow-none min-w-[100px]">
                        
                        <svg x-show="submitting" class="w-4 h-4 animate-spin text-white" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                        </svg>
                        
                        <span x-text="submitting ? 'Menyimpan...' : 'Simpan'" class="font-bold"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection