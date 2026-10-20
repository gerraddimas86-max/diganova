@extends('social.layouts.app')

@section('title', 'Buat Grup — DIGANOVA Social')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="mb-4">
        <a href="{{ route('social.groups.index') }}" class="text-sm text-violet-600 hover:underline">
            ← Kembali ke daftar grup
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h1 class="text-xl font-bold text-gray-900 mb-1">Buat Grup Baru</h1>
        <p class="text-sm text-gray-500 mb-6">Kumpulkan orang-orang dengan minat sama</p>

        <form method="POST" action="{{ route('social.groups.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Grup *</label>
                <input type="text" name="name" value="{{ old('name') }}" required maxlength="80"
                       placeholder="Contoh: Cosplayer Jakarta"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 outline-none">
                @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Slug (URL)</label>
                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden focus-within:ring-2 focus-within:ring-violet-500">
                    <span class="px-3 py-2.5 bg-gray-50 text-gray-500 text-sm border-r border-gray-300">/groups/</span>
                    <input type="text" name="slug" value="{{ old('slug') }}" maxlength="80"
                           placeholder="cosplayer-jakarta"
                           class="flex-1 px-3 py-2.5 outline-none text-sm font-mono">
                </div>
                <p class="text-xs text-gray-500 mt-1">Kosongkan untuk auto-generate dari nama</p>
                @error('slug') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="4" maxlength="1000"
                          placeholder="Ceritakan tentang grup ini..."
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 outline-none">{{ old('description') }}</textarea>
                @error('description') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Visibilitas *</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="visibility" value="public"
                               class="peer sr-only"
                               @checked(old('visibility', 'public') === 'public')>
                        <div class="border-2 border-gray-200 rounded-lg p-4 peer-checked:border-violet-600 peer-checked:bg-violet-50 transition">
                            <p class="font-semibold text-gray-900">🌐 Public</p>
                            <p class="text-xs text-gray-500 mt-1">Siapa saja bisa langsung join</p>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="visibility" value="private"
                               class="peer sr-only"
                               @checked(old('visibility') === 'private')>
                        <div class="border-2 border-gray-200 rounded-lg p-4 peer-checked:border-violet-600 peer-checked:bg-violet-50 transition">
                            <p class="font-semibold text-gray-900">🔒 Private</p>
                            <p class="text-xs text-gray-500 mt-1">Perlu approval owner</p>
                        </div>
                    </label>
                </div>
                @error('visibility') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-gray-100">
                <a href="{{ route('social.groups.index') }}"
                   class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium text-sm">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-violet-600 hover:bg-violet-700 text-white rounded-lg font-semibold text-sm">
                    Buat Grup
                </button>
            </div>
        </form>
    </div>
</div>

@endsection