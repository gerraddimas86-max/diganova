<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leaderboards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('season_id')->nullable()->constrained()->nullOnDelete();
            $table->string('rank')->default('bronze');
            $table->integer('points')->default(0);
            $table->unsignedInteger('wins')->default(0);
            $table->unsignedInteger('losses')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'season_id']);
            $table->index(['season_id', 'points']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leaderboards');
    }
};