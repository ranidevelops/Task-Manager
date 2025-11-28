<!doctype html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ $title ?? config('app.name','Task Manager') }}</title>
  <link href="{{ asset('build/assets/boostrap/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('build/assets/css/dashboard.css') }}" rel="stylesheet">
  
  <!-- Load jQuery first -->
  <script src="{{asset('build/assets/js/jquery-3.6.0.min.js')}}"></script>
  <!-- Then load Bootstrap bundle -->
  <script src="{{ asset('build/assets/boostrap/bootstrap.bundle.min.js')}}"></script>
  
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body{background:#f6fbff}
    .container-main{max-width:1100px;margin-top:36px;margin-bottom:36px}
    .back-btn {
        background: rgba(13, 110, 253, 0.1);
        border: 1px solid rgba(13, 110, 253, 0.2);
        color: #0d6efd;
        transition: all 0.3s ease;
    }
    .back-btn:hover {
        background: rgba(13, 110, 253, 0.2);
        border-color: rgba(13, 110, 253, 0.3);
        color: #0d6efd;
        transform: translateX(-2px);
    }
    .page-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(0,0,0,0.1);
    }

    /* Fix for dropdown styling */
    .user-dropdown {
        background: none;
        border: none;
        color: white;
        padding: 0.5rem 1rem;
    }
    .user-dropdown:focus {
        box-shadow: none;
        background: rgba(255,255,255,0.1);
    }
    .user-dropdown::after {
        display: none;
    }
    .logout-btn {
        background: none;
        border: none;
        width: 100%;
        text-align: left;
        padding: 0;
    }
    .logout-btn:hover {
        background: none;
        color: inherit;
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        .back-btn {
            align-self: flex-start;
        }
    }
  </style>
</head>
<body>
  <!-- Dashboard Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark" style="background:#0d6efd">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{url('/dashboard')}}">
            <i class="fas fa-rocket me-2"></i>
            {{ config('app.name', 'Task Manager') }}
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item d-lg-none">
                    <a class="nav-link" href="#">
                        <i class="fas fa-bell"></i>
                        <span class="badge bg-danger ms-1">3</span>
                    </a>
                </li>
                <li class="nav-item d-lg-none">
                    <a class="nav-link" href="#">
                        <i class="fas fa-cog"></i>
                    </a>
                </li>
            </ul>
            
            <!-- Fixed Dropdown Structure -->
            <div class="dropdown ms-3 d-none d-lg-block">
                <a class="nav-link dropdown-toggle user-dropdown" href="#" role="button" 
                   data-bs-toggle="dropdown" aria-expanded="false" style="color: white;">
                    <i class="fas fa-user-circle me-1"></i>
                    {{ Auth::user()->name ?? 'User' }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#">
                        <i class="fas fa-user me-2"></i>Profile
                    </a></li>
                    <li><a class="dropdown-item" href="#">
                        <i class="fas fa-cog me-2"></i>Settings
                    </a></li>
                    <li><a class="dropdown-item" href="#">
                        <i class="fas fa-bell me-2"></i>Notifications
                        <span class="badge bg-danger ms-2">3</span>
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>Log Out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-2 col-md-3 sidebar">
            <div class="sidebar-sticky pt-3">
                <div class="sidebar-header">Main Menu</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Dashboard
                        </a>
                    </li>
                    @if(method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('admin'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                            <i class="fas fa-users me-2"></i>Users
                        </a>
                    </li>
                    @endif
                   
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}" href="{{ route('tasks.index')}}">
                            <i class="fas fa-tasks me-2"></i>
                            Tasks
                        </a>
                    </li>
                </ul>
                
                <div class="sidebar-header mt-4">Management</div>
                <!-- Add more management links here if needed -->
                
                <div class="sidebar-header mt-4">Support</div>
                <!-- Add support links here if needed -->
                
                <!-- Mobile Logout -->
                <div class="d-lg-none mt-4 p-3">
                    <div class="text-muted small mb-2">Logged in as {{ Auth::user()->name ?? 'User' }}</div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-sign-out-alt me-2"></i>Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="col-lg-10 col-md-9 main-content">
            <main class="container container-main">
                <!-- Back Button Section -->
                @if(Request::route()->getName() != 'tasks.index' && 
                    Request::route()->getName() != 'dashboard' && 
                    Request::route()->getName() != 'login' && 
                    Request::route()->getName() != 'register' && 
                    url()->current() != url('/'))
                <div class="page-header">
                    <button type="button" class="btn back-btn" onclick="goBack()">
                        <i class="bi bi-arrow-left me-2"></i>Back
                    </button>
                    <div class="flex-grow-1">
                        @hasSection('page-title')
                            <h1 class="h3 mb-0">@yield('page-title')</h1>
                        @else
                            <h1 class="h3 mb-0">{{ $pageTitle ?? '' }}</h1>
                        @endif
                        @hasSection('page-subtitle')
                            <p class="text-muted mb-0 mt-1">@yield('page-subtitle')</p>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Success Alert -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <!-- Error Alert -->
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Please check the form below for errors.
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <!-- Main Content -->
                {{ $slot ?? (isset($content) ? $content : '') }}
                @yield('content')
            </main>
        </div>
    </div>
  </div>

  <script>
    // Back button functionality
    function goBack() {
      if (document.referrer && document.referrer.indexOf(window.location.host) !== -1) {
        window.history.back();
      } else {
        // If no previous page in the same domain, go to dashboard
        window.location.href = "{{ route('dashboard') }}";
      }
    }

    // Keyboard shortcut for back button (Alt + Left Arrow)
    document.addEventListener('keydown', function(event) {
      if (event.altKey && event.key === 'ArrowLeft') {
        event.preventDefault();
        goBack();
      }
    });

    // Auto-dismiss alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(function(alert) {
        setTimeout(function() {
          const bsAlert = new bootstrap.Alert(alert);
          bsAlert.close();
        }, 5000);
      });
    });

    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // Active sidebar link highlighting
    document.addEventListener('DOMContentLoaded', function() {
        const currentUrl = window.location.href;
        const sidebarLinks = document.querySelectorAll('.sidebar .nav-link');
        
        sidebarLinks.forEach(link => {
            if (link.href === currentUrl) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    });
  </script>
</body>
</html>