<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket {{ $registration->order_code }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background: #EEF0F8;
            padding: 24px 20px;
            color: #090E39;
            min-height: 100vh;
        }

        /* ============================
           TICKET CONTAINER (neo-brutalist)
           ============================ */
        .ticket {
            background: #ffffff;
            border-radius: 24px;
            border: 2px solid #090E39;
            max-width: 420px;
            margin: 0 auto;
            box-shadow:
                8px 8px 0 #4382DF,
                20px 24px 40px rgba(9, 14, 57, .18);
            overflow: visible;
            position: relative;
        }

        /* ============================
           HEADER (brand)
           ============================ */
        .header {
            background: #4647AE;
            background: linear-gradient(135deg, #4647AE 0%, #4382DF 50%, #112E81 100%);
            color: #fff;
            text-align: center;
            padding: 26px 20px 24px;
            border-radius: 22px 22px 0 0;
            position: relative;
        }

        .header .brand {
            font-size: 28px;
            font-weight: 900;
            letter-spacing: 2.5px;
            line-height: 1;
            text-shadow: 2px 2px 0 rgba(9, 14, 57, .4);
        }

        .header .tagline {
            font-size: 10px;
            font-style: italic;
            margin-top: 6px;
            opacity: .88;
            letter-spacing: .5px;
        }

        .header .badge {
            display: inline-block;
            background: #fff;
            color: #090E39;
            padding: 5px 14px;
            border-radius: 999px;
            font-size: 9px;
            font-weight: 900;
            letter-spacing: 2px;
            margin-top: 14px;
            border: 2px solid #090E39;
            box-shadow: 2px 2px 0 #090E39;
        }

        /* ============================
           QR SECTION
           ============================ */
        .qr-section {
            padding: 26px 16px 20px;
            text-align: center;
            background: #fff;
        }

        .qr-frame {
            display: inline-block;
            padding: 10px;
            background: #fff;
            border: 3px solid #090E39;
            border-radius: 16px;
            box-shadow: 4px 4px 0 #4382DF;
        }

        .qr-section img {
            width: 200px;
            height: 200px;
            display: block;
        }

        .ticket-code {
            font-size: 9px;
            color: #6B7196;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 16px;
            font-weight: 800;
        }

        .code-value {
            font-family: 'Courier New', monospace;
            font-size: 20px;
            font-weight: 900;
            color: #090E39;
            letter-spacing: 2px;
            margin-top: 5px;
        }

        .hint {
            font-size: 10px;
            color: #6B7196;
            margin-top: 6px;
            font-weight: 600;
        }

        /* ============================
           TICKET NOTCH DIVIDER
           ============================ */
        .divider-wrap {
            position: relative;
            height: 28px;
            background: #fff;
        }

        .divider {
            position: absolute;
            top: 50%;
            left: 16px;
            right: 16px;
            border-top: 2px dashed #090E39;
            opacity: .3;
            transform: translateY(-1px);
        }

        .notch-left,
        .notch-right {
            position: absolute;
            top: 50%;
            width: 28px;
            height: 28px;
            background: #EEF0F8;
            border-radius: 50%;
            transform: translateY(-50%);
            z-index: 2;
        }

        .notch-left { left: -16px; }
        .notch-right { right: -16px; }

        /* ============================
           DETAILS
           ============================ */
        .details {
            padding: 20px 22px 22px;
            background: #fff;
            border-radius: 0 0 22px 22px;
        }

        .field {
            padding: 11px 0;
            border-bottom: 1px dashed rgba(9, 14, 57, .15);
        }

        .field:first-child { padding-top: 0; }
        .field:last-child { border-bottom: 0; padding-bottom: 0; }

        .label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #6B7196;
            font-weight: 800;
            line-height: 1;
        }

        .label svg {
            width: 11px;
            height: 11px;
            vertical-align: -2px;
            margin-right: 4px;
            stroke: #4382DF;
            stroke-width: 2.5;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .value {
            font-size: 13px;
            font-weight: 800;
            color: #090E39;
            margin-top: 5px;
            line-height: 1.4;
            word-break: break-word;
        }

        .value-mono {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            letter-spacing: .5px;
        }

        .row {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 12px;
        }

        .col:last-child {
            padding-right: 0;
            padding-left: 12px;
        }

        /* ============================
           FOOTER
           ============================ */
        .footer {
            text-align: center;
            padding: 14px 20px;
            background: #090E39;
            font-size: 9px;
            color: rgba(255, 255, 255, .68);
            line-height: 1.7;
            font-weight: 500;
            border-radius: 0 0 22px 22px;
            border-top: 2px solid #090E39;
        }

        .footer strong {
            color: #fff;
            font-weight: 900;
            letter-spacing: .5px;
        }

        /* ============================
           RESPONSIVE
           ============================ */
        @media (max-width: 480px) {
            body { padding: 16px 12px; }
            .qr-section img { width: 180px; height: 180px; }
            .header .brand { font-size: 24px; }
            .code-value { font-size: 18px; }
            .ticket {
                box-shadow:
                    5px 5px 0 #4382DF,
                    12px 16px 24px rgba(9, 14, 57, .18);
            }
        }

        /* ============================
           PRINT / PDF FRIENDLY
           ============================ */
        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .ticket {
                box-shadow: none;
                border: 2px solid #090E39;
            }
        }
    </style>
</head>
<body>

<div class="ticket">

    {{-- ============================
         HEADER
         ============================ --}}
    <div class="header">
        <div class="brand">DIGANOVA</div>
        <div class="tagline">"Plan It. Join It. Enjoy It."</div>
        <div class="badge">E-TICKET · ADMIT ONE</div>
    </div>

    {{-- ============================
         QR SECTION
         ============================ --}}
    <div class="qr-section">
        <div class="qr-frame">
            <img src="{{ $qrDataUri }}" alt="QR Code {{ $registration->e_ticket_code }}">
        </div>
        <p class="ticket-code">Kode E-Ticket</p>
        <p class="code-value">{{ $registration->e_ticket_code }}</p>
        <p class="hint">Tunjukkan QR ini di pintu masuk venue</p>
    </div>

    {{-- ============================
         TICKET NOTCH DIVIDER
         ============================ --}}
    <div class="divider-wrap">
        <div class="divider"></div>
        <div class="notch-left"></div>
        <div class="notch-right"></div>
    </div>

    {{-- ============================
         DETAILS
         ============================ --}}
    <div class="details">

        {{-- Event --}}
        <div class="field">
            <div class="label">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><path d="M13 5v2M13 17v2M13 11v2"/></svg>
                Event
            </div>
            <div class="value">{{ $registration->event->title }}</div>
        </div>

        {{-- Tanggal + Kategori --}}
        <div class="row">
            <div class="col">
                <div class="field">
                    <div class="label">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                        Tanggal
                    </div>
                    <div class="value">{{ $registration->event->start_date->format('d M Y') }}</div>
                    <div class="hint" style="margin-top: 3px;">{{ $registration->event->start_date->format('H:i') }} WIB</div>
                </div>
            </div>
            <div class="col">
                <div class="field">
                    <div class="label">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"/><circle cx="7.5" cy="7.5" r=".5" fill="currentColor"/></svg>
                        Kategori
                    </div>
                    <div class="value">{{ $registration->ticketCategory->name }}</div>
                </div>
            </div>
        </div>

        {{-- Lokasi --}}
        <div class="field">
            <div class="label">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg>
                Lokasi
            </div>
            <div class="value">{{ $registration->event->location }}</div>
        </div>

        {{-- Atas Nama + Order Code --}}
        <div class="row">
            <div class="col">
                <div class="field">
                    <div class="label">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Atas Nama
                    </div>
                    <div class="value">{{ $registration->user->name }}</div>
                </div>
            </div>
            <div class="col">
                <div class="field">
                    <div class="label">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 9h16M4 15h16M10 3 8 21M16 3l-2 18"/></svg>
                        Order Code
                    </div>
                    <div class="value value-mono">{{ $registration->order_code }}</div>
                </div>
            </div>
        </div>

    </div>

    {{-- ============================
         FOOTER
         ============================ --}}
    <div class="footer">
        <strong>© {{ date('Y') }} DIGANOVA</strong> · E-Ticket ini sah dan tidak dapat dipindahtangankan.<br>
        Diterbitkan: {{ $registration->payment_verified_at?->format('d M Y H:i') ?? $registration->created_at->format('d M Y H:i') }} WIB
    </div>

</div>

</body>
</html>