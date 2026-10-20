<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupMessage extends Model
{
    protected $fillable = ['event_group_id', 'user_id', 'message'];

    public function eventGroup(): BelongsTo
    {
        return $this->belongsTo(EventGroup::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}