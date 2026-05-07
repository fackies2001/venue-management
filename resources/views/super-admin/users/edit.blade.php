@extends('layouts.app')

@section('title', 'Edit User — ' . $user->name)

@push('styles')
    <style>
        .btn-ocd-primary {
            background-color: var(--ocd-blue);
            color: white;
            border: none;
        }

        .btn-ocd-primary:hover {
            background-color: var(--ocd-orange);
            color: white;
        }

        .info-label-custom {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #94a3b8;
            font-weight: 800;
            margin-bottom: 0.4rem;
        }

        .form-switch .form-check-input {
            width: 3em;
            height: 1.5em;
            cursor: pointer;
        }

        /* Dashboard Header Styling */
        .edit-header-bg {
            background: var(--ocd-dark);
            padding: 3rem 2rem;
            position: relative;
            border-radius: 12px 12px 0 0;
        }

        .avatar-wrapper {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            background: #fff;
            padding: 5px;
            position: absolute;
            bottom: -55px;
            left: 40px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .avatar-inner {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: var(--ocd-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.8rem;
            font-weight: 700;
            color: #fff;
        }

        .header-offset {
            margin-top: 60px;
            padding: 0 40px 25px;
        }

        /* Requirement Checklist */
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
    </style>
@endpush

@section('content')
    {{-- ── TOP SECTION: Branded Dashboard Header ── --}}
    <div class="card border-0 shadow-sm overflow-hidden mb-4" style="border-radius: 15px;">
        <div class="edit-header-bg">
            <div class="avatar-wrapper">
                <div class="avatar-inner">
                    {{ collect(explode(' ', $user->name))->map(fn($w) => strtoupper($w[0] ?? ''))->take(2)->implode('') }}
                </div>
            </div>
            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('super-admin.users.show', $user->id) }}" class="btn btn-light btn-sm fw-bold px-3">
                    <i class="bi bi-eye me-1"></i> View Profile
                </a>
                <a href="{{ route('super-admin.users.index') }}" class="btn btn-outline-light btn-sm fw-bold px-3">
                    <i class="bi bi-arrow-left me-1"></i> Back to Users
                </a>
            </div>
        </div>
        <div class="header-offset">
            <h2 class="fw-bold text-dark mb-1">{{ $user->name }}</h2>
            <p class="text-muted small mb-0">
                <i class="bi bi-envelope me-1"></i>{{ $user->email }} |
                <span
                    class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ strtoupper($user->role) }}</span>
            </p>
        </div>
    </div>

    <div class="row g-4">
        {{-- ── LEFT COLUMN: Status & Tips ── --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h6 class="fw-bold text-dark mb-3">Account Summary</h6>
                <div class="p-3 rounded-3 bg-light">
                    <div class="mb-3">
                        <label class="info-label-custom d-block">Current Assignment</label>
                        <span
                            class="fw-semibold text-dark small">{{ $user->division->name ?? 'No Division Assigned' }}</span>
                    </div>
                    <hr class="opacity-10">
                    <div>
                        <label class="info-label-custom d-block">System Status</label>
                        @if ($user->is_active)
                            <span class="text-success fw-bold small"><i class="bi bi-check-circle-fill me-1"></i> Active
                                Access</span>
                        @else
                            <span class="text-danger fw-bold small"><i class="bi bi-dash-circle-fill me-1"></i> Restricted
                                Access</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm p-4"
                style="background: #fffdf5; border-left: 4px solid var(--ocd-orange) !important;">
                <h6 class="fw-bold text-dark mb-2 small"><i class="bi bi-lightbulb text-warning me-2"></i>Administrative
                    Tips</h6>
                <ul class="text-muted small ps-3 mb-0">
                    <li class="mb-2">Changes to account info take effect immediately upon saving.</li>
                    <li class="mb-2">Password updates are independent of profile changes.</li>
                    <li>Deactivating a user prevents login without deleting their history.</li>
                </ul>
            </div>
        </div>

        {{-- ── RIGHT COLUMN: Main Forms ── --}}
        <div class="col-lg-8">
            {{-- Form 1: Account Information --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-person-gear me-2 text-primary"></i>Account
                        Information</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <form method="POST" action="{{ route('super-admin.users.update', $user->id) }}">
                        @csrf @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="info-label-custom d-block">Full Name</label>
                                <input type="text" name="name" class="form-control bg-light border-0 py-2"
                                    value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="info-label-custom d-block">Email Address</label>
                                <input type="email" name="email" class="form-control bg-light border-0 py-2"
                                    value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="info-label-custom d-block">System Role</label>
                                <select name="role" class="form-select bg-light border-0 py-2">
                                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="super_admin" {{ $user->role == 'super_admin' ? 'selected' : '' }}>Super
                                        Admin</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="info-label-custom d-block">Service / Division</label>
                                <select name="division_id" class="form-select bg-light border-0 py-2">
                                    @foreach ($divisions as $division)
                                        <option value="{{ $division->id }}"
                                            {{ $user->division_id == $division->id ? 'selected' : '' }}>
                                            {{ $division->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 mt-3">
                                <div
                                    class="form-check form-switch p-3 bg-light rounded-3 d-flex align-items-center justify-content-between">
                                    <div>
                                        <label class="form-check-label fw-bold mb-0" for="activeToggle">Account
                                            Activation</label>
                                        <p class="text-muted small mb-0">Toggle to enable or disable system access.</p>
                                    </div>
                                    <input class="form-check-input" type="checkbox" name="is_active" id="activeToggle"
                                        {{ $user->is_active ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-ocd-primary px-4 fw-bold shadow-sm">Save Profile
                                Changes</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Form 2: Password Reset --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-shield-lock me-2 text-warning"></i>Administrative
                        Password Reset</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="alert alert-warning border-0 small mb-4">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> Leave fields blank if you do not wish to change
                        the password.
                    </div>
                    <form method="POST" action="{{ route('super-admin.users.update', $user->id) }}">
                        @csrf @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="info-label-custom d-block">New Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="adminNewPass"
                                        class="form-control bg-light border-0 py-2" placeholder="Min. 16 characters">
                                    <button class="btn btn-light border-0" type="button"
                                        onclick="togglePass('adminNewPass', this)"><i class="bi bi-eye"></i></button>
                                </div>
                                {{-- Live Restrictions Checklist --}}
                                <div id="adminChecklist" class="mt-3 p-3 bg-light rounded-3 shadow-sm border"
                                    style="display:none;">
                                    <p class="mb-2 fw-bold small text-dark">SECURITY REQUIREMENTS:</p>
                                    <div class="requirement-item req-invalid mb-1" id="len"><i
                                            class="bi bi-x-circle me-2"></i>16+ Characters</div>
                                    <div class="requirement-item req-invalid mb-1" id="cap"><i
                                            class="bi bi-x-circle me-2"></i>Uppercase Letter</div>
                                    <div class="requirement-item req-invalid mb-1" id="num"><i
                                            class="bi bi-x-circle me-2"></i>Numeric Digits</div>
                                    <div class="requirement-item req-invalid" id="spec"><i
                                            class="bi bi-x-circle me-2"></i>Special Characters</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="info-label-custom d-block">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="adminConfPass"
                                        class="form-control bg-light border-0 py-2" placeholder="Confirm Password">
                                    <button class="btn btn-light border-0" type="button"
                                        onclick="togglePass('adminConfPass', this)"><i class="bi bi-eye"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-warning text-white px-4 fw-bold shadow-sm">Force
                                Password Reset</button>
                        </div>
                    </form>
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

        const passInput = document.getElementById('adminNewPass');
        const checklist = document.getElementById('adminChecklist');

        passInput.addEventListener('focus', () => checklist.style.display = 'block');
        passInput.addEventListener('input', function() {
            const val = this.value;
            const rules = {
                len: val.length >= 16,
                cap: /[A-Z]/.test(val),
                num: /[0-9]/.test(val),
                spec: /[!@#$%^&*(),.?":{}|<>]/.test(val)
            };
            Object.entries(rules).forEach(([id, ok]) => {
                const el = document.getElementById(id);
                el.className = ok ? 'requirement-item req-valid mb-1' : 'requirement-item req-invalid mb-1';
                el.querySelector('i').className = ok ? 'bi bi-check-circle-fill me-2' :
                    'bi bi-x-circle me-2';
            });
        });
    </script>
@endpush
