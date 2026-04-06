@extends('master')

@section('title', 'MedSuite AI - Modern EMR System for Healthcare Practices')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet">
<style>
:root {
    --navy: #060d1f;
    --navy-mid: #0a1628;
    --navy-card: #0f1c3a;
    --teal: #00d4aa;
    --teal-dark: #00a88a;
    --teal-dim: rgba(0,212,170,0.1);
    --teal-glow: rgba(0,212,170,0.25);
    --offwhite: #e8edf5;
    --muted: rgba(232,237,231,0.55);
    --border: rgba(255,255,255,0.07);
    --glass-bg: rgba(10,22,40,0.6);
    --card-bg: rgba(10,22,40,0.85);
    --card-border: rgba(0,212,170,0.15);
    --font-display: 'Cormorant Garamond', Georgia, serif;
    --font-body: 'DM Sans', sans-serif;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { background: var(--navy) !important; color: var(--offwhite) !important; font-family: var(--font-body) !important; }
a { text-decoration: none; color: inherit; }

.ms-main { background: var(--navy) !important; }
#main-content { background: var(--navy) !important; }

/* ── Hero ── */
.hero-section {
    position: relative;
    min-height: 100vh;
    background: var(--navy);
    display: flex;
    align-items: center;
    overflow: hidden;
}

.hero-orb {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
}
.hero-orb-1 {
    width: 700px; height: 700px;
    background: radial-gradient(circle, rgba(0,212,170,0.12) 0%, transparent 65%);
    top: -250px; right: -200px;
    animation: orbFloat 12s ease-in-out infinite;
}
.hero-orb-2 {
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(0,150,200,0.08) 0%, transparent 65%);
    bottom: -200px; left: -100px;
    animation: orbFloat 16s ease-in-out infinite reverse;
}
.hero-orb-3 {
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(0,212,170,0.06) 0%, transparent 65%);
    top: 40%; left: 40%;
    animation: orbFloat 20s ease-in-out infinite;
}
@keyframes orbFloat {
    0%, 100% { transform: scale(1) translate(0, 0); }
    33% { transform: scale(1.05) translate(15px, -20px); }
    66% { transform: scale(0.97) translate(-10px, 15px); }
}

.hero-grid-overlay {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(0,212,170,0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(0,212,170,0.03) 1px, transparent 1px);
    background-size: 60px 60px;
    pointer-events: none;
}

.hero-content {
    position: relative; z-index: 2;
    padding: 6rem 0;
}

.hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(0,212,170,0.1);
    border: 1px solid rgba(0,212,170,0.2);
    color: var(--teal);
    padding: 0.4rem 1rem;
    border-radius: 50px;
    font-size: 0.78rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 1.5rem;
}

.hero-headline {
    font-family: var(--font-display);
    font-size: clamp(2.8rem, 5vw, 4.5rem);
    font-weight: 300;
    line-height: 1.05;
    color: var(--offwhite);
    margin-bottom: 1.5rem;
}
.hero-headline em { color: var(--teal); font-style: italic; }
.hero-headline .accent { color: var(--teal); font-weight: 600; }

.hero-sub {
    font-size: 1.05rem;
    color: var(--muted);
    line-height: 1.8;
    max-width: 520px;
    margin-bottom: 2.5rem;
}

.hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 2rem;
}

.btn-hero-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    background: var(--teal);
    color: var(--navy);
    padding: 0.9rem 2rem;
    border-radius: 50px;
    font-weight: 700;
    font-size: 0.95rem;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
    box-shadow: 0 0 40px rgba(0,212,170,0.25);
}
.btn-hero-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(0,212,170,0.4);
    color: var(--navy);
}

.btn-hero-outline {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    background: rgba(255,255,255,0.04);
    border: 1.5px solid rgba(255,255,255,0.15);
    color: var(--offwhite);
    padding: 0.9rem 2rem;
    border-radius: 50px;
    font-weight: 500;
    font-size: 0.95rem;
    transition: all 0.3s;
    backdrop-filter: blur(10px);
}
.btn-hero-outline:hover {
    background: rgba(255,255,255,0.08);
    border-color: rgba(255,255,255,0.25);
    transform: translateY(-3px);
    color: var(--offwhite);
}

.hero-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 1.25rem;
}
.hero-badge {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.8rem;
    color: var(--muted);
}
.hero-badge i { color: var(--teal); font-size: 0.75rem; }

.hero-visual {
    display: flex;
    align-items: center;
    justify-content: center;
}

.hero-icon-wrap {
    width: 300px;
    height: 300px;
    border-radius: 50%;
    background: var(--glass-bg);
    border: 1px solid var(--card-border);
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(20px);
    box-shadow: 0 0 80px rgba(0,212,170,0.1), inset 0 0 60px rgba(0,212,170,0.03);
    position: relative;
}
.hero-icon-wrap::before {
    content: '';
    position: absolute;
    inset: -20px;
    border-radius: 50%;
    border: 1px solid rgba(0,212,170,0.06);
}
.hero-icon-wrap i { font-size: 80px; color: var(--teal); opacity: 0.8; }

/* ── Features ── */
.features-section {
    padding: 6rem 0;
    background: var(--navy);
    position: relative;
}
.features-section::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(0,212,170,0.2), transparent);
}

.section-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--teal);
    margin-bottom: 0.75rem;
}
.section-eyebrow::before, .section-eyebrow::after {
    content: '';
    width: 24px; height: 1px;
    background: rgba(0,212,170,0.4);
}

.section-title {
    font-family: var(--font-display);
    font-size: clamp(2rem, 3.5vw, 3rem);
    font-weight: 300;
    color: var(--offwhite);
    margin-bottom: 1rem;
    line-height: 1.1;
}
.section-title em { color: var(--teal); font-style: italic; }

.section-sub {
    font-size: 0.95rem;
    color: var(--muted);
    max-width: 500px;
    margin: 0 auto 3.5rem;
}

.feature-card {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: 20px;
    padding: 2rem 1.75rem;
    text-align: center;
    backdrop-filter: blur(12px);
    transition: all 0.3s;
    height: 100%;
}
.feature-card:hover {
    border-color: rgba(0,212,170,0.35);
    transform: translateY(-6px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
}

.feature-icon {
    width: 64px; height: 64px;
    border-radius: 16px;
    background: rgba(0,212,170,0.1);
    border: 1px solid rgba(0,212,170,0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.25rem;
    font-size: 1.5rem;
    color: var(--teal);
}

.feature-card h4 {
    font-family: var(--font-display);
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--offwhite);
    margin-bottom: 0.6rem;
}
.feature-card p {
    font-size: 0.85rem;
    color: var(--muted);
    line-height: 1.7;
}

/* ── How It Works ── */
.how-section {
    padding: 6rem 0;
    background: var(--navy-mid);
    position: relative;
}

.step-card {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: 20px;
    padding: 2.5rem 2rem;
    text-align: center;
    position: relative;
    backdrop-filter: blur(12px);
    transition: all 0.3s;
    height: 100%;
}
.step-card:hover {
    border-color: rgba(0,212,170,0.3);
    transform: translateY(-5px);
}

.step-num {
    position: absolute;
    top: -18px;
    left: 50%;
    transform: translateX(-50%);
    width: 44px; height: 44px;
    border-radius: 50%;
    background: var(--teal);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1rem;
    color: var(--navy);
    box-shadow: 0 0 20px rgba(0,212,170,0.3);
}

.step-card .step-icon {
    font-size: 2.5rem;
    color: var(--teal);
    margin: 1rem auto 1.25rem;
    display: block;
}
.step-card h4 {
    font-family: var(--font-display);
    font-size: 1.15rem;
    font-weight: 600;
    color: var(--offwhite);
    margin-bottom: 0.6rem;
}
.step-card p {
    font-size: 0.85rem;
    color: var(--muted);
    line-height: 1.7;
}

/* ── Pricing ── */
.pricing-section {
    padding: 6rem 0;
    background: var(--navy);
    position: relative;
}
.pricing-section::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(0,212,170,0.2), transparent);
}

.pricing-card {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: 24px;
    padding: 2.5rem;
    text-align: center;
    backdrop-filter: blur(12px);
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
}
.pricing-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--teal), rgba(0,212,170,0.3));
}
.pricing-card:hover {
    border-color: rgba(0,212,170,0.4);
    transform: translateY(-8px);
    box-shadow: 0 25px 50px rgba(0,0,0,0.4);
}

.pricing-card h3 {
    font-family: var(--font-display);
    font-size: 1.4rem;
    font-weight: 600;
    color: var(--offwhite);
    margin-bottom: 0.5rem;
}
.pricing-card .pricing-desc {
    font-size: 0.85rem;
    color: var(--muted);
    margin-bottom: 1.5rem;
}

.price-block {
    margin-bottom: 1.5rem;
}
.price-amount {
    font-family: var(--font-display);
    font-size: 3.5rem;
    font-weight: 700;
    color: var(--teal);
    line-height: 1;
}
.price-period {
    font-size: 0.85rem;
    color: var(--muted);
}
.price-yearly {
    font-size: 0.78rem;
    color: var(--muted);
    margin-top: 0.25rem;
}

.feature-list {
    list-style: none;
    text-align: left;
    margin-bottom: 2rem;
}
.feature-list li {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.6rem 0;
    border-bottom: 1px solid var(--border);
    font-size: 0.875rem;
    color: var(--muted);
}
.feature-list li:last-child { border-bottom: none; }
.feature-list li i { color: var(--teal); font-size: 0.8rem; flex-shrink: 0; }

.btn-pricing {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    width: 100%;
    padding: 0.9rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.95rem;
    text-decoration: none;
    transition: all 0.25s;
}
.btn-pricing-primary {
    background: var(--teal);
    color: var(--navy);
}
.btn-pricing-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,212,170,0.3);
    color: var(--navy);
}

.pricing-trust {
    margin-top: 1rem;
    font-size: 0.75rem;
    color: var(--muted);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
}
.pricing-trust i { color: var(--teal); }

/* ── CTA ── */
.cta-section {
    padding: 6rem 0;
    background: var(--navy-mid);
    position: relative;
    overflow: hidden;
}
.cta-section::before {
    content: '';
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    width: 800px; height: 400px;
    background: radial-gradient(ellipse, rgba(0,212,170,0.08) 0%, transparent 70%);
    pointer-events: none;
}

.cta-title {
    font-family: var(--font-display);
    font-size: clamp(2rem, 4vw, 3.2rem);
    font-weight: 300;
    color: var(--offwhite);
    margin-bottom: 1rem;
    line-height: 1.1;
}
.cta-title em { color: var(--teal); font-style: italic; }
.cta-sub {
    font-size: 1rem;
    color: var(--muted);
    margin-bottom: 2.5rem;
}

.cta-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
}

.cta-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 1.25rem;
    justify-content: center;
    margin-top: 2rem;
}
.cta-badge {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.8rem;
    color: var(--muted);
}
.cta-badge i { color: var(--teal); font-size: 0.75rem; }

/* ── Responsive ── */
@media (max-width: 991px) {
    .hero-visual { margin-top: 3rem; }
    .hero-icon-wrap { width: 220px; height: 220px; }
    .hero-icon-wrap i { font-size: 60px; }
}
@media (max-width: 767px) {
    .hero-content { padding: 4rem 0; }
    .section-title { font-size: 2rem; }
}
</style>
@endpush

@section('content')
<!-- Hero -->
<section class="hero-section">
    <div class="hero-orb hero-orb-1"></div>
    <div class="hero-orb hero-orb-2"></div>
    <div class="hero-orb hero-orb-3"></div>
    <div class="hero-grid-overlay"></div>
    <div class="container" style="position: relative; z-index: 2;">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content" style="padding-left: 0; padding-right: 2rem;">
                    <div class="hero-eyebrow">
                        <i class="bi bi-lightning-fill"></i>
                        AI-Powered Healthcare Platform
                    </div>
                    <h1 class="hero-headline">
                        Modern EMR System<br>for <em>Healthcare</em><br><span class="accent">Practices</span>
                    </h1>
                    <p class="hero-sub">
                        Complete patient management, appointment scheduling, voice transcription, and billing — all in one secure, HIPAA-compliant platform.
                    </p>
                    <div class="hero-actions">
                        <a href="{{ route('register.doctor') }}" class="btn-hero-primary">
                            <i class="fas fa-stethoscope"></i>
                            Start Free Trial
                        </a>
                        <a href="#features" class="btn-hero-outline">
                            <i class="fas fa-play"></i>
                            See Features
                        </a>
                    </div>
                    <div class="hero-badges">
                        <span class="hero-badge"><i class="fas fa-check-circle"></i>14-day free trial</span>
                        <span class="hero-badge"><i class="fas fa-check-circle"></i>No credit card required</span>
                        <span class="hero-badge"><i class="fas fa-check-circle"></i>Cancel anytime</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-visual">
                    <div class="hero-icon-wrap">
                        <i class="fas fa-file-medical"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section id="features" class="features-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-eyebrow">Core Platform</div>
            <h2 class="section-title">Everything you need to run<br>a <em>modern practice</em></h2>
            <p class="section-sub">Powerful tools designed for healthcare professionals who demand excellence.</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-users"></i></div>
                    <h4>Patient Management</h4>
                    <p>Complete patient records, medical history, and treatment tracking in one secure place.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-calendar-check"></i></div>
                    <h4>Smart Scheduling</h4>
                    <p>Automated appointment booking with reminders and seamless calendar integration.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-microphone"></i></div>
                    <h4>Voice Transcription</h4>
                    <p>Real-time speech-to-text for clinical notes and hands-free documentation.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-file-prescription"></i></div>
                    <h4>Digital Prescriptions</h4>
                    <p>Create and manage prescriptions digitally with full patient medication history.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                    <h4>Billing & Invoicing</h4>
                    <p>Automated billing, payment tracking, and comprehensive financial reporting.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                    <h4>Analytics Dashboard</h4>
                    <p>Track appointments, revenue, and practice performance with real-time insights.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section id="how-it-works" class="how-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-eyebrow">Simple Process</div>
            <h2 class="section-title">Get started in<br><em>three steps</em></h2>
            <p class="section-sub">Up and running in minutes — no technical expertise required.</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="step-card">
                    <div class="step-num">1</div>
                    <i class="fas fa-user-doctor step-icon"></i>
                    <h4>Create Account</h4>
                    <p>Sign up and set up your practice profile in minutes with our guided onboarding.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="step-card">
                    <div class="step-num">2</div>
                    <i class="fas fa-cog step-icon"></i>
                    <h4>Configure Settings</h4>
                    <p>Set your availability, appointment types, specialties, and practice preferences.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="step-card">
                    <div class="step-num">3</div>
                    <i class="fas fa-rocket step-icon"></i>
                    <h4>Start Managing</h4>
                    <p>Begin accepting appointments and managing patients with full AI-powered tools.</p>
                </div>
            </div>
        </div>
    </div>
</section>

@if($showPricingSection)
<!-- Pricing -->
<section id="pricing" class="pricing-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-eyebrow">Pricing</div>
            <h2 class="section-title">Simple, transparent<br><em>pricing</em></h2>
            <p class="section-sub">One plan with everything you need — no hidden fees.</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                @if(isset($pricingPlans) && !empty($pricingPlans))
                    @foreach($pricingPlans as $plan)
                    <div class="pricing-card">
                        <h3>{{ $plan['name'] }}</h3>
                        <p class="pricing-desc">{{ $plan['description'] }}</p>
                        <div class="price-block">
                            <div class="price-amount">${{ $plan['price_monthly'] }}</div>
                            <div class="price-period">per month</div>
                            <div class="price-yearly">or ${{ $plan['price_yearly'] }}/year (save 17%)</div>
                        </div>
                        <ul class="feature-list">
                            @foreach(array_slice($plan['features'], 0, 8) as $feature)
                            <li><i class="fas fa-check"></i>{{ $feature }}</li>
                            @endforeach
                        </ul>
                        <a href="{{ $plan['button_url'] }}" class="btn-pricing btn-pricing-primary">
                            {{ $plan['button_text'] }}
                        </a>
                        <div class="pricing-trust">
                            <i class="fas fa-shield-alt"></i>
                            14-day free trial · No credit card required
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</section>
@endif

<!-- CTA -->
<section class="cta-section">
    <div class="container text-center" style="position: relative; z-index: 2;">
        <h2 class="cta-title">Ready to <em>get started?</em></h2>
        <p class="cta-sub">Join 500+ healthcare professionals using MedSuite AI.</p>
        <div class="cta-actions">
            <a href="{{ route('register.doctor') }}" class="btn-hero-primary">
                <i class="fas fa-stethoscope"></i>
                Start Free Trial
            </a>
            <a href="{{ route('contact') }}" class="btn-hero-outline">
                <i class="fas fa-phone"></i>
                Contact Sales
            </a>
        </div>
        <div class="cta-badges">
            <span class="cta-badge"><i class="fas fa-check-circle"></i>14-day free trial</span>
            <span class="cta-badge"><i class="fas fa-check-circle"></i>No credit card required</span>
            <span class="cta-badge"><i class="fas fa-check-circle"></i>Cancel anytime</span>
        </div>
    </div>
</section>

<script>
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.feature-card, .step-card, .pricing-card').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = 'all 0.5s ease';
    observer.observe(el);
});
</script>
@endsection
