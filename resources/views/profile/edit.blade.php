@extends('layouts.profile')

@section('title', 'Edit Profil — DIGANOVA')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
        <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-4">
            <h1 class="text-2xl font-bold text-gray-900">Edit Profil</h1>
            <a href="{{ route('profile.show') }}" class="text-sm text-gray-500 hover:text-gray-900 font-medium">Batal</a>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Avatar -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Avatar</label>
                <div class="grid grid-cols-4 sm:grid-cols-5 gap-3">
                    @foreach ($allAvatars as $avatar)
                        @php $isUnlocked = in_array($avatar->id, $unlockedIds); @endphp
                        <label class="cursor-pointer relative {{ !$isUnlocked ? 'opacity-50 grayscale' : '' }}">
                            <input type="radio" name="avatar_id" value="{{ $avatar->id }}" class="peer sr-only" {{ $user->avatar_id == $avatar->id ? 'checked' : '' }} {{ !$isUnlocked ? 'disabled' : '' }}>
                            <div class="rounded-xl border-2 border-transparent peer-checked:border-nova-600 p-1 transition">
                                <x-avatar :avatar="$avatar" :user="$user" size="w-full aspect-square" textSize="text-4xl" />
                            </div>
                            @if (!$isUnlocked)
                                <div class="absolute inset-0 flex items-center justify-center bg-black/40 rounded-xl text-white text-xs font-bold">🔒</div>
                            @endif
                        </label>
                    @endforeach
                </div>
                <p class="text-xs text-gray-500 mt-2">Avatar dengan simbol gembok harus di-unlock melalui pencapaian.</p>
            </div>

            <!-- Nama -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nova-500 outline-none">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Bio -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bio (Maks 160 Karakter)</label>
                <textarea name="bio" rows="3" maxlength="160"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nova-500 outline-none">{{ old('bio', $user->bio) }}</textarea>
                @error('bio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Submit -->
            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-nova-600 hover:bg-nova-700 text-white px-8 py-3 rounded-xl font-bold transition shadow-lg shadow-nova-500/30">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection