@php
    $isMine = $post->user_id === auth()->id();
    $isLiked = auth()->check() && $post->likes()->where('user_id', auth()->id())->exists();
    $isReposted = auth()->check() && $post->reposts()->where('user_id', auth()->id())->exists();
    $isAdmin = auth()->check() && auth()->user()->isAdmin();
@endphp

{{-- CARD UTAMA --}}
<article class="w-full bg-[#0F1F3D] border-[3px] border-[#BFDBFE] dark:border-[#1E3A8A] rounded-[2rem] p-4 sm:p-5 shadow-[4px_4px_0_#BFDBFE] dark:shadow-[4px_4px_0_#1E3A8A] hover:-translate-y-1 transition-transform duration-300 relative group" 
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

    {{-- 1. HEADER: Avatar, Nama, Username & Waktu --}}
    <div class="flex items-center gap-3 mb-3">
        <a href="{{ route('social.profile.show', $post->user->username) }}" class="flex-shrink-0">
            <!-- Avatar dengan Ring Kuning -->
            <div class="w-11 h-11 rounded-full bg-[#1E293B] border-[3px] border-[#FACC15] flex items-center justify-center text-white font-bold text-lg shrink-0 group-hover:scale-105 transition-transform duration-300 shadow-sm overflow-hidden">
                @if($post->user->avatar)
                    <img src="{{ $post->user->avatar }}" alt="{{ $post->user->name }}" class="w-full h-full object-cover">
                @else
                    {{ strtoupper(substr($post->user->name, 0, 1)) }}
                @endif
            </div>
        </a>
        
        <!-- Info User -->
        <div class="min-w-0 flex-1">
            <div class="flex items-center gap-1.5 flex-wrap">
                <a href="{{ route('social.profile.show', $post->user->username) }}" class="font-bold text-[14px] sm:text-[15px] text-[#0F172A] dark:text-white truncate hover:text-[#FACC15] transition-colors">
                    {{ $post->user->name }}
                </a>
                <span class="text-[12px] text-slate-500 font-medium truncate">
                    {{ '@' . $post->user->username }}
                </span>
                <span class="text-[12px] text-slate-400">&bull;</span>
                <span class="text-[12px] text-slate-400 whitespace-nowrap">
                    {{ $post->created_at->diffForHumans() }}
                </span>
            </div>
        </div>
    </div>

    {{-- 2. BODY: Isi Postingan --}}
    <div class="mb-4 text-[14px] sm:text-[15px] text-[#334155] dark:text-slate-200 font-medium leading-relaxed break-words whitespace-pre-line">
        {!! nl2br(e($post->body)) !!}
    </div>

    {{-- 3. HASHTAGS --}}
    @if ($post->hashtags->isNotEmpty())
        <div class="mb-4 flex flex-wrap gap-2">
            @foreach ($post->hashtags as $tag)
                <a href="{{ route('social.hashtag', $tag->tag) }}"
                   class="text-[11px] md:text-xs font-black text-[#1E3A8A] bg-[#FACC15] border-2 border-[#1E3A8A] px-2.5 py-1 rounded-lg hover:scale-105 transition-transform shadow-[1px_1px_0_#1E3A8A]">
                    #{{ $tag->tag }}
                </a>
            @endforeach
        </div>
    @endif

    <!-- Garis Pembatas Horizontal -->
    <div class="w-full h-[2px] bg-[#BFDBFE] dark:bg-[#1E3A8A]/50 mb-3 rounded-full"></div>

    {{-- 4. FOOTER: Tombol Interaksi & Edit/Hapus --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        
        <!-- Grup Kiri: Like, Comment, Repost (Bentuk Pill) -->
        <div class="flex items-center gap-2">
            {{-- Like --}}
            <button type="button" @click="toggleLike()"
                    :class="liked ? 'text-[#F43F5E] bg-[#F43F5E]/10 border-[#F43F5E] shadow-[1px_1px_0_#F43F5E]' : 'text-[#334155] dark:text-slate-300 bg-[#F8FAFC] dark:bg-[#152D54] border-[#BFDBFE] dark:border-[#1E3A8A] hover:bg-[#DBEAFE] dark:hover:bg-[#1E3A8A]'"
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-full border-[2px] transition-colors text-[12px] font-bold">
                <svg class="w-4 h-4" :fill="liked ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <span x-text="likesCount"></span>
            </button>
            
            {{-- Comment --}}
            <button type="button" @click="toggleComments()"
                    :class="showComments ? 'text-[#3B82F6] bg-[#DBEAFE] dark:bg-[#1E3A8A] border-[#3B82F6] shadow-[1px_1px_0_#3B82F6]' : 'text-[#334155] dark:text-slate-300 bg-[#F8FAFC] dark:bg-[#152D54] border-[#BFDBFE] dark:border-[#1E3A8A] hover:bg-[#DBEAFE] dark:hover:bg-[#1E3A8A]'"
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-full border-[2px] transition-colors text-[12px] font-bold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <span x-text="commentsCount"></span>
            </button>
            
            {{-- Repost --}}
            <button type="button" @click="toggleRepost()" :disabled="!canRepost"
                    :class="reposted ? 'text-emerald-500 bg-emerald-100 border-emerald-500 shadow-[1px_1px_0_#10B981]' : 'text-[#334155] dark:text-slate-300 bg-[#F8FAFC] dark:bg-[#152D54] border-[#BFDBFE] dark:border-[#1E3A8A] hover:bg-[#DBEAFE] dark:hover:bg-[#1E3A8A]'"
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-full border-[2px] transition-colors text-[12px] font-bold disabled:opacity-50 disabled:cursor-not-allowed">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <span x-text="repostsCount"></span>
            </button>
        </div>

        <!-- Grup Kanan: Edit & Hapus (Hanya muncul jika post milik user/admin) -->
        @if ($isMine || $isAdmin)
            <div class="flex items-center gap-2 ml-auto">
                @if ($isMine)
                    <a href="{{ route('social.posts.edit', $post) }}"
                       class="bg-[#60A5FA] hover:bg-[#3B82F6] text-white px-3.5 py-1.5 rounded-lg text-[12px] font-bold border-2 border-[#3B82F6] transition-colors shadow-sm">
                        Edit
                    </a>
                @endif
                
                <form method="POST" action="{{ route('social.posts.destroy', $post) }}" 
                      @submit.prevent="showDeleteModal = true"
                      x-ref="deleteForm">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-[#F43F5E] hover:bg-[#E11D48] text-white px-3.5 py-1.5 rounded-lg text-[12px] font-bold border-2 border-[#E11D48] transition-colors shadow-sm">
                        Hapus
                    </button>
                </form>
            </div>
        @endif
    </div>

    {{-- ==================================================== --}}
    {{-- COMMENT SECTION --}}
    {{-- ==================================================== --}}
    <div x-show="showComments" x-cloak class="mt-4 p-3 md:p-4 bg-[#F8FAFC] dark:bg-[#152D54] border-2 border-[#BFDBFE] dark:border-[#1E3A8A] rounded-xl shadow-inner">
        <div class="space-y-3 mb-4">
            <template x-for="c in comments" :key="c.id">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#FACC15] to-[#22D3EE] flex items-center justify-center text-[#1E3A8A] text-xs font-black flex-shrink-0 shadow-sm border-2 border-[#1E3A8A]"
                         x-text="c.user.initial"></div>
                    
                    <div class="flex-1 min-w-0 bg-white dark:bg-[#0F1F3D] rounded-xl rounded-tl-sm px-4 py-3 border border-[#BFDBFE] dark:border-[#1E3A8A] shadow-sm">
                        <div class="flex items-center gap-2 flex-wrap mb-1">
                            <span class="f-display text-xs font-bold text-[#0F172A] dark:text-white" x-text="c.user.name"></span>
                            <span class="text-[10px] font-bold text-[#3B82F6]" x-text="'@' + c.user.username"></span>
                            <span class="text-[10px] font-medium text-slate-500" x-text="c.time_ago"></span>
                        </div>
                        <p class="text-sm text-[#334155] dark:text-slate-200 font-medium mt-1 whitespace-pre-line break-words leading-relaxed" x-text="c.body"></p>
                    </div>
                </div>
            </template>
            <p x-show="comments.length === 0 && !loadingComments" class="text-xs font-medium text-slate-400 text-center py-3">
                Belum ada komentar. Jadi yang pertama!
            </p>
            <p x-show="loadingComments" class="text-xs font-bold text-[#3B82F6] text-center py-3 animate-pulse">
                Memuat komentar...
            </p>
        </div>

        @auth
            <form @submit.prevent="submitComment()" class="flex gap-2">
                <input type="text" x-model="newComment" maxlength="500"
                       placeholder="Tulis komentar..." autocomplete="off"
                       class="flex-1 px-4 py-2.5 text-sm border-2 border-[#BFDBFE] dark:border-[#1E3A8A] rounded-xl focus:ring-0 focus:border-[#3B82F6] outline-none text-[#0F172A] dark:text-white font-medium placeholder-slate-500 transition-all bg-white dark:bg-[#0F1F3D]">
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
            <p class="text-xs font-medium text-slate-400 text-center bg-white dark:bg-[#0F1F3D] py-2.5 rounded-xl border-2 border-[#BFDBFE] dark:border-[#1E3A8A]">
                <a href="{{ route('login') }}" class="text-[#3B82F6] font-bold hover:underline">Login</a> untuk berkomentar
            </p>
        @endauth
    </div>

    {{-- ==================================================== --}}
    {{-- MODAL KONFIRMASI HAPUS --}}
    {{-- ==================================================== --}}
    <template x-teleport="body">
        <div x-show="showDeleteModal" 
             x-cloak
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-opacity"
             @click.self="showDeleteModal = false">
            
            <div class="bg-white dark:bg-[#0F1F3D] border-2 border-[#BFDBFE] dark:border-[#1E3A8A] rounded-2xl shadow-2xl max-w-sm w-full p-6 transform transition-all animate-in zoom-in duration-200 relative">
                
                <div class="flex items-start gap-4 mb-5">
                    <div class="w-12 h-12 rounded-full bg-red-100 border-2 border-red-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.74-2.99l-6.93-12a2 2 0 00-3.48 0l-6.93 12A2 2 0 005.07 19z"/>
                        </svg>
                    </div>
                    
                    <div class="flex-1 pt-1">
                        <h3 class="f-display text-xl font-bold text-[#0F172A] dark:text-white tracking-tight">Hapus Postingan?</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 font-medium leading-relaxed">
                            Tindakan ini tidak dapat dibatalkan. Postingan akan dihapus secara permanen dari feed.
                        </p>
                    </div>
                </div>

                <div class="flex gap-3 justify-end mt-6">
                    <button @click="showDeleteModal = false" type="button"
                            class="px-5 py-2.5 bg-[#F8FAFC] dark:bg-[#152D54] border-2 border-[#BFDBFE] dark:border-[#1E3A8A] hover:bg-[#DBEAFE] dark:hover:bg-[#1E3A8A] text-[#334155] dark:text-slate-300 rounded-xl text-sm font-bold transition-all duration-200">
                        Batal
                    </button>
                    <button @click="$refs.deleteForm.submit()" type="button"
                            class="px-5 py-2.5 bg-[#F43F5E] hover:bg-red-600 text-white border-2 border-red-700 rounded-xl text-sm font-black transition-all duration-200 shadow-[2px_2px_0_#BE123C] hover:-translate-y-0.5">
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
                    // ✅ FIX: Menambahkan prefix /social agar sesuai dengan route web.php
                    const res = await fetch(`/social/posts/${this.postId}/like`, {
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
                } catch (e) { console.error(e); }
            },

            async toggleRepost() {
                if (!this.canRepost) return;
                if (!{{ auth()->check() ? 'true' : 'false' }}) {
                    window.location.href = '{{ route('login') }}';
                    return;
                }
                try {
                    // ✅ FIX: Menambahkan prefix /social
                    const res = await fetch(`/social/posts/${this.postId}/repost`, {
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
                } catch (e) { console.error(e); }
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
                    // ✅ FIX: Menambahkan prefix /social
                    const res = await fetch(`/social/posts/${this.postId}/comments`, {
                        headers: { 'Accept': 'application/json' },
                    });
                    if (res.ok) {
                        const data = await res.json();
                        this.comments = data.comments;
                        this.commentsLoaded = true;
                    }
                } catch (e) { console.error(e); }
                this.loadingComments = false;
            },

            async submitComment() {
                const body = this.newComment.trim();
                if (!body || this.submittingComment) return;
                this.submittingComment = true;
                try {
                    // ✅ FIX: Menambahkan prefix /social
                    const res = await fetch(`/social/posts/${this.postId}/comments`, {
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
                } catch (e) { console.error(e); }
                this.submittingComment = false;
            },
        };
    };
}
</script>