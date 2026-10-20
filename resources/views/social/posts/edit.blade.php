@extends('social.layouts.app')

@section('title', 'Edit Post — DIGANOVA Social')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="mb-4">
        <a href="{{ route('social.posts.index') }}" class="text-sm text-violet-600 hover:underline">
            ← Kembali ke Feed
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h1 class="text-xl font-bold text-gray-900 mb-4">Edit Post</h1>

        <form method="POST" action="{{ route('social.posts.update', $post) }}"
              x-data="{ body: {{ json_encode(old('body', $post->body)) }}, count: {{ strlen(old('body', $post->body)) }} }">
            @csrf
            @method('PUT')

            <textarea name="body" x-model="body"
                      @input="count = body.length"
                      maxlength="500" rows="6" required
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 outline-none resize-none text-sm"></textarea>
            @error('body')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror

            <div class="flex justify-between items-center mt-3">
                <span class="text-xs text-gray-500">
                    <span x-text="count"></span> / 500
                </span>
                <div class="flex gap-2">
                    <a href="{{ route('social.posts.index') }}"
                       class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium">
                        Batal
                    </a>
                    <button type="submit"
                            class="bg-violet-600 hover:bg-violet-700 text-white px-5 py-2 rounded-lg text-sm font-medium">
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection