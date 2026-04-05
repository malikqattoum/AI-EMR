@extends('master')

@section('title', 'Forgot Password - MedSuite')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/landing.css') }}">
<style>
    body {
        background: linear-gradient(135deg, #f3f4f6 0%, #f0fdfa 100%);
        min-height: 100vh;
    }

    .auth-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        padding: 3rem;
        max-width: 450px;
        width: 100%;
        animation: fadeInUp 0.5s ease-out;
    }

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

    .auth-logo {
        width: 72px;
        height: 72px;
        background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: 0 4px 14px rgba(13, 148, 136, 0.25);
    }

    .auth-logo i {
        font-size: 2rem;
        color: white;
    }

    .auth-title {
        color: #111827;
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .auth-subtitle {
        color: #6b7280;
        font-size: 1rem;
        margin-bottom: 0;
    }

    .form-label {
        color: #374151;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 150ms ease;
        background: rgba(255, 255, 255, 0.8);
        width: 100%;
    }

    .form-control:focus {
        border-color: #0d9488;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15);
        background: white;
        outline: none;
    }

    .form-control::placeholder {
        color: #9ca3af;
    }

    .form-control.is-invalid {
        border-color: #ef4444;
    }

    .invalid-feedback {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    .btn-primary {
        background-color: #0d9488;
        color: #ffffff;
        border-color: #0d9488;
        padding: 0.875rem 1.5rem;
        font-weight: 600;
        font-size: 1rem;
        border-radius: 12px;
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 150ms ease;
        border: 2px solid transparent;
        box-shadow: 0 4px 14px rgba(13, 148, 136, 0.25);
    }

    .btn-primary:hover {
        background-color: #0f766e;
        border-color: #0f766e;
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(13, 148, 136, 0.35);
        color: #ffffff;
    }

    .btn-primary:active {
        background-color: #0f766e;
        transform: translateY(0);
    }

    .auth-link {
        color: #0d9488;
        text-decoration: none;
        font-weight: 500;
        transition: color 150ms ease;
    }

    .auth-link:hover {
        color: #0f766e;
        text-decoration: underline;
    }

    .alert-success {
        background: #f0fdf4;
        border: 1px solid #dcfce7;
        color: #166534;
        border-radius: 12px;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    @media (max-width: 768px) {
        .auth-card {
            padding: 2rem;
            margin: 1rem;
        }

        .auth-title {
            font-size: 1.75rem;
        }
    }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-center" style="min-height: 100vh; padding: 2rem;">
    <div class="auth-card">
        <!-- Header -->
        <div class="text-center mb-4">
            <div class="auth-logo">
                <i class="bi bi-key"></i>
            </div>
            <h2 class="auth-title">Forgot Password?</h2>
            <p class="auth-subtitle">Enter your email to receive a password reset link</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="alert-success mb-4">
                <i class="bi bi-check-circle"></i>
                {{ session('status') }}
            </div>
        @endif

        <!-- Forgot Password Form -->
        <form method="POST" action="{{ route('password.email') }}">
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
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    placeholder="Enter your email"
                >
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-primary mb-3">
                <i class="bi bi-envelope-paper"></i>
                Send Reset Link
            </button>

            <!-- Back to Login -->
            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="auth-link">
                    <i class="bi bi-arrow-left me-1"></i> Back to Login
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
