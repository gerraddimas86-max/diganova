<?php

namespace Database\Seeders;

use App\Models\NavoSkin;
use Illuminate\Database\Seeder;

class NavoSkinSeeder extends Seeder
{
    public function run(): void
    {
        $skins = [
            [
                'name' => 'Navo Original',
                'slug' => 'navo-original',
                'description' => 'Navo rubah klasik dengan bulu oranye.',
                'image_path' => 'navo/skins/original.png',
                'rarity' => 'common',
                'is_default' => true,
            ],
            [
                'name' => 'Crystal Navo',
                'slug' => 'crystal-navo',
                'description' => 'Navo dengan bulu kristal biru bercahaya.',
                'image_path' => 'navo/skins/crystal.png',
                'rarity' => 'rare',
                'is_default' => false,
            ],
            [
                'name' => 'Shadow Navo',
                'slug' => 'shadow-navo',
                'description' => 'Navo dari dunia bayangan.',
                'image_path' => 'navo/skins/shadow.png',
                'rarity' => 'epic',
                'is_default' => false,
            ],
            [
                'name' => 'Solar Navo',
                'slug' => 'solar-navo',
                'description' => 'Navo dengan aura matahari.',
                'image_path' => 'navo/skins/solar.png',
                'rarity' => 'epic',
                'is_default' => false,
            ],
            [
                'name' => 'Celestial Navo',
                'slug' => 'celestial-navo',
                'description' => 'Navo dewa dari langit berbintang. Skin legendaris.',
                'image_path' => 'navo/skins/celestial.png',
                'rarity' => 'legendary',
                'is_default' => false,
            ],
        ];

        foreach ($skins as $skin) {
            NavoSkin::updateOrCreate(['slug' => $skin['slug']], $skin);
        }

        $this->command->info('✅ 5 Navo skins seeded');
    }
}