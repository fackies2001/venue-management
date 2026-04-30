@extends('layouts.app')

@section('title', 'Edit User — ' . $user->name)
@section('page-title', 'Edit User')

@push('styles')
    <style>
        .avatar-circle-sm {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: var(--ocd-blue);
            color: #fff;
            font-size: 1.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(26, 60, 114, .25);
            flex-shrink: 0;
        }

        .section-divider {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-bottom: 1.25rem;
        }

        .section-divider .section-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .section-divider .section-title {
            font-weight: 700;
            font-size: 1rem;
            color: var(--ocd-blue);
        }

        .section-divider .section-sub {
            font-size: .78rem;
            color: #6c757d;
        }

        .form-label {
            font-weight: 600;
            font-size: .85rem;
            color: #374151;
            margin-bottom: .35rem;
        }

        .form-control,
        .form-select {
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: .9rem;
            padding: .5rem .75rem;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--ocd-blue);
            box-shadow: 0 0 0 3px rgba(26, 60, 114, .1);
        }

        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: #dc3545;
        }

        .input-group .btn {
            border: 1.5px solid #e2e8f0;
            border-left: none;
            border-radius: 0 8px 8px 0;
            background: #f8f9fa;
            color: #6c757d;
        }

        .input-group .form-control {
            border-radius: 8px 0 0 8px;
        }

        .card-section {
            border: none;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .08);
        }

        .card-section .card-body {
            padding: 1.75rem;
        }

        .hint-box {
            background: #f0f9ff;
            border-left: 3px solid #0ea5e9;
            border-radius: 0 8px 8px 0;
            padding: .6rem .9rem;
            font-size: .82rem;
            color: #0369a1;
        }

        .warning-box {
            background: #fffbeb;
            border-left: 3px solid var(--ocd-orange);
            border-radius: 0 8px 8px 0;
            padding: .6rem .9rem;
            font-size: .82rem;
            color: #92400e;
        }

        .profile-meta span {
            font-size: .78rem;
            color: #6c757d;
        }
    </style>
@endpush

@section('content')

    @php
        $roleColors = [
            \App\Models\User::ROLE_SUPER_ADMIN => 'danger',
            \App\Models\User::ROLE_NDRRMOC => 'warning',
            \App\Models\User::ROLE_NAB => 'info',
            \App\Models\User::ROLE_USER => 'secondary',
        ];
        $roleLabels = [
            \App\Models\User::ROLE_SUPER_ADMIN => 'Super Admin',
            \App\Models\User::ROLE_NDRRMOC => 'NDRRMOC',
            \App\Models\User::ROLE_NAB => 'NAB',
            \App\Models\User::ROLE_USER => 'User',
        ];
        $color = $roleColors[$user->role] ?? 'secondary';
        $roleLabel = $roleLabels[$user->role] ?? strtoupper($user->role);
    @endphp

    {{-- Page Header --}}
    <div class="page-header d-flex justify-content-between align-items-center">
        <h1><i class="bi bi-person-gear me-2"></i>Edit User</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('super-admin.users.show', $user) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-eye me-1"></i> View Profile
            </a>
            <a href="{{ route('super-admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Users
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- ── LEFT: Profile Snapshot ── --}}
        <div class="col-lg-3 col-md-4">
            <div class="card card-section mb-3">
                <div class="card-body text-center py-4">
                    <div class="avatar-circle-sm mx-auto mb-3">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <h6 class="fw-bold mb-1">{{ $user->name }}</h6>
                    <p class="text-muted mb-2" style="font-size:.8rem;">{{ $user->email }}</p>
                    <div class="d-flex justify-content-center gap-1">
                        <span class="badge bg-{{ $color }}">{{ $roleLabel }}</span>
                        @if ($user->is_active)
                            <span class="badge badge-approved">Active</span>
                        @else
                            <span class="badge badge-rejected">Inactive</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Tips card --}}
            <div class="card card-section">
                <div class="card-body p-3">
                    <p class="fw-semibold mb-2" style="font-size:.85rem;color:var(--ocd-blue);">
                        <i class="bi bi-lightbulb me-1 text-warning"></i> Tips
                    </p>
                    <ul class="mb-0 ps-3" style="font-size:.8rem;color:#6c757d;line-height:1.8;">
                        <li>Changes are saved immediately after clicking <strong>Save Changes</strong>.</li>
                        <li>Password update is a separate action — fill in both fields and click <strong>Set
                                Password</strong>.</li>
                        <li>Deactivating a user prevents them from logging in without deleting their data.</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- ── RIGHT: Forms ── --}}
        <div class="col-lg-9 col-md-8">

            {{-- ── FORM 1: Account Info ── --}}
            <div class="card card-section mb-4">
                <div class="card-body">
                    <div class="section-divider">
                        <div class="section-icon" style="background:#eff6ff;color:var(--ocd-blue);">
                            <i class="bi bi-person-vcard"></i>
                        </div>
                        <div>
                            <div class="section-title">Account Information</div>
                            <div class="section-sub">Update name, email, role, and contact details</div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('super-admin.users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Role <span class="text-danger">*</span></label>
                                <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                    <option value="">-- Select Role --</option>
                                    <option value="{{ \App\Models\User::ROLE_USER }}"
                                        {{ old('role', $user->role) == \App\Models\User::ROLE_USER ? 'selected' : '' }}>
                                        User</option>
                                    <option value="{{ \App\Models\User::ROLE_NDRRMOC }}"
                                        {{ old('role', $user->role) == \App\Models\User::ROLE_NDRRMOC ? 'selected' : '' }}>
                                        NDRRMOC</option>
                                    <option value="{{ \App\Models\User::ROLE_NAB }}"
                                        {{ old('role', $user->role) == \App\Models\User::ROLE_NAB ? 'selected' : '' }}>NAB
                                    </option>
                                    <option value="{{ \App\Models\User::ROLE_SUPER_ADMIN }}"
                                        {{ old('role', $user->role) == \App\Models\User::ROLE_SUPER_ADMIN ? 'selected' : '' }}>
                                        Super Admin</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Department</label>
                                <input type="text" name="department"
                                    class="form-control @error('department') is-invalid @enderror"
                                    value="{{ old('department', $user->department) }}" placeholder="e.g. ICTS, Operations">
                                @error('department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Contact Number</label>
                                <input type="text" name="contact_number"
                                    class="form-control @error('contact_number') is-invalid @enderror"
                                    value="{{ old('contact_number', $user->contact_number) }}"
                                    placeholder="e.g. 09xxxxxxxxx">
                                @error('contact_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <div class="p-3 rounded-3" style="background:#f8fafc;border:1.5px solid #e2e8f0;">
                                    <div class="form-check form-switch mb-1">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                            value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                            style="width:2.5em;height:1.4em;">
                                        <label class="form-check-label fw-semibold ms-2" for="is_active">
                                            Active Account
                                        </label>
                                    </div>
                                    <small class="text-muted ps-1">
                                        Uncheck to prevent this user from logging in without deleting their account.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="d-flex align-items-center gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-circle me-1"></i> Save Changes
                            </button>
                            <a href="{{ route('super-admin.users.show', $user) }}"
                                class="btn btn-outline-secondary px-4">Cancel</a>
                            <span class="text-muted ms-auto" style="font-size:.78rem;">
                                <i class="bi bi-clock me-1"></i>
                                Last updated {{ $user->updated_at->diffForHumans() }}
                            </span>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ── FORM 2: Set Password ── --}}
            <div class="card card-section" style="border: 1.5px solid #fde68a;">
                <div class="card-body">
                    <div class="section-divider">
                        <div class="section-icon" style="background:#fffbeb;color:#d97706;">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <div>
                            <div class="section-title" style="color:#d97706;">Set New Password</div>
                            <div class="section-sub">Override this user's current password</div>
                        </div>
                    </div>

                    <div class="warning-box mb-4">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Only fill this section if you want to change the user's password.
                        Leave both fields blank to keep the existing password unchanged.
                    </div>

                    <form method="POST" action="{{ route('super-admin.users.update', $user) }}">
                        @csrf
                        @method('PUT')
                        {{-- Pass through required fields --}}
                        <input type="hidden" name="name" value="{{ $user->name }}">
                        <input type="hidden" name="email" value="{{ $user->email }}">
                        <input type="hidden" name="role" value="{{ $user->role }}">
                        <input type="hidden" name="department" value="{{ $user->department }}">
                        <input type="hidden" name="contact_number" value="{{ $user->contact_number }}">
                        <input type="hidden" name="is_active" value="{{ $user->is_active ? '1' : '0' }}">
                        <input type="hidden" name="password_only" value="1">

                        <div class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label">
                                    New Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" id="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Min. 8 characters" autocomplete="new-password">
                                    <button type="button" class="btn" onclick="togglePassword('password','eye1')">
                                        <i class="bi bi-eye" id="eye1"></i>
                                    </button>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label">
                                    Confirm Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="form-control" placeholder="Re-enter new password"
                                        autocomplete="new-password">
                                    <button type="button" class="btn"
                                        onclick="togglePassword('password_confirmation','eye2')">
                                        <i class="bi bi-eye" id="eye2"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn w-100"
                                    style="background:var(--ocd-orange);color:#fff;border:none;border-radius:8px;padding:.5rem;">
                                    <i class="bi bi-key me-1"></i> Set Password
                                </button>
                            </div>
                        </div>

                        {{-- Password strength indicator --}}
                        <div class="mt-3" id="strengthWrapper" style="display:none;">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <small class="text-muted">Password strength:</small>
                                <small id="strengthLabel" class="fw-semibold"></small>
                            </div>
                            <div class="progress" style="height:5px;border-radius:4px;">
                                <div id="strengthBar" class="progress-bar" style="width:0%;transition:width .3s;"></div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function togglePassword(fieldId, iconId) {
            const f = document.getElementById(fieldId);
            const i = document.getElementById(iconId);
            f.type = f.type === 'password' ? 'text' : 'password';
            i.classList.toggle('bi-eye');
            i.classList.toggle('bi-eye-slash');
        }

        // Password strength meter
        document.getElementById('password').addEventListener('input', function() {
            const val = this.value;
            const wrap = document.getElementById('strengthWrapper');
            const bar = document.getElementById('strengthBar');
            const lbl = document.getElementById('strengthLabel');

            if (!val) {
                wrap.style.display = 'none';
                return;
            }
            wrap.style.display = 'block';

            let score = 0;
            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            const levels = [{
                    pct: '25%',
                    color: '#ef4444',
                    label: 'Weak'
                },
                {
                    pct: '50%',
                    color: '#f97316',
                    label: 'Fair'
                },
                {
                    pct: '75%',
                    color: '#eab308',
                    label: 'Good'
                },
                {
                    pct: '100%',
                    color: '#22c55e',
                    label: 'Strong'
                },
            ];
            const lvl = levels[score - 1] || levels[0];
            bar.style.width = lvl.pct;
            bar.style.backgroundColor = lvl.color;
            lbl.textContent = lvl.label;
            lbl.style.color = lvl.color;
        });
    </script>
@endpush
