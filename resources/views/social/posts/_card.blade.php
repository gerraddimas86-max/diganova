@php
    $isMine = $post->user_id === auth()->id();
    $isLiked = auth()->check() && $post->likes()->where('user_id', auth()->id())->exists();
    $isReposted = auth()->check() && $post->reposts()->where('user_id', auth()->id())->exists();
    $isAdmin = auth()->check() && auth()->user()->isAdmin();
@endphp

<article class="bg-white rounded-xl border border-gray-200 hover:border-gray-300 transition p-4"
         x-data="postCard({
            postId: {{ $post->id }},
            initialLiked: {{ $isLiked ? 'true' : 'false' }},
            initialLikes: {{ $post->likes_count }},
            initialReposted: {{ $isReposted ? 'true' : 'false' }},
            initialReposts: {{ $post->reposts_count }},
            initialComments: {{ $post->comments_count }},
            canRepost: {{ $isMine ? 'false' : 'true' }}
         })">

    {{-- Header --}}
    <div class="flex items-start gap-3">
        <a href="{{ route('social.profile.show', $post->user->username) }}">
            <x-avatar :avatar="$post->user->avatar" :user="$post->user" size="w-10 h-10" />
        </a>
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('social.profile.show', $post->user->username) }}"
                   class="font-semibold text-gray-900 hover:underline">
                    {{ $post->user->name }}
                </a>
                <span class="text-xs text-gray-500">{{ '@' . $post->user->username }}</span>
                <span class="text-gray-400 text-xs">·</span>
                <span class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</span>
            </div>

            {{-- Body --}}
            <div class="mt-2 text-gray-900 text-sm whitespace-pre-line break-words">{{ $post->body }}</div>

            {{-- Hashtags (CLICKABLE) --}}
            @if ($post->hashtags->isNotEmpty())
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach ($post->hashtags as $tag)
                        <a href="{{ route('social.hashtag', $tag->tag) }}"
                           class="text-xs text-violet-600 hover:text-violet-800 font-medium hover:underline">
                            #{{ $tag->tag }}
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Actions --}}
            <div class="mt-3 flex items-center gap-1">
                {{-- Like --}}
                <button type="button" @click="toggleLike()"
                        :class="liked ? 'text-red-500' : 'text-gray-500 hover:text-red-500'"
                        class="flex items-center gap-1 text-sm px-2 py-1 rounded hover:bg-red-50 transition">
                    <svg class="w-4 h-4" :fill="liked ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <span x-text="likesCount"></span>
                </button>

                {{-- Comment --}}
                <button type="button" @click="toggleComments()"
                        class="flex items-center gap-1 text-sm px-2 py-1 rounded text-gray-500 hover:text-blue-500 hover:bg-blue-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <span x-text="commentsCount"></span>
                </button>

                {{-- Repost --}}
                <button type="button" @click="toggleRepost()"
                        :disabled="!canRepost"
                        :class="reposted ? 'text-green-600' : 'text-gray-500 hover:text-green-600'"
                        class="flex items-center gap-1 text-sm px-2 py-1 rounded hover:bg-green-50 transition disabled:opacity-40 disabled:cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span x-text="repostsCount"></span>
                </button>

                {{-- Edit / Delete --}}
                @if ($isMine || $isAdmin)
                    <div class="ml-auto flex items-center gap-2">
                        @if ($isMine)
                            <a href="{{ route('social.posts.edit', $post) }}"
                               class="text-xs text-gray-500 hover:text-violet-600">Edit</a>
                        @endif
                        <form method="POST" action="{{ route('social.posts.destroy', $post) }}"
                              onsubmit="return confirm('Hapus post ini?');">
                            @csrf
                            @method('DELETE')
                            <button class="text-xs text-red-500 hover:text-red-700">Hapus</button>
                        </form>
                    </div>
                @endif
            </div>

            {{-- Comment Section --}}
            <div x-show="showComments" x-cloak class="mt-3 pt-3 border-t border-gray-100">
                <div class="space-y-2 mb-3">
                    <template x-for="c in comments" :key="c.id">
                        <div class="flex items-start gap-2">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-gray-400 to-gray-300 flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                                 x-text="c.user.initial"></div>
                            <div class="flex-1 min-w-0 bg-gray-50 rounded-lg px-3 py-2">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-xs font-semibold text-gray-900" x-text="c.user.name"></span>
                                    <span class="text-xs text-gray-500" x-text="'@' + c.user.username"></span>
                                    <span class="text-xs text-gray-400" x-text="c.time_ago"></span>
                                </div>
                                <p class="text-sm text-gray-700 mt-1 whitespace-pre-line break-words" x-text="c.body"></p>
                            </div>
                        </div>
                    </template>
                    <p x-show="comments.length === 0 && !loadingComments"
                       class="text-xs text-gray-400 text-center py-2">
                        Belum ada komentar. Jadi yang pertama!
                    </p>
                    <p x-show="loadingComments" class="text-xs text-gray-400 text-center py-2">
                        Memuat...
                    </p>
                </div>

                @auth
                    <form @submit.prevent="submitComment()" class="flex gap-2">
                        <input type="text" x-model="newComment" maxlength="500"
                               placeholder="Tulis komentar..."
                               autocomplete="off"
                               class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 outline-none">
                        <button type="submit" :disabled="!newComment.trim() || submittingComment"
                                class="bg-violet-600 hover:bg-violet-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition disabled:opacity-50">
                            <span x-show="!submittingComment">Kirim</span>
                            <span x-show="submittingComment">...</span>
                        </button>
                    </form>
                @else
                    <p class="text-xs text-gray-500 text-center">
                        <a href="{{ route('login') }}" class="text-violet-600 hover:underline">Login</a> untuk komentar
                    </p>
                @endauth
            </div>
        </div>
    </div>
</article>

<script>
if (typeof postCard === 'undefined') {
    window.postCard = function (config) {
        return {
            postId: config.postId,
            liked: config.initialLiked,
            likesCount: config.initialLikes,
            reposted: config.initialReposted,
            repostsCount: config.initialReposts,
            commentsCount: config.initialComments,
            canRepost: config.canRepost,
            showComments: false,
            commentsLoaded: false,
            loadingComments: false,
            comments: [],
            newComment: '',
            submittingComment: false,

            csrf() {
                return document.querySelector('meta[name="csrf-token"]').content;
            },

            async toggleLike() {
                if (!{{ auth()->check() ? 'true' : 'false' }}) {
                    window.location.href = '{{ route('login') }}';
                    return;
                }
                try {
                    const res = await fetch(`/posts/${this.postId}/like`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': this.csrf(),
                            'Accept': 'application/json',
                        },
                    });
                    if (!res.ok) return;
                    const data = await res.json();
                    this.liked = data.liked;
                    this.likesCount = data.likes_count;
                } catch (e) {}
            },

            async toggleRepost() {
                if (!this.canRepost) return;
                if (!{{ auth()->check() ? 'true' : 'false' }}) {
                    window.location.href = '{{ route('login') }}';
                    return;
                }
                try {
                    const res = await fetch(`/posts/${this.postId}/repost`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': this.csrf(),
                            'Accept': 'application/json',
                        },
                    });
                    const data = await res.json();
                    if (data.error) {
                        alert(data.error);
                        return;
                    }
                    this.reposted = data.reposted;
                    this.repostsCount = data.reposts_count;
                } catch (e) {}
            },

            async toggleComments() {
                this.showComments = !this.showComments;
                if (this.showComments && !this.commentsLoaded) {
                    await this.loadComments();
                }
            },

            async loadComments() {
                this.loadingComments = true;
                try {
                    const res = await fetch(`/posts/${this.postId}/comments`, {
                        headers: { 'Accept': 'application/json' },
                    });
                    if (res.ok) {
                        const data = await res.json();
                        this.comments = data.comments;
                        this.commentsLoaded = true;
                    }
                } catch (e) {}
                this.loadingComments = false;
            },

            async submitComment() {
                const body = this.newComment.trim();
                if (!body || this.submittingComment) return;
                this.submittingComment = true;
                try {
                    const res = await fetch(`/posts/${this.postId}/comments`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrf(),
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ body }),
                    });
                    if (res.ok) {
                        const data = await res.json();
                        this.comments.push(data.comment);
                        this.commentsCount = data.comments_count;
                        this.newComment = '';
                    }
                } catch (e) {}
                this.submittingComment = false;
            },
        };
    };
}
</script>