<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SponsorProposal extends Model
{
    protected $fillable = [
        'event_id', 'user_id', 'company_name', 'contact_email', 'contact_phone',
        'proposal_text', 'attachment_path', 'tier_requested', 'status',
        'admin_notes', 'reviewed_by', 'reviewed_at',
    ];

    protected function casts(): array
    {
        return ['reviewed_at' => 'datetime'];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}