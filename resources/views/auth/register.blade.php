<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('build/assets/boostrap/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{asset('build/assets/js/jquery-3.6.0.min.js')}}"></script>
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
        .register-container {
            max-width: 450px;
            width: 100%;
            padding: 20px;
        }
        .register-card {
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
        .btn-register {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-register:hover {
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
        .login-link {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .login-link:hover {
            color: #3730a3;
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
        .password-toggle {
            cursor: pointer;
            color: #6b7280;
            background: none;
            border: none;
            padding: 0;
        }
        .password-toggle:hover {
            color: #4f46e5;
        }
        .invalid-feedback {
            display: block;
            font-weight: 500;
        }
        .password-strength {
            height: 4px;
            border-radius: 2px;
            margin-top: 5px;
            transition: all 0.3s ease;
        }
        .strength-weak {
            background-color: #dc3545;
            width: 25%;
        }
        .strength-medium {
            background-color: #ffc107;
            width: 50%;
        }
        .strength-strong {
            background-color: #198754;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="card register-card">
            <div class="card-header">
                <h3 class="mb-0">Create Account</h3>
                <p class="mb-0 mt-2 opacity-75">Join our community today</p>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <div class="input-group">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                   name="name" value="{{ old('name') }}" required autofocus autocomplete="name" 
                                   placeholder="Enter your full name">
                            <span class="input-icon">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>
                        @error('name')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" required autocomplete="username" 
                                   placeholder="Enter your email">
                            <span class="input-icon">
                                <i class="fas fa-envelope"></i>
                            </span>
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                   name="password" required autocomplete="new-password" 
                                   placeholder="Create a password">
                            <button type="button" class="input-icon password-toggle" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-strength strength-weak" id="passwordStrength"></div>
                        @error('password')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <input id="password_confirmation" type="password" class="form-control" 
                                   name="password_confirmation" required autocomplete="new-password" 
                                   placeholder="Confirm your password">
                            <button type="button" class="input-icon password-toggle" id="toggleConfirmPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="mt-2" id="passwordMatch"></div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a class="login-link" href="{{ route('login') }}">
                            Already registered?
                        </a>
                        <button type="submit" class="btn btn-primary btn-register">
                            Register
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

  <script src="{{ asset('build/assets/boostrap/bootstrap.bundle.min.js')}}"></script>
    
    <script>
        $(document).ready(function() {
            // Password visibility toggle using jQuery
            $('#togglePassword').on('click', function() {
                const passwordInput = $('#password');
                const icon = $(this).find('i');
                
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
            
            $('#toggleConfirmPassword').on('click', function() {
                const confirmPasswordInput = $('#password_confirmation');
                const icon = $(this).find('i');
                
                if (confirmPasswordInput.attr('type') === 'password') {
                    confirmPasswordInput.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    confirmPasswordInput.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // Password strength indicator
            $('#password').on('input', function() {
                const password = $(this).val();
                const strengthBar = $('#passwordStrength');
                
                // Reset strength bar
                strengthBar.removeClass('strength-weak strength-medium strength-strong');
                
                if (password.length === 0) {
                    strengthBar.css('width', '0');
                    return;
                }
                
                // Calculate strength
                let strength = 0;
                
                // Length check
                if (password.length >= 8) strength += 1;
                
                // Contains lowercase
                if (/[a-z]/.test(password)) strength += 1;
                
                // Contains uppercase
                if (/[A-Z]/.test(password)) strength += 1;
                
                // Contains numbers
                if (/[0-9]/.test(password)) strength += 1;
                
                // Contains special characters
                if (/[^A-Za-z0-9]/.test(password)) strength += 1;
                
                // Update strength bar
                if (strength <= 2) {
                    strengthBar.addClass('strength-weak');
                } else if (strength <= 4) {
                    strengthBar.addClass('strength-medium');
                } else {
                    strengthBar.addClass('strength-strong');
                }
            });

            // Password match validation
            $('#password_confirmation').on('input', function() {
                const password = $('#password').val();
                const confirmPassword = $(this).val();
                const matchIndicator = $('#passwordMatch');
                
                if (confirmPassword.length === 0) {
                    matchIndicator.html('');
                    return;
                }
                
                if (password === confirmPassword) {
                    matchIndicator.html('<small class="text-success"><i class="fas fa-check-circle"></i> Passwords match</small>');
                } else {
                    matchIndicator.html('<small class="text-danger"><i class="fas fa-times-circle"></i> Passwords do not match</small>');
                }
            });

            // Form submission validation
            $('form').on('submit', function(e) {
                const password = $('#password').val();
                const confirmPassword = $('#password_confirmation').val();
                
                if (password !== confirmPassword) {
                    e.preventDefault();
                    alert('Please make sure your passwords match.');
                    return false;
                }
                
                if (password.length < 8) {
                    e.preventDefault();
                    alert('Password must be at least 8 characters long.');
                    return false;
                }
            });
        });
    </script>
</body>
</html>