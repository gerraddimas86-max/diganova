<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gacha_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['skin', 'emote', 'badge']);
            $table->enum('rarity', ['common', 'rare', 'epic', 'legendary']);
            $table->string('image_path')->nullable();
            $table->text('description')->nullable();
            $table->decimal('drop_rate', 5, 2)->default(0); // persen, misal 15.00
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['type', 'rarity']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gacha_items');
    }
};