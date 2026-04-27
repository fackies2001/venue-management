<!DOCTYPE html>
<html lang="en">

<!-- <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register – OCD Venue Management System</title>
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
            background: linear-gradient(135deg, var(--ocd-blue) 0%, #0d2347 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .register-card {
            width: 100%;
            max-width: 480px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, .3);
            overflow: hidden;
        }

        .register-header {
            background: var(--ocd-blue);
            color: #fff;
            padding: 1.5rem 2rem;
        }

        .register-header h4 {
            font-weight: 700;
            margin: 0;
        }

        .register-body {
            padding: 1.75rem 2rem;
        }

        .btn-register {
            background: var(--ocd-orange);
            border: none;
            color: #fff;
            font-weight: 600;
            width: 100%;
            padding: .65rem;
            border-radius: 8px;
        }

        .form-control:focus {
            border-color: var(--ocd-blue);
            box-shadow: 0 0 0 .2rem rgba(26, 60, 114, .2);
        }

        .note {
            background: #fff8e1;
            border-left: 3px solid var(--ocd-orange);
            padding: .75rem 1rem;
            border-radius: 4px;
            font-size: .82rem;
            color: #555;
        }
    </style>
</head> -->

<!-- <body>
    <div class="register-card">
        <div class="register-header">
            <h4><i class="bi bi-person-plus me-2"></i>Register Account</h4>
            <p class="mb-0 small opacity-75">OCD Venue Management System</p>
        </div>
        <div class="register-body">

            <div class="note mb-3">
                <i class="bi bi-info-circle me-1"></i>
                Reminder: Registration requires a verified Gmail account. Unverified accounts will not be accepted.
            </div>

            @if ($errors->any())
                <div class="alert alert-danger py-2 small">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" placeholder="Juan Dela Cruz" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" placeholder="you@ocd.gov.ph" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Department <span class="text-danger">*</span></label>
                    <input type="text" name="department"
                        class="form-control @error('department') is-invalid @enderror" value="{{ old('department') }}"
                        placeholder="e.g. Operations, NDRRMOC" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Contact Number</label>
                    <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number') }}"
                        placeholder="09XXXXXXXXX">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        placeholder="Minimum 8 characters" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold small">Confirm Password <span
                            class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-register">Submit Registration</button>
            </form>

            <div class="text-center mt-3 small">
                Already have an account? <a href="{{ route('login') }}"
                    style="color:var(--ocd-blue);font-weight:600;">Log in</a>
            </div>
        </div>
    </div>
</body> -->

</html>