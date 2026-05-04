@extends('layouts.app')

@section('title', 'User Management')
@section('page-title', 'User Management')

@push('styles')
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        #usersTable thead th {
            background: #212529;
            color: #fff;
            border-color: #373b3e;
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

        #usersTable {
            border-collapse: separate !important;
            border-spacing: 0 8px !important;
            width: 100% !important;
        }

        #usersTable thead th {
            background-color: #212529 !important;
            color: white !important;
            padding: 15px 10px !important;
            border: none !important;
        }

        #usersTable tbody td {
            padding: 12px 10px !important;
            background-color: #ffffff;
            border-top: 1px solid #dee2e6 !important;
            border-bottom: 1px solid #dee2e6 !important;
            vertical-align: middle !important;
        }

        .col-id {
            width: 50px;
        }

        .col-name {
            width: 200px;
        }

        .col-email {
            width: 220px;
        }

        .col-role {
            width: 120px;
        }

        .col-div {
            width: 120px;
        }

        .col-status {
            width: 100px;
        }

        .col-actions {
            width: 280px;
        }

        .btn-group-gap {
            display: flex;
            gap: 6px;
            justify-content: center;
            flex-wrap: nowrap;

        }
    </style>
@endpush

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center">
        <h1><i class="bi bi-people me-2"></i>User Management</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="usersTable" class="table table-hover align-middle w-100 mb-0">
                    <thead>
                        <tr>
                            <th class="text-center col-id">#</th>
                            <th class="col-name text-start">Name</th>
                            <th class="col-email text-start">Email</th>
                            <th class="text-center col-role">Role</th>
                            <th class="text-center col-div">Division</th>
                            <th class="text-center col-status">Status</th>
                            <th class="text-center col-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $user->name }}</div>
                                    @if ($user->contact_number)
                                        <div class="text-muted small">{{ $user->contact_number }}</div>
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @php
                                        $roleColors = [
                                            \App\Models\User::ROLE_SUPER_ADMIN => 'danger',
                                            \App\Models\User::ROLE_ADMIN => 'warning',
                                            \App\Models\User::ROLE_USER => 'secondary',
                                        ];
                                        $color = $roleColors[$user->role] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $color }}">{{ strtoupper($user->role) }}</span>
                                </td>

                                {{-- UPDATED: Now points to the division name --}}
                                <td>{{ $user->division->name ?? '—' }}</td>

                                {{-- STATUS COLUMN --}}
                                <td class="text-center"> {{-- ← ADD THIS --}}
                                    @if (!$user->is_active)
                                        <span class="badge bg-danger">Deactivated</span>
                                    @elseif (is_null($user->email_verified_at))
                                        <span class="badge bg-secondary">Unverified</span>
                                    @elseif ($user->isPending())
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif ($user->isRejected())
                                        <span class="badge bg-dark">Rejected</span>
                                    @else
                                        <span class="badge bg-success">Active</span>
                                    @endif
                                </td>
                                {{-- END STATUS COLUMN --}}

                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1 flex-wrap">

                                        {{-- View --}}
                                        <a href="{{ route('super-admin.users.show', $user) }}"
                                            class="btn btn-sm btn-outline-primary px-2">
                                            <i class="bi bi-eye me-1"></i>View
                                        </a>

                                        {{-- Edit --}}
                                        <a href="{{ route('super-admin.users.edit', $user) }}"
                                            class="btn btn-sm btn-outline-warning px-2">
                                            <i class="bi bi-pencil me-1"></i>Edit
                                        </a>


                                        {{-- Deactivate / Activate --}}
                                        @if ($user->is_active)
                                            <form method="POST" id="deactivateForm{{ $user->id }}"
                                                action="{{ route('super-admin.users.deactivate', $user) }}">
                                                @csrf @method('PATCH')
                                                <button type="button" class="btn btn-sm btn-outline-secondary px-2"
                                                    onclick="confirmDeactivate({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                                    <i class="bi bi-person-dash me-1"></i>Deactivate
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" id="activateForm{{ $user->id }}"
                                                action="{{ route('super-admin.users.activate', $user) }}">
                                                @csrf @method('PATCH')
                                                <button type="button" class="btn btn-sm btn-outline-success px-2"
                                                    onclick="confirmActivate({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                                    <i class="bi bi-person-check me-1"></i>Activate
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Approve / Reject (For Pending Users Only) --}}
                                        @if ($user->isPending())
                                            <form method="POST" id="approveForm{{ $user->id }}"
                                                action="{{ route('super-admin.users.approve', $user) }}">
                                                @csrf @method('PATCH')
                                                <button type="button" class="btn btn-sm btn-outline-success px-2"
                                                    onclick="confirmApprove({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                                    <i class="bi bi-check-circle me-1"></i>Approve
                                                </button>
                                            </form>

                                            <form method="POST" id="rejectForm{{ $user->id }}"
                                                action="{{ route('super-admin.users.user-reject', $user) }}">
                                                @csrf @method('PATCH')
                                                <button type="button" class="btn btn-sm btn-outline-danger px-2"
                                                    onclick="confirmReject({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                                    <i class="bi bi-x-circle me-1"></i>Reject
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Delete --}}
                                        @if ($user->role !== \App\Models\User::ROLE_SUPER_ADMIN)
                                            <form method="POST" id="deleteForm{{ $user->id }}"
                                                action="{{ route('super-admin.users.destroy', $user) }}">
                                                @csrf @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-outline-danger px-2"
                                                    onclick="confirmDelete({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                                    <i class="bi bi-trash me-1"></i>Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        // ── DataTables ──────────────────────────────────────────
        $(document).ready(function() {
            $('#usersTable').DataTable({
                destroy: true,
                order: [
                    [0, 'desc']
                ],
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                columnDefs: [{
                    orderable: false,
                    targets: 6
                }],
                language: {
                    search: 'Search:',
                    lengthMenu: 'Show _MENU_ entries',
                    info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                    paginate: {
                        previous: 'Previous',
                        next: 'Next'
                    }
                }
            });
        });


        // ── SweetAlert: Approve ────────────────────────────────
        function confirmApprove(id, name) {
            Swal.fire({
                title: 'Approve User?',
                html: `Are you sure you want to approve <strong>${name}</strong>?<br><span class="text-muted small">They will now have access to the system.</span>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-check-circle me-1"></i> Yes, Approve',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('approveForm' + id).submit();
                }
            });
        }

        // ── SweetAlert: Reject ──────────────────────────────────
        function confirmReject(id, name) {
            Swal.fire({
                title: 'Reject User?',
                html: `Are you sure you want to reject <strong>${name}</strong>?<br><span class="text-danger small">They will be denied access to the system.</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-x-circle me-1"></i> Yes, Reject',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('rejectForm' + id).submit();
                }
            });
        }

        // ── SweetAlert: Deactivate ──────────────────────────────
        function confirmDeactivate(id, name) {
            Swal.fire({
                title: 'Deactivate User?',
                html: `Are you sure you want to deactivate <strong>${name}</strong>?<br><span class="text-muted small">They will no longer be able to log in.</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6c757d',
                cancelButtonColor: '#1a3c72',
                confirmButtonText: '<i class="bi bi-person-dash me-1"></i> Yes, Deactivate',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deactivateForm' + id).submit();
                }
            });
        }

        // ── SweetAlert: Activate ────────────────────────────────
        function confirmActivate(id, name) {
            Swal.fire({
                title: 'Activate User?',
                html: `Are you sure you want to activate <strong>${name}</strong>?<br><span class="text-muted small">They will be able to log in again.</span>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-person-check me-1"></i> Yes, Activate',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('activateForm' + id).submit();
                }
            });
        }

        // ── SweetAlert: Delete ──────────────────────────────────
        function confirmDelete(id, name) {
            Swal.fire({
                title: 'Delete User?',
                html: `You are about to permanently delete <strong>${name}</strong>.<br><span class="text-danger small">This action cannot be undone.</span>`,
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-trash me-1"></i> Yes, Delete',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm' + id).submit();
                }
            });
        }

        // ── SweetAlert: Flash success/error messages ────────────
        @if (session('success'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        @endif

        @if (session('error'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
            });
        @endif
    </script>
@endpush
