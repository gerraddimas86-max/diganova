<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('name');                    // "First Event"
            $table->string('slug')->unique();          // "first-event"
            $table->text('description');
            $table->string('icon')->nullable();        // emoji atau path gambar
            $table->enum('category', ['event', 'social', 'playground', 'general']);
            $table->integer('points_reward')->default(0);
            $table->timestamps();

            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};