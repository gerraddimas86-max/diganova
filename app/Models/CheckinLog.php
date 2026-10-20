<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckinLog extends Model
{
    protected $fillable = ['event_registration_id', 'user_id', 'method', 'check_in_at', 'ip_address', 'user_agent'];

    protected function casts(): array
    {
        return ['check_in_at' => 'datetime'];
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(EventRegistration::class, 'event_registration_id');
    }

    public function scanner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}