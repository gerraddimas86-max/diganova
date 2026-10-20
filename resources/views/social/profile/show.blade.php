@extends('social.layouts.app')

@section('title', $user->name . ' — DIGANOVA Social')

@section('content')

<div x-data="profilePage({
        username: '{{ $user->username }}',
        initialFollowing: {{ $isFollowing ? 'true' : 'false' }},
        initialFollowers: {{ $stats['followers'] }}
     })">

    {{-- Profile Header Banner --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-4">
        {{-- Cover --}}
        <div class="h-32 bg-gradient-to-r from-violet-500 via-nova-500 to-crystal-400"></div>

        <div class="px-6 pb-6">
            {{-- Avatar + Action --}}
            <div class="flex justify-between items-end -mt-12 mb-3">
                <div class="ring-4 ring-white rounded-full">
                    <x-avatar :avatar="$user->avatar" :user="$user" size="w-24 h-24" textSize="text-4xl" />
                </div>

                @if ($isMe)
                    <a href="{{ route('social.profile.edit') }}"
                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium text-sm transition">
                        ✏️ Edit Profil
                    </a>
                @elseif (auth()->check())
                    <button type="button" @click="toggleFollow()"
                            :class="following ? 'bg-gray-100 hover:bg-gray-200 text-gray-700' : 'bg-violet-600 hover:bg-violet-700 text-white'"
                            class="px-6 py-2 rounded-lg font-semibold text-sm transition">
                        <span x-text="following ? '✓ Following' : '+ Follow'"></span>
                    </button>
                @else
                    <a href="{{ route('login') }}"
                       class="bg-violet-600 hover:bg-violet-700 text-white px-6 py-2 rounded-lg font-semibold text-sm transition">
                        Login untuk Follow
                    </a>
                @endif
            </div>

            {{-- Name & Username --}}
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $user->name }}</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ '@' . $user->username }}</p>
            </div>

            {{-- Bio --}}
            @if ($user->bio)
                <p class="text-sm text-gray-700 mt-3 whitespace-pre-line">{{ $user->bio }}</p>
            @endif

            {{-- Meta --}}
            <div class="flex items-center gap-4 mt-3 text-xs text-gray-500">
                <span>📅 Bergabung {{ $user->created_at->format('M Y') }}</span>
                <span>🏅 Rank <strong class="capitalize text-gray-700">{{ $user->rank }}</strong></span>
                <span>⭐ <strong class="text-gray-700">{{ $user->total_points }}</strong> poin</span>
            </div>

            {{-- Stats --}}
            <div class="flex gap-6 mt-4 pt-4 border-t border-gray-100">
                <div>
                    <p class="text-lg font-bold text-gray-900">{{ $stats['posts'] }}</p>
                    <p class="text-xs text-gray-500">Posts</p>
                </div>
                <div>
                    <p class="text-lg font-bold text-gray-900" x-text="followersCount"></p>
                    <p class="text-xs text-gray-500">Followers</p>
                </div>
                <div>
                    <p class="text-lg font-bold text-gray-900">{{ $stats['following'] }}</p>
                    <p class="text-xs text-gray-500">Following</p>
                </div>
                <div>
                    <p class="text-lg font-bold text-gray-900">{{ $stats['achievements'] }}</p>
                    <p class="text-xs text-gray-500">Achievement</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="bg-white rounded-xl border border-gray-200 mb-4 overflow-x-auto">
        <div class="flex min-w-max">
            <button type="button" @click="tab = 'posts'"
                    :class="tab === 'posts' ? 'border-violet-600 text-violet-700 bg-violet-50' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                    class="flex-1 px-4 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap">
                📝 Posts
            </button>
            <button type="button" @click="tab = 'likes'"
                    :class="tab === 'likes' ? 'border-violet-600 text-violet-700 bg-violet-50' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                    class="flex-1 px-4 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap">
                ❤️ Likes
            </button>
            <button type="button" @click="tab = 'achievements'"
                    :class="tab === 'achievements' ? 'border-violet-600 text-violet-700 bg-violet-50' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                    class="flex-1 px-4 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap">
                🏅 Achievement
            </button>
            <button type="button" @click="tab = 'followers'"
                    :class="tab === 'followers' ? 'border-violet-600 text-violet-700 bg-violet-50' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                    class="flex-1 px-4 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap">
                👥 Followers
            </button>
            <button type="button" @click="tab = 'following'"
                    :class="tab === 'following' ? 'border-violet-600 text-violet-700 bg-violet-50' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                    class="flex-1 px-4 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap">
                ➡️ Following
            </button>
        </div>
    </div>

    {{-- ============ TAB: POSTS ============ --}}
    <div x-show="tab === 'posts'" x-cloak class="space-y-4">
        @forelse ($posts as $post)
            @include('social.posts._card', ['post' => $post])
        @empty
            <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                <div class="text-5xl mb-3">📝</div>
                <p class="text-gray-700 font-medium">Belum ada post</p>
                @if ($isMe)
                    <a href="{{ route('social.posts.index') }}"
                       class="inline-block mt-3 text-violet-600 hover:underline text-sm">Buat post pertamamu →</a>
                @endif
            </div>
        @endforelse
    </div>

    {{-- ============ TAB: LIKES ============ --}}
    <div x-show="tab === 'likes'" x-cloak class="space-y-4">
        @forelse ($likedPosts as $post)
            @include('social.posts._card', ['post' => $post])
        @empty
            <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                <div class="text-5xl mb-3">❤️</div>
                <p class="text-gray-700 font-medium">Belum ada post yang di-like</p>
            </div>
        @endforelse
    </div>

    {{-- ============ TAB: ACHIEVEMENTS ============ -->
    --}}
    <div x-show="tab === 'achievements'" x-cloak>
        @if ($achievements->isEmpty())
            <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                <div class="text-5xl mb-3">🏅</div>
                <p class="text-gray-700 font-medium">Belum ada achievement</p>
                <p class="text-sm text-gray-500 mt-1">Ikut event, posting, atau main game untuk unlock!</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach ($achievements as $ach)
                    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-start gap-3">
                        <div class="text-4xl">{{ $ach->icon ?? '🏅' }}</div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900">{{ $ach->name }}</p>
                            <p class="text-xs text-gray-600 mt-1">{{ $ach->description }}</p>
                            <div class="flex items-center gap-3 mt-2 text-xs">
                                <span class="bg-violet-100 text-violet-700 px-2 py-0.5 rounded capitalize">
                                    {{ $ach->category }}
                                </span>
                                <span class="text-gray-500">+{{ $ach->points_reward }} poin</span>
                            </div>
                            <p class="text-xs text-gray-400 mt-2">
                                Unlocked {{ $ach->pivot->unlocked_at ? \Carbon\Carbon::parse($ach->pivot->unlocked_at)->diffForHumans() : '' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ============ TAB: FOLLOWERS ============ --}}
    <div x-show="tab === 'followers'" x-cloak>
        @if ($followers->isEmpty())
            <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                <div class="text-5xl mb-3">👥</div>
                <p class="text-gray-700 font-medium">Belum ada followers</p>
            </div>
        @else
            <div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100">
                @foreach ($followers as $f)
                    <a href="{{ route('social.profile.show', $f->username) }}"
                       class="flex items-center gap-3 p-4 hover:bg-gray-50 transition">
                        <x-avatar :avatar="$f->avatar" :user="$f" size="w-12 h-12" />
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 truncate">{{ $f->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ '@' . $f->username }}</p>
                        </div>
                        <span class="text-xs text-violet-600">Lihat →</span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ============ TAB: FOLLOWING ============ --}}
    <div x-show="tab === 'following'" x-cloak>
        @if ($following->isEmpty())
            <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                <div class="text-5xl mb-3">➡️</div>
                <p class="text-gray-700 font-medium">Belum follow siapa pun</p>
            </div>
        @else
            <div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100">
                @foreach ($following as $f)
                    <a href="{{ route('social.profile.show', $f->username) }}"
                       class="flex items-center gap-3 p-4 hover:bg-gray-50 transition">
                        <x-avatar :avatar="$f->avatar" :user="$f" size="w-12 h-12" />
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 truncate">{{ $f->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ '@' . $f->username }}</p>
                        </div>
                        <span class="text-xs text-violet-600">Lihat →</span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

</div>

<style>[x-cloak]{display:none!important}</style>

<script>
function profilePage(config) {
    return {
        tab: 'posts',
        following: config.initialFollowing,
        followersCount: config.initialFollowers,

        async toggleFollow() {
            try {
                const res = await fetch(`/u/${config.username}/follow`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const data = await res.json();
                if (data.error) {
                    alert(data.error);
                    return;
                }
                this.following = data.following;
                this.followersCount = data.followers_count;
            } catch (e) {
                alert('Gagal follow. Coba lagi.');
            }
        },
    };
}
</script>

@endsection