<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CheckinLog;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CheckinController extends Controller
{
    /**
     * List semua event yang bisa di-check-in.
     */
    public function index(): View
    {
        $events = Event::withCount([
            'registrations as verified_count' => fn($q) => $q->where('payment_status', 'verified'),
            'registrations as checked_in_count' => fn($q) => $q->where('checked_in', true),
        ])
            ->orderByDesc('start_date')
            ->get();

        return view('admin.checkin.index', compact('events'));
    }

    /**
     * Halaman scanner untuk event tertentu.
     */
    public function scanner(Event $event): View
    {
        $stats = [
            'total_verified' => $event->registrations()->where('payment_status', 'verified')->count(),
            'checked_in' => $event->registrations()->where('checked_in', true)->count(),
        ];

        $recentCheckins = $event->registrations()
            ->where('checked_in', true)
            ->with(['user', 'ticketCategory'])
            ->latest('checked_in_at')
            ->take(5)
            ->get()
            ->map(fn($r) => [
                'name' => $r->user->name,
                'username' => '@' . $r->user->username,
                'ticket' => $r->ticketCategory->name,
                'avatar_initial' => strtoupper(substr($r->user->name, 0, 1)),
                'checked_in_at' => $r->checked_in_at->format('H:i'),
            ])
            ->toArray();

        return view('admin.checkin.scanner', compact('event', 'stats', 'recentCheckins'));
    }

    /**
     * Proses scan QR / input manual (dipanggil via AJAX).
     */
    public function process(Request $request, Event $event): JsonResponse
    {
        $validated = $request->validate([
            'e_ticket_code' => ['required', 'string', 'max:50'],
            'method' => ['nullable', 'in:qr,manual'],
        ]);

        $code = strtoupper(trim($validated['e_ticket_code']));
        $method = $validated['method'] ?? 'qr';

        $registration = EventRegistration::with(['user', 'ticketCategory'])
            ->where('e_ticket_code', $code)
            ->where('event_id', $event->id)
            ->first();

        // 1. E-Ticket tidak ditemukan untuk event ini
        if (!$registration) {
            return response()->json([
                'status' => 'error',
                'code' => 'NOT_FOUND',
                'message' => 'E-Ticket tidak ditemukan untuk event ini.',
            ], 404);
        }

        // 2. Belum verified
        if ($registration->payment_status !== 'verified') {
            return response()->json([
                'status' => 'error',
                'code' => 'NOT_VERIFIED',
                'message' => 'Pembayaran belum diverifikasi (status: ' . $registration->payment_status . ').',
                'registration' => $this->serialize($registration),
            ], 400);
        }

        // 3. Sudah check-in (anti duplikat)
        if ($registration->checked_in) {
            return response()->json([
                'status' => 'error',
                'code' => 'ALREADY_CHECKED_IN',
                'message' => 'Peserta sudah check-in pada ' . $registration->checked_in_at->format('d M Y H:i'),
                'registration' => $this->serialize($registration),
            ], 409);
        }

        // 4. Berhasil — mark + log
        $registration->markCheckedIn();

        CheckinLog::create([
            'event_registration_id' => $registration->id,
            'user_id' => Auth::id(),
            'method' => $method,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Check-in berhasil!',
            'registration' => $this->serialize($registration->fresh()->load('user', 'ticketCategory')),
        ]);
    }

    /**
     * Format data registrasi untuk response JSON.
     */
    private function serialize(EventRegistration $r): array
    {
        return [
            'name' => $r->user->name,
            'username' => '@' . $r->user->username,
            'ticket' => $r->ticketCategory->name,
            'avatar_initial' => strtoupper(substr($r->user->name, 0, 1)),
            'checked_in_at' => $r->checked_in_at?->format('H:i'),
        ];
    }
}