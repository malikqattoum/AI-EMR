@extends('master')

@section('title', 'About Us — MedSuite AI')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">
@endpush

@section('content')
<div class="page-root">

    <!-- ═══════════ PAGE HERO ═══════════ -->
    <section class="page-hero">
        <div class="page-hero-bg">
            <div class="page-orb page-orb-1"></div>
            <div class="page-orb page-orb-2"></div>
            <div class="page-grid-overlay"></div>
        </div>
        <div class="page-hero-inner">
            <div class="page-eyebrow">
                <span class="page-eyebrow-dot"></span>
                About MedSuite AI
            </div>
            <h1 class="page-hero-title">
                Built by clinicians,<br>
                <em>for the future</em><br>
                <span class="page-title-accent">of healthcare.</span>
            </h1>
            <p class="page-hero-sub">
                We believe technology should amplify the human side of medicine — not replace it. MedSuite AI is the platform that makes that vision real.
            </p>
            <nav class="page-breadcrumb" aria-label="Breadcrumb">
                <a href="/">Home</a>
                <i class="bi bi-chevron-right"></i>
                <span>About Us</span>
            </nav>
        </div>
    </section>

    <!-- ═══════════ FEATURES SECTION ═══════════ -->
    <section class="page-section page-features-section">
        <div class="page-container">
            <div class="page-section-header">
                <div class="page-eyebrow page-eyebrow-light">Core Capabilities</div>
                <h2 class="page-section-title">Everything your<br><em>practice needs</em></h2>
                <p class="page-section-sub">Discover what makes MedSuite AI the platform of choice for modern healthcare professionals.</p>
            </div>

            <div class="page-features-grid">
                @foreach($features as $feature)
                <div class="page-feat-card">
                    <div class="page-feat-icon"><i class="{{ $feature['icon'] }}"></i></div>
                    <h3>{{ $feature['title'] }}</h3>
                    <p>{{ $feature['description'] }}</p>
                    <div class="page-feat-glow"></div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ═══════════ COUNTERS SECTION ═══════════ -->
    <section class="page-section page-counters-section">
        <div class="page-container">
            <div class="page-counters-grid">
                <div class="page-counter-card">
                    <div class="page-counter-icon"><i class="bi bi-stethoscope"></i></div>
                    <div class="page-counter-value">15,000+</div>
                    <div class="page-counter-label">Consultations Completed</div>
                </div>
                <div class="page-counter-card">
                    <div class="page-counter-icon"><i class="bi bi-people-fill"></i></div>
                    <div class="page-counter-value">1,200+</div>
                    <div class="page-counter-label">Healthcare Professionals</div>
                </div>
                <div class="page-counter-card">
                    <div class="page-counter-icon"><i class="bi bi-calendar-check-fill"></i></div>
                    <div class="page-counter-value">75,000+</div>
                    <div class="page-counter-label">Patient Appointments</div>
                </div>
                <div class="page-counter-card">
                    <div class="page-counter-icon"><i class="bi bi-star-fill"></i></div>
                    <div class="page-counter-value">4.8<span class="page-counter-star">★</span></div>
                    <div class="page-counter-label">Patient Satisfaction</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════ WHAT WE DO SECTION ═══════════ -->
    <section class="page-section page-what-section">
        <div class="page-container">
            <div class="page-what-grid">

                <!-- Left: Description + Features -->
                <div class="page-what-left">
                    <div class="page-eyebrow page-eyebrow-light">Who We Are</div>
                    <h2 class="page-section-title">{{ $whatWeDoTitle }}</h2>
                    <p class="page-what-desc">{{ $whatWeDoDescription }}</p>

                    <div class="page-what-features">
                        @foreach($whatWeDoFeatures as $feature)
                        <div class="page-what-feature">
                            <div class="page-what-feature-icon"><i class="{{ $feature['icon'] }}"></i></div>
                            <span>{{ $feature['description'] }}</span>
                        </div>
                        @endforeach
                    </div>

                    <a href="{{ route('register.doctor') }}" class="page-btn-primary">
                        <span>Get Started Today</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <!-- Right: How It Works + Core Principles -->
                <div class="page-what-right">

                    <!-- How It Works -->
                    <div class="page-card">
                        <div class="page-card-header">
                            <i class="bi bi-clipboard-check-fill"></i>
                            <h4>How It Works</h4>
                        </div>
                        <div class="page-steps">
                            <div class="page-step">
                                <div class="page-step-circle"><i class="bi bi-person-plus-fill"></i></div>
                                <div class="page-step-content">
                                    <strong>Register</strong>
                                    <span>Create your account</span>
                                </div>
                            </div>
                            <div class="page-step-line"></div>
                            <div class="page-step">
                                <div class="page-step-circle"><i class="bi bi-gear-fill"></i></div>
                                <div class="page-step-content">
                                    <strong>Configure</strong>
                                    <span>Set up your profile</span>
                                </div>
                            </div>
                            <div class="page-step-line"></div>
                            <div class="page-step">
                                <div class="page-step-circle"><i class="bi bi-stethoscope"></i></div>
                                <div class="page-step-content">
                                    <strong>Patient Care</strong>
                                    <span>Start providing care</span>
                                </div>
                            </div>
                            <div class="page-step-line"></div>
                            <div class="page-step">
                                <div class="page-step-circle"><i class="bi bi-people-fill"></i></div>
                                <div class="page-step-content">
                                    <strong>Manage</strong>
                                    <span>Handle patients</span>
                                </div>
                            </div>
                            <div class="page-step-line"></div>
                            <div class="page-step">
                                <div class="page-step-circle"><i class="bi bi-graph-up-arrow"></i></div>
                                <div class="page-step-content">
                                    <strong>Grow</strong>
                                    <span>Expand your practice</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Core Principles -->
                    <div class="page-card">
                        <div class="page-card-header">
                            <i class="bi bi-heart-fill"></i>
                            <h4>Core Principles</h4>
                        </div>
                        <ul class="page-principles-list">
                            <li><i class="bi bi-check-circle-fill"></i> Evidence-based medical practice</li>
                            <li><i class="bi bi-check-circle-fill"></i> Comprehensive patient management</li>
                            <li><i class="bi bi-check-circle-fill"></i> HIPAA-compliant security standards</li>
                            <li><i class="bi bi-check-circle-fill"></i> Professional practice growth tools</li>
                            <li><i class="bi bi-check-circle-fill"></i> Multi-channel patient communication</li>
                            <li><i class="bi bi-check-circle-fill"></i> Automated workflow optimization</li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </section>

</div>

<style>
/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   PAGE TOKENS & BASE
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.page-root {
    --navy:        #060d1f;
    --navy-mid:    #0c1633;
    --navy-card:   #0f1c3a;
    --navy-input:  #0a1428;
    --teal:        #00d4aa;
    --teal-dim:    rgba(0,212,170,0.10);
    --teal-glow:   rgba(0,212,170,0.25);
    --white:       #ffffff;
    --offwhite:    #e8edf5;
    --muted:       rgba(232,237,245,0.45);
    --border:      rgba(255,255,255,0.07);
    --border-hi:   rgba(255,255,255,0.14);
    --glass:       rgba(255,255,255,0.035);
    --error:       #f87171;
    --font-display: 'Cormorant Garamond', Georgia, serif;
    --font-body:    'DM Sans', sans-serif;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { background: var(--navy) !important; color: var(--offwhite) !important; font-family: var(--font-body) !important; }
a { text-decoration: none; color: inherit; }

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   PAGE HERO
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.page-hero {
    position: relative;
    min-height: 60vh;
    display: flex;
    align-items: center;
    overflow: hidden;
    padding: 8rem 2rem 5rem;
}
.page-hero-bg {
    position: absolute; inset: 0; pointer-events: none;
}
.page-orb {
    position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.5;
}
.page-orb-1 {
    width: 600px; height: 600px;
    background: radial-gradient(circle, rgba(0,212,170,0.2) 0%, transparent 65%);
    top: -200px; left: -200px;
    animation: orbFloat 12s ease-in-out infinite;
}
.page-orb-2 {
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, transparent 65%);
    bottom: -100px; right: -100px;
    animation: orbFloat 15s ease-in-out infinite reverse;
}
.page-grid-overlay {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
    background-size: 50px 50px;
}
@keyframes orbFloat {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.08) translate(10px, -10px); }
}

.page-hero-inner {
    position: relative; z-index: 2;
    max-width: 700px; margin: 0 auto;
    text-align: center;
    animation: heroIn 0.8s ease-out both;
}
@keyframes heroIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: none; } }

.page-eyebrow {
    display: inline-flex; align-items: center; gap: 0.5rem;
    font-size: 0.78rem; font-weight: 500; letter-spacing: 0.1em;
    text-transform: uppercase; color: var(--teal); margin-bottom: 1.5rem;
}
.page-eyebrow-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: var(--teal); box-shadow: 0 0 8px var(--teal);
    animation: pulse 2s ease-in-out infinite;
}
@keyframes pulse { 0%,100%{opacity:1;}50%{opacity:0.4;} }
.page-eyebrow-light { color: var(--teal); }

.page-hero-title {
    font-family: var(--font-display);
    font-size: clamp(2.5rem, 5vw, 4rem); font-weight: 300; line-height: 1.05;
    color: var(--white); margin-bottom: 1.25rem;
}
.page-hero-title em { color: var(--teal); font-style: italic; }
.page-title-accent { color: var(--offwhite); font-style: normal; }

.page-hero-sub {
    font-size: 1.05rem; color: var(--muted); line-height: 1.75;
    margin-bottom: 2rem; max-width: 540px; margin-left: auto; margin-right: auto;
}

.page-breadcrumb {
    display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    font-size: 0.8125rem; color: var(--muted);
}
.page-breadcrumb a { color: var(--teal); transition: opacity 0.2s; }
.page-breadcrumb a:hover { opacity: 0.8; }
.page-breadcrumb i { font-size: 0.6rem; }

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   SECTIONS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.page-section {
    padding: 5rem 2rem;
}

.page-container {
    max-width: 1100px; margin: 0 auto;
}

.page-section-header {
    text-align: center; margin-bottom: 4rem;
}
.page-section-title {
    font-family: var(--font-display);
    font-size: clamp(2rem, 4vw, 3rem); font-weight: 300; line-height: 1.1;
    color: var(--white); margin-bottom: 1rem;
}
.page-section-title em { color: var(--teal); font-style: italic; }
.page-section-sub {
    font-size: 0.95rem; color: var(--muted); max-width: 500px;
    margin: 0 auto; line-height: 1.7;
}

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   FEATURES GRID
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.page-features-section {
    background: var(--navy-mid);
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
}
.page-features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}
.page-feat-card {
    position: relative;
    background: var(--navy-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 2rem;
    overflow: hidden;
    transition: border-color 0.3s, transform 0.3s;
}
.page-feat-card:hover {
    border-color: rgba(0,212,170,0.3);
    transform: translateY(-3px);
}
.page-feat-icon {
    width: 48px; height: 48px;
    background: var(--teal-dim);
    border: 1px solid rgba(0,212,170,0.2);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: var(--teal); font-size: 1.25rem;
    margin-bottom: 1.25rem;
}
.page-feat-card h3 {
    font-family: var(--font-display);
    font-size: 1.2rem; font-weight: 600; color: var(--white);
    margin-bottom: 0.6rem;
}
.page-feat-card p {
    font-size: 0.875rem; color: var(--muted); line-height: 1.65;
}
.page-feat-glow {
    position: absolute; bottom: -40px; right: -40px;
    width: 120px; height: 120px;
    background: radial-gradient(circle, rgba(0,212,170,0.08) 0%, transparent 70%);
    pointer-events: none;
}

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   COUNTERS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.page-counters-section {
    background: var(--navy);
}
.page-counters-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
}
.page-counter-card {
    text-align: center;
    background: var(--navy-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 2.5rem 1.5rem;
    transition: border-color 0.3s;
}
.page-counter-card:hover { border-color: rgba(0,212,170,0.3); }
.page-counter-icon {
    font-size: 1.75rem; color: var(--teal); margin-bottom: 1rem;
}
.page-counter-value {
    font-family: var(--font-display);
    font-size: 2.5rem; font-weight: 600; color: var(--white);
    margin-bottom: 0.25rem;
}
.page-counter-star { color: var(--teal); }
.page-counter-label {
    font-size: 0.8125rem; color: var(--muted);
}

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   WHAT WE DO
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.page-what-section {
    background: var(--navy-mid);
    border-top: 1px solid var(--border);
}
.page-what-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: start;
}
.page-what-left .page-section-title { text-align: left; font-size: clamp(1.8rem, 3vw, 2.5rem); }
.page-what-desc {
    font-size: 0.9rem; color: var(--muted); line-height: 1.75;
    margin-bottom: 2rem;
}
.page-what-features {
    display: flex; flex-direction: column; gap: 0.875rem;
    margin-bottom: 2.5rem;
}
.page-what-feature {
    display: flex; align-items: flex-start; gap: 0.75rem;
}
.page-what-feature-icon {
    width: 32px; height: 32px; flex-shrink: 0;
    background: var(--teal-dim);
    border: 1px solid rgba(0,212,170,0.2);
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    color: var(--teal); font-size: 0.875rem;
    margin-top: 2px;
}
.page-what-feature span {
    font-size: 0.875rem; color: var(--muted); line-height: 1.5;
}

.page-btn-primary {
    display: inline-flex; align-items: center; gap: 0.5rem;
    padding: 0.875rem 1.75rem;
    background: var(--teal); color: var(--navy);
    font-size: 0.9rem; font-weight: 600;
    border-radius: 50px;
    transition: all 0.25s;
    box-shadow: 0 0 24px rgba(0,212,170,0.25);
}
.page-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 40px rgba(0,212,170,0.4);
}
.page-btn-primary i { font-size: 0.8rem; }

/* Right column */
.page-what-right {
    display: flex; flex-direction: column; gap: 1.5rem;
}
.page-card {
    background: var(--navy-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 1.75rem;
}
.page-card-header {
    display: flex; align-items: center; gap: 0.6rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border);
}
.page-card-header i { color: var(--teal); font-size: 1rem; }
.page-card-header h4 {
    font-family: var(--font-display);
    font-size: 1rem; font-weight: 600; color: var(--white);
}

/* Steps */
.page-steps { display: flex; flex-direction: column; }
.page-step {
    display: flex; align-items: center; gap: 1rem;
}
.page-step-circle {
    width: 36px; height: 36px; flex-shrink: 0;
    background: var(--teal-dim);
    border: 1px solid rgba(0,212,170,0.25);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: var(--teal); font-size: 0.8rem;
}
.page-step-content {
    display: flex; flex-direction: column;
}
.page-step-content strong {
    font-size: 0.8125rem; font-weight: 500; color: var(--white);
}
.page-step-content span {
    font-size: 0.75rem; color: var(--muted);
}
.page-step-line {
    width: 2px; height: 20px;
    background: var(--border);
    margin: 0 0 0 1.1rem;
}

/* Principles list */
.page-principles-list {
    list-style: none;
    display: flex; flex-direction: column; gap: 0.75rem;
}
.page-principles-list li {
    display: flex; align-items: center; gap: 0.6rem;
    font-size: 0.8125rem; color: var(--muted);
}
.page-principles-list li i {
    color: var(--teal); font-size: 0.875rem; flex-shrink: 0;
}

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   RESPONSIVE
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
@media (max-width: 900px) {
    .page-what-grid { grid-template-columns: 1fr; gap: 3rem; }
    .page-what-left .page-section-title { text-align: center; }
    .page-what-left { text-align: center; }
    .page-what-features { align-items: center; }
    .page-btn-primary { margin: 0 auto; }
}
@media (max-width: 768px) {
    .page-counters-grid { grid-template-columns: repeat(2, 1fr); }
    .page-hero { padding: 7rem 1.5rem 4rem; min-height: auto; }
}
@media (max-width: 480px) {
    .page-counters-grid { grid-template-columns: 1fr 1fr; gap: 1rem; }
    .page-counter-card { padding: 1.5rem 1rem; }
    .page-counter-value { font-size: 2rem; }
}
</style>
@endsection
