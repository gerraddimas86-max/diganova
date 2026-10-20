<?php

namespace Database\Seeders;

use App\Models\Card;
use Illuminate\Database\Seeder;

class CardSeeder extends Seeder
{
    public function run(): void
    {
        $cards = [
            // ============ ATTACK (7) ============
            [
                'name' => 'Crystal Slash',
                'slug' => 'crystal-slash',
                'type' => 'attack',
                'description' => 'Serangan kristal tajam. Damage 30.',
                'energy_cost' => 1,
                'damage' => 30,
                'effect_type' => null,
            ],
            [
                'name' => 'Fox Fire',
                'slug' => 'fox-fire',
                'type' => 'attack',
                'description' => 'Api rubah. Damage 20 + burn 5/turn selama 3 turn.',
                'energy_cost' => 2,
                'damage' => 20,
                'effect_type' => 'burn',
                'effect_value' => 5,
                'effect_duration' => 3,
            ],
            [
                'name' => 'Quick Paw',
                'slug' => 'quick-paw',
                'type' => 'attack',
                'description' => 'Cakar cepat. Damage 15, bisa dimainkan 2x per turn.',
                'energy_cost' => 1,
                'damage' => 15,
                'effect_type' => 'double_play',
            ],
            [
                'name' => 'Star Burst',
                'slug' => 'star-burst',
                'type' => 'attack',
                'description' => 'Ledakan bintang. Damage 40, tapi skip turn berikutnya.',
                'energy_cost' => 3,
                'damage' => 40,
                'effect_type' => 'self_skip',
                'effect_duration' => 1,
            ],
            [
                'name' => 'Meteor Drop',
                'slug' => 'meteor-drop',
                'type' => 'attack',
                'description' => 'Hujan meteor. Damage 50. Butuh 3 energy.',
                'energy_cost' => 3,
                'damage' => 50,
                'effect_type' => null,
            ],
            [
                'name' => 'Triple Strike',
                'slug' => 'triple-strike',
                'type' => 'attack',
                'description' => 'Serangan 3x. Total damage 30.',
                'energy_cost' => 2,
                'damage' => 10,
                'effect_type' => 'triple_hit',
            ],
            [
                'name' => 'Piercing Fang',
                'slug' => 'piercing-fang',
                'type' => 'attack',
                'description' => 'Taring menembus shield. Damage 25, abaikan block.',
                'energy_cost' => 2,
                'damage' => 25,
                'effect_type' => 'pierce',
            ],

            // ============ DEFENSE (7) ============
            [
                'name' => 'Crystal Shield',
                'slug' => 'crystal-shield',
                'type' => 'defense',
                'description' => 'Perisai kristal. Block 30 damage.',
                'energy_cost' => 1,
                'block' => 30,
                'effect_type' => null,
            ],
            [
                'name' => 'Healing Light',
                'slug' => 'healing-light',
                'type' => 'defense',
                'description' => 'Cahaya penyembuh. Heal 20 HP.',
                'energy_cost' => 1,
                'heal' => 20,
                'effect_type' => null,
            ],
            [
                'name' => 'Iron Fur',
                'slug' => 'iron-fur',
                'type' => 'defense',
                'description' => 'Bulu besi. Defense +10 selama 2 turn.',
                'energy_cost' => 1,
                'effect_type' => 'defense_up',
                'effect_value' => 10,
                'effect_duration' => 2,
            ],
            [
                'name' => 'Dodge',
                'slug' => 'dodge',
                'type' => 'defense',
                'description' => 'Hindari 1 serangan berikutnya.',
                'energy_cost' => 2,
                'effect_type' => 'dodge',
                'effect_duration' => 1,
            ],
            [
                'name' => 'Mirror Shield',
                'slug' => 'mirror-shield',
                'type' => 'defense',
                'description' => 'Reflect 50% damage ke lawan.',
                'energy_cost' => 2,
                'effect_type' => 'reflect',
                'effect_value' => 50,
                'effect_duration' => 1,
            ],
            [
                'name' => 'Second Wind',
                'slug' => 'second-wind',
                'type' => 'defense',
                'description' => 'Heal 40 HP. Hanya sekali per battle.',
                'energy_cost' => 3,
                'heal' => 40,
                'effect_type' => 'once_per_battle',
            ],
            [
                'name' => 'Fortress',
                'slug' => 'fortress',
                'type' => 'defense',
                'description' => 'Block 50 damage, tapi skip attack turn ini.',
                'energy_cost' => 3,
                'block' => 50,
                'effect_type' => 'skip_attack',
            ],

            // ============ SKILL (6) ============
            [
                'name' => 'Power Up',
                'slug' => 'power-up',
                'type' => 'skill',
                'description' => '+10 damage selama 2 turn.',
                'energy_cost' => 1,
                'effect_type' => 'damage_up',
                'effect_value' => 10,
                'effect_duration' => 2,
            ],
            [
                'name' => 'Slow Down',
                'slug' => 'slow-down',
                'type' => 'skill',
                'description' => 'Lawan skip 1 turn.',
                'energy_cost' => 2,
                'effect_type' => 'skip_enemy',
                'effect_duration' => 1,
            ],
            [
                'name' => 'Energy Boost',
                'slug' => 'energy-boost',
                'type' => 'skill',
                'description' => 'Dapat +2 energy langsung.',
                'energy_cost' => 1,
                'effect_type' => 'energy_gain',
                'effect_value' => 2,
            ],
            [
                'name' => 'Steal',
                'slug' => 'steal',
                'type' => 'skill',
                'description' => 'Ambil 1 kartu random dari tangan lawan.',
                'energy_cost' => 2,
                'effect_type' => 'steal_card',
            ],
            [
                'name' => 'Copy',
                'slug' => 'copy',
                'type' => 'skill',
                'description' => 'Copy kartu terakhir yang dimainkan lawan.',
                'energy_cost' => 2,
                'effect_type' => 'copy_card',
            ],
            [
                'name' => 'Sacrifice',
                'slug' => 'sacrifice',
                'type' => 'skill',
                'description' => 'Korbankan 10 HP untuk +3 energy.',
                'energy_cost' => 0,
                'effect_type' => 'sacrifice',
                'effect_value' => 10,
            ],
        ];

        foreach ($cards as $card) {
            Card::updateOrCreate(
                ['slug' => $card['slug']],
                array_merge([
                    'heal' => 0,
                    'block' => 0,
                    'effect_value' => 0,
                    'effect_duration' => 0,
                    'is_active' => true,
                ], $card)
            );
        }

        $this->command->info('✅ 20 cards seeded (7 attack, 7 defense, 6 skill)');
    }
}