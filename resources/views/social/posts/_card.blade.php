@php
    $isMine = $post->user_id === auth()->id();
    $isLiked = auth()->check() && $post->likes()->where('user_id', auth()->id())->exists();
    $isReposted = auth()->check() && $post->reposts()->where('user_id', auth()->id())->exists();
    $isAdmin = auth()->check() && auth()->user()->isAdmin();
@endphp

{{-- CARD UTAMA --}}
<article class="bg-[#0F1F3D] rounded-2xl p-4 md:p-5 relative border-2 border-[#3B82F6] shadow-[0_0_0_4px_#1E3A8A] hover:-translate-y-1 hover:border-[#60A5FA] hover:shadow-[0_0_0_4px_#1E3A8A,0_10px_20px_rgba(59,130,246,0.2)] transition-all duration-300 ease-out group" 
         x-data="{
            ...postCard({
                postId: {{ $post->id }},
                initialLiked: {{ $isLiked ? 'true' : 'false' }},
                initialLikes: {{ $post->likes_count }},
                initialReposted: {{ $isReposted ? 'true' : 'false' }},
                initialReposts: {{ $post->reposts_count }},
                initialComments: {{ $post->comments_count }},
                canRepost: {{ $isMine ? 'false' : 'true' }}
            }),
            showDeleteModal: false
         }">

    {{-- Header --}}
    <div class="flex items-start gap-3 md:gap-4">
        <a href="{{ route('social.profile.show', $post->user->username) }}" class="flex-shrink-0">
            {{-- Bingkai Avatar (Gradient Kuning-Cyan) --}}
            <div class="relative p-[2px] rounded-full bg-gradient-to-tr from-[#FACC15] to-[#22D3EE] group-hover:scale-105 transition-transform duration-300 shadow-sm">
                <div class="bg-[#0F1F3D] rounded-full">
                    <x-avatar :avatar="$post->user->avatar" :user="$post->user" size="w-10 h-10 md:w-11 md:h-11 rounded-full" />
                </div>
            </div>
        </a>
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 flex-wrap mb-1">
                {{-- Nama & Username --}}
                <a href="{{ route('social.profile.show', $post->user->username) }}"
                   class="f-display font-bold text-white hover:text-[#FACC15] transition-colors text-sm md:text-base truncate">
                    {{ $post->user->name }}
                </a>
                <span class="text-[11px] md:text-xs font-medium text-slate-400 truncate">{{ '@' . $post->user->username }}</span>
                <span class="text-slate-600 text-[10px]">•</span>
                <span class="text-[11px] md:text-xs font-medium text-slate-500">{{ $post->created_at->diffForHumans() }}</span>
            </div>

            {{-- Body Postingan --}}
            <div class="mt-2 text-slate-200 text-sm md:text-[15px] leading-relaxed whitespace-pre-line break-words">{{ $post->body }}</div>

            {{-- Hashtags (Stiker Kuning) --}}
            @if ($post->hashtags->isNotEmpty())
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach ($post->hashtags as $tag)
                        <a href="{{ route('social.hashtag', $tag->tag) }}"
                           class="text-[11px] md:text-xs font-black text-[#1E3A8A] bg-[#FACC15] border-2 border-[#1E3A8A] px-2.5 py-1 rounded-lg hover:scale-105 transition-transform shadow-[1px_1px_0_#1E3A8A]">
                            #{{ $tag->tag }}
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Actions (Like, Comment, Repost) --}}
            <div class="mt-4 pt-3 border-t-2 border-[#1E3A8A]/50 flex items-center gap-2">
                {{-- Like --}}
                <button type="button" @click="toggleLike()"
                        :class="liked ? 'text-[#F43F5E] bg-[#F43F5E]/10 border-[#F43F5E]/30 shadow-[2px_2px_0_#F43F5E]' : 'text-slate-300 bg-[#152D54] border-[#1E3A8A] hover:text-[#F43F5E] hover:bg-[#F43F5E]/10'"
                        class="flex items-center gap-1.5 text-xs md:text-sm font-bold px-3 py-1.5 rounded-xl border-2 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0">
                    <svg class="w-4 h-4" :fill="liked ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <span x-text="likesCount"></span>
                </button>

                {{-- Comment --}}
                <button type="button" @click="toggleComments()"
                        :class="showComments ? 'text-[#60A5FA] bg-[#1E3A8A] border-[#3B82F6] shadow-[2px_2px_0_#3B82F6]' : 'text-slate-300 bg-[#152D54] border-[#1E3A8A] hover:text-[#60A5FA] hover:bg-[#1E3A8A]'"
                        class="flex items-center gap-1.5 text-xs md:text-sm font-bold px-3 py-1.5 rounded-xl border-2 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <span x-text="commentsCount"></span>
                </button>

                {{-- Repost --}}
                <button type="button" @click="toggleRepost()"
                        :disabled="!canRepost"
                        :class="reposted ? 'text-emerald-400 bg-emerald-400/10 border-emerald-400/30 shadow-[2px_2px_0_#34D399]' : 'text-slate-300 bg-[#152D54] border-[#1E3A8A] hover:text-emerald-400 hover:bg-emerald-400/10'"
                        class="flex items-center gap-1.5 text-xs md:text-sm font-bold px-3 py-1.5 rounded-xl border-2 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:translate-y-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span x-text="repostsCount"></span>
                </button>

                {{-- Edit / Delete --}}
                @if ($isMine || $isAdmin)
                    <div class="ml-auto flex items-center gap-2">
                        @if ($isMine)
                            <a href="{{ route('social.posts.edit', $post) }}"
                               class="text-[11px] md:text-xs font-bold text-[#1E3A8A] bg-[#60A5FA] border-2 border-[#1E3A8A] hover:bg-[#3B82F6] hover:text-white px-2.5 py-1.5 rounded-lg transition-colors hover:-translate-y-0.5 shadow-sm">
                                Edit
                            </a>
                        @endif
                        
                        <form method="POST" action="{{ route('social.posts.destroy', $post) }}" 
                              @submit.prevent="showDeleteModal = true"
                              x-ref="deleteForm">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="text-[11px] md:text-xs font-bold text-white bg-[#F43F5E] border-2 border-[#1E3A8A] hover:bg-red-600 px-2.5 py-1.5 rounded-lg transition-colors hover:-translate-y-0.5 shadow-sm">
                                Hapus
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            {{-- Comment Section --}}
            <div x-show="showComments" x-cloak class="mt-4 p-3 md:p-4 bg-[#152D54] border-2 border-[#1E3A8A] rounded-xl shadow-inner">
                <div class="space-y-3 mb-4">
                    <template x-for="c in comments" :key="c.id">
                        <div class="flex items-start gap-3">
                            {{-- Avatar Komentar (Menggunakan inisial jika API tidak mengirim avatar, atau bisa disesuaikan) --}}
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#FACC15] to-[#22D3EE] flex items-center justify-center text-[#1E3A8A] text-xs font-black flex-shrink-0 shadow-sm border-2 border-[#1E3A8A]"
                                 x-text="c.user.initial"></div>
                            
                            <div class="flex-1 min-w-0 bg-[#0F1F3D] rounded-xl rounded-tl-sm px-4 py-3 border border-[#1E3A8A] shadow-sm">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <span class="f-display text-xs font-bold text-white" x-text="c.user.name"></span>
                                    <span class="text-[10px] font-bold text-[#FACC15]" x-text="'@' + c.user.username"></span>
                                    <span class="text-[10px] font-medium text-slate-500" x-text="c.time_ago"></span>
                                </div>
                                <p class="text-sm text-slate-200 font-medium mt-1 whitespace-pre-line break-words leading-relaxed" x-text="c.body"></p>
                            </div>
                        </div>
                    </template>
                    <p x-show="comments.length === 0 && !loadingComments" class="text-xs font-medium text-slate-400 text-center py-3">
                        Belum ada komentar. Jadi yang pertama!
                    </p>
                    <p x-show="loadingComments" class="text-xs font-bold text-[#60A5FA] text-center py-3 animate-pulse">
                        Memuat komentar...
                    </p>
                </div>

                @auth
                    <form @submit.prevent="submitComment()" class="flex gap-2">
                        <input type="text" x-model="newComment" maxlength="500"
                               placeholder="Tulis komentar..."
                               autocomplete="off"
                               class="flex-1 px-4 py-2.5 text-sm border-2 border-[#1E3A8A] rounded-xl focus:ring-0 focus:border-[#3B82F6] outline-none text-white font-medium placeholder-slate-500 transition-all bg-[#0F1F3D]">
                        <button type="submit" :disabled="!newComment.trim() || submittingComment"
                                class="bg-[#FACC15] hover:bg-[#EAB308] text-[#1E3A8A] border-2 border-[#1E3A8A] px-5 py-2.5 rounded-xl text-sm font-black transition-all duration-300 shadow-[2px_2px_0_#1E3A8A] hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center min-w-[80px]">
                            <span x-show="!submittingComment">Kirim</span>
                            <svg x-show="submittingComment" class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                            </svg>
                        </button>
                    </form>
                @else
                    <p class="text-xs font-medium text-slate-400 text-center bg-[#0F1F3D] py-2.5 rounded-xl border-2 border-[#1E3A8A]">
                        <a href="{{ route('login') }}" class="text-[#FACC15] font-bold hover:underline">Login</a> untuk berkomentar
                    </p>
                @endauth
            </div>
        </div>
    </div>

    {{-- ==================================================== --}}
    {{-- MODAL KONFIRMASI HAPUS --}}
    {{-- ==================================================== --}}
    <template x-teleport="body">
        <div x-show="showDeleteModal" 
             x-cloak
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-[#060D1F]/80 backdrop-blur-sm transition-opacity"
             @click.self="showDeleteModal = false">
            
            <div class="bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-2xl shadow-[0_0_0_4px_#3B82F6] max-w-sm w-full p-6 transform transition-all animate-in zoom-in duration-200 relative">
                
                <div class="flex items-start gap-4 mb-5">
                    <div class="w-12 h-12 rounded-full bg-[#F43F5E]/20 border-2 border-[#F43F5E] flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-[#F43F5E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.74-2.99l-6.93-12a2 2 0 00-3.48 0l-6.93 12A2 2 0 005.07 19z"/>
                        </svg>
                    </div>
                    
                    <div class="flex-1 pt-1">
                        <h3 class="f-display text-xl font-bold text-white tracking-tight">Hapus Postingan?</h3>
                        <p class="text-sm text-slate-400 mt-1 font-medium leading-relaxed">
                            Tindakan ini tidak dapat dibatalkan. Postingan akan dihapus secara permanen dari feed.
                        </p>
                    </div>
                </div>

                <div class="flex gap-3 justify-end mt-6">
                    <button @click="showDeleteModal = false" type="button"
                            class="px-5 py-2.5 bg-[#152D54] border-2 border-[#1E3A8A] hover:bg-[#1E3A8A] text-slate-300 hover:text-white rounded-xl text-sm font-bold transition-all duration-200">
                        Batal
                    </button>
                    <button @click="$refs.deleteForm.submit()" type="button"
                            class="px-5 py-2.5 bg-[#F43F5E] hover:bg-red-600 text-white border-2 border-[#1E3A8A] rounded-xl text-sm font-black transition-all duration-200 shadow-[2px_2px_0_#1E3A8A] hover:-translate-y-0.5">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </template>

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