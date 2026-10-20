<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('decks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name')->default('Deck 1');
            $table->unsignedTinyInteger('slot')->default(1); // 1, 2, atau 3 preset
            $table->boolean('is_active')->default(false);    // deck yang dipakai saat battle
            $table->timestamps();

            $table->unique(['user_id', 'slot']);
            $table->index(['user_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('decks');
    }
};