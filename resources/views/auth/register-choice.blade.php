@extends('master')

@section('title', 'Join MedSuite AI — Choose Account Type')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">
@endpush

@section('content')
<div class="msreg-root">

    <!-- Background -->
    <div class="msreg-bg">
        <div class="msreg-orb msreg-orb-1"></div>
        <div class="msreg-orb msreg-orb-2"></div>
        <div class="msreg-grid"></div>
    </div>

    <!-- Top bar -->
    <header class="msreg-header">
        <a href="/" class="msreg-brand">
            <span class="msreg-brand-icon"><i class="bi bi-heart-pulse-fill"></i></span>
            <span class="msreg-brand-text">MedSuite<em>AI</em></span>
        </a>
        <div class="msreg-header-right">
            <span class="msreg-header-hint">Already registered?</span>
            <a href="{{ route('login') }}" class="msreg-signin-btn">Sign In <i class="bi bi-arrow-right ms-1"></i></a>
        </div>
    </header>

    <!-- Main -->
    <main class="msreg-main">

        <!-- Step label -->
        <div class="msreg-step-label">
            <span class="msreg-step-dot"></span>
            Step 1 of 2 — Account Type
        </div>

        <!-- Headline -->
        <div class="msreg-headline">
            <h1>Who are<br><em>you?</em></h1>
            <p>Choose the account type that best describes your role. Each unlocks a different set of tools built precisely for you.</p>
        </div>

        <!-- Cards -->
        <div class="msreg-cards">

            <!-- Doctor Card -->
            <a href="/register-doctor" class="msreg-card msreg-card-doctor">
                <div class="msreg-card-bg-glow"></div>
                <div class="msreg-card-inner">
                    <div class="msreg-card-icon">
                        <i class="fas fa-user-doctor"></i>
                    </div>
                    <div class="msreg-card-badge">For Clinicians</div>
                    <h2 class="msreg-card-title">Healthcare<br>Professional</h2>
                    <p class="msreg-card-desc">
                        Doctor, nurse, or healthcare provider. Access clinical decision support, voice documentation, practice management, and professional tools.
                    </p>
                    <ul class="msreg-card-features">
                        <li><i class="bi bi-check2"></i> AI Medical Copilot</li>
                        <li><i class="bi bi-check2"></i> Voice Transcription</li>
                        <li><i class="bi bi-check2"></i> Patient Management</li>
                        <li><i class="bi bi-check2"></i> Billing & Invoicing</li>
                        <li><i class="bi bi-check2"></i> Professional Landing Page</li>
                    </ul>
                    <div class="msreg-card-cta">
                        <span>Register as Clinician</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </div>
            </a>

            <!-- Divider -->
            <div class="msreg-or">
                <span>or</span>
            </div>

            <!-- Patient Card -->
            <a href="{{ route('patient.register') }}" class="msreg-card msreg-card-patient">
                <div class="msreg-card-bg-glow msreg-card-bg-glow-patient"></div>
                <div class="msreg-card-inner">
                    <div class="msreg-card-icon msreg-card-icon-patient">
                        <i class="fas fa-user-injured"></i>
                    </div>
                    <div class="msreg-card-badge msreg-card-badge-patient">For Individuals</div>
                    <h2 class="msreg-card-title">Patient</h2>
                    <p class="msreg-card-desc">
                        Book appointments, manage your health records, receive reminders, and connect with healthcare professionals near you.
                    </p>
                    <ul class="msreg-card-features">
                        <li><i class="bi bi-check2"></i> Easy Appointment Booking</li>
                        <li><i class="bi bi-check2"></i> Health Record Access</li>
                        <li><i class="bi bi-check2"></i> Visit History</li>
                        <li><i class="bi bi-check2"></i> SMS & Email Reminders</li>
                        <li><i class="bi bi-check2"></i> Doctor Search</li>
                    </ul>
                    <div class="msreg-card-cta msreg-card-cta-patient">
                        <span>Register as Patient</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- Guest option -->
        <div class="msreg-guest">
            <span>Not ready to commit?</span>
            <a href="{{ route('doctors.index') }}">Browse doctors as guest <i class="bi bi-arrow-right ms-1"></i></a>
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
    --teal:       #00d4aa;
    --teal-dim:   rgba(0,212,170,0.10);
    --teal-glow:  rgba(0,212,170,0.25);
    --violet:     #6366f1;
    --violet-dim: rgba(99,102,241,0.10);
    --white:      #ffffff;
    --offwhite:   #e8edf5;
    --muted:      rgba(232,237,245,0.45);
    --border:     rgba(255,255,255,0.07);
    --glass:      rgba(255,255,255,0.03);
    --font-display: 'Cormorant Garamond', Georgia, serif;
    --font-body:    'DM Sans', sans-serif;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { background: var(--navy); color: var(--offwhite); font-family: var(--font-body); }
a { text-decoration: none; color: inherit; }

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   ROOT
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.msreg-root { min-height: 100vh; position: relative; display: flex; flex-direction: column; overflow: hidden; }

.msreg-bg { position: fixed; inset: 0; pointer-events: none; z-index: 0; }
.msreg-orb {
    position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.4;
}
.msreg-orb-1 {
    width: 700px; height: 700px;
    background: radial-gradient(circle, rgba(0,212,170,0.2) 0%, transparent 65%);
    top: -300px; right: -200px;
    animation: regOrb1 10s ease-in-out infinite;
}
.msreg-orb-2 {
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, transparent 65%);
    bottom: -200px; left: -100px;
    animation: regOrb1 13s ease-in-out infinite reverse;
}
.msreg-grid {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
    background-size: 60px 60px;
}
@keyframes regOrb1 {
    0%, 100% { transform: scale(1) translate(0,0); }
    50% { transform: scale(1.08) translate(-20px, 20px); }
}

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   HEADER
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.msreg-header {
    position: relative; z-index: 10;
    display: flex; align-items: center; justify-content: space-between;
    padding: 1.5rem 3rem;
    border-bottom: 1px solid var(--border);
    background: rgba(6,13,31,0.6);
    backdrop-filter: blur(20px);
}
.msreg-brand {
    display: flex; align-items: center; gap: 0.6rem;
    font-family: var(--font-display); font-size: 1.35rem; font-weight: 600; color: var(--white);
}
.msreg-brand-icon {
    width: 32px; height: 32px; background: var(--teal); border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    color: var(--navy); font-size: 0.85rem;
}
.msreg-brand-text em { color: var(--teal); font-style: normal; }
.msreg-header-right { display: flex; align-items: center; gap: 1rem; }
.msreg-header-hint { font-size: 0.84375rem; color: var(--muted); }
.msreg-signin-btn {
    font-size: 0.84375rem; font-weight: 500; color: var(--teal);
    padding: 0.45rem 1rem;
    border: 1px solid rgba(0,212,170,0.3);
    border-radius: 50px; transition: all 0.2s;
}
.msreg-signin-btn:hover { background: rgba(0,212,170,0.08); }

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   MAIN
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.msreg-main {
    position: relative; z-index: 2;
    flex: 1;
    display: flex; flex-direction: column; align-items: center;
    padding: 4rem 2rem 3rem;
    animation: mainIn 0.7s ease-out both;
}
@keyframes mainIn { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: none; } }

.msreg-step-label {
    display: inline-flex; align-items: center; gap: 0.5rem;
    font-size: 0.72rem; letter-spacing: 0.12em; text-transform: uppercase;
    color: var(--teal); margin-bottom: 2rem;
}
.msreg-step-dot {
    width: 6px; height: 6px; background: var(--teal); border-radius: 50%;
    box-shadow: 0 0 8px var(--teal);
}

.msreg-headline {
    text-align: center; margin-bottom: 3.5rem;
}
.msreg-headline h1 {
    font-family: var(--font-display);
    font-size: clamp(3rem, 6vw, 5rem);
    font-weight: 300; line-height: 1.05;
    color: var(--white); margin-bottom: 1rem;
}
.msreg-headline h1 em { color: var(--teal); font-style: italic; }
.msreg-headline p {
    font-size: 1rem; color: var(--muted); max-width: 480px; margin: 0 auto; line-height: 1.75;
}

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   CARDS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.msreg-cards {
    display: flex; align-items: stretch; gap: 0;
    max-width: 900px; width: 100%;
    margin-bottom: 2.5rem;
}

.msreg-card {
    flex: 1; position: relative; overflow: hidden;
    display: block;
    background: var(--navy-card);
    border: 1px solid var(--border);
    border-radius: 24px;
    cursor: pointer;
    transition: all 0.35s cubic-bezier(0.2, 0, 0, 1);
}
.msreg-card-doctor { border-radius: 24px 0 0 24px; border-right: none; }
.msreg-card-patient { border-radius: 0 24px 24px 0; }

.msreg-card:hover { transform: translateY(-6px); z-index: 2; }
.msreg-card-doctor:hover { border-color: rgba(0,212,170,0.4); box-shadow: -20px 0 60px rgba(0,0,0,0.3), 0 30px 60px rgba(0,212,170,0.12); }
.msreg-card-patient:hover { border-color: rgba(99,102,241,0.4); box-shadow: 20px 0 60px rgba(0,0,0,0.3), 0 30px 60px rgba(99,102,241,0.12); }

.msreg-card-bg-glow {
    position: absolute; inset: 0; pointer-events: none;
    background: radial-gradient(ellipse at 50% 0%, rgba(0,212,170,0.12) 0%, transparent 65%);
    opacity: 0; transition: opacity 0.35s;
}
.msreg-card-bg-glow-patient {
    background: radial-gradient(ellipse at 50% 0%, rgba(99,102,241,0.12) 0%, transparent 65%);
}
.msreg-card:hover .msreg-card-bg-glow { opacity: 1; }

.msreg-card-inner { position: relative; z-index: 2; padding: 2.5rem 2rem; }

.msreg-card-icon {
    width: 56px; height: 56px;
    background: var(--teal-dim);
    border: 1px solid rgba(0,212,170,0.25);
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    color: var(--teal); font-size: 1.4rem;
    margin-bottom: 1rem;
}
.msreg-card-icon-patient {
    background: var(--violet-dim);
    border-color: rgba(99,102,241,0.25);
    color: #818cf8;
}

.msreg-card-badge {
    display: inline-block;
    font-size: 0.68rem; letter-spacing: 0.12em; text-transform: uppercase;
    color: var(--teal); padding: 0.25rem 0.6rem;
    background: rgba(0,212,170,0.08);
    border: 1px solid rgba(0,212,170,0.2);
    border-radius: 50px;
    margin-bottom: 1.25rem;
}
.msreg-card-badge-patient { color: #818cf8; background: rgba(99,102,241,0.08); border-color: rgba(99,102,241,0.2); }

.msreg-card-title {
    font-family: var(--font-display);
    font-size: 2rem; font-weight: 400; line-height: 1.1;
    color: var(--white); margin-bottom: 1rem;
}

.msreg-card-desc {
    font-size: 0.875rem; color: var(--muted); line-height: 1.75;
    margin-bottom: 1.75rem;
}

.msreg-card-features {
    list-style: none; display: flex; flex-direction: column; gap: 0.6rem;
    margin-bottom: 2rem;
}
.msreg-card-features li {
    display: flex; align-items: center; gap: 0.6rem;
    font-size: 0.84375rem; color: var(--offwhite);
}
.msreg-card-features i { color: var(--teal); font-size: 0.875rem; flex-shrink: 0; }

.msreg-card-cta {
    display: inline-flex; align-items: center; gap: 0.6rem;
    padding: 0.75rem 1.5rem;
    background: var(--teal); color: var(--navy);
    font-size: 0.875rem; font-weight: 600;
    border-radius: 50px;
    transition: all 0.25s;
    box-shadow: 0 0 20px rgba(0,212,170,0.2);
}
.msreg-card-doctor:hover .msreg-card-cta { box-shadow: 0 0 30px rgba(0,212,170,0.45); transform: translateX(3px); }
.msreg-card-cta-patient {
    background: #6366f1; color: var(--white);
    box-shadow: 0 0 20px rgba(99,102,241,0.2);
}
.msreg-card-patient:hover .msreg-card-cta-patient { box-shadow: 0 0 30px rgba(99,102,241,0.45); transform: translateX(3px); }

/* OR divider */
.msreg-or {
    display: flex; align-items: center; justify-content: center;
    width: 48px; flex-shrink: 0;
    position: relative;
}
.msreg-or::before {
    content: ''; position: absolute; top: 0; bottom: 0; left: 50%;
    width: 1px; background: var(--border);
}
.msreg-or span {
    position: relative; z-index: 1;
    width: 32px; height: 32px;
    background: var(--navy-mid); border: 1px solid var(--border);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.7rem; color: var(--muted);
}

/* Guest */
.msreg-guest {
    display: flex; align-items: center; gap: 0.75rem;
    font-size: 0.84375rem; color: var(--muted);
}
.msreg-guest a { color: var(--offwhite); font-weight: 400; transition: color 0.2s; }
.msreg-guest a:hover { color: var(--teal); }

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   RESPONSIVE
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
@media (max-width: 768px) {
    .msreg-header { padding: 1.25rem 1.5rem; }
    .msreg-cards { flex-direction: column; max-width: 480px; gap: 1rem; }
    .msreg-card-doctor { border-radius: 20px; border-right: 1px solid var(--border); }
    .msreg-card-patient { border-radius: 20px; }
    .msreg-card:hover { transform: translateY(-3px); }
    .msreg-or { height: 40px; width: auto; flex-direction: row; }
    .msreg-or::before { top: 50%; bottom: auto; left: 0; right: 0; width: auto; height: 1px; }
}
</style>
@endsection
