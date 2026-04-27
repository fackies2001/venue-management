<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6fb;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .08);
        }

        .header {
            background: #1a3c72;
            padding: 30px;
            text-align: center;
        }

        .header h2 {
            color: #fff;
            margin: 0;
            font-size: 1.4rem;
        }

        .body {
            padding: 30px;
        }

        .status-badge {
            display: inline-block;
            background: #f8d7da;
            color: #842029;
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .detail-row {
            display: flex;
            padding: 8px 0;
            border-bottom: 1px solid #f1f3f5;
            font-size: .9rem;
        }

        .detail-label {
            width: 40%;
            color: #6c757d;
            font-weight: 600;
        }

        .footer {
            background: #f8f9fa;
            padding: 16px 30px;
            text-align: center;
            font-size: .8rem;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>🏢 Venue Management System</h2>
            <p style="color:rgba(255,255,255,.8); margin:6px 0 0;">Office of Civil Defense</p>
        </div>
        <div class="body">
            <p>Dear <strong>{{ $booking->user->name }}</strong>,</p>
            <p>We regret to inform you that your venue booking has been <strong>rejected</strong>.</p>

            <span class="status-badge">❌ REJECTED</span>

            <div class="detail-row">
                <div class="detail-label">Event Title</div>
                <div>{{ $booking->event_title }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Venue</div>
                <div>{{ $booking->venue->name }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Event Date</div>
                <div>{{ $booking->event_date->format('F d, Y') }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Time</div>
                <div>{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} –
                    {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</div>
            </div>
            @if ($booking->admin_remarks)
                <div class="detail-row">
                    <div class="detail-label">Reason</div>
                    <div>{{ $booking->admin_remarks }}</div>
                </div>
            @endif

            <p style="margin-top:24px;">You may submit a new booking request. If you have any concerns, please contact
                the admin.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Office of Civil Defense – Venue Management System
        </div>
    </div>
</body>

</html>
