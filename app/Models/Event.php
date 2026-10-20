<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'category', 'location', 'address',
        'latitude', 'longitude', 'start_date', 'end_date', 'registration_deadline',
        'banner_image', 'max_participants', 'status', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'registration_deadline' => 'datetime',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    // ============================================================
    // RELATIONS
    // ============================================================

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(EventSchedule::class)->orderBy('start_time');
    }

    public function ticketCategories(): HasMany
    {
        return $this->hasMany(TicketCategory::class);
    }

    public function sponsors(): HasMany
    {
        return $this->hasMany(EventSponsor::class);
    }

    public function sponsorProposals(): HasMany
    {
        return $this->hasMany(SponsorProposal::class);
    }

    public function venueMaps(): HasMany
    {
        return $this->hasMany(VenueMap::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function group(): HasOne
    {
        return $this->hasOne(EventGroup::class);
    }

    // ============================================================
    // HELPERS — Status & Availability
    // ============================================================

    /**
     * Apakah event masih menerima pendaftaran?
     */
    public function isRegistrationOpen(): bool
    {
        if ($this->status !== 'published') {
            return false;
        }

        if ($this->registration_deadline && $this->registration_deadline->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Tiket yang masih tersedia (aktif & belum habis).
     */
    public function availableTickets()
    {
        return $this->ticketCategories()
            ->where('is_active', true)
            ->whereRaw('sold_count < quota')
            ->get();
    }

    /**
     * Total peserta (pending + verified).
     */
    public function totalParticipants(): int
    {
        return $this->registrations()
            ->whereIn('payment_status', ['pending', 'verified'])
            ->count();
    }

    /**
     * Apakah user sudah pernah daftar event ini?
     */
    public function isRegisteredBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $this->registrations()
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Jumlah registrasi user (untuk cek limit max 3x).
     */
    public function registrationCountBy(?User $user): int
    {
        if (!$user) {
            return 0;
        }

        return $this->registrations()
            ->where('user_id', $user->id)
            ->count();
    }
}