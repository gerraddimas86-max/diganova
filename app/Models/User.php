<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'username',
    'email',
    'password',
    'bio',
    'avatar_id',
    'total_points',
    'rank',
    'is_admin',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'total_points' => 'integer',
            'is_admin' => 'boolean',
        ];
    }

    // ============================================================
    // CORE RELATIONS (Shared Domain)
    // ============================================================

    public function avatar(): BelongsTo
    {
        return $this->belongsTo(Avatar::class);
    }

    public function avatars(): BelongsToMany
    {
        return $this->belongsToMany(Avatar::class, 'user_avatars')
            ->withPivot('unlocked_at');
    }

    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
            ->withPivot('unlocked_at');
    }

    public function hallOfFame(): HasMany
    {
        return $this->hasMany(HallOfFame::class);
    }

    // ============================================================
    // EVENT DOMAIN RELATIONS
    // ============================================================

    public function createdEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    public function eventRegistrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function eventGroups(): BelongsToMany
    {
        return $this->belongsToMany(EventGroup::class, 'event_group_members')
            ->withPivot(['role', 'joined_at']);
    }

    public function groupMessages(): HasMany
    {
        return $this->hasMany(GroupMessage::class);
    }

    public function groupPosts(): HasMany
    {
        return $this->hasMany(GroupPost::class);
    }

    public function sponsorProposals(): HasMany
    {
        return $this->hasMany(SponsorProposal::class);
    }

    // ============================================================
    // SOCIAL DOMAIN RELATIONS
    // ============================================================

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function postLikes(): HasMany
    {
        return $this->hasMany(PostLike::class);
    }

    public function postComments(): HasMany
    {
        return $this->hasMany(PostComment::class);
    }

    public function reposts(): HasMany
    {
        return $this->hasMany(Repost::class);
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'follows',
            'following_id',
            'follower_id'
        )->withTimestamps();
    }

    public function following(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'follows',
            'follower_id',
            'following_id'
        )->withTimestamps();
    }

    public function ownedSocialGroups(): HasMany
    {
        return $this->hasMany(SocialGroup::class, 'owner_id');
    }

    public function socialGroups(): BelongsToMany
    {
        return $this->belongsToMany(SocialGroup::class, 'social_group_members')
            ->withPivot(['role', 'joined_at']);
    }

    public function sentDirectMessages(): HasMany
    {
        return $this->hasMany(DirectMessage::class, 'sender_id');
    }

    public function isFollowing(User $user): bool
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    // ============================================================
    // PLAYGROUND DOMAIN RELATIONS
    // ============================================================

    public function navoProfile(): HasOne
    {
        return $this->hasOne(NavoProfile::class);
    }

    public function decks(): HasMany
    {
        return $this->hasMany(Deck::class);
    }

    public function activeDeck(): HasOne
    {
        return $this->hasOne(Deck::class)->where('is_active', true);
    }

    public function battlesAsPlayer1(): HasMany
    {
        return $this->hasMany(Battle::class, 'player1_id');
    }

    public function battlesAsPlayer2(): HasMany
    {
        return $this->hasMany(Battle::class, 'player2_id');
    }

    public function wonBattles(): HasMany
    {
        return $this->hasMany(Battle::class, 'winner_id');
    }

    public function leaderboardEntries(): HasMany
    {
        return $this->hasMany(Leaderboard::class);
    }

    public function gachaItems(): BelongsToMany
    {
        return $this->belongsToMany(GachaItem::class, 'user_gacha_items')
            ->withPivot(['is_equipped', 'obtained_at']);
    }

    public function gachaPulls(): HasMany
    {
        return $this->hasMany(GachaPull::class);
    }

    public function matchmakingEntries(): HasMany
    {
        return $this->hasMany(MatchmakingQueue::class);
    }

    // ============================================================
    // HELPERS
    // ============================================================

    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }

    public function addPoints(int $points): void
    {
        $this->increment('total_points', $points);
        $this->updateRank();
    }

    public function updateRank(): void
    {
        $points = $this->total_points;

        $rank = match (true) {
            $points >= 5000 => 'legend',
            $points >= 3500 => 'diamond',
            $points >= 2000 => 'platinum',
            $points >= 1000 => 'gold',
            $points >= 500  => 'silver',
            default         => 'bronze',
        };

        $this->update(['rank' => $rank]);
    }
}