<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6fb;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .05);
            border-top: 5px solid #e87722;
        }

        .header {
            background: #1a3c72;
            padding: 35px 20px;
            text-align: center;
        }

        .header h2 {
            color: #ffffff;
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .header p {
            color: rgba(255, 255, 255, 0.85);
            margin: 5px 0 0;
            font-size: 0.9rem;
        }

        .body {
            padding: 35px 40px;
        }

        .greeting {
            font-size: 1.05rem;
            margin-bottom: 15px;
        }

        .message {
            font-size: 0.95rem;
            line-height: 1.6;
            color: #555;
            margin-bottom: 25px;
        }

        .status-badge {
            display: inline-block;
            background: #f8d7da;
            color: #842029;
            padding: 8px 18px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 25px;
            border: 1px solid #f5c2c7;
            letter-spacing: 0.5px;
        }

        .details-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #e9ecef;
        }

        .detail-row {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
            font-size: 0.9rem;
        }

        .detail-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .detail-row:first-child {
            padding-top: 0;
        }

        .detail-label {
            width: 40%;
            color: #6c757d;
            font-weight: 600;
        }

        .detail-value {
            width: 60%;
            color: #212529;
            font-weight: 500;
        }

        .footer-message {
            margin-top: 30px;
            font-size: 0.9rem;
            line-height: 1.5;
            color: #6c757d;
        }

        .footer {
            background: #f1f3f5;
            padding: 20px;
            text-align: center;
            font-size: 0.8rem;
            color: #6c757d;
            border-top: 1px solid #e9ecef;
        }

        @media only screen and (max-width: 600px) {
            .body {
                padding: 25px 20px;
            }

            .detail-row {
                flex-direction: column;
            }

            .detail-label,
            .detail-value {
                width: 100%;
            }

            .detail-value {
                margin-top: 4px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Venue Management System</h2>
            <p>Office of Civil Defense</p>
        </div>

        <div class="body">
            <div class="greeting">Dear <strong>{{ $booking->user->name }}</strong>,</div>

            <div class="message">
                Thank you for submitting your request through the Venue Management System. We regret to inform you that
                your venue booking request could not be approved at this time.
            </div>

            <div style="text-align: center;">
                <span class="status-badge">STATUS: DECLINED</span>
            </div>

            <div class="details-card">
                <div class="detail-row">
                    <div class="detail-label">Event Title</div>
                    <div class="detail-value">{{ $booking->event_title }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Venue</div>
                    <div class="detail-value">{{ $booking->venue->name }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Event Date</div>
                    <div class="detail-value">{{ $booking->event_date->format('F d, Y') }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Time</div>
                    <div class="detail-value">
                        {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} –
                        {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                    </div>
                </div>
                @if ($booking->admin_remarks)
                    <div class="detail-row">
                        <div class="detail-label">Reason</div>
                        <div class="detail-value" style="color: #d9534f;">{{ $booking->admin_remarks }}</div>
                    </div>
                @endif
            </div>

            <div class="footer-message">
                You are welcome to submit a new booking request for an alternative date or venue. If you have any
                concerns or need clarification, please contact the administrator.
            </div>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Office of Civil Defense – Venue Management System.<br>This is an automated
            message, please do not reply.
        </div>
    </div>
</body>

</html>
