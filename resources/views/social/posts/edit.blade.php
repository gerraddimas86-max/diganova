@extends('social.layouts.app')

@section('title', 'Edit Post — DIGANOVA Social')

@section('content')

{{-- Custom Style untuk Animasi Aurora --}}
<style>
    @keyframes pulse-slow {
        0%, 100% { opacity: 0.3; transform: scale(1); }
        50% { opacity: 0.6; transform: scale(1.05); }
    }
    .animate-pulse-slow { animation: pulse-slow 8s ease-in-out infinite; }
</style>

{{-- WRAPPER BACKGROUND GELAP (Seamless dengan Navbar) --}}
<div class="relative w-full min-h-screen bg-[#090E39] -mt-[1px] overflow-hidden">
    
    {{-- ================= BACKGROUND IMAGE & EFFECTS ================= --}}
    <div class="absolute inset-0 pointer-events-none z-0 overflow-hidden">
        
        {{-- 1. Gambar Latar Belakang (Abstract Liquid) --}}
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=2564&auto=format&fit=crop')] bg-cover bg-center bg-fixed opacity-30 mix-blend-screen"></div>
        
        {{-- 2. Overlay Gradient --}}
        <div class="absolute inset-0 bg-gradient-to-b from-[#090E39]/80 via-[#090E39]/90 to-[#090E39]"></div>

        {{-- 3. Aurora Glow (Pendaran Cahaya) --}}
        <div class="absolute top-[-10%] right-[-10%] w-[40vw] h-[40vw] rounded-full bg-[#4382DF] blur-[150px] opacity-20 animate-pulse-slow mix-blend-screen"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[50vw] h-[50vw] rounded-full bg-[#4647AE] blur-[120px] opacity-20 animate-pulse-slow mix-blend-screen" style="animation-delay: 2s;"></div>
    </div>
    {{-- ================= END BACKGROUND EFFECTS ================= --}}

    <div class="relative z-10 max-w-2xl mx-auto px-4 py-6 sm:py-8">

        {{-- Back link --}}
        <div class="mb-5">
            <a href="{{ route('social.posts.index') }}"
               class="inline-flex items-center gap-1.5 text-sm font-bold text-[#4382DF] hover:text-white transition-colors group">
                <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Feed
            </a>
        </div>

        {{-- Main Card (Glassmorphism) --}}
        <div class="bg-[#112E81]/20 backdrop-blur-xl border border-[#4382DF]/20 rounded-3xl shadow-2xl overflow-hidden">

            {{-- Header --}}
            <div class="px-6 pt-6 pb-5 border-b border-[#4382DF]/20 bg-[#090E39]/50 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-[#4382DF] blur-3xl opacity-20 rounded-full pointer-events-none"></div>
                
                <div class="flex items-start gap-4 relative z-10">
                    <div class="w-12 h-12 rounded-xl bg-[#112E81] border border-[#4382DF]/30 flex items-center justify-center flex-shrink-0 shadow-lg shadow-[#4382DF]/10">
                        <svg class="w-6 h-6 text-[#4382DF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-extrabold text-white tracking-tight">Edit Post</h1>
                        <p class="text-sm text-slate-400 mt-0.5 font-medium">Perbarui isi postingan Anda</p>
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
                    <div class="flex items-center justify-between mb-2">
                        <label for="body" class="block text-sm font-bold text-white">Isi Postingan</label>
                        <span class="text-[11px] font-bold text-slate-400 bg-[#090E39]/80 px-2 py-0.5 rounded-md border border-[#4382DF]/30">
                            <span x-text="count">0</span>/500
                        </span>
                    </div>

                    <textarea name="body" id="body" x-model="body"
                              @input="count = body.length"
                              maxlength="500" rows="6" required
                              class="w-full px-4 py-3 border rounded-xl outline-none resize-y transition-all text-sm font-medium text-white placeholder-slate-500 bg-[#090E39]/50
                                     @error('body') border-red-400 focus:ring-4 focus:ring-red-500/20 focus:border-red-400
                                     @else border-[#4382DF]/30 focus:ring-4 focus:ring-[#4382DF]/20 focus:border-[#4382DF] @enderror"></textarea>
                    
                    @error('body')
                        <p class="flex items-center gap-1.5 text-xs font-semibold text-red-400 mt-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10A8 8 0 11.001 10 8 8 0 0118 10zm-9-4a1 1 0 012 0v4a1 1 0 11-2 0V6zm1 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2.5 mt-6 pt-5 border-t border-[#4382DF]/20">
                    <a href="{{ route('social.posts.index') }}"
                       class="inline-flex items-center justify-center px-6 py-2.5 bg-[#112E81]/40 hover:bg-[#112E81]/80
                              text-[#4382DF] hover:text-white rounded-xl font-bold text-sm transition-colors duration-200 border border-[#4382DF]/20">
                        Batal
                    </a>
                    <button type="submit" 
                            :disabled="submitting || !body.trim()"
                            class="inline-flex items-center justify-center gap-2 px-7 py-2.5
                                   bg-gradient-to-r from-[#112E81] to-[#4382DF] hover:from-[#4647AE] hover:to-[#4382DF]
                                   text-white rounded-xl font-bold text-sm transition-all duration-300
                                   shadow-lg shadow-[#4382DF]/20 hover:shadow-[#4382DF]/40 hover:-translate-y-0.5
                                   disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0 disabled:hover:shadow-none min-w-[100px]">
                        
                        <svg x-show="submitting" class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                        </svg>
                        
                        <span x-text="submitting ? 'Menyimpan...' : 'Simpan'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection