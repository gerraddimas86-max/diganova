<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ticket_category_id')->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('order_code')->unique();           // DGN-2024-XXXX
            $table->string('payment_proof_path')->nullable();
            $table->enum('payment_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->foreignId('payment_verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('payment_verified_at')->nullable();
            $table->text('payment_notes')->nullable();
            $table->string('e_ticket_code')->unique();        // untuk QR
            $table->boolean('checked_in')->default(false);
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'event_id']);
            $table->index(['event_id', 'payment_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
    }
};