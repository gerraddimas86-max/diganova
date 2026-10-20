<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Ticket {{ $registration->order_code }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background: #f5f5f5;
            padding: 16px;
            color: #1f2937;
        }
        .ticket {
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            max-width: 420px;
            margin: 0 auto;
        }
        .header {
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 50%, #22d3ee 100%);
            color: #fff;
            text-align: center;
            padding: 20px 16px;
        }
        .header h1 { font-size: 22px; font-weight: bold; letter-spacing: 1px; }
        .header p { font-size: 10px; font-style: italic; margin-top: 3px; opacity: 0.9; }
        .header .badge {
            display: inline-block;
            background: rgba(255,255,255,0.25);
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 9px;
            font-weight: bold;
            letter-spacing: 1.5px;
            margin-top: 8px;
        }
        .qr-section { padding: 20px 16px 14px; text-align: center; }
        .qr-section img { width: 200px; height: 200px; }
        .ticket-code {
            font-size: 9px;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 8px;
        }
        .code-value {
            font-family: 'Courier New', monospace;
            font-size: 15px;
            font-weight: bold;
            color: #111827;
            letter-spacing: 1px;
        }
        .hint { font-size: 9px; color: #6b7280; margin-top: 3px; }
        .divider {
            border-top: 1px dashed #d1d5db;
            margin: 0 16px;
        }
        .details { padding: 16px 20px; }
        .details .field { margin-bottom: 10px; }
        .label {
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #9ca3af;
        }
        .value { font-size: 12px; font-weight: 600; color: #111827; margin-top: 2px; }
        .value-small { font-size: 10px; font-weight: 500; color: #111827; margin-top: 2px; }
        .row { display: table; width: 100%; }
        .col { display: table-cell; width: 50%; vertical-align: top; padding-right: 8px; }
        .footer {
            text-align: center;
            padding: 12px;
            background: #f9fafb;
            font-size: 8px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>

<div class="ticket">

    {{-- Header --}}
    <div class="header">
        <h1>DIGANOVA</h1>
        <p>"Plan It. Join It. Enjoy It."</p>
        <div class="badge">E-TICKET</div>
    </div>

    {{-- QR --}}
    <div class="qr-section">
        <img src="{{ $qrDataUri }}" alt="QR Code">
        <p class="ticket-code">Kode E-Ticket</p>
        <p class="code-value">{{ $registration->e_ticket_code }}</p>
        <p class="hint">Tunjukkan QR ini di pintu masuk venue</p>
    </div>

    <div class="divider"></div>

    {{-- Detail --}}
    <div class="details">

        <div class="field">
            <div class="label">Event</div>
            <div class="value">{{ $registration->event->title }}</div>
        </div>

        <div class="row">
            <div class="col">
                <div class="field">
                    <div class="label">Tanggal</div>
                    <div class="value-small">{{ $registration->event->start_date->format('d M Y') }}</div>
                    <div class="hint">{{ $registration->event->start_date->format('H:i') }} WIB</div>
                </div>
            </div>
            <div class="col">
                <div class="field">
                    <div class="label">Kategori Tiket</div>
                    <div class="value-small">{{ $registration->ticketCategory->name }}</div>
                </div>
            </div>
        </div>

        <div class="field">
            <div class="label">Lokasi</div>
            <div class="value-small">{{ $registration->event->location }}</div>
        </div>

        <div class="row">
            <div class="col">
                <div class="field">
                    <div class="label">Atas Nama</div>
                    <div class="value-small">{{ $registration->user->name }}</div>
                </div>
            </div>
            <div class="col">
                <div class="field">
                    <div class="label">Order Code</div>
                    <div class="value-small" style="font-family: monospace;">{{ $registration->order_code }}</div>
                </div>
            </div>
        </div>

    </div>

    {{-- Footer --}}
    <div class="footer">
        &copy; {{ date('Y') }} DIGANOVA. E-Ticket ini sah dan tidak dapat dipindahtangankan.<br>
        Diterbitkan: {{ $registration->payment_verified_at?->format('d M Y H:i') ?? $registration->created_at->format('d M Y H:i') }} WIB
    </div>

</div>

</body>
</html>