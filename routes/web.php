<?php

use App\Http\Controllers\Admin\CheckinController as AdminCheckinController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\RegistrationController as AdminRegistrationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventGroupController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\HubController;
use App\Http\Controllers\Playground\BattleController as PlaygroundBattleController;
use App\Http\Controllers\Playground\DeckController as PlaygroundDeckController;
use App\Http\Controllers\Playground\GachaController as PlaygroundGachaController;
use App\Http\Controllers\Playground\InventoryController as PlaygroundInventoryController;
use App\Http\Controllers\Playground\LeaderboardController as PlaygroundLeaderboardController;
use App\Http\Controllers\Playground\MatchmakingController as PlaygroundMatchmakingController;
use App\Http\Controllers\Playground\NavoProfileController as PlaygroundNavoProfileController;
use App\Http\Controllers\Playground\PlaygroundController;
use App\Http\Controllers\Playground\PvpBattleController as PlaygroundPvpBattleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Social\FollowController as SocialFollowController;
use App\Http\Controllers\Social\MessageController as SocialMessageController;
use App\Http\Controllers\Social\PostController as SocialPostController;
use App\Http\Controllers\Social\PostInteractionController as SocialPostInteractionController;
use App\Http\Controllers\Social\ProfileController as SocialProfileController;
use App\Http\Controllers\Social\SearchController as SocialSearchController;
use App\Http\Controllers\Social\SocialGroupController;
use App\Http\Controllers\Social\SocialGroupMemberController;
use App\Http\Controllers\Social\SocialHomeController;
use App\Http\Controllers\Social\TrendingController as SocialTrendingController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| DIGANOVA — Routing (Final Batch 7)
|--------------------------------------------------------------------------
*/

// ============ AUTH & SHARED ============
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // ============ HUB (Domain Selector) ============
    Route::get('/hub', [HubController::class, 'index'])->name('hub');
    
    // Redirect dashboard lama ke hub
    Route::get('/dashboard', fn() => redirect()->route('hub'))->name('dashboard');

    // ============ UNIFIED PROFILE (Part 5) ============
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// ================================================================
// DOMAIN: EVENT (diganova.test)
// ================================================================
Route::domain(config('app.event_domain'))->group(function () {

    // -------- Halaman Depan (Guest Landing / Auth Home) --------
    Route::get('/', [EventController::class, 'home'])->name('event.home');

    // -------- Auth Required (Semua fitur Event wajib login) --------
    Route::middleware('auth')->group(function () {
        
        // Event Public
        Route::get('/events', [EventController::class, 'index'])->name('event.events.index');
        Route::get('/events/{slug}', [EventController::class, 'show'])->name('event.events.show');

        // Registrasi Event
        Route::get('/events/{slug}/register', [EventRegistrationController::class, 'create'])->name('event.registrations.create');
        Route::post('/events/{slug}/register', [EventRegistrationController::class, 'store'])->name('event.registrations.store');
        Route::get('/registrations', [EventRegistrationController::class, 'index'])->name('event.registrations.index');

        // E-Ticket (Urutan diperhatikan agar tidak bentrok)
        Route::get('/registrations/{orderCode}/ticket', [TicketController::class, 'show'])->name('event.registrations.ticket');
        Route::get('/registrations/{orderCode}/ticket/download', [TicketController::class, 'download'])->name('event.registrations.ticket.download');
        Route::get('/registrations/{orderCode}', [EventRegistrationController::class, 'show'])->name('event.registrations.show');

        // Grup Event
        Route::get('/groups', [EventGroupController::class, 'index'])->name('event.groups.index');
        Route::get('/groups/{group}', [EventGroupController::class, 'show'])->name('event.groups.show');
        Route::post('/groups/{group}/posts', [EventGroupController::class, 'postStore'])->name('event.groups.posts.store');
        Route::delete('/groups/{group}/posts/{post}', [EventGroupController::class, 'postDestroy'])->name('event.groups.posts.destroy');
        Route::post('/groups/{group}/posts/{post}/pin', [EventGroupController::class, 'postPin'])->name('event.groups.posts.pin');
        Route::get('/groups/{group}/messages', [EventGroupController::class, 'messages'])->name('event.groups.messages.index');
        Route::post('/groups/{group}/messages', [EventGroupController::class, 'messageStore'])->name('event.groups.messages.store');
    });

    // -------- Admin Panel (auth + admin) --------
    Route::middleware(['auth', 'admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::resource('events', AdminEventController::class);
            Route::get('/registrations', [AdminRegistrationController::class, 'index'])->name('registrations.index');
            Route::get('/registrations/{registration}', [AdminRegistrationController::class, 'show'])->name('registrations.show');
            Route::post('/registrations/{registration}/approve', [AdminRegistrationController::class, 'approve'])->name('registrations.approve');
            Route::post('/registrations/{registration}/reject', [AdminRegistrationController::class, 'reject'])->name('registrations.reject');
            Route::get('/checkin', [AdminCheckinController::class, 'index'])->name('checkin.index');
            Route::get('/checkin/{event}', [AdminCheckinController::class, 'scanner'])->name('checkin.scanner');
            Route::post('/checkin/{event}/process', [AdminCheckinController::class, 'process'])->name('checkin.process');
        });
});

// ================================================================
// DOMAIN: SOCIAL (social.diganova.test)
// ================================================================
Route::domain(config('app.social_domain'))->group(function () {

    // -------- Halaman Depan (Guest Landing / Welcome Dashboard) --------
    Route::get('/', [SocialHomeController::class, 'home'])->name('social.home');

    // -------- Auth Required (Semua fitur Social wajib login) --------
    Route::middleware('auth')->group(function () {
        
        // Feed Utama
        Route::get('/feed', [SocialPostController::class, 'index'])->name('social.posts.index');
        
        // Profil publik
        Route::get('/u/{username}', [SocialProfileController::class, 'show'])->name('social.profile.show');
        Route::get('/profile/edit', [SocialProfileController::class, 'edit'])->name('social.profile.edit');
        Route::put('/profile', [SocialProfileController::class, 'update'])->name('social.profile.update');

        // Trending & hashtag
        Route::get('/trending', [SocialTrendingController::class, 'index'])->name('social.trending');
        Route::get('/hashtag/{tag}', [SocialTrendingController::class, 'hashtag'])->name('social.hashtag');

        // Search (suggestions HARUS sebelum search utama)
        Route::get('/search/suggestions', [SocialSearchController::class, 'suggestions'])->name('social.search.suggestions');
        Route::get('/search', [SocialSearchController::class, 'index'])->name('social.search');

        // Post CRUD & Interaksi
        Route::post('/posts', [SocialPostController::class, 'store'])->name('social.posts.store');
        Route::get('/posts/{post}/edit', [SocialPostController::class, 'edit'])->name('social.posts.edit');
        Route::put('/posts/{post}', [SocialPostController::class, 'update'])->name('social.posts.update');
        Route::delete('/posts/{post}', [SocialPostController::class, 'destroy'])->name('social.posts.destroy');
        Route::post('/posts/{post}/like', [SocialPostInteractionController::class, 'like'])->name('social.posts.like');
        Route::post('/posts/{post}/repost', [SocialPostInteractionController::class, 'repost'])->name('social.posts.repost');
        Route::get('/posts/{post}/comments', [SocialPostInteractionController::class, 'comments'])->name('social.posts.comments.index');
        Route::post('/posts/{post}/comments', [SocialPostInteractionController::class, 'comment'])->name('social.posts.comments.store');
        Route::delete('/posts/{post}/comments/{comment}', [SocialPostInteractionController::class, 'destroyComment'])->name('social.posts.comments.destroy');

        // Follow
        Route::post('/u/{username}/follow', [SocialFollowController::class, 'toggle'])->name('social.follow.toggle');

        // DM (Direct Message)
        Route::get('/messages', [SocialMessageController::class, 'index'])->name('social.messages.index');
        Route::get('/messages/unread-count', [SocialMessageController::class, 'unreadCount'])->name('social.messages.unread-count');
        Route::get('/messages/{username}', [SocialMessageController::class, 'show'])->name('social.messages.show');
        Route::post('/messages/{username}', [SocialMessageController::class, 'send'])->name('social.messages.send');
        Route::get('/messages/{username}/poll', [SocialMessageController::class, 'poll'])->name('social.messages.poll');

        // Grup Sosial
        Route::get('/groups', [SocialGroupController::class, 'index'])->name('social.groups.index');
        Route::get('/groups/create', [SocialGroupController::class, 'create'])->name('social.groups.create');
        Route::post('/groups', [SocialGroupController::class, 'store'])->name('social.groups.store');
        Route::get('/groups/{group}/edit', [SocialGroupController::class, 'edit'])->name('social.groups.edit');
        Route::put('/groups/{group}', [SocialGroupController::class, 'update'])->name('social.groups.update');
        Route::delete('/groups/{group}', [SocialGroupController::class, 'destroy'])->name('social.groups.destroy');
        
        // Member actions
        Route::post('/groups/{group}/join', [SocialGroupMemberController::class, 'join'])->name('social.groups.join');
        Route::post('/groups/{group}/leave', [SocialGroupMemberController::class, 'leave'])->name('social.groups.leave');
        Route::post('/groups/{group}/approve/{user}', [SocialGroupMemberController::class, 'approve'])->name('social.groups.approve');
        Route::post('/groups/{group}/reject/{user}', [SocialGroupMemberController::class, 'reject'])->name('social.groups.reject');
        Route::post('/groups/{group}/kick/{user}', [SocialGroupMemberController::class, 'kick'])->name('social.groups.kick');
        Route::post('/groups/{group}/promote/{user}', [SocialGroupMemberController::class, 'promote'])->name('social.groups.promote');
        Route::post('/groups/{group}/demote/{user}', [SocialGroupMemberController::class, 'demote'])->name('social.groups.demote');

        // Detail Grup (Harus di bawah create)
        Route::get('/groups/{group}', [SocialGroupController::class, 'show'])->name('social.groups.show');
    });
});

// ================================================================
// DOMAIN: PLAYGROUND (play.diganova.test)
// ================================================================
Route::domain(config('app.play_domain'))->group(function () {

    // -------- Halaman Depan (Guest Landing / Cyber Lobby) --------
    Route::get('/', [PlaygroundController::class, 'index'])->name('play.home');

    // -------- Auth Required (Semua fitur Game wajib login) --------
    Route::middleware('auth')->group(function () {

        // Navo Profile
        Route::get('/profile', [PlaygroundNavoProfileController::class, 'show'])->name('play.profile.show');
        Route::get('/profile/skins', [PlaygroundNavoProfileController::class, 'skins'])->name('play.profile.skins');
        Route::post('/profile/skins', [PlaygroundNavoProfileController::class, 'updateSkin'])->name('play.profile.skins.update');

        // Deck Building
        Route::get('/decks', [PlaygroundDeckController::class, 'index'])->name('play.decks.index');
        Route::get('/decks/create', [PlaygroundDeckController::class, 'create'])->name('play.decks.create');
        Route::post('/decks', [PlaygroundDeckController::class, 'store'])->name('play.decks.store');
        Route::get('/decks/{deck}/edit', [PlaygroundDeckController::class, 'edit'])->name('play.decks.edit');
        Route::put('/decks/{deck}', [PlaygroundDeckController::class, 'update'])->name('play.decks.update');
        Route::post('/decks/{deck}/activate', [PlaygroundDeckController::class, 'activate'])->name('play.decks.activate');
        Route::post('/decks/{deck}/clear', [PlaygroundDeckController::class, 'clear'])->name('play.decks.clear');

        // Battle PvE
        Route::post('/battle/start', [PlaygroundBattleController::class, 'startPve'])->name('play.battle.start');
        Route::get('/battle/pve', function () {
            return app(PlaygroundBattleController::class)->startPve();
        })->name('play.battle.pve');
        Route::get('/battle/{battle}', [PlaygroundBattleController::class, 'show'])->name('play.battle.show');
        Route::post('/battle/{battle}/play', [PlaygroundBattleController::class, 'playCard'])->name('play.battle.play');
        Route::post('/battle/{battle}/skip', [PlaygroundBattleController::class, 'skip'])->name('play.battle.skip');
        Route::post('/battle/{battle}/forfeit', [PlaygroundBattleController::class, 'forfeit'])->name('play.battle.forfeit');

        // Battle PvP (Urutan pvp/* harus sebelum {battle})
        Route::post('/battle/pvp/queue', [PlaygroundMatchmakingController::class, 'queue'])->name('play.battle.pvp.queue.start');
        Route::get('/battle/pvp/waiting', [PlaygroundMatchmakingController::class, 'queuePage'])->name('play.battle.pvp.queue');
        Route::get('/battle/pvp/status', [PlaygroundMatchmakingController::class, 'status'])->name('play.battle.pvp.status');
        Route::post('/battle/pvp/cancel', [PlaygroundMatchmakingController::class, 'cancel'])->name('play.battle.pvp.cancel');
        Route::get('/battle/pvp', function () {
            return redirect()->route('play.battle.pvp.queue.start');
        })->name('play.battle.pvp');
        Route::get('/battle/pvp/{battle}', [PlaygroundPvpBattleController::class, 'show'])->name('play.battle.pvp.show');
        Route::post('/battle/pvp/{battle}/play', [PlaygroundPvpBattleController::class, 'play'])->name('play.battle.pvp.play');
        Route::post('/battle/pvp/{battle}/skip', [PlaygroundPvpBattleController::class, 'skip'])->name('play.battle.pvp.skip');
        Route::get('/battle/pvp/{battle}/state', [PlaygroundPvpBattleController::class, 'state'])->name('play.battle.pvp.state');
        Route::post('/battle/pvp/{battle}/forfeit', [PlaygroundPvpBattleController::class, 'forfeit'])->name('play.battle.pvp.forfeit');

        // Leaderboard & Gacha & Inventory
        Route::get('/leaderboard', [PlaygroundLeaderboardController::class, 'index'])->name('play.leaderboard');
        Route::get('/gacha', [PlaygroundGachaController::class, 'index'])->name('play.gacha.index');
        Route::post('/gacha/pull', [PlaygroundGachaController::class, 'pull'])->name('play.gacha.pull');
        Route::get('/gacha/history', [PlaygroundGachaController::class, 'history'])->name('play.gacha.history');
        Route::get('/inventory', [PlaygroundInventoryController::class, 'index'])->name('play.inventory.index');
        Route::post('/inventory/{userItem}/equip', [PlaygroundInventoryController::class, 'equip'])->name('play.inventory.equip');
        Route::post('/inventory/{userItem}/unequip', [PlaygroundInventoryController::class, 'unequip'])->name('play.inventory.unequip');
    });
});