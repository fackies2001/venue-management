@extends('layouts.app')

@section('title', 'Admin Edit Booking')
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
            background: linear-gradient(135deg, var(--ocd-blue) 0%, #244e96 100%);
            padding: 1.5rem 2rem;
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
    </style>
@endpush

@section('content')

    @php
        $prefix = match (auth()->user()->role) {
            'ndrrmoc_admin' => 'ndrrmoc',
            'nab_admin' => 'nab',
            'super_admin' => 'super-admin',
            default => 'user',
        };
    @endphp

    <div class="page-header-row">
        <h1 style="font-size:1.4rem;font-weight:700;color:var(--ocd-blue);margin:0;">
            <i class="bi bi-pencil-square me-2"></i>Admin Edit Booking
        </h1>
        <a href="{{ route($prefix . '.bookings.index') }}" class="btn-back-custom">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <form id="bookingForm" method="POST" action="{{ route($prefix . '.bookings.update', $booking) }}"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="booking-form-card card">
            <div class="booking-form-header">
                <div class="header-icon">
                    <i class="bi bi-pencil"></i>
                </div>
                <div class="header-text">
                    <div class="label">Admin Override</div>
                    <div class="title">Edit Venue Booking Form</div>
                </div>
            </div>

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

                <div class="form-section">
                    <div class="form-section-title"><i class="bi bi-calendar3 me-1"></i>Event Details</div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Subject / Event Title <span class="text-danger">*</span></label>
                            <input type="text" name="event_title"
                                class="form-control @error('event_title') is-invalid @enderror"
                                value="{{ old('event_title', $booking->event_title) }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Agenda / Topic</label>
                            <input type="text" name="agenda" class="form-control @error('agenda') is-invalid @enderror"
                                value="{{ old('agenda', $booking->agenda) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Reservation Date <span class="text-danger">*</span></label>
                            <input type="date" name="event_date"
                                class="form-control @error('event_date') is-invalid @enderror"
                                value="{{ old('event_date', \Carbon\Carbon::parse($booking->event_date)->format('Y-m-d')) }}"
                                required>
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
                                required>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title"><i class="bi bi-person me-1"></i>Contact Information</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="booker_name"
                                class="form-control @error('booker_name') is-invalid @enderror"
                                value="{{ old('booker_name', $booking->booker_name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $booking->email) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Service <span class="text-danger">*</span></label>
                            <input type="text" name="service"
                                class="form-control @error('service') is-invalid @enderror"
                                value="{{ old('service', $booking->service) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Division <span class="text-danger">*</span></label>
                            <input type="text" name="division"
                                class="form-control @error('division') is-invalid @enderror"
                                value="{{ old('division', $booking->division) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telephone / IP Phone No. <span class="text-danger">*</span></label>
                            <input type="text" name="phone"
                                class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone', $booking->phone) }}" required>
                        </div>
                    </div>
                </div>

                <div class="form-section mb-0">
                    <div class="form-section-title"><i class="bi bi-paperclip me-1"></i>Attachments & Remarks</div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Notice of Meeting <span
                                    class="text-muted fw-normal">(Optional)</span></label>

                            {{-- UPDATED ATTACHMENT SECTION FOR EDIT PAGE --}}
                            @if ($booking->attachment_path)
                                <div class="mb-2 small d-flex align-items-center gap-2">
                                    <span class="text-muted">Current file:</span>
                                    @php
                                        $fileUrl = asset(Storage::url($booking->attachment_path));
                                        $fileExt = strtolower(pathinfo($booking->attachment_path, PATHINFO_EXTENSION));
                                    @endphp
                                    <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2"
                                        style="font-size: 0.8rem;"
                                        onclick="openFileModal('{{ $fileUrl }}', '{{ $fileExt }}')">
                                        <i class="bi bi-eye me-1"></i> View File
                                    </button>
                                </div>
                            @endif

                            <input type="file" name="attachment_path"
                                class="form-control @error('attachment_path') is-invalid @enderror"
                                accept=".pdf,.docx,.jpg,.jpeg,.png">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Remarks <span class="text-muted fw-normal">(Optional)</span></label>
                            <textarea name="remarks" class="form-control @error('remarks') is-invalid @enderror" rows="3">{{ old('remarks', $booking->remarks) }}</textarea>
                        </div>
                    </div>
                </div>

            </div>

            <div class="booking-form-footer">
                <button type="submit" class="btn-submit">
                    <i class="bi bi-save"></i> Save Changes
                </button>
                <a href="{{ route($prefix . '.bookings.index') }}" class="btn-cancel-custom">
                    <i class="bi bi-x"></i> Cancel
                </a>
            </div>

        </div>
    </form>

    {{-- FILE PREVIEW MODAL (OPTION 1: 95vw / 95vh) --}}
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
                    {{-- JS Injected Content --}}
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

        // Script para sa File Preview Modal
        function openFileModal(fileUrl, fileExt) {
            const modalBody = document.getElementById('fileViewerBody');
            const downloadBtn = document.getElementById('downloadFileBtn');

            // Set download button link
            downloadBtn.href = fileUrl;

            // Loading state
            modalBody.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
                    <p class="mt-3 text-muted">Loading preview...</p>
                </div>`;

            let content = '';

            // Image Preview
            if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExt)) {
                content =
                    `<img src="${fileUrl}" class="img-fluid" style="max-height: 100%; max-width: 100%; object-fit: contain; padding: 1rem;" alt="Attachment">`;
            }
            // PDF Preview
            else if (fileExt === 'pdf') {
                content =
                    `<iframe src="${fileUrl}#toolbar=0" width="100%" height="100%" style="border: none; min-height: 75vh;"></iframe>`;
            }
            // Office Documents Preview (Word, Excel)
            else if (['doc', 'docx', 'xls', 'xlsx'].includes(fileExt)) {
                const encodedUrl = encodeURIComponent(fileUrl);
                content = `
                    <div class="w-100 h-100 d-flex flex-column">
                        <div class="alert alert-warning m-3 text-center small">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> 
                            <strong>Note:</strong> Word/Excel preview requires a public internet connection. If it doesn't load on localhost, please use the download button below.
                        </div>
                        <iframe src="https://view.officeapps.live.com/op/embed.aspx?src=${encodedUrl}" width="100%" height="100%" style="border: none; flex-grow: 1;"></iframe>
                    </div>`;
            }
            // Fallback para sa hindi supported na files
            else {
                content = `
                    <div class="text-center py-5">
                        <i class="bi bi-file-earmark-x display-1 text-muted"></i>
                        <h5 class="mt-3">Preview Not Supported</h5>
                        <p class="text-muted">No preview available for .${fileExt} files.</p>
                        <p class="small text-muted">Please download the file to view its contents.</p>
                    </div>`;
            }

            // Inject content to modal
            modalBody.innerHTML = content;

            // Show Modal
            const previewModal = new bootstrap.Modal(document.getElementById('filePreviewModal'));
            previewModal.show();
        }
    </script>
@endpush
