@extends('layouts.app')

@section('title', 'My Bookings')
@section('page-title', 'Venue Booking List')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        /* Reset table to prevent unwanted stretching */
        #bookingsTable {
            width: 100% !important;
            table-layout: auto !important;
        }

        /* Target the Action column specifically */
        .col-action {
            width: 180px !important;
            /* Tightened the width */
            min-width: 180px !important;
        }

        /* Wrapper to align buttons to the right with a margin */
        .action-right-wrapper {
            display: flex;
            gap: 4px;
            justify-content: flex-end;
            /* Pushes buttons to the right */
            align-items: center;
            padding-right: 15px;
            /* Keeps it from hitting the very edge */
        }

        /* Reduce button padding to save space */
        .btn-action-sm {
            padding: 2px 8px !important;
            font-size: 0.75rem !important;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 3px;
        }

        /* Ensure Title and Venue take up the remaining space */
        .col-event-title {
            width: 20%;
        }

        .col-venue-name {
            width: 20%;
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
                            <th class="text-center" style="width: 50px;">#</th>
                            <th class="text-start">Requested By</th>
                            <th class="text-start col-event-title">Event Title</th>
                            <th class="text-start col-venue-name">Venue</th>
                            <th class="text-center" style="width: 120px;">Date</th>
                            <th class="text-center" style="width: 100px;">Status</th>
                            <th class="text-end col-action" style="padding-right: 23px !important;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td class="text-muted small text-center">{{ $booking->id }}</td>
                                <td class="fw-semibold">{{ $booking->event_title }}</td>

                                <td>
                                    {{ $booking->venue->name ?? 'Deleted Venue' }}
                                    @if ($booking->venue && $booking->venue->room_floor)
                                        <span class="text-muted small">({{ $booking->venue->room_floor }})</span>
                                    @endif
                                </td>

                                <td class="text-center" data-order="{{ $booking->event_date->format('Y-m-d') }}">
                                    {{ $booking->event_date->format('M d, Y') }}
                                </td>

                                <td class="small text-muted text-center">
                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}
                                    – {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                                </td>

                                <td class="text-center">
                                    <span class="badge {{ $booking->statusBadgeClass() }} px-2 py-1">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>

                                {{-- UPDATED: Align to the right with padding for breathing room --}}
                                <td class="text-end">
                                    <div class="action-right-wrapper">
                                        <a href="{{ route('user.bookings.show', $booking) }}"
                                            class="btn btn-action-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> View
                                        </a>

                                        @if ($booking->isPending())
                                            <a href="{{ route('user.bookings.edit', $booking) }}"
                                                class="btn btn-action-sm btn-outline-success">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>

                                            <form method="POST" action="{{ route('user.bookings.cancel', $booking) }}"
                                                class="d-inline" onsubmit="return confirm('Cancel this booking?')">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-action-sm btn-outline-warning">
                                                    <i class="bi bi-x-circle"></i> Cancel
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            {{-- Handled by DataTables --}}
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTables for the Venue Booking List
            $('#bookingsTable').DataTable({
                // Disable autoWidth to allow our custom CSS classes to control column widths
                autoWidth: false,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],

                // Sort by the first column (ID) in descending order by default
                order: [
                    [0, 'desc']
                ],

                // Define specific alignment for columns
                columnDefs: [{
                        // Targets: # (0), Date (4), Status (5)
                        targets: [0, 4, 5],
                        className: 'text-center'
                    },
                    {
                        // Target: Requested By (1), Event Title (2), Venue (3)
                        targets: [1, 2, 3],
                        className: 'text-start'
                    },
                    {
                        // Target: Action (6)
                        // We use 'text-end' to push content to the right
                        // 'pe-4' adds a balanced padding so it is not touching the border
                        targets: [6],
                        className: 'text-end pe-4',
                        orderable: false, // Actions should not be sortable
                        searchable: false // Actions should not be searchable
                    }
                ],

                // Customizing the interface text
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
