@extends('master')

@section('title', 'Set New Password - MedSuite')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/landing.css') }}">
<style>
/* Reset Password Page Specific Styles */
.reset-password-card {
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

.reset-password-logo {
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

.reset-password-logo i {
    font-size: 2rem;
    color: white;
}

.reset-password-title {
    color: var(--color-gray-800);
    font-weight: var(--font-weight-bold);
    font-size: var(--font-size-2xl);
    margin-bottom: var(--space-2);
}

.reset-password-subtitle {
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

.reset-password-input {
    border: 2px solid var(--color-gray-200);
    border-radius: var(--radius-md);
    padding: var(--space-3) var(--space-4);
    font-size: var(--font-size-base);
    transition: all var(--transition-fast);
    background: rgba(255, 255, 255, 0.8);
    width: 100%;
}

.reset-password-input:focus {
    border-color: var(--color-teal-primary);
    box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15);
    background: white;
    outline: none;
}

.reset-password-input::placeholder {
    color: var(--color-gray-400);
}

.reset-password-input:read-only {
    background-color: var(--color-gray-100);
    cursor: not-allowed;
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
.btn-reset-password {
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

.btn-reset-password:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(13, 148, 136, 0.4);
    background: linear-gradient(135deg, var(--color-teal-primary-dark) 0%, var(--color-teal-primary) 100%);
    color: white;
}

.btn-reset-password:active {
    transform: translateY(0);
}

/* Links */
.reset-password-link {
    color: var(--color-teal-primary);
    text-decoration: none;
    font-weight: var(--font-weight-medium);
    transition: all var(--transition-fast);
}

.reset-password-link:hover {
    color: var(--color-teal-primary-dark);
    text-decoration: underline;
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
    .reset-password-card {
        padding: var(--space-8);
        max-width: 100%;
    }
}

@media (max-width: 639px) {
    .reset-password-card {
        padding: var(--space-6);
        border-radius: var(--radius-lg);
    }

    .reset-password-logo {
        width: 60px;
        height: 60px;
    }

    .reset-password-logo i {
        font-size: 1.5rem;
    }

    .reset-password-title {
        font-size: var(--font-size-xl);
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
<!-- PWA Meta Tags -->
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

<div class="d-flex align-items-center justify-content-center" style="min-height: 100vh; padding: var(--space-6);">
    <div class="reset-password-card">
        <!-- Header -->
        <div class="text-center mb-5">
            <div class="reset-password-logo">
                <i class="bi bi-shield-lock"></i>
            </div>
            <h2 class="reset-password-title">Set New Password</h2>
            <p class="reset-password-subtitle">Create a new secure password for your account</p>
        </div>

        <!-- Reset Password Form -->
        <form method="POST" action="{{ route('password.store') }}" class="reset-password-form">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Field -->
            <div class="mb-4">
                <label for="email" class="form-label">
                    <i class="bi bi-envelope me-2"></i>Email Address
                </label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    class="reset-password-input @error('email') is-invalid @enderror"
                    value="{{ old('email', $request->email) }}"
                    required
                    autofocus
                    readonly
                >
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password Field -->
            <div class="mb-4">
                <label for="password" class="form-label">
                    <i class="bi bi-lock me-2"></i>New Password
                </label>
                <div class="password-wrapper">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="reset-password-input @error('password') is-invalid @enderror"
                        required
                        placeholder="Enter your new password"
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword('password')" aria-label="Toggle password visibility">
                        <i class="bi bi-eye" id="password-eye"></i>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password Field -->
            <div class="mb-4">
                <label for="password_confirmation" class="form-label">
                    <i class="bi bi-lock-fill me-2"></i>Confirm Password
                </label>
                <div class="password-wrapper">
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        class="reset-password-input"
                        required
                        placeholder="Confirm your new password"
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')" aria-label="Toggle password visibility">
                        <i class="bi bi-eye" id="password_confirmation-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-reset-password mb-4">
                <i class="bi bi-check-circle"></i>
                Reset Password
            </button>

            <!-- Back to Login -->
            <div class="text-center">
                <a href="{{ route('login') }}" class="reset-password-link">
                    <i class="bi bi-arrow-left me-1"></i> Back to Login
                </a>
            </div>
        </form>
    </div>
</div>

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
