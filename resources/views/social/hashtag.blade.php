@extends('social.layouts.app')

@section('title', '#' . $hashtag->tag . ' — DIGANOVA Social')

@section('content')

<div class="mb-4">
    <a href="{{ route('social.posts.index') }}" class="text-sm text-violet-600 hover:underline">
        ← Kembali ke Feed
    </a>
</div>

{{-- Header --}}
<div class="bg-gradient-to-br from-violet-50 to-crystal-50 rounded-xl border border-violet-100 p-6 mb-6">
    <div class="flex items-center gap-3">
        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-violet-500 to-crystal-400 flex items-center justify-center text-white font-bold text-2xl">
            #
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">#{{ $hashtag->tag }}</h1>
            <p class="text-sm text-gray-600 mt-0.5">
                {{ number_format($hashtag->post_count, 0, ',', '.') }} post
            </p>
        </div>
    </div>
</div>

{{-- Posts --}}
@if ($posts->isEmpty())
    <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
        <div class="text-5xl mb-3">🏷️</div>
        <p class="text-gray-700 font-medium">Belum ada post dengan hashtag ini</p>
    </div>
@else
    <div class="space-y-4">
        @foreach ($posts as $post)
            @include('social.posts._card', ['post' => $post])
        @endforeach
    </div>

    <div class="mt-6">
        {{ $posts->links() }}
    </div>
@endif

@endsection