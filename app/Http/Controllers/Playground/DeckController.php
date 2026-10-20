<?php

namespace App\Http\Controllers\Playground;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDeckRequest;
use App\Models\Card;
use App\Models\Deck;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DeckController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $decks = $user->decks()
            ->with('cards')
            ->orderBy('slot')
            ->get();

        // Buat deck default di slot 1,2,3 kalau belum ada
        $existingSlots = $decks->pluck('slot')->toArray();
        for ($i = 1; $i <= 3; $i++) {
            if (!in_array($i, $existingSlots)) {
                Deck::create([
                    'user_id' => $user->id,
                    'name' => 'Deck ' . $i,
                    'slot' => $i,
                    'is_active' => $i === 1 && $decks->isEmpty(),
                ]);
            }
        }

        $decks = $user->decks()
            ->with('cards')
            ->orderBy('slot')
            ->get();

        return view('playground.decks.index', compact('decks'));
    }

    public function create(): RedirectResponse
    {
        // Redirect ke slot pertama yang kosong (belum punya kartu)
        $user = auth()->user();
        $emptyDeck = $user->decks()
            ->doesntHave('cards')
            ->orderBy('slot')
            ->first();

        if (!$emptyDeck) {
            return redirect()
                ->route('play.decks.index')
                ->with('error', 'Semua slot deck sudah terisi. Edit salah satu deck.');
        }

        return redirect()->route('play.decks.edit', $emptyDeck);
    }

    public function store(StoreDeckRequest $request): RedirectResponse
    {
        // Ini tidak dipakai karena create di-redirect ke edit
        abort(404);
    }

    public function edit(Deck $deck): View
    {
        $this->authorizeOwner($deck);

        $deck->load('cards');

        $allCards = Card::where('is_active', true)
            ->orderByRaw("FIELD(type, 'attack', 'defense', 'skill')")
            ->orderBy('name')
            ->get();

        $selectedIds = $deck->cards->pluck('id')->toArray();

        return view('playground.decks.edit', compact('deck', 'allCards', 'selectedIds'));
    }

    public function update(StoreDeckRequest $request, Deck $deck): RedirectResponse
    {
        $this->authorizeOwner($deck);

        $deck->update(['name' => $request->name]);

        // Sync kartu dengan quantity 1 per card
        $cardIds = $request->input('cards', []);
        $syncData = [];
        foreach ($cardIds as $cardId) {
            $syncData[$cardId] = ['quantity' => 1];
        }
        $deck->cards()->sync($syncData);

        return redirect()
            ->route('play.decks.index')
            ->with('success', 'Deck "' . $deck->name . '" berhasil disimpan!');
    }

    public function activate(Deck $deck): RedirectResponse
    {
        $this->authorizeOwner($deck);

        // Pastikan deck punya 10 kartu sebelum diaktifkan
        $totalCards = $deck->cards()->sum('quantity');
        if ($totalCards !== 10) {
            return back()->with('error', 'Deck harus berisi tepat 10 kartu untuk diaktifkan. Saat ini: ' . $totalCards . ' kartu.');
        }

        // Deactivate semua deck lain milik user
        auth()->user()->decks()->update(['is_active' => false]);

        // Activate deck ini
        $deck->update(['is_active' => true]);

        return redirect()
            ->route('play.decks.index')
            ->with('success', 'Deck "' . $deck->name . '" sekarang aktif.');
    }

    public function clear(Deck $deck): RedirectResponse
    {
        $this->authorizeOwner($deck);

        $deck->cards()->detach();
        $deck->update(['is_active' => false]);

        return redirect()
            ->route('play.decks.edit', $deck)
            ->with('success', 'Deck dikosongkan.');
    }

    private function authorizeOwner(Deck $deck): void
    {
        if ($deck->user_id !== auth()->id()) {
            abort(403, 'Kamu hanya bisa mengelola deck milik sendiri.');
        }
    }
}