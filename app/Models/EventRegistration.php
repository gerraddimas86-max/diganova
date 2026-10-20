<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventRegistration extends Model
{
    protected $fillable = [
        'event_id', 'ticket_category_id', 'user_id', 'order_code',
        'payment_proof_path', 'payment_status', 'payment_verified_by',
        'payment_verified_at', 'payment_notes', 'e_ticket_code',
        'checked_in', 'checked_in_at',
    ];

    protected function casts(): array
    {
        return [
            'payment_verified_at' => 'datetime',
            'checked_in_at' => 'datetime',
            'checked_in' => 'boolean',
        ];
    }

    // ============================================================
    // RELATIONS
    // ============================================================

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function ticketCategory(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payment_verified_by');
    }

    public function checkinLogs(): HasMany
    {
        return $this->hasMany(CheckinLog::class);
    }

    // ============================================================
    // HELPERS — Status & Actions
    // ============================================================

    public function isPending(): bool
    {
        return $this->payment_status === 'pending';
    }

    public function isVerified(): bool
    {
        return $this->payment_status === 'verified';
    }

    public function isRejected(): bool
    {
        return $this->payment_status === 'rejected';
    }

    public function canBeCheckedIn(): bool
    {
        return $this->isVerified() && !$this->checked_in;
    }

    /**
     * Tandai pembayaran terverifikasi.
     * - Set status + verifier + timestamp
     * - Auto-join grup event
     * - Unlock achievement "first-event" (sekali saja)
     * - Tambah points dari achievement
     */
    public function markVerified(?int $adminId = null): void
    {
        $this->update([
            'payment_status' => 'verified',
            'payment_verified_by' => $adminId,
            'payment_verified_at' => now(),
        ]);

        // Auto-join grup event
        $group = $this->event->group;
        if ($group) {
            $group->members()->syncWithoutDetaching([
                $this->user_id => ['role' => 'member', 'joined_at' => now()],
            ]);
        }

        // Unlock achievement "first-event" + add points
        $achievement = Achievement::where('slug', 'first-event')->first();
        if (
            $achievement
            && !$this->user->achievements()->where('achievement_id', $achievement->id)->exists()
        ) {
            $this->user->achievements()->attach($achievement->id, ['unlocked_at' => now()]);
            $this->user->addPoints($achievement->points_reward);
        }
    }

    /**
     * Tandai pembayaran ditolak.
     * - Set status + verifier + notes
     * - Release kuota tiket (sold_count--)
     */
    public function markRejected(?int $adminId = null, ?string $notes = null): void
    {
        // Guard: jangan reject yang sudah verified (kuota sudah aman)
        $wasPending = $this->payment_status === 'pending';

        $this->update([
            'payment_status' => 'rejected',
            'payment_verified_by' => $adminId,
            'payment_verified_at' => now(),
            'payment_notes' => $notes,
        ]);

        // Release kuota tiket hanya kalau sebelumnya masih pending
        if ($wasPending) {
            $this->ticketCategory->decrement('sold_count');
        }
    }

    /**
     * Tandai check-in berhasil.
     */
    public function markCheckedIn(): void
    {
        $this->update([
            'checked_in' => true,
            'checked_in_at' => now(),
        ]);
    }
}