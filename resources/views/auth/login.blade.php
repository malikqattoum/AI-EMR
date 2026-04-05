@extends('master')

@section('title', 'Login - MedSuite')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/landing.css') }}">
<style>
/* Login Page Specific Styles */
.login-form-card {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-radius: var(--radius-xl);
    padding: var(--space-10);
    box-shadow: var(--shadow-xl);
    border: 1px solid rgba(255, 255, 255, 0.6);
    width: 100%;
    max-width: 440px;
    animation: fadeInUp 0.5s ease-out 100ms forwards;
    opacity: 0;
}

.login-logo {
    width: 72px;
    height: 72px;
    background: linear-gradient(135deg, var(--color-teal-primary) 0%, var(--color-teal-primary-light) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--space-4);
    box-shadow: var(--shadow-teal);
}

.login-logo i {
    font-size: 2rem;
    color: white;
}

.login-title {
    color: var(--color-gray-800);
    font-weight: var(--font-weight-bold);
    font-size: var(--font-size-2xl);
    margin-bottom: var(--space-2);
}

.login-subtitle {
    color: var(--color-gray-500);
    font-size: var(--font-size-base);
    margin-bottom: var(--space-6);
}

/* Form Elements with Teal Focus */
.form-label {
    color: var(--color-gray-700);
    font-weight: var(--font-weight-semibold);
    font-size: var(--font-size-sm);
    margin-bottom: var(--space-2);
    display: block;
}

.login-input {
    border: 2px solid var(--color-gray-200);
    border-radius: var(--radius-md);
    padding: var(--space-3) var(--space-4);
    font-size: var(--font-size-base);
    transition: all var(--transition-fast);
    background: rgba(255, 255, 255, 0.8);
    width: 100%;
}

.login-input:focus {
    border-color: var(--color-teal-primary);
    box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15);
    background: white;
    outline: none;
}

.login-input::placeholder {
    color: var(--color-gray-400);
}

/* Password Input Wrapper */
.password-wrapper {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--color-gray-400);
    cursor: pointer;
    padding: 4px;
    font-size: 1.2rem;
    transition: color var(--transition-fast);
}

.password-toggle:hover {
    color: var(--color-teal-primary);
}

/* Primary Button - MedSuite Teal */
.btn-login {
    background: linear-gradient(135deg, var(--color-teal-primary) 0%, var(--color-teal-primary-light) 100%);
    border: none;
    border-radius: var(--radius-md);
    padding: var(--space-3) var(--space-6);
    font-weight: var(--font-weight-semibold);
    font-size: var(--font-size-base);
    color: white;
    transition: all var(--transition-fast);
    box-shadow: var(--shadow-teal);
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-2);
}

.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(13, 148, 136, 0.4);
    background: linear-gradient(135deg, var(--color-teal-primary-dark) 0%, var(--color-teal-primary) 100%);
    color: white;
}

.btn-login:active {
    transform: translateY(0);
}

/* Links */
.login-link {
    color: var(--color-teal-primary);
    text-decoration: none;
    font-weight: var(--font-weight-medium);
    transition: all var(--transition-fast);
}

.login-link:hover {
    color: var(--color-teal-primary-dark);
    text-decoration: underline;
}

/* Divider */
.login-divider {
    text-align: center;
    margin: var(--space-6) 0;
    position: relative;
}

.login-divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: var(--color-gray-200);
}

.login-divider span {
    background: white;
    padding: 0 var(--space-4);
    color: var(--color-gray-500);
    font-size: var(--font-size-sm);
    position: relative;
}

/* Form Checkbox - Teal */
.form-check-input:checked {
    background-color: var(--color-teal-primary);
    border-color: var(--color-teal-primary);
}

.form-check-input:focus {
    box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15);
}

/* Error States */
.is-invalid {
    border-color: var(--color-error) !important;
}

.invalid-feedback {
    color: var(--color-error);
    font-size: var(--font-size-sm);
    margin-top: var(--space-2);
}

/* Responsive */
@media (max-width: 1023px) {
    .login-form-card {
        padding: var(--space-8);
        max-width: 100%;
    }
}

@media (max-width: 639px) {
    .login-form-card {
        padding: var(--space-6);
        border-radius: var(--radius-lg);
    }

    .login-logo {
        width: 60px;
        height: 60px;
    }

    .login-logo i {
        font-size: 1.5rem;
    }
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush

@section('content')
<!-- PWA Meta Tags - Dynamic based on portal -->
<script>
(function() {
    var path = window.location.pathname;
    var manifestHref = path.includes('/doctor') ? '/doctor-manifest.webmanifest' : '/patient-manifest.webmanifest';
    var themeColor = path.includes('/doctor') ? '#0d9488' : '#166534';
    var link = document.createElement('link');
    link.rel = 'manifest';
    link.href = manifestHref;
    document.head.appendChild(link);
    var meta = document.createElement('meta');
    meta.name = 'theme-color';
    meta.content = themeColor;
    document.head.appendChild(meta);
    if (window.matchMedia('(display-mode: standalone)').matches) {
        document.documentElement.classList.add('is-installed-pwa');
    }
})();
</script>

<x-auth-layout
    headline="Welcome Back"
    subtext="Sign in to your MedSuite account to access AI-powered healthcare management."
    :features="[
        ['icon' => 'bi-robot', 'text' => 'AI Diagnosis Assistant'],
        ['icon' => 'bi-people', 'text' => 'Patient Management'],
        ['icon' => 'bi-file-earmark-medical', 'text' => 'Secure Health Records'],
        ['icon' => 'bi-mic', 'text' => 'Voice Assistant Access']
    ]"
    brand-icon="bi-shield-check"
    brand-name="MedSuite"
    show-brand="true"
>
    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" class="login-form">
        @csrf

        <!-- Email Field -->
        <div class="mb-4">
            <label for="email" class="form-label">
                <i class="bi bi-envelope me-2"></i>Email Address
            </label>
            <input
                id="email"
                type="email"
                name="email"
                class="login-input @error('email') is-invalid @enderror"
                value="{{ old('email') }}"
                required
                autofocus
                placeholder="Enter your email"
            >
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password Field -->
        <div class="mb-4">
            <label for="password" class="form-label">
                <i class="bi bi-lock me-2"></i>Password
            </label>
            <div class="password-wrapper">
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="login-input @error('password') is-invalid @enderror"
                    required
                    placeholder="Enter your password"
                >
                <button type="button" class="password-toggle" onclick="togglePassword('password')" aria-label="Toggle password visibility">
                    <i class="bi bi-eye" id="password-eye"></i>
                </button>
            </div>
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    Remember me
                </label>
            </div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="login-link">
                    Forgot password?
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <button type="submit" class="btn-login mb-4">
            <i class="bi bi-box-arrow-in-right"></i>
            Sign In to MedSuite
        </button>

        <div class="login-divider">
            <span>or</span>
        </div>

        <!-- Register Link -->
        <div class="text-center">
            <p class="mb-2 text-muted">New to MedSuite?</p>
            <a href="{{ route('patient.register') }}" class="login-link fw-semibold">
                Create an Account <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </form>

    <!-- Footer -->
    <div class="text-center mt-5 pt-4 border-top">
        <small class="text-muted">Need help? <a href="{{ route('contact') }}" class="login-link">Contact Support</a></small>
    </div>
</x-auth-layout>

<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const eye = document.getElementById(inputId + '-eye');

    if (input.type === 'password') {
        input.type = 'text';
        eye.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        eye.className = 'bi bi-eye';
    }
}
</script>
@endsection