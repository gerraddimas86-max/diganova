<?php

namespace App\Observers;

use App\Models\Post;
use App\Services\HashtagService;

class PostObserver
{
    public function __construct(protected HashtagService $hashtags)
    {
    }

    public function created(Post $post): void
    {
        $this->hashtags->sync($post);
    }

    public function updated(Post $post): void
    {
        if ($post->wasChanged('body')) {
            $this->hashtags->sync($post);
        }
    }

    public function deleted(Post $post): void
    {
        $this->hashtags->detach($post);
    }
}