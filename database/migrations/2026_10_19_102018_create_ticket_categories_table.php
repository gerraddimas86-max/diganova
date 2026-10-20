<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('name');                        // "Daily Pass", "Full Pass", "VIP Pass"
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->integer('quota');
            $table->integer('sold_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['event_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_categories');
    }
};