<?php

namespace App\Http\Controllers;

use App\Models\DirectMessage;
use App\Models\Event;
use Illuminate\View\View;

class HubController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $eventStats = [
            'active_events' => Event::where('status', 'published')->count(),
            'my_registrations' => $user->eventRegistrations()->count(),
        ];

        $myConversationIds = \App\Models\DmConversation::where('user_one_id', $user->id)
            ->orWhere('user_two_id', $user->id)
            ->pluck('id');

        $socialStats = [
            'my_posts' => $user->posts()->count(),
            'followers' => $user->followers()->count(),
            'unread_messages' => DirectMessage::whereIn('dm_conversation_id', $myConversationIds)
                ->where('sender_id', '!=', $user->id)
                ->where('is_read', false)
                ->count(),
        ];

        $profile = $user->navoProfile;
        $playgroundStats = [
            'rank' => $user->rank,
            'points' => $user->total_points,
            'battles' => $profile?->total_battles ?? 0,
            'wins' => $profile?->wins ?? 0,
        ];

        $globalStats = [
            'achievements' => $user->achievements()->count(),
            'total_achievements' => \App\Models\Achievement::count(),
        ];

        return view('hub.index', compact(
            'user', 'eventStats', 'socialStats', 'playgroundStats', 'globalStats'
        ));
    }
}