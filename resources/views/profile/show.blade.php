@extends('layouts.profile')

@section('title', 'Profil ' . $user->name . ' — DIGANOVA')

@section('content')
<div x-data="{ tab: 'overview' }">
    <!-- Hero Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 mb-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-nova-100 to-crystal-100 rounded-full blur-3xl opacity-50 -translate-y-1/2 translate-x-1/3"></div>
        <div class="relative flex flex-col md:flex-row gap-6 items-center md:items-start">
            <div class="relative">
                <x-avatar :avatar="$user->avatar" :user="$user" size="w-28 h-28" textSize="text-5xl ring-4 ring-white shadow-lg" />
            </div>
            <div class="flex-1 text-center md:text-left">
                <div class="flex flex-col md:flex-row md:items-center gap-3 mb-2 justify-center md:justify-start">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-sm font-medium">@ {{ $user->username }}</span>
                </div>
                <p class="text-gray-600 max-w-2xl">{{ $user->bio ?? 'Belum ada bio.' }}</p>
                
                <div class="mt-4 flex flex-wrap justify-center md:justify-start gap-3 text-sm">
                    <div class="bg-yellow-50 text-yellow-700 px-3 py-1.5 rounded-lg border border-yellow-100 font-semibold capitalize">🏅 Rank: {{ $user->rank }}</div>
                    <div class="bg-cyan-50 text-cyan-700 px-3 py-1.5 rounded-lg border border-cyan-100 font-semibold">⭐ {{ $user->total_points }} Poin</div>
                    <div class="bg-violet-50 text-violet-700 px-3 py-1.5 rounded-lg border border-violet-100 font-semibold">🎖️ {{ $user->achievements->count() }} Achievement</div>
                </div>
            </div>
            <div>
                <a href="{{ route('profile.edit') }}" class="bg-gray-900 hover:bg-gray-800 text-white px-5 py-2.5 rounded-lg font-medium transition text-sm shadow-md whitespace-nowrap">Edit Profil</a>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-100 p-1 mb-6 hide-scrollbar">
        <button @click="tab = 'overview'" :class="tab === 'overview' ? 'bg-nova-50 text-nova-700' : 'text-gray-600 hover:bg-gray-50'" class="flex-1 px-4 py-3 rounded-lg text-sm font-medium transition whitespace-nowrap">Overview</button>
        <button @click="tab = 'event'" :class="tab === 'event' ? 'bg-nova-50 text-nova-700' : 'text-gray-600 hover:bg-gray-50'" class="flex-1 px-4 py-3 rounded-lg text-sm font-medium transition whitespace-nowrap">🎟️ Event</button>
        <button @click="tab = 'social'" :class="tab === 'social' ? 'bg-nova-50 text-nova-700' : 'text-gray-600 hover:bg-gray-50'" class="flex-1 px-4 py-3 rounded-lg text-sm font-medium transition whitespace-nowrap">💬 Social</button>
        <button @click="tab = 'play'" :class="tab === 'play' ? 'bg-nova-50 text-nova-700' : 'text-gray-600 hover:bg-gray-50'" class="flex-1 px-4 py-3 rounded-lg text-sm font-medium transition whitespace-nowrap">🎮 Playground</button>
    </div>

    <!-- Tab Content -->
    <!-- OVERVIEW -->
    <div x-show="tab === 'overview'" x-transition.opacity>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="font-bold text-gray-900 mb-4 text-lg">Achievement Terbaru</h2>
                @if ($user->achievements->isEmpty())
                    <p class="text-gray-500 text-sm">Belum ada achievement.</p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach ($user->achievements->take(4) as $ach)
                            <div class="flex items-center gap-3 bg-gray-50 p-3 rounded-lg border border-gray-100">
                                <div class="text-3xl">{{ $ach->icon ?? '🏅' }}</div>
                                <div>
                                    <p class="font-semibold text-gray-900 text-sm">{{ $ach->name }}</p>
                                    <p class="text-xs text-gray-500 line-clamp-1">{{ $ach->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="font-bold text-gray-900 mb-4 text-lg">Aktivitas Terakhir</h2>
                <div class="space-y-4">
                    @if ($recentPosts->isNotEmpty())
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-violet-100 text-violet-600 flex items-center justify-center flex-shrink-0">💬</div>
                            <div>
                                <p class="text-sm text-gray-800">Kamu membuat post di Social</p>
                                <p class="text-xs text-gray-500">{{ $recentPosts->first()->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endif
                    @if ($eventRegistrations->isNotEmpty())
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-nova-100 text-nova-600 flex items-center justify-center flex-shrink-0">🎟️</div>
                            <div>
                                <p class="text-sm text-gray-800">Mendaftar event {{ $eventRegistrations->first()->event->title }}</p>
                                <p class="text-xs text-gray-500">{{ $eventRegistrations->first()->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endif
                    @if ($recentBattles->isNotEmpty())
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center flex-shrink-0">⚔️</div>
                            <div>
                                <p class="text-sm text-gray-800">Menyelesaikan pertempuran Navo Clash</p>
                                <p class="text-xs text-gray-500">{{ $recentBattles->first()->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- EVENT -->
    <div x-show="tab === 'event'" x-transition.opacity>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-bold text-gray-900">Riwayat Event</h2>
            </div>
            @if ($eventRegistrations->isEmpty())
                <p class="p-6 text-gray-500 text-center">Belum pernah mendaftar event.</p>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach ($eventRegistrations as $reg)
                        <div class="p-4 sm:px-6 flex items-center justify-between gap-4 hover:bg-gray-50">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $reg->event->title }}</p>
                                <p class="text-xs text-gray-500">{{ $reg->ticketCategory->name }} · {{ $reg->event->start_date->format('d M Y') }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $reg->payment_status === 'verified' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst($reg->payment_status) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- SOCIAL -->
    <div x-show="tab === 'social'" x-transition.opacity>
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
                <p class="text-3xl font-bold text-gray-900">{{ $socialStats['posts'] }}</p>
                <p class="text-sm text-gray-500 mt-1">Posts</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
                <p class="text-3xl font-bold text-gray-900">{{ $socialStats['followers'] }}</p>
                <p class="text-sm text-gray-500 mt-1">Followers</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
                <p class="text-3xl font-bold text-gray-900">{{ $socialStats['following'] }}</p>
                <p class="text-sm text-gray-500 mt-1">Following</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
            <p class="text-gray-600 mb-4">Lihat profil sosial lengkapmu di domain Social.</p>
            <a href="{{ route('social.profile.show', $user->username) }}" class="inline-block bg-violet-600 hover:bg-violet-700 text-white px-6 py-2 rounded-lg font-medium transition">Buka Profil Social</a>
        </div>
    </div>

    <!-- PLAYGROUND -->
    <div x-show="tab === 'play'" x-transition.opacity>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-6 mb-6">
                <x-navo :skin="$navoProfile?->equippedSkin" size="w-20 h-20" textSize="text-4xl" />
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 capitalize">{{ $user->rank }}</h2>
                    <p class="text-gray-500">{{ $navoProfile?->wins ?? 0 }} Wins / {{ $navoProfile?->losses ?? 0 }} Losses</p>
                </div>
            </div>
            <div class="text-center bg-gray-50 rounded-lg p-6 border border-gray-100">
                <p class="text-gray-600 mb-4">Kelola deck dan profil Navo-mu di Playground.</p>
                <a href="{{ route('play.profile.show') }}" class="inline-block bg-cyan-600 hover:bg-cyan-700 text-white px-6 py-2 rounded-lg font-medium transition">Buka Profil Navo</a>
            </div>
        </div>
    </div>
</div>
@endsection