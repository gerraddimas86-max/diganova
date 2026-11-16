@extends('social.layouts.app')

@section('title', $group->name . ' — DIGANOVA Social')

@section('content')

@php
    $isOwner    = $group->owner_id === auth()->id();
    $initial    = mb_strtoupper(mb_substr($group->name, 0, 1));
    $memberCount = $members->count();
    $pendingCount = $pendingMembers->count();
    $adminCount  = $members->filter(fn ($m) => $m->pivot->role === 'admin')->count();
@endphp

<style>
    /* ===================== THEME VARIABLES ===================== */
    html {
        --bg-page: #000000; --bg-card: #0D0D0D; --bg-card-alt: #171717; --bg-inner: #050505;
        --border-card: #262626; --border-strong: #3F3F3F;
        --text-primary: #FFFFFF; --text-secondary: #E2E8F0; --text-muted: #94A3B8;
        --accent-blue: #3B82F6; --accent-blue-soft: #60A5FA; --shadow-color: #1F1F1F;
    }
    html.light-mode {
        --bg-page: #FFFFFF; --bg-card: #EFF6FF; --bg-card-alt: #DBEAFE; --bg-inner: #FFFFFF;
        --border-card: #BFDBFE; --border-strong: #93C5FD;
        --text-primary: #000000; --text-secondary: #334155; --text-muted: #64748B;
        --accent-blue: #2563EB; --accent-blue-soft: #3B82F6; --shadow-color: #BFDBFE;
    }

    body { background-color: var(--bg-page) !important; color: var(--text-primary); }

    /* Override Tailwind backgrounds */
    html .bg-\[\#0F1F3D\] { background-color: var(--bg-card) !important; }
    html .bg-\[\#152D54\] { background-color: var(--bg-card-alt) !important; }
    html .bg-\[\#060D1F\] { background-color: var(--bg-inner) !important; }

    /* Override borders */
    html .border-\[\#1E3A8A\] { border-color: var(--border-card) !important; }
    html .border-\[\#3B82F6\] { border-color: var(--accent-blue) !important; }
    html .border-\[\#22D3EE\] { border-color: var(--accent-blue) !important; }
    html .border-\[\#60A5FA\] { border-color: var(--accent-blue-soft) !important; }

    /* Global text */
    html .text-white, html .f-display, html h1, html h2, html h3, html h4, html p {
        color: var(--text-primary) !important;
    }
    html .text-slate-300, html .text-slate-400, html .text-slate-500 {
        color: var(--text-muted) !important;
    }

    /* Protect button text */
    html.light-mode .bg-\[\#3B82F6\], html.light-mode .bg-\[\#3B82F6\] *,
    html.light-mode .bg-\[\#2563EB\], html.light-mode .bg-\[\#2563EB\] * {
        color: #FFFFFF !important;
    }

    /* Accent text */
    html.light-mode .text-\[\#3B82F6\], html.light-mode .text-\[\#60A5FA\], html.light-mode .text-\[\#2563EB\] { color: #2563EB !important; }
    html:not(.light-mode) .text-\[\#3B82F6\], html:not(.light-mode) .text-\[\#60A5FA\] { color: #60A5FA !important; }

    /* Inputs */
    html input, html textarea, html select { color: var(--text-primary) !important; }

    /* Shadows */
    html .shadow-\[0_0_0_4px_\#1E3A8A\] { box-shadow: 0 0 0 4px var(--shadow-color) !important; }
    html .shadow-\[3px_3px_0_\#1E3A8A\] { box-shadow: 3px 3px 0 var(--shadow-color) !important; }
    html .shadow-\[2px_2px_0_\#1E3A8A\] { box-shadow: 2px 2px 0 var(--shadow-color) !important; }
    html .shadow-sm, html .shadow-md { box-shadow: 3px 3px 0 var(--shadow-color) !important; }
</style>

<div class="relative z-10 max-w-5xl mx-auto px-4 py-6">
    {{-- Flash messages --}}
    @if (session('success'))
        <div class="mb-4 bg-[#0F1F3D] border-2 border-[#3B82F6] shadow-sm text-white rounded-xl px-4 py-3 text-sm flex items-start gap-2">
            <svg class="w-5 h-5 flex-shrink-0 text-[#3B82F6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 bg-[#0F1F3D] border-2 border-[#3B82F6] shadow-sm text-[#3B82F6] rounded-xl px-4 py-3 text-sm flex items-start gap-2">
            <svg class="w-5 h-5 flex-shrink-0 text-[#3B82F6]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10A8 8 0 11.001 10 8 8 0 0118 10zm-9-4a1 1 0 012 0v4a1 1 0 11-2 0V6zm1 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
            <span class="font-bold">{{ session('error') }}</span>
        </div>
    @endif

    {{-- HERO HEADER --}}
    <div class="relative overflow-hidden rounded-3xl bg-[#0F1F3D] border-2 border-[#3B82F6] shadow-[0_0_0_4px_#1E3A8A] text-white mb-6">
        <div class="relative p-6 md:p-8">
            <div class="flex flex-col md:flex-row md:items-start gap-5 md:gap-6">
                <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl p-[3px] bg-[#3B82F6] flex-shrink-0 shadow-lg">
                    <div class="w-full h-full bg-[#0F1F3D] rounded-xl flex items-center justify-center text-3xl font-extrabold text-white border-2 border-[#0F1F3D]">
                        {{ $initial }}
                    </div>
                </div>

                <div class="flex-1 min-w-0 md:pt-1">
                    <div class="flex items-center gap-3 flex-wrap mb-2">
                        <h1 class="f-display text-2xl md:text-3xl font-bold tracking-tight text-white break-words">{{ $group->name }}</h1>
                        @if ($group->isPrivate())
                            <span class="inline-flex items-center gap-1.5 text-[10px] md:text-xs bg-[#1E3A8A] border-2 border-[#3B82F6] px-2.5 py-1 rounded-full font-bold text-[#60A5FA] tracking-wider shadow-sm">PRIVATE</span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-[10px] md:text-xs bg-[#1E3A8A] border-2 border-[#3B82F6] px-2.5 py-1 rounded-full font-bold text-[#60A5FA] tracking-wider shadow-sm">PUBLIC</span>
                        @endif
                    </div>

                    <div class="flex items-center gap-3 md:gap-4 text-xs md:text-sm text-slate-400 flex-wrap font-medium">
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-[#3B82F6]" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
                            <strong class="text-white text-sm md:text-base">{{ $memberCount }}</strong> Anggota
                        </span>
                        <span class="text-slate-600">|</span>
                        <span>Dibuat oleh <strong class="text-white">{{ $group->owner->name }}</strong></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ACTION BAR --}}
    <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-2xl shadow-sm p-4 mb-6">
        <div class="flex flex-wrap gap-3 items-center">
            @auth
                @if ($isAdmin)
                    <a href="{{ route('social.groups.edit', $group->slug) }}" class="inline-flex items-center gap-2 bg-[#152D54] hover:bg-[#1E3A8A] text-[#3B82F6] border-2 border-[#1E3A8A] px-4 py-2 rounded-xl font-bold text-sm transition-colors duration-200">
                        Edit
                    </a>
                    <form method="POST" action="{{ route('social.groups.destroy', $group->slug) }}" data-confirm-submit data-confirm-title="Hapus grup ini?" data-confirm-message="Seluruh data grup, anggota, dan riwayat akan hilang permanen." data-confirm-variant="danger" data-confirm-ok="Ya, Hapus">
                        @csrf @method('DELETE')
                        <button class="inline-flex items-center gap-2 bg-[#3B82F6]/10 hover:bg-[#3B82F6]/20 border-2 border-[#3B82F6]/30 text-[#3B82F6] px-4 py-2 rounded-xl font-bold text-sm transition-colors duration-200">Hapus</button>
                    </form>

                    @if ($pendingCount > 0)
                        <button type="button" data-tab-jump="pending" class="inline-flex items-center gap-2 bg-[#3B82F6]/10 hover:bg-[#3B82F6]/20 border-2 border-[#3B82F6]/30 text-[#3B82F6] px-4 py-2 rounded-xl font-bold text-sm transition-colors duration-200">
                            {{ $pendingCount }} Permintaan
                        </button>
                    @endif
                @endif

                <div class="flex-1 hidden md:block"></div>

                @if ($isMember)
                    @if (!$isOwner)
                        <form method="POST" action="{{ route('social.groups.leave', $group->slug) }}" data-confirm-submit data-confirm-title="Keluar dari grup?" data-confirm-message="Kamu bisa bergabung lagi nanti." data-confirm-variant="warning" data-confirm-ok="Ya, Keluar">
                            @csrf
                            <button class="inline-flex items-center gap-2 bg-[#152D54] border-2 border-[#1E3A8A] hover:bg-[#1E3A8A] text-slate-300 hover:text-white px-4 py-2 rounded-xl font-bold text-sm transition-colors duration-200">Keluar</button>
                        </form>
                    @else
                        <span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-bold text-white bg-[#3B82F6] border-[2px] border-[#1E3A8A] rounded-xl shadow-sm">Anda Owner</span>
                    @endif
                @elseif ($hasPending)
                    <div class="flex-1 w-full md:w-auto bg-[#3B82F6]/10 border-2 border-[#3B82F6]/30 rounded-xl px-4 py-2 text-sm text-[#3B82F6] flex items-center justify-center md:justify-start gap-2 font-bold">
                        <span>Menunggu persetujuan owner</span>
                    </div>
                @else
                    <form method="POST" action="{{ route('social.groups.join', $group->slug) }}" class="flex-1 w-full md:w-auto mt-2 md:mt-0">
                        @csrf
                        <button class="w-full inline-flex items-center justify-center gap-2 bg-[#3B82F6] hover:bg-[#2563EB] text-white px-6 py-2.5 rounded-xl font-black text-sm transition-all duration-300 border-[3px] border-[#1E3A8A] shadow-[3px_3px_0_#1E3A8A] hover:-translate-y-0.5">
                            Join Grup
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('login') }}" class="w-full md:w-auto text-center inline-flex items-center justify-center gap-2 bg-[#3B82F6] hover:bg-[#2563EB] text-white px-6 py-2.5 rounded-xl font-black text-sm transition-all duration-300 border-[3px] border-[#1E3A8A] shadow-[3px_3px_0_#1E3A8A] hover:-translate-y-0.5">Login untuk Join</a>
            @endauth
        </div>
    </div>

    {{-- TABS & CONTENT --}}
    <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-2xl shadow-sm overflow-hidden">
        <div class="border-b-2 border-[#1E3A8A] flex overflow-x-auto bg-[#152D54]">
            <button data-tab="members" class="tab-btn whitespace-nowrap px-5 md:px-6 py-4 text-xs md:text-sm font-bold border-b-4 transition-colors duration-200 text-white border-[#3B82F6] bg-[#1E3A8A]/30">
                Anggota <span class="ml-1.5 text-[10px] md:text-xs bg-[#3B82F6] text-white px-2 py-0.5 rounded-md font-black">{{ $memberCount }}</span>
            </button>
            @if ($isAdmin)
                <button data-tab="pending" class="tab-btn whitespace-nowrap px-5 md:px-6 py-4 text-xs md:text-sm font-bold border-b-4 transition-colors duration-200 text-slate-400 border-transparent hover:text-white hover:bg-[#1E3A8A]/20">
                    Permintaan @if ($pendingCount > 0) <span class="ml-1.5 text-[10px] md:text-xs bg-[#3B82F6] text-white px-2 py-0.5 rounded-md font-black animate-pulse">{{ $pendingCount }}</span> @endif
                </button>
            @endif
            <button data-tab="about" class="tab-btn whitespace-nowrap px-5 md:px-6 py-4 text-xs md:text-sm font-bold border-b-4 transition-colors duration-200 text-slate-400 border-transparent hover:text-white hover:bg-[#1E3A8A]/20">Tentang</button>
        </div>

        {{-- Panel: Members --}}
        <div data-panel="members" class="p-4 md:p-6">
            <div class="space-y-2">
                @forelse ($members as $member)
                    @php $role = $group->owner_id === $member->id ? 'owner' : $member->pivot->role; @endphp
                    <div class="flex items-center gap-3 md:gap-4 p-2 md:p-3 rounded-xl hover:bg-[#152D54] border-2 border-transparent hover:border-[#1E3A8A] transition-all group">
                        <a href="{{ route('social.profile.show', $member->username) }}" class="flex-shrink-0 relative">
                            <div class="w-10 h-10 md:w-11 md:h-11 rounded-full p-[2px] bg-[#3B82F6] group-hover:rotate-12 transition-transform">
                                <div class="w-full h-full rounded-full border-2 border-[#0F1F3D] overflow-hidden bg-[#0F1F3D]">
                                    <x-avatar :avatar="$member->avatar" :user="$member" size="w-full h-full" />
                                </div>
                            </div>
                        </a>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap mb-0.5">
                                <a href="{{ route('social.profile.show', $member->username) }}" class="font-bold text-white hover:text-[#3B82F6] text-sm truncate transition-colors">{{ $member->name }}</a>
                                @if ($role === 'owner')
                                    <span class="text-[9px] md:text-[10px] bg-[#3B82F6] text-white px-2 py-0.5 rounded-md font-black tracking-wide uppercase">Owner</span>
                                @elseif ($role === 'admin')
                                    <span class="text-[9px] md:text-[10px] bg-[#60A5FA] text-white px-2 py-0.5 rounded-md font-black tracking-wide uppercase">Admin</span>
                                @endif
                            </div>
                            <p class="text-[11px] md:text-xs text-slate-400 font-medium truncate">{{ '@' . $member->username }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-400 text-center py-8 font-medium">Belum ada anggota</p>
                @endforelse
            </div>
        </div>

        {{-- Panel: Pending --}}
        @if ($isAdmin)
            <div data-panel="pending" class="p-4 md:p-6 hidden">
                @if ($pendingCount === 0)
                    <div class="text-center py-10">
                        <p class="text-sm text-slate-400 font-bold">Tidak ada permintaan bergabung</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($pendingMembers as $pending)
                            <div class="bg-[#3B82F6]/10 border-2 border-[#3B82F6]/30 rounded-xl p-3 md:p-4 flex flex-col sm:flex-row sm:items-center gap-3 md:gap-4">
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    <x-avatar :avatar="$pending->avatar" :user="$pending" size="w-10 h-10 md:w-11 md:h-11 border-2 border-[#0F1F3D] shadow-sm" />
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-white text-sm truncate">{{ $pending->name }}</p>
                                        <p class="text-[11px] md:text-xs text-[#3B82F6] truncate font-medium">{{ '@' . $pending->username }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-2 w-full sm:w-auto">
                                    <form method="POST" action="{{ route('social.groups.approve', [$group->slug, $pending->id]) }}" class="flex-1 sm:flex-none">
                                        @csrf
                                        <button class="w-full bg-[#3B82F6] hover:bg-[#2563EB] border-2 border-[#1E3A8A] text-white text-xs px-4 py-2 rounded-lg font-black transition-colors shadow-sm">Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('social.groups.reject', [$group->slug, $pending->id]) }}" class="flex-1 sm:flex-none">
                                        @csrf
                                        <button class="w-full bg-[#152D54] hover:bg-[#1E3A8A] border-2 border-[#1E3A8A] text-white text-xs px-4 py-2 rounded-lg font-bold transition-colors">Reject</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        {{-- Panel: About --}}
        <div data-panel="about" class="p-4 md:p-6 hidden space-y-6">
            <div>
                <h3 class="f-display text-sm md:text-base font-bold text-white mb-3">Deskripsi</h3>
                <div class="bg-[#152D54] rounded-xl p-4 border-2 border-[#1E3A8A]">
                    <p class="text-sm text-slate-300 whitespace-pre-line {{ $group->description ? 'leading-relaxed' : 'italic text-slate-500 font-medium' }}">{{ $group->description ?: 'Belum ada deskripsi untuk grup ini.' }}</p>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div class="bg-[#152D54] border-2 border-[#1E3A8A] rounded-xl p-3 md:p-4 text-center">
                    <p class="text-[10px] md:text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Anggota</p>
                    <p class="f-display text-xl md:text-2xl font-bold text-[#3B82F6]">{{ $memberCount }}</p>
                </div>
                <div class="bg-[#152D54] border-2 border-[#1E3A8A] rounded-xl p-3 md:p-4 text-center">
                    <p class="text-[10px] md:text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Admin</p>
                    <p class="f-display text-xl md:text-2xl font-bold text-[#60A5FA]">{{ $adminCount }}</p>
                </div>
                <div class="bg-[#152D54] border-2 border-[#1E3A8A] rounded-xl p-3 md:p-4 text-center">
                    <p class="text-[10px] md:text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Visibilitas</p>
                    <p class="f-display text-xs md:text-sm font-bold text-[#3B82F6] uppercase mt-1.5">{{ $group->visibility }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CONFIRM MODAL --}}
<div id="confirmModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-[#060D1F]/80 backdrop-blur-sm p-4 transition-opacity" role="dialog" aria-modal="true">
    <div class="bg-[#0F1F3D] border-2 border-[#3B82F6] shadow-[0_0_0_4px_#1E3A8A] rounded-2xl max-w-sm w-full p-6 transform transition-transform scale-100">
        <div class="flex items-start gap-4 mb-5">
            <div id="confirmIconWrap" class="w-12 h-12 rounded-full bg-[#3B82F6]/20 border-2 border-[#3B82F6]/30 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-[#3B82F6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.74-2.99l-6.93-12a2 2 0 00-3.48 0l-6.93 12A2 2 0 005.07 19z"/></svg>
            </div>
            <div class="flex-1 pt-1">
                <h3 id="confirmTitle" class="f-display text-xl font-bold text-white"></h3>
                <p id="confirmMessage" class="text-sm text-slate-400 mt-1 font-medium leading-relaxed"></p>
            </div>
        </div>
        <div class="flex gap-3 justify-end mt-6">
            <button id="confirmCancel" type="button" class="px-5 py-2.5 bg-[#152D54] border-2 border-[#1E3A8A] hover:bg-[#1E3A8A] text-slate-300 rounded-xl text-sm font-bold transition-colors">Batal</button>
            <button id="confirmOk" type="button" class="px-5 py-2.5 bg-[#3B82F6] hover:bg-[#2563EB] text-white rounded-xl text-sm font-black transition-colors shadow-sm">Ya, Lanjutkan</button>
        </div>
    </div>
</div>

<script>
(() => {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanels  = document.querySelectorAll('[data-panel]');
    const activateTab = (name) => {
        tabButtons.forEach(btn => {
            const active = btn.dataset.tab === name;
            btn.classList.toggle('text-white', active);
            btn.classList.toggle('bg-[#1E3A8A]/30', active);
            btn.classList.toggle('border-[#3B82F6]', active);
            btn.classList.toggle('text-slate-400', !active);
            btn.classList.toggle('border-transparent', !active);
        });
        tabPanels.forEach(p => p.classList.toggle('hidden', p.dataset.panel !== name));
    };
    tabButtons.forEach(btn => btn.addEventListener('click', () => activateTab(btn.dataset.tab)));
    document.querySelectorAll('[data-tab-jump]').forEach(el => el.addEventListener('click', () => { const tab = el.dataset.tabJump; activateTab(tab); }));

    const modal = document.getElementById('confirmModal'), titleEl = document.getElementById('confirmTitle'), msgEl = document.getElementById('confirmMessage'), okBtn = document.getElementById('confirmOk'), cancelBtn = document.getElementById('confirmCancel');
    let pendingForm = null;

    const hideModal = () => { modal.classList.add('hidden'); modal.classList.remove('flex'); pendingForm = null; };

    document.querySelectorAll('form[data-confirm-submit]').forEach(form => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            pendingForm = form;
            titleEl.textContent = form.dataset.confirmTitle || 'Konfirmasi';
            msgEl.textContent   = form.dataset.confirmMessage || 'Lanjutkan aksi ini?';
            okBtn.textContent   = form.dataset.confirmOk || 'Ya, Lanjutkan';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    okBtn.addEventListener('click', () => { if (pendingForm) { const f = pendingForm; pendingForm = null; f.submit(); } });
    cancelBtn.addEventListener('click', hideModal);
})();
</script>

@endsection