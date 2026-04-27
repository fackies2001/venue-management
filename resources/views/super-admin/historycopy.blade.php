{{-- @extends('layouts.app')

@section('title', 'History')
@section('page-title', 'Booking History') --}}

{{-- @push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        table.dataTable thead th.sorting::before,
        table.dataTable thead th.sorting::after,
        table.dataTable thead th.sorting_asc::before,
        table.dataTable thead th.sorting_asc::after,
        table.dataTable thead th.sorting_desc::before,
        table.dataTable thead th.sorting_desc::after {
            display: none !important;
            content: '' !important;
        }

        table.dataTable thead th .sort-icon {
            display: inline-flex;
            flex-direction: column;
            line-height: 1;
            margin-left: 6px;
            vertical-align: middle;
        }

        table.dataTable thead th .sort-icon .up,
        table.dataTable thead th .sort-icon .down {
            color: rgba(255, 255, 255, 0.35);
            font-size: 13px;
            font-weight: 900;
            line-height: 1;
        }

        table.dataTable thead th.sorting_asc .sort-icon .up {
            color: #ffffff !important;
        }

        table.dataTable thead th.sorting_desc .sort-icon .down {
            color: #ffffff !important;
        }

        tbody tr {
            cursor: pointer;
        }

        .modal-header {
            background: #1a3c72;
            color: #fff;
        }

        .detail-label {
            font-size: .75rem;
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
        }

        .detail-value {
            font-size: .95rem;
        }
    </style>
@endpush --}}

@section('content')
    <div class="page-header">
        <h1><i class="bi bi-clock-history me-2"></i>Booking History</h1>
    </div>

    {{-- Filters --}}
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-auto">
                    <label class="form-label small mb-1">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All</option>
                        @foreach (['approved', 'rejected', 'cancelled', 'completed'] as $s)
                            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <label class="form-label small mb-1">From</label>
                    <input type="date" name="date_from" class="form-control form-control-sm"
                        value="{{ request('date_from') }}">
                </div>
                <div class="col-auto">
                    <label class="form-label small mb-1">To</label>
                    <input type="date" name="date_to" class="form-control form-control-sm"
                        value="{{ request('date_to') }}">
                </div>
                <div class="col-auto">
                    <button class="btn btn-sm btn-outline-primary">Filter</button>
                    <a href="{{ request()->url() }}" class="btn btn-sm btn-outline-secondary ms-1">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- History Table --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="historyTable" class="table table-hover align-middle mb-0 w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>#<span class="sort-icon"><span class="up">▲</span><span class="down">▼</span></span>
                            </th>
                            @if (auth()->user()->isAdmin())
                                <th>Requested By<span class="sort-icon"><span class="up">▲</span><span
                                            class="down">▼</span></span></th>
                            @endif
                            <th>Event Title<span class="sort-icon"><span class="up">▲</span><span
                                        class="down">▼</span></span></th>
                            <th>Venue<span class="sort-icon"><span class="up">▲</span><span
                                        class="down">▼</span></span></th>
                            <th>Event Date<span class="sort-icon"><span class="up">▲</span><span
                                        class="down">▼</span></span></th>
                            <th>Status<span class="sort-icon"><span class="up">▲</span><span
                                        class="down">▼</span></span></th>
                            <th>Processed<span class="sort-icon"><span class="up">▲</span><span
                                        class="down">▼</span></span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($history as $booking)
                            {{-- Clickable Row --}}
                            <tr data-bs-toggle="modal" data-bs-target="#detailModal{{ $booking->id }}">
                                <td class="text-muted small">{{ $booking->id }}</td>
                                @if (auth()->user()->isAdmin())
                                    <td>
                                        <div class="small fw-semibold">{{ $booking->user->name }}</div>
                                        <div class="text-muted" style="font-size:.75rem;">
                                            {{ $booking->user->department ?? '' }}
                                        </div>
                                    </td>
                                @endif
                                <td>{{ $booking->event_title }}</td>
                                <td>{{ $booking->venue->name }}</td>
                                <td data-order="{{ $booking->event_date->format('Y-m-d') }}">
                                    {{ $booking->event_date->format('M d, Y') }}
                                </td>
                                <td>
                                    <span class="badge {{ $booking->statusBadgeClass() }} px-2 py-1">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td class="small text-muted">
                                    {{ $booking->approved_at ? $booking->approved_at->format('M d, Y') : '—' }}
                                </td>
                            </tr>

                            {{-- Detail Modal --}}
                            <div class="modal fade" id="detailModal{{ $booking->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">

                                        {{-- Modal Header --}}
                                        <div class="modal-header">
                                            <div>
                                                <h5 class="modal-title mb-0">
                                                    <i class="bi bi-journal-text me-2"></i>Booking Details —
                                                    #{{ $booking->id }}
                                                </h5>
                                                <small class="opacity-75">{{ $booking->event_title }}</small>
                                            </div>
                                            <button type="button" class="btn-close btn-close-white"
                                                data-bs-dismiss="modal"></button>
                                        </div>

                                        {{-- Modal Body --}}
                                        <div class="modal-body p-0">

                                            {{-- Top Info Bar --}}
                                            <div class="d-flex align-items-center justify-content-between px-4 py-2"
                                                style="background:#f8f9fa; border-bottom:1px solid #dee2e6;">
                                                <div class="small text-muted">
                                                    <i class="bi bi-clock me-1"></i>
                                                    Booked on {{ $booking->created_at->format('M d, Y h:i A') }}
                                                </div>
                                                <span class="badge {{ $booking->statusBadgeClass() }} px-3 py-2">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </div>

                                            <div class="px-4 py-3">

                                                {{-- Section: Requester Info --}}
                                                <p class="text-uppercase fw-bold small text-muted mb-2"
                                                    style="letter-spacing:.05em;">
                                                    <i class="bi bi-person me-1"></i>Requester Info
                                                </p>
                                                <div class="row g-3 mb-3">
                                                    <div class="col-md-6">
                                                        <div class="detail-label">Booked By</div>
                                                        <div class="detail-value fw-semibold">
                                                            {{ $booking->user->name }}
                                                            <span class="text-muted small fw-normal">(User ID:
                                                                {{ $booking->user->id }})</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="detail-label">Service</div>
                                                        <div class="detail-value">{{ $booking->service ?? '—' }}</div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="detail-label">Division</div>
                                                        <div class="detail-value">{{ $booking->division ?? '—' }}</div>
                                                    </div>
                                                </div>

                                                <hr class="my-3">

                                                {{-- Section: Event Details --}}
                                                <p class="text-uppercase fw-bold small text-muted mb-2"
                                                    style="letter-spacing:.05em;">
                                                    <i class="bi bi-calendar-event me-1"></i>Event Details
                                                </p>
                                                <div class="row g-3 mb-3">
                                                    <div class="col-md-6">
                                                        <div class="detail-label">Venue</div>
                                                        <div class="detail-value fw-semibold">{{ $booking->venue->name }}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="detail-label">Event Date & Time</div>
                                                        <div class="detail-value">
                                                            {{ $booking->event_date->format('M d, Y') }}
                                                            &nbsp;
                                                            <span class="text-muted">
                                                                {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}
                                                                –
                                                                {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="detail-label">Agenda / Topic</div>
                                                        <div class="detail-value">{{ $booking->agenda ?? '—' }}</div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="detail-label">Number of People</div>
                                                        <div class="detail-value">
                                                            <i
                                                                class="bi bi-people me-1 text-muted"></i>{{ $booking->expected_attendees }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr class="my-3">

                                                {{-- Section: Additional Info --}}
                                                <p class="text-uppercase fw-bold small text-muted mb-2"
                                                    style="letter-spacing:.05em;">
                                                    <i class="bi bi-info-circle me-1"></i>Additional Info
                                                </p>
                                                <div class="row g-3">
                                                    <div class="col-12">
                                                        <div class="detail-label">Remarks</div>
                                                        <div class="detail-value">{{ $booking->remarks ?? '—' }}</div>
                                                    </div>

                                                    @if ($booking->admin_remarks)
                                                        <div class="col-12">
                                                            <div class="p-3 rounded"
                                                                style="background:#fff5f5; border-left: 4px solid #dc3545;">
                                                                <div class="detail-label text-danger">
                                                                    <i class="bi bi-exclamation-circle me-1"></i>Admin
                                                                    Remarks
                                                                </div>
                                                                <div class="detail-value text-danger">
                                                                    {{ $booking->admin_remarks }}</div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <div class="col-12">
                                                        <div class="detail-label">Attachment</div>
                                                        <div class="detail-value">
                                                            @if ($booking->attachment_path)
                                                                <a href="{{ asset('storage/' . $booking->attachment_path) }}"
                                                                    target="_blank"
                                                                    class="btn btn-sm btn-outline-primary">
                                                                    <i class="bi bi-paperclip me-1"></i>View Attachment
                                                                </a>
                                                            @else
                                                                <span class="text-muted">N/A</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        {{-- Modal Footer --}}
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary btn-sm"
                                                data-bs-dismiss="modal">
                                                <i class="bi bi-x me-1"></i>Close
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            {{-- End Modal --}}

                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">No history records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

{{-- @push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#historyTable').DataTable({
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                order: [
                    [0, 'desc']
                ],
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        previous: "Previous",
                        next: "Next"
                    }
                }
            });
        });
    </script>
@endpush --}}
