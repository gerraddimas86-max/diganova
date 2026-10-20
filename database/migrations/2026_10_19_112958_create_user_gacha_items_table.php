<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_gacha_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('gacha_item_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_equipped')->default(false);
            $table->timestamp('obtained_at')->useCurrent();
            $table->timestamps();

            $table->unique(['user_id', 'gacha_item_id']);
            $table->index(['user_id', 'is_equipped']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_gacha_items');
    }
};