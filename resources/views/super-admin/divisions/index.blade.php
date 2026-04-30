@extends('layouts.app')

@section('title', 'Division Management')
@section('page-title', 'Division Management')

@push('styles')
    {{-- DataTables & SweetAlert2 CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')

    <div class="page-header d-flex align-items-center justify-content-between mb-3">
        <h1><i class="bi bi-building me-2"></i>Division Management</h1>
    </div>

    <div class="row">
        <!-- Active Divisions Table (Now Full Width) -->
        <div class="col-12">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0 text-dark">Active Divisions</h5>
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#addDivisionModal">
                            <i class="bi bi-plus-lg me-1"></i>Add Division
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table id="divisionsTable" class="table table-hover align-middle mb-0 w-100">
                            <thead class="table-dark">
                                <tr>
                                    <th class="ps-3">Division Name</th>
                                    <th>Total Users</th>
                                    <th class="text-end pe-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($divisions as $division)
                                    <tr>
                                        <td class="ps-3 fw-semibold">{{ $division->name }}</td>
                                        <td>
                                            <span class="badge bg-secondary px-2 py-1">
                                                {{ $division->users()->count() }} Users
                                            </span>
                                        </td>
                                        <td class="text-end pe-3">
                                            <div class="d-flex justify-content-end gap-1 flex-wrap">
                                                <!-- View Users -->
                                                <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewUsersModal{{ $division->id }}">
                                                    <i class="bi bi-people me-1"></i>Users
                                                </button>

                                                <!-- Edit -->
                                                <button type="button" class="btn btn-sm btn-outline-success py-0 px-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editDivisionModal{{ $division->id }}">
                                                    <i class="bi bi-pencil me-1"></i>Edit
                                                </button>

                                                <!-- Delete (Handled by SweetAlert JS) -->
                                                <form action="{{ route('super-admin.divisions.destroy', $division->id) }}"
                                                    method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
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

    <!-- MODAL: Add Division -->
    <div class="modal fade" id="addDivisionModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:14px;overflow:hidden;border:none;">
                <div class="modal-header" style="background:#1a3c72;color:#fff;border:none;">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle me-2"></i>Add New Division
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('super-admin.divisions.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-2">
                            <label class="form-label fw-semibold small">Division Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                placeholder="e.g. Operations Division" required style="border-radius:8px;font-size:.9rem;">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #f0f2f5;background:#fafbfc;">
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-primary fw-semibold px-3"
                            style="background:#1a3c72; border:none;">
                            <i class="bi bi-save me-1"></i>Save Division
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- LOOPS FOR EDIT & VIEW USERS MODALS --}}
    @foreach ($divisions as $division)
        <!-- MODAL: Edit Division -->
        <div class="modal fade text-start" id="editDivisionModal{{ $division->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius:14px;overflow:hidden;border:none;">
                    <div class="modal-header" style="background:#1a3c72;color:#fff;border:none;">
                        <h5 class="modal-title fw-bold">
                            <i class="bi bi-pencil-square me-2"></i>Edit Division
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('super-admin.divisions.update', $division->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body p-4">
                            <div class="mb-2">
                                <label class="form-label fw-semibold small">Division Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ $division->name }}"
                                    required style="border-radius:8px;font-size:.9rem;">
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

        <!-- MODAL: View Users -->
        <div class="modal fade text-start" id="viewUsersModal{{ $division->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content" style="border-radius:14px;overflow:hidden;border:none;">
                    <div class="modal-header" style="background:#f8f9fa; border-bottom: 1px solid #dee2e6;">
                        <h5 class="modal-title text-dark fw-bold">
                            <i class="bi bi-building me-2 text-primary"></i> Users in {{ $division->name }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-0">
                        @if ($division->users->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th class="text-end pe-4">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($division->users as $user)
                                            <tr>
                                                <td class="ps-4 fw-medium">{{ $user->name }}</td>
                                                <td class="text-muted small">{{ $user->email }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-primary bg-opacity-10 text-primary border border-primary px-2 py-1">
                                                        {{ ucwords(str_replace('_', ' ', $user->role)) }}
                                                    </span>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <a href="{{ route('super-admin.users.edit', $user->id) }}"
                                                        class="btn btn-sm btn-outline-primary py-0 px-2">
                                                        <i class="bi bi-pencil-square me-1"></i>Edit User
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-person-x fs-1 opacity-50 d-block mb-2"></i>
                                No users are currently registered under this division.
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer" style="background:#fafbfc;">
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                            data-bs-dismiss="modal">Close</button>
                    </div>
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

            // 1. Initialize DataTables
            $('#divisionsTable').DataTable({
                destroy: true,
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                order: [
                    [0, 'asc']
                ], // Sort by Name ascending
                columnDefs: [{
                    orderable: false,
                    targets: -1 // Disable sorting on the Actions column
                }],
                language: {
                    search: "Search Divisions:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ divisions",
                    emptyTable: "No divisions found.",
                }
            });

            // 2. SweetAlert2 Toast Notifications (Replaces the duplicate HTML alerts)
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

            @if ($errors->any())
                Toast.fire({
                    icon: 'error',
                    title: "Validation failed. Please check the form."
                });
                // Automatically open the Add modal if there was a validation error
                var addModal = new bootstrap.Modal(document.getElementById('addDivisionModal'));
                addModal.show();
            @endif

            // 3. SweetAlert Delete Confirmation
            $('.delete-form').on('submit', function(e) {
                e.preventDefault();
                const form = this;

                Swal.fire({
                    title: 'Delete Division?',
                    text: "Are you sure you want to delete this division? This action cannot be undone.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="bi bi-trash-fill me-1"></i> Yes, Delete',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

        });
    </script>
@endpush
