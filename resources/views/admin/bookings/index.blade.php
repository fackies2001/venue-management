@extends('layouts.app')

@section('title', 'Manage Bookings')
@section('page-title', 'Manage Bookings')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')

    @php
        $prefix = match (auth()->user()->role) {
            'admin' => 'admin', // ← ADD THIS LINE
            'ndrrmoc_admin' => 'ndrrmoc',
            'nab_admin' => 'nab',
            'super_admin' => 'super-admin',
            default => 'user',
        };
    @endphp

    <div class="page-header d-flex align-items-center justify-content-between">
        <h1><i class="bi bi-journal-check me-2"></i>Manage Bookings</h1>
    </div>

    {{-- Filters --}}
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-auto">
                    <label class="form-label small mb-1">Status</label>
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All</option>
                        @foreach (['pending', 'approved', 'rejected', 'cancelled'] as $s)
                            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <label class="form-label small mb-1">Venue</label>
                    <select name="venue_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Venues</option>
                        @foreach ($venues as $venue)
                            {{-- ✅ FIXED: Idinagdag ang Room/Floor sa Dropdown --}}
                            <option value="{{ $venue->id }}" {{ request('venue_id') == $venue->id ? 'selected' : '' }}>
                                {{ $venue->name }} {{ $venue->room_floor ? '(' . $venue->room_floor . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn btn-sm btn-outline-primary">Filter</button>
                    <a href="{{ request()->url() }}" class="btn btn-sm btn-outline-secondary ms-1">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Bookings Table --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="bookingsTable" class="table table-hover align-middle mb-0 w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Requested By</th>
                            <th>Event Title</th>
                            <th>Venue</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Attendees</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td class="text-muted small">{{ $booking->id }}</td>
                                <td>
                                    <div class="fw-semibold small">{{ $booking->user->name }}</div>
                                    <div class="text-muted" style="font-size:.75rem;">
                                        {{ $booking->user->department ?? '' }}
                                    </div>
                                </td>
                                <td>{{ $booking->event_title }}</td>

                                {{-- ✅ FIXED: Idinagdag ang Room/Floor display at null-safe check --}}
                                <td>
                                    {{ $booking->venue->name ?? 'Deleted Venue' }}
                                    @if ($booking->venue && $booking->venue->room_floor)
                                        <span class="text-muted small">({{ $booking->venue->room_floor }})</span>
                                    @endif
                                </td>

                                <td data-order="{{ $booking->event_date->format('Y-m-d') }}">
                                    {{ $booking->event_date->format('M d, Y') }}
                                </td>
                                <td class="small text-muted">
                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}
                                    – {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                                </td>
                                <td>{{ $booking->expected_attendees }}</td>
                                <td>
                                    <span class="badge {{ $booking->statusBadgeClass() }} px-2 py-1">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1 flex-wrap">
                                        <a href="{{ route($prefix . '.bookings.show', $booking) }}"
                                            class="btn btn-sm btn-outline-primary py-0 px-2">
                                            <i class="bi bi-eye me-1"></i>View
                                        </a>
                                        <a href="{{ route($prefix . '.bookings.edit', $booking) }}"
                                            class="btn btn-sm btn-outline-success py-0 px-2">
                                            <i class="bi bi-pencil me-1"></i>Edit
                                        </a>

                                        @if ($booking->isPending())
                                            <form method="POST"
                                                action="{{ route($prefix . '.bookings.approve', $booking) }}"
                                                class="d-inline" onsubmit="return confirm('Approve this booking?')">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success py-0 px-2">
                                                    <i class="bi bi-check-lg me-1"></i>Approve
                                                </button>
                                            </form>
                                            <button class="btn btn-sm btn-warning py-0 px-2 text-dark"
                                                data-bs-toggle="modal" data-bs-target="#rejectModal{{ $booking->id }}">
                                                <i class="bi bi-x-lg me-1"></i>Reject
                                            </button>
                                        @endif

                                        <form method="POST" action="{{ route($prefix . '.bookings.destroy', $booking) }}"
                                            class="d-inline"
                                            onsubmit="return confirm('Completely delete this booking? This cannot be undone.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2">
                                                <i class="bi bi-trash me-1"></i>Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ═══ Reject Modals — LABAS ng table ═══ --}}
    @foreach ($bookings as $booking)
        @if ($booking->isPending())
            <div class="modal fade" id="rejectModal{{ $booking->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content" style="border-radius:14px;overflow:hidden;border:none;">
                        <div class="modal-header" style="background:#1a3c72;color:#fff;border:none;">
                            <h5 class="modal-title fw-bold">
                                <i class="bi bi-x-circle me-2"></i>Reject Booking
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="{{ route($prefix . '.bookings.reject', $booking) }}">
                            @csrf @method('PATCH')
                            <div class="modal-body p-4">
                                <p class="text-muted mb-3" style="font-size:.875rem;">
                                    Please provide a reason for rejecting
                                    <strong>{{ $booking->event_title }}</strong>.
                                    This will be sent to the requester via email.
                                </p>
                                <label class="form-label fw-semibold small">
                                    Reason for Rejection <span class="text-danger">*</span>
                                </label>
                                <textarea name="admin_remarks" class="form-control" rows="3" placeholder="e.g. Venue not available on this date…"
                                    required style="border-radius:8px;font-size:.9rem;"></textarea>
                            </div>
                            <div class="modal-footer" style="border-top:1px solid #f0f2f5;background:#fafbfc;">
                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-sm btn-danger fw-semibold px-3">
                                    <i class="bi bi-x-circle me-1"></i>Confirm Reject
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#bookingsTable').DataTable({
                destroy: true,
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                order: [
                    [0, 'desc']
                ],
                columnDefs: [{
                    orderable: false,
                    targets: -1
                }],
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    emptyTable: "No bookings found.",
                    paginate: {
                        previous: "Previous",
                        next: "Next"
                    }
                },
                drawCallback: function() {
                    if (this.api().rows().count() === 0) {
                        $(this.api().table().node()).find('tbody tr td[colspan]')
                            .removeAttr('style');
                    }
                }
            });
        });
    </script>
@endpush
