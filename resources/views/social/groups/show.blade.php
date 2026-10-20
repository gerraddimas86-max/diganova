@extends('social.layouts.app')

@section('title', $group->name . ' — DIGANOVA Social')

@section('content')

{{-- Header --}}
<div class="bg-gradient-to-br from-violet-600 via-nova-500 to-crystal-400 rounded-2xl p-6 text-white mb-4">
    <div class="flex items-start gap-4">
        <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-3xl flex-shrink-0">
            👥
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 flex-wrap">
                <h1 class="text-2xl font-bold">{{ $group->name }}</h1>
                @if ($group->isPrivate())
                    <span class="text-xs bg-white/20 backdrop-blur px-2 py-0.5 rounded font-semibold">🔒 Private</span>
                @else
                    <span class="text-xs bg-white/20 backdrop-blur px-2 py-0.5 rounded font-semibold">🌐 Public</span>
                @endif
            </div>
            <p class="text-sm text-white/90 mt-1">
                oleh <strong>{{ $group->owner->name }}</strong> · {{ $group->members_count }} anggota
            </p>
            @if ($group->description)
                <p class="text-sm text-white/85 mt-3 whitespace-pre-line">{{ $group->description }}</p>
            @endif
        </div>
    </div>
</div>

{{-- Action Buttons --}}
<div class="bg-white rounded-xl border border-gray-200 p-4 mb-4 flex flex-wrap gap-2">
    @auth
        @if ($isAdmin)
            <a href="{{ route('social.groups.edit', $group->slug) }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium text-sm">
                ✏️ Edit Grup
            </a>
            <form method="POST" action="{{ route('social.groups.destroy', $group->slug) }}"
                  onsubmit="return confirm('Yakin hapus grup ini? Semua data akan hilang.');">
                @csrf
                @method('DELETE')
                <button class="bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded-lg font-medium text-sm">
                    🗑️ Hapus Grup
                </button>
            </form>
        @endif

        @if ($isMember)
            @if (!$group->isOwner(auth()->user()))
                <form method="POST" action="{{ route('social.groups.leave', $group->slug) }}"
                      onsubmit="return confirm('Keluar dari grup ini?');">
                    @csrf
                    <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium text-sm">
                        🚪 Keluar Grup
                    </button>
                </form>
            @endif
        @elseif ($hasPending)
            <div class="flex-1 bg-yellow-50 border border-yellow-200 rounded-lg px-4 py-2 text-sm text-yellow-800">
                ⏳ Permintaan bergabung menunggu approval owner
            </div>
        @else
            <form method="POST" action="{{ route('social.groups.join', $group->slug) }}" class="flex-1">
                @csrf
                <button class="w-full bg-violet-600 hover:bg-violet-700 text-white px-6 py-2 rounded-lg font-semibold text-sm">
                    {{ $group->isPrivate() ? '🔒 Minta Join' : '+ Join Grup' }}
                </button>
            </form>
        @endif
    @else
        <a href="{{ route('login') }}"
           class="flex-1 text-center bg-violet-600 hover:bg-violet-700 text-white px-6 py-2 rounded-lg font-semibold text-sm">
            Login untuk Join
        </a>
    @endauth
</div>

{{-- Content --}}
<div class="space-y-4">

    {{-- Pending Requests (owner/admin only) --}}
    @if ($isAdmin && $pendingMembers->isNotEmpty())
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
            <h2 class="font-bold text-yellow-900 mb-3">
                ⏳ Permintaan Bergabung ({{ $pendingMembers->count() }})
            </h2>
            <div class="space-y-2">
                @foreach ($pendingMembers as $pending)
                    <div class="bg-white rounded-lg p-3 flex items-center gap-3">
                        <x-avatar :avatar="$pending->avatar" :user="$pending" size="w-10 h-10" />
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 text-sm">{{ $pending->name }}</p>
                            <p class="text-xs text-gray-500">{{ '@' . $pending->username }}</p>
                        </div>
                        <form method="POST" action="{{ route('social.groups.approve', [$group->slug, $pending->id]) }}">
                            @csrf
                            <button class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-1.5 rounded font-medium">
                                ✓ Approve
                            </button>
                        </form>
                        <form method="POST" action="{{ route('social.groups.reject', [$group->slug, $pending->id]) }}">
                            @csrf
                            <button class="bg-red-100 hover:bg-red-200 text-red-700 text-xs px-3 py-1.5 rounded font-medium">
                                ✕ Reject
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Members List --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <h2 class="font-bold text-gray-900 mb-3">👥 Anggota ({{ $members->count() }})</h2>

        @if ($members->isEmpty())
            <p class="text-sm text-gray-500 text-center py-6">Belum ada anggota</p>
        @else
            <div class="space-y-1">
                @foreach ($members as $member)
                    <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50">
                        <a href="{{ route('social.profile.show', $member->username) }}">
                            <x-avatar :avatar="$member->avatar" :user="$member" size="w-10 h-10" />
                        </a>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <a href="{{ route('social.profile.show', $member->username) }}"
                                   class="font-medium text-gray-900 hover:underline text-sm">
                                    {{ $member->name }}
                                </a>
                                @if ($member->pivot->role === 'admin')
                                    <span class="text-xs bg-violet-100 text-violet-700 px-2 py-0.5 rounded font-medium">Admin</span>
                                @endif
                                @if ($group->owner_id === $member->id)
                                    <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded font-medium">Owner</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500">{{ '@' . $member->username }}</p>
                        </div>

                        @if ($isAdmin && $member->id !== auth()->id() && $group->owner_id !== $member->id)
                            <div class="flex gap-1">
                                @if ($group->isOwner(auth()->user()))
                                    @if ($member->pivot->role === 'member')
                                        <form method="POST" action="{{ route('social.groups.promote', [$group->slug, $member->id]) }}">
                                            @csrf
                                            <button class="text-xs text-violet-600 hover:text-violet-800 font-medium px-2 py-1">Promote</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('social.groups.demote', [$group->slug, $member->id]) }}">
                                            @csrf
                                            <button class="text-xs text-gray-500 hover:text-gray-700 font-medium px-2 py-1">Demote</button>
                                        </form>
                                    @endif
                                @endif
                                <form method="POST" action="{{ route('social.groups.kick', [$group->slug, $member->id]) }}"
                                      onsubmit="return confirm('Kick {{ $member->name }} dari grup?');">
                                    @csrf
                                    <button class="text-xs text-red-500 hover:text-red-700 font-medium px-2 py-1">Kick</button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@endsection