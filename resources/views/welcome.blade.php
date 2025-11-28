<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Task Management System</title>
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <link href="{{ asset('build/assets/boostrap/bootstrap.min.css') }}" rel="stylesheet">

  <style>
    :root{
      --brand: #adc8f0ff; /* Bootstrap primary (blue) */
      --accent: #7f5febff;
    }
    body { background: linear-gradient(180deg, #f6fbff 0%, #ffffff 60%); }
    .hero {
      background: linear-gradient(90deg, rgba(55, 65, 177, 0.95) 0%, rgba(11,94,215,0.90) 100%);
      color: #fff;
    }
    .card-ghost { background: rgba(255,255,255,0.85); backdrop-filter: blur(6px); }
    .feature-icon { width:56px; height:56px; display:inline-flex; align-items:center; justify-content:center; border-radius:12px; background: rgba(13,110,253,0.08); color:var(--brand); font-weight:600; }
    .btn-ghost { background: rgba(255,255,255,0.12); color:#fff; border:1px solid rgba(255,255,255,0.12); }
    @media (min-width: 992px){ .hero { padding:6rem 0; } }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark" style="background:var(--brand)">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="white" class="me-2" viewBox="0 0 24 24"><path d="M3 3h7v7H3zM14 3h7v7h-7zM3 14h7v7H3zM14 14h7v7h-7z"/></svg>
        <span class="fw-bold">Task Management System</span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navMain">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item me-2">
            <a class="nav-link text-white" href="{{url('/tasks')}}">Tasks</a>
          </li>

          @if (Route::has('login'))
            @auth
              <li class="nav-item me-2"><a class="nav-link text-white" href="{{ url('/dashboard') }}">Dashboard</a></li>
              <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button class="btn btn-sm btn-light">Logout</button>
                </form>
              </li>
            @else
              <li class="nav-item me-2">
                <a class="btn btn-outline-light btn-sm" href="{{ route('login') }}">Log in</a>
              </li>
              @if (Route::has('register'))
                <li class="nav-item">
                  <a class="btn btn-light btn-sm text-primary" href="{{ route('register') }}">Sign up</a>
                </li>
              @endif
            @endauth
          @endif
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero -->
  <header class="hero text-white">
    <div class="container">
      <div class="row align-items-center gy-4">
        <div class="col-lg-6">
          <h1 class="display-5 fw-bold">Task Management System</h1>
          <p class="lead opacity-85 mb-4">Create tasks, assign team members, attach documents and track progress.</p>

          <div class="d-flex gap-2">
            <a href="{{url('/tasks')}}" class="btn btn-light btn-lg text-primary shadow">View Tasks</a>
            @guest
              <a href="{{ route('register') }}" class="btn btn-ghost btn-lg ms-1">Get Started</a>
            @endguest
          </div>
        </div>

        <div class="col-lg-4 d-none d-lg-block text-center mr-5">
         <div class="card  p-2 shadow-sm border-0" style="height: 300px;">
            <img src="{{ asset('build/assets/images/Home1.png') }}"
                alt="image"
                class="w-100 h-100"
                style="object-fit: cover;">

        </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Features -->
  <section class="py-5">
    <div class="container">
      <div class="row g-4">
        <div class="col-md-4">
          <div class="p-4 bg-white rounded shadow-sm h-100">
            <div class="d-flex align-items-start gap-3">
              <div class="feature-icon">A</div>
              <div>
                <h5 class="mb-1">Authentication & Roles</h5>
                <div class="text-muted small">Secure login, registration and role-based access using Spatie permission.</div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="p-4 bg-white rounded shadow-sm h-100">
            <div class="d-flex align-items-start gap-3">
              <div class="feature-icon">T</div>
              <div>
                <h5 class="mb-1">Task CRUD</h5>
                <div class="text-muted small">Create, view, update and delete tasks with priority and deadlines.</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="text-center mt-5">
        <a href="{{url('tasks/create')}}" class="btn btn-primary btn-lg">Create your first task</a>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="py-4 text-center text-muted">
    <div class="container">
      © {{ date('Y') }} Task Management System
    </div>
  </footer>

  <script src="{{ asset('build/assets/boostrap/bootstrap.bundle.min.js')}}"></script>
</body>
</html>
