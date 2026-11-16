<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DirectMessage extends Model
{
    /**
     * Kolom yang boleh diisi secara massal (Mass Assignment).
     */
    protected $fillable = [
        'dm_conversation_id', 
        'sender_id', 
        'body', 
        'is_read', 
        'read_at',
        'deleted_by',              // Menyimpan array ID user yang menghapus pesan ini (Hapus untuk saya)
        'is_deleted_for_everyone', // Menandai apakah pesan dihapus untuk semua orang
    ];

    /**
     * Konversi tipe data kolom (Casting).
     */
    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'read_at' => 'datetime',
            'is_deleted_for_everyone' => 'boolean', 
            'deleted_by' => 'array', // Otomatis mengubah JSON database menjadi array PHP dan sebaliknya
        ];
    }

    /**
     * Relasi ke percakapan (Conversation).
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(DmConversation::class, 'dm_conversation_id');
    }

    /**
     * Relasi ke pengirim pesan (User).
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}