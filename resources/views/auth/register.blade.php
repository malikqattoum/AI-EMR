@extends('master')

@section('title', 'Register - MedSuite')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/landing.css') }}">
@endpush

<x-auth-layout
    :headline="'Join MedSuite'"
    :subtext="'Create your account and revolutionize patient care with advanced AI diagnosis tools and seamless practice management.'"
    :showBrand="true"
    brandIcon="bi-shield-check"
    brandName="MedSuite"
    :features="[
        ['icon' => 'bi-robot', 'text' => 'AI-Powered Diagnosis'],
        ['icon' => 'bi-mic', 'text' => 'Voice Assistant Technology'],
        ['icon' => 'bi-people', 'text' => 'Patient Management'],
        ['icon' => 'bi-globe', 'text' => 'Professional Landing Pages']
    ]"
>
    <!-- Register Form -->
    <form method="POST" action="{{ route('register') }}" class="register-form">
        @csrf

        <!-- Form Header -->
        <div class="form-header text-center mb-5">
            <div class="form-icon">
                <i class="bi bi-person-plus"></i>
            </div>
            <h2 class="form-title">Create Account</h2>
            <p class="form-subtitle">Join MedSuite as a healthcare professional</p>
        </div>

        <!-- Name Field -->
        <div class="form-group mb-4">
            <label for="name" class="form-label">
                <i class="bi bi-person me-2"></i>Full Name
            </label>
            <input
                id="name"
                type="text"
                name="name"
                class="form-control @if($errors ?? false) @error('name') is-invalid @enderror @endif"
                value="{{ old('name') }}"
                required
                autofocus
                placeholder="Enter your full name"
            >
            @error('name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Field -->
        <div class="form-group mb-4">
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
                placeholder="Enter your email"
            >
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- Phone Number Field -->
        <div class="form-group mb-4">
            <label for="phone" class="form-label">
                <i class="bi bi-telephone me-2"></i>Phone Number <span class="text-danger">*</span>
            </label>
            <input
                id="phone"
                type="tel"
                name="phone"
                class="form-control @error('phone') is-invalid @enderror"
                value="{{ old('phone') }}"
                required
                placeholder="+1234567890"
                pattern="^\+?[1-9]\d{1,14}$"
            >
            <div class="form-text">
                <small class="text-muted">
                    <i class="bi bi-info-circle me-1"></i>
                    Required for SMS invoice reminders. Include country code.
                </small>
            </div>
            @error('phone')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password Field -->
        <div class="form-group mb-4">
            <label for="password" class="form-label">
                <i class="bi bi-lock me-2"></i>Password
            </label>
            <div class="password-input-wrapper">
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    required
                    placeholder="Create a strong password"
                >
                <button type="button" class="password-toggle" onclick="togglePassword('password')">
                    <i class="bi bi-eye" id="password-eye"></i>
                </button>
            </div>
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password Field -->
        <div class="form-group mb-4">
            <label for="password_confirmation" class="form-label">
                <i class="bi bi-shield-check me-2"></i>Confirm Password
            </label>
            <div class="password-input-wrapper">
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    class="form-control @error('password_confirmation') is-invalid @enderror"
                    required
                    placeholder="Confirm your password"
                >
                <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                    <i class="bi bi-eye" id="password_confirmation-eye"></i>
                </button>
            </div>
            @error('password_confirmation')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- Medical Specialty Field -->
        <div class="form-group mb-4">
            <label for="specialty_select" class="form-label">
                <i class="bi bi-heart-pulse me-2"></i>Medical Specialty <span class="text-danger">*</span>
            </label>
            <select class="form-control" name="specialty_select" id="specialty_select" onchange="toggleCustomSpecialty()">
                <option value="">-- Select Your Specialty --</option>

                <optgroup label="General & Internal Medicine">
                    <option value="General Practitioner">General Practitioner (GP) / Family Medicine</option>
                    <option value="Internal Medicine">Internal Medicine (Internist)</option>
                </optgroup>

                <optgroup label="Internal Medicine Subspecialties">
                    <option value="Cardiology">Cardiology (Heart)</option>
                    <option value="Pulmonology">Pulmonology (Lungs)</option>
                    <option value="Gastroenterology">Gastroenterology (Digestive system)</option>
                    <option value="Nephrology">Nephrology (Kidneys)</option>
                    <option value="Endocrinology">Endocrinology (Hormones & glands)</option>
                    <option value="Hematology">Hematology (Blood)</option>
                    <option value="Rheumatology">Rheumatology (Joints & autoimmune diseases)</option>
                    <option value="Dermatology">Dermatology (Skin, hair, nails)</option>
                </optgroup>

                <optgroup label="Emergency & Critical Care">
                    <option value="Emergency Medicine">Emergency Medicine</option>
                    <option value="Critical Care">Critical Care / Intensive Care Medicine</option>
                </optgroup>

                <optgroup label="Neurology & Psychiatry">
                    <option value="Neurology">Neurology (Brain & nerves)</option>
                    <option value="Neurosurgery">Neurosurgery (Brain & spine surgery)</option>
                    <option value="Psychiatry">Psychiatry (Mental health)</option>
                </optgroup>

                <optgroup label="Surgical Specialties">
                    <option value="General Surgery">General Surgery</option>
                    <option value="Orthopedic Surgery">Orthopedic Surgery (Bones & joints)</option>
                    <option value="Cardiothoracic Surgery">Cardiothoracic Surgery (Heart & lungs)</option>
                    <option value="Plastic & Reconstructive Surgery">Plastic & Reconstructive Surgery</option>
                    <option value="Urology">Urology (Urinary & male reproductive system)</option>
                    <option value="Ophthalmic Surgery">Ophthalmic Surgery (Eye surgery)</option>
                </optgroup>

                <optgroup label="Pediatrics & Women's Health">
                    <option value="Pediatrics">Pediatrics</option>
                    <option value="Obstetrics & Gynecology">Obstetrics & Gynecology (OB/GYN)</option>
                </optgroup>

                <optgroup label="Diagnostic & Support Specialties">
                    <option value="Radiology">Radiology (Medical imaging)</option>
                    <option value="Pathology">Pathology (Laboratory medicine)</option>
                    <option value="Oncology">Oncology (Medical cancer care)</option>
                </optgroup>

                <optgroup label="Other">
                    <option value="Geriatrics">Geriatrics (Elderly care)</option>
                    <option value="Sports Medicine">Sports Medicine</option>
                    <option value="Physical Medicine & Rehabilitation">Physical Medicine & Rehabilitation</option>
                    <option value="other">Other (Please specify)</option>
                </optgroup>
            </select>

            <!-- Custom Specialty Input (Hidden by default) -->
            <div id="custom_specialty_container" class="mt-2" style="display: none;">
                <input
                    type="text"
                    name="custom_specialty"
                    id="custom_specialty"
                    class="form-control @if($errors ?? false) @error('custom_specialty') is-invalid @enderror @endif"
                    placeholder="Please enter your medical specialty"
                    value="{{ old('custom_specialty') }}"
                >
                @if($errors ?? false)
                    @error('custom_specialty')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                @endif
            </div>

            <!-- Hidden field to store the final specialty value -->
            <input type="hidden" name="specialty" id="specialty" value="{{ old('specialty') }}">

            @if($errors ?? false)
                @error('specialty')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            @endif
        </div>

        <!-- Terms Agreement -->
        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" id="terms" required>
            <label class="form-check-label" for="terms">
                I agree to the <a href="#" class="auth-link">Terms of Service</a> and <a href="#" class="auth-link">Privacy Policy</a>
            </label>
        </div>

        <!-- Register Button -->
        <button type="submit" class="btn btn-primary w-100 mb-3">
            <i class="bi bi-person-plus me-2"></i>
            Create MedSuite Account
        </button>

        <!-- Divider -->
        <div class="auth-divider">
            <span>or</span>
        </div>

        <!-- Login Link -->
        <div class="text-center">
            <p class="mb-0">Already have an account?</p>
            <a href="{{ route('login') }}" class="auth-link-primary">
                Sign in here <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </form>
</x-auth-layout>

<style>
/* Register Form Styles */
.register-form {
    width: 100%;
}

.form-header {
    margin-bottom: var(--space-6);
}

.form-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto var(--space-4);
    background: linear-gradient(135deg, var(--color-teal-50) 0%, var(--color-teal-100) 100%);
    border-radius: var(--radius-xl);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    color: var(--color-teal-primary);
}

.form-title {
    font-size: var(--font-size-2xl);
    font-weight: var(--font-weight-bold);
    color: var(--text-primary);
    margin-bottom: var(--space-2);
}

.form-subtitle {
    font-size: var(--font-size-base);
    color: var(--text-secondary);
    margin: 0;
}

.form-group {
    margin-bottom: var(--space-4);
}

.form-label {
    display: flex;
    align-items: center;
    font-size: var(--font-size-sm);
    font-weight: var(--font-weight-medium);
    color: var(--text-primary);
    margin-bottom: var(--space-2);
}

.form-label i {
    color: var(--color-teal-primary);
    font-size: var(--font-size-base);
}

.form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    font-size: var(--font-size-base);
    font-family: var(--font-family-sans);
    color: var(--text-primary);
    background-color: var(--color-white);
    border: 2px solid var(--color-gray-200);
    border-radius: var(--radius-md);
    transition: var(--transition-normal);
    -webkit-appearance: menulist;
    appearance: menulist;
}

.form-control:focus {
    outline: none;
    border-color: var(--color-teal-primary);
    box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15);
    background: var(--color-white);
}

.form-control::placeholder {
    color: var(--color-gray-400);
}

.form-control.is-invalid {
    border-color: var(--color-error);
}

.invalid-feedback {
    font-size: var(--font-size-sm);
    color: var(--color-error);
    margin-top: var(--space-1);
}

.form-text {
    margin-top: var(--space-1);
}

.text-muted {
    color: var(--text-muted);
    font-size: var(--font-size-xs);
}

/* Password Input */
.password-input-wrapper {
    position: relative;
}

.password-input-wrapper .form-control {
    padding-right: 3rem;
}

.password-toggle {
    position: absolute;
    right: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--color-gray-400);
    cursor: pointer;
    padding: 0.25rem;
    font-size: var(--font-size-lg);
    transition: var(--transition-fast);
}

.password-toggle:hover {
    color: var(--color-teal-primary);
}

/* Form Check */
.form-check {
    display: flex;
    align-items: flex-start;
    gap: var(--space-2);
}

.form-check-input {
    width: 1.125rem;
    height: 1.125rem;
    margin-top: 0.125rem;
    accent-color: var(--color-teal-primary);
    cursor: pointer;
}

.form-check-label {
    font-size: var(--font-size-sm);
    color: var(--text-secondary);
    cursor: pointer;
}

.auth-link {
    color: var(--color-teal-primary);
    text-decoration: none;
    font-weight: var(--font-weight-medium);
    transition: var(--transition-fast);
}

.auth-link:hover {
    color: var(--color-teal-primary-dark);
    text-decoration: underline;
}

/* Primary Button */
.btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.875rem 1.5rem;
    font-size: var(--font-size-base);
    font-weight: var(--font-weight-semibold);
    color: var(--color-white);
    background: linear-gradient(135deg, var(--color-teal-primary) 0%, var(--color-teal-primary-dark) 100%);
    border: none;
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: var(--transition-normal);
    box-shadow: var(--shadow-teal);
}

.btn-primary:hover {
    background: linear-gradient(135deg, var(--color-teal-primary-light) 0%, var(--color-teal-primary) 100%);
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(13, 148, 136, 0.35);
}

.btn-primary:active {
    transform: translateY(0);
}

/* Divider */
.auth-divider {
    display: flex;
    align-items: center;
    margin: var(--space-6) 0;
    color: var(--text-muted);
    font-size: var(--font-size-sm);
}

.auth-divider::before,
.auth-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--color-gray-200);
}

.auth-divider span {
    padding: 0 var(--space-4);
}

/* Login Link */
.auth-link-primary {
    display: inline-flex;
    align-items: center;
    color: var(--color-teal-primary);
    font-weight: var(--font-weight-semibold);
    text-decoration: none;
    transition: var(--transition-fast);
}

.auth-link-primary:hover {
    color: var(--color-teal-primary-dark);
}

.auth-link-primary i {
    transition: var(--transition-fast);
}

.auth-link-primary:hover i {
    transform: translateX(4px);
}

/* Text Danger */
.text-danger {
    color: var(--color-error);
}
</style>

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

function toggleCustomSpecialty() {
    const select = document.getElementById('specialty_select');
    const customContainer = document.getElementById('custom_specialty_container');
    const customInput = document.getElementById('custom_specialty');
    const hiddenInput = document.getElementById('specialty');

    if (select.value === 'other') {
        customContainer.style.display = 'block';
        customInput.required = true;
        customInput.focus();
        hiddenInput.value = '';
    } else {
        customContainer.style.display = 'none';
        customInput.required = false;
        customInput.value = '';
        hiddenInput.value = select.value;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const customInput = document.getElementById('custom_specialty');
    const hiddenInput = document.getElementById('specialty');
    const select = document.getElementById('specialty_select');

    customInput.addEventListener('input', function() {
        if (select.value === 'other') {
            hiddenInput.value = this.value;
        }
    });

    const form = document.querySelector('.register-form');
    form.addEventListener('submit', function(e) {
        const select = document.getElementById('specialty_select');
        const customInput = document.getElementById('custom_specialty');
        const hiddenInput = document.getElementById('specialty');

        if (select.value === 'other') {
            if (!customInput.value.trim()) {
                e.preventDefault();
                customInput.focus();
                customInput.classList.add('is-invalid');
                return false;
            }
            hiddenInput.value = customInput.value.trim();
        } else {
            hiddenInput.value = select.value;
        }
    });

    // Restore old values
    const oldSpecialty = '{{ old("specialty") }}';
    const oldCustomSpecialty = '{{ old("custom_specialty") }}';

    if (oldCustomSpecialty) {
        document.getElementById('specialty_select').value = 'other';
        toggleCustomSpecialty();
        document.getElementById('custom_specialty').value = oldCustomSpecialty;
        document.getElementById('specialty').value = oldCustomSpecialty;
    } else if (oldSpecialty) {
        const selectOptions = Array.from(document.getElementById('specialty_select').options);
        const optionExists = selectOptions.some(option => option.value === oldSpecialty);

        if (optionExists) {
            document.getElementById('specialty_select').value = oldSpecialty;
        } else {
            document.getElementById('specialty_select').value = 'other';
            toggleCustomSpecialty();
            document.getElementById('custom_specialty').value = oldSpecialty;
        }
        document.getElementById('specialty').value = oldSpecialty;
    }
});
</script>
