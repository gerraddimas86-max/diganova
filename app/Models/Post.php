<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    protected $fillable = ['user_id', 'body', 'likes_count', 'comments_count', 'reposts_count', 'is_pinned'];

    protected function casts(): array
    {
        return ['is_pinned' => 'boolean'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(PostLike::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class)->orderBy('created_at');
    }

    public function reposts(): HasMany
    {
        return $this->hasMany(Repost::class);
    }

    public function hashtags(): BelongsToMany
    {
        return $this->belongsToMany(Hashtag::class, 'post_hashtags');
    }

    public function isLikedBy(?User $user): bool
    {
        if (!$user) return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function isRepostedBy(?User $user): bool
    {
        if (!$user) return false;
        return $this->reposts()->where('user_id', $user->id)->exists();
    }
}