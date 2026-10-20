<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checkin_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_registration_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained(); // admin yang scan
            $table->enum('method', ['qr', 'manual'])->default('qr');
            $table->timestamp('check_in_at')->useCurrent();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index('event_registration_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checkin_logs');
    }
};