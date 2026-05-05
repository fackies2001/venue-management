<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – OCD Venue Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('OCDLOGO.png') }}">
    <style>
        :root {
            --ocd-blue: #1a3c72;
            --ocd-orange: #e87722;
        }

        body {
            min-height: 100vh;
            background: url('{{ asset('bgimg.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, .3);
            overflow: hidden;
            border-top: 6px solid var(--ocd-orange);
        }

        .login-header {
            background: #0a1144;
            color: #fff;
            padding: 2.5rem 2rem 1.5rem;
            text-align: center;
        }

        .login-header img {
            max-width: 80px;
            height: auto;
            margin-bottom: 1rem;
        }

        .login-header h4 {
            font-weight: 700;
            margin: 0;
            margin-bottom: 0.25rem;
        }

        .login-body {
            padding: 2rem;
        }

        .btn-login {
            background-color: var(--ocd-orange) !important;
            border: none !important;
            color: #ffffff !important;
            font-weight: 600;
            width: 100%;
            padding: .65rem;
            border-radius: 8px;
            box-shadow: none !important;
        }

        .btn-login:hover {
            opacity: 0.9;
        }

        .form-control:focus {
            border-color: var(--ocd-blue);
            box-shadow: 0 0 0 .2rem rgba(26, 60, 114, .2);
        }

        .divider {
            text-align: center;
            color: #888;
            font-size: .8rem;
            margin: 1.5rem 0 1rem;
        }

        a.register-link,
        .btn-link-custom {
            color: var(--ocd-blue);
            font-weight: 600;
            text-decoration: none;
        }

        .btn-link-custom {
            background: none;
            border: none;
            padding: 0;
            font: inherit;
            cursor: pointer;
        }

        .btn-link-custom:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="login-header">
            <img src="{{ asset('OCDLOGO.png') }}" alt="OCD Logo">
            <h4>{{ session('show_otp') ? 'Two-Factor Verification' : 'Log In' }}</h4>
            <p class="mb-0 small opacity-75">Venue Management System</p>
        </div>
        <div class="login-body">

            @if (session('success'))
                <div class="alert alert-success py-2 small"><i
                        class="bi bi-check-circle me-1"></i>{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger py-2 small"><i
                        class="bi bi-exclamation-triangle me-1"></i>{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger py-2 small"><i
                        class="bi bi-exclamation-circle me-1"></i>{{ $errors->first() }}</div>
            @endif

            {{-- 1. KUNG MAY OTP FLAG, ITO ANG LALABAS --}}
            @if (session('show_otp'))
                <form method="POST" action="{{ route('otp.verify') }}">
                    @csrf
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="bi bi-shield-lock text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <p class="small text-muted">We've sent a 6-digit security code to your email. Please enter it
                            below.</p>
                    </div>

                    <div class="mb-3">
                        <input type="text" name="otp_code" class="form-control text-center fw-bold"
                            placeholder="· · · · · ·" maxlength="6" style="font-size: 1.5rem; letter-spacing: 5px;"
                            required autofocus autocomplete="off">
                    </div>

                    <button type="submit" class="btn btn-login mb-3">Verify Account</button>
                </form>

                {{-- Working Resend OTP Form --}}
                <form method="POST" action="{{ route('otp.resend') }}" class="text-center">
                    @csrf
                    <div class="small">
                        Didn't receive the code?
                        <button type="submit" class="btn-link-custom">Resend OTP</button>
                    </div>
                </form>

                {{-- 2. KUNG WALA PANG OTP, NORMAL LOGIN FORM --}}
            @else
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-envelope text-secondary"></i></span>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                placeholder="Enter your email" required autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-lock text-secondary"></i></span>
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="Password" required>
                            <button type="button" class="input-group-text" onclick="toggleEye()">
                                <i id="eyeIcon" class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label small" for="remember">Remember me</label>
                    </div>

                    <button type="submit" class="btn btn-login">Log In</button>
                </form>

                <div class="divider"></div>
                <div class="text-center small">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="register-link">Register here</a>
                </div>
            @endif
        </div>
    </div>

    <script>
        function toggleEye() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }
    </script>
</body>

</html>
