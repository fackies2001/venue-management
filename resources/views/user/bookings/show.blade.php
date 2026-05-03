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
        }
    </style>
@endpush

@section('content')

    {{-- Page Header --}}
    <div class="page-header-row">
        <h1 style="font-size:1.4rem;font-weight:700;color:var(--ocd-blue);margin:0;">
            <i class="bi bi-journal-text me-2"></i>Booking Details
        </h1>
        <a href="{{ route('user.bookings.index') }}" class="btn-back-custom">
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

                {{-- ✅ FIXED: Added Room/Floor display --}}
                <div class="detail-item">
                    <span class="di-label">Venue</span>
                    <span class="di-value">
                        <i class="bi bi-geo-alt-fill"></i>{{ $booking->venue->name ?? 'Deleted Venue' }}
                        @if ($booking->venue && $booking->venue->room_floor)
                            <span class="text-muted small">({{ $booking->venue->room_floor }})</span>
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

                {{-- ✅ FIXED: Added Attachment View for User --}}
                @if ($booking->attachment_path)
                    <div class="detail-item full-width">
                        <span class="di-label">Your Attachment</span>
                        <span class="di-value">
                            @php
                                $fileUrl = asset(Storage::url($booking->attachment_path));
                                $fileExt = strtolower(pathinfo($booking->attachment_path, PATHINFO_EXTENSION));
                            @endphp
                            <button type="button" class="btn btn-sm btn-outline-primary"
                                onclick="openAttachmentPreview('{{ $fileUrl }}', '{{ basename($booking->attachment_path) }}', '{{ $fileExt }}')">
                                <i class="bi bi-eye me-1"></i> View My Uploaded File
                            </button>
                        </span>
                    </div>
                @endif

                <div class="detail-item full-width">
                    <span class="di-label">Your Remarks</span>
                    <span class="di-value">
                        @if ($booking->remarks)
                            <i class="bi bi-chat-right-text"></i>{{ $booking->remarks }}
                        @else
                            <span class="text-muted italic">No additional remarks provided.</span>
                        @endif
                    </span>
                </div>

            </div>

            {{-- Admin Remarks --}}
            @if ($booking->admin_remarks)
                <div class="remarks-box mt-3">
                    <div class="rm-label"><i class="bi bi-chat-left-text me-1"></i>Admin Feedback</div>
                    <div class="rm-text">{{ $booking->admin_remarks }}</div>
                </div>
            @endif

            {{-- Approved By Info --}}
            @if ($booking->approvedBy && $booking->isApproved())
                <div class="mt-3 d-flex align-items-center gap-2" style="font-size:.82rem;color:#6c757d;">
                    <i class="bi bi-check-circle-fill text-success"></i>
                    Confirmed by <strong class="ms-1 text-dark">{{ $booking->approvedBy->name }}</strong>
                    @if ($booking->approved_at)
                        <span>&mdash; {{ $booking->approved_at->format('M d, Y h:i A') }}</span>
                    @endif
                </div>
            @endif
        </div>

    </div>

    {{--  Attachment Preview Modal — offline capable --}}
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
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/mammoth@1.6.0/mammoth.browser.min.js"></script>
    <script src="https://cdn.sheetjs.com/xlsx-0.20.0/package/dist/xlsx.full.min.js"></script>
    <script>
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

            new bootstrap.Modal(document.getElementById('attachmentModal')).show();
        }
    </script>
@endpush
