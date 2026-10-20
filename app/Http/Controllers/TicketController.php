<?php

namespace App\Http\Controllers;

use App\Models\EventRegistration;
use App\Services\TicketQrService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function __construct(
        protected TicketQrService $qr
    ) {
    }

    /**
     * Tampilkan e-ticket (HTML) untuk di-scan di layar HP.
     */
    public function show(string $orderCode): View
    {
        $registration = $this->resolveRegistration($orderCode);

        abort_if($registration->payment_status !== 'verified', 403, 'E-Ticket hanya tersedia setelah pembayaran terverifikasi.');

        $qrSvg = $this->qr->renderSvg($registration->e_ticket_code, 240);

        return view('event.registrations.ticket', compact('registration', 'qrSvg'));
    }

    /**
     * Download e-ticket sebagai PDF.
     */
    public function download(string $orderCode): Response
    {
        $registration = $this->resolveRegistration($orderCode);

        abort_if($registration->payment_status !== 'verified', 403, 'E-Ticket hanya tersedia setelah pembayaran terverifikasi.');

        $qrDataUri = $this->qr->renderDataUri($registration->e_ticket_code, 260);

        $pdf = Pdf::loadView('event.registrations.ticket-pdf', [
            'registration' => $registration,
            'qrDataUri' => $qrDataUri,
        ])->setPaper('a5', 'portrait');

        $filename = 'DIGANOVA-Ticket-' . $registration->order_code . '.pdf';

        return $pdf->download($filename);
    }

    private function resolveRegistration(string $orderCode): EventRegistration
    {
        return EventRegistration::with(['event', 'ticketCategory', 'user'])
            ->where('order_code', $orderCode)
            ->where('user_id', Auth::id())
            ->firstOrFail();
    }
}