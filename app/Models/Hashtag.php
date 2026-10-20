<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Hashtag extends Model
{
    protected $fillable = ['tag', 'post_count'];

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_hashtags');
    }

    public static function trending(int $limit = 10)
    {
        return static::where('post_count', '>', 0)
            ->orderByDesc('post_count')
            ->limit($limit)
            ->get();
    }
}