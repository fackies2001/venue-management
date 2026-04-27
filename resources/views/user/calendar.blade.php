@extends('layouts.app')

@section('title', 'Venue Calendar')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --ocd-blue: #1a3c72;
            --ocd-orange: #e87722;
            --ocd-dark: #0a1144;
        }

        .calendar-wrapper {
            display: flex;
            gap: 1.5rem;
            align-items: flex-start;
        }

        .calendar-panel {
            flex: 1 1 0;
            min-width: 0;
        }

        .form-panel {
            width: 580px;
            flex-shrink: 0;
        }

        #calendar {
            padding-top: 0.5rem;
        }

        .fc-toolbar-title {
            font-size: 1.15rem !important;
            font-weight: 700;
            color: var(--ocd-dark);
        }

        .fc-button-primary {
            background: var(--ocd-dark) !important;
            border-color: var(--ocd-dark) !important;
            font-size: .8rem !important;
            padding: .35rem .75rem !important;
            font-weight: 600 !important;
        }

        .fc-button-primary:hover {
            background: var(--ocd-orange) !important;
            border-color: var(--ocd-orange) !important;
        }

        .fc-daygrid-day-number,
        .fc-col-header-cell-cushion {
            font-size: .82rem;
            color: #333;
            text-decoration: none;
        }

        .fc-daygrid-day-events {
            overflow: hidden;
        }

        .fc-daygrid-event {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: .75rem !important;
            border-radius: 4px;
            padding: 1px 3px;
        }

        /* Form panel sticky */
        .form-panel .card {
            position: sticky;
            top: 80px;
        }

        .form-panel .card-header {
            background: var(--ocd-dark) !important;
            color: #fff !important;
            font-weight: 700;
            font-size: 1rem;
            border-radius: 12px 12px 0 0 !important;
            padding: .75rem 1.25rem;
        }

        /* No overflow — all fields show at once */
        .form-panel .card-body {
            padding: .9rem 1.1rem;
            overflow: visible;
        }

        .form-panel .form-label {
            font-size: .76rem;
            margin-bottom: .15rem;
            color: #495057;
            font-weight: 600;
        }

        .form-panel .form-control,
        .form-panel .form-select {
            font-size: .85rem;
            padding: .35rem .7rem;
            border-radius: 6px;
            border: 1px solid #dee2e6;
            height: 36px;
        }

        .form-panel textarea.form-control {
            height: 100px;
            resize: vertical;
        }

        .form-panel .form-control:focus,
        .form-panel .form-select:focus {
            border-color: var(--ocd-orange);
            box-shadow: 0 0 0 0.15rem rgba(232, 119, 34, .15);
        }

        .form-panel .form-text {
            font-size: .67rem;
            line-height: 1.2;
        }

        .btn-submit {
            background: var(--ocd-orange);
            color: #fff;
            border: none;
            font-size: .85rem;
            font-weight: 600;
            width: 100%;
            padding: .45rem;
            border-radius: 6px;
            margin-top: .5rem;
            transition: all .2s;
        }

        .btn-submit:hover {
            background: #d1691c;
            color: #fff;
        }

        /* 2-column grid */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .35rem .7rem;
        }

        .form-grid .col-full {
            grid-column: 1 / -1;
        }

        .form-grid .mb-2 {
            margin-bottom: 0 !important;
        }

        /* Section divider label */
        .section-divider {
            grid-column: 1 / -1;
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--ocd-dark);
            border-bottom: 1px solid #dee2e6;
            padding-bottom: .15rem;
            margin-top: .35rem;
        }

        /* Event modal */
        #eventModal .modal-dialog {
            max-width: 560px;
        }

        #eventModal .modal-header {
            background: var(--ocd-dark) !important;
            color: #fff !important;
        }

        #eventModal .modal-body table th {
            width: 30%;
            color: #6c757d;
            font-weight: 600;
        }

        #eventModal .modal-body table td {
            font-size: 1.05rem;
            color: #333;
        }

        #eventModal .modal-body table th {
            font-size: 1rem;
        }

        .swal2-confirm.swal-btn-blue {
            background-color: var(--ocd-dark) !important;
        }

        @media (max-width: 1200px) {
            .calendar-wrapper {
                flex-direction: column;
            }

            .form-panel {
                width: 100%;
            }

            .form-panel .card {
                position: static;
            }
        }

        @media (max-width: 576px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-grid .col-full {
                grid-column: 1;
            }

            .section-divider {
                grid-column: 1;
            }
        }
    </style>
@endpush

@section('content')

    <div class="page-header mb-3">
        <h1 style="font-size:1.4rem; color: var(--ocd-dark);">
            <i class="bi bi-calendar3 me-2 text-muted"></i> Venue Calendar
        </h1>
    </div>

    <div class="calendar-wrapper">

        {{-- ══ LEFT: Calendar ══ --}}
        <div class="calendar-panel">
            <div class="card p-4">

                {{-- Filters --}}
                <div class="mb-3 d-flex gap-2 flex-wrap">
                    <select id="buildingFilter" class="form-select form-select-sm" style="max-width:200px; font-weight:500;">
                        <option value="">All Buildings</option>
                        @foreach ($buildings as $building)
                            <option value="{{ $building }}">{{ $building }}</option>
                        @endforeach
                    </select>

                    <select id="venueFilter" class="form-select form-select-sm" style="max-width:220px; font-weight:500;">
                        <option value="">All Venues</option>
                        @foreach ($venues as $venue)
                            <option value="{{ $venue->id }}" data-building="{{ $venue->building }}">
                                {{ $venue->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Legend --}}
                <div class="mb-3 p-3 rounded" style="background:#f8f9fa; border:1px solid #e9ecef;">
                    @php $groupedVenues = $venues->groupBy('building'); @endphp
                    <div class="row">
                        @foreach ($groupedVenues as $buildingName => $buildingVenues)
                            <div class="col-md-6 mb-2 mb-md-0">
                                <div class="fw-bold mb-2" style="color:var(--ocd-dark); font-size:.85rem;">
                                    {{ $buildingName ?: 'Other Venues' }}
                                </div>
                                <div class="d-flex flex-column gap-2 ps-1">
                                    @foreach ($buildingVenues as $venue)
                                        @php $color = $venue->color ?? '#6c757d'; @endphp
                                        <span class="d-flex align-items-center gap-2"
                                            style="font-size:.8rem; color:#495057;">
                                            <span
                                                style="display:inline-block;width:14px;height:14px;border-radius:4px;background:{{ $color }};flex-shrink:0;"></span>
                                            {{ $venue->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div id="calendar"></div>
            </div>
        </div>

        {{-- ══ RIGHT: Booking Form — 2-column, no scroll ══ --}}
        <div class="form-panel">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-calendar-plus me-2"></i>Venue Event
                </div>
                <div class="card-body">

                    <form id="venueEventForm" method="POST"
                        action="{{ route(
                            match (auth()->user()->role) {
                                'ndrrmoc_admin' => 'ndrrmoc.bookings.store',
                                'nab_admin' => 'nab.bookings.store',
                                'super_admin' => 'super-admin.bookings.store',
                                default => 'user.bookings.store',
                            },
                        ) }}"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="form-grid">

                            {{-- ── Section: Venue Details ── --}}
                            <div class="section-divider">Venue Details</div>

                            {{-- Building --}}
                            <div class="mb-2">
                                <label class="form-label">Building <span class="text-danger">*</span></label>
                                <select id="buildingSelect" name="building"
                                    class="form-select @error('building') is-invalid @enderror" required>
                                    <option value="">-- Select Building --</option>
                                    @foreach ($buildings as $building)
                                        <option value="{{ $building }}"
                                            {{ old('building') === $building ? 'selected' : '' }}>
                                            {{ $building }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Venue --}}
                            <div class="mb-2">
                                <label class="form-label">Venue <span class="text-danger">*</span></label>
                                <select id="venueSelect" name="venue_id"
                                    class="form-select @error('venue_id') is-invalid @enderror" required>
                                    <option value="">-- Select Building First --</option>
                                    @foreach ($venues as $venue)
                                        <option value="{{ $venue->id }}" data-building="{{ $venue->building }}"
                                            {{ old('venue_id') == $venue->id ? 'selected' : '' }} hidden>
                                            {{ $venue->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Subject (full width) --}}
                            <div class="mb-2 col-full">
                                <label class="form-label">Subject <span class="text-danger">*</span></label>
                                <input type="text" name="event_title"
                                    class="form-control @error('event_title') is-invalid @enderror"
                                    value="{{ old('event_title') }}" placeholder="Enter name of event" required>
                            </div>

                            {{-- Agenda (full width) --}}
                            <div class="mb-2 col-full">
                                <label class="form-label">Agenda / Topic</label>
                                <input type="text" name="agenda"
                                    class="form-control @error('agenda') is-invalid @enderror" value="{{ old('agenda') }}"
                                    placeholder="Enter Agenda/Topic">
                            </div>

                            {{-- Date --}}
                            <div class="mb-2">
                                <label class="form-label">Reservation Date <span class="text-danger">*</span></label>
                                <input type="date" name="event_date"
                                    class="form-control @error('event_date') is-invalid @enderror"
                                    value="{{ old('event_date') }}" min="{{ date('Y-m-d') }}" required>
                            </div>

                            {{-- Start + End Time stacked in one cell --}}
                            <div class="mb-2">
                                <label class="form-label">Time (Start – End) <span class="text-danger">*</span></label>
                                <div class="d-flex gap-1">
                                    <select name="start_time" class="form-select @error('start_time') is-invalid @enderror"
                                        required>
                                        <option value="">Start</option>
                                        @foreach (generateTimeOptions() as $time)
                                            <option value="{{ $time['value'] }}"
                                                {{ old('start_time') === $time['value'] ? 'selected' : '' }}>
                                                {{ $time['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <select name="end_time" class="form-select @error('end_time') is-invalid @enderror"
                                        required>
                                        <option value="">End</option>
                                        @foreach (generateTimeOptions() as $time)
                                            <option value="{{ $time['value'] }}"
                                                {{ old('end_time') === $time['value'] ? 'selected' : '' }}>
                                                {{ $time['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- ── Section: Requester Info ── --}}
                            <div class="section-divider">Requester Information</div>

                            {{-- Name --}}
                            <div class="mb-2">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" name="booker_name"
                                    class="form-control @error('booker_name') is-invalid @enderror"
                                    value="{{ old('booker_name', auth()->user()->name) }}" placeholder="Enter your name"
                                    required>
                            </div>

                            {{-- Email --}}
                            <div class="mb-2">
                                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', auth()->user()->email) }}" placeholder="Enter email" required>
                            </div>

                            {{-- Service --}}
                            <div class="mb-2">
                                <label class="form-label">Service <span class="text-danger">*</span></label>
                                <input type="text" name="service"
                                    class="form-control @error('service') is-invalid @enderror"
                                    value="{{ old('service') }}" placeholder="Enter service" required>
                            </div>

                            {{-- Division --}}
                            <div class="mb-2">
                                <label class="form-label">Division <span class="text-danger">*</span></label>
                                <input type="text" name="division"
                                    class="form-control @error('division') is-invalid @enderror"
                                    value="{{ old('division') }}" placeholder="Enter division" required>
                            </div>

                            {{-- Phone --}}
                            <div class="mb-2">
                                <label class="form-label">Tel. / IP Phone No. <span class="text-danger">*</span></label>
                                <input type="text" id="phoneInput" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone') }}" placeholder="Enter phone number" inputmode="numeric"
                                    pattern="[0-9\+\-\s\(\)]+" title="Numbers only" required>
                                <div class="form-text text-muted">Numbers only (0-9, +, -, spaces)</div>
                            </div>

                            {{-- No. of Participants --}}
                            <div class="mb-2">
                                <label class="form-label">No. of Participants <span class="text-danger">*</span></label>
                                <input type="number" name="expected_attendees"
                                    class="form-control @error('expected_attendees') is-invalid @enderror"
                                    value="{{ old('expected_attendees') }}" min="1" placeholder="Enter number"
                                    required>
                            </div>

                            {{-- ── Section: Additional ── --}}
                            <div class="section-divider">Additional</div>

                            {{-- Attachment --}}
                            <div class="mb-2">
                                <label class="form-label">Attachment (Optional)</label>
                                <input type="file" name="attachment_path"
                                    class="form-control @error('attachment_path') is-invalid @enderror"
                                    accept=".pdf,.docx,.jpg,.png"
                                    style="height:auto; padding:.2rem .5rem; font-size:.75rem;">
                                <div class="form-text">Max 5MB · PDF, DOCX, JPG, PNG</div>
                            </div>

                            {{-- Remarks --}}
                            <div class="mb-2">
                                <label class="form-label">Remarks</label>
                                <textarea name="remarks" class="form-control @error('remarks') is-invalid @enderror" placeholder="Enter remarks">{{ old('remarks') }}</textarea>
                            </div>

                        </div>{{-- /.form-grid --}}

                        <button type="submit" class="btn btn-submit" id="submitBtn">
                            <i class="bi bi-send-fill me-2"></i> Submit Booking
                        </button>

                    </form>
                </div>
            </div>
        </div>

    </div>

    {{-- ══ Event Detail Modal ══ --}}
    <div class="modal fade" id="eventModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title text-white">
                        <i class="bi bi-calendar-event me-2" style="color: var(--ocd-orange);"></i>
                        <span id="modalTitle"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <th style="width:30%">Venue</th>
                            <td id="modalVenue"></td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td id="modalDate"></td>
                        </tr>
                        <tr>
                            <th>Time</th>
                            <td id="modalTime"></td>
                        </tr>
                        <tr>
                            <th>Booked by</th>
                            <td id="modalBooker"></td>
                        </tr>
                        <tr>
                            <th>Details</th>
                            <td id="modalDesc"></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            @php
                $prefix = match (auth()->user()->role) {
                    'ndrrmoc_admin' => 'ndrrmoc',
                    'nab_admin' => 'nab',
                    'super_admin' => 'super-admin',
                    default => 'user',
                };
            @endphp

            const eventsUrl = "{{ route($prefix . '.calendar.events') }}";
            const modal = new bootstrap.Modal(document.getElementById('eventModal'));

            const swalOcd = Swal.mixin({
                confirmButtonColor: '#0a1144',
                cancelButtonColor: '#6c757d',
                buttonsStyling: true,
            });

            // ── FullCalendar ──────────────────────────────────────────────────────
            const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                initialView: 'dayGridMonth',
                height: 'auto',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay',
                },
                eventDisplay: 'block',

                eventContent: function(arg) {
                    return {
                        html: `<div style="padding:2px 5px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:.88rem;font-weight:600;">${arg.event.title}</div>`
                    };
                },

                events: function(info, successCb, failureCb) {
                    const venueId = document.getElementById('venueFilter').value;
                    fetch(`${eventsUrl}?start=${info.startStr}&end=${info.endStr}&venue_id=${venueId}`)
                        .then(r => r.json())
                        .then(successCb)
                        .catch(failureCb);
                },

                eventClick: function(info) {
                    const e = info.event;
                    const fmt = d => d ? d.toLocaleDateString('en-PH', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    }) : '—';
                    document.getElementById('modalTitle').textContent = e.title;
                    document.getElementById('modalVenue').textContent = e.extendedProps.venue || '—';
                    document.getElementById('modalDate').textContent = fmt(e.start);
                    document.getElementById('modalTime').textContent = e.extendedProps.time || '—';
                    document.getElementById('modalBooker').textContent = e.extendedProps.booker || '—';
                    document.getElementById('modalDesc').textContent = e.extendedProps.description ||
                        '—';
                    modal.show();
                },

                dateClick: function(info) {
                    const dateInput = document.querySelector('[name="event_date"]');
                    if (dateInput) dateInput.value = info.dateStr;
                    if (window.innerWidth <= 1200) {
                        document.querySelector('.form-panel').scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                },
            });

            calendar.render();

            // ── Venue filter ──────────────────────────────────────────────────────
            document.getElementById('venueFilter').addEventListener('change', () => calendar.refetchEvents());

            // ── Building filter ───────────────────────────────────────────────────
            document.getElementById('buildingFilter').addEventListener('change', function() {
                const building = this.value;
                const venueSelect = document.getElementById('venueFilter');
                venueSelect.value = '';
                Array.from(venueSelect.options).forEach(opt => {
                    if (!opt.value) return;
                    opt.hidden = building ? opt.dataset.building !== building : false;
                });
                calendar.refetchEvents();
            });

            // ── Form: building → venue cascade ────────────────────────────────────
            document.getElementById('buildingSelect').addEventListener('change', function() {
                const building = this.value;
                const venueSelect = document.getElementById('venueSelect');
                venueSelect.value = '';
                venueSelect.options[0].textContent = building ? '-- Select Venue --' :
                    '-- Select Building First --';
                Array.from(venueSelect.options).forEach(opt => {
                    if (!opt.value) return;
                    opt.hidden = building ? opt.dataset.building !== building : true;
                });
            });

            // ── Restore cascade on validation error ───────────────────────────────
            @if (old('building'))
                const savedBuilding = "{{ old('building') }}";
                const bs = document.getElementById('buildingSelect');
                bs.value = savedBuilding;
                bs.dispatchEvent(new Event('change'));
                @if (old('venue_id'))
                    document.getElementById('venueSelect').value = "{{ old('venue_id') }}";
                @endif
            @endif

            // ── Phone: block non-numeric ──────────────────────────────────────────
            const phoneInput = document.getElementById('phoneInput');
            phoneInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9\+\-\s\(\)]/g, '');
            });
            phoneInput.addEventListener('keypress', function(e) {
                if (!/[0-9\+\-\s\(\)]/.test(e.key)) e.preventDefault();
            });

            // ── Form submit: SweetAlert confirm ───────────────────────────────────
            let confirmed = false;

            document.getElementById('venueEventForm').addEventListener('submit', function(e) {
                if (confirmed) return;
                e.preventDefault();

                const phone = phoneInput.value.trim();
                if (!phone || !/^[0-9\+\-\s\(\)]+$/.test(phone)) {
                    swalOcd.fire({
                        icon: 'error',
                        title: 'Invalid Phone Number',
                        text: 'Telephone number must contain numbers only.',
                    });
                    phoneInput.focus();
                    return;
                }

                swalOcd.fire({
                    icon: 'question',
                    title: 'Submit Booking?',
                    html: 'Please confirm that all details are correct before submitting.',
                    showCancelButton: true,
                    confirmButtonText: '<i class="bi bi-send-fill me-1"></i> Yes, Submit',
                    cancelButtonText: 'Review Again',
                    reverseButtons: true,
                }).then(result => {
                    if (result.isConfirmed) {
                        confirmed = true;
                        const btn = document.getElementById('submitBtn');
                        btn.disabled = true;
                        btn.innerHTML =
                            '<span class="spinner-border spinner-border-sm me-1"></span> Submitting…';
                        document.getElementById('venueEventForm').submit();
                    }
                });
            });

            // ── Flash: validation errors ──────────────────────────────────────────
            @if ($errors->any())
                swalOcd.fire({
                    icon: 'error',
                    title: 'Please fix the following errors:',
                    html: '<ul class="text-start ps-3 mb-0">' +
                        {!! json_encode($errors->all()) !!}.map(e => `<li>${e}</li>`).join('') +
                        '</ul>',
                    confirmButtonText: "OK, I'll fix it",
                });
            @endif

            // ── Flash: success ────────────────────────────────────────────────────
            @if (session('success'))
                swalOcd.fire({
                    icon: 'success',
                    title: 'Booking Submitted!',
                    text: "{{ session('success') }}",
                    timer: 4000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                });
            @endif

            // ── Flash: error ──────────────────────────────────────────────────────
            @if (session('error'))
                swalOcd.fire({
                    icon: 'error',
                    title: 'Booking Failed',
                    text: "{{ session('error') }}",
                });
            @endif

        });
    </script>
@endpush
