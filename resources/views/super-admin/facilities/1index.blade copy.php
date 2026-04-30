@extends('layouts.app')
@section('title', 'Manage Facilities')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="m-0" style="color: #0a1144;"><i class="bi bi-buildings me-2"></i>Manage Facilities</h2>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            {{-- BUILDINGS TABLE --}}
            <div class="col-md-5 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Buildings</h5>
                        <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#addBuildingModal">+
                            Add</button>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover m-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($buildings as $building)
                                    <tr>
                                        <td class="fw-bold">{{ $building->name }}</td>
                                        <td>{!! $building->is_active
                                            ? '<span class="badge bg-success">Active</span>'
                                            : '<span class="badge bg-danger">Inactive</span>' !!}</td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-1">
                                                {{-- Edit Button --}}
                                                <button class="btn btn-sm btn-outline-primary py-0 px-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editBuildingModal{{ $building->id }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                {{-- Delete Button --}}
                                                <form action="{{ route('super-admin.buildings.destroy', $building) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Delete this building? All venues inside will be deleted too.')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-danger py-0 px-1"><i
                                                            class="bi bi-trash"></i></button>
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

            {{-- VENUES TABLE --}}
            <div class="col-md-7 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header text-white d-flex justify-content-between align-items-center"
                        style="background: #e87722;">
                        <h5 class="m-0">Venues</h5>
                        <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#addVenueModal">+ Add
                            Venue</button>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover m-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Color</th>
                                    <th>Venue Name</th>
                                    {{-- ✅ DINAGDAG: Bagong column header para sa Room/Floor --}}
                                    <th>Room/Floor</th>
                                    <th>Building</th>
                                    <th>Capacity</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($venues as $venue)
                                    <tr>
                                        <td><span
                                                style="display:inline-block;width:20px;height:20px;border-radius:4px;background:{{ $venue->color ?? '#6c757d' }};"></span>
                                        </td>
                                        <td class="fw-bold">{{ $venue->name }}</td>
                                        {{-- ✅ DINAGDAG: Ipinapakita ang data sa table --}}
                                        <td>{{ $venue->room_floor ?? '—' }}</td>
                                        <td>{{ $venue->building->name ?? 'N/A' }}</td>
                                        <td>{{ $venue->capacity ?? 'N/A' }}</td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-1">
                                                {{-- Edit Button --}}
                                                <button class="btn btn-sm btn-outline-primary py-0 px-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editVenueModal{{ $venue->id }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                {{-- Delete Button --}}
                                                <form action="{{ route('super-admin.venues.destroy', $venue) }}"
                                                    method="POST" onsubmit="return confirm('Delete this venue?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-danger py-0 px-1"><i
                                                            class="bi bi-trash"></i></button>
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
   (Nandito sa labas para hindi masira ang design)
   ========================================== --}}

    {{-- MODAL: Add Building --}}
    <div class="modal fade" id="addBuildingModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('super-admin.buildings.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">Add Building</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Building Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                        <label class="form-check-label">Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-dark">Save Building</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL: Add Venue --}}
    <div class="modal fade" id="addVenueModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('super-admin.venues.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header text-white" style="background: #e87722;">
                    <h5 class="modal-title">Add Venue</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Select Building</label>
                        <select name="building_id" class="form-select" required>
                            <option value="">-- Choose --</option>
                            @foreach ($buildings as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Venue Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    {{-- ✅ DINAGDAG: Room / Floor Input sa Add Modal --}}
                    <div class="mb-3">
                        <label>Room / Floor (Optional)</label>
                        <input type="text" name="room_floor" class="form-control"
                            placeholder="e.g. 2nd Floor, Room 404">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Color Code</label>
                            <input type="color" name="color" class="form-control form-control-color w-100"
                                value="#6c757d">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Capacity</label>
                            <input type="number" name="capacity" class="form-control" min="1">
                        </div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                        <label class="form-check-label">Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn" style="background: #e87722; color: #fff;">Save Venue</button>
                </div>
            </form>
        </div>
    </div>

    {{-- LOOPS PARA SA EDIT MODALS --}}
    @foreach ($buildings as $building)
        <div class="modal fade" id="editBuildingModal{{ $building->id }}" tabindex="-1">
            <div class="modal-dialog">
                <form action="{{ route('super-admin.buildings.update', $building->id) }}" method="POST"
                    class="modal-content">
                    @csrf @method('PUT')
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title">Edit Building</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Building Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $building->name }}"
                                required>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                {{ $building->is_active ? 'checked' : '' }}>
                            <label class="form-check-label">Active</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-dark">Update Building</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    @foreach ($venues as $venue)
        <div class="modal fade" id="editVenueModal{{ $venue->id }}" tabindex="-1">
            <div class="modal-dialog">
                <form action="{{ route('super-admin.venues.update', $venue->id) }}" method="POST"
                    class="modal-content">
                    @csrf @method('PUT')
                    <div class="modal-header text-white" style="background: #e87722;">
                        <h5 class="modal-title">Edit Venue</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Select Building</label>
                            <select name="building_id" class="form-select" required>
                                @foreach ($buildings as $b)
                                    <option value="{{ $b->id }}"
                                        {{ $venue->building_id == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Venue Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $venue->name }}"
                                required>
                        </div>

                        {{-- ✅ DINAGDAG: Room / Floor Input sa Edit Modal --}}
                        <div class="mb-3">
                            <label>Room / Floor (Optional)</label>
                            <input type="text" name="room_floor" class="form-control"
                                value="{{ $venue->room_floor }}" placeholder="e.g. 2nd Floor, Room 404">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Color Code</label>
                                <input type="color" name="color" class="form-control form-control-color w-100"
                                    value="{{ $venue->color ?? '#6c757d' }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Capacity</label>
                                <input type="number" name="capacity" class="form-control" min="1"
                                    value="{{ $venue->capacity }}">
                            </div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                {{ $venue->is_active ? 'checked' : '' }}>
                            <label class="form-check-label">Active</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn" style="background: #e87722; color: #fff;">Update
                            Venue</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

@endsection
