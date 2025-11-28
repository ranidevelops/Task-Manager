@extends('layouts.app')

@section('page-title', isset($user) ? 'Edit User' : 'Create User')
@section('page-subtitle', isset($user) ? 'Update user information' : 'Add a new user to the system')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light py-3">
                <h5 class="card-title mb-0">
                    <i class="bi bi-{{ isset($user) ? 'person-gear' : 'person-plus' }} text-primary me-2"></i>
                    {{ isset($user) ? 'Edit User' : 'Create New User' }}
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}">
                    @csrf
                    @if(isset($user))
                        @method('PUT')
                    @endif

                    <div class="row g-3">
                        <!-- Name -->
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name ?? '') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="col-md-6">
                            <label for="password" class="form-label">
                                {{ isset($user) ? 'New Password' : 'Password' }} 
                                {{ isset($user) ? '(leave blank to keep current)' : '*' }}
                            </label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" {{ isset($user) ? '' : 'required' }}>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">
                                Confirm Password {{ isset($user) ? '' : '*' }}
                            </label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" 
                                   {{ isset($user) ? '' : 'required' }}>
                        </div>

                        <!-- Role -->
                        <div class="col-12">
                            <label for="role" class="form-label">Role *</label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="">Select Role</option>
                                <option value="user" {{ old('role', (isset($user) && method_exists($user, 'hasRole') && $user->hasRole('user')) ? 'selected' : '') }}>
                                    User
                                </option>
                                <option value="admin" {{ old('role', (isset($user) && method_exists($user, 'hasRole') && $user->hasRole('admin')) ? 'selected' : '') }}>
                                    Administrator
                                </option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-{{ isset($user) ? 'check-circle' : 'plus-circle' }} me-2"></i>
                            {{ isset($user) ? 'Update User' : 'Create User' }}
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary ms-2">
                            <i class="bi bi-arrow-left me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection