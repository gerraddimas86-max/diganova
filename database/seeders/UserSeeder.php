<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Avatar;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $defaultAvatar = Avatar::where('is_default', true)->first();
        $welcomeAchievement = Achievement::where('slug', 'welcome')->first();

        $users = [
            [
                'name' => 'Admin DIGANOVA',
                'username' => 'admin',
                'email' => 'admin@diganova.test',
                'bio' => 'Admin & founder DIGANOVA. Plan It. Join It. Enjoy It! 🦊',
                'total_points' => 0,
                'is_admin' => true,
            ],
            [
                'name' => 'Rani Putri',
                'username' => 'rani',
                'email' => 'rani@diganova.test',
                'bio' => 'Pecinta musik & cosplay 🎵🎭',
                'total_points' => 750,
                'is_admin' => false,
            ],
            [
                'name' => 'Bagas Pratama',
                'username' => 'bagas',
                'email' => 'bagas@diganova.test',
                'bio' => 'Gamer strategi. Navo Clash main semua hari 🎮',
                'total_points' => 2300,
                'is_admin' => false,
            ],
            [
                'name' => 'Sinta Dewi',
                'username' => 'sinta',
                'email' => 'sinta@diganova.test',
                'bio' => 'Kreator sosial. Suka nulis & diskusi ✍️',
                'total_points' => 1200,
                'is_admin' => false,
            ],
            [
                'name' => 'Andi Wijaya',
                'username' => 'andi',
                'email' => 'andi@diganova.test',
                'bio' => 'Sponsor & partner event DIGANOVA',
                'total_points' => 100,
                'is_admin' => false,
            ],
        ];

        foreach ($users as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                array_merge($data, [
                    'password' => Hash::make('password'),
                    'avatar_id' => $defaultAvatar?->id,
                ])
            );

            // Auto-assign default avatar
            if ($defaultAvatar) {
                $user->avatars()->syncWithoutDetaching([$defaultAvatar->id]);
            }

            // Unlock welcome achievement
            if ($welcomeAchievement) {
                $user->achievements()->syncWithoutDetaching([$welcomeAchievement->id]);
            }

            // Update rank berdasarkan poin
            $user->updateRank();
        }

        $this->command->info('✅ 5 users created (1 admin, 4 regular) — password: "password"');
    }
}