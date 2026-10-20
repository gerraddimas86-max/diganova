<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventGroup extends Model
{
    protected $fillable = ['event_id', 'name', 'description'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_group_members')
            ->withPivot(['role', 'joined_at']);
    }

    public function admins(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_group_members')
            ->withPivot(['role', 'joined_at'])
            ->wherePivot('role', 'admin');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(GroupMessage::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(GroupPost::class);
    }

    public function isMember(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    public function isAdmin(User $user): bool
    {
        return $this->admins()->where('user_id', $user->id)->exists();
    }
}