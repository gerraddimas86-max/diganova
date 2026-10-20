<?php

namespace App\Http\Controllers\Social;

use App\Http\Controllers\Controller;
use App\Models\SocialGroup;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class SocialGroupMemberController extends Controller
{
    /**
     * Join grup (public = direct, private = pending).
     */
    public function join(SocialGroup $group): RedirectResponse
    {
        $me = auth()->user();

        if ($group->isMember($me)) {
            return back()->with('error', 'Kamu sudah menjadi anggota grup ini.');
        }

        if ($group->hasPendingRequest($me)) {
            return back()->with('error', 'Permintaan gabung kamu masih menunggu approval.');
        }

        $status = $group->isPrivate() ? 'pending' : 'approved';

        $group->members()->attach($me->id, [
            'role' => 'member',
            'status' => $status,
            'joined_at' => now(),
        ]);

        if ($status === 'approved') {
            $group->syncMembersCount();
            return back()->with('success', 'Berhasil bergabung dengan grup!');
        }

        return back()->with('success', 'Permintaan bergabung terkirim. Tunggu approval owner.');
    }

    /**
     * Leave grup.
     */
    public function leave(SocialGroup $group): RedirectResponse
    {
        $me = auth()->user();

        if ($group->isOwner($me)) {
            return back()->with('error', 'Owner tidak bisa keluar grup. Hapus grup jika ingin membubarkan.');
        }

        // Hapus dari pivot (member / pending)
        $group->members()->detach($me->id);
        $group->pendingMembers()->detach($me->id);
        $group->syncMembersCount();

        return redirect()
            ->route('social.groups.index')
            ->with('success', 'Kamu telah keluar dari grup.');
    }

    /**
     * Approve pending member (owner/admin only).
     */
    public function approve(SocialGroup $group, User $user): RedirectResponse
    {
        $this->authorizeAdmin($group);

        $pivot = $group->pendingMembers()->where('user_id', $user->id)->first();
        if (!$pivot) {
            return back()->with('error', 'User tidak dalam daftar pending.');
        }

        $group->pendingMembers()->updateExistingPivot($user->id, ['status' => 'approved']);
        $group->syncMembersCount();

        return back()->with('success', 'Member ' . $user->name . ' disetujui.');
    }

    /**
     * Reject pending member.
     */
    public function reject(SocialGroup $group, User $user): RedirectResponse
    {
        $this->authorizeAdmin($group);

        $group->pendingMembers()->detach($user->id);

        return back()->with('success', 'Permintaan ' . $user->name . ' ditolak.');
    }

    /**
     * Kick member (owner/admin only).
     */
    public function kick(SocialGroup $group, User $user): RedirectResponse
    {
        $this->authorizeAdmin($group);

        if ($group->isOwner($user)) {
            return back()->with('error', 'Tidak bisa kick owner grup.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Gunakan tombol Leave untuk keluar sendiri.');
        }

        $group->members()->detach($user->id);
        $group->syncMembersCount();

        return back()->with('success', $user->name . ' dikeluarkan dari grup.');
    }

    /**
     * Promote member jadi admin (owner only).
     */
    public function promote(SocialGroup $group, User $user): RedirectResponse
    {
        if (!$group->isOwner(auth()->user())) {
            abort(403, 'Hanya owner yang bisa promote admin.');
        }

        if (!$group->isMember($user)) {
            return back()->with('error', 'User bukan member grup.');
        }

        $group->members()->updateExistingPivot($user->id, ['role' => 'admin']);

        return back()->with('success', $user->name . ' dipromosikan jadi admin.');
    }

    /**
     * Demote admin jadi member biasa (owner only).
     */
    public function demote(SocialGroup $group, User $user): RedirectResponse
    {
        if (!$group->isOwner(auth()->user())) {
            abort(403);
        }

        $group->members()->updateExistingPivot($user->id, ['role' => 'member']);

        return back()->with('success', $user->name . ' diturunkan jadi member.');
    }

    private function authorizeAdmin(SocialGroup $group): void
    {
        if (!$group->isAdmin(auth()->user())) {
            abort(403, 'Hanya admin grup yang bisa melakukan aksi ini.');
        }
    }
}