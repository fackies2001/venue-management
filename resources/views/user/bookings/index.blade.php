@extends('layouts.app')

@section('title', 'My Bookings')
@section('page-title', 'Venue Booking List')

@push('styles')
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
            line-height: 1;
        }

        table.dataTable thead th.sorting_asc .sort-icon .up {
            color: #ffffff !important;
        }

        table.dataTable thead th.sorting_desc .sort-icon .down {
            color: #ffffff !important;
        }
    </style>
@endpush

@section('content')
    <div class="page-header d-flex align-items-center justify-content-between">
        <h1><i class="bi bi-journal-text me-2"></i>My Bookings</h1>
        <a href="{{ route('user.bookings.create') }}" class="btn btn-sm" style="background:#e87722;color:#fff;border:none;">
            <i class="bi bi-plus-lg me-1"></i> New Booking
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="bookingsTable" class="table table-hover align-middle mb-0 w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>#<span class="sort-icon"><span class="up">▲</span><span class="down">▼</span></span>
                            </th>
                            <th>Event Title<span class="sort-icon"><span class="up">▲</span><span
                                        class="down">▼</span></span></th>
                            <th>Venue<span class="sort-icon"><span class="up">▲</span><span
                                        class="down">▼</span></span></th>
                            <th>Date<span class="sort-icon"><span class="up">▲</span><span
                                        class="down">▼</span></span></th>
                            <th>Time<span class="sort-icon"><span class="up">▲</span><span
                                        class="down">▼</span></span></th>
                            <th>Status<span class="sort-icon"><span class="up">▲</span><span
                                        class="down">▼</span></span></th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td class="text-muted small">{{ $booking->id }}</td>
                                <td class="fw-semibold">{{ $booking->event_title }}</td>
                                <td>{{ $booking->venue->name }}</td>
                                <td data-order="{{ $booking->event_date->format('Y-m-d') }}">
                                    {{ $booking->event_date->format('M d, Y') }}
                                </td>
                                <td class="small text-muted">
                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}
                                    – {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                                </td>
                                <td>
                                    <span class="badge {{ $booking->statusBadgeClass() }} px-2 py-1">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('user.bookings.show', $booking) }}"
                                            class="btn btn-sm btn-outline-primary py-0">View</a>

                                        @if ($booking->isPending())
                                            <a href="{{ route('user.bookings.edit', $booking) }}"
                                                class="btn btn-sm btn-outline-success py-0">Edit</a>

                                            <form method="POST" action="{{ route('user.bookings.cancel', $booking) }}"
                                                class="d-inline" onsubmit="return confirm('Cancel this booking?')">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-sm btn-outline-warning py-0">Cancel</button>
                                            </form>

                                            <form method="POST" action="{{ route('user.bookings.destroy', $booking) }}"
                                                class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this booking? This cannot be undone.')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger py-0">Delete</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            {{-- DataTables ang mag-hahandle ng empty state --}}
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#bookingsTable').DataTable({
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                order: [
                    [0, 'desc']
                ],
                columnDefs: [{
                    orderable: false,
                    targets: [6]
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
                }
            });
        });
    </script>
@endpush
