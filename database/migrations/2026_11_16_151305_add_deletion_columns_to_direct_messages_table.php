<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('direct_messages', function (Blueprint $table) {
            // Menyimpan ID user yang menghapus pesan ini (untuk fitur "Hapus untuk saya")
            $table->json('deleted_by')->nullable()->after('read_at');
            
            // Menandai apakah pesan sudah dihapus untuk semua orang
            $table->boolean('is_deleted_for_everyone')->default(false)->after('deleted_by');
        });
    }

    public function down(): void
    {
        Schema::table('direct_messages', function (Blueprint $table) {
            $table->dropColumn(['deleted_by', 'is_deleted_for_everyone']);
        });
    }
};