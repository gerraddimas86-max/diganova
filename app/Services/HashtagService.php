<?php

namespace App\Services;

use App\Models\Hashtag;
use App\Models\Post;

class HashtagService
{
    /**
     * Ekstrak hashtag dari body. Return array lowercase tag tanpa #.
     */
    public function extract(string $body): array
    {
        preg_match_all('/#([\p{L}\p{N}_]+)/u', $body, $matches);

        return array_values(array_unique(
            array_map(fn($t) => mb_strtolower($t), $matches[1] ?? [])
        ));
    }

    /**
     * Sync hashtag: hapus yang lama, tambah yang baru, update counter.
     */
    public function sync(Post $post): void
    {
        // Kurangi counter hashtag lama
        foreach ($post->hashtags as $oldTag) {
            if ($oldTag->post_count > 0) {
                $oldTag->decrement('post_count');
            }
        }
        $post->hashtags()->detach();

        // Extract & attach yang baru
        $tags = $this->extract($post->body);
        if (empty($tags)) {
            return;
        }

        $ids = [];
        foreach ($tags as $tag) {
            $hashtag = Hashtag::firstOrCreate(
                ['tag' => $tag],
                ['post_count' => 0]
            );
            $hashtag->increment('post_count');
            $ids[] = $hashtag->id;
        }

        $post->hashtags()->attach($ids);
    }

    /**
     * Hapus semua hashtag dari post (dipanggil saat delete).
     */
    public function detach(Post $post): void
    {
        foreach ($post->hashtags as $tag) {
            if ($tag->post_count > 0) {
                $tag->decrement('post_count');
            }
        }
        $post->hashtags()->detach();
    }
}