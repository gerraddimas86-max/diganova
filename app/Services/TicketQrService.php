<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketQrService
{
    /**
     * Generate QR sebagai SVG string (bisa dirender di HTML & PDF).
     */
    public function renderSvg(string $code, int $size = 220): string
    {
        return (string) QrCode::format('svg')
            ->size($size)
            ->errorCorrection('H')
            ->margin(1)
            ->generate($code);
    }

    /**
     * Generate QR sebagai data URI (base64) untuk embed di HTML/PDF.
     * Cocok kalau PDF engine tidak support SVG inline.
     */
    public function renderDataUri(string $code, int $size = 220): string
    {
        $svg = $this->renderSvg($code, $size);

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}