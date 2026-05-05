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

        .btn-verify {
            display: inline-block;
            background-color: #1a3c72;
            color: #ffffff !important;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 10px 0 25px;
            letter-spacing: 0.5px;
        }

        .footer-message {
            margin-top: 20px;
            font-size: 0.95rem;
            line-height: 1.5;
            color: #555;
        }

        .link-fallback {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            font-size: 0.8rem;
            color: #6c757d;
            word-break: break-all;
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
            <div class="greeting">Hello User,</div>

            <div class="message">
                Please click the button below to verify your email address.
            </div>

            <div style="text-align: center;">
                <a href="{{ $url }}" class="btn-verify">Verify Email Address</a>
            </div>

            <div class="message">
                If you did not create an account, no further action is required.
            </div>

            <div class="footer-message">
                Regards,<br>
                ICTS-SDIMD
            </div>

            <div class="link-fallback">
                If you're having trouble clicking the "Verify Email Address" button, copy and paste the URL below into
                your web browser: <br>
                <a href="{{ $url }}"
                    style="color: #1a3c72; margin-top: 5px; display: inline-block;">{{ $url }}</a>
            </div>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Office of Civil Defense – Venue Management System.<br>This is an automated
            message, please do not reply.
        </div>
    </div>
</body>

</html>
