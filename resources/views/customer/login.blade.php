<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CARE System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

    <!-- LEFT SIDEBAR -->
    <nav class="sidebar">
        <div class="logo">
            <i class="fa-solid fa-ticket"></i>
            <span>CARE System</span>
        </div>

        <ul class="nav-links">
            <li><a href="{{ url('/') }}"><i class="fa-solid fa-house"></i> Home</a></li>
            <li><a href="#"><i class="fa-solid fa-circle-info"></i> About</a></li>
            <li><a href="#"><i class="fa-solid fa-headset"></i> Contact</a></li>
        </ul>

        <div class="user-info-sidebar">
            <p style="color: var(--text-dim); font-size: 0.875rem;">
                Don't have an account?
            </p>
            <a href="#" class="btn-link">Sign Up</a>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="main-content hero-bg">
        <div class="bg-graphic"></div>

        <div class="login-container">
            <div class="login-header">
                <div class="login-icon">
                    <i class="fa-solid fa-right-to-bracket"></i>
                </div>
                <h1>Welcome Back</h1>
                <p>Sign in to your account</p>
            </div>

            {{-- Error Message --}}
            @if($errors->any())
                <div style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3);
                            color: #ef4444; padding: 12px 16px; border-radius: 8px; margin-bottom: 1rem;
                            font-size: 0.875rem;">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}
                </div>
            @endif

            {{-- Success Message --}}
            @if(session('success'))
                <div style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.3);
                            color: #10b981; padding: 12px 16px; border-radius: 8px; margin-bottom: 1rem;
                            font-size: 0.875rem;">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif

            {{-- Laravel form - posts to backend --}}
            <form action="{{ route('customer.login.post') }}" method="POST">
                @csrf

                <!-- Username -->
                <div class="form-group">
                    <label>Username <span class="required">*</span></label>
                    <input type="text"
                           name="c_username"
                           placeholder="Enter your username"
                           value="{{ old('c_username') }}"
                           required>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label>Password <span class="required">*</span></label>
                    <div class="password-input-wrapper">
                        <input type="password"
                               name="c_password"
                               id="passwordInput"
                               placeholder="Enter your password"
                               required>
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <i class="fa-solid fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Keep Signed In -->
                <div class="form-options">
                    <label class="checkbox-option">
                        <input type="checkbox" name="remember">
                        <span>Keep me signed in on this device</span>
                    </label>
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>

                <!-- Login Button -->
                <button type="submit" class="btn-login">
                    <i class="fa-solid fa-sign-in-alt"></i> Sign In
                </button>
            </form>

            <div class="login-footer">
                <p>Don't have an account? <a href="#">Sign up</a></p>
            </div>

            <div class="back-home">
                <a href="{{ url('/') }}"><i class="fa-solid fa-arrow-left"></i> Back to Home</a>
            </div>
        </div>
    </main>

    <script src="{{ asset('js/customer-login.js') }}"></script>
</body>
</html>