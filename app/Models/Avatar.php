<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Avatar extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image_path',
        'gender',
        'is_default',
        'unlock_type',
        'unlock_value',
        'unlock_description',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_avatars')
            ->withPivot('unlocked_at');
    }
}