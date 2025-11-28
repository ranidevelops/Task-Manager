@extends('layouts.app')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Overview')

@section('content')
 <div class="dashboard-body">
                                   

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        @if(auth()->user()->user_type !== 'staff')
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon icon-primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stats-number">{{$totalUser}}</div>
                <div class="stats-label">Total Users</div>
            </div>
        </div>
        @endif
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon icon-success">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stats-number">{{$totalTasks}}</div>
                <div class="stats-label">Total Tasks</div>
            </div>
        </div>
         <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon icon-warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stats-number">{{$pending}}</div>
                <div class="stats-label">Pending Tasks</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon icon-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stats-number">{{$completed}}</div>
                <div class="stats-label">Completed Tasks</div>
            </div>
        </div>
    </div>

</div>   
@endsection