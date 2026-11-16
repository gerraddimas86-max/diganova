<?php

namespace App\Http\Controllers\Social;

use App\Http\Controllers\Controller;
use App\Models\DirectMessage;
use App\Models\DmConversation;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(Request $request): View
    {
        $me = auth()->id();

        $conversations = DmConversation::query()
            ->where('user_one_id', $me)
            ->orWhere('user_two_id', $me)
            ->with(['userOne', 'userTwo'])
            ->withCount(['messages as unread_count' => function ($q) use ($me) {
                $q->where('sender_id', '!=', $me)->where('is_read', false);
            }])
            ->orderByDesc('last_message_at')
            ->orderByDesc('updated_at')
            ->get()
            ->map(function ($conv) use ($me) {
                $other = $conv->user_one_id === $me ? $conv->userTwo : $conv->userOne;
                
                // Cari pesan terakhir yang belum dihapus oleh user ini
                $lastMsg = $conv->messages()
                    ->where(function($q) use ($me) {
                        $q->whereNull('deleted_by')
                          ->orWhereJsonDoesntContain('deleted_by', (string) $me)
                          ->orWhereJsonDoesntContain('deleted_by', $me);
                    })
                    ->latest()
                    ->first();

                return [
                    'id' => $conv->id,
                    'user' => $other,
                    'unread_count' => $conv->unread_count,
                    'last_message' => $lastMsg ? ($lastMsg->is_deleted_for_everyone ? 'Pesan ini telah dihapus' : $lastMsg->body) : null,
                    'last_message_at' => $lastMsg?->created_at,
                    'is_mine' => $lastMsg ? $lastMsg->sender_id === $me : false,
                ];
            });

        $existingUserIds = $conversations->pluck('user.id')->push($me)->toArray();
        $suggested = User::whereNotIn('id', $existingUserIds)
            ->with('avatar')
            ->take(6)
            ->get();

        return view('social.messages.index', compact('conversations', 'suggested'));
    }

    public function show(string $username): View|RedirectResponse
    {
        $me = auth()->user();
        $other = User::where('username', $username)->firstOrFail();

        if ($other->id === $me->id) {
            return redirect()->route('social.messages.index')->with('error', 'Kamu tidak bisa chat dengan diri sendiri.');
        }

        $conversation = DmConversation::findOrCreateBetween($me->id, $other->id);

        $conversation->messages()
            ->where('sender_id', '!=', $me->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        // Ambil pesan (kecuali yang dihapus oleh saya)
        $messages = $conversation->messages()
            ->with('sender')
            ->where(function($q) use ($me) {
                $q->whereNull('deleted_by')
                  ->orWhereJsonDoesntContain('deleted_by', (string) $me->id)
                  ->orWhereJsonDoesntContain('deleted_by', $me->id);
            })
            ->orderByDesc('created_at')
            ->take(50)
            ->get()
            ->reverse()
            ->values();

        $conversations = DmConversation::query()
            ->where('user_one_id', $me->id)
            ->orWhere('user_two_id', $me->id)
            ->with(['userOne', 'userTwo'])
            ->withCount(['messages as unread_count' => function ($q) use ($me) {
                $q->where('sender_id', '!=', $me->id)->where('is_read', false);
            }])
            ->orderByDesc('last_message_at')
            ->orderByDesc('updated_at')
            ->get()
            ->map(function ($conv) use ($me) {
                $otherUser = $conv->user_one_id === $me->id ? $conv->userTwo : $conv->userOne;
                
                $lastMsg = $conv->messages()
                    ->where(function($q) use ($me) {
                        $q->whereNull('deleted_by')
                          ->orWhereJsonDoesntContain('deleted_by', (string) $me->id)
                          ->orWhereJsonDoesntContain('deleted_by', $me->id);
                    })
                    ->latest()
                    ->first();

                return [
                    'id' => $conv->id,
                    'user' => $otherUser,
                    'unread_count' => $conv->unread_count,
                    'last_message' => $lastMsg ? ($lastMsg->is_deleted_for_everyone ? 'Pesan ini telah dihapus' : $lastMsg->body) : null,
                    'last_message_at' => $lastMsg?->created_at,
                    'is_mine' => $lastMsg ? $lastMsg->sender_id === $me->id : false,
                ];
            });

        return view('social.messages.show', compact('conversation', 'other', 'messages', 'conversations'));
    }

    public function send(Request $request, string $username): JsonResponse
    {
        $me = auth()->user();
        $other = User::where('username', $username)->firstOrFail();

        if ($other->id === $me->id) {
            return response()->json(['error' => 'Tidak bisa chat diri sendiri.'], 400);
        }

        $validated = $request->validate(['body' => ['required', 'string', 'max:1000']]);
        $conversation = DmConversation::findOrCreateBetween($me->id, $other->id);

        $message = $conversation->messages()->create([
            'sender_id' => $me->id,
            'body' => $validated['body'],
            'is_read' => false,
        ]);

        $conversation->update(['last_message_at' => now()]);

        return response()->json(['status' => 'ok', 'message' => $this->serializeMessage($message)]);
    }

    public function update(Request $request, string $username, int $messageId): JsonResponse
    {
        $me = auth()->user();
        $message = DirectMessage::findOrFail($messageId);

        if ($message->sender_id !== $me->id || $message->is_deleted_for_everyone) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate(['body' => ['required', 'string', 'max:1000']]);
        
        if ($message->body === $validated['body']) {
            return response()->json(['status' => 'ok', 'message' => $this->serializeMessage($message)]);
        }

        $message->update(['body' => $validated['body']]);

        return response()->json(['status' => 'ok', 'message' => $this->serializeMessage($message->fresh())]);
    }

    /**
     * Hapus pesan (AJAX).
     */
    public function destroy(Request $request, string $username, int $messageId): JsonResponse
    {
        $me = auth()->user();
        $message = DirectMessage::findOrFail($messageId);
        $type = $request->input('type'); // 'me' atau 'everyone'

        if ($type === 'everyone') {
            // Hanya pengirim yang bisa hapus untuk semua orang
            if ($message->sender_id !== $me->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            $message->update([
                'is_deleted_for_everyone' => true,
                'body' => 'Pesan ini telah dihapus', 
            ]);
        } else {
            // Hapus untuk saya
            $deletedBy = $message->deleted_by ?? [];
            if (!in_array((string) $me->id, $deletedBy) && !in_array($me->id, $deletedBy)) {
                $deletedBy[] = (string) $me->id;
                $message->update(['deleted_by' => $deletedBy]);
            }
        }

        return response()->json(['status' => 'ok']);
    }

    public function poll(Request $request, string $username): JsonResponse
    {
        $me = auth()->user();
        $other = User::where('username', $username)->firstOrFail();
        $conversation = DmConversation::findOrCreateBetween($me->id, $other->id);
        $afterId = (int) $request->get('after_id', 0);

        $query = $conversation->messages()
            ->with('sender')
            ->where(function($q) use ($me) {
                $q->whereNull('deleted_by')
                  ->orWhereJsonDoesntContain('deleted_by', (string) $me->id)
                  ->orWhereJsonDoesntContain('deleted_by', $me->id);
            })
            ->orderBy('id', 'asc');

        if ($afterId > 0) {
            $query->where('id', '>', $afterId);
        } else {
            $query->limit(50);
        }

        $messages = $query->get();

        $conversation->messages()
            ->where('sender_id', '!=', $me->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        // ✅ TAMBAHAN: Ambil data Last Seen untuk lawan chat
        $isOnline = $other->last_seen_at && $other->last_seen_at->diffInMinutes(now()) < 5;
        $lastSeenText = $other->last_seen_at ? $other->last_seen_at->diffForHumans() : 'Tidak diketahui';

        return response()->json([
            'messages' => $messages->map(fn($m) => $this->serializeMessage($m))->values()->toArray(),
            'other_last_seen' => $lastSeenText,
            'other_is_online' => $isOnline,
        ]);
    }

    public function unreadCount(): JsonResponse
    {
        $me = auth()->id();
        $count = DirectMessage::whereHas('conversation', function ($q) use ($me) {
            $q->where('user_one_id', $me)->orWhere('user_two_id', $me);
        })
            ->where('sender_id', '!=', $me)
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    private function serializeMessage(DirectMessage $m): array
    {
        return [
            'id' => $m->id,
            'body' => $m->body,
            'is_mine' => $m->sender_id === auth()->id(),
            'time' => $m->created_at->format('H:i'),
            'created_at' => $m->created_at->toIso8601String(),
            'is_edited' => $m->updated_at->gt($m->created_at) && !$m->is_deleted_for_everyone,
            'is_deleted' => $m->is_deleted_for_everyone,
        ];
    }
}