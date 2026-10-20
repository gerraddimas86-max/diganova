@extends('social.layouts.app')

@section('title', 'Edit Profil — DIGANOVA Social')

@section('content')

<div class="max-w-2xl mx-auto"
     x-data="{ selectedAvatarId: {{ old('avatar_id', $user->avatar_id ?? 'null') }} }">

    <div class="mb-4">
        <a href="{{ route('social.profile.show', $user->username) }}"
           class="text-sm text-violet-600 hover:underline">
            ← Kembali ke profil
        </a>
    </div>

    <form method="POST" action="{{ route('social.profile.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Avatar Picker --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="font-bold text-gray-900 mb-1">Avatar</h2>
            <p class="text-xs text-gray-500 mb-4">
                2 avatar gratis + 8 avatar unlock via achievement.
                Avatar dengan 🔒 belum terbuka.
            </p>

            <div class="grid grid-cols-5 sm:grid-cols-5 gap-3">
                @foreach ($allAvatars as $av)
                    @php
                        $isUnlocked = in_array($av->id, $unlockedIds);
                        $isDefault = $av->is_default;
                    @endphp
                    <label class="cursor-pointer {{ $isUnlocked ? '' : 'cursor-not-allowed' }}">
                        <input type="radio" name="avatar_id" value="{{ $av->id }}"
                               class="peer sr-only"
                               x-model.number="selectedAvatarId"
                               @if ($isUnlocked) @checked(old('avatar_id', $user->avatar_id) == $av->id) @endif
                               @if (!$isUnlocked) disabled @endif>

                        <div class="relative aspect-square rounded-xl border-2 transition
                                    {{ $isUnlocked ? 'border-gray-200 peer-checked:border-violet-600 peer-checked:ring-2 peer-checked:ring-violet-200 hover:border-gray-300' : 'border-gray-100 opacity-50' }}">
                            <div class="w-full h-full flex items-center justify-center">
                                <x-avatar :avatar="$av" :user="$user" size="w-full h-full" textSize="text-3xl" />
                            </div>

                            {{-- Selected check --}}
                            @if ($isUnlocked)
                                <div class="absolute top-1 right-1 w-5 h-5 bg-violet-600 rounded-full items-center justify-center text-white text-xs hidden peer-checked:flex">
                                    ✓
                                </div>
                            @endif

                            {{-- Locked overlay --}}
                            @if (!$isUnlocked)
                                <div class="absolute inset-0 bg-black/40 rounded-xl flex flex-col items-center justify-center text-white text-xs p-1 text-center">
                                    <span class="text-2xl">🔒</span>
                                    <span class="mt-1 leading-tight">{{ $av->unlock_description ?? 'Locked' }}</span>
                                </div>
                            @endif

                            {{-- Default badge --}}
                            @if ($isDefault)
                                <div class="absolute top-1 left-1 bg-green-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded">
                                    FREE
                                </div>
                            @endif
                        </div>

                        <p class="text-center text-xs text-gray-600 mt-1 truncate">{{ $av->name }}</p>
                    </label>
                @endforeach
            </div>

            @error('avatar_id')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- Bio --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6"
             x-data="{ bio: {{ json_encode(old('bio', $user->bio ?? '')) }}, count: {{ strlen(old('bio', $user->bio ?? '')) }} }">
            <h2 class="font-bold text-gray-900 mb-1">Bio</h2>
            <p class="text-xs text-gray-500 mb-3">Maks 160 karakter</p>

            <textarea name="bio" x-model="bio" @input="count = bio.length"
                      maxlength="160" rows="4"
                      placeholder="Ceritakan sedikit tentang dirimu..."
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 outline-none resize-none text-sm"></textarea>
            @error('bio')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror

            <div class="flex justify-between items-center mt-2">
                <span class="text-xs text-gray-500">
                    <span x-text="count"></span> / 160
                </span>
            </div>
        </div>

        {{-- Read-only info --}}
        <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 text-xs text-gray-500">
            <p><strong class="text-gray-700">Nama:</strong> {{ $user->name }}</p>
            <p class="mt-1"><strong class="text-gray-700">Username:</strong> {{ '@' . $user->username }} <span class="text-gray-400">(tidak bisa diubah)</span></p>
            <p class="mt-1"><strong class="text-gray-700">Email:</strong> {{ $user->email }}</p>
        </div>

        {{-- Actions --}}
        <div class="flex justify-end gap-2">
            <a href="{{ route('social.profile.show', $user->username) }}"
               class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium text-sm">
                Batal
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-violet-600 hover:bg-violet-700 text-white rounded-lg font-semibold text-sm">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

@endsection