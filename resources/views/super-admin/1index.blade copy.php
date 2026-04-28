@extends('layouts.app')

@section('title', 'User Management')
@section('page-title', 'User Management')

@section('content')
    <div class="page-header">
        <h1><i class="bi bi-people me-2"></i>User Management</h1>
    </div>

    {{-- Filters --}}
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-auto">
                    <label class="form-label small mb-1">Role</label>
                    <select name="role" class="form-select form-select-sm">
                        <option value="">All Roles</option>
                        @foreach (['user' => 'Regular User', 'ndrrmoc_admin' => 'NDRRMOC Admin', 'nab_admin' => 'NAB Admin', 'super_admin' => 'Super Admin'] as $val => $label)
                            <option value="{{ $val }}" {{ request('role') === $val ? 'selected' : '' }}>
                                {{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <label class="form-label small mb-1">Search</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Name or email"
                        value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button class="btn btn-sm btn-outline-primary">Search</button>
                    <a href="{{ route('super-admin.users.index') }}" class="btn btn-sm btn-outline-secondary ms-1">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td class="text-muted small">{{ $user->id }}</td>
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td class="small">{{ $user->email }}</td>
                                <td class="small">{{ $user->department ?? '—' }}</td>
                                <td>
                                    @php
                                        $roleLabels = [
                                            'user' => ['Regular User', 'bg-light text-dark border'],
                                            'ndrrmoc_admin' => ['NDRRMOC Admin', 'bg-primary text-white'],
                                            'nab_admin' => ['NAB Admin', 'bg-info text-dark'],
                                            'super_admin' => ['Super Admin', 'bg-dark text-white'],
                                        ];
                                        [$label, $cls] = $roleLabels[$user->role] ?? [
                                            $user->role,
                                            'bg-secondary text-white',
                                        ];
                                    @endphp
                                    <span class="badge {{ $cls }} px-2 py-1">{{ $label }}</span>
                                </td>
                                <td>
                                    @if ($user->is_active)
                                        <span class="badge" style="background:#d1e7dd;color:#0f5132;">Active</span>
                                    @else
                                        <span class="badge" style="background:#fff3cd;color:#856404;">Inactive</span>
                                    @endif
                                </td>
                                <td class="small text-muted">{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('super-admin.users.edit', $user) }}"
                                            class="btn btn-sm btn-outline-primary py-0">Edit</a>

                                        @if ($user->is_active)
                                            <form method="POST"
                                                action="{{ route('super-admin.users.deactivate', $user) }}">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-sm btn-outline-warning py-0"
                                                    onclick="return confirm('Deactivate this user?')">Deactivate</button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('super-admin.users.activate', $user) }}">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-sm btn-outline-success py-0"
                                                    onclick="return confirm('Activate this user?')">Activate</button>
                                            </form>
                                        @endif

                                        @if ($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('super-admin.users.destroy', $user) }}"
                                                onsubmit="return confirm('Delete this user permanently?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger py-0">Delete</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($users->hasPages())
            <div class="card-footer bg-white">{{ $users->links() }}</div>
        @endif
    </div>
@endsection
