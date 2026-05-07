@extends('layouts.app')

@section('title', 'User Profile — ' . $user->name)

@push('styles')
    <style>
        .profile-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .main-profile-card {
            border-radius: 12px;
            border: none;
            overflow: hidden;
            background: #fff;
        }

        /* Consistent Sidebar Dark Navy */
        .profile-header-bg {
            background: var(--ocd-dark);
            padding: 3.5rem 2rem;
            position: relative;
        }

        .profile-avatar-wrapper {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            background: #fff;
            padding: 5px;
            position: absolute;
            bottom: -65px;
            left: 50px;
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
            font-size: 3.5rem;
            font-weight: 700;
            color: #fff;
        }

        .header-content-offset {
            margin-top: 80px;
            padding: 0 50px 30px;
        }

        .data-section {
            padding: 1.5rem 0;
            border-bottom: 1px solid #f4f6f9;
        }

        .data-section:last-child {
            border-bottom: none;
        }

        .data-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #94a3b8;
            font-weight: 800;
            margin-bottom: 0.4rem;
        }

        .data-value {
            font-size: 0.95rem;
            color: var(--ocd-dark);
            font-weight: 600;
        }

        /* Consistent Sidebar Orange Badge */
        .status-pill {
            padding: 0.35rem 0.9rem;
            border-radius: 4px;
            font-weight: 700;
            font-size: 0.7rem;
            letter-spacing: 0.5px;
        }

        /* Action Buttons */
        .btn-edit-profile {
            background-color: #fff;
            color: var(--ocd-dark);
            border: 1px solid #e2e8f0;
            font-weight: 700;
        }

        .btn-edit-profile:hover {
            background-color: #f8fafc;
            color: var(--ocd-orange);
        }

        .btn-deactivate {
            background-color: var(--ocd-orange);
            color: #fff;
            border: none;
            font-weight: 700;
        }

        .btn-deactivate:hover {
            background-color: #d66a1e;
            color: #fff;
        }
    </style>
@endpush

@section('content')
    <div class="profile-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h5 fw-bold mb-0 text-dark" style="letter-spacing: -0.5px;">Account Overview</h2>
            <a href="{{ route('super-admin.users.index') }}" class="text-decoration-none text-muted small fw-bold">
                <i class="bi bi-arrow-left me-1"></i> BACK TO USER DIRECTORY
            </a>
        </div>

        <div class="card main-profile-card shadow-sm">
            {{-- Consistent Dark Navy Header --}}
            <div class="profile-header-bg text-end">
                <div class="profile-avatar-wrapper">
                    <div class="profile-avatar-inner">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                </div>
                {{-- Professional Actions --}}
                <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('super-admin.users.edit', $user) }}"
                        class="btn btn-edit-profile btn-sm px-4 shadow-sm">
                        <i class="bi bi-pencil me-2"></i>Edit Profile
                    </a>
                    @if ($user->is_active)
                        <button onclick="confirmDeactivate()" class="btn btn-deactivate btn-sm px-4 shadow-sm">
                            <i class="bi bi-shield-lock me-2"></i>Deactivate
                        </button>
                    @else
                        <button onclick="confirmActivate()" class="btn btn-success btn-sm px-4 shadow-sm">
                            <i class="bi bi-shield-check me-2"></i>Activate
                        </button>
                    @endif
                </div>
            </div>

            <div class="header-content-offset">
                <div class="row align-items-end mb-4 pt-2">
                    <div class="col">
                        <h2 class="fw-bold text-dark mb-0">{{ $user->name }}</h2>
                        <p class="text-muted small mb-0">{{ $user->email }}</p>
                    </div>
                    <div class="col-auto">
                        <span
                            class="status-pill {{ $user->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                            <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>
                            {{ $user->is_active ? 'ACTIVE ACCOUNT' : 'INACTIVE' }}
                        </span>
                    </div>
                </div>

                <div class="row g-4 mt-2">
                    <div class="col-md-6 border-end">
                        <div class="data-section">
                            <label class="data-label">Security Role</label>
                            <div class="data-value">
                                <i class="bi bi-shield-check text-primary me-2"></i>
                                {{ strtoupper(str_replace('_', ' ', $user->role)) }}
                            </div>
                        </div>
                        <div class="data-section">
                            <label class="data-label">Assigned Division</label>
                            <div class="data-value">
                                <i class="bi bi-building text-primary me-2"></i>
                                {{ $user->division->name ?? 'Not Assigned' }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 ps-md-4">
                        <div class="data-section">
                            <label class="data-label">Registration Date</label>
                            <div class="data-value text-muted">
                                <i class="bi bi-calendar3 me-2"></i>
                                {{ $user->created_at->format('M d, Y') }} ({{ $user->created_at->diffForHumans() }})
                            </div>
                        </div>
                        <div class="data-section">
                            <label class="data-label">Last Login Activity</label>
                            <div class="data-value {{ $user->last_login_at ? 'text-primary' : 'text-danger' }}">
                                <i class="bi bi-clock-history me-2"></i>
                                {{ $user->last_login_at ? $user->last_login_at->format('M d, Y | h:i A') : 'No History Recorded' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-top text-center">
                    <p class="text-muted" style="font-size: 0.7rem;">Internal Record ID: #{{ $user->id }} | Office of
                        Civil Defense Venue Management System</p>
                    @if ($user->role !== 'super_admin')
                        <button onclick="confirmDelete()"
                            class="btn btn-link text-danger text-decoration-none fw-bold small mt-1">
                            <i class="bi bi-trash3 me-1"></i> Delete Permanent Record
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
