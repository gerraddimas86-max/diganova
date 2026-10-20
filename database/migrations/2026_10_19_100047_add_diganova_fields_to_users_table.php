<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('name');
            $table->string('bio', 500)->nullable()->after('email');
            $table->foreignId('avatar_id')
                ->nullable()
                ->after('bio')
                ->constrained('avatars')
                ->nullOnDelete();
            $table->integer('total_points')->default(0)->after('avatar_id');
            $table->string('rank')->default('bronze')->after('total_points');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['avatar_id']);
            $table->dropColumn(['username', 'bio', 'avatar_id', 'total_points', 'rank']);
        });
    }
};