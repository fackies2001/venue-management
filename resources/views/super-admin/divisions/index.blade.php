@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="page-title mb-0">Division & Service Management</h3>
        </div>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        Add New Division
                    </div>
                    <div class="card-body">
                        <form action="{{ route('super-admin.divisions.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Division Name</label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn text-white w-100" style="background: var(--ocd-orange);">
                                <i class="bi bi-plus-circle me-1"></i> Add Division
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Active Divisions
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Name</th>
                                        <th>Users</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($divisions as $division)
                                        <tr>
                                            <td class="ps-4 fw-medium">{{ $division->name }}</td>
                                            <td>
                                                <span class="badge bg-secondary rounded-pill">
                                                    {{ $division->users()->count() }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <form action="{{ route('super-admin.divisions.destroy', $division->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this division?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4 text-muted">
                                                No divisions found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
