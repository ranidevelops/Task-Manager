@extends('layouts.app')

@section('page-title', 'Tasks')
@section('page-subtitle', 'Manage and track all tasks')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Tasks</h1>
        <p class="text-muted mb-0">Manage and track all your tasks in one place</p>
    </div>
    
    @if(auth()->user()->user_type !== 'staff')
    <a href="{{ route('tasks.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>New Task
    </a>
    @endif
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Assignee</th>
                        <th>Priority</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th width="160" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $task)
                        @if(!$task) @continue @endif
                        <tr>
                            <td>
                                <a href="{{ route('tasks.show', $task->id) }}" class="text-decoration-none text-dark fw-semibold">
                                    {{ $task->title ?? 'Untitled Task' }}
                                </a>
                                @if($task->description)
                                    <div class="text-muted small mt-1">
                                        {{ Str::limit($task->description, 80) }}
                                    </div>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex align-items-center">
                                    @if($task->assignee)
                                        <div class="avatar-sm me-2">
                                            <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                                {{ substr($task->assignee->name, 0, 1) }}
                                            </div>
                                        </div>
                                        {{ $task->assignee->name }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </div>
                            </td>

                            <td>
                                @php
                                    $priorityConfig = [
                                        'high' => ['class' => 'danger', 'icon' => 'bi-arrow-up'],
                                        'medium' => ['class' => 'warning', 'icon' => 'bi-dash'],
                                        'low' => ['class' => 'success', 'icon' => 'bi-arrow-down']
                                    ];
                                    $config = $priorityConfig[$task->priority] ?? ['class' => 'secondary', 'icon' => 'bi-circle'];
                                @endphp
                                <span class="badge bg-{{ $config['class'] }} bg-opacity-10 text-{{ $config['class'] }} border border-{{ $config['class'] }} border-opacity-25">
                                    <i class="bi {{ $config['icon'] }} me-1"></i>{{ ucfirst($task->priority ?? 'NA') }}
                                </span>
                            </td>

                            <td>
                                @if($task->deadline)
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-calendar3 text-muted me-2"></i>
                                        <div>
                                            <div>{{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}</div>
                                            <div class="small text-muted">{{ \Carbon\Carbon::parse($task->deadline)->format('H:i') }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <td>
                                @php
                                    $statusConfig = [
                                        'completed' => ['class' => 'success', 'icon' => 'bi-check-circle'],
                                        'in_progress' => ['class' => 'primary', 'icon' => 'bi-arrow-clockwise'],
                                        'pending' => ['class' => 'secondary', 'icon' => 'bi-clock']
                                    ];
                                    $status = $statusConfig[$task->status] ?? ['class' => 'secondary', 'icon' => 'bi-circle'];
                                @endphp
                                <span class="badge bg-{{ $status['class'] }} bg-opacity-10 text-{{ $status['class'] }} border border-{{ $status['class'] }} border-opacity-25">
                                    <i class="bi {{ $status['icon'] }} me-1"></i>{{ ucfirst($task->status ?? 'unknown') }}
                                </span>
                            </td>

                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- View Button - Always allowed --}}
                                    <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-sm btn-outline-info" 
                                       data-bs-toggle="tooltip" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    {{-- Edit Button - Only for non-staff users --}}
                                    @if(auth()->user()->user_type !== 'staff')
                                        <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-outline-primary"
                                           data-bs-toggle="tooltip" title="Edit Task">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endif

                                    {{-- Delete Button - Only for non-staff users --}}
                                    @if(auth()->user()->user_type !== 'staff')
                                        <button type="button" 
                                            class="btn btn-sm btn-outline-danger delete-task-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteTaskModal"
                                            data-task-id="{{ $task->id }}"
                                            data-task-title="{{ $task->title }}"
                                            data-bs-toggle="tooltip" title="Delete Task">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @else
                                        {{-- Disabled Delete Button for Staff --}}
                                        <button class="btn btn-sm btn-outline-secondary" disabled
                                                data-bs-toggle="tooltip" title="Delete not allowed for staff">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-inbox display-4 text-muted"></i>
                                <h5 class="text-muted mt-3">No tasks found</h5>
                                <p class="text-muted">Get started by creating your first task</p>
                                @if(auth()->user()->user_type !== 'staff')
                                    <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                                        <i class="bi bi-plus-circle me-2"></i>Create Task
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($tasks->hasPages())
        <div class="card-footer bg-light py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing {{ $tasks->firstItem() }} to {{ $tasks->lastItem() }} of {{ $tasks->total() }} results
                </div>
                {{ $tasks->links() }}
            </div>
        </div>
    @endif
</div>

{{-- Delete Confirmation Modal --}}
@if(auth()->user()->user_type !== 'staff')
<div class="modal fade" id="deleteTaskModal" tabindex="-1" aria-labelledby="deleteTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <div class="modal-icon-wrapper bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                    <i class="bi bi-exclamation-triangle-fill text-danger fs-4"></i>
                </div>
                <h5 class="modal-title" id="deleteTaskModalLabel">Delete Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="mb-1">Are you sure you want to delete this task?</p>
                <p class="fw-semibold text-danger mb-0" id="task-title-placeholder">Task Title</p>
                <p class="small text-muted mt-2">This action cannot be undone. All associated documents and data will be permanently removed.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="delete-task-form" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i> Delete Task
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<style>
.avatar-sm {
    width: 28px;
    height: 28px;
}
.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 600;
}
.badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.65rem;
}
.btn-sm {
    padding: 0.25rem 0.5rem;
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
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    // Delete task modal handler
    $('.delete-task-btn').on('click', function () {
        let taskId = $(this).data('task-id');
        let taskTitle = $(this).data('task-title');

        $('#task-title-placeholder').text(taskTitle);

        let url = "{{ route('tasks.destroy', ':id') }}".replace(':id', taskId);
        $('#delete-task-form').attr('action', url);
    });

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection