<!DOCTYPE html>
<html lang="en">

 <head> 
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verify Email – OCD</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link rel="icon" type="image/png" href="{{ asset('OCDLOGO.png') }}">
</head>

<body style="background:#0a1144; min-height:100vh; display:flex; align-items:center; justify-content:center;"> 

<div class="card shadow" style="width:460px; border-radius:14px; overflow:hidden;">
    {{-- Header --}}
    <div class="text-center py-4" style="background:#0d2461;">
        <i class="bi bi-envelope-check text-white" style="font-size:2.5rem;"></i>
        <h5 class="text-white fw-bold mt-2 mb-0">Verify Your Email Address</h5>
    </div>

    {{-- Body --}}
    <div class="card-body px-4 py-4 text-center">
        <p class="text-muted mb-2">
            A verification link has been sent to:
        </p>
        <p class="fw-bold mb-4" style="color:#0d2461; font-size:1rem;">
            {{ auth()->user()->email }}
        </p>
        <p class="text-muted small mb-4">
            Please check your inbox and click the link to activate your account
            before accessing the <strong>Venue Management System</strong>.
        </p>

        @if (session('message'))
        <div class="alert alert-success py-2 small">
            <i class="bi bi-check-circle me-1"></i>{{ session('message') }}
        </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn w-100 fw-semibold mb-3"
                style="background:#f07d00; color:#fff; border:none;">
                <i class="bi bi-send me-2"></i>Send Verification Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary btn-sm w-100">
                <i class="bi bi-box-arrow-left me-1"></i>Back to Login
            </button>
        </form>
    </div>
</div>

</body>

</html>