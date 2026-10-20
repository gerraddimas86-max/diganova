<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['attack', 'defense', 'skill']);
            $table->text('description');
            $table->integer('energy_cost')->default(1);      // biaya main kartu
            $table->integer('damage')->default(0);          // damage dasar
            $table->integer('heal')->default(0);            // heal
            $table->integer('block')->default(0);           // block/shield
            $table->string('effect_type')->nullable();      // burn, dodge, reflect, dll
            $table->integer('effect_value')->default(0);    // nilai efek
            $table->integer('effect_duration')->default(0); // durasi turn
            $table->string('image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};