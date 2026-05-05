@extends('layouts.app')

@section('title', 'Booking Details')
@section('page-title', 'Booking Details')

@push('styles')
    <style>
        .booking-detail-card {
            max-width: 780px;
            border-radius: 16px !important;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(26, 60, 114, .10) !important;
            margin-left: auto;
            margin-right: auto;
        }

        .booking-card-header {
            background: linear-gradient(135deg, var(--ocd-blue) 0%, #244e96 100%);
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .booking-card-header .event-title-block .label {
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .09em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, .55);
            margin-bottom: .2rem;
        }

        .booking-card-header .event-title-block .title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.3;
        }

        .status-pill {
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
            padding: .45rem 1.1rem;
            border-radius: 50px;
            white-space: nowrap;
        }

        .status-pill.bg-warning {
            background: #ffc107 !important;
            color: #5c3d00 !important;
        }

        .status-pill.bg-success {
            background: #28a745 !important;
            color: #fff !important;
        }

        .status-pill.bg-danger {
            background: #dc3545 !important;
            color: #fff !important;
        }

        .status-pill.bg-secondary {
            background: #6c757d !important;
            color: #fff !important;
        }

        .status-pill.bg-info {
            background: #17a2b8 !important;
            color: #fff !important;
        }

        .booking-body {
            padding: 1.75rem 2rem;
            background: #fff;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
        }

        .detail-item {
            padding: .9rem 1rem;
            border-bottom: 1px solid #f0f2f5;
            display: flex;
            flex-direction: column;
            gap: .25rem;
        }

        .detail-item:nth-child(odd) {
            border-right: 1px solid #f0f2f5;
        }

        .detail-item .di-label {
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #9aa5b4;
        }

        .detail-item .di-value {
            font-size: .925rem;
            font-weight: 500;
            color: #1c2a3a;
        }

        .detail-item .di-value .bi {
            color: var(--ocd-blue);
            margin-right: .3rem;
        }

        .detail-item.full-width {
            grid-column: 1 / -1;
            border-right: none;
        }

        .remarks-box {
            background: #fff8f0;
            border-left: 4px solid var(--ocd-orange);
            border-radius: 0 8px 8px 0;
            padding: .85rem 1.1rem;
            margin-top: 1.25rem;
        }

        .remarks-box .rm-label {
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--ocd-orange);
            margin-bottom: .3rem;
        }

        .remarks-box .rm-text {
            font-size: .9rem;
            color: #3d2200;
            font-weight: 500;
        }

        .action-bar {
            padding: 1.25rem 2rem;
            background: #f8f9fc;
            border-top: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            gap: .75rem;
            flex-wrap: wrap;
        }

        .btn-approve {
            background: #28a745;
            color: #fff;
            border: none;
            padding: .5rem 1.3rem;
            border-radius: 8px;
            font-size: .875rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            transition: background .15s, transform .1s;
        }

        .btn-approve:hover {
            background: #218838;
            transform: translateY(-1px);
            color: #fff;
        }

        .btn-reject {
            background: #fff;
            color: #dc3545;
            border: 1.5px solid #dc3545;
            padding: .5rem 1.3rem;
            border-radius: 8px;
            font-size: .875rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            transition: background .15s, color .15s, transform .1s;
        }

        .btn-reject:hover {
            background: #dc3545;
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-edit-action {
            background: #1a3c72;
            color: #fff;
            border: none;
            padding: .5rem 1.3rem;
            border-radius: 8px;
            font-size: .875rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            transition: background .15s, transform .1s;
            text-decoration: none;
        }

        .btn-edit-action:hover {
            background: #112a52;
            transform: translateY(-1px);
            color: #fff;
        }

        .btn-delete-action {
            background: #fff;
            color: #dc3545;
            border: 1.5px solid #dc3545;
            padding: .5rem 1.3rem;
            border-radius: 8px;
            font-size: .875rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            transition: background .15s, color .15s, transform .1s;
        }

        .btn-delete-action:hover {
            background: #dc3545;
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-back-custom {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            font-size: .85rem;
            font-weight: 600;
            color: #6c757d;
            background: #fff;
            border: 1.5px solid #dee2e6;
            border-radius: 8px;
            padding: .45rem 1rem;
            text-decoration: none;
            transition: background .15s, color .15s;
        }

        .btn-back-custom:hover {
            background: #f0f2f5;
            color: #1a3c72;
        }

        .page-header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            max-width: 780px;
            margin-left: auto;
            margin-right: auto;
        }

        @media (max-width: 600px) {
            .detail-grid {
                grid-template-columns: 1fr;
            }

            .detail-item:nth-child(odd) {
                border-right: none;
            }

            .detail-item.full-width {
                grid-column: 1;
            }

            .booking-body {
                padding: 1.25rem 1rem;
            }

            .booking-card-header {
                padding: 1.1rem 1rem;
                flex-direction: column;
                align-items: flex-start;
                gap: .75rem;
            }

            .action-bar {
                padding: 1rem;
            }
        }
    </style>
@endpush

@section('content')

    @php
        $prefix = match (auth()->user()->role) {
            'admin' => 'admin',
            'super_admin' => 'super-admin',
            default => 'user',
        };
    @endphp

    {{-- Page Header --}}
    <div class="page-header-row">
        <h1 style="font-size:1.4rem;font-weight:700;color:var(--ocd-blue);margin:0;">
            <i class="bi bi-journal-text me-2"></i>Booking Details
        </h1>
        <a href="{{ route($prefix . '.bookings.index') }}" class="btn-back-custom">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="booking-detail-card card">

        {{-- Card Header: Title + Status --}}
        <div class="booking-card-header">
            <div class="event-title-block">
                <div class="label">Event</div>
                <div class="title">{{ $booking->event_title }}</div>
            </div>
            <span class="status-pill {{ $booking->statusBadgeClass() }}">
                {{ ucfirst($booking->status) }}
            </span>
        </div>

        {{-- Detail Grid --}}
        <div class="booking-body">
            <div class="detail-grid">

                <div class="detail-item">
                    <span class="di-label">Venue</span>
                    <span class="di-value">
                        {{--  FIXED: Dinagdag ang Room/Floor dito! --}}
                        <i class="bi bi-geo-alt-fill"></i>{{ $booking->venue->name ?? 'Deleted Venue' }}
                        @if ($booking->venue && $booking->venue->room_floor)
                            <span class="text-muted" style="font-size: .85rem;">({{ $booking->venue->room_floor }})</span>
                        @endif
                    </span>
                </div>

                <div class="detail-item">
                    <span class="di-label">Event Date</span>
                    <span class="di-value">
                        <i class="bi bi-calendar3"></i>{{ $booking->event_date->format('F d, Y') }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="di-label">Time</span>
                    <span class="di-value">
                        <i class="bi bi-clock"></i>
                        {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}
                        &ndash;
                        {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="di-label">Expected Attendees</span>
                    <span class="di-value">
                        <i class="bi bi-people-fill"></i>{{ $booking->expected_attendees }} pax
                    </span>
                </div>

                @if ($booking->booker_name)
                    <div class="detail-item">
                        <span class="di-label">Requested By</span>
                        <span class="di-value">
                            <i class="bi bi-person-fill"></i>{{ $booking->booker_name }}
                        </span>
                    </div>
                @endif

                @if ($booking->service || $booking->division)
                    <div class="detail-item">
                        <span class="di-label">Service / Division</span>
                        <span class="di-value">
                            <i class="bi bi-building"></i>
                            {{ implode(' — ', array_filter([$booking->service, $booking->division])) }}
                        </span>
                    </div>
                @endif


                @if ($booking->agenda)
                    <div class="detail-item full-width">
                        <span class="di-label">Agenda</span>
                        <span class="di-value">
                            <i class="bi bi-card-text"></i>{{ $booking->agenda }}
                        </span>
                    </div>
                @endif

                {{-- UPDATED ATTACHMENT SECTION --}}
                @if ($booking->attachment_path)
                    <div class="detail-item full-width">
                        <span class="di-label">Attachment</span>
                        <span class="di-value">
                            @php
                                $fileUrl = asset(Storage::url($booking->attachment_path));
                                $fileExt = strtolower(pathinfo($booking->attachment_path, PATHINFO_EXTENSION));
                            @endphp
                            <button type="button" class="btn btn-sm btn-outline-primary"
                                onclick="openFileModal('{{ $fileUrl }}', '{{ $fileExt }}')">
                                <i class="bi bi-eye me-1"></i> View Uploaded File
                            </button>
                        </span>
                    </div>
                @endif

                {{-- User's Remarks --}}
                <div class="detail-item full-width">
                    <span class="di-label">Remarks / Additional Info</span>
                    <span class="di-value">
                        @if ($booking->remarks)
                            <i class="bi bi-chat-text"></i>{{ $booking->remarks }}
                        @else
                            <span class="text-muted">None specified</span>
                        @endif
                    </span>
                </div>

            </div>

            {{-- Admin Remarks --}}
            @if ($booking->admin_remarks)
                <div class="remarks-box mt-3">
                    <div class="rm-label"><i class="bi bi-chat-left-text me-1"></i>Admin Remarks</div>
                    <div class="rm-text">{{ $booking->admin_remarks }}</div>
                </div>
            @endif

            {{-- Approved By --}}
            @if ($booking->approvedBy && $booking->isApproved())
                <div class="mt-3 d-flex align-items-center gap-2" style="font-size:.82rem;color:#6c757d;">
                    <i class="bi bi-check-circle-fill text-success"></i>
                    Approved by <strong class="ms-1 text-dark">{{ $booking->approvedBy->name }}</strong>
                    @if ($booking->approved_at)
                        <span>&mdash; {{ $booking->approved_at->format('M d, Y h:i A') }}</span>
                    @endif
                </div>
            @endif
        </div>

        {{-- Action Bar --}}
        @if (auth()->user()->role !== 'user')
            <div class="action-bar justify-content-between">

                <div class="d-flex gap-2">
                    {{-- Approve/Reject Buttons for Pending --}}
                    @if ($booking->isPending())
                        <form method="POST" action="{{ route($prefix . '.bookings.approve', $booking) }}"
                            onsubmit="return confirm('Approve this booking?')" class="m-0">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-approve">
                                <i class="bi bi-check-lg"></i> Approve
                            </button>
                        </form>
                        <button class="btn-reject" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="bi bi-x-lg"></i> Reject
                        </button>
                    @endif

                    {{-- Cancel Button for Approved --}}
                    @if ($booking->isApproved())
                        <button class="btn-reject" data-bs-toggle="modal" data-bs-target="#cancelModal">
                            <i class="bi bi-x-circle"></i> Cancel Booking
                        </button>
                    @endif
                </div>

                {{-- Edit and Delete --}}
                <div class="d-flex gap-2 ms-auto">
                    <a href="{{ route($prefix . '.bookings.edit', $booking) }}" class="btn-edit-action">
                        <i class="bi bi-pencil"></i> Edit Details
                    </a>
                    <form method="POST" action="{{ route($prefix . '.bookings.destroy', $booking) }}"
                        onsubmit="return confirm('Are you sure you want to completely delete this booking? This action cannot be undone.')"
                        class="m-0">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-delete-action">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        @endif

    </div>{{-- end .booking-detail-card --}}

    {{-- Cancel Modal --}}
    @if (auth()->user()->role !== 'user' && $booking->isApproved())
        <div class="modal fade" id="cancelModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius:14px;overflow:hidden;border:none;">
                    <div class="modal-header" style="background:var(--ocd-blue);color:#fff;border:none;">
                        <h5 class="modal-title fw-bold">
                            <i class="bi bi-x-circle me-2"></i>Cancel Booking
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="{{ route($prefix . '.bookings.cancel', $booking) }}">
                        @csrf @method('PATCH')
                        <div class="modal-body p-4">
                            <p class="text-muted mb-3" style="font-size:.875rem;">
                                Please provide a reason for cancelling <strong>{{ $booking->event_title }}</strong>.
                                This will be sent to the requester via email.
                            </p>
                            <label class="form-label fw-semibold small text-muted">
                                Reason for Cancellation (Optional)
                            </label>
                            <textarea name="admin_remarks" class="form-control" rows="3" placeholder="e.g. Reason for Cancellation"
                                style="border-radius:8px;font-size:.9rem;"></textarea>
                        </div>
                        <div class="modal-footer" style="border-top:1px solid #f0f2f5;background:#fafbfc;">
                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                data-bs-dismiss="modal">Back</button>
                            <button type="submit" class="btn btn-sm btn-danger fw-semibold px-3">
                                <i class="bi bi-x-circle me-1"></i>Confirm Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Reject Modal --}}
    @if (auth()->user()->role !== 'user' && $booking->isPending())
        <div class="modal fade" id="rejectModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius:14px;overflow:hidden;border:none;">
                    <div class="modal-header" style="background:var(--ocd-blue);color:#fff;border:none;">
                        <h5 class="modal-title fw-bold">
                            <i class="bi bi-x-circle me-2"></i>Reject Booking
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="{{ route($prefix . '.bookings.reject', $booking) }}">
                        @csrf @method('PATCH')
                        <div class="modal-body p-4">
                            <p class="text-muted mb-3" style="font-size:.875rem;">
                                Please provide a reason for rejecting <strong>{{ $booking->event_title }}</strong>.
                                This will be sent to the requester via email.
                            </p>
                            <label class="form-label fw-semibold small text-muted">
                                Reason for Rejection (Optional)
                            </label>
                            <textarea name="admin_remarks" class="form-control" rows="3" placeholder="e.g. Schedule Conflict..."
                                style="border-radius:8px;font-size:.9rem;"></textarea>
                        </div>
                        <div class="modal-footer" style="border-top:1px solid #f0f2f5;background:#fafbfc;">
                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                data-bs-dismiss="modal">Back</button>
                            <button type="submit" class="btn btn-sm btn-danger fw-semibold px-3">
                                <i class="bi bi-x-circle me-1"></i>Confirm Reject
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- OPTION 1: File Preview Modal (95% Width & Height) --}}
    <div class="modal fade" id="filePreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 95vw;">
            <div class="modal-content" style="border-radius:14px; overflow:hidden; border:none; height: 95vh;">
                <div class="modal-header" style="background:var(--ocd-blue); color:#fff; border:none;">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-file-earmark-text me-2"></i>Attachment Preview
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 d-flex justify-content-center align-items-center" id="fileViewerBody"
                    style="background: #f8f9fa; overflow-y: auto;">
                    {{-- Dito papasok yung JS injected content --}}
                </div>
                <div class="modal-footer" style="border-top:1px solid #f0f2f5; background:#fafbfc; padding: 0.5rem 1rem;">
                    <a href="#" id="downloadFileBtn" class="btn btn-sm btn-primary fw-semibold" download
                        target="_blank">
                        <i class="bi bi-download me-1"></i> Download File
                    </a>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/mammoth@1.6.0/mammoth.browser.min.js"></script>
    <script src="https://cdn.sheetjs.com/xlsx-0.20.0/package/dist/xlsx.full.min.js"></script>
    <script>
        function openFileModal(fileUrl, fileExt) {
            const modalBody = document.getElementById('fileViewerBody');
            const downloadBtn = document.getElementById('downloadFileBtn');

            downloadBtn.href = fileUrl;

            modalBody.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status" style="width:3rem; height:3rem;"></div>
                    <p class="mt-3 text-muted">Loading preview...</p>
                </div>`;

            if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExt)) {
                modalBody.innerHTML = `
                    <img src="${fileUrl}" class="img-fluid"
                        style="max-height:100%; max-width:100%; object-fit:contain; padding:1rem;" alt="Attachment">`;

            } else if (fileExt === 'pdf') {
                modalBody.innerHTML = `
                    <iframe src="${fileUrl}#toolbar=0" width="100%" height="100%"
                        style="border:none; min-height:75vh;"></iframe>`;

            } else if (['doc', 'docx'].includes(fileExt)) {
                modalBody.innerHTML = `
                    <div class="w-100 p-4" style="overflow-y:auto; max-height:80vh;">
                        <div id="mammoth-output" style="font-size:.9rem; line-height:1.6;"></div>
                    </div>`;

                fetch(fileUrl)
                    .then(res => res.arrayBuffer())
                    .then(buffer => mammoth.convertToHtml({
                        arrayBuffer: buffer
                    }))
                    .then(result => {
                        document.getElementById('mammoth-output').innerHTML =
                            result.value || '<p class="text-muted">No content to preview.</p>';
                    })
                    .catch(() => {
                        document.getElementById('mammoth-output').innerHTML = `
                            <div class="text-center py-4 text-danger">
                                <i class="bi bi-exclamation-circle display-4"></i>
                                <p class="mt-2">Could not render preview. Please download the file.</p>
                            </div>`;
                    });

                const previewModal = new bootstrap.Modal(document.getElementById('filePreviewModal'));
                previewModal.show();
                return;

            } else if (['xls', 'xlsx'].includes(fileExt)) {
                modalBody.innerHTML = `
                    <div id="xlsx-preview-output" class="w-100 p-3"
                        style="background:#fff; max-height:80vh; overflow:auto; font-size:.88rem;">
                        <div class="text-muted small text-center py-3">
                            <i class="bi bi-hourglass-split me-1"></i>Rendering spreadsheet...
                        </div>
                    </div>`;

                fetch(fileUrl)
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

                const previewModal = new bootstrap.Modal(document.getElementById('filePreviewModal'));
                previewModal.show();
                return;

            } else {
                modalBody.innerHTML = `
                    <div class="text-center py-5">
                        <i class="bi bi-file-earmark-x display-1 text-muted"></i>
                        <h5 class="mt-3">Preview Not Supported</h5>
                        <p class="text-muted">No preview available for .${fileExt} files.</p>
                        <p class="small text-muted">Please download the file to view its contents.</p>
                    </div>`;
            }

            const previewModal = new bootstrap.Modal(document.getElementById('filePreviewModal'));
            previewModal.show();
        }
    </script>
@endpush
