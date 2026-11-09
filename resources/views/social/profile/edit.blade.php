@extends('social.layouts.app')

@section('title', 'Edit Profil — DIGANOVA Social')

@section('content')

<div class="relative z-10 max-w-2xl mx-auto px-4 py-6 sm:py-8"
     x-data="{ 
         selectedAvatarId: {{ old('avatar_id', $user->avatar_id ?? 'null') }},
         submitting: false
     }">

    {{-- Back link --}}
    <div class="mb-5">
        <a href="{{ route('social.profile.show', $user->username) }}"
           class="inline-flex items-center gap-1.5 text-sm font-bold text-[#60A5FA] hover:text-white transition-colors group">
            <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke profil
        </a>
    </div>

    <form method="POST" action="{{ route('social.profile.update') }}" class="space-y-6" @submit="submitting = true">
        @csrf
        @method('PUT')

        {{-- Avatar Picker Section --}}
        <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-3xl shadow-sm overflow-hidden">
            <div class="px-6 pt-6 pb-5 border-b-2 border-[#1E3A8A] bg-[#152D54]">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-[#1E3A8A] border-2 border-[#3B82F6] flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg class="w-6 h-6 text-[#FACC15]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="f-display text-xl font-bold text-white tracking-tight">Pilih Avatar</h2>
                        <p class="text-sm text-slate-400 mt-0.5 font-medium">2 avatar gratis + 8 avatar unlock via achievement.</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-4 sm:grid-cols-5 gap-3 md:gap-4">
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

                            <div class="relative aspect-square rounded-2xl border-2 transition-all duration-300
                                        {{ $isUnlocked 
                                            ? 'border-[#1E3A8A] bg-[#152D54] hover:border-[#3B82F6] peer-checked:border-[#FACC15] peer-checked:bg-[#1E3A8A]' 
                                            : 'border-gray-800 bg-[#0F1F3D] opacity-60 grayscale' }}">
                                
                                <div class="w-full h-full flex items-center justify-center p-1.5">
                                    <x-avatar :avatar="$av" :user="$user" size="w-full h-full rounded-xl" textSize="text-3xl" />
                                </div>

                                @if ($isUnlocked)
                                    <div class="absolute -top-2 -right-2 w-7 h-7 bg-[#FACC15] rounded-full items-center justify-center text-[#1E3A8A] shadow-sm border-[2px] border-[#1E3A8A] hidden peer-checked:flex animate-in zoom-in duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                @endif

                                @if (!$isUnlocked)
                                    <div class="absolute inset-0 bg-[#0F1F3D]/80 backdrop-blur-sm rounded-2xl flex flex-col items-center justify-center text-white text-[10px] p-2 text-center">
                                        <svg class="w-5 h-5 mb-1 text-[#60A5FA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                        <span class="leading-tight font-bold">{{ $av->unlock_description ?? 'Locked' }}</span>
                                    </div>
                                @endif

                                @if ($isDefault && $isUnlocked)
                                    <div class="absolute top-1.5 left-1.5 bg-[#22D3EE] text-[#1E3A8A] text-[9px] uppercase tracking-wider font-bold px-2 py-0.5 rounded shadow-sm border border-[#1E3A8A]">
                                        FREE
                                    </div>
                                @endif
                            </div>

                            <p class="text-center text-[11px] md:text-xs font-bold mt-2 truncate transition-colors
                                      {{ $isUnlocked ? 'text-slate-400 peer-checked:text-[#FACC15]' : 'text-slate-600' }}">
                                {{ $av->name }}
                            </p>
                        </label>
                    @endforeach
                </div>

                @error('avatar_id')
                    <p class="flex items-center gap-1.5 text-xs font-semibold text-[#F43F5E] mt-3">
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        {{-- Bio Section --}}
        <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-3xl shadow-sm overflow-hidden"
             x-data="{ bio: {{ json_encode(old('bio', $user->bio ?? '')) }}, count: {{ strlen(old('bio', $user->bio ?? '')) }} }">
            <div class="px-6 pt-6 pb-5 border-b-2 border-[#1E3A8A] bg-[#152D54]">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-[#1E3A8A] border-2 border-[#3B82F6] flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg class="w-6 h-6 text-[#FACC15]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="f-display text-xl font-bold text-white tracking-tight">Bio Singkat</h2>
                        <p class="text-sm text-slate-400 mt-0.5 font-medium">Ceritakan sedikit tentang dirimu</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="flex items-center justify-between mb-2">
                    <label for="bio" class="block text-sm font-bold text-white">Bio</label>
                    <span class="text-[11px] font-bold text-[#60A5FA] bg-[#152D54] px-2 py-0.5 rounded-md border-2 border-[#1E3A8A]">
                        <span x-text="count">0</span>/160
                    </span>
                </div>

                <textarea name="bio" id="bio" x-model="bio" @input="count = bio.length"
                          maxlength="160" rows="4"
                          placeholder="Ceritakan sedikit tentang dirimu..."
                          class="w-full px-4 py-3 border-2 rounded-xl outline-none resize-y transition-all text-sm font-medium text-white placeholder-slate-500 bg-[#152D54]
                                 @error('bio') border-[#F43F5E] focus:border-[#F43F5E]
                                 @else border-[#1E3A8A] focus:border-[#3B82F6] @enderror"></textarea>
                
                @error('bio')
                    <p class="flex items-center gap-1.5 text-xs font-semibold text-[#F43F5E] mt-2">
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        {{-- Read-only info --}}
        <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-3xl p-6 shadow-sm text-sm">
            <h3 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Informasi Akun</h3>
            <div class="space-y-3">
                <p class="flex justify-between items-center border-b-2 border-[#1E3A8A]/50 pb-3">
                    <span class="font-medium text-slate-400">Nama</span>
                    <strong class="text-white">{{ $user->name }}</strong>
                </p>
                <p class="flex justify-between items-center border-b-2 border-[#1E3A8A]/50 pb-3">
                    <span class="font-medium text-slate-400">Username</span>
                    <strong class="text-white">{{ '@' . $user->username }} <span class="text-[10px] text-slate-500 font-normal ml-1">(Permanen)</span></strong>
                </p>
                <p class="flex justify-between items-center">
                    <span class="font-medium text-slate-400">Email</span>
                    <strong class="text-white">{{ $user->email }}</strong>
                </p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2">
            <a href="{{ route('social.profile.show', $user->username) }}"
               class="inline-flex items-center justify-center px-6 py-2.5 bg-[#152D54] hover:bg-[#1E3A8A]
                      text-slate-300 hover:text-white rounded-xl font-bold text-sm transition-colors duration-200 border-2 border-[#1E3A8A]">
                Batal
            </a>
            <button type="submit" 
                    :disabled="submitting"
                    class="inline-flex items-center justify-center gap-2 px-8 py-2.5
                           bg-[#FACC15] hover:bg-[#EAB308] text-[#1E3A8A]
                           rounded-xl font-black text-sm transition-all duration-300
                           border-[3px] border-[#1E3A8A] shadow-[3px_3px_0_#1E3A8A] hover:-translate-y-0.5 hover:shadow-[5px_5px_0_#1E3A8A]
                           disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0 disabled:hover:shadow-[3px_3px_0_#1E3A8A] min-w-[150px]">
                
                <svg x-show="submitting" class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                </svg>
                
                <span x-text="submitting ? 'Menyimpan...' : 'Simpan Perubahan'"></span>
            </button>
        </div>
    </form>
</div>

@endsection