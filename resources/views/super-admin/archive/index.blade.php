{{-- resources/views/super-admin/archive/index.blade.php --}}
@extends('layouts.app')

@push('styles')
    <style>
        .archive-tabs .nav-link {
            color: var(--ocd-blue);
            font-weight: 600;
            border-radius: 8px 8px 0 0;
        }

        .archive-tabs .nav-link.active {
            background: var(--ocd-blue);
            color: #fff;
            border-color: var(--ocd-blue);
        }

        .log-table td {
            vertical-align: middle;
            font-size: 0.875rem;
        }

        .action-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.3rem 0.6rem;
            border-radius: 20px;
        }

        .deleted-card {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            transition: box-shadow 0.2s;
        }

        .deleted-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .prop-badge {
            background: rgba(26, 60, 114, 0.08);
            color: var(--ocd-blue);
            font-size: 0.72rem;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            display: inline-block;
            margin: 2px;
        }
    </style>
@endpush

@section('content')
    <div class="d-flex align-items-center gap-3 mb-4">
        <i class="bi bi-archive-fill fs-3" style="color: var(--ocd-blue);"></i>
        <h2 class="fw-bold mb-0" style="color: var(--ocd-blue);">Archive</h2>
    </div>

    {{-- Tabs --}}
    <ul class="nav nav-tabs archive-tabs mb-0" id="archiveTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#activity-tab" type="button">
                <i class="bi bi-clock-history me-1"></i> Activity Logs
                <span class="badge bg-secondary ms-1">{{ $logs->total() }}</span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#deleted-tab" type="button">
                <i class="bi bi-trash3 me-1"></i> Deleted Bookings
                <span class="badge bg-danger ms-1">{{ $deletedBookings->count() }}</span>
            </button>
        </li>
    </ul>

    <div class="card border-top-0 rounded-0 rounded-bottom shadow-sm">
        <div class="card-body p-4">
            <div class="tab-content" id="archiveTabContent">

                {{-- ── ACTIVITY LOGS TAB ──────────────────────────── --}}
                <div class="tab-pane fade show active" id="activity-tab">

                    {{-- Filters --}}
                    <form method="GET" class="row g-2 mb-4">
                        <div class="col-sm-3">
                            <select name="action" class="form-select form-select-sm">
                                <option value="">All Actions</option>
                                @foreach (['created', 'updated', 'approved', 'rejected', 'cancelled', 'deleted', 'restored', 'permanently_deleted'] as $act)
                                    <option value="{{ $act }}" {{ request('action') === $act ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $act)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="date" name="date_from" class="form-control form-control-sm"
                                value="{{ request('date_from') }}" placeholder="From">
                        </div>
                        <div class="col-sm-2">
                            <input type="date" name="date_to" class="form-control form-control-sm"
                                value="{{ request('date_to') }}" placeholder="To">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="search" class="form-control form-control-sm"
                                value="{{ request('search') }}" placeholder="Search description...">
                        </div>
                        <div class="col-sm-2 d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-primary px-3">
                                <i class="bi bi-funnel me-1"></i>Filter
                            </button>
                            <a href="{{ route('super-admin.archive.index') }}" class="btn btn-sm btn-outline-secondary">
                                Reset
                            </a>
                        </div>
                    </form>

                    @if ($logs->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-clock-history fs-1 d-block mb-2"></i>
                            No activity logs found.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover log-table align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Action</th>
                                        <th>Performed By</th>
                                        <th>Description</th>
                                        <th>Details</th>
                                        <th>IP Address</th>
                                        <th>Date & Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($logs as $log)
                                        <tr>
                                            <td class="text-muted">{{ $log->id }}</td>
                                            <td>
                                                <span class="action-badge {{ $log->actionBadgeClass() }} text-white">
                                                    {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $log->user?->name ?? 'System' }}</div>
                                                <small class="text-muted text-capitalize">
                                                    {{ str_replace('_', ' ', $log->user?->role ?? '') }}
                                                </small>
                                            </td>
                                            <td>{{ $log->description }}</td>
                                            <td>
                                                @if ($log->properties)
                                                    @foreach ($log->properties as $key => $val)
                                                        @if ($val)
                                                            <span class="prop-badge">
                                                                <strong>{{ ucfirst($key) }}:</strong> {{ $val }}
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td><small class="text-muted">{{ $log->ip_address ?? '—' }}</small></td>
                                            <td>
                                                <div>{{ $log->created_at->format('M d, Y') }}</div>
                                                <small class="text-muted">{{ $log->created_at->format('h:i A') }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">
                                Showing {{ $logs->firstItem() }}–{{ $logs->lastItem() }} of {{ $logs->total() }} entries
                            </small>
                            {{ $logs->appends(request()->except('logs_page'))->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>

                {{-- ── DELETED BOOKINGS TAB ────────────────────────── --}}
                <div class="tab-pane fade" id="deleted-tab">

                    @if ($deletedBookings->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-trash3 fs-1 d-block mb-2"></i>
                            No deleted bookings found.
                        </div>
                    @else
                        <div class="row g-3 mt-1">
                            @foreach ($deletedBookings as $booking)
                                <div class="col-md-6 col-xl-4">
                                    <div class="deleted-card p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="fw-bold mb-0" style="color: var(--ocd-blue);">
                                                {{ $booking->event_title }}
                                            </h6>
                                            <span class="badge bg-danger">Deleted</span>
                                        </div>

                                        <div class="text-muted" style="font-size:0.8rem;">
                                            <div><i class="bi bi-geo-alt me-1"></i>{{ $booking->venue?->name ?? 'N/A' }}
                                            </div>
                                            <div><i
                                                    class="bi bi-calendar3 me-1"></i>{{ $booking->event_date?->format('M d, Y') }}
                                            </div>
                                            <div><i class="bi bi-person me-1"></i>{{ $booking->booker_name }}</div>
                                            <div><i class="bi bi-building me-1"></i>{{ $booking->division }}</div>
                                            <div class="mt-1">
                                                <span class="badge {{ $booking->statusBadgeClass() }}">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                                <span class="text-danger ms-2">
                                                    <i class="bi bi-trash3 me-1"></i>
                                                    Deleted {{ $booking->deleted_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="d-flex gap-2 mt-3">
                                            {{-- Restore --}}
                                            <form method="POST"
                                                action="{{ route('super-admin.archive.restore', $booking->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="bi bi-arrow-counterclockwise me-1"></i>Restore
                                                </button>
                                            </form>

                                            {{-- Permanent Delete --}}
                                            <form method="POST"
                                                action="{{ route('super-admin.archive.force-delete', $booking->id) }}"
                                                onsubmit="return confirm('Permanently delete this booking? This cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash3-fill me-1"></i>Delete Forever
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection
