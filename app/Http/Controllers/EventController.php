<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * Halaman Depan Event Domain (Membedakan Guest & Auth)
     */
    public function home(): View
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            $activeEvents = Event::where('status', 'published')->count();
            $myRegistrationsCount = $user->eventRegistrations()->count();
            
            // Ambil 6 event yang akan datang
            $featuredEvents = Event::with(['ticketCategories'])
                ->where('status', 'published')
                ->orderBy('start_date', 'asc')
                ->take(6)
                ->get();

            $categories = [
                ['slug' => 'music', 'name' => 'Musik', 'icon' => '🎵', 'color' => 'from-pink-500 to-rose-500'],
                ['slug' => 'cosplay', 'name' => 'Cosplay', 'icon' => '🎭', 'color' => 'from-purple-500 to-violet-500'],
                ['slug' => 'art', 'name' => 'Seni', 'icon' => '🎨', 'color' => 'from-blue-500 to-cyan-500'],
                ['slug' => 'game_tournament', 'name' => 'Turnamen', 'icon' => '🎮', 'color' => 'from-emerald-500 to-teal-500'],
                ['slug' => 'festival', 'name' => 'Festival', 'icon' => '🎉', 'color' => 'from-amber-500 to-orange-500'],
            ];

            return view('event.home', compact('featuredEvents', 'myRegistrationsCount', 'activeEvents', 'categories'));
        }

        // Jika Guest
        return view('event.guest-landing');
    }

    /**
     * List event publik (Hanya bisa diakses user login)
     */
    public function index(Request $request): View
    {
        $query = Event::with(['ticketCategories', 'creator'])
            ->where('status', 'published')
            ->orderBy('start_date', 'asc');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $events = $query->paginate(9)->withQueryString();

        return view('event.events.index', compact('events'));
    }

    /**
     * Detail event berdasarkan slug
     */
    public function show(string $slug): View
    {
        $event = Event::with([
            'creator',
            'schedules' => fn($q) => $q->orderBy('start_time'),
            'ticketCategories' => fn($q) => $q->where('is_active', true),
            'sponsors' => fn($q) => $q->orderBy('sort_order'),
            'venueMaps',
            'group',
        ])
            ->where('slug', $slug)
            ->whereIn('status', ['published', 'ongoing', 'completed'])
            ->firstOrFail();

        $myRegistrations = collect();
        if (auth()->check()) {
            $myRegistrations = $event->registrations()
                ->where('user_id', auth()->id())
                ->get();
        }

        return view('event.events.show', compact('event', 'myRegistrations'));
    }
}