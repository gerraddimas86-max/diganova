<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description');
            $table->enum('category', ['music', 'cosplay', 'art', 'game_tournament', 'festival', 'other']);
            $table->string('location');
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->dateTime('registration_deadline')->nullable();
            $table->string('banner_image')->nullable();
            $table->integer('max_participants')->nullable();
            $table->enum('status', ['draft', 'published', 'ongoing', 'completed', 'cancelled'])->default('draft');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['status', 'start_date']);
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};