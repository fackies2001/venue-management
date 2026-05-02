@extends('layouts.app')

@section('title', 'Manage Facilities')
@section('page-title', 'Manage Facilities')

@push('styles')
    {{-- DataTables & SweetAlert2 CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        #buildingsTable thead th,
        #venuesTable thead th {
            background-color: #212529 !important;
            color: #fff !important;
            border-color: #373b3e !important;
        }

        div.dataTables_wrapper div.dataTables_length label,
        div.dataTables_wrapper div.dataTables_filter label,
        div.dataTables_wrapper div.dataTables_info {
            font-size: .875rem;
            color: #6c757d;
        }

        div.dataTables_wrapper div.dataTables_filter input {
            margin-left: .5rem;
        }



        #buildingsTable {
            width: 100% !important;
        }


        #buildingsTable th:nth-child(1),
        #buildingsTable td:nth-child(1) {
            width: 60%;
        }


        #buildingsTable th:nth-child(2),
        #buildingsTable td:nth-child(2) {
            width: 15%;
            text-align: center;
        }


        #buildingsTable th:nth-child(3),
        #buildingsTable td:nth-child(3) {
            width: 25%;
            text-align: center;

            vertical-align: middle;
        }


        #buildingsTable thead th:nth-child(3) {
            padding-left: 30px !important;



        }
    </style>
@endpush

@section('content')

    <div class="page-header d-flex align-items-center justify-content-between mb-3">
        <h1><i class="bi bi-buildings me-2"></i>Venue Management</h1>
    </div>

    {{-- NOTE: HTML Alerts have been REMOVED here to prevent the duplicate notification bug. 
         Notifications are now handled by the sleek SweetAlert Toasts at the bottom of the script. --}}

    <div class="d-flex flex-column gap-3">

        {{-- ==============================
              BUILDINGS TABLE
    =============================== --}}
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-dark">Buildings</h5>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                        data-bs-target="#addBuildingModal">
                        <i class="bi bi-plus-lg me-1"></i>Add
                    </button>
                </div>
                <table id="buildingsTable" class="table table-hover align-middle mb-0 w-100">
                    <thead>
                        <tr>
                            <th class="text-start">Name</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($buildings as $building)
                            <tr>
                                <!-- Column 1: Name -->
                                <td class="text-start">{{ $building->name }}</td>

                                <!-- Column 2: Status -->
                                <td class="text-center">
                                    <span class="badge bg-success">Active</span>
                                </td>

                                <!-- Column 3: Action (Dito natin gigitna) -->
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-sm btn-outline-success py-0 px-2" data-bs-toggle="modal"
                                            data-bs-target="#editBuildingModal{{ $building->id }}">
                                            <i class="bi bi-pencil me-1"></i>Edit
                                        </button>

                                        <form method="POST"
                                            action="{{ route('super-admin.buildings.destroy', $building) }}"
                                            class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2">
                                                <i class="bi bi-trash me-1"></i>Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ==============================
                VENUES TABLE
    =============================== --}}
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0 text-dark">Venues</h5>
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addVenueModal">
                    <i class="bi bi-plus-lg me-1"></i>Add Venue
                </button>
            </div>
            <div class="table-responsive">
                <table id="venuesTable" class="table table-hover align-middle mb-0 w-100">
                    <thead>
                        <tr>
                            <th class="text-center">Color</th>
                            <th class="text-start">Venue Name</th>
                            <th class="text-start">Room/Floor</th>
                            <th class="text-start">Building</th>
                            <th class="text-center">Capacity</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($venues as $venue)
                            <tr>
                                <!-- 1. Color -->
                                <td class="text-center">
                                    <div
                                        style="width: 20px; height: 20px; background-color: {{ $venue->color }}; border-radius: 4px; margin: 0 auto;">
                                    </div>
                                </td>

                                <!-- 2. Venue Name -->
                                <td class="text-start fw-bold">{{ $venue->name }}</td>

                                <!-- 3. Room/Floor -->
                                <td class="text-start text-muted">{{ $venue->room_floor }}</td>

                                <!-- 4. Building -->
                                <td class="text-start text-muted">{{ $venue->building->name }}</td>

                                <!-- 5. Capacity -->
                                <td class="text-center">{{ $venue->capacity }}</td>

                                <!-- 6. Action (Gitna rin gaya ng Building) -->
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-sm btn-outline-success py-0 px-2" data-bs-toggle="modal"
                                            data-bs-target="#editVenueModal{{ $venue->id }}">
                                            <i class="bi bi-pencil me-1"></i>Edit
                                        </button>

                                        <form method="POST" action="{{ route('super-admin.venues.destroy', $venue) }}"
                                            class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2">
                                                <i class="bi bi-trash me-1"></i>Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    </div>

    {{-- ==========================================
                 MODALS SECTION 
   ========================================== --}}

    {{-- MODAL: Add Building --}}
    <div class="modal fade" id="addBuildingModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:14px;overflow:hidden;border:none;">
                <div class="modal-header" style="background:#1a3c72;color:#fff;border:none;">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-building-add me-2"></i>Add Building
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('super-admin.buildings.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Building Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="" required
                                style="border-radius:8px;font-size:.9rem;">
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" checked
                                id="statusSwitchAddBldg">
                            <label class="form-check-label fw-semibold small text-muted" for="statusSwitchAddBldg">Set as
                                Active</label>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #f0f2f5;background:#fafbfc;">
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-primary fw-semibold px-3"
                            style="background:#1a3c72; border:none;">
                            <i class="bi bi-save me-1"></i>Save Building
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL: Add Venue --}}
    <div class="modal fade" id="addVenueModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:14px;overflow:hidden;border:none;">
                <div class="modal-header" style="background:#1a3c72;color:#fff;border:none;">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-square-dotted me-2"></i>Add Venue
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('super-admin.venues.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Select Building <span
                                    class="text-danger">*</span></label>
                            <select name="building_id" class="form-select" required
                                style="border-radius:8px;font-size:.9rem;">
                                <option value="">-- Choose --</option>
                                @foreach ($buildings as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Venue Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="" required
                                style="border-radius:8px;font-size:.9rem;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Room / Floor <span
                                    class="fw-normal text-muted">(Optional)</span></label>
                            <input type="text" name="room_floor" class="form-control" placeholder=""
                                style="border-radius:8px;font-size:.9rem;">
                        </div>

                        <div class="row g-2">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small">Color Code</label>
                                <input type="color" name="color" class="form-control form-control-color w-100 p-1"
                                    value="#6c757d" style="border-radius:8px; height: 38px;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small">Capacity</label>
                                <input type="number" name="capacity" class="form-control" min="1"
                                    placeholder="" style="border-radius:8px;font-size:.9rem;">
                            </div>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" checked
                                id="statusSwitchAddVenue">
                            <label class="form-check-label fw-semibold small text-muted" for="statusSwitchAddVenue">Set as
                                Active</label>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #f0f2f5;background:#fafbfc;">
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-primary fw-semibold px-3"
                            style="background:#1a3c72; border:none;">
                            <i class="bi bi-save me-1"></i>Save Venue
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- LOOPS PARA SA EDIT MODALS --}}
    @foreach ($buildings as $building)
        <div class="modal fade" id="editBuildingModal{{ $building->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius:14px;overflow:hidden;border:none;">
                    <div class="modal-header" style="background:#1a3c72;color:#fff;border:none;">
                        <h5 class="modal-title fw-bold">
                            <i class="bi bi-pencil-square me-2"></i>Edit Building
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('super-admin.buildings.update', $building->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Building Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ $building->name }}"
                                    required style="border-radius:8px;font-size:.9rem;">
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                    id="editBldgSwitch{{ $building->id }}" {{ $building->is_active ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold small text-muted"
                                    for="editBldgSwitch{{ $building->id }}">Active</label>
                            </div>
                        </div>
                        <div class="modal-footer" style="border-top:1px solid #f0f2f5;background:#fafbfc;">
                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-sm btn-primary fw-semibold px-3"
                                style="background:#1a3c72; border:none;">
                                <i class="bi bi-check-circle me-1"></i>Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    @foreach ($venues as $venue)
        <div class="modal fade" id="editVenueModal{{ $venue->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius:14px;overflow:hidden;border:none;">
                    <div class="modal-header" style="background:#1a3c72;color:#fff;border:none;">
                        <h5 class="modal-title fw-bold">
                            <i class="bi bi-pencil-square me-2"></i>Edit Venue
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('super-admin.venues.update', $venue->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Select Building <span
                                        class="text-danger">*</span></label>
                                <select name="building_id" class="form-select" required
                                    style="border-radius:8px;font-size:.9rem;">
                                    @foreach ($buildings as $b)
                                        <option value="{{ $b->id }}"
                                            {{ $venue->building_id == $b->id ? 'selected' : '' }}>
                                            {{ $b->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Venue Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ $venue->name }}"
                                    required style="border-radius:8px;font-size:.9rem;">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Room / Floor <span
                                        class="fw-normal text-muted">(Optional)</span></label>
                                <input type="text" name="room_floor" class="form-control"
                                    value="{{ $venue->room_floor }}" placeholder=""
                                    style="border-radius:8px;font-size:.9rem;">
                            </div>

                            <div class="row g-2">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold small">Color Code</label>
                                    <input type="color" name="color"
                                        class="form-control form-control-color w-100 p-1"
                                        value="{{ $venue->color ?? '#6c757d' }}"
                                        style="border-radius:8px; height: 38px;">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold small">Capacity</label>
                                    <input type="number" name="capacity" class="form-control" min="1"
                                        value="{{ $venue->capacity }}" style="border-radius:8px;font-size:.9rem;">
                                </div>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                    id="editVenueSwitch{{ $venue->id }}" {{ $venue->is_active ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold small text-muted"
                                    for="editVenueSwitch{{ $venue->id }}">Active</label>
                            </div>
                        </div>
                        <div class="modal-footer" style="border-top:1px solid #f0f2f5;background:#fafbfc;">
                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-sm btn-primary fw-semibold px-3"
                                style="background:#1a3c72; border:none;">
                                <i class="bi bi-check-circle me-1"></i>Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {

            const dtConfig = {
                destroy: true,
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                columnDefs: [{
                    orderable: false,
                    targets: -1
                }],
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    emptyTable: "No records found.",
                    paginate: {
                        previous: "Previous",
                        next: "Next"
                    }
                },
                drawCallback: function() {
                    if (this.api().rows().count() === 0) {
                        $(this.api().table().node()).find('tbody tr td[colspan]').removeAttr('style');
                    }
                }
            };

            $('#buildingsTable').DataTable({
                ...dtConfig,
                order: [
                    [0, 'asc']
                ],
                columnDefs: [{
                        orderable: true,
                        targets: [0, 1]
                    },
                    {
                        orderable: false,
                        targets: [2]
                    }
                ]
            });

            $('#venuesTable').DataTable({
                ...dtConfig,
                order: [
                    [3, 'asc'],
                    [1, 'asc']
                ]
            });

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            @endif

            @if (session('error'))
                Toast.fire({
                    icon: 'error',
                    title: "{{ session('error') }}"
                });
            @endif

        });
    </script>
@endpush
