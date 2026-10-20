<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('battle_states', function (Blueprint $table) {
            $table->unsignedBigInteger('player1_pending_card_id')->nullable()->after('player1_hand');
            $table->unsignedBigInteger('player2_pending_card_id')->nullable()->after('player2_hand');
            $table->boolean('player1_skip_ready')->default(false)->after('player1_pending_card_id');
            $table->boolean('player2_skip_ready')->default(false)->after('player2_pending_card_id');
        });
    }

    public function down(): void
    {
        Schema::table('battle_states', function (Blueprint $table) {
            $table->dropColumn([
                'player1_pending_card_id',
                'player2_pending_card_id',
                'player1_skip_ready',
                'player2_skip_ready',
            ]);
        });
    }
};