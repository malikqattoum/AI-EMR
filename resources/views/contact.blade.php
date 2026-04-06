@extends('master')

@section('title', 'Contact Us — MedSuite AI')

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
                Get in Touch
            </div>
            <h1 class="page-hero-title">
                Let's build the<br>
                <em>future of care</em><br>
                <span class="page-title-accent">together.</span>
            </h1>
            <p class="page-hero-sub">
                Questions about AI diagnosis, voice transcription, patient management, or any feature? Our team of healthcare technology experts is ready to help.
            </p>
            <div class="page-hero-contact">
                <a href="mailto:info@medsuiteai.com" class="page-hero-email">
                    <i class="bi bi-envelope-fill"></i>
                    info@medsuiteai.com
                </a>
            </div>
        </div>
    </section>

    <!-- ═══════════ CONTACT CONTENT ═══════════ -->
    <section class="page-section page-contact-section">
        <div class="page-container">
            <div class="page-contact-grid">

                <!-- Left: Form -->
                <div class="page-form-panel">
                    <div class="page-form-header">
                        <h2>Send us a message</h2>
                        <p>Fill out the form below and our team will get back to you within 2 hours.</p>
                    </div>

                    @if(session('success'))
                        <div class="page-alert page-alert-success">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="page-alert page-alert-error">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    <form class="page-form" method="POST" action="{{ route('contact.store') }}">
                        @csrf

                        <div class="page-form-row">
                            <div class="page-field">
                                <label for="name">
                                    <i class="bi bi-person"></i> Full Name <span class="page-required">Required</span>
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                       placeholder="Your full name" required>
                                @error('name')
                                    <div class="page-field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="page-field">
                                <label for="email">
                                    <i class="bi bi-envelope"></i> Email Address <span class="page-required">Required</span>
                                </label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                       placeholder="you@example.com" required>
                                @error('email')
                                    <div class="page-field-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="page-form-row">
                            <div class="page-field">
                                <label for="phone">
                                    <i class="bi bi-telephone"></i> Phone Number
                                </label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                       placeholder="+1 234 567 8900">
                                @error('phone')
                                    <div class="page-field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="page-field">
                                <label for="service">
                                    <i class="bi bi-tag"></i> Inquiry Type
                                </label>
                                <div class="page-select-wrap">
                                    <select id="service" name="service">
                                        <option value="">— Select One —</option>
                                        <option value="General Inquiry" {{ old('service') == 'General Inquiry' ? 'selected' : '' }}>General Inquiry</option>
                                        <option value="AI Diagnosis Support" {{ old('service') == 'AI Diagnosis Support' ? 'selected' : '' }}>AI Diagnosis Support</option>
                                        <option value="Voice Assistant Help" {{ old('service') == 'Voice Assistant Help' ? 'selected' : '' }}>Voice Assistant Help</option>
                                        <option value="Patient Management" {{ old('service') == 'Patient Management' ? 'selected' : '' }}>Patient Management</option>
                                        <option value="Landing Page Setup" {{ old('service') == 'Landing Page Setup' ? 'selected' : '' }}>Landing Page Setup</option>
                                        <option value="Technical Support" {{ old('service') == 'Technical Support' ? 'selected' : '' }}>Technical Support</option>
                                        <option value="Billing & Subscription" {{ old('service') == 'Billing & Subscription' ? 'selected' : '' }}>Billing & Subscription</option>
                                        <option value="Partnership" {{ old('service') == 'Partnership' ? 'selected' : '' }}>Partnership Opportunities</option>
                                        <option value="Demo Request" {{ old('service') == 'Demo Request' ? 'selected' : '' }}>Platform Demo Request</option>
                                        <option value="Integration Support" {{ old('service') == 'Integration Support' ? 'selected' : '' }}>Integration Support</option>
                                    </select>
                                    <i class="bi bi-chevron-down page-select-arrow"></i>
                                </div>
                                @error('service')
                                    <div class="page-field-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="page-field">
                            <label for="subject">
                                <i class="bi bi-chat-left-text"></i> Subject <span class="page-required">Required</span>
                            </label>
                            <input type="text" id="subject" name="subject" value="{{ old('subject') }}"
                                   placeholder="How can we help?" required>
                            @error('subject')
                                <div class="page-field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="page-field">
                            <label for="message">
                                <i class="bi bi-blockquote-left"></i> Message <span class="page-required">Required</span>
                            </label>
                            <textarea id="message" name="message" rows="5"
                                      placeholder="Tell us about your needs or questions regarding our AI healthcare platform..."
                                      required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="page-field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="page-submit">
                            <span>Send Message</span>
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </form>
                </div>

                <!-- Right: Info Cards -->
                <div class="page-info-panel">
                    <div class="page-info-card">
                        <div class="page-info-icon"><i class="bi bi-headset"></i></div>
                        <div class="page-info-content">
                            <h4>24/7 Platform Support</h4>
                            <p>Our AI healthcare platform is available around the clock with dedicated support for all features.</p>
                            <span class="page-info-tag">Response within 2 hours</span>
                        </div>
                    </div>

                    <div class="page-info-card">
                        <div class="page-info-icon"><i class="bi bi-robot"></i></div>
                        <div class="page-info-content">
                            <h4>AI Expertise</h4>
                            <p>Specialized support for AI diagnosis, voice assistant, and automated patient management features.</p>
                            <span class="page-info-tag">Healthcare AI Specialists</span>
                        </div>
                    </div>

                    <div class="page-info-card">
                        <div class="page-info-icon"><i class="bi bi-shield-check-fill"></i></div>
                        <div class="page-info-content">
                            <h4>HIPAA Compliance</h4>
                            <p>All communications and data are encrypted and HIPAA compliant with enterprise-grade security.</p>
                            <span class="page-info-tag">Enterprise Grade Security</span>
                        </div>
                    </div>

                    <div class="page-info-card">
                        <div class="page-info-icon"><i class="bi bi-people-fill"></i></div>
                        <div class="page-info-content">
                            <h4>Healthcare Technology Experts</h4>
                            <p>Our team includes medical professionals, AI specialists, and healthcare technology experts.</p>
                            <span class="page-info-tag">Complete Healthcare Solutions</span>
                        </div>
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
    min-height: 55vh;
    display: flex; align-items: center;
    overflow: hidden;
    padding: 8rem 2rem 5rem;
}
.page-hero-bg { position: absolute; inset: 0; pointer-events: none; }
.page-orb { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.5; }
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

.page-hero-title {
    font-family: var(--font-display);
    font-size: clamp(2.5rem, 5vw, 4rem); font-weight: 300; line-height: 1.05;
    color: var(--white); margin-bottom: 1.25rem;
}
.page-hero-title em { color: var(--teal); font-style: italic; }
.page-title-accent { color: var(--offwhite); font-style: normal; }

.page-hero-sub {
    font-size: 1.05rem; color: var(--muted); line-height: 1.75;
    margin-bottom: 2rem; max-width: 500px; margin-left: auto; margin-right: auto;
}

.page-hero-contact { display: flex; justify-content: center; }
.page-hero-email {
    display: inline-flex; align-items: center; gap: 0.5rem;
    color: var(--teal); font-size: 0.9rem; font-weight: 500;
    padding: 0.5rem 1.25rem;
    border: 1px solid rgba(0,212,170,0.3);
    border-radius: 50px;
    transition: all 0.2s;
}
.page-hero-email:hover { background: rgba(0,212,170,0.08); }

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   CONTACT SECTION
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.page-section { padding: 5rem 2rem; }
.page-container { max-width: 1100px; margin: 0 auto; }

.page-contact-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 3rem;
    align-items: start;
}

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   FORM PANEL
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.page-form-panel {
    background: var(--navy-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 2.5rem;
}
.page-form-header { margin-bottom: 2rem; }
.page-form-header h2 {
    font-family: var(--font-display);
    font-size: 1.75rem; font-weight: 600; color: var(--white);
    margin-bottom: 0.5rem;
}
.page-form-header p { font-size: 0.875rem; color: var(--muted); }

.page-alert {
    display: flex; align-items: center; gap: 0.75rem;
    padding: 1rem 1.25rem;
    border-radius: 12px;
    font-size: 0.875rem;
    margin-bottom: 1.5rem;
}
.page-alert-success {
    background: rgba(34,197,94,0.1);
    border: 1px solid rgba(34,197,94,0.25);
    color: #86efac;
}
.page-alert-error {
    background: rgba(248,113,113,0.1);
    border: 1px solid rgba(248,113,113,0.25);
    color: #fca5a5;
}
.page-alert i { font-size: 1rem; flex-shrink: 0; }

.page-form { display: flex; flex-direction: column; gap: 1.25rem; }
.page-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }

.page-field { display: flex; flex-direction: column; gap: 0.5rem; }
.page-field label {
    display: flex; align-items: center; gap: 0.4rem;
    font-size: 0.8125rem; font-weight: 500; color: var(--offwhite);
}
.page-field label i { color: var(--teal); font-size: 0.875rem; }
.page-required {
    margin-left: auto;
    font-size: 0.68rem; letter-spacing: 0.06em; text-transform: uppercase;
    color: var(--teal);
    background: rgba(0,212,170,0.08);
    border: 1px solid rgba(0,212,170,0.2);
    padding: 0.15rem 0.5rem; border-radius: 50px;
}
.page-field input,
.page-field textarea,
.page-field select {
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
.page-field input::placeholder,
.page-field textarea::placeholder { color: rgba(232,237,245,0.25); }
.page-field input:focus,
.page-field textarea:focus,
.page-field select:focus {
    border-color: rgba(0,212,170,0.5);
    box-shadow: 0 0 0 3px rgba(0,212,170,0.08), inset 0 0 0 1px rgba(0,212,170,0.1);
}
.page-field textarea { resize: vertical; min-height: 120px; }
.page-select-wrap { position: relative; }
.page-select-wrap select { padding-right: 2.5rem; cursor: pointer; }
.page-select-arrow {
    position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);
    color: var(--muted); pointer-events: none; font-size: 0.75rem;
}
.page-select-wrap select option,
.page-select-wrap select optgroup { background: #0c1633; color: var(--offwhite); }
.page-field-error {
    display: flex; align-items: center; gap: 0.4rem;
    font-size: 0.78rem; color: var(--error);
}

.page-submit {
    display: flex; align-items: center; justify-content: center; gap: 0.6rem;
    width: 100%; padding: 1rem;
    background: var(--teal); color: var(--navy);
    font-size: 1rem; font-weight: 600; font-family: var(--font-body);
    border: none; border-radius: 12px; cursor: pointer;
    transition: all 0.25s;
    box-shadow: 0 0 30px rgba(0,212,170,0.25);
    margin-top: 0.5rem;
}
.page-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 50px rgba(0,212,170,0.4);
}
.page-submit i { font-size: 0.875rem; }

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   INFO PANEL
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.page-info-panel {
    display: flex; flex-direction: column; gap: 1.25rem;
}
.page-info-card {
    background: var(--navy-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 1.5rem;
    display: flex; align-items: flex-start; gap: 1rem;
    transition: border-color 0.3s;
}
.page-info-card:hover { border-color: rgba(0,212,170,0.3); }
.page-info-icon {
    width: 44px; height: 44px; flex-shrink: 0;
    background: var(--teal-dim);
    border: 1px solid rgba(0,212,170,0.2);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: var(--teal); font-size: 1.1rem;
}
.page-info-content { flex: 1; }
.page-info-content h4 {
    font-family: var(--font-display);
    font-size: 1rem; font-weight: 600; color: var(--white);
    margin-bottom: 0.4rem;
}
.page-info-content p {
    font-size: 0.8125rem; color: var(--muted); line-height: 1.6;
    margin-bottom: 0.6rem;
}
.page-info-tag {
    display: inline-flex; align-items: center;
    font-size: 0.7rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.06em;
    color: var(--teal);
    background: rgba(0,212,170,0.06);
    border: 1px solid rgba(0,212,170,0.15);
    padding: 0.2rem 0.6rem; border-radius: 50px;
}

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   RESPONSIVE
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
@media (max-width: 900px) {
    .page-contact-grid { grid-template-columns: 1fr; }
    .page-info-panel { order: -1; display: grid; grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 640px) {
    .page-form-row { grid-template-columns: 1fr; }
    .page-info-panel { grid-template-columns: 1fr; }
    .page-form-panel { padding: 1.5rem; }
}
</style>
@endsection
