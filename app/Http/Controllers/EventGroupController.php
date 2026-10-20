<?php

namespace App\Http\Controllers;

use App\Models\EventGroup;
use App\Models\GroupMessage;
use App\Models\GroupPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventGroupController extends Controller
{
    /**
     * List grup yang diikuti user.
     */
    public function index(): View
    {
        $groups = EventGroup::query()
            ->whereHas('members', fn($q) => $q->where('user_id', auth()->id()))
            ->orWhereHas('event', fn($q) => $q->where('created_by', auth()->id()))
            ->with(['event'])
            ->withCount('members')
            ->orderByDesc('updated_at')
            ->get();

        return view('event.groups.index', compact('groups'));
    }

    /**
     * Detail grup: feed, chat, members.
     */
    public function show(EventGroup $group): View
    {
        $this->authorizeAccess($group);

        $group->load(['event', 'members']);

        $posts = $group->posts()
            ->with('author')
            ->orderByDesc('is_pinned')
            ->latest()
            ->take(20)
            ->get();

        $messages = $group->messages()
            ->with('user')
            ->latest()
            ->take(50)
            ->get()
            ->reverse()
            ->values();

        $isGroupAdmin = $this->canPostAnnouncement($group);
        $currentUserId = auth()->id();

        return view('event.groups.show', compact(
            'group',
            'posts',
            'messages',
            'isGroupAdmin',
            'currentUserId'
        ));
    }

    /**
     * Post pengumuman baru (admin grup only).
     */
    public function postStore(Request $request, EventGroup $group): RedirectResponse
    {
        $this->authorizeAccess($group);

        if (!$this->canPostAnnouncement($group)) {
            abort(403, 'Hanya admin grup yang bisa membuat pengumuman.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:3000'],
            'is_pinned' => ['nullable', 'boolean'],
        ]);

        GroupPost::create([
            'event_group_id' => $group->id,
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'body' => $validated['body'],
            'is_pinned' => !empty($validated['is_pinned']),
        ]);

        return back()->with('success', 'Pengumuman berhasil dipost.');
    }

    /**
     * Hapus post (author atau admin).
     */
    public function postDestroy(EventGroup $group, GroupPost $post): RedirectResponse
    {
        $this->authorizeAccess($group);

        if ($post->event_group_id !== $group->id) {
            abort(404);
        }

        if ($post->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $post->delete();

        return back()->with('success', 'Pengumuman dihapus.');
    }

    /**
     * Toggle pin post (admin grup only).
     */
    public function postPin(EventGroup $group, GroupPost $post): RedirectResponse
    {
        $this->authorizeAccess($group);

        if ($post->event_group_id !== $group->id) {
            abort(404);
        }

        if (!$this->canPostAnnouncement($group)) {
            abort(403);
        }

        $post->update(['is_pinned' => !$post->is_pinned]);

        return back()->with('success', $post->is_pinned ? 'Postingan dipin.' : 'Pin dilepas.');
    }

    /**
     * Kirim chat (AJAX).
     */
    public function messageStore(Request $request, EventGroup $group): JsonResponse
    {
        $this->authorizeAccess($group);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $msg = GroupMessage::create([
            'event_group_id' => $group->id,
            'user_id' => auth()->id(),
            'message' => $validated['message'],
        ]);

        $msg->load('user');

        return response()->json([
            'status' => 'ok',
            'message' => $this->serializeMessage($msg, true),
        ]);
    }

    /**
     * Polling chat — return 50 pesan terbaru.
     */
    public function messages(EventGroup $group): JsonResponse
    {
        $this->authorizeAccess($group);

        $messages = $group->messages()
            ->with('user')
            ->latest()
            ->take(50)
            ->get()
            ->reverse()
            ->values()
            ->map(fn($m) => $this->serializeMessage($m))
            ->toArray();

        return response()->json(['messages' => $messages]);
    }

    // ============================================================
    // HELPERS
    // ============================================================

    private function authorizeAccess(EventGroup $group): void
    {
        if (!auth()->check()) {
            abort(403, 'Login diperlukan.');
        }

        $isMember = $group->members()->where('user_id', auth()->id())->exists();
        $isEventAdmin = $group->event->created_by === auth()->id();

        if (!$isMember && !$isEventAdmin) {
            abort(403, 'Kamu belum menjadi anggota grup ini. Daftar event & verifikasi pembayaran untuk bergabung.');
        }
    }

    private function canPostAnnouncement(EventGroup $group): bool
    {
        if ($group->event->created_by === auth()->id()) {
            return true;
        }

        return $group->admins()->where('user_id', auth()->id())->exists();
    }

    private function serializeMessage(GroupMessage $m, bool $isNew = false): array
    {
        return [
            'id' => $m->id,
            'user' => $m->user->name,
            'username' => '@' . $m->user->username,
            'avatar_initial' => strtoupper(substr($m->user->name, 0, 1)),
            'body' => $m->message,
            'time' => $m->created_at->format('H:i'),
            'is_mine' => $isNew || $m->user_id === auth()->id(),
        ];
    }
}