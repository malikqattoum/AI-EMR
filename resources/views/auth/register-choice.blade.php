@extends('master')

@section('title', 'Register - MedSuite')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
<!-- PWA Meta Tags -->
<script>
(function() {
    var link = document.createElement('link');
    link.rel = 'manifest';
    link.href = '/patient-manifest.webmanifest';
    document.head.appendChild(link);
    var meta = document.createElement('meta');
    meta.name = 'theme-color';
    meta.content = '#0d9488';
    document.head.appendChild(meta);
    if (window.matchMedia('(display-mode: standalone)').matches) {
        document.documentElement.classList.add('is-installed-pwa');
    }
})();
</script>

<div class="auth-page light-bg">
    <div class="container-fluid">
        <div class="row justify-content-center min-vh-100 align-items-center">
            <div class="col-12 col-md-10 col-lg-8 col-xl-7" style="position: relative; z-index: 2;">

                <!-- Main form card -->
                <div class="auth-card alt-style">
                    <!-- Header -->
                    <div class="auth-header mb-5">
                        <div class="auth-logo">
                            <i class="bi bi-heart-pulse"></i>
                        </div>
                        <h2 class="auth-title">Join MedSuite</h2>
                        <p class="auth-subtitle">Select the account type that best describes you</p>
                    </div>

                    <!-- Registration Options -->
                    <div class="row g-4 justify-content-center">
                        <!-- Healthcare Professional Registration -->
                        <div class="col-12 col-lg-5 col-md-5">
                            <div class="registration-option-card doctor-card" onclick="window.location.href='/register-doctor'">
                                <div class="card-body text-center p-4 d-flex flex-column h-100">
                                    <div class="option-icon mb-3 mx-auto">
                                        <i class="fas fa-user-doctor"></i>
                                    </div>
                                    <h4 class="option-title mb-3">Healthcare Professional</h4>
                                    <p class="option-description mb-4">
                                        Register as a doctor, nurse, or healthcare provider to access clinical decision support tools, patient management, and professional features.
                                    </p>
                                    <div class="option-features mb-4">
                                        <div class="d-flex flex-column gap-2 text-start">
                                            <div>
                                                <i class="fas fa-check text-success me-2"></i>
                                                <small>Clinical Decision Support</small>
                                            </div>
                                            <div>
                                                <i class="fas fa-check text-success me-2"></i>
                                                <small>Voice Assistant</small>
                                            </div>
                                            <div>
                                                <i class="fas fa-check text-success me-2"></i>
                                                <small>Patient Management</small>
                                            </div>
                                            <div>
                                                <i class="fas fa-check text-success me-2"></i>
                                                <small>Professional Landing Page</small>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn auth-btn w-100 mt-auto">
                                        <i class="fas fa-user-doctor me-2"></i>
                                        Register as Healthcare Professional
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Patient Registration -->
                        <div class="col-12 col-lg-5 col-md-5">
                            <div class="registration-option-card patient-card" onclick="window.location.href='{{ route('patient.register') }}'">
                                <div class="card-body text-center p-4 d-flex flex-column h-100">
                                    <div class="option-icon mb-3 mx-auto">
                                        <i class="fas fa-user-injured"></i>
                                    </div>
                                    <h4 class="option-title mb-3">Patient</h4>
                                    <p class="option-description mb-4">
                                        Create a patient account to easily book appointments, manage your health records, and access personalized healthcare services.
                                    </p>
                                    <div class="option-features mb-4">
                                        <div class="d-flex flex-column gap-2 text-start">
                                            <div>
                                                <i class="fas fa-check text-success me-2"></i>
                                                <small>Easy Booking</small>
                                            </div>
                                            <div>
                                                <i class="fas fa-check text-success me-2"></i>
                                                <small>Health Records</small>
                                            </div>
                                            <div>
                                                <i class="fas fa-check text-success me-2"></i>
                                                <small>Appointment History</small>
                                            </div>
                                            <div>
                                                <i class="fas fa-check text-success me-2"></i>
                                                <small>Email Reminders</small>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn auth-btn-green w-100 mt-auto">
                                        <i class="fas fa-user-injured me-2"></i>
                                        Register as Patient
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Guest Option -->
                    <div class="text-center mt-5">
                        <div class="auth-divider">
                            <span>or</span>
                        </div>
                        <p class="text-muted mb-3">Don't want to create an account?</p>
                        <a href="{{ route('doctors.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-search me-2"></i>
                            Browse Doctors as Guest
                        </a>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center mt-4">
                        <p class="mb-0">Already have an account?</p>
                        <a href="{{ route('login') }}" class="auth-link-primary">
                            Sign in here <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Footer links -->
                <div class="text-center mt-4">
                    <small class="text-muted">Need help? <a href="{{ route('contact') }}" class="auth-link">Contact Support</a></small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
