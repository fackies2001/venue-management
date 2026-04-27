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

                {{-- Venue --}}
                <div class="detail-item">
                    <span class="di-label">Venue</span>
                    <span class="di-value">
                        <i class="bi bi-geo-alt-fill"></i>{{ $booking->venue->name }}
                    </span>
                </div>

                {{-- Event Date --}}
                <div class="detail-item">
                    <span class="di-label">Event Date</span>
                    <span class="di-value">
                        <i class="bi bi-calendar3"></i>{{ $booking->event_date->format('F d, Y') }}
                    </span>
                </div>

                {{-- Time --}}
                <div class="detail-item">
                    <span class="di-label">Time</span>
                    <span class="di-value">
                        <i class="bi bi-clock"></i>
                        {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}
                        &ndash;
                        {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                    </span>
                </div>

                {{-- Expected Attendees --}}
                <div class="detail-item">
                    <span class="di-label">Expected Attendees</span>
                    <span class="di-value">
                        <i class="bi bi-people-fill"></i>{{ $booking->expected_attendees }} pax
                    </span>
                </div>

                {{-- Booker Name --}}
                @if ($booking->booker_name)
                    <div class="detail-item">
                        <span class="di-label">Requested By</span>
                        <span class="di-value">
                            <i class="bi bi-person-fill"></i>{{ $booking->booker_name }}
                        </span>
                    </div>
                @endif

                {{-- Service / Division --}}
                @if ($booking->service || $booking->division)
                    <div class="detail-item">
                        <span class="di-label">Service / Division</span>
                        <span class="di-value">
                            <i class="bi bi-building"></i>
                            {{ implode(' — ', array_filter([$booking->service, $booking->division])) }}
                        </span>
                    </div>
                @endif


                {{-- Agenda --}}
                @if ($booking->agenda)
                    <div class="detail-item full-width">
                        <span class="di-label">Agenda</span>
                        <span class="di-value">
                            <i class="bi bi-card-text"></i>{{ $booking->agenda }}
                        </span>
                    </div>
                @endif

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

    </div>{{-- end .booking-detail-card --}}

@endsection
