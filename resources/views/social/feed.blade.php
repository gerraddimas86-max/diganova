@extends('social.layouts.app')

@section('title', 'Feed — DIGANOVA Social')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

    {{-- MAIN FEED --}}
    <div class="lg:col-span-3 space-y-4">

        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Feed</h1>
            <p class="text-sm text-gray-500 mt-1">Apa yang sedang terjadi di DIGANOVA?</p>
        </div>

        {{-- Create Post --}}
        @auth
            <div class="bg-white rounded-xl border border-gray-200 p-4"
                 x-data="{ body: '', count: 0, submitting: false }">
                <form method="POST" action="{{ route('social.posts.store') }}"
                      @submit="submitting = true">
                    @csrf
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-500 to-crystal-400 flex items-center justify-center text-white font-bold flex-shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <textarea name="body" x-model="body"
                                      @input="count = body.length"
                                      maxlength="500" rows="3" required
                                      placeholder="Apa yang kamu pikirkan?"
                                      class="w-full border-0 focus:ring-0 outline-none resize-none text-sm placeholder:text-gray-400"></textarea>
                            @error('body')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                                <div class="text-xs text-gray-500">
                                    <span x-text="count"></span> / 500
                                </div>
                                <button type="submit"
                                        :disabled="!body.trim() || submitting"
                                        class="bg-violet-600 hover:bg-violet-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span x-show="!submitting">Post</span>
                                    <span x-show="submitting">Posting...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @else
            <div class="bg-gradient-to-br from-violet-50 to-crystal-50 rounded-xl border border-violet-200 p-6 text-center">
                <p class="text-gray-700 font-medium">Ingin posting?</p>
                <p class="text-sm text-gray-600 mt-1">Login dulu untuk join percakapan.</p>
                <div class="mt-3 flex justify-center gap-2">
                    <a href="{{ route('login') }}"
                       class="bg-violet-600 hover:bg-violet-700 text-white px-5 py-2 rounded-lg text-sm font-medium">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="bg-white hover:bg-gray-50 text-violet-700 border border-violet-300 px-5 py-2 rounded-lg text-sm font-medium">
                        Daftar
                    </a>
                </div>
            </div>
        @endauth

        {{-- Post List --}}
        @if ($posts->isEmpty())
            <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                <div class="text-5xl mb-3">💬</div>
                <p class="text-gray-700 font-medium">Belum ada post</p>
                <p class="text-sm text-gray-500 mt-1">Jadilah yang pertama memposting!</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($posts as $post)
                    @include('social.posts._card', ['post' => $post])
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="pt-4">
                {{ $posts->links() }}
            </div>
        @endif
    </div>

    {{-- SIDEBAR --}}
    <aside class="hidden lg:block lg:col-span-1">
        <div class="sticky top-20 space-y-4">

            @auth
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-violet-500 to-crystal-400 flex items-center justify-center text-white font-bold text-lg">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ '@' . auth()->user()->username }}</p>
                        </div>
                    </div>
                    <div class="mt-3 grid grid-cols-3 gap-2 text-center text-xs">
                        <div>
                            <p class="font-bold text-gray-900">{{ auth()->user()->posts()->count() }}</p>
                            <p class="text-gray-500">Post</p>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">{{ auth()->user()->followers()->count() }}</p>
                            <p class="text-gray-500">Followers</p>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">{{ auth()->user()->following()->count() }}</p>
                            <p class="text-gray-500">Following</p>
                        </div>
                    </div>
                </div>
            @endauth

            <div class="bg-gradient-to-br from-violet-50 to-crystal-50 rounded-xl border border-violet-100 p-4">
                <p class="font-bold text-gray-900 text-sm">🦊 Selamat datang!</p>
                <p class="text-xs text-gray-600 mt-1">
                    DIGANOVA Social adalah tempat komunitas festival ngobrol, sharing, dan diskusi.
                </p>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="font-bold text-gray-900 text-sm mb-2">🚀 Domain Lain</p>
                <div class="space-y-2 text-xs">
                    <a href="{{ route('event.home') }}" class="block text-violet-600 hover:underline">→ Event</a>
                    <a href="{{ route('play.home') }}" class="block text-violet-600 hover:underline">→ Playground</a>
                </div>
            </div>
        </div>
    </aside>
</div>

@endsection