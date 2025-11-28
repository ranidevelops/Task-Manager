@extends('layouts.app')

@section('content')
<div class="card">
  <div class="card-body">
    <h5 class="card-title">Create Task</h5>

    <form action="{{ route('tasks.store') }}" method="POST" novalidate>
      @csrf

      <!-- TITLE -->
      <div class="mb-3">
        <label class="form-label">Title</label>
        <input
            name="title"
            value="{{ old('title') }}"
            class="form-control @error('title') is-invalid @enderror"
            required
        >
        @error('title')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- DESCRIPTION -->
      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea
            name="description"
            class="form-control @error('description') is-invalid @enderror"
            rows="4"
        >{{ old('description') }}</textarea>

        @error('description')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="row g-3">
        <!-- PRIORITY -->
        <div class="col-md-4">
          <label class="form-label">Priority</label>
          <select name="priority" class="form-select @error('priority') is-invalid @enderror">
            <option value="low" {{ old('priority')=='low' ? 'selected':'' }}>Low</option>
            <option value="medium" {{ old('priority','medium')=='medium' ? 'selected':'' }}>Medium</option>
            <option value="high" {{ old('priority')=='high' ? 'selected':'' }}>High</option>
          </select>
          @error('priority') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- DEADLINE -->
        <div class="col-md-4">
          <label class="form-label">Deadline</label>
          <input
            type="datetime-local"
            name="deadline"
            value="{{ old('deadline') }}"
            class="form-control @error('deadline') is-invalid @enderror"
          >
          @error('deadline') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- ASSIGNEE -->
        <div class="col-md-4">
          <label class="form-label">Assign To</label>
          <select name="assignee_id" class="form-select @error('assignee_id') is-invalid @enderror">
            <option value="">Unassigned</option>
            @foreach($users as $u)
              <option value="{{ $u->id }}" {{ old('assignee_id') == $u->id ? 'selected':'' }}>{{ $u->name }}</option>
            @endforeach
          </select>
          @error('assignee_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="mt-4 text-end">
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary me-2">Cancel</a>
        <button class="btn btn-primary">Create Task</button>
      </div>
    </form>
  </div>
</div>
@endsection
