<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket #{{ $ticket->id }}</title>
    <style>
        * { 
            margin: 0; 
            padding: 0; 
        }
        
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #050505;
            margin: 0;
            padding: 20px;
            color: #fff;
        }
        
        @page {
            size: A4;
            margin: 0;
        }
        
        .ticket-container {
            width: 100%;
            margin: 0;
            padding: 0;
            background-color: #0B1224;
            border: 1px solid #1a2f4a;
            border-radius: 8px;
            overflow: hidden;
            display: table;
        }
        
        .ticket-banner {
            width: 100%;
            background-size: cover;
            background-position: center;
            background-attachment: scroll;
            padding: 40px 30px;
            text-align: center;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        
        .ticket-banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.6));
            z-index: 1;
        }
        
        .ticket-banner h1, .ticket-banner-label {
            position: relative;
            z-index: 2;
        }
        
        .ticket-banner h1 {
            font-size: 32px;
            font-weight: 900;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .ticket-banner-label {
            font-size: 11px;
            color: #f5a623;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
        }
        
        .ticket-main {
            display: table;
            width: 100%;
            table-layout: fixed;
        }
        
        .ticket-content {
            display: table-cell;
            width: 70%;
            padding: 30px;
            vertical-align: top;
            border-right: 2px dashed #f5a623;
        }
        
        .ticket-stub {
            display: table-cell;
            width: 30%;
            padding: 20px;
            vertical-align: top;
            background-color: #1a2f4a;
            text-align: center;
        }
        
        .ticket-stub-header {
            background-color: #f5a623;
            color: #050505;
            padding: 8px 0;
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
            border-radius: 3px;
        }
        
        .status-badge {
            display: inline-block;
            background-color: rgba(245, 166, 35, 0.2);
            border: 1px solid #f5a623;
            color: #f5a623;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 25px;
            border-collapse: collapse;
        }
        
        .info-grid-row {
            display: table-row;
        }
        
        .info-col {
            display: table-cell;
            padding: 12px 15px;
            border-bottom: 1px solid rgba(245, 166, 35, 0.15);
            vertical-align: top;
        }
        
        .info-col:nth-child(odd) {
            width: 50%;
        }
        
        .info-label {
            font-size: 10px;
            color: #94A3B8;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: bold;
            margin-bottom: 4px;
            display: block;
        }
        
        .info-value {
            font-size: 14px;
            color: #ffffff;
            font-weight: 600;
        }
        
        .ticket-number-section {
            margin: 20px 0;
            padding-top: 15px;
            border-top: 1px solid rgba(245, 166, 35, 0.15);
        }
        
        .ticket-number {
            font-size: 11px;
            color: #94A3B8;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
        
        .ticket-number-value {
            font-size: 18px;
            color: #f5a623;
            font-family: monospace;
            font-weight: bold;
        }
        
        .ticket-footer {
            background-color: rgba(245, 166, 35, 0.1);
            border-top: 1px solid rgba(245, 166, 35, 0.2);
            padding: 15px 30px;
            display: table;
            width: 100%;
            table-layout: fixed;
        }
        
        .footer-col {
            display: table-cell;
            padding: 0 15px;
            vertical-align: top;
        }
        
        .footer-label {
            font-size: 10px;
            color: #94A3B8;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        .footer-value {
            font-size: 13px;
            color: #fff;
            font-weight: 600;
        }
        
        .footer-value-gold {
            color: #f5a623;
        }
        
        .qr-section {
            margin-bottom: 20px;
        }
        
        .qr-label {
            font-size: 10px;
            color: #94A3B8;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: bold;
            margin-bottom: 8px;
            display: block;
        }
        
        .qr-box {
            border: 2px solid rgba(245, 166, 35, 0.3);
            padding: 8px;
            background: #fff;
            display: inline-block;
            border-radius: 4px;
        }
        
        .qr-box img {
            display: block;
            width: 140px;
            height: 140px;
        }
        
        .stub-ticket-id {
            font-size: 10px;
            color: #94A3B8;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 5px;
        }
        
        .stub-ticket-id-value {
            font-size: 12px;
            color: #f5a623;
            font-family: monospace;
            font-weight: bold;
            word-break: break-all;
        }
        
        .stub-rules {
            border-top: 1px solid rgba(245, 166, 35, 0.2);
            margin-top: 15px;
            padding-top: 10px;
            font-size: 9px;
            color: #94A3B8;
            line-height: 1.4;
        }
        
        .stub-rules-title {
            color: #fff;
            font-weight: bold;
            margin-bottom: 3px;
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <!-- Banner Section with Cinematic Image -->
        <div class="ticket-banner" style="background-image: url('{{ $ticket->event->getCinematicImageUrl() }}');">
            <span class="ticket-banner-label">Event Ticket</span>
            <h1>{{ $ticket->event->title }}</h1>
        </div>

        <!-- Main Content: Split Layout -->
        <div class="ticket-main">
            <!-- Left Side: Ticket Details (70%) -->
            <div class="ticket-content">
                <!-- Status Badge -->
                <div class="status-badge">
                    @if($ticket->is_used)
                        ✓ USED
                    @elseif($ticket->is_verified)
                        ✓ VERIFIED
                    @else
                        ● VALID
                    @endif
                </div>

                <!-- Event Information Grid -->
                <div class="info-grid">
                    <!-- Row 1 -->
                    <div class="info-grid-row">
                        <div class="info-col">
                            <span class="info-label">Date & Time</span>
                            <div class="info-value">{{ \Carbon\Carbon::parse($ticket->event->date)->format('M d, Y') }}</div>
                            <div style="font-size: 12px; color: #94A3B8; margin-top: 2px;">{{ \Carbon\Carbon::parse($ticket->event->date)->format('g:i A') }}</div>
                        </div>
                        <div class="info-col">
                            <span class="info-label">Location</span>
                            <div class="info-value">{{ $ticket->event->location ?? 'TBA' }}</div>
                        </div>
                    </div>

                    <!-- Row 2 -->
                    <div class="info-grid-row">
                        <div class="info-col">
                            <span class="info-label">Seat Position</span>
                            <div class="info-value">{{ $ticket->seat_number ?? 'General Entry' }}</div>
                        </div>
                        <div class="info-col">
                            <span class="info-label">Ticket Type</span>
                            <div class="info-value">{{ $ticket->ticket_type ?? 'Standard' }}</div>
                        </div>
                    </div>

                    <!-- Row 3 -->
                    <div class="info-grid-row">
                        <div class="info-col">
                            <span class="info-label">Holder Name</span>
                            <div class="info-value">{{ $buyer->name ?? 'Guest' }}</div>
                        </div>
                        <div class="info-col">
                            <span class="info-label">Purchased</span>
                            <div class="info-value">{{ $ticket->created_at->format('M d, Y') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Ticket Number -->
                <div class="ticket-number-section">
                    <span class="ticket-number">Ticket Number</span>
                    <div class="ticket-number-value">{{ $ticket->ticket_number ?? '#' . str_pad($ticket->id, 8, '0', STR_PAD_LEFT) }}</div>
                </div>
            </div>

            <!-- Right Side: Stub (30%) -->
            <div class="ticket-stub">
                <div class="ticket-stub-header">ADMIT ONE</div>

                <!-- Status Icon -->
                <div style="font-size: 10px; color: #f5a623; text-transform: uppercase; font-weight: bold; margin-bottom: 10px;">
                    @if($ticket->is_used)
                        <div style="font-size: 24px; color: #ef4444;">✓</div>
                        Used
                    @elseif($ticket->is_verified)
                        <div style="font-size: 24px; color: #22c55e;">✓</div>
                        Verified
                    @else
                        <div style="font-size: 24px; color: #f5a623;">●</div>
                        Valid
                    @endif
                </div>

                <!-- QR Code -->
                <div class="qr-section">
                    <span class="qr-label">QR CODE</span>
                    <div class="qr-box">
                        @php
                            $qrPath = public_path('storage/qrcodes/' . $ticket->qr_code);
                        @endphp
                        @if($ticket->qr_code && file_exists($qrPath))
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents($qrPath)) }}" alt="QR Code" />
                        @else
                            <div style="width: 140px; height: 140px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #999; font-size: 9px;">QR CODE</div>
                        @endif
                    </div>
                </div>

                <!-- Ticket ID -->
                <div class="stub-ticket-id">Ticket ID</div>
                <div class="stub-ticket-id-value">{{ $ticket->ticket_number ?? 'TK' . str_pad($ticket->id, 8, '0', STR_PAD_LEFT) }}</div>

                <!-- Rules -->
                <div class="stub-rules">
                    <div class="stub-rules-title">Important</div>
                    Present at entry
                    <br>
                    Non-transferable
                    <br>
                    king-ticket.com
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="ticket-footer">
            <div class="footer-col" style="width: 50%;">
                <div class="footer-label">Event Organizer</div>
                <div class="footer-value">{{ $ticket->event->organizer->name ?? 'King Ticket' }}</div>
            </div>
            <div class="footer-col" style="width: 50%; text-align: right;">
                <div class="footer-label">Powered by</div>
                <div class="footer-value footer-value-gold">KING TICKET</div>
            </div>
        </div>
    </div>
</body>
</html>
