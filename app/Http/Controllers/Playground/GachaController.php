<?php

namespace App\Http\Controllers\Playground;

use App\Http\Controllers\Controller;
use App\Models\GachaItem;
use App\Models\GachaPull;
use App\Services\GachaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GachaController extends Controller
{
    public function __construct(protected GachaService $gacha)
    {
    }

    public function index(): View
    {
        $user = auth()->user();

        $allItems = GachaItem::where('is_active', true)
            ->orderByRaw("FIELD(rarity, 'legendary', 'epic', 'rare', 'common')")
            ->orderBy('id')
            ->get();

        $ownedIds = $user->gachaItems()->pluck('gacha_items.id')->toArray();

        $stats = [
            'total_owned' => count($ownedIds),
            'total_items' => $allItems->count(),
            'total_pulls' => GachaPull::where('user_id', $user->id)->count(),
        ];

        return view('playground.gacha.index', compact('user', 'allItems', 'ownedIds', 'stats'));
    }

    public function pull(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'count' => ['required', 'integer', 'in:1,10'],
        ]);

        $result = $this->gacha->pull(auth()->user(), (int) $validated['count']);

        if (!$result['success']) {
            return response()->json($result, 400);
        }

        return response()->json($result);
    }

    public function history(): View
    {
        $pulls = GachaPull::where('user_id', auth()->id())
            ->with('item')
            ->latest()
            ->paginate(30);

        $stats = [
            'total' => GachaPull::where('user_id', auth()->id())->count(),
            'unique_items' => auth()->user()->gachaItems()->count(),
            'duplicates' => GachaPull::where('user_id', auth()->id())->where('is_duplicate', true)->count(),
        ];

        return view('playground.gacha.history', compact('pulls', 'stats'));
    }
}