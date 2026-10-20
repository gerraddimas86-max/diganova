@extends('social.layouts.app')

@section('title', 'Grup — DIGANOVA Social')

@section('content')

<div class="flex justify-between items-start mb-6 flex-wrap gap-3">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">👥 Grup Komunitas</h1>
        <p class="text-sm text-gray-500 mt-1">Temukan & buat komunitas sesuai minatmu</p>
    </div>
    @auth
        <a href="{{ route('social.groups.create') }}"
           class="bg-violet-600 hover:bg-violet-700 text-white px-5 py-2.5 rounded-lg font-medium text-sm transition">
            + Buat Grup Baru
        </a>
    @endauth
</div>

{{-- Filter --}}
<form method="GET" class="bg-white rounded-xl border border-gray-200 p-4 mb-6 flex flex-col sm:flex-row gap-3">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Cari nama atau deskripsi grup..."
           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 outline-none">
    <select name="visibility"
            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 outline-none">
        <option value="">Semua</option>
        <option value="public" @selected(request('visibility') === 'public')>Public</option>
        <option value="private" @selected(request('visibility') === 'private')>Private</option>
    </select>
    <button type="submit"
            class="bg-gray-800 hover:bg-gray-900 text-white px-5 py-2 rounded-lg font-medium">
        Filter
    </button>
    @if (request()->hasAny(['search', 'visibility']))
        <a href="{{ route('social.groups.index') }}"
           class="text-sm text-gray-500 hover:text-red-600 px-3 py-2">
            Reset
        </a>
    @endif
</form>

{{-- Grid --}}
@if ($groups->isEmpty())
    <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
        <div class="text-5xl mb-3">🏘️</div>
        <p class="text-gray-700 font-medium">Belum ada grup</p>
        @auth
            <a href="{{ route('social.groups.create') }}"
               class="inline-block mt-3 text-violet-600 hover:underline text-sm">
                Buat grup pertama →
            </a>
        @endauth
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($groups as $group)
            @php
                $isJoined = in_array($group->id, $myGroupIds);
            @endphp
            <a href="{{ route('social.groups.show', $group->slug) }}"
               class="block bg-white rounded-xl border border-gray-200 hover:shadow-md hover:border-violet-300 transition p-5 group">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-500 to-crystal-400 flex items-center justify-center text-white text-xl font-bold flex-shrink-0">
                        👥
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-gray-900 line-clamp-1 group-hover:text-violet-600 transition">
                            {{ $group->name }}
                        </h3>
                        <p class="text-xs text-gray-500 truncate">oleh {{ $group->owner->name }}</p>
                    </div>
                    @if ($group->isPrivate())
                        <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded font-medium">🔒 Private</span>
                    @endif
                </div>

                <p class="text-sm text-gray-600 line-clamp-2 min-h-[2.5rem]">
                    {{ $group->description ?: 'Tidak ada deskripsi' }}
                </p>

                <div class="mt-3 pt-3 border-t border-gray-100 flex justify-between items-center text-xs">
                    <span class="text-gray-500">
                        <strong class="text-gray-700">{{ $group->members_count }}</strong> anggota
                    </span>
                    @if ($isJoined)
                        <span class="text-green-600 font-medium">✓ Joined</span>
                    @else
                        <span class="text-violet-600 font-medium">Lihat →</span>
                    @endif
                </div>
            </a>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $groups->links() }}
    </div>
@endif

@endsection