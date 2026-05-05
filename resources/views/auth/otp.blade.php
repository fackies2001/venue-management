@extends('layouts.app') <!-- Replace with your guest layout if different -->

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="card shadow-sm"
            style="max-width: 400px; width: 100%; border-radius: 12px; border-top: 5px solid var(--ocd-blue);">
            <div class="card-body p-4 text-center">

                <i class="bi bi-shield-lock text-primary display-4 mb-3"></i>
                <h4 class="fw-bold mb-2">Two-Factor Verification</h4>
                <p class="text-muted small mb-4">We've sent a 6-digit security code to your email. Please enter it below to
                    securely access your account.</p>

                @if (session('error'))
                    <div class="alert alert-danger py-2 small">{{ session('error') }}</div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success py-2 small">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('otp.verify') }}">
                    @csrf
                    <div class="mb-4">
                        <input type="text" name="otp_code" class="form-control form-control-lg text-center fw-bold"
                            style="letter-spacing: 10px; font-size: 1.5rem;" placeholder="••••••" maxlength="6" required
                            autocomplete="off" autofocus>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold mb-3"
                        style="background: var(--ocd-blue); border: none;">
                        Verify Account
                    </button>
                </form>

                <form method="POST" action="{{ route('otp.resend') }}">
                    @csrf
                    <p class="small text-muted mb-0">Didn't receive the code?
                        <button type="submit" class="btn btn-link p-0 text-decoration-none fw-semibold">Resend OTP</button>
                    </p>
                </form>

            </div>
        </div>
    </div>
@endsection
