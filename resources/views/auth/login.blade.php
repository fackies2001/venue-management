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
            /* Eto yung bago para sa background image */
            background: url('{{ asset('bgimg.jpg') }}') no-repeat center center fixed;
            background-size: contain;

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
            background: var(--ocd-orange);
            border: none;
            color: #fff;
            font-weight: 600;
            width: 100%;
            padding: .65rem;
            border-radius: 8px;
            transition: opacity .15s;
        }

        .btn-login:hover {
            opacity: .9;
            color: #fff;
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

        a.register-link {
            color: var(--ocd-blue);
            font-weight: 600;
            text-decoration: none;
        }

        a.register-link:hover {
            text-decoration: underline;
        }

        .toggle-eye-btn {
            background: transparent;
            border: 1px solid #dee2e6;
            border-left: none;
            color: #888;
            transition: color 0.15s;
        }

        .toggle-eye-btn:hover {
            color: var(--ocd-blue);
        }

        .form-control-password {
            border-right: none;
        }

        .form-control-password:focus+.toggle-eye-btn {
            border-color: var(--ocd-blue);
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="login-header">
            <img src="{{ asset('OCDLOGO.png') }}" alt="OCD Logo">
            <h4>Log In</h4>
            <p class="mb-0 small opacity-75">Venue Management System</p>
        </div>
        <div class="login-body">

            @if (session('success'))
                <div class="alert alert-success py-2 small"><i
                        class="bi bi-check-circle me-1"></i>{{ session('success') }}</div>
            @endif

            {{--  FIXED: Eto yung sasalo ng error messages galing sa controller --}}
            @if (session('error'))
                <div class="alert alert-danger py-2 small"><i
                        class="bi bi-exclamation-triangle me-1"></i>{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger py-2 small"><i
                        class="bi bi-exclamation-circle me-1"></i>{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-envelope text-secondary"></i></span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" placeholder="Enter your gmail" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-lock text-secondary"></i></span>
                        <input type="password" id="password" name="password" class="form-control form-control-password"
                            placeholder="Password" required>
                        <button type="button" class="input-group-text toggle-eye-btn" onclick="toggleEye()"
                            tabindex="-1" aria-label="Toggle password visibility">
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
