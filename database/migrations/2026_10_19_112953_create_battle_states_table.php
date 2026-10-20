<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('battle_states', function (Blueprint $table) {
            $table->id();
            $table->foreignId('battle_id')->unique()->constrained()->cascadeOnDelete();

            $table->unsignedTinyInteger('current_turn')->default(1); // 1 atau 2

            $table->integer('player1_hp')->default(100);
            $table->integer('player2_hp')->default(100);
            $table->integer('player1_energy')->default(3);
            $table->integer('player2_energy')->default(3);

            $table->json('player1_deck')->nullable();
            $table->json('player2_deck')->nullable();
            $table->json('player1_hand')->nullable();
            $table->json('player2_hand')->nullable();
            $table->json('player1_effects')->nullable();
            $table->json('player2_effects')->nullable();

            $table->timestamp('turn_deadline')->nullable(); // server-side timer
            $table->unsignedInteger('player1_consecutive_disconnects')->default(0);
            $table->unsignedInteger('player2_consecutive_disconnects')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('battle_states');
    }
};