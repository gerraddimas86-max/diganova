<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupPost extends Model
{
    protected $fillable = ['event_group_id', 'user_id', 'title', 'body', 'is_pinned'];

    protected function casts(): array
    {
        return ['is_pinned' => 'boolean'];
    }

    public function eventGroup(): BelongsTo
    {
        return $this->belongsTo(EventGroup::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}