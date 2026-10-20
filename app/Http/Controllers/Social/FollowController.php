<?php

namespace App\Http\Controllers\Social;

use App\Http\Controllers\Controller;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class FollowController extends Controller
{
    /**
     * Toggle follow/unfollow (AJAX).
     */
    public function toggle(string $username): JsonResponse
    {
        $target = User::where('username', $username)->firstOrFail();
        $me = auth()->user();

        if ($me->id === $target->id) {
            return response()->json([
                'error' => 'Tidak bisa follow diri sendiri.',
            ], 400);
        }

        $existing = Follow::where('follower_id', $me->id)
            ->where('following_id', $target->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $following = false;
        } else {
            Follow::create([
                'follower_id' => $me->id,
                'following_id' => $target->id,
            ]);
            $following = true;
        }

        return response()->json([
            'following' => $following,
            'followers_count' => $target->followers()->count(),
        ]);
    }
}