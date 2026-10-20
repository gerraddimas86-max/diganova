<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DmConversation extends Model
{
    protected $fillable = ['user_one_id', 'user_two_id', 'last_message_at'];

    protected function casts(): array
    {
        return ['last_message_at' => 'datetime'];
    }

    public function userOne(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(DirectMessage::class)->orderBy('created_at');
    }

    /**
     * Cari atau buat conversation antara 2 user.
     * Selalu simpan dengan user_one_id < user_two_id untuk hindari duplikat.
     */
    public static function findOrCreateBetween(int $userIdA, int $userIdB): self
    {
        $oneId = min($userIdA, $userIdB);
        $twoId = max($userIdA, $userIdB);

        return static::firstOrCreate([
            'user_one_id' => $oneId,
            'user_two_id' => $twoId,
        ]);
    }
}