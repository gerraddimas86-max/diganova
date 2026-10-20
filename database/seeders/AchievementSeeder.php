<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $achievements = [
            // === Event ===
            ['name' => 'First Event', 'slug' => 'first-event', 'description' => 'Ikut event pertamamu', 'icon' => '🎉', 'category' => 'event', 'points_reward' => 50],
            ['name' => 'Event Veteran', 'slug' => 'event-veteran', 'description' => 'Ikut 5 event', 'icon' => '🏆', 'category' => 'event', 'points_reward' => 200],
            ['name' => 'Champion', 'slug' => 'champion', 'description' => 'Juara 1 di event', 'icon' => '🥇', 'category' => 'event', 'points_reward' => 500],
            ['name' => 'Cosplayer', 'slug' => 'cosplayer', 'description' => 'Ikut lomba cosplay', 'icon' => '🎭', 'category' => 'event', 'points_reward' => 100],

            // === Social ===
            ['name' => 'First Post', 'slug' => 'first-post', 'description' => 'Buat post pertama', 'icon' => '✍️', 'category' => 'social', 'points_reward' => 10],
            ['name' => 'Social Butterfly', 'slug' => 'social-butterfly', 'description' => 'Post 10x', 'icon' => '🦋', 'category' => 'social', 'points_reward' => 100],
            ['name' => 'Influencer', 'slug' => 'influencer', 'description' => 'Dapat 100 like', 'icon' => '⭐', 'category' => 'social', 'points_reward' => 250],

            // === Playground ===
            ['name' => 'First Battle', 'slug' => 'first-battle', 'description' => 'Main battle pertama', 'icon' => '⚔️', 'category' => 'playground', 'points_reward' => 20],
            ['name' => 'Warrior', 'slug' => 'warrior', 'description' => 'Menang 10 battle', 'icon' => '🛡️', 'category' => 'playground', 'points_reward' => 150],
            ['name' => 'Legend', 'slug' => 'legend', 'description' => 'Capai rank Legend', 'icon' => '👑', 'category' => 'playground', 'points_reward' => 1000],

            // === General ===
            ['name' => 'Welcome', 'slug' => 'welcome', 'description' => 'Bergabung dengan DIGANOVA', 'icon' => '🦊', 'category' => 'general', 'points_reward' => 10],
        ];

        foreach ($achievements as $achievement) {
            Achievement::updateOrCreate(['slug' => $achievement['slug']], $achievement);
        }
    }
}