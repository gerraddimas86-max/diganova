<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RejectRegistrationRequest;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function index(Request $request): View
    {
        $query = EventRegistration::with(['event', 'user', 'ticketCategory', 'verifier'])
            ->latest();

        // Filter status
        if ($request->filled('status') && in_array($request->status, ['pending', 'verified', 'rejected'])) {
            $query->where('payment_status', $request->status);
        }

        // Filter event
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                    ->orWhere('e_ticket_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('username', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $registrations = $query->paginate(20)->withQueryString();

        $events = Event::orderBy('title')->get(['id', 'title']);

        $stats = [
            'pending' => EventRegistration::where('payment_status', 'pending')->count(),
            'verified_today' => EventRegistration::where('payment_status', 'verified')
                ->whereDate('payment_verified_at', today())
                ->count(),
            'rejected_today' => EventRegistration::where('payment_status', 'rejected')
                ->whereDate('payment_verified_at', today())
                ->count(),
            'total' => EventRegistration::count(),
        ];

        return view('admin.registrations.index', compact('registrations', 'events', 'stats'));
    }

    public function show(EventRegistration $registration): View
    {
        $registration->load(['event', 'user', 'ticketCategory', 'verifier', 'checkinLogs']);

        return view('admin.registrations.show', compact('registration'));
    }

    public function approve(EventRegistration $registration): RedirectResponse
    {
        if ($registration->payment_status !== 'pending') {
            return back()->with('error', 'Registrasi ini sudah diproses sebelumnya.');
        }

        $registration->markVerified(Auth::id());

        return redirect()
            ->route('admin.registrations.index', ['status' => 'pending'])
            ->with('success', 'Registrasi ' . $registration->order_code . ' berhasil diverifikasi. Grup event & achievement telah diaktifkan otomatis.');
    }

    public function reject(RejectRegistrationRequest $request, EventRegistration $registration): RedirectResponse
    {
        if ($registration->payment_status !== 'pending') {
            return back()->with('error', 'Registrasi ini sudah diproses sebelumnya.');
        }

        $registration->markRejected(Auth::id(), $request->notes);

        return redirect()
            ->route('admin.registrations.index', ['status' => 'pending'])
            ->with('success', 'Registrasi ' . $registration->order_code . ' telah ditolak. Kuota tiket dikembalikan.');
    }
}