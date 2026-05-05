<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6fb;
            padding: 0;
            margin: 0;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            border-radius: 8px;
            border-top: 5px solid #1a3c72;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .05);
        }

        .header {
            background: #1a3c72;
            padding: 30px;
            text-align: center;
            color: #fff;
        }

        .header h2 {
            margin: 0;
        }

        .body {
            padding: 40px;
            text-align: center;
        }

        .otp-code {
            font-size: 2.5rem;
            font-weight: bold;
            letter-spacing: 5px;
            color: #1a3c72;
            background: #f8f9fa;
            padding: 15px 30px;
            border-radius: 8px;
            display: inline-block;
            margin: 20px 0;
            border: 1px dashed #ced4da;
        }

        .footer {
            background: #f1f3f5;
            padding: 20px;
            text-align: center;
            font-size: 0.8rem;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Venue Management System</h2>
            <p style="margin: 5px 0 0; opacity: 0.8;">Office of Civil Defense</p>
        </div>
        <div class="body">
            <p style="font-size: 1.1rem; color: #555;">Your One-Time Password (OTP) for login is:</p>
            <div class="otp-code">{{ $otpCode }}</div>
            <p style="color: #dc3545; font-size: 0.9rem;">This code will expire in 10 minutes. Do not share this with
                anyone.</p>
            <div style="margin-top: 30px; font-size: 0.95rem; color: #555; text-align: left;">
                Regards,<br><strong>ICTS-SDIMD</strong>
            </div>
        </div>
        <div class="footer">&copy; {{ date('Y') }} Office of Civil Defense.</div>
    </div>
</body>

</html>
