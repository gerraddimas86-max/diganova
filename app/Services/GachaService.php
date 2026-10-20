<?php

namespace App\Services;

use App\Models\GachaItem;
use App\Models\GachaPull;
use App\Models\User;
use Illuminate\Support\Collection;

class GachaService
{
    const COST_1X = 50;
    const COST_10X = 450;

    const DUPLICATE_REFUND = [
        'common' => 10,
        'rare' => 30,
        'epic' => 100,
        'legendary' => 300,
    ];

    public function costFor(int $count): int
    {
        return $count === 10 ? self::COST_10X : self::COST_1X;
    }

    public function pull(User $user, int $count): array
    {
        if (!in_array($count, [1, 10])) {
            return ['success' => false, 'error' => 'Jumlah pull tidak valid.'];
        }

        $cost = $this->costFor($count);

        if ($user->total_points < $cost) {
            return [
                'success' => false,
                'error' => "Poin tidak cukup. Butuh {$cost} poin, kamu punya {$user->total_points}.",
            ];
        }

        $items = GachaItem::where('is_active', true)->get();

        if ($items->isEmpty()) {
            return ['success' => false, 'error' => 'Tidak ada item gacha tersedia.'];
        }

        $totalRate = $items->sum('drop_rate');

        if ($totalRate <= 0) {
            return ['success' => false, 'error' => 'Konfigurasi drop rate tidak valid.'];
        }

        // Deduct cost dulu
        $user->decrement('total_points', $cost);

        // Get existing items
        $existingIds = $user->gachaItems()->pluck('gacha_items.id')->toArray();

        $results = [];
        $duplicatePoints = 0;

        for ($i = 0; $i < $count; $i++) {
            $picked = $this->pickWeighted($items, $totalRate);
            $isDuplicate = in_array($picked->id, $existingIds);

            $refund = 0;

            if ($isDuplicate) {
                $refund = self::DUPLICATE_REFUND[$picked->rarity] ?? 0;
                $duplicatePoints += $refund;
            } else {
                $user->gachaItems()->attach($picked->id, [
                    'is_equipped' => false,
                    'obtained_at' => now(),
                ]);
                $existingIds[] = $picked->id;
            }

            GachaPull::create([
                'user_id' => $user->id,
                'gacha_item_id' => $picked->id,
                'is_duplicate' => $isDuplicate,
            ]);

            $results[] = [
                'id' => $picked->id,
                'name' => $picked->name,
                'type' => $picked->type,
                'rarity' => $picked->rarity,
                'description' => $picked->description,
                'image_path' => $picked->image_path,
                'is_duplicate' => $isDuplicate,
                'refund' => $refund,
            ];
        }

        // Refund duplicate points
        if ($duplicatePoints > 0) {
            $user->increment('total_points', $duplicatePoints);
        }

        return [
            'success' => true,
            'results' => $results,
            'cost' => $cost,
            'duplicate_points' => $duplicatePoints,
            'new_points' => $user->fresh()->total_points,
        ];
    }

    public function pickWeighted(Collection $items, float $totalRate): GachaItem
    {
        // Random value 0..totalRate (dengan 2 desimal)
        $rand = mt_rand(0, (int) ($totalRate * 100)) / 100;

        $cumulative = 0;

        foreach ($items as $item) {
            $cumulative += (float) $item->drop_rate;
            if ($rand <= $cumulative) {
                return $item;
            }
        }

        // Fallback (shouldn't happen)
        return $items->last();
    }

    public function rarityColor(string $rarity): string
    {
        return match ($rarity) {
            'common' => 'gray',
            'rare' => 'blue',
            'epic' => 'purple',
            'legendary' => 'yellow',
            default => 'gray',
        };
    }
}