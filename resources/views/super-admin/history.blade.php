@extends('layouts.app')

@section('title', 'History')
@section('page-title', 'Booking History')

@push('styles')
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        #historyTable thead th {
            background: #212529;
            color: #fff;
            border-color: #373b3e;
        }

        div.dataTables_wrapper div.dataTables_length label,
        div.dataTables_wrapper div.dataTables_filter label,
        div.dataTables_wrapper div.dataTables_info {
            font-size: .875rem;
            color: #6c757d;
        }

        div.dataTables_wrapper div.dataTables_filter input {
            margin-left: .5rem;
        }

        .btn-view-detail {
            font-size: .78rem;
            padding: .2rem .55rem;
        }

        /* Modal detail rows */
        .detail-label {
            width: 38%;
            color: #6c757d;
            font-weight: 600;
            font-size: .88rem;
        }

        .detail-value {
            font-size: .9rem;
        }

        .detail-row {
            display: flex;
            padding: .55rem 0;
            border-bottom: 1px solid #f1f3f5;
        }

        .detail-row:last-child {
            border-bottom: none;
        }
    </style>
@endpush

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center">
        <h1><i class="bi bi-clock-history me-2"></i>Booking History</h1>
    </div>

    {{-- Filters --}}
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-auto">
                    <label class="form-label small mb-1">Status</label>
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All</option>
                        @foreach (['approved', 'rejected', 'cancelled'] as $s)
                            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <label class="form-label small mb-1">Date From</label>
                    <input type="date" name="date_from" class="form-control form-control-sm"
                        value="{{ request('date_from') }}">
                </div>
                <div class="col-auto">
                    <label class="form-label small mb-1">Date To</label>
                    <input type="date" name="date_to" class="form-control form-control-sm"
                        value="{{ request('date_to') }}">
                </div>
                <div class="col-auto">
                    <button class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                    <a href="{{ request()->url() }}" class="btn btn-sm btn-outline-secondary ms-1">
                        <i class="bi bi-x-circle me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="historyTable" class="table table-hover align-middle w-100 mb-0">
                    <thead>
                        <tr>
                            <th>#</th>

                            <th>Event Title</th>
                            <th>Venue</th>
                            <th>Event Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Processed On</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($history as $booking)
                            <tr>
                                <td>{{ $booking->id }}</td>
                                {{-- <td>
                                    <div class="fw-semibold small">{{ $booking->user->name }}</div>
                                    <div class="text-muted" style="font-size:.75rem;">
                                        {{ $booking->user->department ?? '—' }}
                                    </div>
                                </td> --}}
                                <td>{{ $booking->event_title }}</td>
                                <td>{{ $booking->venue->name }}</td>
                                <td data-order="{{ $booking->event_date->timestamp }}">
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
                                <td data-order="{{ $booking->approved_at ? $booking->approved_at->timestamp : 0 }}"
                                    class="small text-muted">
                                    {{ $booking->approved_at ? $booking->approved_at->format('M d, Y') : '—' }}
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary btn-view-detail"
                                        onclick="showDetail({{ $booking->id }})" title="View Details">
                                        <i class="bi bi-eye me-1"></i>View
                                    </button>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ══ Booking Detail Modal ══ --}}
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background:var(--ocd-blue);color:#fff;">
                    <h5 class="modal-title">
                        <i class="bi bi-calendar-event me-2"></i>
                        Booking Details — <span id="modalEventTitle"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <div class="row g-3">
                        {{-- Left column --}}
                        <div class="col-md-6">
                            <p class="fw-bold mb-2"
                                style="color:var(--ocd-blue);font-size:.8rem;text-transform:uppercase;letter-spacing:.05em;">
                                <i class="bi bi-calendar3 me-1"></i> Event Info
                            </p>
                            <div class="detail-row">
                                <div class="detail-label">Event Title</div>
                                <div class="detail-value" id="dEventTitle"></div>
                            </div>

                            <div class="detail-row">
                                <div class="detail-label">Venue</div>
                                <div class="detail-value" id="dVenue"></div>
                            </div>

                            <div class="detail-row">
                                <div class="detail-label">Event Date</div>
                                <div class="detail-value" id="dDate"></div>
                            </div>

                            <div class="detail-row">
                                <div class="detail-label">Time</div>
                                <div class="detail-value" id="dTime"></div>
                            </div>

                            <div class="detail-row">
                                <div class="detail-label">Agenda / Topic</div>
                                <div class="detail-value" id="dAgenda"></div>
                            </div>

                            <div class="detail-row">
                                <div class="detail-label">Participants</div>
                                <div class="detail-value" id="dParticipants"></div>
                            </div>

                            <div class="detail-row">
                                <div class="detail-label">Remarks</div>
                                <div class="detail-value" id="dRemarks"></div>
                            </div>

                            <div class="detail-row">
                                <div class="detail-label">Attachment</div>
                                <div class="detail-value" id="dAttachment"></div>
                            </div>
                        </div>

                        {{-- Right column --}}
                        <div class="col-md-6">
                            <p class="fw-bold mb-2"
                                style="color:var(--ocd-blue);font-size:.8rem;text-transform:uppercase;letter-spacing:.05em;">
                                <i class="bi bi-person me-1"></i> Requester Info
                            </p>
                            <div class="detail-row">
                                <div class="detail-label">Name</div>
                                <div class="detail-value" id="dBooker"></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Department</div>
                                <div class="detail-value" id="dDepartment"></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Email</div>
                                <div class="detail-value" id="dEmail"></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Phone</div>
                                <div class="detail-value" id="dPhone"></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Service</div>
                                <div class="detail-value" id="dService"></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Division</div>
                                <div class="detail-value" id="dDivision"></div>
                            </div>

                            <p class="fw-bold mb-2 mt-3"
                                style="color:var(--ocd-blue);font-size:.8rem;text-transform:uppercase;letter-spacing:.05em;">
                                <i class="bi bi-check-circle me-1"></i> Processing Info
                            </p>
                            <div class="detail-row">
                                <div class="detail-label">Status</div>
                                <div class="detail-value" id="dStatus"></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Processed On</div>
                                <div class="detail-value" id="dProcessed"></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Admin Remarks</div>
                                <div class="detail-value" id="dAdminRemarks"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Pass booking data to JS --}}
    @php
        $bookingsJson = $history->map(
            fn($b) => [
                'id' => $b->id,
                'event_title' => $b->event_title,
                'venue' => $b->venue->name,
                'event_date' => $b->event_date->format('F d, Y'),
                'start_time' => \Carbon\Carbon::parse($b->start_time)->format('h:i A'),
                'end_time' => \Carbon\Carbon::parse($b->end_time)->format('h:i A'),
                'agenda' => $b->agenda ?? '—',
                'expected_attendees' => $b->expected_attendees ?? '—',
                'remarks' => $b->remarks ?? '—',
                'booker_name' => $b->booker_name ?? $b->user->name,
                'department' => $b->user->department ?? '—',
                'email' => $b->email ?? $b->user->email,
                'phone' => $b->phone ?? '—',
                'service' => $b->service ?? '—',
                'division' => $b->division ?? '—',
                'status' => ucfirst($b->status),
                'status_class' => $b->statusBadgeClass(),
                'approved_at' => $b->approved_at ? $b->approved_at->format('M d, Y h:i A') : '—',
                'admin_remarks' => $b->admin_remarks ?? '—',
                'attachment_path' => $b->attachment_path ? Storage::url($b->attachment_path) : null,
                'attachment_name' => $b->attachment_path ? basename($b->attachment_path) : null,
            ],
        );
    @endphp

    <script>
        const bookingsData = @json($bookingsJson);
    </script>

@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#historyTable').DataTable({
                destroy: true, // ← dagdag ito
                order: [
                    [6, 'desc']
                ], // ← i-change sa column 6 (Processed On)
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                columnDefs: [{
                    orderable: false,
                    targets: -1
                }],
                language: {
                    search: 'Search:',
                    lengthMenu: 'Show _MENU_ entries',
                    info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                    emptyTable: 'No history records found.',
                    paginate: {
                        previous: 'Previous',
                        next: 'Next'
                    }
                }
            });
        });


        function showDetail(id) {
            const b = bookingsData.find(x => x.id === id);
            if (!b) return;

            document.getElementById('modalEventTitle').textContent = b.event_title;
            document.getElementById('dEventTitle').textContent = b.event_title;
            document.getElementById('dVenue').textContent = b.venue;
            document.getElementById('dDate').textContent = b.event_date;
            document.getElementById('dTime').textContent = b.start_time + ' – ' + b.end_time;
            document.getElementById('dAgenda').textContent = b.agenda;
            document.getElementById('dParticipants').textContent = b.expected_attendees;
            document.getElementById('dRemarks').textContent = b.remarks;
            document.getElementById('dBooker').textContent = b.booker_name;
            document.getElementById('dDepartment').textContent = b.department;
            document.getElementById('dEmail').textContent = b.email;
            document.getElementById('dPhone').textContent = b.phone;
            document.getElementById('dService').textContent = b.service;
            document.getElementById('dDivision').textContent = b.division;
            document.getElementById('dStatus').innerHTML =
                `<span class="badge ${b.status_class} px-2 py-1">${b.status}</span>`;
            document.getElementById('dProcessed').textContent = b.approved_at;
            document.getElementById('dAdminRemarks').textContent = b.admin_remarks;

            // Attachment
            const attachEl = document.getElementById('dAttachment');
            if (b.attachment_path) {
                attachEl.innerHTML = `<a href="${b.attachment_path}" target="_blank">
        <i class="bi bi-paperclip me-1"></i>View Attachment</a>`;
            } else {
                attachEl.textContent = '—';
            }

            new bootstrap.Modal(document.getElementById('detailModal')).show();
        }
    </script>
@endpush
