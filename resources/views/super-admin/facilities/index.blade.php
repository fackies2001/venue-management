@extends('layouts.app')

@section('title', 'Manage Facilities')
@section('page-title', 'Manage Facilities')

@push('styles')
    {{-- DataTables & SweetAlert2 CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')

    <div class="page-header d-flex align-items-center justify-content-between mb-3">
        <h1><i class="bi bi-buildings me-2"></i>Venue Management</h1>
    </div>

    {{-- NOTE: HTML Alerts have been REMOVED here to prevent the duplicate notification bug. 
         Notifications are now handled by the sleek SweetAlert Toasts at the bottom of the script. --}}

    <div class="row g-3">
        {{-- ==============================
                  BUILDINGS TABLE
        =============================== --}}
        <div class="col-xl-5">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0 text-dark">Buildings</h5>
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#addBuildingModal">
                            <i class="bi bi-plus-lg me-1"></i>Add
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table id="buildingsTable" class="table table-hover align-middle mb-0 w-100">
                            <thead class="table-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($buildings as $building)
                                    <tr>
                                        <td class="fw-semibold">{{ $building->name }}</td>
                                        <td>
                                            @if ($building->is_active)
                                                <span class="badge bg-success px-2 py-1">Active</span>
                                            @else
                                                <span class="badge bg-danger px-2 py-1">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end gap-1 flex-wrap">
                                                <button class="btn btn-sm btn-outline-success py-0 px-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editBuildingModal{{ $building->id }}">
                                                    <i class="bi bi-pencil me-1"></i>Edit
                                                </button>
                                                <form method="POST"
                                                    action="{{ route('super-admin.buildings.destroy', $building) }}"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Delete this building? All venues inside will be deleted too.')">
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

        {{-- ==============================
                    VENUES TABLE
        =============================== --}}
        <div class="col-xl-7">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0 text-dark">Venues</h5>
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#addVenueModal">
                            <i class="bi bi-plus-lg me-1"></i>Add Venue
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table id="venuesTable" class="table table-hover align-middle mb-0 w-100">
                            <thead class="table-dark">
                                <tr>
                                    <th>Color</th>
                                    <th>Venue Name</th>
                                    <th>Room/Floor</th>
                                    <th>Building</th>
                                    <th>Capacity</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($venues as $venue)
                                    <tr>
                                        <td>
                                            <span
                                                style="display:inline-block;width:16px;height:16px;border-radius:4px;background:{{ $venue->color ?? '#6c757d' }};"></span>
                                        </td>
                                        <td class="fw-semibold">{{ $venue->name }}</td>
                                        <td class="text-muted small">{{ $venue->room_floor ?? '—' }}</td>
                                        <td>{{ $venue->building->name ?? 'N/A' }}</td>
                                        <td>{{ $venue->capacity ?? 'N/A' }}</td>
                                        <td>
                                            <div class="d-flex justify-content-end gap-1 flex-wrap">
                                                <button class="btn btn-sm btn-outline-success py-0 px-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editVenueModal{{ $venue->id }}">
                                                    <i class="bi bi-pencil me-1"></i>Edit
                                                </button>
                                                <form method="POST"
                                                    action="{{ route('super-admin.venues.destroy', $venue) }}"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Completely delete this venue? This cannot be undone.')">
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
    {{-- Scripts for DataTables and SweetAlert --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {

            // ==========================================
            // 1. DataTables Configuration
            // ==========================================
            const dtConfig = {
                destroy: true,
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                columnDefs: [{
                    orderable: false,
                    targets: -1 // Disables sorting on the Action column
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

            // Initialize Buildings Table
            $('#buildingsTable').DataTable({
                ...dtConfig,
                order: [
                    [0, 'asc']
                ] // Sort by Name
            });

            // Initialize Venues Table
            $('#venuesTable').DataTable({
                ...dtConfig,
                order: [
                    [3, 'asc'],
                    [1, 'asc']
                ] // Sort by Building Name, then Venue Name
            });


            // ==========================================
            // 2. SweetAlert2 Toast Notifications
            // ==========================================
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

            // Fire Success Toast if session has success message
            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            @endif

            // Fire Error Toast if session has error message
            @if (session('error'))
                Toast.fire({
                    icon: 'error',
                    title: "{{ session('error') }}"
                });
            @endif

        });
    </script>
@endpush
