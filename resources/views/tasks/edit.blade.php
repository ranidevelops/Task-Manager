@extends('layouts.app')

@section('content')
<div class="card">
  <div class="card-body">
    <h5 class="card-title">Edit Task</h5>

    <form action="{{ route('tasks.update', $task) }}" method="POST">
      @csrf @method('PUT')

      <div class="mb-3">
        <label class="form-label">Title</label>
        <input name="title" value="{{ old('title', $task->title) }}" class="form-control @error('title') is-invalid @enderror" required>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="4">{{ old('description', $task->description) }}</textarea>
      </div>

      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Priority</label>
          <select name="priority" class="form-select">
            <option value="low" {{ $task->priority=='low'?'selected':'' }}>Low</option>
            <option value="medium" {{ $task->priority=='medium'?'selected':'' }}>Medium</option>
            <option value="high" {{ $task->priority=='high'?'selected':'' }}>High</option>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Deadline</label>
          <input type="datetime-local" name="deadline" class="form-control" value="{{ old('deadline', optional($task->deadline)->format('Y-m-d\TH:i')) }}">
        </div>

        <div class="col-md-4">
          <label class="form-label">Assign To</label>
          <select name="assignee_id" class="form-select">
            <option value="">Unassigned</option>
            @foreach($users as $u)
              <option value="{{ $u->id }}" {{ (old('assignee_id', $task->assignee_id) == $u->id) ? 'selected' : '' }}>{{ $u->name }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="mt-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="pending" {{ $task->status=='pending'?'selected':'' }}>Pending</option>
          <option value="in_progress" {{ $task->status=='in_progress'?'selected':'' }}>In Progress</option>
          <option value="completed" {{ $task->status=='completed'?'selected':'' }}>Completed</option>
          <option value="cancelled" {{ $task->status=='cancelled'?'selected':'' }}>Cancelled</option>
        </select>
      </div>

      <div class="mt-4 text-end">
        <a href="{{ route('tasks.show', $task) }}" class="btn btn-secondary me-2">Cancel</a>
        <button class="btn btn-primary">Save Changes</button>
      </div>
    </form>
  </div>
</div>
@endsection
