@extends('social.layouts.app')

@section('title', $user->name . ' — DIGANOVA Social')

@section('content')

<style>
    /* CSS OVERRIDE: Card Sederhana */
    .dark-card-wrapper article {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        border-radius: inherit !important;
    }
    .dark-card-wrapper h3,
    .dark-card-wrapper .text-\[\#000000\] { color: #F1F5F9 !important; }
    .dark-card-wrapper .text-gray-500,
    .dark-card-wrapper .text-gray-600 { color: #94A3B8 !important; }
    .dark-card-wrapper .text-\[\#112E81\] { color: #60A5FA !important; }
    .dark-card-wrapper .bg-\[\#F8FAFC\] { background-color: #152D54 !important; }
    .dark-card-wrapper .border-gray-100 { border-color: rgba(96, 165, 250, 0.1) !important; }
    .dark-card-wrapper .hover\:bg-\[\#F8FAFC\]:hover { background-color: rgba(96, 165, 250, 0.1) !important; }

    [x-cloak] { display: none !important; }
</style>

<div class="relative z-10 max-w-5xl mx-auto px-4 py-6 sm:py-8"
     x-data="profilePage({
        username: '{{ $user->username }}',
        initialFollowing: {{ $isFollowing ? 'true' : 'false' }},
        initialFollowers: {{ $stats['followers'] }}
     })">

    {{-- Profile Header Banner --}}
    <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-3xl shadow-sm overflow-hidden mb-6 relative">
        
        {{-- Cover (Ditambahkan pola agar tidak polos) --}}
        <div class="h-32 md:h-48 bg-gradient-to-br from-[#0F1F3D] via-[#1E3A8A] to-[#2563EB] relative overflow-hidden border-b-2 border-[#1E3A8A]">
            {{-- Pola Grid --}}
            <div class="absolute inset-0 opacity-20" style="background-image: linear-gradient(rgba(96, 165, 250, 0.2) 1px, transparent 1px), linear-gradient(90deg, rgba(96, 165, 250, 0.2) 1px, transparent 1px); background-size: 40px 40px;"></div>
            {{-- Efek Glow --}}
            <div class="absolute -top-10 right-10 w-40 h-40 bg-[#FACC15] rounded-full blur-[60px] opacity-30 pointer-events-none"></div>
            <div class="absolute -bottom-10 left-10 w-40 h-40 bg-[#22D3EE] rounded-full blur-[60px] opacity-30 pointer-events-none"></div>
        </div>

        <div class="px-5 md:px-8 pb-6 relative">
            {{-- Avatar + Action --}}
            <div class="flex justify-between items-end -mt-12 md:-mt-16 mb-4 relative z-10">
                
                {{-- Avatar dengan ring menyala --}}
                <div class="relative group cursor-pointer">
                    <div class="relative p-1.5 bg-[#0F1F3D] rounded-full shadow-[0_0_20px_rgba(59,130,246,0.4)] border-2 border-[#3B82F6] group-hover:scale-105 transition-transform duration-300">
                        <x-avatar :avatar="$user->avatar" :user="$user" size="w-24 h-24 md:w-28 md:h-28" textSize="text-4xl md:text-5xl" />
                    </div>
                    @if($user->is_online ?? false) 
                        <div class="absolute bottom-2 right-2 w-6 h-6 bg-[#22D3EE] border-4 border-[#0F1F3D] rounded-full"></div>
                    @endif
                </div>

                {{-- Action Buttons --}}
                @if ($isMe)
                    <a href="{{ route('social.profile.edit') }}"
                       class="inline-flex items-center gap-2 bg-[#FACC15] hover:bg-[#EAB308] text-[#1E3A8A] border-[3px] border-[#1E3A8A] px-5 py-2.5 rounded-xl font-black text-sm transition-all duration-300 shadow-[3px_3px_0_#1E3A8A] hover:-translate-y-1 hover:shadow-[5px_5px_0_#1E3A8A]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit Profil
                    </a>
                @elseif (auth()->check())
                    <button type="button" @click="toggleFollow()"
                            :class="following ? 'bg-[#152D54] text-slate-300 border-[#1E3A8A] hover:bg-[#1E3A8A] hover:text-white' : 'bg-[#3B82F6] text-white border-[#1E3A8A] shadow-[2px_2px_0_#1E3A8A] hover:-translate-y-0.5'"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl font-bold text-sm transition-all duration-300 border-2">
                        
                        <template x-if="!following">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                        </template>
                        <template x-if="following">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </template>

                        <span x-text="following ? 'Following' : 'Follow'"></span>
                    </button>
                @else
                    <a href="{{ route('login') }}"
                       class="bg-[#3B82F6] text-white border-2 border-[#1E3A8A] px-6 py-2.5 rounded-xl font-bold text-sm transition-all duration-300 shadow-[2px_2px_0_#1E3A8A] hover:-translate-y-0.5">
                        Login untuk Follow
                    </a>
                @endif
            </div>

            {{-- Name & Username --}}
            <div>
                <h1 class="f-display text-2xl md:text-3xl font-bold text-white tracking-tight">{{ $user->name }}</h1>
                <p class="text-sm font-medium text-slate-400 mt-1">{{ '@' . $user->username }}</p>
            </div>

            {{-- Bio --}}
            @if ($user->bio)
                <p class="text-sm md:text-[15px] text-slate-300 mt-4 whitespace-pre-line leading-relaxed max-w-2xl font-medium">{{ $user->bio }}</p>
            @endif

            {{-- Meta --}}
            <div class="flex items-center flex-wrap gap-x-5 gap-y-2 mt-5 text-[13px] font-medium text-slate-400">
                <span class="inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-[#60A5FA]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Bergabung {{ $user->created_at->format('M Y') }}
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-[#FACC15]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <strong class="text-white">{{ $user->total_points }}</strong> poin
                </span>
            </div>

            {{-- Stats (BISA DIKLIK) --}}
            <div class="grid grid-cols-4 gap-2 mt-6 pt-5 border-t-2 border-[#1E3A8A]">
                
                {{-- Klik Posts --}}
                <button type="button" @click="tab = 'posts'" class="text-center sm:text-left group cursor-pointer p-2 rounded-xl hover:bg-[#152D54] transition-colors focus:outline-none focus:ring-2 focus:ring-[#3B82F6]/50">
                    <p class="f-display text-xl md:text-2xl font-bold text-white group-hover:text-[#60A5FA] transition-colors">{{ $stats['posts'] }}</p>
                    <p class="text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5 group-hover:text-white transition-colors">Posts</p>
                </button>

                {{-- Klik Followers --}}
                <button type="button" @click="tab = 'followers'" class="text-center sm:text-left group cursor-pointer p-2 rounded-xl hover:bg-[#152D54] transition-colors focus:outline-none focus:ring-2 focus:ring-[#FACC15]/50">
                    <p class="f-display text-xl md:text-2xl font-bold text-[#FACC15]" x-text="followersCount"></p>
                    <p class="text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5 group-hover:text-white transition-colors">Followers</p>
                </button>

                {{-- Klik Following --}}
                <button type="button" @click="tab = 'following'" class="text-center sm:text-left group cursor-pointer p-2 rounded-xl hover:bg-[#152D54] transition-colors focus:outline-none focus:ring-2 focus:ring-[#3B82F6]/50">
                    <p class="f-display text-xl md:text-2xl font-bold text-white group-hover:text-[#60A5FA] transition-colors">{{ $stats['following'] }}</p>
                    <p class="text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5 group-hover:text-white transition-colors">Following</p>
                </button>

                {{-- Klik Achievements --}}
                <button type="button" @click="tab = 'achievements'" class="text-center sm:text-left group cursor-pointer p-2 rounded-xl hover:bg-[#152D54] transition-colors focus:outline-none focus:ring-2 focus:ring-[#22D3EE]/50">
                    <p class="f-display text-xl md:text-2xl font-bold text-[#22D3EE]">{{ $stats['achievements'] }}</p>
                    <p class="text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5 group-hover:text-white transition-colors">Achievement</p>
                </button>
            </div>
        </div>
    </div>

    {{-- Tabs Navigasi (Tetap) --}}
    <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-2xl shadow-sm mb-6 overflow-x-auto">
        <div class="flex min-w-max">
            <button type="button" @click="tab = 'posts'"
                    :class="tab === 'posts' ? 'border-[#FACC15] text-[#FACC15] bg-[#1E3A8A]/30' : 'text-slate-400 hover:text-white hover:bg-[#152D54] border-transparent'"
                    class="flex-1 flex items-center justify-center gap-2 px-5 py-4 text-xs md:text-sm font-bold border-b-4 transition-colors duration-200 whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l6 6v10a2 2 0 01-2 2z"/></svg>
                Posts
            </button>
            
            <button type="button" @click="tab = 'likes'"
                    :class="tab === 'likes' ? 'border-[#FACC15] text-[#FACC15] bg-[#1E3A8A]/30' : 'text-slate-400 hover:text-white hover:bg-[#152D54] border-transparent'"
                    class="flex-1 flex items-center justify-center gap-2 px-5 py-4 text-xs md:text-sm font-bold border-b-4 transition-colors duration-200 whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                Likes
            </button>

            <button type="button" @click="tab = 'achievements'"
                    :class="tab === 'achievements' ? 'border-[#FACC15] text-[#FACC15] bg-[#1E3A8A]/30' : 'text-slate-400 hover:text-white hover:bg-[#152D54] border-transparent'"
                    class="flex-1 flex items-center justify-center gap-2 px-5 py-4 text-xs md:text-sm font-bold border-b-4 transition-colors duration-200 whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                Achievement
            </button>

            <button type="button" @click="tab = 'followers'"
                    :class="tab === 'followers' ? 'border-[#FACC15] text-[#FACC15] bg-[#1E3A8A]/30' : 'text-slate-400 hover:text-white hover:bg-[#152D54] border-transparent'"
                    class="flex-1 flex items-center justify-center gap-2 px-5 py-4 text-xs md:text-sm font-bold border-b-4 transition-colors duration-200 whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a3 3 0 10-2-5.83"/></svg>
                Followers
            </button>

            <button type="button" @click="tab = 'following'"
                    :class="tab === 'following' ? 'border-[#FACC15] text-[#FACC15] bg-[#1E3A8A]/30' : 'text-slate-400 hover:text-white hover:bg-[#152D54] border-transparent'"
                    class="flex-1 flex items-center justify-center gap-2 px-5 py-4 text-xs md:text-sm font-bold border-b-4 transition-colors duration-200 whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Following
            </button>
        </div>
    </div>

    {{-- ============ TAB: POSTS ============ --}}
    <div x-show="tab === 'posts'" x-cloak class="space-y-6">
        @forelse ($posts as $post)
            <div class="dark-card-wrapper bg-[#0F1F3D] border-2 border-[#3B82F6] shadow-[0_0_0_4px_#1E3A8A] rounded-2xl overflow-hidden hover:border-[#60A5FA] transition-colors">
                @include('social.posts._card', ['post' => $post])
            </div>
        @empty
            <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-2xl p-12 text-center shadow-sm">
                <div class="w-16 h-16 mx-auto rounded-full bg-[#152D54] border-2 border-[#1E3A8A] flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-[#FACC15]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l6 6v10a2 2 0 01-2 2z"/></svg>
                </div>
                <p class="f-display text-white font-bold text-xl">Belum ada post</p>
                @if ($isMe)
                    <a href="{{ route('social.posts.index') }}" class="inline-flex items-center gap-1.5 mt-3 text-sm font-bold text-[#60A5FA] hover:text-white hover:underline transition-colors">
                        Buat post pertamamu
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    {{-- ============ TAB: LIKES ============ --}}
    <div x-show="tab === 'likes'" x-cloak class="space-y-6">
        @forelse ($likedPosts as $post)
            <div class="dark-card-wrapper bg-[#0F1F3D] border-2 border-[#3B82F6] shadow-[0_0_0_4px_#1E3A8A] rounded-2xl overflow-hidden hover:border-[#60A5FA] transition-colors">
                @include('social.posts._card', ['post' => $post])
            </div>
        @empty
            <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-2xl p-12 text-center shadow-sm">
                <div class="w-16 h-16 mx-auto rounded-full bg-[#152D54] border-2 border-[#1E3A8A] flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-[#F43F5E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <p class="f-display text-white font-bold text-xl">Belum ada post yang di-like</p>
            </div>
        @endforelse
    </div>

    {{-- ============ TAB: ACHIEVEMENTS ============ --}}
    <div x-show="tab === 'achievements'" x-cloak>
        @if ($achievements->isEmpty())
            <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-2xl p-12 text-center shadow-sm">
                <div class="w-16 h-16 mx-auto rounded-full bg-[#152D54] border-2 border-[#1E3A8A] flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-[#22D3EE]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                </div>
                <p class="f-display text-white font-bold text-xl">Belum ada achievement</p>
                <p class="text-sm font-medium text-slate-400 mt-1">Ikut event, posting, atau main game untuk unlock!</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($achievements as $ach)
                    <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-2xl shadow-sm hover:border-[#3B82F6] transition-all duration-300 p-5 flex items-start gap-4 group">
                        <div class="w-12 h-12 rounded-xl bg-[#152D54] border-2 border-[#1E3A8A] flex items-center justify-center flex-shrink-0 shadow-sm group-hover:scale-105 transition-transform">
                            <span class="text-3xl">{{ $ach->icon ?? '🏅' }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="f-display font-bold text-white text-base">{{ $ach->name }}</p>
                            <p class="text-xs font-medium text-slate-400 mt-1 leading-relaxed">{{ $ach->description }}</p>
                            <div class="flex items-center gap-3 mt-3 text-[11px] font-bold">
                                <span class="bg-[#1E3A8A] text-[#60A5FA] border border-[#3B82F6]/50 px-2.5 py-1 rounded-md capitalize">
                                    {{ $ach->category }}
                                </span>
                                <span class="inline-flex items-center gap-1 text-[#FACC15]">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    +{{ $ach->points_reward }} poin
                                </span>
                            </div>
                            <p class="text-[10px] font-medium text-slate-500 mt-3">
                                Unlocked {{ $ach->pivot->unlocked_at ? \Carbon\Carbon::parse($ach->pivot->unlocked_at)->diffForHumans() : '' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ============ TAB: FOLLOWERS ============ --}}
    <div x-show="tab === 'followers'" x-cloak>
        @if ($followers->isEmpty())
            <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-2xl p-12 text-center shadow-sm">
                <div class="w-16 h-16 mx-auto rounded-full bg-[#152D54] border-2 border-[#1E3A8A] flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-[#60A5FA]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a3 3 0 10-2-5.83"/></svg>
                </div>
                <p class="f-display text-white font-bold text-xl">Belum ada followers</p>
            </div>
        @else
            <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-2xl shadow-sm divide-y-2 divide-[#1E3A8A]/50 overflow-hidden">
                @foreach ($followers as $f)
                    <a href="{{ route('social.profile.show', $f->username) }}" class="flex items-center gap-4 p-4 hover:bg-[#152D54] transition-colors group">
                        <div class="relative flex-shrink-0">
                            <div class="w-12 h-12 rounded-full p-[2px] bg-gradient-to-tr from-[#22D3EE] to-[#3B82F6] group-hover:rotate-6 transition-transform">
                                <div class="w-full h-full rounded-full border-2 border-[#0F1F3D] overflow-hidden bg-[#0F1F3D]">
                                    <x-avatar :avatar="$f->avatar" :user="$f" size="w-full h-full" />
                                </div>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-white group-hover:text-[#FACC15] transition-colors truncate text-sm md:text-base">{{ $f->name }}</p>
                            <p class="text-[11px] font-medium text-slate-400 truncate mt-0.5">{{ '@' . $f->username }}</p>
                        </div>
                        <span class="text-[11px] font-bold text-[#60A5FA] bg-[#152D54] border-2 border-[#1E3A8A] px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-200">Lihat &rarr;</span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ============ TAB: FOLLOWING ============ --}}
    <div x-show="tab === 'following'" x-cloak>
        @if ($following->isEmpty())
            <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-2xl p-12 text-center shadow-sm">
                <div class="w-16 h-16 mx-auto rounded-full bg-[#152D54] border-2 border-[#1E3A8A] flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-[#60A5FA]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                </div>
                <p class="f-display text-white font-bold text-xl">Belum follow siapa pun</p>
            </div>
        @else
            <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-2xl shadow-sm divide-y-2 divide-[#1E3A8A]/50 overflow-hidden">
                @foreach ($following as $f)
                    <a href="{{ route('social.profile.show', $f->username) }}" class="flex items-center gap-4 p-4 hover:bg-[#152D54] transition-colors group">
                        <div class="relative flex-shrink-0">
                            <div class="w-12 h-12 rounded-full p-[2px] bg-gradient-to-tr from-[#22D3EE] to-[#3B82F6] group-hover:rotate-6 transition-transform">
                                <div class="w-full h-full rounded-full border-2 border-[#0F1F3D] overflow-hidden bg-[#0F1F3D]">
                                    <x-avatar :avatar="$f->avatar" :user="$f" size="w-full h-full" />
                                </div>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-white group-hover:text-[#FACC15] transition-colors truncate text-sm md:text-base">{{ $f->name }}</p>
                            <p class="text-[11px] font-medium text-slate-400 truncate mt-0.5">{{ '@' . $f->username }}</p>
                        </div>
                        <span class="text-[11px] font-bold text-[#60A5FA] bg-[#152D54] border-2 border-[#1E3A8A] px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-200">Lihat &rarr;</span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

</div>

<script>
function profilePage(config) {
    return {
        tab: 'posts',
        following: config.initialFollowing,
        followersCount: config.initialFollowers,

        async toggleFollow() {
            try {
                const res = await fetch(`/u/${config.username}/follow`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const data = await res.json();
                if (data.error) {
                    alert(data.error);
                    return;
                }
                this.following = data.following;
                this.followersCount = data.followers_count;
            } catch (e) {
                alert('Gagal follow. Coba lagi.');
            }
        },
    };
}
</script>

@endsection