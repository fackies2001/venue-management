@extends('layouts.app')

@section('title', 'Venue Calendar')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- <style>
    :root {
        --ocd-blue: #1a3c72;
        --ocd-orange: #e87722;
        --ocd-dark: #0a1144;
        /* Added dark color to match sidebar */
    }

    .calendar-wrapper {
        display: flex;
        gap: 1.5rem;
        /* Dinagdagan ng konting space para mas makahinga */
        align-items: flex-start;
    }

    .calendar-panel {
        flex: 1 1 0;
        min-width: 0;
    }

    .form-panel {
        width: 380px;
        flex-shrink: 0;
    }

    /* Inalis na natin yung background sa mismong #calendar kasi ibabalot natin sa card */
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
        /* Ginawang dark blue ang buttons */
        border-color: var(--ocd-dark) !important;
        font-size: .8rem !important;
        padding: .35rem .75rem !important;
        font-weight: 600 !important;
    }

    .fc-button-primary:hover {
        background: var(--ocd-orange) !important;
        /* Orange hover effect */
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

    /* Form panel */
    .form-panel .card {
        position: sticky;
        top: 85px;
        /* Inadjust para hindi dikit sa navbar */
    }

    .form-panel .card-header {
        background: var(--ocd-dark) !important;
        /* Katulad ng kulay sa sidebar */
        color: #fff !important;
        font-weight: 700;
        font-size: 1rem;
        border-radius: 12px 12px 0 0 !important;
        padding: 1rem 1.25rem;
    }

    .form-panel .card-body {
        max-height: calc(100vh - 160px);
        overflow-y: auto;
        padding: 1.25rem;
    }

    .form-panel .card-body::-webkit-scrollbar {
        width: 5px;
    }

    .form-panel .card-body::-webkit-scrollbar-thumb {
        background: #ced4da;
        border-radius: 10px;
    }

    .form-panel .form-label {
        font-size: .82rem;
        margin-bottom: .25rem;
        color: #495057;
    }

    .form-panel .form-control,
    .form-panel .form-select {
        font-size: .85rem;
        padding: .4rem .75rem;
        border-radius: 6px;
        border: 1px solid #dee2e6;
    }

    .form-panel .form-control:focus,
    .form-panel .form-select:focus {
        border-color: var(--ocd-orange);
        box-shadow: 0 0 0 0.2rem rgba(232, 119, 34, 0.15);
    }

    .form-panel .form-text {
        font-size: .72rem;
    }

    .btn-submit {
        background: var(--ocd-orange);
        color: #fff;
        border: none;
        font-size: .9rem;
        font-weight: 600;
        width: 100%;
        padding: .65rem;
        border-radius: 6px;
        margin-top: 0.5rem;
        transition: all 0.2s;
    }

    .btn-submit:hover {
        background: #d1691c;
        color: #fff;
    }

    .time-row {
        display: flex;
        gap: .75rem;
    }

    .time-row>div {
        flex: 1;
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
        font-size: .95rem;
        color: #333;
    }

    /* SweetAlert custom */
    .swal2-confirm.swal-btn-blue {
        background-color: var(--ocd-dark) !important;
    }

    @media (max-width: 1100px) {
        .calendar-wrapper {
            flex-direction: column;
        }

        .form-panel {
            width: 100%;
        }

        .form-panel .card {
            position: static;
        }

        .form-panel .card-body {
            max-height: none;
        }
    }
</style>
@endpush -->

@section('content')

<!-- <div class="page-header mb-4">
    <h1 style="font-size:1.4rem; color: var(--ocd-dark);">
        <i class="bi bi-calendar3 me-2 text-muted"></i> Venue Calendar

    </h1>
</div> -->

<!-- <div class="calendar-wrapper">

    {{-- ══ LEFT: Calendar (Nasa loob na ng Card para pumantay sa Form) ══ --}}
    <div class="calendar-panel">
        <div class="card p-4">

            {{-- Filters --}}
            <div class="mb-3 d-flex gap-2 flex-wrap">
                <select id="buildingFilter" class="form-select form-select-sm"
                    style="max-width:200px; font-weight: 500;">
                    <option value="">All Buildings</option>
                    @foreach ($buildings as $building)
                    <option value="{{ $building }}">{{ $building }}</option>
                    @endforeach
                </select>

                <select id="venueFilter" class="form-select form-select-sm" style="max-width:220px; font-weight: 500;">
                    <option value="">All Venues</option>
                    @foreach ($venues as $venue)
                    <option value="{{ $venue->id }}" data-building="{{ $venue->building }}">
                        {{ $venue->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Legend (Grouped by Building) --}}
            <div class="mb-4 p-3 rounded" style="background: #f8f9fa; border: 1px solid #e9ecef;">
                @php
                // Pinaghiwa-hiwalay natin ang mga venues base sa Building nila
                $groupedVenues = $venues->groupBy('building');
                @endphp

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
                                style="font-size:.8rem; color: #495057;">
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

            {{-- Calendar --}}
            <div id="calendar"></div>
        </div>
    </div>

    {{-- ══ RIGHT: Venue Event Form ══ --}}
    <div class="form-panel">
        <div class="card">
            <div class="card-header">
                Venue Event
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

                    {{-- Building --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Building <span class="text-danger">*</span></label>
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
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Venue <span class="text-danger">*</span></label>
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

                    {{-- Subject --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                        <input type="text" name="event_title"
                            class="form-control @error('event_title') is-invalid @enderror"
                            value="{{ old('event_title') }}" placeholder="Enter name of event" required>
                    </div>

                    {{-- Agenda --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Agenda / Topic</label>
                        <input type="text" name="agenda" class="form-control @error('agenda') is-invalid @enderror"
                            value="{{ old('agenda') }}" placeholder="Enter Agenda/Topic">
                    </div>

                    {{-- Date --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Reservation Start Date <span
                                class="text-danger">*</span></label>
                        <input type="date" name="event_date"
                            class="form-control @error('event_date') is-invalid @enderror"
                            value="{{ old('event_date') }}" min="{{ date('Y-m-d') }}" required>
                    </div>

                    {{-- Time --}}
                    <div class="mb-3 time-row">
                        <div>
                            <label class="form-label fw-semibold">Start Time <span class="text-danger">*</span></label>
                            <select name="start_time" class="form-select @error('start_time') is-invalid @enderror"
                                required>
                                <option value="">-- --</option>
                                @foreach (generateTimeOptions() as $time)
                                <option value="{{ $time['value'] }}"
                                    {{ old('start_time') === $time['value'] ? 'selected' : '' }}>
                                    {{ $time['label'] }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label fw-semibold">End Time <span class="text-danger">*</span></label>
                            <select name="end_time" class="form-select @error('end_time') is-invalid @enderror"
                                required>
                                <option value="">-- --</option>
                                @foreach (generateTimeOptions() as $time)
                                <option value="{{ $time['value'] }}"
                                    {{ old('end_time') === $time['value'] ? 'selected' : '' }}>
                                    {{ $time['label'] }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Name --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                        <input type="text" name="booker_name"
                            class="form-control @error('booker_name') is-invalid @enderror"
                            value="{{ old('booker_name', auth()->user()->name) }}" placeholder="Enter your name"
                            required>
                    </div>

                    {{-- Service --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Service <span class="text-danger">*</span></label>
                        <input type="text" name="service"
                            class="form-control @error('service') is-invalid @enderror" value="{{ old('service') }}"
                            placeholder="Enter service" required>
                    </div>

                    {{-- Division --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Division <span class="text-danger">*</span></label>
                        <input type="text" name="division"
                            class="form-control @error('division') is-invalid @enderror"
                            value="{{ old('division') }}" placeholder="Enter division" required>
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', auth()->user()->email) }}" placeholder="Enter Email Address"
                            required>
                    </div>

                    {{-- Phone --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Telephone No. / IP Phone No. <span
                                class="text-danger">*</span></label>
                        <input type="text" id="phoneInput" name="phone"
                            class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}"
                            placeholder="Enter Phone Number" inputmode="numeric" pattern="[0-9\+\-\s\(\)]+"
                            title="Phone number must contain numbers only" required>
                        <div class="form-text text-muted">Numbers only (0-9, +, -, spaces allowed)</div>
                    </div>

                    {{-- Participants --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Number of Participants <span
                                class="text-danger">*</span></label>
                        <input type="number" name="expected_attendees"
                            class="form-control @error('expected_attendees') is-invalid @enderror"
                            value="{{ old('expected_attendees') }}" min="1"
                            placeholder="Enter number of participants" required>
                    </div>

                    {{-- Attachment --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Attachment (Optional)</label>
                        <input type="file" name="attachment_path"
                            class="form-control @error('attachment_path') is-invalid @enderror"
                            accept=".pdf,.docx,.jpg,.png">
                        <div class="form-text">Max 5MB · PDF, DOCX, JPG, PNG</div>
                    </div>

                    {{-- Remarks --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Remarks</label>
                        <textarea name="remarks" class="form-control @error('remarks') is-invalid @enderror" rows="2"
                            placeholder="Enter remarks">{{ old('remarks') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-submit" id="submitBtn">
                        <i class="bi bi-send-fill me-2"></i> Submit Booking
                    </button>
                </form>
            </div>
        </div>
    </div>

</div> -->

<!-- {{-- ══ Event Detail Modal ══ --}}
<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title text-white">
                    <i class="bi bi-calendar-event me-2" style="color: var(--ocd-orange);"></i><span
                        id="modalTitle"></span>
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
</div> -->

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- <script>
    document.addEventListener('DOMContentLoaded', function() {

        @php
        $prefix = match(auth() - > user() - > role) {
            'ndrrmoc_admin' => 'ndrrmoc',
            'nab_admin' => 'nab',
            'super_admin' => 'super-admin',
            default => 'user',
        };
        @endphp

        const eventsUrl = "{{ route($prefix . '.calendar.events') }}";
        const modal = new bootstrap.Modal(document.getElementById('eventModal'));

        // ── SweetAlert defaults ───────────────────────────────────────────────
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

            // ✅ Title only — no time prefix
            eventContent: function(arg) {
                return {
                    html: `<div style="padding:2px 5px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:.75rem;font-weight:600;letter-spacing:0.3px;">${arg.event.title}</div>`
                };
            },

            // ✅ Fetch events
            events: function(info, successCb, failureCb) {
                const venueId = document.getElementById('venueFilter').value;
                fetch(`${eventsUrl}?start=${info.startStr}&end=${info.endStr}&venue_id=${venueId}`)
                    .then(r => r.json())
                    .then(successCb)
                    .catch(failureCb);
            },

            // ✅ Click event → show modal
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

            // ✅ Click date → auto-fill date input in form
            dateClick: function(info) {
                const dateInput = document.querySelector('[name="event_date"]');
                if (dateInput) dateInput.value = info.dateStr;

                // Optional: Scroll to form on mobile when date is clicked
                if (window.innerWidth <= 1100) {
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
        @if(old('building'))
        const savedBuilding = "{{ old('building') }}";
        const bs = document.getElementById('buildingSelect');
        bs.value = savedBuilding;
        bs.dispatchEvent(new Event('change'));
        @if(old('venue_id'))
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

            // Phone validation
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

            // Confirm dialog
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
        @if($errors - > any())
        swalOcd.fire({
            icon: 'error',
            title: 'Please fix the following errors:',
            html: '<ul class="text-start ps-3 mb-0">' + {
                    !!json_encode($errors - > all()) !!
                }.map(e => `<li>${e}</li>`).join('') +
                '</ul>',
            confirmButtonText: "OK, I'll fix it",
        });
        @endif

        // ── Flash: success ────────────────────────────────────────────────────
        @if(session('success'))
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
        @if(session('error'))
        swalOcd.fire({
            icon: 'error',
            title: 'Booking Failed',
            text: "{{ session('error') }}",
        });
        @endif

    });
</script>
@endpush -->