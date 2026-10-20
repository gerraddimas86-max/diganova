<?php

namespace Database\Seeders;

use App\Models\Avatar;
use Illuminate\Database\Seeder;

class AvatarSeeder extends Seeder
{
    public function run(): void
    {
        $avatars = [
            // === 2 Default (gratis) ===
            [
                'name' => 'Novo Male',
                'slug' => 'novo-male',
                'image_path' => 'avatars/novo-male.png',
                'gender' => 'male',
                'is_default' => true,
                'unlock_type' => null,
                'unlock_value' => null,
                'unlock_description' => 'Avatar default',
            ],
            [
                'name' => 'Nova Female',
                'slug' => 'nova-female',
                'image_path' => 'avatars/nova-female.png',
                'gender' => 'female',
                'is_default' => true,
                'unlock_type' => null,
                'unlock_value' => null,
                'unlock_description' => 'Avatar default',
            ],

            // === 8 Unlock via achievement ===
            [
                'name' => 'Event Goer',
                'slug' => 'event-goer',
                'image_path' => 'avatars/event-goer.png',
                'gender' => 'unisex',
                'is_default' => false,
                'unlock_type' => 'event_count',
                'unlock_value' => 1,
                'unlock_description' => 'Ikut 1 event',
            ],
            [
                'name' => 'Gamer',
                'slug' => 'gamer',
                'image_path' => 'avatars/gamer.png',
                'gender' => 'unisex',
                'is_default' => false,
                'unlock_type' => 'game_count',
                'unlock_value' => 1,
                'unlock_description' => 'Main 1 game',
            ],
            [
                'name' => 'Social Butterfly',
                'slug' => 'social-butterfly',
                'image_path' => 'avatars/social-butterfly.png',
                'gender' => 'unisex',
                'is_default' => false,
                'unlock_type' => 'post_count',
                'unlock_value' => 10,
                'unlock_description' => 'Post 10x',
            ],
            [
                'name' => 'Influencer',
                'slug' => 'influencer',
                'image_path' => 'avatars/influencer.png',
                'gender' => 'unisex',
                'is_default' => false,
                'unlock_type' => 'like_count',
                'unlock_value' => 100,
                'unlock_description' => 'Dapat 100 like',
            ],
            [
                'name' => 'Champion',
                'slug' => 'champion',
                'image_path' => 'avatars/champion.png',
                'gender' => 'unisex',
                'is_default' => false,
                'unlock_type' => 'event_win',
                'unlock_value' => 1,
                'unlock_description' => 'Juara 1 event',
            ],
            [
                'name' => 'Veteran',
                'slug' => 'veteran',
                'image_path' => 'avatars/veteran.png',
                'gender' => 'unisex',
                'is_default' => false,
                'unlock_type' => 'event_count',
                'unlock_value' => 5,
                'unlock_description' => 'Ikut 5 event',
            ],
            [
                'name' => 'Legend',
                'slug' => 'legend',
                'image_path' => 'avatars/legend.png',
                'gender' => 'unisex',
                'is_default' => false,
                'unlock_type' => 'season_rank',
                'unlock_value' => 1,
                'unlock_description' => 'Peringkat 1 season',
            ],
            [
                'name' => 'Cosplayer',
                'slug' => 'cosplayer',
                'image_path' => 'avatars/cosplayer.png',
                'gender' => 'unisex',
                'is_default' => false,
                'unlock_type' => 'cosplay_join',
                'unlock_value' => 1,
                'unlock_description' => 'Ikut lomba cosplay',
            ],
        ];

        foreach ($avatars as $avatar) {
            Avatar::updateOrCreate(['slug' => $avatar['slug']], $avatar);
        }
    }
}