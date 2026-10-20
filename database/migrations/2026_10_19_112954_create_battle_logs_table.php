<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('battle_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('battle_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('turn_number');
            $table->foreignId('actor_user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->enum('action_type', [
                'play_card', 'skip_turn', 'timeout',
                'disconnect', 'forfeit', 'game_end',
            ]);

            $table->foreignId('card_id')->nullable()->constrained()->nullOnDelete();

            $table->integer('damage_dealt')->default(0);
            $table->integer('heal_amount')->default(0);
            $table->integer('block_amount')->default(0);
            $table->string('effect_applied')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();

            $table->index(['battle_id', 'turn_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('battle_logs');
    }
};