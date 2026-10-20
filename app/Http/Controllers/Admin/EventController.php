<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Models\Event;
use App\Models\EventGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $query = Event::with('creator')->latest();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $events = $query->paginate(10)->withQueryString();

        return view('admin.events.index', compact('events'));
    }

    public function create(): View
    {
        return view('admin.events.create');
    }

    public function store(StoreEventRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Auto-generate slug unik
        $data['slug'] = $this->generateUniqueSlug($data['title']);
        $data['created_by'] = Auth::id();

        // Handle banner upload
        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')->store('events/banners', 'public');
        }

        $event = Event::create($data);

        // Auto-create event group
        EventGroup::create([
            'event_id' => $event->id,
            'name' => $event->title . ' Community',
            'description' => 'Grup resmi peserta ' . $event->title,
        ]);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event "' . $event->title . '" berhasil dibuat!');
    }

    public function show(Event $event): View
    {
        $event->load(['creator', 'schedules', 'ticketCategories', 'group']);
        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event): View
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(StoreEventRequest $request, Event $event): RedirectResponse
    {
        $data = $request->validated();

        // Regenerate slug kalau title berubah
        if ($data['title'] !== $event->title) {
            $data['slug'] = $this->generateUniqueSlug($data['title'], $event->id);
        }

        // Handle banner baru
        if ($request->hasFile('banner_image')) {
            if ($event->banner_image) {
                Storage::disk('public')->delete($event->banner_image);
            }
            $data['banner_image'] = $request->file('banner_image')->store('events/banners', 'public');
        }

        $event->update($data);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event "' . $event->title . '" berhasil diupdate!');
    }

    public function destroy(Event $event): RedirectResponse
    {
        if ($event->banner_image) {
            Storage::disk('public')->delete($event->banner_image);
        }

        $title = $event->title;
        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event "' . $title . '" berhasil dihapus.');
    }

    private function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $counter = 1;

        while (Event::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $original . '-' . $counter++;
        }

        return $slug;
    }
}