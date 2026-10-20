<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hall_of_fame', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('season_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('category', ['event', 'social', 'playground', 'general']);
            $table->string('title');                   // "Champion Season 1"
            $table->text('description')->nullable();
            $table->integer('rank_position')->nullable();
            $table->timestamp('awarded_at')->useCurrent();
            $table->timestamps();

            $table->index(['category', 'season_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hall_of_fame');
    }
};