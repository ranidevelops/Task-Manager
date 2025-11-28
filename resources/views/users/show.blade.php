@extends('layouts.app')

@section('page-title', $user->name)
@section('page-subtitle', 'User details and activities')

@section('content')
<div class="row g-4">
    <!-- User Profile -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <div class="avatar-lg bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                    <span class="text-primary fs-2 fw-bold">{{ substr($user->name, 0, 1) }}</span>
                </div>
                <h4 class="mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-3">{{ $user->email }}</p>
                
                @php
                    $role = method_exists($user, 'getRoleNames') && $user->getRoleNames()->isNotEmpty() 
                            ? $user->getRoleNames()->first() 
                            : 'user';
                    $roleColor = $role === 'admin' ? 'danger' : 'primary';
                @endphp
                <span class="badge bg-{{ $roleColor }} bg-opacity-10 text-{{ $roleColor }} border fs-6 mb-3">
                    {{ ucfirst($role) }}
                </span>

                <div class="row mt-4 text-start">
                    <div class="col-6">
                        <div class="border-end">
                            <div class="h5 mb-1">{{ $user->createdTasks->count() }}</div>
                            <small class="text-muted">Created Tasks</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="h5 mb-1">{{ $user->assignedTasks->count() }}</div>
                        <small class="text-muted">Assigned Tasks</small>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="text-muted small">
                        <i class="bi bi-calendar me-1"></i>
                        Member since {{ $user->created_at->format('M d, Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Activities -->
    <div class="col-lg-8">
        <!-- Assigned Tasks -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-light py-3">
                <h5 class="card-title mb-0">
                    <i class="bi bi-list-task text-primary me-2"></i>Assigned Tasks
                </h5>
            </div>
            <div class="card-body">
                @if($userTasks->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($userTasks as $task)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <a href="{{ route('tasks.show', $task) }}" class="text-decoration-none">
                                            <h6 class="mb-1">{{ $task->title }}</h6>
                                        </a>
                                        <div class="d-flex align-items-center gap-3 mb-2">
                                            <span class="badge bg-{{ $task->status === 'completed' ? 'success' : ($task->status === 'in_progress' ? 'primary' : 'secondary') }} bg-opacity-10 text-{{ $task->status === 'completed' ? 'success' : ($task->status === 'in_progress' ? 'primary' : 'secondary') }}">
                                                {{ ucfirst($task->status) }}
                                            </span>
                                            <span class="text-muted small">
                                                <i class="bi bi-person me-1"></i>Created by {{ $task->creator->name }}
                                            </span>
                                        </div>
                                    </div>
                                    @if($task->deadline)
                                        <div class="text-end">
                                            <div class="small text-muted">Due</div>
                                            <div class="fw-semibold {{ $task->deadline->isPast() && $task->status !== 'completed' ? 'text-danger' : '' }}">
                                                {{ $task->deadline->format('M d') }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox display-6 text-muted"></i>
                        <p class="text-muted mt-2">No tasks assigned to this user</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Created Tasks -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light py-3">
                <h5 class="card-title mb-0">
                    <i class="bi bi-plus-circle text-primary me-2"></i>Created Tasks
                </h5>
            </div>
            <div class="card-body">
                @if($createdTasks->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($createdTasks as $task)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <a href="{{ route('tasks.show', $task) }}" class="text-decoration-none">
                                            <h6 class="mb-1">{{ $task->title }}</h6>
                                        </a>
                                        <div class="d-flex align-items-center gap-3 mb-2">
                                            <span class="badge bg-{{ $task->status === 'completed' ? 'success' : ($task->status === 'in_progress' ? 'primary' : 'secondary') }} bg-opacity-10 text-{{ $task->status === 'completed' ? 'success' : ($task->status === 'in_progress' ? 'primary' : 'secondary') }}">
                                                {{ ucfirst($task->status) }}
                                            </span>
                                            @if($task->assignee)
                                                <span class="text-muted small">
                                                    <i class="bi bi-person-check me-1"></i>Assigned to {{ $task->assignee->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    @if($task->deadline)
                                        <div class="text-end">
                                            <div class="small text-muted">Due</div>
                                            <div class="fw-semibold {{ $task->deadline->isPast() && $task->status !== 'completed' ? 'text-danger' : '' }}">
                                                {{ $task->deadline->format('M d') }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox display-6 text-muted"></i>
                        <p class="text-muted mt-2">No tasks created by this user</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.avatar-lg {
    width: 80px;
    height: 80px;
}
.card {
    border-radius: 0.5rem;
}
.list-group-item {
    border: none;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}
.list-group-item:last-child {
    border-bottom: none;
}
</style>
@endsection