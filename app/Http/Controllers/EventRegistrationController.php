<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRegistrationRequest;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\TicketCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EventRegistrationController extends Controller
{
    public function create(string $slug): View|RedirectResponse
    {
        $event = Event::with(['ticketCategories' => fn($q) => $q->where('is_active', true)])
            ->where('slug', $slug)
            ->firstOrFail();

        // Cek status event
        if (!$event->isRegistrationOpen()) {
            return redirect()
                ->route('event.events.show', $slug)
                ->with('error', 'Pendaftaran event ini sudah ditutup.');
        }

        // Cek batas 3x daftar
        $myRegistrationsCount = $event->registrations()
            ->where('user_id', Auth::id())
            ->count();

        if ($myRegistrationsCount >= 3) {
            return redirect()
                ->route('event.events.show', $slug)
                ->with('error', 'Kamu sudah mendaftar 3x untuk event ini. Maksimal 3x per event.');
        }

        // Cek tiket tersedia
        if ($event->availableTickets()->isEmpty()) {
            return redirect()
                ->route('event.events.show', $slug)
                ->with('error', 'Semua tiket untuk event ini sudah habis.');
        }

        return view('event.registrations.create', compact('event', 'myRegistrationsCount'));
    }

    public function store(StoreEventRegistrationRequest $request, string $slug): RedirectResponse
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        // ===== VALIDASI ULANG (server-side) =====
        if (!$event->isRegistrationOpen()) {
            return redirect()->route('event.events.show', $slug)
                ->with('error', 'Pendaftaran sudah ditutup.');
        }

        $count = $event->registrations()->where('user_id', Auth::id())->count();
        if ($count >= 3) {
            return redirect()->route('event.events.show', $slug)
                ->with('error', 'Kamu sudah mendaftar 3x. Maksimal 3x per event.');
        }

        $ticket = TicketCategory::where('id', $request->ticket_category_id)
            ->where('event_id', $event->id)
            ->where('is_active', true)
            ->firstOrFail();

        if ($ticket->sold_count >= $ticket->quota) {
            return back()
                ->withErrors(['ticket_category_id' => 'Tiket yang kamu pilih sudah habis.'])
                ->withInput();
        }

        $isFree = $ticket->price == 0;

        // ===== VALIDASI UPLOAD =====
        if (!$isFree && !$request->hasFile('payment_proof')) {
            return back()
                ->withErrors(['payment_proof' => 'Bukti pembayaran wajib diupload untuk tiket berbayar.'])
                ->withInput();
        }

        // ===== SIMPAN FILE =====
        $proofPath = null;
        if ($request->hasFile('payment_proof')) {
            $proofPath = $request->file('payment_proof')->store('payments/proofs', 'public');
        }

        // ===== BUAT REGISTRASI =====
        $registration = EventRegistration::create([
            'event_id' => $event->id,
            'ticket_category_id' => $ticket->id,
            'user_id' => Auth::id(),
            'order_code' => $this->generateOrderCode(),
            'payment_proof_path' => $proofPath,
            'payment_status' => $isFree ? 'verified' : 'pending',
            'payment_verified_at' => $isFree ? now() : null,
            'e_ticket_code' => strtoupper(Str::random(16)),
        ]);

        // Reserve kuota (slot langsung dikunci)
        $ticket->increment('sold_count');

        // Auto-process kalau tiket gratis
        if ($isFree) {
            $registration->markVerified();
        }

        $message = $isFree
            ? 'Pendaftaran berhasil! Tiket gratis kamu sudah aktif. 🎉'
            : 'Pendaftaran berhasil! Tunggu verifikasi pembayaran dari admin.';

        return redirect()
            ->route('event.registrations.show', $registration->order_code)
            ->with('success', $message);
    }

    public function index(): View
    {
        $registrations = EventRegistration::with(['event', 'ticketCategory'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('event.registrations.index', compact('registrations'));
    }

    public function show(string $orderCode): View
    {
        $registration = EventRegistration::with(['event', 'ticketCategory', 'verifier'])
            ->where('order_code', $orderCode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('event.registrations.show', compact('registration'));
    }

    private function generateOrderCode(): string
    {
        do {
            $code = 'DGN-' . date('Y') . '-' . strtoupper(Str::random(6));
        } while (EventRegistration::where('order_code', $code)->exists());

        return $code;
    }
}