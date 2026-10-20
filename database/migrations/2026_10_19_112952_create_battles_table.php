<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('battles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('season_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('mode', ['pve', 'pvp']);
            $table->foreignId('player1_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('player2_id')->nullable()->constrained('users')->nullOnDelete();

            $table->enum('status', ['waiting', 'in_progress', 'completed', 'cancelled', 'forfeited'])
                ->default('waiting');

            $table->foreignId('winner_id')->nullable()->constrained('users')->nullOnDelete();

            $table->integer('player1_hp_final')->nullable();
            $table->integer('player2_hp_final')->nullable();

            $table->integer('player1_points_change')->default(0);
            $table->integer('player2_points_change')->default(0);

            $table->unsignedInteger('turn_count')->default(0);

            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();

            $table->timestamps();

            $table->index(['status', 'mode']);
            $table->index(['player1_id', 'player2_id']);
            $table->index('season_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('battles');
    }
};