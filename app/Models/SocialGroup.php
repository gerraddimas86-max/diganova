<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SocialGroup extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'owner_id', 'visibility', 'members_count',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ============================================================
    // RELATIONS
    // ============================================================

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Semua member (approved only).
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'social_group_members')
            ->withPivot(['role', 'joined_at', 'status'])
            ->wherePivot('status', 'approved');
    }

    /**
     * Admin grup (approved + role admin).
     */
    public function admins(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'social_group_members')
            ->withPivot(['role', 'joined_at', 'status'])
            ->wherePivot('status', 'approved')
            ->wherePivot('role', 'admin');
    }

    /**
     * Pending members (untuk private group).
     */
    public function pendingMembers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'social_group_members')
            ->withPivot(['role', 'joined_at', 'status'])
            ->wherePivot('status', 'pending');
    }

    // ============================================================
    // HELPERS
    // ============================================================

    public function isOwner(User $user): bool
    {
        return $this->owner_id === $user->id;
    }

    public function isMember(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    public function isAdmin(User $user): bool
    {
        if ($this->isOwner($user)) {
            return true;
        }
        return $this->admins()->where('user_id', $user->id)->exists();
    }

    public function hasPendingRequest(User $user): bool
    {
        return $this->pendingMembers()->where('user_id', $user->id)->exists();
    }

    public function isPrivate(): bool
    {
        return $this->visibility === 'private';
    }

    public function syncMembersCount(): void
    {
        $this->update(['members_count' => $this->members()->count()]);
    }
}