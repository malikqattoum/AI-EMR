@extends('master')

@section('title', 'Register — MedSuite AI')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">
@endpush

@section('content')
<div class="msdr-root">

    <!-- ═══ LEFT PANEL ═══ -->
    <aside class="msdr-panel">
        <div class="msdr-panel-bg">
            <div class="msdr-panel-orb msdr-panel-orb-1"></div>
            <div class="msdr-panel-orb msdr-panel-orb-2"></div>
            <div class="msdr-panel-grid"></div>
        </div>

        <div class="msdr-panel-inner">
            <!-- Brand -->
            <a href="/" class="msdr-brand">
                <span class="msdr-brand-icon"><i class="bi bi-heart-pulse-fill"></i></span>
                <span class="msdr-brand-name">MedSuite<em>AI</em></span>
            </a>

            <!-- Headline -->
            <div class="msdr-panel-headline">
                <h2>Join the next<br>generation of<br><em>clinical care.</em></h2>
                <p>Your AI-powered practice management platform — from patient records to intelligent diagnosis support.</p>
            </div>

            <!-- Features list -->
            <ul class="msdr-panel-features">
                <li>
                    <div class="msdr-pf-icon"><i class="bi bi-robot"></i></div>
                    <div>
                        <strong>AI Medical Copilot</strong>
                        <span>Clinical decision support at every appointment</span>
                    </div>
                </li>
                <li>
                    <div class="msdr-pf-icon"><i class="bi bi-mic-fill"></i></div>
                    <div>
                        <strong>Voice Transcription</strong>
                        <span>Hands-free documentation in real time</span>
                    </div>
                </li>
                <li>
                    <div class="msdr-pf-icon"><i class="bi bi-people-fill"></i></div>
                    <div>
                        <strong>Patient Management</strong>
                        <span>Complete records and smart timelines</span>
                    </div>
                </li>
                <li>
                    <div class="msdr-pf-icon"><i class="bi bi-globe2"></i></div>
                    <div>
                        <strong>Professional Page</strong>
                        <span>Your own bookable landing page</span>
                    </div>
                </li>
            </ul>

            <!-- Trust indicators -->
            <div class="msdr-panel-trust">
                <div class="msdr-trust-row">
                    <i class="bi bi-shield-check-fill"></i>
                    <span>HIPAA Compliant & End-to-End Encrypted</span>
                </div>
                <div class="msdr-trust-row">
                    <i class="bi bi-star-fill"></i>
                    <span>Trusted by 500+ healthcare professionals</span>
                </div>
            </div>
        </div>
    </aside>

    <!-- ═══ RIGHT PANEL — FORM ═══ -->
    <main class="msdr-form-panel">

        <!-- Top strip -->
        <div class="msdr-form-topbar">
            <a href="{{ route('register') }}" class="msdr-back-btn">
                <i class="bi bi-arrow-left"></i> Back
            </a>
            <span class="msdr-topbar-hint">Already have an account?</span>
            <a href="{{ route('login') }}" class="msdr-topbar-link">Sign in <i class="bi bi-arrow-right ms-1"></i></a>
        </div>

        <!-- Form wrapper -->
        <div class="msdr-form-wrapper">
            <!-- Progress dots -->
            <div class="msdr-progress">
                <div class="msdr-progress-step msdr-progress-step-done"><i class="bi bi-check"></i></div>
                <div class="msdr-progress-line msdr-progress-line-done"></div>
                <div class="msdr-progress-step msdr-progress-step-active">2</div>
                <div class="msdr-progress-line"></div>
                <div class="msdr-progress-step">3</div>
                <span class="msdr-progress-label">Account Details</span>
            </div>

            <div class="msdr-form-header">
                <h1>Create your<br><em>account.</em></h1>
                <p>Fill in your professional details to get started. It only takes 2 minutes.</p>
            </div>

            <!-- FORM -->
            <form method="POST" action="{{ route('register') }}" class="msdr-form" novalidate>
                @csrf

                <!-- Full Name -->
                <div class="msdr-field">
                    <label for="name" class="msdr-label">
                        <i class="bi bi-person"></i> Full Name
                    </label>
                    <div class="msdr-input-wrap">
                        <input
                            id="name" type="text" name="name"
                            class="msdr-input @error('name') msdr-input-error @enderror"
                            value="{{ old('name') }}" required autofocus
                            placeholder="Dr. Sarah Mitchell"
                        >
                    </div>
                    @error('name')
                        <div class="msdr-error-msg"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="msdr-field">
                    <label for="email" class="msdr-label">
                        <i class="bi bi-envelope"></i> Email Address
                    </label>
                    <div class="msdr-input-wrap">
                        <input
                            id="email" type="email" name="email"
                            class="msdr-input @error('email') msdr-input-error @enderror"
                            value="{{ old('email') }}" required
                            placeholder="you@clinic.com"
                        >
                    </div>
                    @error('email')
                        <div class="msdr-error-msg"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="msdr-field">
                    <label for="phone" class="msdr-label">
                        <i class="bi bi-telephone"></i> Phone Number
                        <span class="msdr-label-required">Required</span>
                    </label>
                    <div class="msdr-input-wrap">
                        <input
                            id="phone" type="tel" name="phone"
                            class="msdr-input @error('phone') msdr-input-error @enderror"
                            value="{{ old('phone') }}" required
                            placeholder="+1 234 567 8900"
                            pattern="^\+?[1-9]\d{1,14}$"
                        >
                    </div>
                    <div class="msdr-field-hint">
                        <i class="bi bi-info-circle"></i> Used for SMS appointment reminders. Include country code.
                    </div>
                    @error('phone')
                        <div class="msdr-error-msg"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <!-- Passwords row -->
                <div class="msdr-field-row">
                    <!-- Password -->
                    <div class="msdr-field">
                        <label for="password" class="msdr-label">
                            <i class="bi bi-lock"></i> Password
                        </label>
                        <div class="msdr-input-wrap msdr-input-wrap-toggle">
                            <input
                                id="password" type="password" name="password"
                                class="msdr-input @error('password') msdr-input-error @enderror"
                                required placeholder="Create a strong password"
                            >
                            <button type="button" class="msdr-eye-btn" onclick="msdrToggle('password', 'eye-pw')">
                                <i id="eye-pw" class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="msdr-error-msg"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="msdr-field">
                        <label for="password_confirmation" class="msdr-label">
                            <i class="bi bi-shield-check"></i> Confirm Password
                        </label>
                        <div class="msdr-input-wrap msdr-input-wrap-toggle">
                            <input
                                id="password_confirmation" type="password" name="password_confirmation"
                                class="msdr-input @error('password_confirmation') msdr-input-error @enderror"
                                required placeholder="Confirm password"
                            >
                            <button type="button" class="msdr-eye-btn" onclick="msdrToggle('password_confirmation', 'eye-pc')">
                                <i id="eye-pc" class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <div class="msdr-error-msg"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Specialty -->
                <div class="msdr-field">
                    <label for="specialty_select" class="msdr-label">
                        <i class="bi bi-heart-pulse"></i> Medical Specialty
                        <span class="msdr-label-required">Required</span>
                    </label>

                    <div class="msdr-select-wrap">
                        <select
                            id="specialty_select" name="specialty_select"
                            class="msdr-input msdr-select"
                            onchange="msdrToggleSpecialty()"
                        >
                            <option value="">— Select your specialty —</option>
                            <optgroup label="General & Internal Medicine">
                                <option value="General Practitioner">General Practitioner / Family Medicine</option>
                                <option value="Internal Medicine">Internal Medicine</option>
                            </optgroup>
                            <optgroup label="Internal Medicine Subspecialties">
                                <option value="Cardiology">Cardiology</option>
                                <option value="Pulmonology">Pulmonology</option>
                                <option value="Gastroenterology">Gastroenterology</option>
                                <option value="Nephrology">Nephrology</option>
                                <option value="Endocrinology">Endocrinology</option>
                                <option value="Hematology">Hematology</option>
                                <option value="Rheumatology">Rheumatology</option>
                                <option value="Dermatology">Dermatology</option>
                            </optgroup>
                            <optgroup label="Emergency & Critical Care">
                                <option value="Emergency Medicine">Emergency Medicine</option>
                                <option value="Critical Care">Critical Care / ICU</option>
                            </optgroup>
                            <optgroup label="Neurology & Psychiatry">
                                <option value="Neurology">Neurology</option>
                                <option value="Neurosurgery">Neurosurgery</option>
                                <option value="Psychiatry">Psychiatry</option>
                            </optgroup>
                            <optgroup label="Surgical Specialties">
                                <option value="General Surgery">General Surgery</option>
                                <option value="Orthopedic Surgery">Orthopedic Surgery</option>
                                <option value="Cardiothoracic Surgery">Cardiothoracic Surgery</option>
                                <option value="Plastic & Reconstructive Surgery">Plastic & Reconstructive Surgery</option>
                                <option value="Urology">Urology</option>
                                <option value="Ophthalmic Surgery">Ophthalmic Surgery</option>
                            </optgroup>
                            <optgroup label="Pediatrics & Women's Health">
                                <option value="Pediatrics">Pediatrics</option>
                                <option value="Obstetrics & Gynecology">Obstetrics & Gynecology (OB/GYN)</option>
                            </optgroup>
                            <optgroup label="Diagnostic & Support">
                                <option value="Radiology">Radiology</option>
                                <option value="Pathology">Pathology</option>
                                <option value="Oncology">Oncology</option>
                            </optgroup>
                            <optgroup label="Other">
                                <option value="Geriatrics">Geriatrics</option>
                                <option value="Sports Medicine">Sports Medicine</option>
                                <option value="Physical Medicine & Rehabilitation">Physical Medicine & Rehabilitation</option>
                                <option value="other">Other (specify below)</option>
                            </optgroup>
                        </select>
                        <i class="bi bi-chevron-down msdr-select-arrow"></i>
                    </div>

                    <!-- Custom specialty input -->
                    <div id="msdr-custom-specialty" class="msdr-custom-reveal" style="display:none; margin-top: 0.75rem;">
                        <input
                            type="text" name="custom_specialty" id="custom_specialty"
                            class="msdr-input @error('custom_specialty') msdr-input-error @enderror"
                            placeholder="Enter your specialty"
                            value="{{ old('custom_specialty') }}"
                        >
                        @error('custom_specialty')
                            <div class="msdr-error-msg"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Hidden fields -->
                    <input type="hidden" name="specialty" id="specialty" value="{{ old('specialty') }}">
                    <input type="hidden" name="selected_plan" id="selected_plan" value="{{ $selectedPlan ?? 'professional' }}">
                    <input type="hidden" name="selected_billing" id="selected_billing" value="{{ $selectedBilling ?? 'monthly' }}">

                    @error('specialty')
                        <div class="msdr-error-msg"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <!-- Terms -->
                <div class="msdr-terms">
                    <input class="msdr-checkbox" type="checkbox" id="terms" required>
                    <label for="terms">
                        I agree to MedSuite's
                        <a href="#" class="msdr-link">Terms of Service</a> and
                        <a href="#" class="msdr-link">Privacy Policy</a>
                    </label>
                </div>

                <!-- Submit -->
                <button type="submit" class="msdr-submit">
                    <span>Create My Account</span>
                    <i class="bi bi-arrow-right"></i>
                </button>

                <!-- Bottom hint -->
                <p class="msdr-bottom-hint">
                    <i class="bi bi-lock-fill"></i>
                    Your data is encrypted and HIPAA-compliant. We never sell your information.
                </p>
            </form>
        </div>
    </main>
</div>

<style>
/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   TOKENS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
:root {
    --navy:       #060d1f;
    --navy-mid:   #0c1633;
    --navy-card:  #0f1c3a;
    --navy-input: #0a1428;
    --teal:       #00d4aa;
    --teal-dim:   rgba(0,212,170,0.10);
    --teal-glow:  rgba(0,212,170,0.25);
    --white:      #ffffff;
    --offwhite:   #e8edf5;
    --muted:      rgba(232,237,245,0.45);
    --border:     rgba(255,255,255,0.07);
    --border-focus: rgba(0,212,170,0.5);
    --glass:      rgba(255,255,255,0.03);
    --error:      #f87171;
    --font-display: 'Cormorant Garamond', Georgia, serif;
    --font-body:    'DM Sans', sans-serif;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { background: var(--navy); color: var(--offwhite); font-family: var(--font-body); }
a { text-decoration: none; color: inherit; }

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   ROOT LAYOUT — TWO PANEL
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.msdr-root {
    display: grid;
    grid-template-columns: 420px 1fr;
    min-height: 100vh;
}

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   LEFT PANEL
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.msdr-panel {
    position: sticky; top: 0;
    height: 100vh; overflow: hidden;
    background: var(--navy-mid);
    border-right: 1px solid var(--border);
}
.msdr-panel-bg { position: absolute; inset: 0; pointer-events: none; }
.msdr-panel-orb {
    position: absolute; border-radius: 50%; filter: blur(70px); opacity: 0.6;
}
.msdr-panel-orb-1 {
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(0,212,170,0.2) 0%, transparent 65%);
    top: -200px; left: -150px;
    animation: panelOrb 10s ease-in-out infinite;
}
.msdr-panel-orb-2 {
    width: 350px; height: 350px;
    background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, transparent 65%);
    bottom: -100px; right: -100px;
    animation: panelOrb 13s ease-in-out infinite reverse;
}
.msdr-panel-grid {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
    background-size: 50px 50px;
}
@keyframes panelOrb {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1) translate(10px, -10px); }
}

.msdr-panel-inner {
    position: relative; z-index: 2;
    height: 100%; display: flex; flex-direction: column;
    padding: 2.5rem 2.5rem;
    overflow-y: auto;
}

.msdr-brand {
    display: flex; align-items: center; gap: 0.6rem;
    font-family: var(--font-display); font-size: 1.3rem; font-weight: 600; color: var(--white);
    margin-bottom: 3.5rem;
}
.msdr-brand-icon {
    width: 32px; height: 32px; background: var(--teal); border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    color: var(--navy); font-size: 0.85rem;
}
.msdr-brand-name em { color: var(--teal); font-style: normal; }

.msdr-panel-headline { margin-bottom: 3rem; }
.msdr-panel-headline h2 {
    font-family: var(--font-display);
    font-size: 2.5rem; font-weight: 300; line-height: 1.1;
    color: var(--white); margin-bottom: 1rem;
}
.msdr-panel-headline h2 em { color: var(--teal); font-style: italic; }
.msdr-panel-headline p { font-size: 0.9rem; color: var(--muted); line-height: 1.75; }

.msdr-panel-features {
    list-style: none; display: flex; flex-direction: column; gap: 1.25rem;
    margin-bottom: 3rem; flex: 1;
}
.msdr-panel-features li {
    display: flex; align-items: flex-start; gap: 0.875rem;
}
.msdr-pf-icon {
    width: 36px; height: 36px; flex-shrink: 0;
    background: var(--teal-dim);
    border: 1px solid rgba(0,212,170,0.2);
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    color: var(--teal); font-size: 0.875rem;
}
.msdr-panel-features strong { display: block; font-size: 0.875rem; font-weight: 500; color: var(--white); margin-bottom: 0.15rem; }
.msdr-panel-features span { font-size: 0.78rem; color: var(--muted); }

.msdr-panel-trust { display: flex; flex-direction: column; gap: 0.75rem; margin-top: auto; padding-top: 2rem; border-top: 1px solid var(--border); }
.msdr-trust-row {
    display: flex; align-items: center; gap: 0.6rem;
    font-size: 0.78rem; color: var(--muted);
}
.msdr-trust-row i { color: var(--teal); font-size: 0.8rem; }

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   RIGHT PANEL
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.msdr-form-panel {
    background: var(--navy);
    display: flex; flex-direction: column;
    overflow-y: auto;
}

.msdr-form-topbar {
    display: flex; align-items: center; gap: 1rem;
    padding: 1.25rem 3rem;
    border-bottom: 1px solid var(--border);
    position: sticky; top: 0; z-index: 10;
    background: rgba(6,13,31,0.85);
    backdrop-filter: blur(20px);
}
.msdr-back-btn {
    display: inline-flex; align-items: center; gap: 0.4rem;
    font-size: 0.84375rem; color: var(--muted);
    padding: 0.4rem 0.875rem;
    border: 1px solid var(--border); border-radius: 50px;
    transition: all 0.2s; margin-right: auto;
}
.msdr-back-btn:hover { color: var(--white); border-color: rgba(255,255,255,0.2); }
.msdr-topbar-hint { font-size: 0.84375rem; color: var(--muted); }
.msdr-topbar-link {
    font-size: 0.84375rem; font-weight: 500; color: var(--teal);
    padding: 0.4rem 0.875rem;
    border: 1px solid rgba(0,212,170,0.3); border-radius: 50px;
    transition: all 0.2s;
}
.msdr-topbar-link:hover { background: rgba(0,212,170,0.08); }

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   FORM WRAPPER
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.msdr-form-wrapper {
    max-width: 600px; margin: 0 auto; width: 100%;
    padding: 3rem 2rem 4rem;
    animation: formIn 0.6s ease-out both;
}
@keyframes formIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: none; } }

/* Progress */
.msdr-progress {
    display: flex; align-items: center; gap: 0; margin-bottom: 3rem; position: relative;
}
.msdr-progress-step {
    width: 32px; height: 32px; border-radius: 50%;
    background: var(--glass); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.78rem; color: var(--muted); flex-shrink: 0;
    z-index: 1;
}
.msdr-progress-step-done { background: var(--teal); border-color: var(--teal); color: var(--navy); font-size: 0.875rem; }
.msdr-progress-step-active { background: rgba(0,212,170,0.15); border-color: var(--teal); color: var(--teal); font-weight: 600; }
.msdr-progress-line {
    flex: 1; height: 1px; background: var(--border);
}
.msdr-progress-line-done { background: var(--teal); }
.msdr-progress-label {
    margin-left: 1rem; font-size: 0.78rem; color: var(--muted); white-space: nowrap;
}

.msdr-form-header { margin-bottom: 2.5rem; }
.msdr-form-header h1 {
    font-family: var(--font-display);
    font-size: clamp(2rem, 3.5vw, 3rem);
    font-weight: 300; line-height: 1.05;
    color: var(--white); margin-bottom: 0.75rem;
}
.msdr-form-header h1 em { color: var(--teal); font-style: italic; }
.msdr-form-header p { font-size: 0.9rem; color: var(--muted); }

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   FORM FIELDS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.msdr-form { display: flex; flex-direction: column; gap: 1.5rem; }

.msdr-field { display: flex; flex-direction: column; gap: 0.5rem; }

.msdr-field-row {
    display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;
}

.msdr-label {
    display: flex; align-items: center; gap: 0.4rem;
    font-size: 0.8125rem; font-weight: 500; color: var(--offwhite);
}
.msdr-label i { color: var(--teal); font-size: 0.875rem; }
.msdr-label-required {
    margin-left: auto;
    font-size: 0.68rem; letter-spacing: 0.06em; text-transform: uppercase;
    color: var(--teal); background: rgba(0,212,170,0.08);
    border: 1px solid rgba(0,212,170,0.2);
    padding: 0.15rem 0.5rem; border-radius: 50px;
}

.msdr-input-wrap { position: relative; }
.msdr-input-wrap-toggle .msdr-input { padding-right: 3rem; }

.msdr-input {
    width: 100%;
    padding: 0.8rem 1rem;
    background: var(--navy-input);
    border: 1px solid var(--border);
    border-radius: 12px;
    color: var(--offwhite);
    font-size: 0.9rem; font-family: var(--font-body);
    transition: all 0.2s;
    outline: none;
    -webkit-appearance: none; appearance: none;
}
.msdr-input::placeholder { color: rgba(232,237,245,0.25); }
.msdr-input:focus {
    border-color: var(--border-focus);
    background: rgba(0,212,170,0.03);
    box-shadow: 0 0 0 3px rgba(0,212,170,0.08), inset 0 0 0 1px rgba(0,212,170,0.1);
}
.msdr-input-error { border-color: rgba(248,113,113,0.5) !important; }
.msdr-input-error:focus { box-shadow: 0 0 0 3px rgba(248,113,113,0.08) !important; }

.msdr-select { cursor: pointer; }
.msdr-select option, .msdr-select optgroup { background: #0c1633; color: var(--offwhite); }
.msdr-select-wrap { position: relative; }
.msdr-select-wrap .msdr-select { padding-right: 2.5rem; }
.msdr-select-arrow {
    position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);
    color: var(--muted); pointer-events: none; font-size: 0.75rem;
}

.msdr-eye-btn {
    position: absolute; right: 0.875rem; top: 50%; transform: translateY(-50%);
    background: none; border: none; color: var(--muted); cursor: pointer;
    font-size: 0.9rem; padding: 0.2rem;
    transition: color 0.2s;
}
.msdr-eye-btn:hover { color: var(--teal); }

.msdr-error-msg {
    display: flex; align-items: center; gap: 0.4rem;
    font-size: 0.78rem; color: var(--error);
}

.msdr-field-hint {
    display: flex; align-items: center; gap: 0.4rem;
    font-size: 0.75rem; color: var(--muted);
}
.msdr-field-hint i { color: var(--teal); }

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   TERMS + SUBMIT
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.msdr-terms {
    display: flex; align-items: flex-start; gap: 0.6rem;
}
.msdr-checkbox {
    width: 16px; height: 16px; margin-top: 2px;
    accent-color: var(--teal); cursor: pointer; flex-shrink: 0;
}
.msdr-terms label { font-size: 0.84375rem; color: var(--muted); cursor: pointer; line-height: 1.5; }
.msdr-link { color: var(--teal); font-weight: 400; transition: opacity 0.2s; }
.msdr-link:hover { opacity: 0.8; }

.msdr-submit {
    display: flex; align-items: center; justify-content: center; gap: 0.6rem;
    width: 100%; padding: 1rem;
    background: var(--teal); color: var(--navy);
    font-size: 1rem; font-weight: 600; font-family: var(--font-body);
    border: none; border-radius: 12px; cursor: pointer;
    transition: all 0.25s;
    box-shadow: 0 0 30px rgba(0,212,170,0.25);
}
.msdr-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 50px rgba(0,212,170,0.4);
}
.msdr-submit:active { transform: translateY(0); }

.msdr-bottom-hint {
    display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    font-size: 0.75rem; color: var(--muted); text-align: center;
}
.msdr-bottom-hint i { color: var(--teal); }

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   RESPONSIVE
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
@media (max-width: 900px) {
    .msdr-root { grid-template-columns: 1fr; }
    .msdr-panel { display: none; }
    .msdr-form-topbar { padding: 1rem 1.5rem; }
    .msdr-form-wrapper { padding: 2rem 1.5rem 3rem; }
    .msdr-field-row { grid-template-columns: 1fr; }
}
@media (max-width: 480px) {
    .msdr-topbar-hint { display: none; }
    .msdr-progress-label { display: none; }
}
</style>

@push('scripts')
<script>
function msdrToggle(inputId, eyeId) {
    const input = document.getElementById(inputId);
    const eye = document.getElementById(eyeId);
    if (input.type === 'password') {
        input.type = 'text';
        eye.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        eye.className = 'bi bi-eye';
    }
}

function msdrToggleSpecialty() {
    const select = document.getElementById('specialty_select');
    const container = document.getElementById('msdr-custom-specialty');
    const customInput = document.getElementById('custom_specialty');
    const hidden = document.getElementById('specialty');

    if (select.value === 'other') {
        container.style.display = 'block';
        customInput.required = true;
        customInput.focus();
        hidden.value = '';
    } else {
        container.style.display = 'none';
        customInput.required = false;
        customInput.value = '';
        hidden.value = select.value;
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const customInput = document.getElementById('custom_specialty');
    const hidden = document.getElementById('specialty');
    const select = document.getElementById('specialty_select');

    customInput.addEventListener('input', function () {
        if (select.value === 'other') hidden.value = this.value;
    });

    // Form submit guard
    document.querySelector('.msdr-form').addEventListener('submit', function (e) {
        const sel = document.getElementById('specialty_select');
        const ci = document.getElementById('custom_specialty');
        const hi = document.getElementById('specialty');

        if (sel.value === 'other') {
            if (!ci.value.trim()) {
                e.preventDefault();
                ci.focus();
                ci.classList.add('msdr-input-error');
                return false;
            }
            hi.value = ci.value.trim();
        } else {
            hi.value = sel.value;
        }
    });

    // Restore old values
    const oldSpecialty = '{{ old("specialty") }}';
    const oldCustom = '{{ old("custom_specialty") }}';

    if (oldCustom) {
        select.value = 'other';
        msdrToggleSpecialty();
        customInput.value = oldCustom;
        hidden.value = oldCustom;
    } else if (oldSpecialty) {
        const exists = Array.from(select.options).some(o => o.value === oldSpecialty);
        if (exists) {
            select.value = oldSpecialty;
        } else {
            select.value = 'other';
            msdrToggleSpecialty();
            customInput.value = oldSpecialty;
        }
        hidden.value = oldSpecialty;
    }
});
</script>
@endpush