@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center mb-2">
                        <h1 class="h3 mb-0 me-3">{{ $task->title }}</h1>
                        <span class="badge bg-{{ $task->status == 'completed' ? 'success' : ($task->status == 'in_progress' ? 'primary' : 'secondary') }} fs-6">
                            {{ ucfirst($task->status) }}
                        </span>
                    </div>
                    <div class="text-muted">
                        <i class="bi bi-person-circle me-1"></i> Created by {{ $task->creator->name }} 
                        <i class="bi bi-clock ms-3 me-1"></i> {{ $task->created_at->diffForHumans() }}
                    </div>
                </div>
                
                <div class="btn-group">
                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </a>
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteTaskModal">
                        <i class="bi bi-trash me-1"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Content Column -->
        <div class="col-lg-8">
            <!-- Description Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light py-3">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-text-paragraph text-primary me-2"></i>Description
                    </h5>
                </div>
                <div class="card-body">
                    <p class="card-text">{{ $task->description ?? 'No description provided.' }}</p>
                </div>
            </div>
            
            <!-- Attachments Section -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-paperclip text-primary me-2"></i>Attachments
                    </h5>
                    <span class="badge bg-primary">{{ $task->documents->count() }}</span>
                </div>
                
                <div class="card-body">
                    <!-- Upload Form -->
                    <div class="mb-4 p-3 bg-light rounded">
                        <h6 class="mb-3">Upload New File</h6>
                        <form method="POST" action="{{ route('tasks.documents.store', $task) }}" enctype="multipart/form-data" class="row g-2 align-items-center">
                            @csrf
                            <div class="col-md-8">
                                <input class="form-control @error('document') is-invalid @enderror" type="file" name="document" required>
                                @error('document') 
                                    <div class="invalid-feedback">{{ $message }}</div> 
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary w-100">
                                    <i class="bi bi-upload me-1"></i> Upload
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Documents List -->
                    @if($task->documents->isEmpty())
                        <div class="text-center py-4">
                            <i class="bi bi-inbox display-4 text-muted"></i>
                            <p class="text-muted mt-2">No documents attached to this task.</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($task->documents as $doc)
                                <div class="list-group-item d-flex justify-content-between align-items-start px-0">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="bi bi-file-earmark-text display-6 text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $doc->original_name }}</div>
                                            <div class="small text-muted">
                                                {{ round($doc->size / 1024, 2) }} KB • {{ $doc->mime }}
                                            </div>
                                            <div class="small text-muted">
                                                <i class="bi bi-person me-1"></i> Uploaded by {{ $doc->uploader?->name ?? '—' }} 
                                                <i class="bi bi-clock ms-2 me-1"></i> {{ $doc->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="btn-group">
                                        <a href="{{ route('documents.download', $doc) }}" class="btn btn-outline-success btn-sm">
                                            <i class="bi bi-download me-1"></i> Download
                                        </a>

                                        @php
                                            $user = auth()->user();
                                            $canDelete = (method_exists($user,'hasRole') && $user->hasRole('admin'))
                                                         || $doc->uploaded_by === $user->id
                                                         || $task->creator_id === $user->id;
                                        @endphp

                                        @if($canDelete)
                                            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteDocumentModal" data-document-id="{{ $doc->id }}" data-document-name="{{ $doc->original_name }}">
                                                <i class="bi bi-trash me-1"></i> Delete
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Sidebar Column -->
        <div class="col-lg-4">
            <!-- Task Details Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light py-3">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-info-circle text-primary me-2"></i>Task Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-1">
                            <i class="bi bi-person-check text-muted me-2"></i>
                            <strong class="me-2">Assignee:</strong>
                            <span>{{ $task->assignee?->name ?? 'Unassigned' }}</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-1">
                            <i class="bi bi-flag text-muted me-2"></i>
                            <strong class="me-2">Priority:</strong>
                            @php
                                $priorityColors = [
                                    'low' => 'success',
                                    'medium' => 'warning',
                                    'high' => 'danger'
                                ];
                            @endphp
                            <span class="badge bg-{{ $priorityColors[$task->priority] ?? 'secondary' }}">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-1">
                            <i class="bi bi-calendar-event text-muted me-2"></i>
                            <strong class="me-2">Deadline:</strong>
                            @if($task->deadline)
                                <span class="{{ \Carbon\Carbon::parse($task->deadline)->isPast() && $task->status != 'completed' ? 'text-danger' : '' }}">
                                    {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y, H:i') }}
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-0">
                        <div class="d-flex align-items-center mb-1">
                            <i class="bi bi-circle-fill text-muted me-2"></i>
                            <strong class="me-2">Status:</strong>
                            <span class="badge bg-{{ $task->status == 'completed' ? 'success' : ($task->status == 'in_progress' ? 'primary' : 'secondary') }}">
                                {{ ucfirst($task->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Activity Card (Optional) -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light py-3">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clock-history text-primary me-2"></i>Recent Activity
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center py-3">
                        <i class="bi bi-activity display-6 text-muted"></i>
                        <p class="text-muted mt-2 small">Activity tracking coming soon</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Task Confirmation Modal -->
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
                <p class="fw-semibold text-danger mb-0">"{{ $task->title }}"</p>
                <p class="small text-muted mt-2">This action cannot be undone. All associated documents and data will be permanently removed.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline">
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

<!-- Delete Document Confirmation Modal -->
<div class="modal fade" id="deleteDocumentModal" tabindex="-1" aria-labelledby="deleteDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <div class="modal-icon-wrapper bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                    <i class="bi bi-file-earmark-excel-fill text-danger fs-4"></i>
                </div>
                <h5 class="modal-title" id="deleteDocumentModalLabel">Delete Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="mb-1">Are you sure you want to delete this document?</p>
                <p class="fw-semibold text-danger mb-0" id="document-name">Document Name</p>
                <p class="small text-muted mt-2">This action cannot be undone. The file will be permanently removed from the system.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="delete-document-form" method="POST" class="d-inline">
                    @csrf 
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i> Delete Document
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 0.5rem;
    }
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,.05);
    }
    .btn {
        border-radius: 0.375rem;
    }
    .list-group-item {
        border: none;
        border-bottom: 1px solid rgba(0,0,0,.05);
    }
    .list-group-item:last-child {
        border-bottom: none;
    }
    .modal-icon-wrapper {
        flex-shrink: 0;
    }
</style>

<script>
    // JavaScript for document deletion modal
    document.addEventListener('DOMContentLoaded', function() {
        const deleteDocumentModal = document.getElementById('deleteDocumentModal');
        const documentNameElement = document.getElementById('document-name');
        const deleteDocumentForm = document.getElementById('delete-document-form');
        
        if (deleteDocumentModal) {
            deleteDocumentModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const documentId = button.getAttribute('data-document-id');
                const documentName = button.getAttribute('data-document-name');
                const deleteUrl = "{{ route('documents.destroy', ':id') }}".replace(':id', documentId);
                
                documentNameElement.textContent = documentName;
                deleteDocumentForm.action = deleteUrl;
            });
        }
    });
</script>
@endsection