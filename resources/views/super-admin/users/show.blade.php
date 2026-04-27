@extends('layouts.app')

@section('title', 'User Details — ' . $user->name)
@section('page-title', 'User Details')

@push('styles')
    <style>
        .avatar-circle {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: var(--ocd-blue);
            color: #fff;
            font-size: 2.2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 4px 12px rgba(26, 60, 114, .25);
        }

        .info-row {
            display: flex;
            align-items: flex-start;
            padding: .65rem 0;
            border-bottom: 1px solid #f1f3f5;
            font-size: .9rem;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            width: 175px;
            flex-shrink: 0;
            color: #6c757d;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: .45rem;
        }

        .info-value {
            color: #212529;
        }

        .stat-card {
            border-radius: 10px;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }
    </style>
@endpush

@section('content')

    {{-- Header --}}
    <div class="page-header d-flex justify-content-between align-items-center">
        <h1><i class="bi bi-person-circle me-2"></i>User Details</h1>
        <a href="{{ route('super-admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Users
        </a>
    </div>

    @php
        $roleColors = [
            \App\Models\User::ROLE_SUPER_ADMIN => 'danger',
            \App\Models\User::ROLE_NDRRMOC => 'warning',
            \App\Models\User::ROLE_NAB => 'info',
            \App\Models\User::ROLE_USER => 'secondary',
        ];
        $color = $roleColors[$user->role] ?? 'secondary';

        $roleLabels = [
            \App\Models\User::ROLE_SUPER_ADMIN => 'Super Admin',
            \App\Models\User::ROLE_NDRRMOC => 'NDRRMOC',
            \App\Models\User::ROLE_NAB => 'NAB',
            \App\Models\User::ROLE_USER => 'User',
        ];
        $roleLabel = $roleLabels[$user->role] ?? strtoupper($user->role);
    @endphp

    <div class="row g-4">

        {{-- ── LEFT COLUMN ── --}}
        <div class="col-lg-4 col-md-5">

            {{-- Profile Card --}}
            <div class="card mb-4">
                <div class="card-body text-center pt-4 pb-3 px-3">
                    <div class="avatar-circle">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <h5 class="fw-bold mb-0">{{ $user->name }}</h5>
                    <p class="text-muted small mb-2">{{ $user->email }}</p>
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <span class="badge bg-{{ $color }} px-3 py-2">{{ $roleLabel }}</span>
                        @if ($user->is_active)
                            <span class="badge badge-approved px-3 py-2">Active</span>
                        @else
                            <span class="badge badge-rejected px-3 py-2">Inactive</span>
                        @endif
                    </div>
                    @if ($user->department)
                        <p class="text-muted small mb-3">
                            <i class="bi bi-building me-1"></i>{{ $user->department }}
                        </p>
                    @endif
                </div>
                <div class="card-footer bg-transparent px-3 pb-3 pt-0">
                    <div class="d-flex flex-column gap-2">

                        {{-- Edit --}}
                        <a href="{{ route('super-admin.users.edit', $user) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil me-1"></i> Edit User
                        </a>

                        {{-- Deactivate / Activate --}}
                        @if ($user->is_active)
                            <form method="POST" id="deactivateForm"
                                action="{{ route('super-admin.users.deactivate', $user) }}">
                                @csrf @method('PATCH')
                                <button type="button" class="btn btn-outline-secondary btn-sm w-100"
                                    onclick="confirmDeactivate()">
                                    <i class="bi bi-person-dash me-1"></i> Deactivate
                                </button>
                            </form>
                        @else
                            <form method="POST" id="activateForm"
                                action="{{ route('super-admin.users.activate', $user) }}">
                                @csrf @method('PATCH')
                                <button type="button" class="btn btn-outline-success btn-sm w-100"
                                    onclick="confirmActivate()">
                                    <i class="bi bi-person-check me-1"></i> Activate
                                </button>
                            </form>
                        @endif

                        {{-- Delete --}}
                        <form method="POST" id="deleteForm" action="{{ route('super-admin.users.destroy', $user) }}">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="confirmDelete()">
                                <i class="bi bi-trash me-1"></i> Delete User
                            </button>
                        </form>

                    </div>
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-bar-chart-line me-1"></i> Quick Info
                </div>
                <div class="card-body p-3 d-flex flex-column gap-2">
                    <div class="stat-card" style="background:#eef2ff;">
                        <div class="stat-icon" style="background:#c7d2fe;color:#3730a3;">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Member Since</div>
                            <div class="fw-semibold" style="font-size:.9rem;">
                                {{ $user->created_at->format('M d, Y') }}
                            </div>
                        </div>
                    </div>
                    <div class="stat-card" style="background:#f0fdf4;">
                        <div class="stat-icon" style="background:#bbf7d0;color:#15803d;">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Last Updated</div>
                            <div class="fw-semibold" style="font-size:.9rem;">
                                {{ $user->updated_at->format('M d, Y') }}
                            </div>
                        </div>
                    </div>
                    <div class="stat-card" style="background:#fff7ed;">
                        <div class="stat-icon" style="background:#fed7aa;color:#c2410c;">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Account Status</div>
                            <div class="fw-semibold" style="font-size:.9rem;">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── RIGHT COLUMN ── --}}
        <div class="col-lg-8 col-md-7">

            {{-- Account Information --}}
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="bi bi-person-vcard" style="color:var(--ocd-blue);"></i>
                    Account Information
                </div>
                <div class="card-body px-4 py-2">
                    <div class="info-row">
                        <div class="info-label"><i class="bi bi-person"></i> Full Name</div>
                        <div class="info-value fw-semibold">{{ $user->name }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="bi bi-envelope"></i> Email Address</div>
                        <div class="info-value">{{ $user->email }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="bi bi-shield"></i> Role</div>
                        <div class="info-value">
                            <span class="badge bg-{{ $color }}">{{ $roleLabel }}</span>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="bi bi-building"></i> Department</div>
                        <div class="info-value">{{ $user->department ?? '—' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="bi bi-telephone"></i> Contact Number</div>
                        <div class="info-value">{{ $user->contact_number ?? '—' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="bi bi-toggle-on"></i> Status</div>
                        <div class="info-value">
                            @if ($user->is_active)
                                <span class="badge badge-approved">Active</span>
                            @else
                                <span class="badge badge-rejected">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Activity Timestamps --}}
            <div class="card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="bi bi-clock" style="color:var(--ocd-blue);"></i>
                    Activity Log
                </div>
                <div class="card-body px-4 py-2">
                    <div class="info-row">
                        <div class="info-label"><i class="bi bi-calendar-plus"></i> Registered</div>
                        <div class="info-value">
                            {{ $user->created_at->format('F d, Y') }}
                            <span class="text-muted small ms-2">{{ $user->created_at->format('h:i A') }}</span>
                            <span class="badge bg-light text-muted ms-2 small">
                                {{ $user->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="bi bi-pencil-square"></i> Last Updated</div>
                        <div class="info-value">
                            {{ $user->updated_at->format('F d, Y') }}
                            <span class="text-muted small ms-2">{{ $user->updated_at->format('h:i A') }}</span>
                            <span class="badge bg-light text-muted ms-2 small">
                                {{ $user->updated_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="bi bi-box-arrow-in-right"></i> Last Login</div>
                        <div class="info-value">
                            @if ($user->last_login_at)
                                {{ $user->last_login_at->format('F d, Y') }}
                                <span class="text-muted small ms-2">{{ $user->last_login_at->format('h:i A') }}</span>
                                <span class="badge bg-light text-muted ms-2 small">
                                    {{ $user->last_login_at->diffForHumans() }}
                                </span>
                            @else
                                <span class="text-muted">Never logged in</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ── Deactivate ──────────────────────────────────────────
        function confirmDeactivate() {
            Swal.fire({
                title: 'Deactivate User?',
                html: `Are you sure you want to deactivate <strong>{{ addslashes($user->name) }}</strong>?<br>
                       <span style="font-size:.875rem;color:#6c757d;">They will no longer be able to log in.</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6c757d',
                cancelButtonColor: '#1a3c72',
                confirmButtonText: '<i class="bi bi-person-dash me-1"></i> Yes, Deactivate',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deactivateForm').submit();
                }
            });
        }

        // ── Activate ────────────────────────────────────────────
        function confirmActivate() {
            Swal.fire({
                title: 'Activate User?',
                html: `Are you sure you want to activate <strong>{{ addslashes($user->name) }}</strong>?<br>
                       <span style="font-size:.875rem;color:#6c757d;">They will be able to log in again.</span>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-person-check me-1"></i> Yes, Activate',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('activateForm').submit();
                }
            });
        }

        // ── Delete ──────────────────────────────────────────────
        function confirmDelete() {
            Swal.fire({
                title: 'Delete User?',
                html: `You are about to permanently delete <strong>{{ addslashes($user->name) }}</strong>.<br>
                       <span style="font-size:.875rem;color:#dc3545;">This action cannot be undone.</span>`,
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-trash me-1"></i> Yes, Delete',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm').submit();
                }
            });
        }

        // ── Toast: Flash Messages ───────────────────────────────
        @if (session('success'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        @endif

        @if (session('error'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
            });
        @endif
    </script>
@endpush
