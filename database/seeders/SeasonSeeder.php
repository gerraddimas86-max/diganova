<?php

namespace Database\Seeders;

use App\Models\Season;
use Illuminate\Database\Seeder;

class SeasonSeeder extends Seeder
{
    public function run(): void
    {
        Season::updateOrCreate(
            ['slug' => 'season-1'],
            [
                'name' => 'Season 1: Crystal Dawn',
                'description' => 'Season pembuka DIGANOVA. Awal dari segalanya.',
                'start_date' => now()->startOfMonth(),
                'end_date' => now()->addMonths(2)->endOfMonth(),
                'is_active' => true,
            ]
        );
    }
}