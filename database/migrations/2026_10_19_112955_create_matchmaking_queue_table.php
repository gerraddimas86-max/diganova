<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matchmaking_queue', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('season_id')->nullable()->constrained()->nullOnDelete();
            $table->string('rank'); // snapshot rank saat masuk queue
            $table->enum('status', ['waiting', 'matched', 'cancelled'])->default('waiting');
            $table->foreignId('matched_battle_id')->nullable()->constrained('battles')->nullOnDelete();
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();

            $table->index(['status', 'rank']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matchmaking_queue');
    }
};