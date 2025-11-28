@extends('layouts.app')

@section('page-title', 'Users Management')
@section('page-subtitle', 'Manage system users and their roles')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-light py-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="card-title mb-0">
                    <i class="bi bi-people-fill text-primary me-2"></i>Users
                </h5>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-end gap-2">
                    <form method="GET" class="d-flex me-2">
                        <input type="text" name="search" class="form-control form-control-sm" 
                               placeholder="Search users..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary btn-sm ms-2">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>Add User
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Tasks Assigned</th>
                        <th>Created</th>
                        <th width="120" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $user->name }}</div>
                                        @if($user->id === auth()->id())
                                            <span class="badge bg-info bg-opacity-10 text-info">You</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @php
                                    $role = method_exists($user, 'getRoleNames') && $user->getRoleNames()->isNotEmpty() 
                                        ? $user->getRoleNames()->first() 
                                        : 'user';
                                    $roleColor = $role === 'admin' ? 'danger' : 'primary';
                                @endphp
                                <span class="badge bg-{{ $roleColor }} bg-opacity-10 text-{{ $roleColor }} border">
                                    {{ ucfirst($role) }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $user->assignedTasks->count() }}</span>
                                <small class="text-muted">assigned</small>
                            </td>
                            <td>
                                <div class="small text-muted">
                                    {{ $user->created_at->format('M d, Y') }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-info" 
                                       data-bs-toggle="tooltip" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary"
                                       data-bs-toggle="tooltip" title="Edit User">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                data-bs-toggle="modal" data-bs-target="#deleteUserModal"
                                                data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}"
                                                data-bs-toggle="tooltip" title="Delete User">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-outline-secondary" disabled
                                                data-bs-toggle="tooltip" title="Cannot delete yourself">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif

                                    @if($user->user_type !== 'manager' && $user->id !== auth()->id())
                                        <button type="button" class="btn btn-sm btn-outline-warning"
                                                data-bs-toggle="modal"
                                                data-bs-target="#makeManagerModal"
                                                data-user-id="{{ $user->id }}"
                                                data-user-name="{{ $user->name }}"
                                                title="Make Manager">
                                            <i class="bi bi-star-fill"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-people display-4 text-muted"></i>
                                <h5 class="text-muted mt-3">No users found</h5>
                                <p class="text-muted">Get started by creating the first user</p>
                                <a href="{{ route('users.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-2"></i>Add User
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($users->hasPages())
        <div class="card-footer bg-light py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results
                </div>
                {{ $users->links() }}
            </div>
        </div>
    @endif
</div>

<!-- Delete User Confirmation Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <div class="modal-icon-wrapper bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                    <i class="bi bi-person-x-fill text-danger fs-4"></i>
                </div>
                <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="mb-1">Are you sure you want to delete this user?</p>
                <p class="fw-semibold text-danger mb-0" id="user-name-placeholder">User Name</p>
                <p class="small text-muted mt-2">
                    This action cannot be undone. All tasks assigned to this user will be unassigned.
                </p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="delete-user-form" method="POST" class="d-inline">
                    @csrf 
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i> Delete User
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Make Manager Modal -->
<div class="modal fade" id="makeManagerModal" tabindex="-1" aria-labelledby="makeManagerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <div class="modal-icon-wrapper bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                    <i class="bi bi-star-fill text-warning fs-4"></i>
                </div>
                <h5 class="modal-title" id="makeManagerModalLabel">Make User a Manager</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body py-4">
                <p>Are you sure you want to make this user a manager?</p>
                <p class="fw-semibold text-warning mb-0" id="make-manager-user-name">User Name</p>
                <p class="small text-muted mt-2">
                    The selected user will gain manager privileges.
                </p>
            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>

                <form id="make-manager-form" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-star-fill me-1"></i> Confirm
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


<style>
.avatar-sm {
    width: 36px;
    height: 36px;
}
.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}
.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6c757d;
}
.modal-icon-wrapper {
    flex-shrink: 0;
}
</style>
<script>
$(document).ready(function () {
    $('#deleteUserModal').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget);
        let userId = button.data('user-id');
        let userName = button.data('user-name');
        let deleteUrl = "{{ route('users.destroy', ':id') }}".replace(':id', userId);

        $('#user-name-placeholder').text(userName);
        $('#delete-user-form').attr('action', deleteUrl);
    });
    $('#makeManagerModal').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget);
        let userId = button.data('user-id');
        let userName = button.data('user-name');
        let makeManagerUrl = "{{ route('users.makeManager', ':id') }}".replace(':id', userId);

        $('#make-manager-user-name').text(userName);
        $('#make-manager-form').attr('action', makeManagerUrl);
    });
    $('[data-bs-toggle="tooltip"]').tooltip();

});
</script>
@endsection