<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // === Core ===
            SeasonSeeder::class,
            AvatarSeeder::class,
            AchievementSeeder::class,

            // === Game Data ===
            CardSeeder::class,
            NavoSkinSeeder::class,
            GachaItemSeeder::class,

            // === Users ===
            UserSeeder::class,

            // === Domain Data ===
            EventSeeder::class,
            SocialSeeder::class,
            PlaygroundSeeder::class,
        ]);
    }
}