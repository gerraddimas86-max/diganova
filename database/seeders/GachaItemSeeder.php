<?php

namespace Database\Seeders;

use App\Models\GachaItem;
use Illuminate\Database\Seeder;

class GachaItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // Skins (dari NavoSkin, tapi di gacha juga)
            ['name' => 'Crystal Navo Skin', 'slug' => 'gacha-crystal-navo', 'type' => 'skin', 'rarity' => 'rare', 'drop_rate' => 15.00, 'image_path' => 'navo/skins/crystal.png'],
            ['name' => 'Shadow Navo Skin', 'slug' => 'gacha-shadow-navo', 'type' => 'skin', 'rarity' => 'epic', 'drop_rate' => 5.00, 'image_path' => 'navo/skins/shadow.png'],
            ['name' => 'Celestial Navo Skin', 'slug' => 'gacha-celestial-navo', 'type' => 'skin', 'rarity' => 'legendary', 'drop_rate' => 1.00, 'image_path' => 'navo/skins/celestial.png'],

            // Emotes
            ['name' => 'Wave Emote', 'slug' => 'emote-wave', 'type' => 'emote', 'rarity' => 'common', 'drop_rate' => 20.00, 'image_path' => 'emotes/wave.png'],
            ['name' => 'Dance Emote', 'slug' => 'emote-dance', 'type' => 'emote', 'rarity' => 'common', 'drop_rate' => 20.00, 'image_path' => 'emotes/dance.png'],
            ['name' => 'Victory Emote', 'slug' => 'emote-victory', 'type' => 'emote', 'rarity' => 'rare', 'drop_rate' => 10.00, 'image_path' => 'emotes/victory.png'],

            // Badges
            ['name' => 'Bronze Badge', 'slug' => 'badge-bronze', 'type' => 'badge', 'rarity' => 'common', 'drop_rate' => 10.00, 'image_path' => 'badges/bronze.png'],
            ['name' => 'Silver Badge', 'slug' => 'badge-silver', 'type' => 'badge', 'rarity' => 'rare', 'drop_rate' => 8.00, 'image_path' => 'badges/silver.png'],
            ['name' => 'Gold Badge', 'slug' => 'badge-gold', 'type' => 'badge', 'rarity' => 'epic', 'drop_rate' => 6.00, 'image_path' => 'badges/gold.png'],
            ['name' => 'Crystal Badge', 'slug' => 'badge-crystal', 'type' => 'badge', 'rarity' => 'legendary', 'drop_rate' => 5.00, 'image_path' => 'badges/crystal.png'],
        ];

        foreach ($items as $item) {
            GachaItem::updateOrCreate(
                ['slug' => $item['slug']],
                array_merge(['is_active' => true], $item)
            );
        }

        $this->command->info('✅ 10 gacha items seeded (3 skins, 3 emotes, 4 badges)');
    }
}