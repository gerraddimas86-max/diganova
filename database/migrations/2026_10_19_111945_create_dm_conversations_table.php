<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dm_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_one_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('user_two_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();

            // Pastikan user_one_id < user_two_id selalu, untuk cegah duplikat
            $table->unique(['user_one_id', 'user_two_id']);
            $table->index('last_message_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dm_conversations');
    }
};