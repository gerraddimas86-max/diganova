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
    /**
     * List semua conversation user.
     */
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
                $lastMsg = $conv->messages()->latest()->first();

                return [
                    'id' => $conv->id,
                    'user' => $other,
                    'unread_count' => $conv->unread_count,
                    'last_message' => $lastMsg?->body,
                    'last_message_at' => $lastMsg?->created_at,
                    'is_mine' => $lastMsg ? $lastMsg->sender_id === $me : false,
                ];
            });

        // Suggestion: user yang belum pernah dichat
        $existingUserIds = $conversations->pluck('user.id')->push($me)->toArray();
        $suggested = User::whereNotIn('id', $existingUserIds)
            ->with('avatar')
            ->take(6)
            ->get();

        return view('social.messages.index', compact('conversations', 'suggested'));
    }

    /**
     * Buka chat room dengan user tertentu.
     */
    public function show(string $username): View|RedirectResponse
    {
        $me = auth()->user();
        $other = User::where('username', $username)->firstOrFail();

        if ($other->id === $me->id) {
            return redirect()
                ->route('social.messages.index')
                ->with('error', 'Kamu tidak bisa chat dengan diri sendiri.');
        }

        // Cari atau buat conversation
        $conversation = DmConversation::findOrCreateBetween($me->id, $other->id);

        // Mark semua pesan dari lawan sebagai read
        $conversation->messages()
            ->where('sender_id', '!=', $me->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        // Ambil 50 pesan terbaru
        $messages = $conversation->messages()
            ->with('sender')
            ->orderByDesc('created_at')
            ->take(50)
            ->get()
            ->reverse()
            ->values();

        return view('social.messages.show', compact('conversation', 'other', 'messages'));
    }

    /**
     * Kirim pesan (AJAX).
     */
    public function send(Request $request, string $username): JsonResponse
    {
        $me = auth()->user();
        $other = User::where('username', $username)->firstOrFail();

        if ($other->id === $me->id) {
            return response()->json(['error' => 'Tidak bisa chat diri sendiri.'], 400);
        }

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:1000'],
        ]);

        $conversation = DmConversation::findOrCreateBetween($me->id, $other->id);

        $message = $conversation->messages()->create([
            'sender_id' => $me->id,
            'body' => $validated['body'],
            'is_read' => false,
        ]);

        $conversation->update(['last_message_at' => now()]);

        return response()->json([
            'status' => 'ok',
            'message' => $this->serializeMessage($message),
        ]);
    }

    /**
     * Polling pesan terbaru (AJAX).
     * Optional param: after_id (untuk ambil pesan baru saja).
     */
    public function poll(Request $request, string $username): JsonResponse
    {
        $me = auth()->user();
        $other = User::where('username', $username)->firstOrFail();

        $conversation = DmConversation::findOrCreateBetween($me->id, $other->id);

        $afterId = (int) $request->get('after_id', 0);

        $query = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at');

        if ($afterId > 0) {
            $query->where('id', '>', $afterId);
        } else {
            $query->limit(50);
        }

        $messages = $query->get();

        // Mark incoming sebagai read
        $conversation->messages()
            ->where('sender_id', '!=', $me->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json([
            'messages' => $messages->map(fn($m) => $this->serializeMessage($m))->values()->toArray(),
        ]);
    }

    /**
     * AJAX unread count (untuk badge navbar).
     */
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
        ];
    }
}