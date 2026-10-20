<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventSponsor extends Model
{
    protected $fillable = ['event_id', 'name', 'logo_path', 'website_url', 'tier', 'sort_order'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}