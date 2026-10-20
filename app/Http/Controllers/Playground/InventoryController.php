<?php

namespace App\Http\Controllers\Playground;

use App\Http\Controllers\Controller;
use App\Models\UserGachaItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();

        $filter = $request->get('type', 'all');
        if (!in_array($filter, ['all', 'skin', 'emote', 'badge'])) {
            $filter = 'all';
        }

        $query = $user->gachaItems();

        if ($filter !== 'all') {
            $query->where('gacha_items.type', $filter);
        }

        $items = $query
            ->orderByRaw("FIELD(gacha_items.rarity, 'legendary', 'epic', 'rare', 'common')")
            ->orderBy('gacha_items.name')
            ->get();

        // Count per type
        $counts = [
            'all' => $user->gachaItems()->count(),
            'skin' => $user->gachaItems()->where('gacha_items.type', 'skin')->count(),
            'emote' => $user->gachaItems()->where('gacha_items.type', 'emote')->count(),
            'badge' => $user->gachaItems()->where('gacha_items.type', 'badge')->count(),
        ];

        return view('playground.inventory.index', compact('items', 'filter', 'counts'));
    }

    public function equip(UserGachaItem $userItem): RedirectResponse
    {
        $this->authorizeOwner($userItem);

        $itemType = $userItem->item->type;

        // Unequip semua item sejenis dulu (hanya 1 equip per type)
        UserGachaItem::where('user_id', auth()->id())
            ->whereHas('item', fn($q) => $q->where('type', $itemType))
            ->update(['is_equipped' => false]);

        // Equip item ini
        $userItem->update(['is_equipped' => true]);

        return redirect()
            ->route('play.inventory.index')
            ->with('success', $userItem->item->name . ' berhasil dipakai.');
    }

    public function unequip(UserGachaItem $userItem): RedirectResponse
    {
        $this->authorizeOwner($userItem);

        $userItem->update(['is_equipped' => false]);

        return redirect()
            ->route('play.inventory.index')
            ->with('success', $userItem->item->name . ' dilepas.');
    }

    private function authorizeOwner(UserGachaItem $userItem): void
    {
        if ($userItem->user_id !== auth()->id()) {
            abort(403);
        }
    }
}