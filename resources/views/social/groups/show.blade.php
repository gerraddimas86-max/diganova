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

<div class="relative z-10 max-w-5xl mx-auto px-4 py-6">

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="mb-4 bg-[#0F1F3D] border-2 border-[#22D3EE] shadow-sm text-white rounded-xl px-4 py-3 text-sm flex items-start gap-2">
            <svg class="w-5 h-5 flex-shrink-0 text-[#22D3EE]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 bg-[#0F1F3D] border-2 border-[#F43F5E] shadow-sm text-[#F43F5E] rounded-xl px-4 py-3 text-sm flex items-start gap-2">
            <svg class="w-5 h-5 flex-shrink-0 text-[#F43F5E]" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10A8 8 0 11.001 10 8 8 0 0118 10zm-9-4a1 1 0 012 0v4a1 1 0 11-2 0V6zm1 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
            </svg>
            <span class="font-bold">{{ session('error') }}</span>
        </div>
    @endif

    {{-- ============ HERO HEADER (Double Border) ============ --}}
    <div class="relative overflow-hidden rounded-3xl bg-[#0F1F3D] border-2 border-[#3B82F6] shadow-[0_0_0_4px_#1E3A8A] text-white mb-6">
        
        <div class="relative p-6 md:p-8">
            <div class="flex flex-col md:flex-row md:items-start gap-5 md:gap-6">
                {{-- Avatar Fun --}}
                <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl p-[3px] bg-gradient-to-tr from-[#FACC15] to-[#22D3EE] flex-shrink-0 shadow-lg">
                    <div class="w-full h-full bg-[#0F1F3D] rounded-xl flex items-center justify-center text-3xl font-extrabold text-white border-2 border-[#0F1F3D]">
                        {{ $initial }}
                    </div>
                </div>

                <div class="flex-1 min-w-0 md:pt-1">
                    <div class="flex items-center gap-3 flex-wrap mb-2">
                        <h1 class="f-display text-2xl md:text-3xl font-bold tracking-tight text-white break-words">{{ $group->name }}</h1>
                        @if ($group->isPrivate())
                            <span class="inline-flex items-center gap-1.5 text-[10px] md:text-xs bg-[#1E3A8A] border-2 border-[#3B82F6] px-2.5 py-1 rounded-full font-bold text-[#60A5FA] tracking-wider shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                PRIVATE
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-[10px] md:text-xs bg-[#1E3A8A] border-2 border-[#3B82F6] px-2.5 py-1 rounded-full font-bold text-[#22D3EE] tracking-wider shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                PUBLIC
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center gap-3 md:gap-4 text-xs md:text-sm text-slate-400 flex-wrap font-medium">
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-[#FACC15]" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                            </svg>
                            <strong class="text-white text-sm md:text-base">{{ $memberCount }}</strong> Anggota
                        </span>
                        <span class="text-slate-600">|</span>
                        <span>Dibuat oleh <strong class="text-white">{{ $group->owner->name }}</strong></span>
                    </div>
                </div>

                <button id="shareBtn"
                        class="self-start md:self-auto p-2.5 md:p-3 rounded-xl bg-[#152D54] hover:bg-[#1E3A8A] border-2 border-[#1E3A8A] transition-all duration-300 group"
                        aria-label="Bagikan grup">
                    <svg class="w-5 h-5 text-slate-300 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- ============ ACTION BAR ============ --}}
    <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-2xl shadow-sm p-4 mb-6">
        <div class="flex flex-wrap gap-3 items-center">
            @auth
                @if ($isAdmin)
                    <a href="{{ route('social.groups.edit', $group->slug) }}"
                       class="inline-flex items-center gap-2 bg-[#152D54] hover:bg-[#1E3A8A] text-[#60A5FA] border-2 border-[#1E3A8A]
                              px-4 py-2 rounded-xl font-bold text-sm transition-colors duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    
                    <form method="POST" action="{{ route('social.groups.destroy', $group->slug) }}"
                          data-confirm-submit
                          data-confirm-title="Hapus grup ini?"
                          data-confirm-message="Seluruh data grup, anggota, dan riwayat akan hilang permanen. Tindakan ini tidak bisa dibatalkan."
                          data-confirm-variant="danger"
                          data-confirm-ok="Ya, Hapus">
                        @csrf
                        @method('DELETE')
                        <button class="inline-flex items-center gap-2 bg-[#F43F5E]/10 hover:bg-[#F43F5E]/20 border-2 border-[#F43F5E]/30
                                       text-[#F43F5E] px-4 py-2 rounded-xl font-bold text-sm transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"/>
                            </svg>
                            Hapus
                        </button>
                    </form>

                    @if ($pendingCount > 0)
                        <button type="button" data-tab-jump="pending"
                                class="inline-flex items-center gap-2 bg-[#FACC15]/10 hover:bg-[#FACC15]/20 border-2 border-[#FACC15]/30
                                       text-[#FACC15] px-4 py-2 rounded-xl font-bold text-sm transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $pendingCount }} Permintaan
                        </button>
                    @endif
                @endif

                <div class="flex-1 hidden md:block"></div>

                @if ($isMember)
                    @if (!$isOwner)
                        <form method="POST" action="{{ route('social.groups.leave', $group->slug) }}"
                              data-confirm-submit
                              data-confirm-title="Keluar dari grup?"
                              data-confirm-message="Kamu bisa bergabung lagi nanti, tapi untuk grup private kamu perlu minta approval ulang."
                              data-confirm-variant="warning"
                              data-confirm-ok="Ya, Keluar">
                            @csrf
                            <button class="inline-flex items-center gap-2 bg-[#152D54] border-2 border-[#1E3A8A] hover:bg-[#1E3A8A]
                                           text-slate-300 hover:text-white px-4 py-2 rounded-xl font-bold text-sm transition-colors duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Keluar
                            </button>
                        </form>
                    @else
                        <span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-bold text-[#1E3A8A] bg-[#22D3EE] border-[2px] border-[#1E3A8A] rounded-xl shadow-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Anda Owner
                        </span>
                    @endif
                @elseif ($hasPending)
                    <div class="flex-1 w-full md:w-auto bg-[#FACC15]/10 border-2 border-[#FACC15]/30 rounded-xl px-4 py-2 text-sm text-[#FACC15] flex items-center justify-center md:justify-start gap-2 font-bold">
                        <svg class="w-4 h-4 md:w-5 md:h-5 flex-shrink-0 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Menunggu persetujuan owner</span>
                    </div>
                @else
                    <form method="POST" action="{{ route('social.groups.join', $group->slug) }}" class="flex-1 w-full md:w-auto mt-2 md:mt-0">
                        @csrf
                        <button class="w-full inline-flex items-center justify-center gap-2
                                       bg-[#FACC15] hover:bg-[#EAB308] text-[#1E3A8A] px-6 py-2.5
                                       rounded-xl font-black text-sm transition-all duration-300 border-[3px] border-[#1E3A8A] shadow-[3px_3px_0_#1E3A8A] hover:-translate-y-0.5">
                            @if ($group->isPrivate())
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Minta Join
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/>
                                </svg>
                                Join Grup
                            @endif
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('login') }}"
                   class="w-full md:w-auto text-center inline-flex items-center justify-center gap-2
                          bg-[#FACC15] hover:bg-[#EAB308] text-[#1E3A8A] px-6 py-2.5
                          rounded-xl font-black text-sm transition-all duration-300 border-[3px] border-[#1E3A8A] shadow-[3px_3px_0_#1E3A8A] hover:-translate-y-0.5">
                    Login untuk Join
                </a>
            @endauth
        </div>
    </div>

    {{-- ============ TABS & CONTENT ============ --}}
    <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-2xl shadow-sm overflow-hidden">

        {{-- Tab Navigation --}}
        <div class="border-b-2 border-[#1E3A8A] flex overflow-x-auto bg-[#152D54]">
            <button data-tab="members"
                    class="tab-btn whitespace-nowrap px-5 md:px-6 py-4 text-xs md:text-sm font-bold border-b-4 transition-colors duration-200
                           text-white border-[#FACC15] bg-[#1E3A8A]/30">
                Anggota
                <span class="ml-1.5 text-[10px] md:text-xs bg-[#FACC15] text-[#1E3A8A] px-2 py-0.5 rounded-md font-black">
                    {{ $memberCount }}
                </span>
            </button>

            @if ($isAdmin)
                <button data-tab="pending"
                        class="tab-btn whitespace-nowrap px-5 md:px-6 py-4 text-xs md:text-sm font-bold border-b-4 transition-colors duration-200
                               text-slate-400 border-transparent hover:text-white hover:bg-[#1E3A8A]/20">
                    Permintaan
                    @if ($pendingCount > 0)
                        <span class="ml-1.5 text-[10px] md:text-xs bg-[#F43F5E] text-white px-2 py-0.5 rounded-md font-black animate-pulse">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </button>
            @endif

            <button data-tab="about"
                    class="tab-btn whitespace-nowrap px-5 md:px-6 py-4 text-xs md:text-sm font-bold border-b-4 transition-colors duration-200
                           text-slate-400 border-transparent hover:text-white hover:bg-[#1E3A8A]/20">
                Tentang
            </button>
        </div>

        {{-- Panel: Members --}}
        <div data-panel="members" class="p-4 md:p-6">
            <div class="flex flex-col sm:flex-row gap-3 mb-5">
                <div class="relative flex-1">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500 pointer-events-none"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                    </svg>
                    <input type="text" id="memberSearch" placeholder="Cari nama atau username..."
                           autocomplete="off"
                           class="w-full pl-10 pr-4 py-2.5 border-2 border-[#1E3A8A] rounded-xl text-sm bg-[#152D54] font-medium text-white placeholder-slate-500
                                  focus:ring-0 focus:border-[#3B82F6] outline-none transition-all">
                </div>
                <select id="memberRoleFilter"
                        class="px-4 py-2.5 border-2 border-[#1E3A8A] rounded-xl text-sm bg-[#152D54] font-bold text-white
                               focus:ring-0 focus:border-[#3B82F6] outline-none sm:w-44 transition-all cursor-pointer">
                    <option value="" class="bg-[#0F1F3D]">Semua Role</option>
                    <option value="owner" class="bg-[#0F1F3D]">Owner</option>
                    <option value="admin" class="bg-[#0F1F3D]">Admin</option>
                    <option value="member" class="bg-[#0F1F3D]">Member</option>
                </select>
            </div>

            <div id="memberList" class="space-y-2">
                @forelse ($members as $member)
                    @php $role = $group->owner_id === $member->id ? 'owner' : $member->pivot->role; @endphp
                    <div class="member-row flex items-center gap-3 md:gap-4 p-2 md:p-3 rounded-xl hover:bg-[#152D54] border-2 border-transparent hover:border-[#1E3A8A] transition-all group"
                         data-search="{{ strtolower($member->name . ' ' . $member->username) }}"
                         data-role="{{ $role }}">

                        <a href="{{ route('social.profile.show', $member->username) }}" class="flex-shrink-0 relative">
                            <div class="w-10 h-10 md:w-11 md:h-11 rounded-full p-[2px] bg-gradient-to-tr from-[#22D3EE] to-[#3B82F6] group-hover:rotate-12 transition-transform">
                                <div class="w-full h-full rounded-full border-2 border-[#0F1F3D] overflow-hidden bg-[#0F1F3D]">
                                    <x-avatar :avatar="$member->avatar" :user="$member" size="w-full h-full" />
                                </div>
                            </div>
                        </a>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap mb-0.5">
                                <a href="{{ route('social.profile.show', $member->username) }}"
                                   class="font-bold text-white hover:text-[#FACC15] text-sm truncate transition-colors">
                                    {{ $member->name }}
                                </a>
                                @if ($role === 'owner')
                                    <span class="inline-flex items-center gap-1 text-[9px] md:text-[10px] bg-[#FACC15] text-[#1E3A8A] border-2 border-[#1E3A8A]
                                                 px-2 py-0.5 rounded-md font-black tracking-wide uppercase">
                                        Owner
                                    </span>
                                @elseif ($role === 'admin')
                                    <span class="inline-flex items-center gap-1 text-[9px] md:text-[10px] bg-[#22D3EE] text-[#1E3A8A] border-2 border-[#1E3A8A]
                                                 px-2 py-0.5 rounded-md font-black tracking-wide uppercase">
                                        Admin
                                    </span>
                                @endif
                            </div>
                            <p class="text-[11px] md:text-xs text-slate-400 font-medium truncate">{{ '@' . $member->username }}</p>
                        </div>

                        @if ($isAdmin && $member->id !== auth()->id() && $role !== 'owner')
                            <div class="flex gap-2 flex-shrink-0 md:opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                @if ($isOwner)
                                    @if ($role === 'member')
                                        <form method="POST" action="{{ route('social.groups.promote', [$group->slug, $member->id]) }}"
                                              data-confirm-submit data-confirm-title="Jadikan admin?" data-confirm-message="{{ $member->name }} akan mendapat akses untuk mengelola anggota." data-confirm-variant="primary" data-confirm-ok="Ya, Promote">
                                            @csrf
                                            <button class="text-[10px] md:text-xs text-[#1E3A8A] bg-[#22D3EE] border-2 border-[#1E3A8A] hover:bg-[#60A5FA]
                                                           font-bold px-2 md:px-3 py-1.5 rounded-lg transition-colors">
                                                Promote
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('social.groups.demote', [$group->slug, $member->id]) }}"
                                              data-confirm-submit data-confirm-title="Turunkan ke member?" data-confirm-message="{{ $member->name }} tidak akan lagi bisa mengelola grup." data-confirm-variant="warning" data-confirm-ok="Ya, Demote">
                                            @csrf
                                            <button class="text-[10px] md:text-xs text-slate-300 bg-[#152D54] border-2 border-[#1E3A8A] hover:bg-[#1E3A8A]
                                                           font-bold px-2 md:px-3 py-1.5 rounded-lg transition-colors">
                                                Demote
                                            </button>
                                        </form>
                                    @endif
                                @endif
                                <form method="POST" action="{{ route('social.groups.kick', [$group->slug, $member->id]) }}"
                                      data-confirm-submit data-confirm-title="Keluarkan anggota?" data-confirm-message="{{ $member->name }} akan dikeluarkan dari grup." data-confirm-variant="danger" data-confirm-ok="Ya, Keluarkan">
                                    @csrf
                                    <button class="text-[10px] md:text-xs text-[#F43F5E] bg-[#F43F5E]/10 border-2 border-[#F43F5E]/30 hover:bg-[#F43F5E]/20
                                                   font-bold px-2 md:px-3 py-1.5 rounded-lg transition-colors">
                                        Kick
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-slate-400 text-center py-8 font-medium">Belum ada anggota</p>
                @endforelse
            </div>

            <p id="memberEmpty" class="hidden text-sm text-slate-400 text-center py-8 font-medium">
                Tidak ada anggota yang cocok dengan pencarian.
            </p>
        </div>

        {{-- Panel: Pending --}}
        @if ($isAdmin)
            <div data-panel="pending" class="p-4 md:p-6 hidden">
                @if ($pendingCount === 0)
                    <div class="text-center py-10">
                        <div class="w-16 h-16 mx-auto rounded-full bg-[#152D54] border-2 border-[#1E3A8A] flex items-center justify-center mb-3">
                            <svg class="w-8 h-8 text-[#22D3EE]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="text-sm text-slate-400 font-bold">Tidak ada permintaan bergabung</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($pendingMembers as $pending)
                            <div class="bg-[#FACC15]/10 border-2 border-[#FACC15]/30 rounded-xl p-3 md:p-4 flex flex-col sm:flex-row sm:items-center gap-3 md:gap-4">
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    <x-avatar :avatar="$pending->avatar" :user="$pending" size="w-10 h-10 md:w-11 md:h-11 border-2 border-[#0F1F3D] shadow-sm" />
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-white text-sm truncate">{{ $pending->name }}</p>
                                        <p class="text-[11px] md:text-xs text-[#FACC15] truncate font-medium">{{ '@' . $pending->username }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-2 w-full sm:w-auto">
                                    <form method="POST" action="{{ route('social.groups.approve', [$group->slug, $pending->id]) }}" class="flex-1 sm:flex-none">
                                        @csrf
                                        <button class="w-full bg-[#22D3EE] hover:bg-[#60A5FA] border-2 border-[#1E3A8A] text-[#1E3A8A] text-xs
                                                       px-4 py-2 rounded-lg font-black transition-colors shadow-sm">
                                            Approve
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('social.groups.reject', [$group->slug, $pending->id]) }}"
                                          data-confirm-submit data-confirm-title="Tolak permintaan?" data-confirm-message="Permintaan dari {{ $pending->name }} akan ditolak." data-confirm-variant="danger" data-confirm-ok="Ya, Tolak" class="flex-1 sm:flex-none">
                                        @csrf
                                        <button class="w-full bg-[#F43F5E]/20 hover:bg-[#F43F5E]/30 border-2 border-[#F43F5E]/50 text-[#F43F5E] text-xs
                                                       px-4 py-2 rounded-lg font-bold transition-colors">
                                            Reject
                                        </button>
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
                    <p class="text-sm text-slate-300 whitespace-pre-line {{ $group->description ? 'leading-relaxed' : 'italic text-slate-500 font-medium' }}">
                        {{ $group->description ?: 'Belum ada deskripsi untuk grup ini.' }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div class="bg-[#152D54] border-2 border-[#1E3A8A] rounded-xl p-3 md:p-4 text-center">
                    <p class="text-[10px] md:text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Anggota</p>
                    <p class="f-display text-xl md:text-2xl font-bold text-[#FACC15]">{{ $memberCount }}</p>
                </div>
                <div class="bg-[#152D54] border-2 border-[#1E3A8A] rounded-xl p-3 md:p-4 text-center">
                    <p class="text-[10px] md:text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Admin</p>
                    <p class="f-display text-xl md:text-2xl font-bold text-[#22D3EE]">{{ $adminCount }}</p>
                </div>
                <div class="bg-[#152D54] border-2 border-[#1E3A8A] rounded-xl p-3 md:p-4 text-center">
                    <p class="text-[10px] md:text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Visibilitas</p>
                    <p class="f-display text-xs md:text-sm font-bold text-[#60A5FA] uppercase mt-1.5">{{ $group->visibility }}</p>
                </div>
            </div>

            <div>
                <h3 class="f-display text-sm md:text-base font-bold text-white mb-3">Link grup</h3>
                <div class="flex gap-2">
                    <input type="text" readonly id="inviteLinkInput"
                           value="{{ route('social.groups.show', $group->slug) }}"
                           class="flex-1 min-w-0 px-3 md:px-4 py-2.5 border-2 border-[#1E3A8A] rounded-xl text-xs md:text-sm font-mono bg-[#152D54] text-slate-300 font-medium
                                  focus:ring-0 focus:border-[#3B82F6] outline-none transition-all">
                    <button type="button" id="copyLinkBtn"
                            class="inline-flex items-center justify-center gap-2 bg-[#3B82F6] hover:bg-[#2563EB]
                                   px-4 md:px-5 py-2.5 rounded-xl text-xs md:text-sm font-bold text-white transition-all shadow-md hover:shadow-lg min-w-[80px] border-2 border-[#1E3A8A]">
                        <svg class="w-4 h-4 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <span id="copyBtnText">Copy</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ============ CONFIRM MODAL (Double Border) ============ --}}
<div id="confirmModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-[#060D1F]/80 backdrop-blur-sm p-4 transition-opacity"
     role="dialog" aria-modal="true" aria-labelledby="confirmTitle">
    <div class="bg-[#0F1F3D] border-2 border-[#3B82F6] shadow-[0_0_0_4px_#1E3A8A] rounded-2xl max-w-sm w-full p-6 transform transition-transform scale-100">
        <div class="flex items-start gap-4 mb-5">
            <div id="confirmIconWrap" class="w-12 h-12 rounded-full bg-[#1E3A8A] border-2 border-[#3B82F6] flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-[#60A5FA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.74-2.99l-6.93-12a2 2 0 00-3.48 0l-6.93 12A2 2 0 005.07 19z"/>
                </svg>
            </div>
            <div class="flex-1 pt-1">
                <h3 id="confirmTitle" class="f-display text-xl font-bold text-white"></h3>
                <p id="confirmMessage" class="text-sm text-slate-400 mt-1 font-medium leading-relaxed"></p>
            </div>
        </div>
        <div class="flex gap-3 justify-end mt-6">
            <button id="confirmCancel" type="button"
                    class="px-5 py-2.5 bg-[#152D54] border-2 border-[#1E3A8A] hover:bg-[#1E3A8A] text-slate-300 rounded-xl text-sm font-bold transition-colors">
                Batal
            </button>
            <button id="confirmOk" type="button"
                    class="px-5 py-2.5 bg-[#F43F5E] hover:bg-red-600 text-white rounded-xl text-sm font-black transition-colors shadow-sm">
                Ya, Lanjutkan
            </button>
        </div>
    </div>
</div>

<script>
(() => {
    // TABS
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanels  = document.querySelectorAll('[data-panel]');
    const activateTab = (name) => {
        tabButtons.forEach(btn => {
            const active = btn.dataset.tab === name;
            btn.classList.toggle('text-white', active);
            btn.classList.toggle('bg-[#1E3A8A]/30', active);
            btn.classList.toggle('border-[#FACC15]', active);
            btn.classList.toggle('text-slate-400', !active);
            btn.classList.toggle('border-transparent', !active);
            btn.classList.toggle('hover:bg-[#1E3A8A]/20', !active);
        });
        tabPanels.forEach(p => p.classList.toggle('hidden', p.dataset.panel !== name));
    };
    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            activateTab(btn.dataset.tab);
            history.replaceState(null, '', '#' + btn.dataset.tab);
        });
    });
    document.querySelectorAll('[data-tab-jump]').forEach(el => {
        el.addEventListener('click', () => {
            const tab = el.dataset.tabJump;
            activateTab(tab);
            history.replaceState(null, '', '#' + tab);
            document.querySelector('[data-panel="' + tab + '"]')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
    const initialHash = location.hash.replace('#', '');
    if (initialHash && document.querySelector('[data-tab="' + initialHash + '"]')) {
        activateTab(initialHash);
    }

    // SEARCH
    const searchInput  = document.getElementById('memberSearch');
    const roleFilter   = document.getElementById('memberRoleFilter');
    const memberRows   = document.querySelectorAll('.member-row');
    const memberEmpty  = document.getElementById('memberEmpty');
    const filterMembers = () => {
        const q = (searchInput?.value || '').toLowerCase().trim();
        const role = roleFilter?.value || '';
        let visible = 0;
        memberRows.forEach(row => {
            const matchSearch = !q || (row.dataset.search || '').includes(q);
            const matchRole = !role || row.dataset.role === role;
            const show = matchSearch && matchRole;
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        memberEmpty?.classList.toggle('hidden', visible > 0 || memberRows.length === 0);
    };
    searchInput?.addEventListener('input', filterMembers);
    roleFilter?.addEventListener('change', filterMembers);

    // MODAL
    const modal      = document.getElementById('confirmModal');
    const titleEl    = document.getElementById('confirmTitle');
    const msgEl      = document.getElementById('confirmMessage');
    const iconWrap   = document.getElementById('confirmIconWrap');
    const okBtn      = document.getElementById('confirmOk');
    const cancelBtn  = document.getElementById('confirmCancel');
    let pendingForm = null;
    let lastFocused = null;

    const variants = {
        danger:  { btn: ['bg-[#F43F5E]','hover:bg-red-600'], icon: ['bg-[#F43F5E]/20','border-[#F43F5E]/30'], iconColor: 'text-[#F43F5E]' },
        warning: { btn: ['bg-[#FACC15]','text-[#1E3A8A]','hover:bg-[#EAB308]'], icon: ['bg-[#FACC15]/20','border-[#FACC15]/30'], iconColor: 'text-[#FACC15]' },
        primary: { btn: ['bg-[#22D3EE]','text-[#1E3A8A]','hover:bg-[#60A5FA]'], icon: ['bg-[#22D3EE]/20','border-[#22D3EE]/30'], iconColor: 'text-[#22D3EE]' },
    };

    const showModal = () => {
        lastFocused = document.activeElement;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        cancelBtn.focus();
    };
    const hideModal = () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        pendingForm = null;
        lastFocused?.focus?.();
    };

    document.querySelectorAll('form[data-confirm-submit]').forEach(form => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            pendingForm = form;
            titleEl.textContent = form.dataset.confirmTitle || 'Konfirmasi';
            msgEl.textContent   = form.dataset.confirmMessage || 'Lanjutkan aksi ini?';
            okBtn.textContent   = form.dataset.confirmOk || 'Ya, Lanjutkan';
            const v = variants[form.dataset.confirmVariant] || variants.primary;
            okBtn.className = 'px-5 py-2.5 rounded-xl text-sm font-black transition-colors shadow-sm ' + v.btn.join(' ');
            iconWrap.className = 'w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0 border-2 ' + v.icon.join(' ');
            iconWrap.querySelector('svg').className = 'w-6 h-6 ' + v.iconColor;
            showModal();
        });
    });

    okBtn.addEventListener('click', () => {
        if (!pendingForm) return hideModal();
        const f = pendingForm;
        pendingForm = null;
        okBtn.disabled = true;
        okBtn.textContent = 'Memproses...';
        f.submit(); 
    });
    cancelBtn.addEventListener('click', hideModal);
    modal.addEventListener('click', (e) => { if (e.target === modal) hideModal(); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !modal.classList.contains('hidden')) hideModal(); });

    // TOAST
    const toast = (msg) => {
        const el = document.createElement('div');
        el.className = 'fixed bottom-6 left-1/2 -translate-x-1/2 bg-[#FACC15] text-[#1E3A8A] text-sm font-black ' +
                       'px-6 py-3 rounded-xl shadow-2xl z-[60] opacity-0 transition-all duration-300 translate-y-4 border-2 border-[#1E3A8A]';
        el.textContent = msg;
        document.body.appendChild(el);
        requestAnimationFrame(() => { el.classList.remove('opacity-0', 'translate-y-4'); });
        setTimeout(() => {
            el.classList.add('opacity-0', 'translate-y-4');
            setTimeout(() => el.remove(), 300);
        }, 2500);
    };

    // SHARE & COPY
    const shareBtn    = document.getElementById('shareBtn');
    const copyBtn     = document.getElementById('copyLinkBtn');
    const copyBtnText = document.getElementById('copyBtnText');
    const inviteInput = document.getElementById('inviteLinkInput');

    const copyText = async (text) => {
        try { await navigator.clipboard.writeText(text); return true; }
        catch {
            try { inviteInput?.select(); return document.execCommand('copy'); }
            catch { return false; }
        }
    };

    shareBtn?.addEventListener('click', async () => {
        const url = window.location.href;
        if (navigator.share) {
            try { await navigator.share({ title: @json($group->name), url }); } catch {}
        } else {
            const ok = await copyText(url);
            toast(ok ? 'Link berhasil disalin' : 'Gagal menyalin link');
        }
    });

    copyBtn?.addEventListener('click', async () => {
        const ok = await copyText(inviteInput.value);
        if (ok) {
            const oldText = copyBtnText.textContent;
            copyBtnText.textContent = 'Disalin!';
            copyBtn.classList.replace('bg-[#3B82F6]', 'bg-[#FACC15]');
            copyBtn.classList.replace('hover:bg-[#2563EB]', 'hover:bg-[#EAB308]');
            copyBtn.classList.replace('text-white', 'text-[#1E3A8A]');
            setTimeout(() => {
                copyBtnText.textContent = oldText;
                copyBtn.classList.replace('bg-[#FACC15]', 'bg-[#3B82F6]');
                copyBtn.classList.replace('hover:bg-[#EAB308]', 'hover:bg-[#2563EB]');
                copyBtn.classList.replace('text-[#1E3A8A]', 'text-white');
            }, 2000);
            toast('Link berhasil disalin');
        } else {
            toast('Gagal menyalin link');
        }
    });

    window.addEventListener('pageshow', (e) => {
        if (e.persisted) {
            hideModal();
            okBtn.disabled = false;
            okBtn.textContent = 'Ya, Lanjutkan';
            if(copyBtnText) copyBtnText.textContent = 'Copy';
        }
    });
})();
</script>

@endsection