<?php

namespace App\Http\Controllers\Social;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSocialGroupRequest;
use App\Models\SocialGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SocialGroupController extends Controller
{
    public function index(Request $request): View
    {
        $query = SocialGroup::with('owner')->withCount('members');

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($request->filled('visibility') && in_array($request->visibility, ['public', 'private'])) {
            $query->where('visibility', $request->visibility);
        }

        $groups = $query->orderByDesc('members_count')
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        $myGroupIds = auth()->check()
            ? auth()->user()->socialGroups()->pluck('social_groups.id')->toArray()
            : [];

        return view('social.groups.index', compact('groups', 'myGroupIds'));
    }

    public function create(): View
    {
        return view('social.groups.create');
    }

    public function store(StoreSocialGroupRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->ensureUniqueSlug($data['slug'] ?? Str::slug($data['name']));
        $data['owner_id'] = auth()->id();
        $data['members_count'] = 1;

        $group = SocialGroup::create($data);

        // Owner otomatis jadi admin & member approved
        $group->members()->attach(auth()->id(), [
            'role' => 'admin',
            'status' => 'approved',
            'joined_at' => now(),
        ]);

        return redirect()
            ->route('social.groups.show', $group->slug)
            ->with('success', 'Grup "' . $group->name . '" berhasil dibuat!');
    }

    public function show(SocialGroup $group): View
    {
        $group->load('owner');

        $isMember = auth()->check() && $group->isMember(auth()->user());
        $isAdmin = auth()->check() && $group->isAdmin(auth()->user());
        $hasPending = auth()->check() && $group->hasPendingRequest(auth()->user());

        $members = $group->members()
            ->orderByRaw("FIELD(social_group_members.role, 'admin', 'member')")
            ->get();

        $pendingMembers = $isAdmin
            ? $group->pendingMembers()->get()
            : collect();

        return view('social.groups.show', compact(
            'group',
            'isMember',
            'isAdmin',
            'hasPending',
            'members',
            'pendingMembers'
        ));
    }

    public function edit(SocialGroup $group): View
    {
        abort_if($group->owner_id !== auth()->id(), 403, 'Hanya owner yang bisa mengedit grup.');

        return view('social.groups.edit', compact('group'));
    }

    public function update(StoreSocialGroupRequest $request, SocialGroup $group): RedirectResponse
    {
        abort_if($group->owner_id !== auth()->id(), 403);

        $data = $request->validated();

        if (!empty($data['slug']) && $data['slug'] !== $group->slug) {
            $data['slug'] = $this->ensureUniqueSlug($data['slug'], $group->id);
        } else {
            unset($data['slug']);
        }

        $group->update($data);

        return redirect()
            ->route('social.groups.show', $group->slug)
            ->with('success', 'Grup berhasil diupdate.');
    }

    public function destroy(SocialGroup $group): RedirectResponse
    {
        abort_if($group->owner_id !== auth()->id(), 403);

        $name = $group->name;
        $group->delete();

        return redirect()
            ->route('social.groups.index')
            ->with('success', 'Grup "' . $name . '" berhasil dihapus.');
    }

    private function ensureUniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $original = $slug;
        $counter = 1;

        while (SocialGroup::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $original . '-' . $counter++;
        }

        return $slug;
    }
}