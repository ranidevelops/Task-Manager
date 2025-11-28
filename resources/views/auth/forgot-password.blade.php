<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- Bootstrap 5 CSS -->
    <link href="{{ asset('build/assets/boostrap/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .reset-container {
            max-width: 450px;
            width: 100%;
            padding: 20px;
        }
        .reset-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            border-bottom: none;
            padding: 1.5rem;
            text-align: center;
        }
        .card-body {
            padding: 2rem;
            background-color: #fff;
        }
        .btn-reset {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(79, 70, 229, 0.4);
        }
        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.25);
        }
        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        .info-text {
            color: #6b7280;
            text-align: center;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }
        .alert {
            border-radius: 8px;
            border: none;
        }
        .input-group {
            position: relative;
        }
        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            z-index: 5;
        }
        .back-to-login {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .back-to-login:hover {
            color: #3730a3;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="card reset-card">
            <div class="card-header">
                <h3 class="mb-0">Reset Password</h3>
                <p class="mb-0 mt-2 opacity-75">We'll send you a reset link</p>
            </div>
            <div class="card-body">
                <!-- Info Text -->
                <div class="info-text">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                </div>

                <!-- Session Status -->
                @if(session('status'))
                <div class="alert alert-success mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('status') }}
                </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-4">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" required autofocus 
                                   placeholder="Enter your email address">
                            <span class="input-icon">
                                <i class="fas fa-envelope"></i>
                            </span>
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a class="back-to-login" href="{{ route('login') }}">
                            <i class="fas fa-arrow-left"></i>
                            Back to Login
                        </a>
                        <button type="submit" class="btn btn-primary btn-reset">
                            <i class="fas fa-paper-plane me-2"></i>
                            Send Reset Link
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="{{ asset('build/assets/boostrap/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('build/assets/js/jquery-3.6.0.min.js')}}"></script>

</body>
</html>