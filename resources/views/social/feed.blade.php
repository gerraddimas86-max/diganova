@extends('social.layouts.app')

@section('title', 'Feed — DIGANOVA Social')

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
        --accent-cyan: #22D3EE;
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
        --accent-cyan: #0891B2;
        --shadow-color: #BFDBFE;
    }

    body { background-color: var(--bg-page) !important; color: var(--text-primary); }

    html .bg-\[\#0F1F3D\] { background-color: var(--bg-card) !important; }
    html .bg-\[\#152D54\] { background-color: var(--bg-card-alt) !important; }
    html .bg-\[\#060D1F\] { background-color: var(--bg-inner) !important; }
    html .bg-\[\#1E3A8A\] { background-color: var(--bg-card-alt) !important; }
    html .bg-\[\#F8FAFC\] { background-color: var(--bg-card-alt) !important; }

    html .border-\[\#1E3A8A\] { border-color: var(--border-card) !important; }
    html .border-\[\#3B82F6\] { border-color: var(--accent-blue) !important; }
    html .border-\[\#22D3EE\] { border-color: var(--accent-blue) !important; }
    html .border-\[\#60A5FA\] { border-color: var(--accent-blue-soft) !important; }

    html .text-white, html .f-display, html h1, html h2, html h3, html h4, html p {
        color: var(--text-primary) !important;
    }
    html .text-slate-300, html .text-slate-400 { color: var(--text-muted) !important; }
    html .placeholder\:text-slate-500::placeholder { color: var(--text-muted) !important; }

    html.light-mode .bg-\[\#3B82F6\], html.light-mode .bg-\[\#3B82F6\] *,
    html.light-mode .bg-\[\#2563EB\], html.light-mode .bg-\[\#2563EB\] * {
        color: #FFFFFF !important;
    }

    html.light-mode .text-\[\#3B82F6\], html.light-mode .text-\[\#60A5FA\], html.light-mode .text-\[\#2563EB\] { color: #2563EB !important; }
    html:not(.light-mode) .text-\[\#3B82F6\], html:not(.light-mode) .text-\[\#60A5FA\] { color: #60A5FA !important; }

    html .shadow-\[5px_5px_0_\#060D1F\],
    html .shadow-\[4px_4px_0_\#060D1F\],
    html .shadow-\[3px_3px_0_\#1E3A8A\],
    html .shadow-\[2px_2px_0_\#1E3A8A\] { box-shadow: 5px 5px 0 var(--shadow-color) !important; }
    
    html .hover\:shadow-\[6px_6px_0_\#060D1F\]:hover,
    html .hover\:shadow-\[5px_5px_0_\#1E3A8A\]:hover { box-shadow: 6px 6px 0 var(--shadow-color) !important; }

    .dark-card-wrapper article {
        background: var(--bg-card) !important;
        border: 3px solid var(--border-card) !important;
        box-shadow: 5px 5px 0 var(--shadow-color) !important;
        border-radius: 20px !important;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .dark-card-wrapper article:hover {
        transform: translateY(-4px);
        box-shadow: 7px 7px 0 var(--shadow-color) !important;
    }

    html:not(.light-mode) .dark-card-wrapper article,
    html:not(.light-mode) .dark-card-wrapper article * {
        color: #FFFFFF !important;
    }
    html:not(.light-mode) .dark-card-wrapper article .text-\[\#3B82F6\],
    html:not(.light-mode) .dark-card-wrapper article .text-\[\#60A5FA\] { color: #60A5FA !important; }

    html.light-mode .dark-card-wrapper article,
    html.light-mode .dark-card-wrapper article * {
        color: #000000 !important;
    }
    html.light-mode .dark-card-wrapper article .text-\[\#3B82F6\],
    html.light-mode .dark-card-wrapper article .text-\[\#60A5FA\],
    html.light-mode .dark-card-wrapper article .text-\[\#112E81\] { color: #2563EB !important; }

    html.light-mode .dark-card-wrapper article a[href*="/hashtag/"],
    html.light-mode .dark-card-wrapper article span[class*="bg-[#FACC15]"] {
        background-color: #DBEAFE !important;
        color: #1E3A8A !important; 
        border-color: #93C5FD !important;
    }

    .pagination-dark nav div:first-child { color: var(--text-muted); }
    .pagination-dark nav div:first-child span { background-color: var(--bg-card); border-color: var(--border-card); color: var(--text-muted); }
    .pagination-dark nav div:first-child a { background-color: var(--bg-card); border-color: var(--border-card); color: var(--text-secondary); transition: all 0.2s; }
    .pagination-dark nav div:first-child a:hover { background-color: var(--accent-blue); color: #FFFFFF; border-color: var(--accent-blue); }
    .pagination-dark nav div:first-child span[aria-current="page"] span { 
        background-color: var(--accent-blue) !important; 
        border-color: var(--accent-blue) !important; 
        color: #FFFFFF !important; 
        font-weight: bold; 
    }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 grid grid-cols-1 lg:grid-cols-4 gap-8 relative z-10">
    <div class="lg:col-span-3 space-y-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="f-display text-3xl font-bold text-white tracking-tight flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-[#3B82F6] text-white flex items-center justify-center shadow-[3px_3px_0_#1E3A8A] border-[2px] border-[#1E3A8A] -rotate-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l6 6v10a2 2 0 01-2 2z"/></svg>
                    </span>
                    Feed
                </h1>
                <p class="text-sm font-medium text-slate-400 mt-2 ml-12">Apa yang sedang terjadi di DIGANOVA hari ini?</p>
            </div>
        </div>

        @auth
            <div class="bg-[#0F1F3D] border-[3px] border-[#1E3A8A] rounded-[2rem] p-5 shadow-[5px_5px_0_#060D1F] relative group">
                <form method="POST" action="{{ route('social.posts.store') }}" 
                      x-data="{ body: '', count: 0, submitting: false }" 
                      @submit="if (submitting) { $event.preventDefault(); } else { submitting = true; }">
                    @csrf
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-[#3B82F6] border-[3px] border-[#1E3A8A] flex items-center justify-center text-white font-black flex-shrink-0 shadow-[2px_2px_0_#1E3A8A]">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                        <div class="flex-1">
                            <!-- ✅ FIX: Enter (tanpa Shift) → submit form. Shift+Enter → baris baru -->
                            <textarea name="body" 
                                      x-model="body" 
                                      @input="count = body.length" 
                                      @keydown.enter="if (!$event.shiftKey) { $event.preventDefault(); if (body.trim() && !submitting) $el.form.requestSubmit(); }"
                                      maxlength="500" rows="3" required 
                                      placeholder="Apa yang kamu pikirkan? Bagikan momenmu..." 
                                      class="w-full bg-transparent border-0 focus:ring-0 outline-none resize-none text-base text-white placeholder:text-slate-500 font-medium"></textarea>
                            <div class="flex justify-between items-center pt-3 border-t-[2px] border-[#1E3A8A] mt-2">
                                <div class="text-xs font-bold text-slate-400 flex items-center gap-1.5">
                                    <svg class="w-5 h-5 text-[#60A5FA]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    <span x-text="count"></span> / 500
                                </div>
                                <button type="submit" 
                                        :disabled="!body.trim() || submitting" 
                                        class="bg-[#3B82F6] border-[3px] border-[#1E3A8A] text-white px-6 py-2.5 rounded-xl text-sm font-black transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed shadow-[3px_3px_0_#1E3A8A] hover:-translate-y-1 hover:shadow-[5px_5px_0_#1E3A8A]">
                                    <span x-show="!submitting" class="flex items-center gap-2">Posting</span>
                                    <span x-show="submitting" class="flex items-center gap-2">Posting...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @endauth

        @if ($posts->isEmpty())
            <div class="bg-[#0F1F3D] border-[3px] border-[#3B82F6] rounded-3xl p-12 text-center shadow-[5px_5px_0_#1E3A8A] relative overflow-hidden">
                <h3 class="f-display text-white font-bold text-2xl mb-2">Belum ada post nih!</h3>
                <p class="text-slate-400 text-sm font-medium">Jadilah yang pertama memulai percakapan!</p>
            </div>
        @else
            <div class="space-y-8 mt-4">
                @foreach ($posts as $post)
                    <div class="relative mt-4">
                        <div class="relative bg-[#0F1F3D] border-[3px] border-[#1E3A8A] rounded-[26px] p-1.5 shadow-[5px_5px_0_#060D1F] hover:-translate-y-1 transition-transform duration-300">
                            <div class="dark-card-wrapper rounded-[20px] overflow-hidden relative z-10">
                                @include('social.posts._card', ['post' => $post])
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="pt-8"><div class="pagination-dark">{{ $posts->links() }}</div></div>
        @endif
    </div>

    <aside class="hidden lg:block lg:col-span-1">
        <div class="sticky top-24 space-y-6">
            @auth
                <a href="{{ route('social.profile.show', auth()->user()->username) }}" class="block bg-[#0F1F3D] border-[3px] border-[#1E3A8A] rounded-[2rem] overflow-hidden shadow-[4px_4px_0_#060D1F] relative group hover:-translate-y-1 hover:shadow-[6px_6px_0_#060D1F] transition-all duration-300 cursor-pointer">
                    <div class="h-20 bg-[#3B82F6] relative"></div>
                    <div class="px-5 pb-5 relative">
                        <div class="flex justify-between items-end -mt-8 mb-3">
                            <div class="w-14 h-14 rounded-2xl p-[3px] bg-[#3B82F6] shadow-[2px_2px_0_#1E3A8A]">
                                <div class="w-full h-full rounded-xl bg-[#152D54] flex items-center justify-center text-white font-black text-xl border-2 border-[#0F1F3D]">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                            </div>
                            <span class="text-[10px] font-black text-white bg-[#3B82F6] px-3 py-1.5 rounded-lg border-[2px] border-[#1E3A8A] shadow-[2px_2px_0_#1E3A8A] group-hover:-translate-y-0.5 transition-all">Profil</span>
                        </div>
                        <div class="min-w-0 mb-4">
                            <p class="font-bold text-white truncate text-base f-display group-hover:text-[#3B82F6] transition-colors">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ '@' . auth()->user()->username }}</p>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-center text-xs">
                            <div class="bg-[#152D54] rounded-xl py-2 border-[2px] border-[#1E3A8A] group-hover:bg-[#1E3A8A] transition-colors">
                                <p class="font-black text-[#3B82F6] text-sm f-display">{{ auth()->user()->posts()->count() }}</p>
                                <p class="text-[9px] text-slate-300 uppercase tracking-wider mt-0.5 font-bold">Post</p>
                            </div>
                            <div class="bg-[#152D54] rounded-xl py-2 border-[2px] border-[#1E3A8A] group-hover:bg-[#1E3A8A] transition-colors">
                                <p class="font-black text-[#3B82F6] text-sm f-display">{{ auth()->user()->followers()->count() }}</p>
                                <p class="text-[9px] text-slate-300 uppercase tracking-wider mt-0.5 font-bold">Followers</p>
                            </div>
                            <div class="bg-[#152D54] rounded-xl py-2 border-[2px] border-[#1E3A8A] group-hover:bg-[#1E3A8A] transition-colors">
                                <p class="font-black text-[#3B82F6] text-sm f-display">{{ auth()->user()->following()->count() }}</p>
                                <p class="text-[9px] text-slate-300 uppercase tracking-wider mt-0.5 font-bold">Following</p>
                            </div>
                        </div>
                    </div>
                </a>
            @endauth
        </div>
    </aside>
</div>

@endsection