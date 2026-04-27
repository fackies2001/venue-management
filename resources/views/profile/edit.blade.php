@extends('layouts.app')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
    <div class="page-header">
        <h1><i class="bi bi-person-circle me-2"></i>My Profile</h1>
    </div>

    <div class="row g-4">

        {{-- ── LEFT COLUMN: Avatar + Role Info ── --}}
        <div class="col-lg-3">
            <div class="card text-center p-4">

                {{-- Initials Avatar --}}
                @php
                    $initials = collect(explode(' ', auth()->user()->name))
                        ->map(fn($w) => strtoupper($w[0] ?? ''))
                        ->take(2)
                        ->implode('');
                @endphp
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle"
                    style="width:90px;height:90px;background:var(--ocd-blue);color:#fff;font-size:2rem;font-weight:700;">
                    {{ $initials }}
                </div>

                <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                <span class="badge rounded-pill mb-2" style="background:var(--ocd-orange);color:#fff;font-size:.78rem;">
                    {{ ucwords(str_replace('_', ' ', $user->role)) }}
                </span>

                <hr>

                <div class="text-start small">
                    <div class="mb-2">
                        <span class="text-muted">
                            <i class="bi bi-envelope me-1"></i>Email
                        </span>
                        <div class="fw-semibold text-truncate">{{ $user->email }}</div>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted">
                            <i class="bi bi-building me-1"></i>Department
                        </span>
                        <div class="fw-semibold">{{ $user->department ?? '—' }}</div>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted">
                            <i class="bi bi-telephone me-1"></i>Contact
                        </span>
                        <div class="fw-semibold">{{ $user->contact_number ?? '—' }}</div>
                    </div>
                    <div>
                        <span class="text-muted">
                            <i class="bi bi-clock me-1"></i>Last Login
                        </span>
                        <div class="fw-semibold">
                            {{ $user->last_login_at ? $user->last_login_at->format('M d, Y h:i A') : '—' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── RIGHT COLUMN: Edit Forms ── --}}
        <div class="col-lg-9">

            {{-- ── SECTION 1: Edit Profile Info ── --}}
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="bi bi-pencil-square" style="color:var(--ocd-orange);"></i>
                    Edit Profile Information
                </div>
                <div class="card-body">

                    @if ($errors->has('name') || $errors->has('email') || $errors->has('department') || $errors->has('contact_number'))
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                @foreach (['name', 'email', 'department', 'contact_number'] as $field)
                                    @error($field)
                                        <li>{{ $message }}</li>
                                    @enderror
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">

                            {{-- Full Name --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Full Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" required>
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Email Address <span class="text-danger">*</span>
                                </label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}" required>
                                <div class="form-text text-muted">
                                    <i class="bi bi-info-circle"></i>
                                    Changing your email will require re-verification.
                                </div>
                            </div>

                            {{-- Department --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Department</label>
                                <input type="text" name="department"
                                    class="form-control @error('department') is-invalid @enderror"
                                    value="{{ old('department', $user->department) }}" placeholder="e.g. Operations">
                            </div>

                            {{-- Contact Number --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Contact Number</label>
                                <input type="text" name="contact_number"
                                    class="form-control @error('contact_number') is-invalid @enderror"
                                    value="{{ old('contact_number', $user->contact_number) }}"
                                    placeholder="e.g. 09XXXXXXXXX">
                            </div>

                            {{-- Role (read-only) --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Role</label>
                                <input type="text" class="form-control"
                                    value="{{ ucwords(str_replace('_', ' ', $user->role)) }}" disabled readonly>
                                <div class="form-text text-muted">
                                    Role can only be changed by a Super Admin.
                                </div>
                            </div>

                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn px-4"
                                style="background:var(--ocd-blue);color:#fff;border:none;">
                                <i class="bi bi-check-lg me-1"></i> Save Changes
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            {{-- ── SECTION 2: Change Password ── --}}
            <div class="card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="bi bi-shield-lock" style="color:var(--ocd-orange);"></i>
                    Change Password
                </div>
                <div class="card-body">

                    @if ($errors->has('current_password') || $errors->has('password'))
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                @foreach (['current_password', 'password'] as $field)
                                    @error($field)
                                        <li>{{ $message }}</li>
                                    @enderror
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.password') }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">

                            {{-- Current Password --}}
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">
                                    Current Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" name="current_password" id="currentPassword"
                                        class="form-control @error('current_password') is-invalid @enderror"
                                        placeholder="Enter current password" required>
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('currentPassword', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- New Password --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    New Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" name="password" id="newPassword"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Min. 8 characters" required>
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('newPassword', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Confirm Password --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Confirm New Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="confirmPassword"
                                        class="form-control" placeholder="Re-enter new password" required>
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('confirmPassword', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn px-4"
                                style="background:var(--ocd-orange);color:#fff;border:none;">
                                <i class="bi bi-lock me-1"></i> Update Password
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }
    </script>
@endpush
