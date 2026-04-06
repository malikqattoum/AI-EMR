@extends('master')

@section('title', 'MedSuite AI — Intelligent Healthcare Platform')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">
@endpush

@section('content')
<div class="ms-root">

    <!-- ═══════════ NAV ═══════════ -->
    <nav class="ms-nav">
        <div class="ms-nav-inner">
            <a href="/" class="ms-nav-brand">
                <span class="ms-brand-icon"><i class="bi bi-heart-pulse-fill"></i></span>
                <span class="ms-brand-text">MedSuite<em>AI</em></span>
            </a>
            <div class="ms-nav-links">
                <a href="#features">Features</a>
                <a href="#ai-section">Intelligence</a>
                <a href="#how">Process</a>
            </div>
            <div class="ms-nav-actions">
                <a href="{{ route('login') }}" class="ms-btn-ghost">Sign In</a>
                <a href="{{ route('register.doctor') }}" class="ms-btn-primary">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- ═══════════ HERO ═══════════ -->
    <section class="ms-hero">
        <div class="ms-hero-bg">
            <div class="ms-orb ms-orb-1"></div>
            <div class="ms-orb ms-orb-2"></div>
            <div class="ms-orb ms-orb-3"></div>
            <div class="ms-grid-overlay"></div>
        </div>

        <div class="ms-hero-inner">
            <div class="ms-hero-text">
                <div class="ms-eyebrow">
                    <span class="ms-eyebrow-dot"></span>
                    AI-Powered EMR Platform
                </div>
                <h1 class="ms-hero-title">
                    Medicine<br>
                    <em>reimagined</em><br>
                    <span class="ms-hero-title-accent">intelligently.</span>
                </h1>
                <p class="ms-hero-sub">
                    Complete patient management, voice transcription, and AI clinical decision support — unified in one elegant platform built for modern medical practices.
                </p>
                <div class="ms-hero-cta">
                    <a href="{{ route('register.doctor') }}" class="ms-btn-hero">
                        <span>Begin Free Trial</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="#features" class="ms-btn-text">
                        Explore features <i class="bi bi-chevron-down"></i>
                    </a>
                </div>
                <div class="ms-hero-trust">
                    <div class="ms-trust-item"><strong>500+</strong><span>Clinicians</span></div>
                    <div class="ms-trust-divider"></div>
                    <div class="ms-trust-item"><strong>25K+</strong><span>Patients Served</span></div>
                    <div class="ms-trust-divider"></div>
                    <div class="ms-trust-item"><strong>4.9★</strong><span>Rated</span></div>
                </div>
            </div>

            <div class="ms-hero-visual">
                <div class="ms-dashboard-card">
                    <div class="ms-dash-header">
                        <div class="ms-dash-dot red"></div>
                        <div class="ms-dash-dot amber"></div>
                        <div class="ms-dash-dot green"></div>
                        <span class="ms-dash-label">Patient Overview</span>
                    </div>
                    <div class="ms-dash-body">
                        <div class="ms-dash-avatar">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <div class="ms-dash-info">
                            <div class="ms-dash-name">Sarah Mitchell</div>
                            <div class="ms-dash-detail">Cardiology • Follow-up</div>
                        </div>
                        <div class="ms-dash-badge">Active</div>
                    </div>
                    <div class="ms-dash-vitals">
                        <div class="ms-vital"><span class="ms-vital-val">72</span><span class="ms-vital-key">BPM</span></div>
                        <div class="ms-vital"><span class="ms-vital-val">118/76</span><span class="ms-vital-key">BP</span></div>
                        <div class="ms-vital"><span class="ms-vital-val">98%</span><span class="ms-vital-key">SpO₂</span></div>
                    </div>
                    <div class="ms-ai-chip">
                        <i class="bi bi-robot"></i>
                        <span>AI Copilot: No drug interactions detected</span>
                    </div>
                </div>

                <div class="ms-float-card ms-float-1">
                    <i class="bi bi-mic-fill"></i>
                    <span>Voice note recorded</span>
                </div>
                <div class="ms-float-card ms-float-2">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Prescription sent</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════ FEATURES ═══════════ -->
    <section id="features" class="ms-section ms-features-section">
        <div class="ms-container">
            <div class="ms-section-header">
                <div class="ms-eyebrow ms-eyebrow-light">Core Capabilities</div>
                <h2 class="ms-section-title">Everything your<br><em>practice needs</em></h2>
                <p class="ms-section-sub">A complete suite of tools built around the way modern clinicians work — not legacy systems designed decades ago.</p>
            </div>

            <div class="ms-features-grid">
                <div class="ms-feat-card ms-feat-large">
                    <div class="ms-feat-icon"><i class="bi bi-people-fill"></i></div>
                    <h3>Patient Management</h3>
                    <p>Complete longitudinal health records, visit history, and smart patient timelines accessible in seconds.</p>
                    <div class="ms-feat-glow"></div>
                </div>
                <div class="ms-feat-card">
                    <div class="ms-feat-icon"><i class="bi bi-mic-fill"></i></div>
                    <h3>Voice Transcription</h3>
                    <p>Real-time AI-powered speech-to-text for clinical notes — hands-free documentation.</p>
                    <div class="ms-feat-glow"></div>
                </div>
                <div class="ms-feat-card">
                    <div class="ms-feat-icon"><i class="bi bi-calendar-check"></i></div>
                    <h3>Smart Scheduling</h3>
                    <p>Intelligent appointment booking with automated reminders and calendar sync.</p>
                    <div class="ms-feat-glow"></div>
                </div>
                <div class="ms-feat-card ms-feat-large ms-feat-large-right">
                    <div class="ms-feat-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
                    <h3>Digital Prescriptions</h3>
                    <p>Create, manage, and send prescriptions with drug interaction checking and patient history context baked in.</p>
                    <div class="ms-feat-glow"></div>
                </div>
                <div class="ms-feat-card">
                    <div class="ms-feat-icon"><i class="bi bi-receipt-cutoff"></i></div>
                    <h3>Billing & Invoicing</h3>
                    <p>Automated invoicing, payment tracking, and detailed financial reporting.</p>
                    <div class="ms-feat-glow"></div>
                </div>
                <div class="ms-feat-card">
                    <div class="ms-feat-icon"><i class="bi bi-graph-up-arrow"></i></div>
                    <h3>Analytics</h3>
                    <p>Practice performance dashboards with revenue, appointment, and patient outcome metrics.</p>
                    <div class="ms-feat-glow"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════ AI INTELLIGENCE ═══════════ -->
    <section id="ai-section" class="ms-section ms-ai-section">
        <div class="ms-container">
            <div class="ms-ai-inner">
                <div class="ms-ai-left">
                    <div class="ms-eyebrow">AI Intelligence</div>
                    <h2 class="ms-section-title">Clinical decisions,<br><em>augmented.</em></h2>
                    <p class="ms-section-sub">Our AI doesn't replace clinical judgment — it enhances it with evidence-based context right when you need it.</p>

                    <div class="ms-ai-list">
                        <div class="ms-ai-item">
                            <div class="ms-ai-item-icon"><i class="bi bi-robot"></i></div>
                            <div>
                                <h4>Medical Copilot</h4>
                                <p>Diagnosis suggestions and treatment recommendations anchored in patient data and clinical literature.</p>
                            </div>
                        </div>
                        <div class="ms-ai-item">
                            <div class="ms-ai-item-icon"><i class="bi bi-capsule"></i></div>
                            <div>
                                <h4>Prescription Intelligence</h4>
                                <p>AI-powered drug interaction checking and dosage recommendations based on patient history.</p>
                            </div>
                        </div>
                        <div class="ms-ai-item">
                            <div class="ms-ai-item-icon"><i class="bi bi-bar-chart-line-fill"></i></div>
                            <div>
                                <h4>Predictive Analytics</h4>
                                <p>Risk stratification and patient outcome modeling to support proactive care decisions.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ms-ai-right">
                    <div class="ms-ai-terminal">
                        <div class="ms-terminal-header">
                            <span>AI Copilot</span>
                            <div class="ms-terminal-status"><span class="ms-pulse"></span> Live</div>
                        </div>
                        <div class="ms-terminal-body">
                            <div class="ms-terminal-msg ms-msg-system">
                                <i class="bi bi-robot"></i>
                                <div>
                                    <strong>MedSuite AI</strong>
                                    <p>Analyzing patient history for James R., 54M — Hypertension, T2DM follow-up.</p>
                                </div>
                            </div>
                            <div class="ms-terminal-msg ms-msg-alert">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                <div>
                                    <strong>Interaction Alert</strong>
                                    <p>Metformin + current Lisinopril dosage — consider renal function review.</p>
                                </div>
                            </div>
                            <div class="ms-terminal-msg ms-msg-suggest">
                                <i class="bi bi-lightbulb-fill"></i>
                                <div>
                                    <strong>Suggestion</strong>
                                    <p>HbA1c last checked 8 months ago — recommend ordering today.</p>
                                </div>
                            </div>
                            <div class="ms-terminal-cursor">
                                <span>Awaiting your input</span><span class="ms-blink">_</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════ HOW IT WORKS ═══════════ -->
    <section id="how" class="ms-section ms-how-section">
        <div class="ms-container">
            <div class="ms-section-header">
                <div class="ms-eyebrow ms-eyebrow-light">Simple Onboarding</div>
                <h2 class="ms-section-title">Up and running<br><em>in minutes.</em></h2>
            </div>
            <div class="ms-steps">
                <div class="ms-step">
                    <div class="ms-step-num">01</div>
                    <div class="ms-step-content">
                        <i class="bi bi-person-plus-fill"></i>
                        <h4>Create Your Account</h4>
                        <p>Sign up and build your professional profile — specialty, credentials, and availability.</p>
                    </div>
                </div>
                <div class="ms-step-arrow"><i class="bi bi-arrow-right"></i></div>
                <div class="ms-step">
                    <div class="ms-step-num">02</div>
                    <div class="ms-step-content">
                        <i class="bi bi-sliders"></i>
                        <h4>Configure Your Practice</h4>
                        <p>Set appointment types, billing rates, scheduling preferences, and team permissions.</p>
                    </div>
                </div>
                <div class="ms-step-arrow"><i class="bi bi-arrow-right"></i></div>
                <div class="ms-step">
                    <div class="ms-step-num">03</div>
                    <div class="ms-step-content">
                        <i class="bi bi-rocket-takeoff-fill"></i>
                        <h4>Start Seeing Patients</h4>
                        <p>Accept bookings, document visits with AI assistance, and manage your practice effortlessly.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════ CTA ═══════════ -->
    <section class="ms-cta-section">
        <div class="ms-cta-bg">
            <div class="ms-orb ms-orb-cta-1"></div>
            <div class="ms-orb ms-orb-cta-2"></div>
        </div>
        <div class="ms-container">
            <div class="ms-cta-inner">
                <h2>Ready to transform<br><em>your practice?</em></h2>
                <p>Join hundreds of clinicians who've modernized their workflow with MedSuite AI. No contracts, cancel anytime.</p>
                <a href="{{ route('register.doctor') }}" class="ms-btn-hero">
                    <span>Start Free Trial</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- ═══════════ FOOTER ═══════════ -->
    <footer class="ms-footer">
        <div class="ms-container">
            <div class="ms-footer-inner">
                <div class="ms-footer-brand">
                    <span class="ms-brand-icon"><i class="bi bi-heart-pulse-fill"></i></span>
                    <span class="ms-brand-text">MedSuite<em>AI</em></span>
                    <p>Intelligent healthcare for modern practices.</p>
                </div>
                <div class="ms-footer-cols">
                    <div>
                        <h5>Product</h5>
                        <a href="#features">Features</a>
                        <a href="#ai-section">AI Tools</a>
                        <a href="#how">How It Works</a>
                    </div>
                    <div>
                        <h5>Company</h5>
                        <a href="#">About</a>
                        <a href="#">Careers</a>
                        <a href="{{ route('contact') }}">Contact</a>
                    </div>
                    <div>
                        <h5>Legal</h5>
                        <a href="#">Privacy Policy</a>
                        <a href="#">Terms of Service</a>
                        <a href="#">HIPAA Compliance</a>
                    </div>
                </div>
            </div>
            <div class="ms-footer-bottom">
                <p>&copy; {{ date('Y') }} MedSuite AI. All rights reserved.</p>
            </div>
        </div>
    </footer>
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
    --teal-dim:   rgba(0,212,170,0.12);
    --teal-glow:  rgba(0,212,170,0.35);
    --amber:      #f59e0b;
    --red:        #ef4444;
    --white:      #ffffff;
    --offwhite:   #e8edf5;
    --muted:      rgba(232,237,245,0.5);
    --border:     rgba(255,255,255,0.07);
    --glass:      rgba(255,255,255,0.04);
    --font-display: 'Cormorant Garamond', Georgia, serif;
    --font-body:    'DM Sans', sans-serif;
}

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   BASE
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { background: var(--navy); color: var(--offwhite); font-family: var(--font-body); font-size: 16px; line-height: 1.6; }
a { text-decoration: none; color: inherit; }
img { max-width: 100%; }
.ms-root { min-height: 100vh; overflow-x: hidden; }
.ms-container { max-width: 1200px; margin: 0 auto; padding: 0 2rem; }

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   NAV
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.ms-nav {
    position: fixed; top: 0; left: 0; right: 0; z-index: 100;
    padding: 1.25rem 2rem;
    background: rgba(6,13,31,0.8);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid var(--border);
}
.ms-nav-inner {
    max-width: 1200px; margin: 0 auto;
    display: flex; align-items: center; justify-content: space-between;
}
.ms-nav-brand {
    display: flex; align-items: center; gap: 0.6rem;
    font-family: var(--font-display); font-size: 1.4rem; font-weight: 600;
    color: var(--white);
}
.ms-nav-brand em { color: var(--teal); font-style: normal; }
.ms-brand-icon {
    width: 34px; height: 34px;
    background: var(--teal); border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    color: var(--navy); font-size: 0.9rem;
}
.ms-brand-text em { color: var(--teal); font-style: normal; }
.ms-nav-links { display: flex; gap: 2rem; }
.ms-nav-links a {
    font-size: 0.875rem; font-weight: 400; color: var(--muted);
    transition: color 0.2s;
}
.ms-nav-links a:hover { color: var(--white); }
.ms-nav-actions { display: flex; align-items: center; gap: 1rem; }
.ms-btn-ghost {
    font-size: 0.875rem; color: var(--offwhite); padding: 0.5rem 1.25rem;
    border: 1px solid var(--border); border-radius: 50px;
    transition: all 0.2s;
}
.ms-btn-ghost:hover { border-color: var(--teal); color: var(--teal); }
.ms-btn-primary {
    font-size: 0.875rem; font-weight: 500;
    padding: 0.5rem 1.25rem;
    background: var(--teal); color: var(--navy);
    border-radius: 50px; transition: all 0.2s;
}
.ms-btn-primary:hover { box-shadow: 0 0 20px var(--teal-glow); transform: translateY(-1px); }

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   HERO
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.ms-hero {
    position: relative; min-height: 100vh;
    display: flex; align-items: center;
    padding: 8rem 2rem 4rem;
    overflow: hidden;
}
.ms-hero-bg {
    position: absolute; inset: 0; pointer-events: none;
}
.ms-orb {
    position: absolute; border-radius: 50%;
    filter: blur(80px); opacity: 0.5;
}
.ms-orb-1 {
    width: 600px; height: 600px;
    background: radial-gradient(circle, rgba(0,212,170,0.25) 0%, transparent 70%);
    top: -200px; right: -100px;
    animation: orbFloat 8s ease-in-out infinite;
}
.ms-orb-2 {
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(99,102,241,0.2) 0%, transparent 70%);
    bottom: 0; left: -100px;
    animation: orbFloat 10s ease-in-out infinite reverse;
}
.ms-orb-3 {
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(0,212,170,0.15) 0%, transparent 70%);
    top: 40%; left: 40%;
    animation: orbFloat 12s ease-in-out infinite;
}
.ms-grid-overlay {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
    background-size: 60px 60px;
}
@keyframes orbFloat {
    0%, 100% { transform: translateY(0) scale(1); }
    50% { transform: translateY(-30px) scale(1.05); }
}

.ms-hero-inner {
    position: relative; z-index: 2;
    max-width: 1200px; margin: 0 auto; width: 100%;
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 4rem; align-items: center;
}

/* Eyebrow */
.ms-eyebrow {
    display: inline-flex; align-items: center; gap: 0.5rem;
    font-size: 0.75rem; font-weight: 500; letter-spacing: 0.15em;
    text-transform: uppercase; color: var(--teal);
    margin-bottom: 1.5rem;
}
.ms-eyebrow-dot {
    width: 6px; height: 6px;
    background: var(--teal); border-radius: 50%;
    box-shadow: 0 0 8px var(--teal);
}
.ms-eyebrow-light { color: var(--teal); }

/* Hero title */
.ms-hero-title {
    font-family: var(--font-display);
    font-size: clamp(3.5rem, 6vw, 5.5rem);
    font-weight: 300; line-height: 1.05;
    color: var(--teal); margin-bottom: 1.5rem;
    letter-spacing: -0.02em;
}
.ms-hero-title em { color: var(--teal); font-style: italic; }
.ms-hero-title-accent {
    color: var(--white);
    -webkit-text-stroke: 1px rgba(255,255,255,0.3);
    color: transparent;
}

.ms-hero-sub {
    font-size: 1.0625rem; color: var(--muted); line-height: 1.75;
    max-width: 480px; margin-bottom: 2.5rem;
}

.ms-hero-cta { display: flex; align-items: center; gap: 1.5rem; margin-bottom: 3rem; }
.ms-btn-hero {
    display: inline-flex; align-items: center; gap: 0.6rem;
    padding: 0.875rem 2rem;
    background: var(--teal); color: var(--navy);
    font-weight: 600; font-size: 0.9375rem;
    border-radius: 50px;
    transition: all 0.25s;
    box-shadow: 0 0 30px rgba(0,212,170,0.3);
}
.ms-btn-hero:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 50px rgba(0,212,170,0.5);
}
.ms-btn-text {
    display: inline-flex; align-items: center; gap: 0.4rem;
    font-size: 0.875rem; color: var(--muted);
    transition: color 0.2s;
}
.ms-btn-text:hover { color: var(--white); }

.ms-hero-trust { display: flex; align-items: center; gap: 1.5rem; }
.ms-trust-item { display: flex; flex-direction: column; }
.ms-trust-item strong { font-size: 1.25rem; font-weight: 600; color: var(--white); }
.ms-trust-item span { font-size: 0.75rem; color: var(--muted); }
.ms-trust-divider { width: 1px; height: 30px; background: var(--border); }

/* Hero Visual */
.ms-hero-visual { position: relative; animation: heroIn 1s ease-out 0.3s both; }
@keyframes heroIn { from { opacity: 0; transform: translateX(30px); } to { opacity: 1; transform: none; } }

.ms-dashboard-card {
    background: var(--navy-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 40px 80px rgba(0,0,0,0.4), 0 0 0 1px rgba(0,212,170,0.08);
    backdrop-filter: blur(20px);
}
.ms-dash-header {
    display: flex; align-items: center; gap: 0.4rem;
    margin-bottom: 1.5rem;
}
.ms-dash-dot {
    width: 10px; height: 10px; border-radius: 50%;
}
.ms-dash-dot.red { background: #ef4444; }
.ms-dash-dot.amber { background: #f59e0b; }
.ms-dash-dot.green { background: #22c55e; }
.ms-dash-label {
    margin-left: auto; font-size: 0.75rem; color: var(--muted);
}
.ms-dash-body {
    display: flex; align-items: center; gap: 0.75rem;
    padding: 0.75rem;
    background: var(--glass);
    border: 1px solid var(--border);
    border-radius: 12px; margin-bottom: 1rem;
}
.ms-dash-avatar { font-size: 2rem; color: var(--teal); }
.ms-dash-name { font-weight: 500; font-size: 0.9375rem; color: var(--white); }
.ms-dash-detail { font-size: 0.75rem; color: var(--muted); }
.ms-dash-badge {
    margin-left: auto;
    font-size: 0.7rem; font-weight: 500;
    padding: 0.2rem 0.6rem;
    background: rgba(34,197,94,0.15);
    color: #22c55e; border-radius: 50px;
    border: 1px solid rgba(34,197,94,0.3);
}
.ms-dash-vitals {
    display: grid; grid-template-columns: repeat(3,1fr);
    gap: 0.5rem; margin-bottom: 1rem;
}
.ms-vital {
    background: var(--glass); border: 1px solid var(--border);
    border-radius: 10px; padding: 0.75rem;
    display: flex; flex-direction: column; align-items: center; gap: 0.2rem;
}
.ms-vital-val { font-size: 1.1rem; font-weight: 600; color: var(--white); }
.ms-vital-key { font-size: 0.65rem; color: var(--muted); letter-spacing: 0.05em; }
.ms-ai-chip {
    display: flex; align-items: center; gap: 0.5rem;
    padding: 0.6rem 0.75rem;
    background: rgba(0,212,170,0.08);
    border: 1px solid rgba(0,212,170,0.2);
    border-radius: 8px;
    font-size: 0.78rem; color: var(--teal);
}

.ms-float-card {
    position: absolute;
    display: flex; align-items: center; gap: 0.5rem;
    padding: 0.6rem 1rem;
    background: var(--navy-card);
    border: 1px solid var(--border);
    border-radius: 50px;
    font-size: 0.78rem; color: var(--offwhite);
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    white-space: nowrap;
}
.ms-float-1 { bottom: -20px; left: -40px; animation: floatAnim 4s ease-in-out infinite; }
.ms-float-1 i { color: var(--teal); }
.ms-float-2 { top: 30px; right: -40px; animation: floatAnim 4s ease-in-out infinite 2s; }
.ms-float-2 i { color: #22c55e; }
@keyframes floatAnim {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-8px); }
}

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   SECTIONS COMMON
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.ms-section { padding: 8rem 2rem; }
.ms-section-header { text-align: center; margin-bottom: 5rem; }
.ms-section-title {
    font-family: var(--font-display);
    font-size: clamp(2.5rem, 4vw, 4rem);
    font-weight: 300; line-height: 1.1;
    color: var(--white); margin-bottom: 1.25rem;
}
.ms-section-title em { color: var(--teal); font-style: italic; }
.ms-section-sub { font-size: 1rem; color: var(--muted); max-width: 520px; margin: 0 auto; line-height: 1.75; }

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   FEATURES GRID
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.ms-features-section { background: var(--navy-mid); }
.ms-features-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-template-rows: auto auto;
    gap: 1.5rem;
}
.ms-feat-card {
    position: relative; overflow: hidden;
    background: var(--glass);
    border: 1px solid var(--border);
    border-radius: 18px; padding: 2rem;
    transition: all 0.3s;
}
.ms-feat-card:hover { border-color: rgba(0,212,170,0.3); transform: translateY(-3px); }
.ms-feat-card:hover .ms-feat-glow { opacity: 1; }
.ms-feat-large { grid-column: span 2; }
.ms-feat-large-right { grid-column: span 2; }
.ms-feat-icon {
    width: 46px; height: 46px;
    background: var(--teal-dim);
    border: 1px solid rgba(0,212,170,0.2);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: var(--teal); font-size: 1.1rem;
    margin-bottom: 1.25rem;
}
.ms-feat-card h3 {
    font-size: 1.1rem; font-weight: 500; color: var(--white); margin-bottom: 0.5rem;
}
.ms-feat-card p { font-size: 0.875rem; color: var(--muted); line-height: 1.7; }
.ms-feat-glow {
    position: absolute; bottom: -40px; right: -40px;
    width: 120px; height: 120px;
    background: radial-gradient(circle, var(--teal-glow) 0%, transparent 70%);
    opacity: 0; transition: opacity 0.3s; pointer-events: none;
}

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   AI SECTION
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.ms-ai-section { background: var(--navy); }
.ms-ai-inner {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 5rem; align-items: center;
}
.ms-ai-list { display: flex; flex-direction: column; gap: 2rem; margin-top: 2.5rem; }
.ms-ai-item { display: flex; gap: 1rem; align-items: flex-start; }
.ms-ai-item-icon {
    width: 42px; height: 42px; flex-shrink: 0;
    background: var(--teal-dim);
    border: 1px solid rgba(0,212,170,0.2);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    color: var(--teal);
}
.ms-ai-item h4 { font-size: 0.9375rem; font-weight: 500; color: var(--white); margin-bottom: 0.3rem; }
.ms-ai-item p { font-size: 0.84375rem; color: var(--muted); line-height: 1.65; }

/* AI Terminal */
.ms-ai-terminal {
    background: var(--navy-card);
    border: 1px solid var(--border);
    border-radius: 18px; overflow: hidden;
    box-shadow: 0 40px 80px rgba(0,0,0,0.4), 0 0 0 1px rgba(0,212,170,0.06);
}
.ms-terminal-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--border);
    font-size: 0.8125rem; font-weight: 500; color: var(--offwhite);
}
.ms-terminal-status {
    display: flex; align-items: center; gap: 0.4rem;
    font-size: 0.72rem; color: var(--teal);
}
.ms-pulse {
    width: 6px; height: 6px; background: var(--teal); border-radius: 50%;
    animation: pulse 2s ease-in-out infinite;
}
@keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }
.ms-terminal-body { padding: 1.25rem; display: flex; flex-direction: column; gap: 1rem; }
.ms-terminal-msg {
    display: flex; gap: 0.75rem; align-items: flex-start;
    padding: 0.875rem; border-radius: 10px;
    font-size: 0.8125rem;
}
.ms-msg-system { background: rgba(255,255,255,0.04); border: 1px solid var(--border); }
.ms-msg-system i { color: var(--teal); font-size: 1rem; margin-top: 1px; }
.ms-msg-alert { background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.2); }
.ms-msg-alert i { color: var(--amber); font-size: 1rem; margin-top: 1px; }
.ms-msg-suggest { background: rgba(0,212,170,0.06); border: 1px solid rgba(0,212,170,0.15); }
.ms-msg-suggest i { color: var(--teal); font-size: 1rem; margin-top: 1px; }
.ms-terminal-msg strong { display: block; color: var(--white); margin-bottom: 0.25rem; font-weight: 500; }
.ms-terminal-msg p { color: var(--muted); margin: 0; line-height: 1.5; }
.ms-terminal-cursor {
    font-size: 0.78rem; color: var(--muted);
    padding: 0 0.25rem;
    display: flex; align-items: center; gap: 0.25rem;
}
.ms-blink { animation: blink 1s step-end infinite; color: var(--teal); }
@keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0; } }

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   HOW IT WORKS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.ms-how-section { background: var(--navy-mid); }
.ms-steps {
    display: flex; align-items: center; gap: 1.5rem;
    justify-content: center;
}
.ms-step {
    flex: 1; max-width: 300px;
    background: var(--glass);
    border: 1px solid var(--border);
    border-radius: 18px; padding: 2.5rem 2rem;
    position: relative; text-align: center;
    transition: all 0.3s;
}
.ms-step:hover { border-color: rgba(0,212,170,0.3); transform: translateY(-4px); }
.ms-step-num {
    position: absolute; top: -1px; left: 50%;
    transform: translateX(-50%);
    font-family: var(--font-display); font-size: 3.5rem; font-weight: 300;
    color: rgba(0,212,170,0.08); line-height: 1;
    pointer-events: none;
}
.ms-step-content i { font-size: 2rem; color: var(--teal); display: block; margin-bottom: 1rem; padding-top: 1.5rem; }
.ms-step-content h4 { font-size: 1rem; font-weight: 500; color: var(--white); margin-bottom: 0.6rem; }
.ms-step-content p { font-size: 0.84375rem; color: var(--muted); line-height: 1.7; }
.ms-step-arrow { color: var(--border); font-size: 1.5rem; flex-shrink: 0; }

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   CTA
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.ms-cta-section {
    position: relative; padding: 8rem 2rem; overflow: hidden;
    background: var(--navy);
}
.ms-cta-bg { position: absolute; inset: 0; pointer-events: none; }
.ms-orb-cta-1 {
    position: absolute; width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(0,212,170,0.2) 0%, transparent 70%);
    top: -200px; left: 50%; transform: translateX(-50%);
    filter: blur(80px);
}
.ms-orb-cta-2 {
    position: absolute; width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, transparent 70%);
    bottom: -100px; right: 10%;
    filter: blur(60px);
}
.ms-cta-inner {
    position: relative; z-index: 2;
    max-width: 600px; margin: 0 auto; text-align: center;
}
.ms-cta-inner h2 {
    font-family: var(--font-display);
    font-size: clamp(2.5rem, 4vw, 4rem);
    font-weight: 300; line-height: 1.1;
    color: var(--white); margin-bottom: 1.25rem;
}
.ms-cta-inner h2 em { color: var(--teal); font-style: italic; }
.ms-cta-inner p { font-size: 1rem; color: var(--muted); margin-bottom: 2.5rem; line-height: 1.7; }

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   FOOTER
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.ms-footer {
    background: var(--navy-mid);
    border-top: 1px solid var(--border);
    padding: 4rem 2rem 2rem;
}
.ms-footer-inner {
    display: grid; grid-template-columns: 1fr 2fr;
    gap: 4rem; max-width: 1200px; margin: 0 auto;
    padding-bottom: 3rem;
    border-bottom: 1px solid var(--border);
}
.ms-footer-brand {
    display: flex; flex-direction: column; gap: 0.5rem;
}
.ms-footer-brand .ms-brand-icon { background: transparent; border: 1px solid var(--border); color: var(--teal); }
.ms-footer-brand .ms-brand-text { font-family: var(--font-display); font-size: 1.2rem; font-weight: 600; color: var(--white); }
.ms-footer-brand p { font-size: 0.84375rem; color: var(--muted); margin-top: 0.5rem; }
.ms-footer-cols {
    display: grid; grid-template-columns: repeat(3,1fr); gap: 2rem;
}
.ms-footer-cols h5 { font-size: 0.8125rem; font-weight: 500; color: var(--white); margin-bottom: 1rem; letter-spacing: 0.05em; }
.ms-footer-cols a {
    display: block; font-size: 0.84375rem; color: var(--muted);
    margin-bottom: 0.6rem; transition: color 0.2s;
}
.ms-footer-cols a:hover { color: var(--teal); }
.ms-footer-bottom {
    max-width: 1200px; margin: 0 auto; padding-top: 2rem;
    text-align: center;
}
.ms-footer-bottom p { font-size: 0.8125rem; color: var(--muted); }

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   HERO ANIMATION
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.ms-hero-text {
    animation: heroTextIn 0.9s ease-out both;
}
@keyframes heroTextIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: none; }
}

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   RESPONSIVE
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
@media (max-width: 1024px) {
    .ms-hero-inner { grid-template-columns: 1fr; }
    .ms-hero-visual { display: none; }
    .ms-ai-inner { grid-template-columns: 1fr; }
    .ms-features-grid { grid-template-columns: 1fr 1fr; }
    .ms-feat-large, .ms-feat-large-right { grid-column: span 2; }
    .ms-footer-inner { grid-template-columns: 1fr; gap: 2rem; }
}
@media (max-width: 768px) {
    .ms-nav-links { display: none; }
    .ms-features-grid { grid-template-columns: 1fr; }
    .ms-feat-large, .ms-feat-large-right { grid-column: span 1; }
    .ms-steps { flex-direction: column; }
    .ms-step-arrow { transform: rotate(90deg); }
    .ms-footer-cols { grid-template-columns: 1fr 1fr; }
}
</style>
@endsection