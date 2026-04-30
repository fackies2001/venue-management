@extends('layouts.app')

@section('title', 'Edit Booking')
@section('page-title', 'Edit Venue Booking')

@push('styles')
    <style>
        .booking-form-card {
            max-width: 780px;
            margin-left: auto;
            margin-right: auto;
            border-radius: 16px !important;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(26, 60, 114, .10) !important;
        }

        .booking-form-header {
            background: #0a1144 !important;
            /* Solid Dark Blue */
            padding: 1.25rem 2rem;
            display: flex;
            align-items: center;
            gap: .85rem;
        }

        .booking-form-header .header-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, .15);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #fff;
            flex-shrink: 0;
        }

        .booking-form-header .header-text .label {
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .09em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, .55);
            margin-bottom: .15rem;
        }

        .booking-form-header .header-text .title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.3;
        }

        .booking-form-body {
            padding: 1.75rem 2rem;
            background: #fff;
        }

        .form-section {
            margin-bottom: 1.5rem;
        }

        .form-section-title {
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .09em;
            text-transform: uppercase;
            color: #9aa5b4;
            padding-bottom: .5rem;
            border-bottom: 1px solid #f0f2f5;
            margin-bottom: 1rem;
        }

        .form-label {
            font-size: .78rem;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: .35rem;
        }

        .form-control,
        .form-select {
            border-radius: 8px !important;
            border: 1.5px solid #e2e8f0 !important;
            font-size: .9rem !important;
            padding: .5rem .85rem !important;
            transition: border-color .15s, box-shadow .15s;
            color: #1c2a3a !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--ocd-blue) !important;
            box-shadow: 0 0 0 3px rgba(26, 60, 114, .08) !important;
        }

        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: #dc3545 !important;
        }

        .form-control::placeholder {
            color: #b0bac5 !important;
        }

        .form-text {
            font-size: .75rem;
            color: #9aa5b4;
            margin-top: .3rem;
        }

        .booking-form-footer {
            padding: 1.25rem 2rem;
            background: #f8f9fc;
            border-top: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .btn-submit {
            background: var(--ocd-blue);
            color: #fff;
            border: none;
            padding: .55rem 1.5rem;
            border-radius: 8px;
            font-size: .875rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            transition: background .15s, transform .1s;
        }

        .btn-submit:hover {
            background: #14306a;
            transform: translateY(-1px);
            color: #fff;
        }

        .btn-cancel-custom {
            background: #fff;
            color: #6c757d;
            border: 1.5px solid #dee2e6;
            padding: .55rem 1.25rem;
            border-radius: 8px;
            font-size: .875rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            transition: background .15s, color .15s;
        }

        .btn-cancel-custom:hover {
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

        @media (max-width: 600px) {
            .booking-form-body {
                padding: 1.25rem 1rem;
            }

            .booking-form-header {
                padding: 1.1rem 1rem;
            }

            .booking-form-footer {
                padding: 1rem;
                flex-wrap: wrap;
            }
        }
    </style>
@endpush

@section('content')

    {{-- Page Header --}}
    <div class="page-header-row">
        <h1 style="font-size:1.4rem;font-weight:700;color:var(--ocd-blue);margin:0;">
            <i class="bi bi-pencil-square me-2"></i>Edit Booking
        </h1>
        <a href="{{ route('user.bookings.index') }}" class="btn-back-custom">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <form id="bookingForm" method="POST" action="{{ route('user.bookings.update', $booking) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="booking-form-card card">

            {{-- Card Header --}}
            <div class="booking-form-header">
                <div class="header-icon">
                    <i class="bi bi-pencil"></i>
                </div>
                <div class="header-text">
                    <div class="label">Update Request</div>
                    <div class="title">Edit Venue Booking Form</div>
                </div>
            </div>

            {{-- Form Body --}}
            <div class="booking-form-body">

                @if ($errors->any())
                    <div class="alert alert-danger small mb-4"
                        style="border-radius:8px;border-left:4px solid #dc3545;background:#fff5f5;">
                        <div
                            style="font-weight:700;font-size:.75rem;letter-spacing:.06em;text-transform:uppercase;color:#dc3545;margin-bottom:.4rem;">
                            <i class="bi bi-exclamation-circle me-1"></i>Please fix the following errors
                        </div>
                        <ul class="mb-0 ps-3" style="color:#842029;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Section: Venue --}}
                <div class="form-section">
                    <div class="form-section-title"><i class="bi bi-geo-alt me-1"></i>Venue Information</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Building <span class="text-danger">*</span></label>
                            <select id="buildingSelect" name="building"
                                class="form-select @error('building') is-invalid @enderror" required>
                                <option value="">— Select Building —</option>
                                @foreach ($buildings as $building)
                                    <option value="{{ $building }}"
                                        {{ old('building', $booking->venue->building ?? '') === $building ? 'selected' : '' }}>
                                        {{ $building }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Venue <span class="text-danger">*</span></label>
                            <select id="venueSelect" name="venue_id"
                                class="form-select @error('venue_id') is-invalid @enderror" required>
                                <option value="">— Select Building First —</option>
                                @foreach ($venues as $venue)
                                    <option value="{{ $venue->id }}" data-building="{{ $venue->building }}"
                                        {{ old('venue_id', $booking->venue_id) == $venue->id ? 'selected' : '' }}
                                        {{ old('building', $booking->venue->building ?? '') !== $venue->building ? 'hidden' : '' }}>
                                        {{ $venue->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Section: Event Details --}}
                <div class="form-section">
                    <div class="form-section-title"><i class="bi bi-calendar3 me-1"></i>Event Details</div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Subject / Event Title <span class="text-danger">*</span></label>
                            <input type="text" name="event_title"
                                class="form-control @error('event_title') is-invalid @enderror"
                                value="{{ old('event_title', $booking->event_title) }}" placeholder="Enter name of event"
                                required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Agenda / Topic</label>
                            <input type="text" name="agenda" class="form-control @error('agenda') is-invalid @enderror"
                                value="{{ old('agenda', $booking->agenda) }}" placeholder="Enter agenda or topic">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Reservation Date <span class="text-danger">*</span></label>
                            <input type="date" name="event_date"
                                class="form-control @error('event_date') is-invalid @enderror"
                                value="{{ old('event_date', \Carbon\Carbon::parse($booking->event_date)->format('Y-m-d')) }}"
                                min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Start Time <span class="text-danger">*</span></label>
                            <input type="time" name="start_time"
                                class="form-control @error('start_time') is-invalid @enderror"
                                value="{{ old('start_time', \Carbon\Carbon::parse($booking->start_time)->format('H:i')) }}"
                                required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">End Time <span class="text-danger">*</span></label>
                            <input type="time" name="end_time"
                                class="form-control @error('end_time') is-invalid @enderror"
                                value="{{ old('end_time', \Carbon\Carbon::parse($booking->end_time)->format('H:i')) }}"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Number of Participants <span class="text-danger">*</span></label>
                            <input type="number" name="expected_attendees"
                                class="form-control @error('expected_attendees') is-invalid @enderror"
                                value="{{ old('expected_attendees', $booking->expected_attendees) }}" min="1"
                                placeholder="Enter number of participants" required>
                        </div>
                    </div>
                </div>

                {{-- Section: Contact Info --}}
                <div class="form-section">
                    <div class="form-section-title"><i class="bi bi-person me-1"></i>Contact Information</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="booker_name"
                                class="form-control @error('booker_name') is-invalid @enderror"
                                value="{{ old('booker_name', $booking->booker_name) }}" placeholder="Enter your name"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $booking->email) }}" placeholder="Enter email address" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Service <span class="text-danger">*</span></label>
                            <input type="text" name="service"
                                class="form-control @error('service') is-invalid @enderror"
                                value="{{ old('service', $booking->service) }}" placeholder="Enter service" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Division <span class="text-danger">*</span></label>
                            <input type="text" name="division"
                                class="form-control @error('division') is-invalid @enderror"
                                value="{{ old('division', $booking->division) }}" placeholder="Enter division" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telephone / IP Phone No. <span class="text-danger">*</span></label>
                            <input type="text" name="phone"
                                class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone', $booking->phone) }}" placeholder="Enter phone number" required>
                        </div>
                    </div>
                </div>

                {{-- Section: Attachments & Remarks --}}
                <div class="form-section mb-0">
                    <div class="form-section-title"><i class="bi bi-paperclip me-1"></i>Attachments & Remarks</div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Notice of Meeting <span
                                    class="text-muted fw-normal">(Optional)</span></label>

                            @if ($booking->attachment_path)
                                <div class="mb-2 small">
                                    Current file: <a href="{{ Storage::url($booking->attachment_path) }}"
                                        target="_blank">View File</a>
                                </div>
                            @endif

                            <input type="file" name="attachment_path"
                                class="form-control @error('attachment_path') is-invalid @enderror"
                                accept=".pdf,.docx,.jpg,.jpeg,.png">
                            <div class="form-text">Upload a new file to replace the existing one. Max: 5MB. Allowed types:
                                PDF, DOCX, JPG, JPEG, PNG.</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Remarks <span class="text-muted fw-normal">(Optional)</span></label>
                            <textarea name="remarks" class="form-control @error('remarks') is-invalid @enderror" rows="3"
                                placeholder="Enter additional remarks">{{ old('remarks', $booking->remarks) }}</textarea>
                        </div>
                    </div>
                </div>

            </div>{{-- end .booking-form-body --}}

            <div class="booking-form-footer">
                <button type="submit" class="btn-submit">
                    <i class="bi bi-save"></i> Save Changes
                </button>
                <a href="{{ route('user.bookings.index') }}" class="btn-cancel-custom">
                    <i class="bi bi-x"></i> Cancel
                </a>
            </div>

        </div>{{-- end .booking-form-card --}}

    </form>

    @push('scripts')
        <script>
            document.getElementById('buildingSelect').addEventListener('change', function() {
                const building = this.value;
                const venueSelect = document.getElementById('venueSelect');
                venueSelect.value = '';
                venueSelect.options[0].textContent = '— Select Venue —';
                Array.from(venueSelect.options).forEach(opt => {
                    if (!opt.value) return;
                    opt.hidden = building ? opt.dataset.building !== building : true;
                });
            });
        </script>
    @endpush

@endsection
