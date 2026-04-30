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
                                onclick="openFileModal('{{ $fileUrl }}', '{{ $fileExt }}')">
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

    </div>{{-- end .booking-detail-card --}}

    {{-- ✅ File Preview Modal (Matches Admin Side) --}}
    <div class="modal fade" id="filePreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 90vw;">
            <div class="modal-content" style="border-radius:14px; overflow:hidden; border:none; height: 90vh;">
                <div class="modal-header" style="background:var(--ocd-blue); color:#fff; border:none;">
                    <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-text me-2"></i>Attachment Preview</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 d-flex justify-content-center align-items-center" id="fileViewerBody"
                    style="background: #f8f9fa; overflow-y: auto;">
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
    <script>
        function openFileModal(fileUrl, fileExt) {
            const modalBody = document.getElementById('fileViewerBody');
            const downloadBtn = document.getElementById('downloadFileBtn');
            downloadBtn.href = fileUrl;

            modalBody.innerHTML =
                `<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-3">Loading...</p></div>`;

            let content = '';
            if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExt)) {
                content =
                    `<img src="${fileUrl}" class="img-fluid" style="max-height: 100%; max-width: 100%; object-fit: contain; padding: 1rem;">`;
            } else if (fileExt === 'pdf') {
                content = `<iframe src="${fileUrl}#toolbar=0" width="100%" height="100%" style="border: none;"></iframe>`;
            } else if (['doc', 'docx', 'xls', 'xlsx'].includes(fileExt)) {
                const encodedUrl = encodeURIComponent(fileUrl);
                content =
                    `<div class="w-100 h-100 d-flex flex-column"><div class="alert alert-warning m-3 text-center small">Preview requires internet. If it doesn't load, use Download.</div><iframe src="https://view.officeapps.live.com/op/embed.aspx?src=${encodedUrl}" width="100%" height="100%" style="border: none; flex-grow: 1;"></iframe></div>`;
            } else {
                content =
                    `<div class="text-center py-5"><i class="bi bi-file-earmark-x display-1 text-muted"></i><h5 class="mt-3">Preview Not Supported</h5><p>Please download the file instead.</p></div>`;
            }

            modalBody.innerHTML = content;
            new bootstrap.Modal(document.getElementById('filePreviewModal')).show();
        }
    </script>
@endpush
