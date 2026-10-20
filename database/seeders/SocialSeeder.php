<?php

namespace Database\Seeders;

use App\Models\Follow;
use App\Models\Hashtag;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostLike;
use App\Models\User;
use Illuminate\Database\Seeder;

class SocialSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all()->keyBy('username');

        if ($users->isEmpty()) {
            $this->command->warn('⚠️  User belum ada. Jalankan UserSeeder dulu.');
            return;
        }

        // ============ POSTS ============
        $postsData = [
            ['username' => 'admin', 'body' => "Selamat datang di DIGANOVA! 🦊✨\n\nSatu akun, tiga pengalaman: Event, Social, Playground. Yuk ikut festival tahunan kita!\n\n#diganova #welcome", 'hashtags' => ['diganova', 'welcome']],
            ['username' => 'rani', 'body' => "Baru daftar DIGANOVA Festival 2026, Full Pass! Siapa lagi yang ikut? Kita meetup yuk di cosplay competition 🎭✨\n\n#diganova #cosplay #festival2026", 'hashtags' => ['diganova', 'cosplay', 'festival2026']],
            ['username' => 'bagas', 'body' => "Navo Clash main lagi nih. Deck attack speed bener-bener kuat 💪⚔️\n\nAda yang mau challenge? PvP queue sekarang!\n\n#navoclash #gaming #diganova", 'hashtags' => ['navoclash', 'gaming', 'diganova']],
            ['username' => 'sinta', 'body' => "Sunset di festival kemarin bener-bener magic. Sampai sekarang masih kebayang 🌅\n\nNext event pasti ikut lagi!\n\n#diganova #festival #sunset", 'hashtags' => ['diganova', 'festival', 'sunset']],
            ['username' => 'andi', 'body' => "Baru submit proposal sponsorship untuk DIGANOVA Festival 2026. Brand kita sangat cocok dengan target audiens gen-z! 🤝\n\n#sponsorship #partnership", 'hashtags' => ['sponsorship', 'partnership']],
            ['username' => 'rani', 'body' => "Tips main Navo Clash: jangan buru-buru habiskan energy, simpan buat defense kalau HP udah low 🛡️\n\n#navoclash #tips", 'hashtags' => ['navoclash', 'tips']],
            ['username' => 'bagas', 'body' => "Rank naik ke Platinum! 🎉 Achievement baru unlocked. Next target Diamond!\n\n#navoclash #achievement", 'hashtags' => ['navoclash', 'achievement']],
            ['username' => 'sinta', 'body' => "Diskusi yuk: menurut kalian apa yang bikin komunitas DIGANOVA beda dari yang lain? 🤔\n\n#diskusi #diganova", 'hashtags' => ['diskusi', 'diganova']],
        ];

        foreach ($postsData as $i => $data) {
            $user = $users[$data['username']] ?? null;
            if (!$user) continue;

            $post = Post::create([
                'user_id' => $user->id,
                'body' => $data['body'],
            ]);

            // Attach hashtags
            foreach ($data['hashtags'] ?? [] as $tag) {
                $hashtag = Hashtag::firstOrCreate(
                    ['tag' => $tag],
                    ['post_count' => 0]
                );
                $post->hashtags()->syncWithoutDetaching([$hashtag->id]);
                $hashtag->increment('post_count');
            }
        }

        // ============ LIKES & COMMENTS ============
        $allPosts = Post::all();
        $allUsers = User::all();

        foreach ($allPosts as $post) {
            // Random likes (skip own post sometimes)
            $likers = $allUsers->where('id', '!=', $post->user_id)->random(min(3, $allUsers->count() - 1));
            foreach ($likers as $liker) {
                PostLike::firstOrCreate([
                    'post_id' => $post->id,
                    'user_id' => $liker->id,
                ]);
            }
            $post->update(['likes_count' => $post->likes()->count()]);

            // Random comments
            if (rand(0, 1) === 1) {
                $commenter = $allUsers->where('id', '!=', $post->user_id)->random();
                PostComment::create([
                    'post_id' => $post->id,
                    'user_id' => $commenter->id,
                    'body' => 'Mantap! 🔥',
                ]);
                $post->update(['comments_count' => $post->comments()->count()]);
            }
        }

        // ============ FOLLOWS ============
        $usernames = $allUsers->pluck('username')->toArray();
        foreach ($allUsers as $user) {
            foreach ($allUsers as $other) {
                if ($user->id === $other->id) continue;
                // Random 60% chance follow
                if (rand(1, 10) <= 6) {
                    Follow::firstOrCreate([
                        'follower_id' => $user->id,
                        'following_id' => $other->id,
                    ]);
                }
            }
        }

        $this->command->info('✅ ' . Post::count() . ' posts + likes + comments + follows seeded');
    }
}