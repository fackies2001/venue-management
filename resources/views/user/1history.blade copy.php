@extends('layouts.app')

@section('title', 'History')
@section('page-title', 'Booking History')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
    <div class="page-header">
        <h1><i class="bi bi-clock-history me-2"></i> Booking History</h1>
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

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="historyTable" class="table table-hover align-middle mb-0 w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Event Title</th>
                            <th>Venue</th>
                            <th>Event Date</th>
                            <th>Status</th>
                            <th>Processed</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Binago dito: Ginawang regular foreach --}}
                        @foreach ($history as $booking)
                            <tr>
                                <td class="text-muted small">{{ $booking->id }}</td>
                                <td>{{ $booking->event_title }}</td>
                                <td>{{ $booking->venue->name }}</td>
                                <td>{{ $booking->event_date->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge {{ $booking->statusBadgeClass() }} px-2 py-1">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td class="small text-muted">
                                    {{ $booking->approved_at ? $booking->approved_at->format('M d, Y') : '—' }}
                                </td>
                            </tr>
                        @endforeach
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
            $('#historyTable').DataTable({
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                order: [
                    [0, 'desc']
                ],
                columnDefs: [{
                    orderable: false,
                    targets: []
                }],
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        previous: "Previous",
                        next: "Next"
                    },
                    // Dinagdag ito para DataTables na ang bahala mag-display kung walang records
                    emptyTable: "No history records found."
                }
            });
        });
    </script>
@endpush
