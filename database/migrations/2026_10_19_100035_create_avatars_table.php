<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avatars', function (Blueprint $table) {
            $table->id();
            $table->string('name');                    // "Event Goer"
            $table->string('slug')->unique();          // "event-goer"
            $table->string('image_path');              // "avatars/event-goer.png"
            $table->enum('gender', ['male', 'female', 'unisex'])->default('unisex');
            $table->boolean('is_default')->default(false); // true = 2 avatar gratis
            $table->string('unlock_type')->nullable();  // "event_count", "post_count", dll
            $table->integer('unlock_value')->nullable(); // 1, 5, 10, 100, dst
            $table->string('unlock_description')->nullable(); // "Ikut 1 event"
            $table->timestamps();

            $table->index('is_default');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avatars');
    }
};