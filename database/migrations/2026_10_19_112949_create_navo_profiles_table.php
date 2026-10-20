<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('navo_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('equipped_skin_id')->nullable()->constrained('navo_skins')->nullOnDelete();
            $table->foreignId('favorite_card_id')->nullable()->constrained('cards')->nullOnDelete();
            $table->json('equipped_accessories')->nullable(); // array of item IDs
            $table->unsignedInteger('total_battles')->default(0);
            $table->unsignedInteger('wins')->default(0);
            $table->unsignedInteger('losses')->default(0);
            $table->unsignedInteger('current_streak')->default(0);
            $table->unsignedInteger('best_streak')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('navo_profiles');
    }
};