@extends('layouts.app')

@section('title', 'My Profile')

@push('styles')
    <style>
        .profile-container {
            max-width: 1100px;
            margin: 0 auto;
        }

        .main-profile-card {
            border-radius: 12px;
            border: none;
            overflow: hidden;
            background: #fff;
        }

        .profile-header-bg {
            background: var(--ocd-dark);
            padding: 3rem 2rem;
            position: relative;
        }

        .profile-avatar-wrapper {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: #fff;
            padding: 5px;
            position: absolute;
            bottom: -60px;
            left: 40px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .profile-avatar-inner {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: var(--ocd-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: 700;
            color: #fff;
        }

        .header-content-offset {
            margin-top: 70px;
            padding: 0 40px 30px;
        }

        .info-label-custom {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #94a3b8;
            font-weight: 800;
            margin-bottom: 0.4rem;
        }

        /* Requirement Checklist Styling */
        .requirement-item {
            font-size: 0.75rem;
            transition: all 0.3s ease;
        }

        .req-invalid {
            color: #dc3545;
        }

        .req-valid {
            color: #198754;
            font-weight: 600;
        }

        .btn-ocd-save {
            background-color: var(--ocd-blue);
            color: #fff;
            font-weight: 700;
            border: none;
        }

        .btn-ocd-save:hover {
            background-color: var(--ocd-dark);
            color: #fff;
        }

        .btn-ocd-orange {
            background-color: var(--ocd-orange);
            color: #fff;
            font-weight: 700;
            border: none;
        }

        .btn-ocd-orange:hover {
            background-color: #d66a1e;
            color: #fff;
        }
    </style>
@endpush

@section('content')
    <div class="profile-container">
        <div class="mb-4">
            <h1 class="h4 fw-bold text-dark"><i class="bi bi-person-vcard me-2 text-primary"></i>My Profile</h1>
            <p class="text-muted small">Update your personal identity and security credentials.</p>
        </div>

        <div class="card main-profile-card shadow-sm mb-5">
            <div class="profile-header-bg">
                <div class="profile-avatar-wrapper">
                    <div class="profile-avatar-inner">
                        {{ collect(explode(' ', auth()->user()->name))->map(fn($w) => strtoupper($w[0] ?? ''))->take(2)->implode('') }}
                    </div>
                </div>
            </div>

            <div class="header-content-offset">
                <div class="row align-items-end mb-5">
                    <div class="col">
                        <h2 class="fw-bold text-dark mb-0">{{ auth()->user()->name }}</h2>
                        <p class="text-muted small mb-0"><i class="bi bi-envelope me-1"></i>{{ auth()->user()->email }}</p>
                    </div>
                </div>

                <div class="row g-5">
                    {{-- ── Personal Information ── --}}
                    <div class="col-lg-6 border-end">
                        <h5 class="fw-bold mb-4 text-dark"><i class="bi bi-info-circle me-2 text-primary"></i>Identity
                            Details</h5>
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf @method('PUT')
                            <div class="mb-3">
                                <label class="info-label-custom d-block">Full Name</label>
                                <input type="text" name="name" class="form-control bg-light border-0 py-2"
                                    value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="info-label-custom d-block">Email Address</label>
                                <input type="email" class="form-control py-2" value="{{ $user->email }}"
                                    style="background-color: #f1f3f5; color: #6c757d; cursor: not-allowed; border: 1px solid #e9ecef;"
                                    readonly tabindex="-1">
                                <p class="text-muted mt-1" style="font-size: 0.65rem;">
                                    <i class="bi bi-lock-fill me-1"></i>Contact Information Technology Service to change
                                    your email.
                                </p>
                            </div>
                            <div class="mb-4">
                                <label class="info-label-custom d-block">Service / Division</label>
                                <select name="division_id" class="form-select bg-light border-0 py-2" required>
                                    @foreach ($divisions as $division)
                                        <option value="{{ $division->id }}"
                                            {{ $user->division_id == $division->id ? 'selected' : '' }}>
                                            {{ $division->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-ocd-save w-100 py-2">Save Profile Changes</button>
                        </form>
                    </div>

                    {{-- ── Security Settings ── --}}
                    <div class="col-lg-6">
                        <h5 class="fw-bold mb-4 text-dark"><i class="bi bi-shield-lock me-2 text-warning"></i>Security
                            Settings</h5>
                        <form method="POST" action="{{ route('profile.password') }}">
                            @csrf @method('PUT')
                            <div class="mb-3">
                                <label class="info-label-custom d-block">Current Password</label>
                                <div class="input-group">
                                    <input type="password" name="current_password" id="curr_pass"
                                        class="form-control bg-light border-0 py-2" placeholder="Current Password" required>
                                    <button class="btn btn-light border-0" type="button"
                                        onclick="togglePass('curr_pass', this)"><i class="bi bi-eye"></i></button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="info-label-custom d-block">New Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="new_pass"
                                        class="form-control bg-light border-0 py-2" placeholder="New Password" required>
                                    <button class="btn btn-light border-0" type="button"
                                        onclick="togglePass('new_pass', this)"><i class="bi bi-eye"></i></button>
                                </div>

                                {{-- Password Restrictions UI --}}
                                <div id="checklist" class="mt-3 p-3 bg-light rounded-3 shadow-sm border"
                                    style="display:none;">
                                    <p class="mb-2 fw-bold small text-dark">PASSWORD RESTRICTIONS:</p>
                                    <div class="requirement-item req-invalid mb-1" id="len"><i
                                            class="bi bi-x-circle me-2"></i>At least 16 Characters</div>
                                    <div class="requirement-item req-invalid mb-1" id="cap"><i
                                            class="bi bi-x-circle me-2"></i>Uppercase Letter (e.g., P)</div>
                                    <div class="requirement-item req-invalid mb-1" id="low"><i
                                            class="bi bi-x-circle me-2"></i>Lowercase Letter</div>
                                    <div class="requirement-item req-invalid mb-1" id="num"><i
                                            class="bi bi-x-circle me-2"></i>Numeric Digits</div>
                                    <div class="requirement-item req-invalid" id="spec"><i
                                            class="bi bi-x-circle me-2"></i>Special Characters</div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="info-label-custom d-block">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="conf_pass"
                                        class="form-control bg-light border-0 py-2" placeholder="Confirm Password" required>
                                    <button class="btn btn-light border-0" type="button"
                                        onclick="togglePass('conf_pass', this)"><i class="bi bi-eye"></i></button>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-ocd-orange w-100 py-2">Update Security
                                Credentials</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function togglePass(id, btn) {
            const input = document.getElementById(id);
            const icon = btn.querySelector('i');
            input.type = input.type === 'password' ? 'text' : 'password';
            icon.className = input.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
        }

        document.getElementById('new_pass').addEventListener('focus', () => document.getElementById('checklist').style
            .display = 'block');

        document.getElementById('new_pass').addEventListener('input', function() {
            const val = this.value;
            const rules = {
                len: val.length >= 16,
                cap: /[A-Z]/.test(val),
                low: /[a-z]/.test(val),
                num: /[0-9]/.test(val),
                spec: /[!@#$%^&*(),.?":{}|<>]/.test(val)
            };
            Object.entries(rules).forEach(([id, ok]) => {
                const el = document.getElementById(id);
                el.className = ok ? 'text-success fw-bold' : 'text-danger';
                el.querySelector('i').className = ok ? 'bi bi-check-circle-fill me-2' :
                    'bi bi-x-circle me-2';
            });
        });
    </script>
@endpush
