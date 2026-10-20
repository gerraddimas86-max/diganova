<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\Deck;
use App\Models\Leaderboard;
use App\Models\NavoProfile;
use App\Models\NavoSkin;
use App\Models\Season;
use App\Models\User;
use Illuminate\Database\Seeder;

class PlaygroundSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $defaultSkin = NavoSkin::where('is_default', true)->first();
        $cards = Card::all();
        $activeSeason = Season::active();

        if ($cards->isEmpty() || !$defaultSkin) {
            $this->command->warn('⚠️  Cards/skins belum ada. Jalankan CardSeeder & NavoSkinSeeder dulu.');
            return;
        }

        foreach ($users as $user) {
            // ============ NAVO PROFILE ============
            NavoProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'equipped_skin_id' => $defaultSkin->id,
                    'favorite_card_id' => $cards->random()->id,
                    'total_battles' => rand(0, 50),
                    'wins' => rand(0, 30),
                    'losses' => rand(0, 20),
                    'current_streak' => rand(0, 5),
                    'best_streak' => rand(0, 10),
                ]
            );

            // ============ DECK (slot 1, is_active) ============
            $deck = Deck::updateOrCreate(
                ['user_id' => $user->id, 'slot' => 1],
                [
                    'name' => 'Deck Utama',
                    'is_active' => true,
                ]
            );

            // Isi dengan 10 kartu random (quantity 1 each)
            $selectedCards = $cards->random(min(10, $cards->count()));
            $deckCardData = [];
            foreach ($selectedCards as $card) {
                $deckCardData[$card->id] = ['quantity' => 1];
            }
            $deck->cards()->sync($deckCardData);

            // ============ LEADERBOARD ============
            if ($activeSeason) {
                Leaderboard::updateOrCreate(
                    ['user_id' => $user->id, 'season_id' => $activeSeason->id],
                    [
                        'rank' => $user->rank,
                        'points' => $user->total_points,
                        'wins' => rand(0, 20),
                        'losses' => rand(0, 15),
                    ]
                );
            }
        }

        $this->command->info('✅ ' . $users->count() . ' Navo profiles + decks + leaderboard entries seeded');
    }
}