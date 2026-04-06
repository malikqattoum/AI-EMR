@extends('master')

@section('title', 'Verify Email — MedSuite AI')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
            <a href="/" class="msdr-brand">
                <span class="msdr-brand-icon"><i class="bi bi-heart-pulse-fill"></i></span>
                <span class="msdr-brand-name">MedSuite<em>AI</em></span>
            </a>

            <div class="msdr-panel-headline">
                <h2>One more<br>step to<br><em>get started.</em></h2>
                <p>Check your inbox — we've sent a verification link to confirm your account.</p>
            </div>

            <ul class="msdr-panel-features">
                <li>
                    <div class="msdr-pf-icon"><i class="bi bi-shield-check-fill"></i></div>
                    <div>
                        <strong>Secure Verification</strong>
                        <span>HIPAA-compliant email verification</span>
                    </div>
                </li>
                <li>
                    <div class="msdr-pf-icon"><i class="bi bi-envelope-open-fill"></i></div>
                    <div>
                        <strong>Check Your Inbox</strong>
                        <span>Look for an email from our team</span>
                    </div>
                </li>
                <li>
                    <div class="msdr-pf-icon"><i class="bi bi-arrow-repeat"></i></div>
                    <div>
                        <strong>Resend Anytime</strong>
                        <span>Request a new link if needed</span>
                    </div>
                </li>
            </ul>

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

    <!-- ═══ RIGHT PANEL ═══ -->
    <main class="msdr-form-panel">

        <div class="msdr-form-topbar">
            <span class="msdr-topbar-hint">Already verified?</span>
            <a href="{{ route('login') }}" class="msdr-topbar-link">Sign in <i class="bi bi-arrow-right ms-1"></i></a>
        </div>

        <div class="msdr-form-wrapper">
            <div class="msdr-form-header">
                <h1>Verify your<br><em>email.</em></h1>
                <p>Click the link in the email we sent to complete your registration.</p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="msdr-success-banner">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>A new verification link has been sent to your email address.</span>
                </div>
            @endif

            <div class="msdr-info-box">
                <i class="bi bi-info-circle"></i>
                <p>Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.</p>
            </div>

            <form method="POST" action="{{ route('verification.send') }}" class="msdr-form">
                @csrf
                <button type="submit" class="msdr-submit">
                    <i class="bi bi-send"></i>
                    <span>Resend Verification Email</span>
                </button>
            </form>

            <div class="msdr-divider"><span>or</span></div>

            <form method="POST" action="{{ route('logout') }}" class="msdr-form">
                @csrf
                <button type="submit" class="msdr-logout-btn">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Sign Out</span>
                </button>
            </form>

            <p class="msdr-bottom-hint">
                <i class="bi bi-lock-fill"></i>
                Your data is encrypted and HIPAA-compliant.
            </p>
        </div>
    </main>
</div>

<style>
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
    --success-bg: rgba(0,212,170,0.08);
    --success-border: rgba(0,212,170,0.25);
    --font-display: 'Cormorant Garamond', Georgia, serif;
    --font-body:    'DM Sans', sans-serif;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { background: var(--navy); color: var(--offwhite); font-family: var(--font-body); }
a { text-decoration: none; color: inherit; }

.msdr-root {
    display: grid;
    grid-template-columns: 420px 1fr;
    min-height: 100vh;
}

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
.msdr-topbar-hint { font-size: 0.84375rem; color: var(--muted); margin-right: auto; }
.msdr-topbar-link {
    font-size: 0.84375rem; font-weight: 500; color: var(--teal);
    padding: 0.4rem 0.875rem;
    border: 1px solid rgba(0,212,170,0.3); border-radius: 50px;
    transition: all 0.2s;
}
.msdr-topbar-link:hover { background: rgba(0,212,170,0.08); }

.msdr-form-wrapper {
    max-width: 600px; margin: 0 auto; width: 100%;
    padding: 3rem 2rem 4rem;
    animation: formIn 0.6s ease-out both;
}
@keyframes formIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: none; } }

.msdr-form-header { margin-bottom: 2.5rem; }
.msdr-form-header h1 {
    font-family: var(--font-display);
    font-size: clamp(2rem, 3.5vw, 3rem);
    font-weight: 300; line-height: 1.05;
    color: var(--white); margin-bottom: 0.75rem;
}
.msdr-form-header h1 em { color: var(--teal); font-style: italic; }
.msdr-form-header p { font-size: 0.9rem; color: var(--muted); }

.msdr-form { display: flex; flex-direction: column; gap: 1.5rem; }

.msdr-success-banner {
    display: flex; align-items: center; gap: 0.75rem;
    padding: 1rem 1.25rem;
    background: var(--success-bg);
    border: 1px solid var(--success-border);
    border-radius: 12px;
    margin-bottom: 1.5rem;
}
.msdr-success-banner i { color: var(--teal); font-size: 1.1rem; flex-shrink: 0; }
.msdr-success-banner span { color: var(--offwhite); font-size: 0.875rem; }

.msdr-info-box {
    display: flex; align-items: flex-start; gap: 0.75rem;
    padding: 1.25rem;
    background: var(--glass);
    border: 1px solid var(--border);
    border-radius: 12px;
    margin-bottom: 1.5rem;
}
.msdr-info-box i { color: var(--teal); font-size: 1rem; flex-shrink: 0; margin-top: 2px; }
.msdr-info-box p { color: var(--muted); font-size: 0.875rem; line-height: 1.65; }

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

.msdr-divider {
    display: flex; align-items: center; gap: 1rem;
    margin: 0.5rem 0;
}
.msdr-divider::before, .msdr-divider::after {
    content: ''; flex: 1; height: 1px; background: var(--border);
}
.msdr-divider span { font-size: 0.78rem; color: var(--muted); }

.msdr-logout-btn {
    display: flex; align-items: center; justify-content: center; gap: 0.6rem;
    width: 100%; padding: 0.875rem;
    background: transparent; color: var(--muted);
    font-size: 0.9rem; font-weight: 500; font-family: var(--font-body);
    border: 1px solid var(--border); border-radius: 12px; cursor: pointer;
    transition: all 0.2s;
}
.msdr-logout-btn:hover {
    border-color: rgba(255,255,255,0.15);
    color: var(--offwhite);
    background: rgba(255,255,255,0.03);
}

.msdr-bottom-hint {
    display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    font-size: 0.75rem; color: var(--muted); text-align: center;
    margin-top: 1rem;
}
.msdr-bottom-hint i { color: var(--teal); }

@media (max-width: 900px) {
    .msdr-root { grid-template-columns: 1fr; }
    .msdr-panel { display: none; }
    .msdr-form-topbar { padding: 1rem 1.5rem; }
    .msdr-form-wrapper { padding: 2rem 1.5rem 3rem; }
}
</style>
@endsection
