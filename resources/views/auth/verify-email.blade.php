<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email – OCD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('OCDLOGO.png') }}">
    <style>
        body {
            background: #0a1144;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .verify-card {
            width: 100%;
            max-width: 460px;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, .3);
            overflow: hidden;
        }

        .verify-header {
            background: #fff;
            color: #0a1144;
            padding: 2.5rem 2rem 1rem;
            text-align: center;
        }

        .btn-verify {
            background: #f07d00;
            color: #fff;
            border: none;
            padding: .65rem;
            border-radius: 8px;
            font-weight: 600;
            transition: opacity .2s;
        }

        .btn-verify:hover {
            opacity: 0.9;
            color: #fff;
        }
    </style>
</head>

<body>

    <div class="verify-card">
        {{-- Header (Clean Solid Theme) --}}
        <div class="verify-header">
            <i class="bi bi-envelope-check" style="font-size:3.5rem; color: #f07d00;"></i>
            <h4 class="fw-bold mt-3 mb-0" style="color: #0a1144;">Verify Your Email Address</h4>
        </div>

        {{-- Body --}}
        <div class="card-body px-4 pb-5 text-center">
            <p class="text-muted mb-2">
                A verification link has been sent to:
            </p>
            <p class="fw-bold mb-4" style="color:#0a1144; font-size:1.1rem;">
                {{ auth()->user()->email }}
            </p>
            <p class="text-muted small mb-4">
                Please check your inbox and click the link to activate your account
                before accessing the <strong>Venue Management System</strong>.
            </p>

            {{-- Success Message & 5-Second Redirect Logic --}}
            @if (session('message') || session('status') == 'verification-link-sent')
                <div class="alert alert-success py-2 small mb-4" id="success-alert">
                    <i class="bi bi-check-circle-fill me-1"></i> Email sent! Redirecting to login in <span
                        id="countdown">5</span>...
                </div>

                {{-- Hidden Logout form to properly clear session before going to login --}}
                <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                    @csrf
                </form>

                <script>
                    let timeLeft = 5;
                    const countdownEl = document.getElementById('countdown');
                    const timer = setInterval(() => {
                        timeLeft--;
                        countdownEl.textContent = timeLeft;
                        if (timeLeft <= 0) {
                            clearInterval(timer);
                            // Submit the hidden logout form to safely redirect to login
                            document.getElementById('logout-form').submit();
                        }
                    }, 1000);
                </script>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-verify w-100">
                    <i class="bi bi-send me-2"></i>Send Verification Email
                </button>
            </form>
        </div>
    </div>

</body>

</html>
