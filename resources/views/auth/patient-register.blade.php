@props([
    'headline' => 'Your Health, Your Way',
    'subtext' => 'Join MedSuite\'s patient portal for seamless healthcare management with AI-powered assistance.',
    'features' => [
        ['icon' => 'bi-calendar-check', 'text' => 'Easy Appointment Booking'],
        ['icon' => 'bi-file-earmark-medical', 'text' => 'Secure Health Records'],
        ['icon' => 'bi-clock-history', 'text' => 'Appointment History'],
        ['icon' => 'bi-bell', 'text' => 'Email Reminders'],
    ],
    'showBrand' => true,
    'brandIcon' => 'bi-heart-pulse',
    'brandName' => 'MedSuite',
    'leftPanelClass' => 'patient-theme',
])

<x-auth-layout>
    <form method="POST" action="{{ route('patient.register.store') }}" class="patient-register-form">
        @csrf

        <!-- Full Name -->
        <div class="form-group">
            <label for="name" class="form-label">
                <i class="bi bi-person me-2"></i>Full Name
            </label>
            <input id="name" name="name" type="text" required
                   class="form-control auth-input @error('name') is-invalid @enderror"
                   placeholder="Enter your full name" value="{{ old('name') }}">
            @error('name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email" class="form-label">
                <i class="bi bi-envelope me-2"></i>Email Address
            </label>
            <input id="email" name="email" type="email" required
                   class="form-control auth-input @error('email') is-invalid @enderror"
                   placeholder="Enter your email address" value="{{ old('email') }}">
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- Phone -->
        <div class="form-group">
            <label for="phone" class="form-label">
                <i class="bi bi-telephone me-2"></i>Phone Number
            </label>
            <input id="phone" name="phone" type="tel" required
                   class="form-control auth-input @error('phone') is-invalid @enderror"
                   placeholder="Enter your phone number" value="{{ old('phone') }}">
            @error('phone')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- Date of Birth -->
        <div class="form-group">
            <label for="date_of_birth" class="form-label">
                <i class="bi bi-calendar-event me-2"></i>Date of Birth
            </label>
            <input id="date_of_birth" name="date_of_birth" type="date" required
                   class="form-control auth-input @error('date_of_birth') is-invalid @enderror"
                   max="{{ date('Y-m-d') }}" value="{{ old('date_of_birth') }}">
            @error('date_of_birth')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- Gender -->
        <div class="form-group">
            <label for="gender" class="form-label">
                <i class="bi bi-gender-neuter me-2"></i>Gender
            </label>
            <select id="gender" name="gender" required class="form-control auth-input @error('gender') is-invalid @enderror">
                <option value="">Select gender</option>
                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
            </select>
            @error('gender')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">
                <i class="bi bi-lock me-2"></i>Password
            </label>
            <div class="password-input-wrapper">
                <input id="password" name="password" type="password" required
                       class="form-control auth-input @error('password') is-invalid @enderror"
                       placeholder="Create a secure password">
                <button type="button" class="password-toggle" onclick="togglePassword('password')" aria-label="Toggle password visibility">
                    <i class="bi bi-eye" id="password-eye"></i>
                </button>
            </div>
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="password_confirmation" class="form-label">
                <i class="bi bi-shield-check me-2"></i>Confirm Password
            </label>
            <div class="password-input-wrapper">
                <input id="password_confirmation" name="password_confirmation" type="password" required
                       class="form-control auth-input"
                       placeholder="Confirm your password">
                <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')" aria-label="Toggle password confirmation visibility">
                    <i class="bi bi-eye" id="password_confirmation-eye"></i>
                </button>
            </div>
        </div>

        <!-- Terms and Privacy -->
        <div class="form-group form-check">
            <input id="terms" name="terms" type="checkbox" required class="form-check-input">
            <label for="terms" class="form-check-label">
                I agree to the <a href="#" class="auth-link">Terms of Service</a>
                and <a href="#" class="auth-link">Privacy Policy</a>
            </label>
            @error('terms')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-register btn-green">
            <i class="bi bi-person-plus me-2"></i>
            Create Patient Account
        </button>
    </form>

    <div class="auth-footer-links">
        <hr class="my-4">
        <p class="text-center text-muted mb-2">Already have an account?</p>
        <div class="text-center">
            <a href="{{ route('login') }}" class="auth-link-primary">
                Sign in to your account <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>

    <div class="auth-help-text text-center mt-4">
        <small class="text-muted">Need help? <a href="{{ route('contact') }}" class="auth-link">Contact Support</a></small>
    </div>
</x-auth-layout>

<style>
/* Patient Theme Overrides for Auth Layout */
.auth-layout-left.patient-theme {
    background: linear-gradient(135deg, var(--color-forest-green) 0%, #134e4a 50%, var(--color-forest-green) 100%);
}

.auth-layout-left.patient-theme::after {
    background: radial-gradient(ellipse, rgba(22, 101, 52, 0.2) 0%, transparent 70%);
}

.auth-layout-left.patient-theme .auth-layout-brand-icon {
    background: linear-gradient(135deg, var(--color-forest-green-light) 0%, var(--color-forest-green) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.auth-layout-left.patient-theme .auth-layout-feature-icon {
    color: var(--color-forest-green-light);
}

/* Patient Register Form Styles */
.patient-register-form {
    display: flex;
    flex-direction: column;
    gap: var(--space-1);
}

.patient-register-form .form-group {
    margin-bottom: var(--space-4);
}

.patient-register-form .form-label {
    display: flex;
    align-items: center;
    font-size: var(--font-size-sm);
    font-weight: var(--font-weight-medium);
    color: var(--text-primary);
    margin-bottom: var(--space-2);
}

.patient-register-form .form-label i {
    color: var(--color-forest-green);
    font-size: var(--font-size-base);
}

.patient-register-form .auth-input {
    width: 100%;
    padding: var(--space-3) var(--space-4);
    font-size: var(--font-size-base);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-lg);
    background: var(--color-white);
    transition: all 0.2s ease;
}

.patient-register-form .auth-input:focus {
    outline: none;
    border-color: var(--color-teal-primary);
    box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15);
}

.patient-register-form .auth-input.is-invalid {
    border-color: var(--color-error);
}

.patient-register-form .auth-input::placeholder {
    color: var(--text-muted);
}

.patient-register-form .form-check-input:checked {
    background-color: var(--color-forest-green);
    border-color: var(--color-forest-green);
}

.patient-register-form .form-check-input:focus {
    border-color: var(--color-teal-primary);
    box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15);
}

/* Password Input Wrapper */
.password-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.password-input-wrapper .auth-input {
    padding-right: var(--space-12);
}

.password-toggle {
    position: absolute;
    right: var(--space-3);
    background: none;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    padding: var(--space-2);
    transition: color 0.2s ease;
}

.password-toggle:hover {
    color: var(--text-primary);
}

/* Register Button */
.btn-register {
    width: 100%;
    padding: var(--space-3) var(--space-4);
    font-size: var(--font-size-base);
    font-weight: var(--font-weight-semibold);
    border-radius: var(--radius-lg);
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-2);
}

.btn-green {
    background: var(--color-forest-green);
    color: var(--text-inverse);
    border: none;
}

.btn-green:hover {
    background: var(--color-forest-green-dark);
    transform: translateY(-1px);
    box-shadow: var(--shadow-lg);
}

.btn-green:active {
    transform: translateY(0);
}

/* Auth Links */
.auth-link {
    color: var(--color-teal-primary);
    text-decoration: none;
    font-weight: var(--font-weight-medium);
    transition: color 0.2s ease;
}

.auth-link:hover {
    color: var(--color-teal-primary-dark);
    text-decoration: underline;
}

.auth-link-primary {
    color: var(--color-forest-green);
    text-decoration: none;
    font-weight: var(--font-weight-semibold);
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: var(--space-1);
}

.auth-link-primary:hover {
    color: var(--color-forest-green-dark);
    text-decoration: underline;
}

.auth-link-primary i {
    transition: transform 0.2s ease;
}

.auth-link-primary:hover i {
    transform: translateX(4px);
}

/* Invalid Feedback */
.invalid-feedback {
    font-size: var(--font-size-sm);
    color: var(--color-error);
    margin-top: var(--space-1);
}

/* Form Check */
.form-check {
    display: flex;
    align-items: flex-start;
    gap: var(--space-2);
}

.form-check-input {
    margin-top: var(--space-1);
    width: var(--space-4);
    height: var(--space-4);
    cursor: pointer;
}

.form-check-label {
    font-size: var(--font-size-sm);
    color: var(--text-secondary);
    cursor: pointer;
}

/* Auth Footer Links */
.auth-footer-links {
    margin-top: var(--space-4);
}

.auth-footer-links hr {
    border: none;
    border-top: 1px solid var(--border-light);
}

/* Responsive Adjustments */
@media (max-width: 639px) {
    .patient-register-form .form-group {
        margin-bottom: var(--space-3);
    }

    .btn-register {
        padding: var(--space-3);
    }
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
</script>