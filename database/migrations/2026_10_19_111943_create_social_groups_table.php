<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->enum('visibility', ['public', 'private'])->default('public');
            $table->unsignedInteger('members_count')->default(0);
            $table->timestamps();

            $table->index('owner_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_groups');
    }
};