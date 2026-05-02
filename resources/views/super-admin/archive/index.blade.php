{{-- resources/views/super-admin/archive/index.blade.php --}}
@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
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
            white-space: nowrap;
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

        /* Modal detail rows */
        #logModal .modal-header {
            background: var(--ocd-dark, #0a1144);
            color: #fff;
        }

        #logModal .detail-label {
            font-size: 0.78rem;
            font-weight: 700;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        #logModal .detail-value {
            font-size: 0.92rem;
            color: #212529;
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
                            <select name="action" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">All Actions</option>
                                @foreach (['created', 'updated', 'approved', 'rejected', 'cancelled', 'deleted', 'archived', 'restored', 'permanently_deleted'] as $act)
                                    <option value="{{ $act }}" {{ request('action') === $act ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $act)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="date" name="date_from" class="form-control form-control-sm"
                                value="{{ request('date_from') }}">
                        </div>
                        <div class="col-sm-2">
                            <input type="date" name="date_to" class="form-control form-control-sm"
                                value="{{ request('date_to') }}">
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
                            {{--  CHANGED: Added id="logsTable", removed IP Address column --}}
                            <table id="logsTable" class="table table-hover log-table align-middle w-100">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Performed By</th>
                                        <th>Description</th>
                                        <th>Date & Time</th>
                                        <th>Status</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($logs as $log)
                                        <tr>
                                            <td class="text-muted small text-center">{{ $log->id }}</td>
                                            <td>
                                                <div class="fw-semibold">{{ $log->user?->name ?? 'System' }}</div>
                                                <small class="text-muted text-capitalize">
                                                    {{ str_replace('_', ' ', $log->user?->role ?? '') }}
                                                </small>
                                            </td>
                                            <td>{{ $log->description }}</td>
                                            <td data-order="{{ $log->created_at->format('Y-m-d H:i:s') }}">
                                                <div>{{ $log->created_at->format('M d, Y') }}</div>
                                                <small class="text-muted">{{ $log->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                <span class="action-badge {{ $log->actionBadgeClass() }} text-white">
                                                    {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary py-0 px-2 btn-view-log"
                                                    data-id="{{ $log->id }}"
                                                    data-action="{{ ucfirst(str_replace('_', ' ', $log->action)) }}"
                                                    data-badge="{{ $log->actionBadgeClass() }}"
                                                    data-performer="{{ $log->user?->name ?? 'System' }}"
                                                    data-role="{{ ucfirst(str_replace('_', ' ', $log->user?->role ?? '')) }}"
                                                    data-description="{{ $log->description }}"
                                                    data-date="{{ $log->created_at->format('M d, Y') }}"
                                                    data-time="{{ $log->created_at->format('h:i A') }}"
                                                    data-properties="{{ json_encode($log->properties ?? []) }}">
                                                    <i class="bi bi-eye me-1"></i>View
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{--  KEPT: Laravel pagination for server-side filtering --}}
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
                        {{--  CHANGED: Cards → Table (consistent with Manage Bookings) --}}
                        <div class="table-responsive">
                            <table id="deletedTable" class="table table-hover align-middle w-100">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Event Title</th>
                                        <th>Venue</th>
                                        <th>Date</th>
                                        <th>Booked By</th>
                                        <th>Division</th>
                                        <th>Status</th>
                                        <th>Deleted</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($deletedBookings as $booking)
                                        <tr>
                                            <td class="text-muted small text-center">{{ $booking->id }}</td>
                                            <td class="fw-semibold">{{ $booking->event_title }}</td>
                                            <td>
                                                {{ $booking->venue?->name ?? 'N/A' }}
                                                @if ($booking->venue?->room_floor)
                                                    <span
                                                        class="text-muted small">({{ $booking->venue->room_floor }})</span>
                                                @endif
                                            </td>
                                            <td data-order="{{ $booking->event_date?->format('Y-m-d') }}">
                                                {{ $booking->event_date?->format('M d, Y') ?? '—' }}
                                            </td>
                                            <td>{{ $booking->booker_name }}</td>
                                            <td>{{ $booking->division }}</td>
                                            <td class="text-center">
                                                <span class="badge {{ $booking->statusBadgeClass() }}">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </td>
                                            <td data-order="{{ $booking->deleted_at->format('Y-m-d H:i:s') }}">
                                                <small class="text-danger">
                                                    {{ $booking->deleted_at->diffForHumans() }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1 flex-wrap">
                                                    {{-- Restore --}}
                                                    <form method="POST"
                                                        action="{{ route('super-admin.archive.restore', $booking->id) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-success py-0 px-2">
                                                            <i class="bi bi-arrow-counterclockwise me-1"></i>Restore
                                                        </button>
                                                    </form>
                                                    {{-- Permanent Delete --}}
                                                    <form method="POST"
                                                        action="{{ route('super-admin.archive.force-delete', $booking->id) }}"
                                                        onsubmit="return confirm('Permanently delete this booking? This cannot be undone.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-danger py-0 px-2">
                                                            <i class="bi bi-trash3-fill me-1"></i>Delete Forever
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    {{-- REDESIGNED: Activity Log View Modal --}}
    <div class="modal fade" id="logModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">

                {{-- Header --}}
                <div class="modal-header px-4 py-3" style="background: var(--ocd-dark, #0a1144); border: none;">
                    <div class="d-flex align-items-center gap-2">
                        <div style="background: rgba(232,119,34,0.2); border-radius: 8px; padding: 6px 10px;">
                            <i class="bi bi-clock-history" style="color: #e87722; font-size: 1.1rem;"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-white fw-bold">Activity Log Detail</h6>
                            <small style="color: rgba(255,255,255,0.55);">Log <span id="modal-id">#—</span></small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                {{-- Status Banner --}}
                <div class="px-4 py-2 d-flex align-items-center gap-2"
                    style="background: #f0f4ff; border-bottom: 1px solid #e2e8f0;">
                    <span class="detail-label mb-0" style="font-size:.72rem;">STATUS</span>
                    <span id="modal-action-badge" class="action-badge text-white ms-2">—</span>
                </div>

                {{-- Body --}}
                <div class="modal-body px-4 py-3" style="background: #fff;">

                    {{-- Performed By + Date --}}
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <div class="p-3 rounded-3 h-100" style="background: #f8f9fa; border: 1px solid #e9ecef;">
                                <div class="detail-label mb-1">
                                    <i class="bi bi-person-fill me-1" style="color:#1a3c72;"></i>Performed By
                                </div>
                                <div class="fw-bold" style="font-size:.92rem; color:#212529;" id="modal-performer">—
                                </div>
                                <div class="text-muted" style="font-size:.78rem;" id="modal-role"></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-3 h-100" style="background: #f8f9fa; border: 1px solid #e9ecef;">
                                <div class="detail-label mb-1">
                                    <i class="bi bi-calendar3 me-1" style="color:#1a3c72;"></i>Date & Time
                                </div>
                                <div class="fw-bold" style="font-size:.92rem; color:#212529;" id="modal-date">—</div>
                                <div class="text-muted" style="font-size:.78rem;" id="modal-time"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="mb-3 p-3 rounded-3" style="background: #f8f9fa; border: 1px solid #e9ecef;">
                        <div class="detail-label mb-1">
                            <i class="bi bi-chat-left-text-fill me-1" style="color:#1a3c72;"></i>Description
                        </div>
                        <div style="font-size:.9rem; color:#212529;" id="modal-description">—</div>
                    </div>

                    {{-- Details / Properties --}}
                    <div class="p-3 rounded-3" style="background: #f8f9fa; border: 1px solid #e9ecef;">
                        <div class="detail-label mb-2">
                            <i class="bi bi-tag-fill me-1" style="color:#1a3c72;"></i>Details
                        </div>
                        <div id="modal-properties">—</div>
                    </div>

                </div>

                {{-- Footer --}}
                <div class="modal-footer px-4 py-2" style="background: #f8f9fa; border-top: 1px solid #e9ecef;">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-4"
                        data-bs-dismiss="modal">Close</button>
                </div>

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

            //  Activity Logs DataTable — sorting + search + show entries
            @if (!$logs->isEmpty())
                $('#logsTable').DataTable({
                    destroy: true,
                    pageLength: 10,
                    lengthMenu: [10, 25, 50, 100],
                    order: [
                        [0, 'desc']
                    ],
                    columnDefs: [{
                        orderable: false,
                        targets: -1 // View column not sortable
                    }],
                    language: {
                        search: "Search:",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        emptyTable: "No activity logs found.",
                        paginate: {
                            previous: "Previous",
                            next: "Next"
                        }
                    }
                });
            @endif

            //  Deleted Bookings DataTable
            @if (!$deletedBookings->isEmpty())
                $('#deletedTable').DataTable({
                    destroy: true,
                    pageLength: 10,
                    lengthMenu: [10, 25, 50, 100],
                    order: [
                        [0, 'desc']
                    ],
                    columnDefs: [{
                        orderable: false,
                        targets: -1 // Action column not sortable
                    }],
                    language: {
                        search: "Search:",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        emptyTable: "No deleted bookings found.",
                        paginate: {
                            previous: "Previous",
                            next: "Next"
                        }
                    }
                });
            @endif

            //  View Modal — populate data from button attributes
            $(document).on('click', '.btn-view-log', function() {
                const btn = $(this);

                $('#modal-id').text('#' + btn.data('id'));
                $('#modal-performer').text(btn.data('performer'));
                $('#modal-role').text(btn.data('role'));
                $('#modal-description').text(btn.data('description'));
                $('#modal-date').text(btn.data('date'));
                $('#modal-time').text(btn.data('time'));

                // Action badge
                const badge = $('#modal-action-badge');
                badge.text(btn.data('action'));
                badge.attr('class', 'action-badge text-white ' + btn.data('badge'));

                // Properties/Details
                const props = btn.data('properties');
                const propsContainer = $('#modal-properties');
                propsContainer.html('');

                if (props && Object.keys(props).length > 0) {
                    $.each(props, function(key, val) {
                        if (val) {
                            propsContainer.append(
                                `<span class="prop-badge"><strong>${key.charAt(0).toUpperCase() + key.slice(1)}:</strong> ${val}</span>`
                            );
                        }
                    });
                } else {
                    propsContainer.html('<span class="text-muted">—</span>');
                }

                new bootstrap.Modal(document.getElementById('logModal')).show();
            });

        });

        // ✅ ADDED: Auto-submit filters on change (Action dropdown + Date fields)
        document.querySelectorAll('select[name="action"], input[name="date_from"], input[name="date_to"]')
            .forEach(function(el) {
                el.addEventListener('change', function() {
                    this.closest('form').submit();
                });
            });
    </script>
@endpush
