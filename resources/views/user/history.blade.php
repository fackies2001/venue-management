@extends('layouts.app')

@section('title', 'History')
@section('page-title', 'Booking History')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        #historyTable thead th {
            background: #212529;
            color: #fff;
            border-color: #373b3e;
        }

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
    <div class="page-header">
        <h1><i class="bi bi-clock-history me-2"></i> Booking History</h1>
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

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="historyTable" class="table table-hover align-middle mb-0 w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Event Title</th>
                            <th>Venue</th>
                            <th>Event Date</th>
                            <th>Status</th>
                            <th>Approved By</th>
                            <th>Processed</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($history as $booking)
                            <tr>
                                <td class="text-muted small">{{ $booking->id }}</td>
                                <td>{{ $booking->event_title }}</td>
                                <td>
                                    {{ $booking->venue->name ?? 'Deleted Venue' }}
                                    @if ($booking->venue && $booking->venue->room_floor)
                                        <span class="text-muted small">({{ $booking->venue->room_floor }})</span>
                                    @endif
                                </td>
                                <td>{{ $booking->event_date->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge {{ $booking->statusBadgeClass() }} px-2 py-1">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td class="small text-muted">
                                    {{ $booking->approvedBy->name ?? '—' }}
                                </td>
                                <td class="small text-muted">
                                    {{ $booking->approved_at ? $booking->approved_at->format('M d, Y') : '—' }}
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary"
                                        onclick="showDetail({{ $booking->id }})" title="View Details">
                                        <i class="bi bi-eye me-1"></i>View
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{--  Booking Detail Modal --}}
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius:16px; overflow:hidden;">

                <div class="modal-header px-4 py-3" style="background:var(--ocd-dark, #0a1144); border:none;">
                    <div class="d-flex align-items-center gap-2">
                        <div style="background:rgba(232,119,34,0.2); border-radius:8px; padding:6px 10px;">
                            <i class="bi bi-calendar-event" style="color:#e87722; font-size:1.1rem;"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-white fw-bold">Booking Details</h6>
                            <small style="color:rgba(255,255,255,0.55);" id="modalEventTitle"></small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="px-4 py-2 d-flex align-items-center gap-2"
                    style="background:#f0f4ff; border-bottom:1px solid #e2e8f0;">
                    <span
                        style="font-size:.72rem; font-weight:700; color:#6c757d; text-transform:uppercase; letter-spacing:.04em;">STATUS</span>
                    <span id="dStatus" class="ms-2"></span>
                    <span class="ms-auto text-muted" style="font-size:.78rem;">
                        <i class="bi bi-clock me-1"></i>Processed: <span id="dProcessed"></span>
                    </span>
                </div>

                <div class="modal-body px-4 py-3" style="background:#fff;">
                    <div class="row g-3">

                        {{-- LEFT: Event Info --}}
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 h-100" style="background:#f8f9fa; border:1px solid #e9ecef;">
                                <p class="fw-bold mb-3"
                                    style="color:var(--ocd-blue,#1a3c72); font-size:.78rem; text-transform:uppercase; letter-spacing:.05em;">
                                    <i class="bi bi-calendar3 me-1"></i>Event Info
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
                                {{--  Attachment row --}}
                                <div class="detail-row" style="border-bottom:none;">
                                    <div class="detail-label">Attachment</div>
                                    <div class="detail-value" id="dAttachment"></div>
                                </div>
                            </div>
                        </div>

                        {{-- RIGHT: Processing Info --}}
                        <div class="col-md-6 d-flex flex-column gap-3">
                            <div class="p-3 rounded-3" style="background:#f8f9fa; border:1px solid #e9ecef;">
                                <p class="fw-bold mb-3"
                                    style="color:var(--ocd-blue,#1a3c72); font-size:.78rem; text-transform:uppercase; letter-spacing:.05em;">
                                    <i class="bi bi-check-circle me-1"></i>Processing Info
                                </p>
                                <div class="detail-row">
                                    <div class="detail-label">Approved By</div>
                                    <div class="detail-value" id="dApprovedBy"></div>
                                </div>
                                <div class="detail-row" style="border-bottom:none;">
                                    <div class="detail-label">Admin Remarks</div>
                                    <div class="detail-value" id="dAdminRemarks"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer px-4 py-2" style="background:#f8f9fa; border-top:1px solid #e9ecef;">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Close
                    </button>
                </div>

            </div>
        </div>
    </div>
    {{--  END detailModal --}}

    {{--  Attachment Preview Modal --}}
    <div class="modal fade" id="attachmentModal" tabindex="-1" style="z-index:1060;">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius:16px; overflow:hidden;">
                <div class="modal-header px-4 py-3" style="background:var(--ocd-dark, #0a1144); border:none;">
                    <div class="d-flex align-items-center gap-2">
                        <div style="background:rgba(232,119,34,0.2); border-radius:8px; padding:6px 10px;">
                            <i class="bi bi-paperclip" style="color:#e87722; font-size:1.1rem;"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-white fw-bold">Attachment Preview</h6>
                            <small style="color:rgba(255,255,255,0.55);" id="attachmentFileName"></small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3" id="attachmentModalBody" style="background:#f8f9fa; min-height:200px;"></div>
                <div class="modal-footer px-4 py-2" style="background:#f8f9fa; border-top:1px solid #e9ecef;">
                    <a id="attachmentDownloadBtn" href="#" download class="btn btn-sm btn-outline-primary px-3">
                        <i class="bi bi-download me-1"></i>Download
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-secondary px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{--  END attachmentModal --}}

    {{-- Pass booking data to JS --}}
    @php
        $bookingsJson = $history->map(
            fn($b) => [
                'id' => $b->id,
                'event_title' => $b->event_title,
                'venue' => $b->venue->name ?? 'Deleted Venue',
                'event_date' => $b->event_date->format('F d, Y'),
                'start_time' => \Carbon\Carbon::parse($b->start_time)->format('h:i A'),
                'end_time' => \Carbon\Carbon::parse($b->end_time)->format('h:i A'),
                'agenda' => $b->agenda ?? '—',
                'expected_attendees' => $b->expected_attendees ?? '—',
                'remarks' => $b->remarks ?? '—',
                'status' => ucfirst($b->status),
                'status_class' => $b->statusBadgeClass(),
                'approved_by' => $b->approvedBy->name ?? '—',
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
    <script src="https://cdn.jsdelivr.net/npm/mammoth@1.6.0/mammoth.browser.min.js"></script>
    <script src="https://cdn.sheetjs.com/xlsx-0.20.0/package/dist/xlsx.full.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#historyTable').DataTable({
                destroy: true,
                order: [
                    [6, 'desc']
                ],
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                columnDefs: [{
                    orderable: false,
                    targets: -1
                }],
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        previous: "Previous",
                        next: "Next"
                    },
                    emptyTable: "No history records found."
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
            document.getElementById('dStatus').innerHTML =
                `<span class="badge ${b.status_class} px-2 py-1">${b.status}</span>`;
            document.getElementById('dApprovedBy').textContent = b.approved_by;
            document.getElementById('dProcessed').textContent = b.approved_at;
            document.getElementById('dAdminRemarks').textContent = b.admin_remarks;

            //  Attachment handler
            const attachEl = document.getElementById('dAttachment');
            if (b.attachment_path && b.attachment_name) {
                attachEl.innerHTML = `
                    <a href="#" onclick="openAttachmentPreview('${b.attachment_path}', '${b.attachment_name}', '${b.attachment_name.split('.').pop().toLowerCase()}'); return false;"
                        class="text-primary" style="font-size:.9rem; text-decoration:none;">
                        <i class="bi bi-paperclip me-1"></i>View Attachment
                    </a>`;
            } else {
                attachEl.textContent = '—';
            }

            //  FIXED: modal.show() is INSIDE showDetail, not outside
            new bootstrap.Modal(document.getElementById('detailModal')).show();
        }

        function openAttachmentPreview(filePath, fileName, ext) {
            document.getElementById('attachmentFileName').textContent = fileName;
            document.getElementById('attachmentDownloadBtn').href = filePath;

            const body = document.getElementById('attachmentModalBody');
            body.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted small">Loading...</p>
                </div>`;

            if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
                body.innerHTML = `
                    <div class="text-center">
                        <img src="${filePath}" alt="${fileName}"
                            style="max-width:100%; max-height:70vh; border-radius:8px; object-fit:contain;">
                    </div>`;
            } else if (ext === 'pdf') {
                body.innerHTML = `
                    <iframe src="${filePath}#toolbar=0" width="100%"
                        style="height:70vh; border:none; border-radius:8px;"></iframe>`;
            } else if (['doc', 'docx'].includes(ext)) {
                body.innerHTML = `
                    <div id="mammoth-attach-output"
                        style="background:#fff; border-radius:8px; padding:1rem;
                               max-height:70vh; overflow-y:auto; font-size:.9rem; line-height:1.7;">
                        <div class="text-muted small text-center py-3">
                            <i class="bi bi-hourglass-split me-1"></i>Rendering document...
                        </div>
                    </div>`;
                fetch(filePath)
                    .then(res => res.arrayBuffer())
                    .then(buffer => mammoth.convertToHtml({
                        arrayBuffer: buffer
                    }))
                    .then(result => {
                        document.getElementById('mammoth-attach-output').innerHTML =
                            result.value || '<p class="text-muted text-center">No content to preview.</p>';
                    })
                    .catch(() => {
                        document.getElementById('mammoth-attach-output').innerHTML = `
                            <div class="text-center py-4 text-danger">
                                <i class="bi bi-exclamation-circle display-4"></i>
                                <p class="mt-2">Could not render preview. Please download the file.</p>
                            </div>`;
                    });
            } else if (['xls', 'xlsx'].includes(ext)) {
                body.innerHTML = `
                    <div id="xlsx-preview-output"
                        style="background:#fff; border-radius:8px; padding:1rem;
                               max-height:70vh; overflow:auto; font-size:.88rem;">
                        <div class="text-muted small text-center py-3">
                            <i class="bi bi-hourglass-split me-1"></i>Rendering spreadsheet...
                        </div>
                    </div>`;
                fetch(filePath)
                    .then(res => res.arrayBuffer())
                    .then(buffer => {
                        const workbook = XLSX.read(buffer, {
                            type: 'array'
                        });
                        const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                        const html = XLSX.utils.sheet_to_html(firstSheet, {
                            header: '',
                            footer: ''
                        });
                        document.getElementById('xlsx-preview-output').innerHTML = `
                            <style>
                                #xlsx-preview-output table { border-collapse:collapse; width:100%; font-size:.82rem; }
                                #xlsx-preview-output td, #xlsx-preview-output th {
                                    border:1px solid #dee2e6; padding:.35rem .6rem; white-space:nowrap;
                                }
                                #xlsx-preview-output tr:nth-child(even) { background:#f8f9fa; }
                                #xlsx-preview-output tr:first-child { background:#212529; color:#fff; font-weight:600; }
                            </style>
                            ${html}`;
                    })
                    .catch(() => {
                        document.getElementById('xlsx-preview-output').innerHTML = `
                            <div class="text-center py-4 text-danger">
                                <i class="bi bi-exclamation-circle display-4"></i>
                                <p class="mt-2">Could not render preview. Please download the file.</p>
                            </div>`;
                    });
            } else {
                body.innerHTML = `
                    <div class="text-center py-5">
                        <i class="bi bi-file-earmark-x display-1 text-muted"></i>
                        <p class="mt-3 text-muted">No preview available for .${ext} files.</p>
                        <p class="small text-muted">Please use the download button below.</p>
                    </div>`;
            }

            bootstrap.Modal.getInstance(document.getElementById('detailModal'))?.hide();
            setTimeout(() => {
                new bootstrap.Modal(document.getElementById('attachmentModal')).show();
            }, 300);
        }
    </script>
@endpush
