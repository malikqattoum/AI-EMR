# MedSuite Redesign Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Complete redesign of MedSuite public pages (landing page + auth pages) with Modern Minimalist aesthetic, new Teal/Forest Green color scheme, and refined component-based architecture.

**Architecture:** Component-based Blade templates with shared CSS design system. Landing page uses single-page section layout. Auth pages use consistent split-screen layout. All pages extend master layout with shared navigation.

**Tech Stack:** Laravel Blade, Bootstrap 5, Custom CSS (CSS Variables), Google Fonts (Inter), Bootstrap Icons

---

## Phase 1: Design System Foundation

### Task 1: Create landing.css with CSS Variables and Base Styles

**Files:**
- Create: `public/css/landing.css`

**Content:**
```css
/* MedSuite Landing Page Design System */
/* Modern Minimalist - Teal Primary */

:root {
    /* Primary Colors */
    --medsuite-teal: #0d9488;
    --medsuite-teal-dark: #0f766e;
    --medsuite-teal-light: #14b8a6;
    --medsuite-teal-50: #f0fdfa;
    --medsuite-teal-100: #ccfbf1;

    /* Secondary Colors */
    --medsuite-green: #166534;
    --medsuite-green-light: #15803d;

    /* Neutrals */
    --medsuite-slate: #1e293b;
    --medsuite-slate-light: #64748b;
    --medsuite-gray-50: #f8fafc;
    --medsuite-gray-100: #f1f5f9;
    --medsuite-gray-200: #e2e8f0;
    --medsuite-gray-300: #cbd5e1;

    /* Typography */
    --font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;

    /* Shadows */
    --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
    --shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -2px rgba(0,0,0,0.1);
    --shadow-md: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -4px rgba(0,0,0,0.1);
    --shadow-lg: 0 25px 50px -12px rgba(0,0,0,0.15);

    /* Border Radius */
    --radius-sm: 8px;
    --radius: 12px;
    --radius-lg: 16px;
    --radius-xl: 20px;

    /* Transitions */
    --transition: all 0.2s ease;
}

/* Reset & Base */
*, *::before, *::after { box-sizing: border-box; }

body {
    font-family: var(--font-family);
    color: var(--medsuite-slate);
    background: white;
    line-height: 1.6;
    margin: 0;
}

/* Typography */
h1, h2, h3, h4, h5, h6 {
    color: var(--medsuite-slate);
    font-weight: 700;
    line-height: 1.2;
    margin: 0 0 1rem;
}

h1 { font-size: 3rem; line-height: 1.1; }
h2 { font-size: 2rem; }
h3 { font-size: 1.5rem; font-weight: 600; }
h4 { font-size: 1.25rem; font-weight: 600; }

p { margin: 0 0 1rem; }
.text-muted { color: var(--medsuite-slate-light); }
.text-teal { color: var(--medsuite-teal); }

/* Container */
.container-landing {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
}

/* Section Spacing */
.section { padding: 96px 0; }
.section-sm { padding: 64px 0; }
.section-lg { padding: 128px 0; }

/* Button Styles */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 14px 28px;
    font-size: 1rem;
    font-weight: 600;
    border-radius: var(--radius-sm);
    border: none;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
}

.btn-primary {
    background: var(--medsuite-teal);
    color: white;
}

.btn-primary:hover {
    background: var(--medsuite-teal-dark);
    color: white;
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-outline {
    background: transparent;
    color: var(--medsuite-slate);
    border: 2px solid var(--medsuite-gray-200);
}

.btn-outline:hover {
    border-color: var(--medsuite-teal);
    color: var(--medsuite-teal);
}

/* Card Styles */
.card {
    background: white;
    border: 1px solid var(--medsuite-gray-200);
    border-radius: var(--radius-lg);
    padding: 32px;
    transition: var(--transition);
}

.card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
}

/* Eyebrow Text */
.eyebrow {
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--medsuite-teal);
    margin-bottom: 16px;
}

/* Responsive */
@media (max-width: 768px) {
    h1 { font-size: 2.25rem; }
    h2 { font-size: 1.75rem; }
    .section { padding: 64px 0; }
    .section-lg { padding: 80px 0; }
}
```

**Step 1: Create the file**
Create `public/css/landing.css` with the CSS variables and base styles above.

**Step 2: Link in master layout**
Modify `resources/views/master.blade.php` to add:
```blade
<link rel="stylesheet" href="{{ asset('css/landing.css') }}">
```
in the head section (after bootstrap and before custom styles).

**Step 3: Commit**
```bash
git add public/css/landing.css resources/views/master.blade.php
git commit -m "feat: add landing page design system CSS"
```

---

### Task 2: Create MedSuite Logo Blade Component

**Files:**
- Create: `resources/views/components/medsuite-logo.blade.php`

**Content:**
```blade
{{-- resources/views/components/medsuite-logo.blade.php --}}
{{-- Usage: <x-medsuite-logo /> --}}
<a href="{{ url('/') }}" class="medsuite-logo">
    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect width="32" height="32" rx="8" fill="url(#logo-gradient)"/>
        <path d="M16 6L16 26M16 6L10 12M16 6L22 12" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
        <defs>
            <linearGradient id="logo-gradient" x1="0" y1="0" x2="32" y2="32" gradientUnits="userSpaceOnUse">
                <stop stop-color="#0d9488"/>
                <stop offset="1" stop-color="#14b8a6"/>
            </linearGradient>
        </defs>
    </svg>
    <span class="logo-text">MedSuite</span>
</a>

<style>
.medsuite-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
}

.logo-text {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--medsuite-slate);
}
</style>
```

**Step 1: Create the component file**

**Step 2: Commit**
```bash
git add resources/views/components/medsuite-logo.blade.php
git commit -m "feat: add MedSuite logo component"
```

---

### Task 3: Update Navigation Partial

**Files:**
- Modify: `resources/views/layouts/navigation.blade.php`

**Goal:** Update existing navigation to use MedSuite branding, teal color scheme, cleaner styling.

**Note:** Read the current file first to understand its structure, then update:
- Logo/brand section to use MedSuite styling
- Nav links with teal hover states
- Login/Register buttons with new styling

**Step 1: Read current navigation**
Use Read tool to examine `resources/views/layouts/navigation.blade.php`

**Step 2: Update with MedSuite styling**
Replace inline styles with classes from landing.css where possible, add custom styles for nav-specific elements.

**Step 3: Commit**
```bash
git add resources/views/layouts/navigation.blade.php
git commit -m "feat: update navigation with MedSuite branding"
```

---

### Task 4: Create Hero Section Blade Component

**Files:**
- Create: `resources/views/components/hero-section.blade.php`

**Content:**
```blade
{{-- resources/views/components/hero-section.blade.php --}}
{{-- Usage: <x-hero-section headline="Title" subhead="Subtitle" /> --}}
<section class="hero-section">
    <div class="container-landing">
        <div class="hero-content">
            <div class="hero-text">
                @if(isset($eyebrow))
                    <span class="eyebrow">{{ $eyebrow }}</span>
                @endif
                <h1>{{ $headline }}</h1>
                <p class="hero-subhead">{{ $subhead }}</p>
                <div class="hero-cta">
                    @if(isset($cta_primary))
                        <a href="{{ $cta_primary_url ?? '#' }}" class="btn btn-primary">
                            {{ $cta_primary }}
                        </a>
                    @endif
                    @if(isset($cta_secondary))
                        <a href="{{ $cta_secondary_url ?? '#' }}" class="btn btn-outline">
                            {{ $cta_secondary }}
                        </a>
                    @endif
                </div>
            </div>
            <div class="hero-visual">
                {{ $slot }}
            </div>
        </div>
    </div>
</section>

<style>
.hero-section {
    padding: 120px 0 80px;
    background: linear-gradient(180deg, var(--medsuite-teal-50) 0%, white 100%);
}

.hero-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 64px;
    align-items: center;
}

.hero-text h1 {
    font-size: 3.5rem;
    margin-bottom: 24px;
    color: var(--medsuite-slate);
}

.hero-subhead {
    font-size: 1.25rem;
    color: var(--medsuite-slate-light);
    margin-bottom: 32px;
    max-width: 540px;
}

.hero-cta {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
}

.hero-visual {
    display: flex;
    justify-content: center;
    align-items: center;
}

@media (max-width: 992px) {
    .hero-content {
        grid-template-columns: 1fr;
        text-align: center;
    }

    .hero-text h1 {
        font-size: 2.5rem;
    }

    .hero-subhead {
        margin-left: auto;
        margin-right: auto;
    }

    .hero-cta {
        justify-content: center;
    }

    .hero-visual {
        display: none;
    }
}
</style>
```

**Step 1: Create the component**

**Step 2: Commit**
```bash
git add resources/views/components/hero-section.blade.php
git commit -m "feat: add hero section component"
```

---

### Task 5: Create Feature Card Blade Component

**Files:**
- Create: `resources/views/components/feature-card.blade.php`

**Content:**
```blade
{{-- resources/views/components/feature-card.blade.php --}}
{{-- Usage: <x-feature-card icon="bi-heart" title="Title" description="Desc" /> --}}
<div class="feature-card">
    <div class="feature-icon">
        <i class="{{ $icon ?? 'bi-star' }}"></i>
    </div>
    <h4>{{ $title }}</h4>
    <p>{{ $description }}</p>
</div>

<style>
.feature-card {
    background: white;
    border: 1px solid var(--medsuite-gray-200);
    border-radius: var(--radius-lg);
    padding: 32px;
    transition: var(--transition);
}

.feature-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
    border-color: var(--medsuite-teal-100);
}

.feature-icon {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, var(--medsuite-teal) 0%, var(--medsuite-teal-light) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
}

.feature-icon i {
    font-size: 1.5rem;
    color: white;
}

.feature-card h4 {
    margin-bottom: 12px;
    color: var(--medsuite-slate);
}

.feature-card p {
    color: var(--medsuite-slate-light);
    font-size: 0.95rem;
    margin: 0;
    line-height: 1.6;
}
</style>
```

**Step 1: Create the component**

**Step 2: Commit**
```bash
git add resources/views/components/feature-card.blade.php
git commit -m "feat: add feature card component"
```

---

### Task 6: Create Stats Counter Component

**Files:**
- Create: `resources/views/components/stats-section.blade.php`

**Content:**
```blade
{{-- resources/views/components/stats-section.blade.php --}}
<section class="stats-section">
    <div class="container-landing">
        <div class="stats-grid">
            @foreach($stats as $stat)
                <div class="stat-item">
                    <span class="stat-number">{{ $stat['number'] }}</span>
                    <span class="stat-label">{{ $stat['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>

<style>
.stats-section {
    background: var(--medsuite-slate);
    padding: 64px 0;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 32px;
    text-align: center;
}

.stat-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--medsuite-teal-light);
}

.stat-label {
    font-size: 1rem;
    color: rgba(255,255,255,0.8);
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
```

**Step 1: Create the component**

**Step 2: Commit**
```bash
git add resources/views/components/stats-section.blade.php
git commit -m "feat: add stats section component"
```

---

### Task 7: Create CTA Section Component

**Files:**
- Create: `resources/views/components/cta-section.blade.php`

**Content:**
```blade
{{-- resources/views/components/cta-section.blade.php --}}
<section class="cta-section">
    <div class="container-landing">
        <div class="cta-content">
            <h2>{{ $headline ?? 'Ready to Get Started?' }}</h2>
            <p>{{ $subtext ?? 'Join thousands of healthcare professionals transforming their practice.' }}</p>
            @if(isset($button_text))
                <a href="{{ $button_url ?? '#' }}" class="btn btn-primary btn-lg">
                    {{ $button_text }}
                </a>
            @endif
        </div>
    </div>
</section>

<style>
.cta-section {
    padding: 96px 0;
    background: linear-gradient(180deg, white 0%, var(--medsuite-teal-50) 100%);
    text-align: center;
}

.cta-content {
    max-width: 600px;
    margin: 0 auto;
}

.cta-content h2 {
    font-size: 2.5rem;
    margin-bottom: 16px;
}

.cta-content p {
    font-size: 1.125rem;
    color: var(--medsuite-slate-light);
    margin-bottom: 32px;
}

.btn-lg {
    padding: 18px 36px;
    font-size: 1.125rem;
}
</style>
```

**Step 1: Create the component**

**Step 2: Commit**
```bash
git add resources/views/components/cta-section.blade.php
git commit -m "feat: add CTA section component"
```

---

## Phase 2: Landing Page Refactor

### Task 8: Refactor main.blade.php Landing Page

**Files:**
- Modify: `resources/views/main.blade.php`

**Goal:** Complete refactor of the main landing page using new components and design system.

**Note:** This is a large refactor. Read the current file first to understand all sections, then replace with new implementation.

**New Structure:**
```blade
@extends('master')

@section('title', 'MedSuite - AI-Powered Healthcare Platform')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/landing.css') }}">
<style>
    /* Page-specific styles */
    .features-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        margin-top: 48px;
    }

    .ai-section {
        background: var(--medsuite-teal-50);
        padding: 96px 0;
    }

    .ai-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 64px;
        align-items: center;
    }

    .ai-features-list {
        list-style: none;
        padding: 0;
        margin: 24px 0 0;
    }

    .ai-features-list li {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 0;
        color: var(--medsuite-slate);
    }

    .ai-features-list li i {
        color: var(--medsuite-teal);
    }

    .steps-section {
        padding: 96px 0;
    }

    .steps-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 48px;
        margin-top: 48px;
        position: relative;
    }

    .step-item {
        text-align: center;
        position: relative;
    }

    .step-number {
        width: 64px;
        height: 64px;
        background: var(--medsuite-teal);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0 auto 20px;
    }

    .step-item h4 {
        margin-bottom: 8px;
    }

    .step-item p {
        color: var(--medsuite-slate-light);
        margin: 0;
    }

    @media (max-width: 992px) {
        .features-grid, .steps-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .ai-content {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 576px) {
        .features-grid, .steps-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
{{-- Hero Section --}}
<x-hero-section
    eyebrow="AI-Powered Healthcare"
    headline="Modern EMR for Modern Practices"
    subhead="Streamline your healthcare practice with AI-assisted diagnostics, voice transcription, and smart patient management."
    cta_primary="Get Started Free"
    cta_primary_url="{{ route('register') }}"
    cta_secondary="Watch Demo"
    cta_secondary_url="#features"
/>

{{-- Features Section --}}
<section id="features" class="section">
    <div class="container-landing">
        <div style="text-align: center; max-width: 600px; margin: 0 auto 48px;">
            <span class="eyebrow">Core Features</span>
            <h2>Everything You Need to Manage Your Practice</h2>
            <p class="text-muted">Powerful tools designed for healthcare professionals, built with the latest AI technology.</p>
        </div>
        <div class="features-grid">
            <x-feature-card icon="bi-people" title="Patient Management" description="Comprehensive patient records, history, and care coordination in one place." />
            <x-feature-card icon="bi-calendar-check" title="Smart Scheduling" description="AI-powered appointment scheduling that optimizes your calendar automatically." />
            <x-feature-card icon="bi-mic" title="Voice Transcription" description="Convert patient conversations to accurate medical notes instantly." />
            <x-feature-card icon="bi-file-earmark-prescription" title="Digital Prescriptions" description="Create, sign, and send prescriptions digitally with drug interaction checks." />
            <x-feature-card icon="bi-receipt" title="Billing & Invoicing" description="Automated billing, insurance claims, and payment tracking." />
            <x-feature-card icon="bi-graph-up" title="Analytics Dashboard" description="Real-time insights into practice performance and patient outcomes." />
        </div>
    </div>
</section>

{{-- AI Features Section --}}
<section class="ai-section">
    <div class="container-landing">
        <div class="ai-content">
            <div class="ai-text">
                <span class="eyebrow">Powered by AI</span>
                <h2>Your Intelligent Medical Assistant</h2>
                <p>MedSuite's AI assistant helps you diagnose more accurately, reduce administrative burden, and spend more time with patients.</p>
                <ul class="ai-features-list">
                    <li><i class="bi bi-check-circle-fill"></i> AI-powered diagnosis suggestions</li>
                    <li><i class="bi bi-check-circle-fill"></i> Voice-activated clinical notes</li>
                    <li><i class="bi bi-check-circle-fill"></i> Smart prescription recommendations</li>
                    <li><i class="bi bi-check-circle-fill"></i> Predictive analytics for patient outcomes</li>
                </ul>
            </div>
            <div class="ai-visual">
                <div style="background: linear-gradient(135deg, var(--medsuite-teal) 0%, var(--medsuite-teal-light) 100%); border-radius: 24px; padding: 48px; aspect-ratio: 1; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-robot" style="font-size: 6rem; color: white;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- How It Works Section --}}
<section class="steps-section">
    <div class="container-landing">
        <div style="text-align: center; max-width: 600px; margin: 0 auto 48px;">
            <span class="eyebrow">Simple Process</span>
            <h2>How It Works</h2>
        </div>
        <div class="steps-grid">
            <div class="step-item">
                <div class="step-number">1</div>
                <h4>Create Account</h4>
                <p>Sign up in minutes with your medical credentials.</p>
            </div>
            <div class="step-item">
                <div class="step-number">2</div>
                <h4>Configure Settings</h4>
                <p>Customize your practice profile and preferences.</p>
            </div>
            <div class="step-item">
                <div class="step-number">3</div>
                <h4>Start Managing</h4>
                <p>Go live and transform your practice workflow.</p>
            </div>
        </div>
    </div>
</section>

{{-- Stats Section --}}
<x-stats-section :stats="[
    ['number' => '10,000+', 'label' => 'Doctors'],
    ['number' => '500,000+', 'label' => 'Appointments'],
    ['number' => '100,000+', 'label' => 'Patients'],
    ['number' => '4.9/5', 'label' => 'Rating']
]" />

{{-- CTA Section --}}
<x-cta-section
    headline="Ready to Transform Your Practice?"
    subtext="Join thousands of healthcare professionals already using MedSuite."
    button_text="Start Free Trial"
    button_url="{{ route('register') }}"
/>

{{-- Footer --}}
<footer class="footer">
    <div class="container-landing">
        <div class="footer-grid">
            <div class="footer-brand">
                <x-medsuite-logo />
                <p class="text-muted" style="margin-top: 16px;">AI-powered healthcare platform for modern medical practices.</p>
            </div>
            <div class="footer-links">
                <h5>Product</h5>
                <a href="#features">Features</a>
                <a href="#">Pricing</a>
                <a href="#">Integrations</a>
            </div>
            <div class="footer-links">
                <h5>Company</h5>
                <a href="{{ route('about') }}">About Us</a>
                <a href="{{ route('contact') }}">Contact</a>
                <a href="#">Blog</a>
            </div>
            <div class="footer-links">
                <h5>Legal</h5>
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">HIPAA Compliance</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} MedSuite. All rights reserved.</p>
        </div>
    </div>
</footer>
@endsection

<style>
.footer {
    background: var(--medsuite-gray-100);
    padding: 64px 0 32px;
}

.footer-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 48px;
    margin-bottom: 48px;
}

.footer-links {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.footer-links h5 {
    font-size: 1rem;
    margin-bottom: 8px;
}

.footer-links a {
    color: var(--medsuite-slate-light);
    text-decoration: none;
    transition: var(--transition);
}

.footer-links a:hover {
    color: var(--medsuite-teal);
}

.footer-bottom {
    border-top: 1px solid var(--medsuite-gray-200);
    padding-top: 24px;
    text-align: center;
}

.footer-bottom p {
    color: var(--medsuite-slate-light);
    font-size: 0.875rem;
    margin: 0;
}

@media (max-width: 768px) {
    .footer-grid {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 576px) {
    .footer-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
```

**Step 1: Read current main.blade.php**
Use Read tool to examine `resources/views/main.blade.php`

**Step 2: Replace with new implementation**
Write the new blade file with the structure above.

**Step 3: Commit**
```bash
git add resources/views/main.blade.php
git commit -m "refactor: redesign main landing page with MedSuite design system"
```

---

## Phase 3: Auth Pages Refactor

### Task 9: Create Shared Auth Layout Component

**Files:**
- Create: `resources/views/components/auth-layout.blade.php`

**Content:**
```blade
{{-- resources/views/components/auth-layout.blade.php --}}
{{-- Usage: <x-auth-layout title="Page Title"> --}}
<div class="auth-split-page">
    <div class="auth-info-panel">
        <div class="auth-info-content">
            <a href="{{ url('/') }}" class="auth-logo-link">
                <x-medsuite-logo />
            </a>
            <h1>{{ $info_headline ?? 'Welcome to MedSuite' }}</h1>
            <p>{{ $info_subtext ?? 'Your AI-powered healthcare companion.' }}</p>
            <div class="auth-features">
                @if(isset($features))
                    @foreach($features as $feature)
                        <div class="auth-feature-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>{{ $feature }}</span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    <div class="auth-form-panel">
        <div class="auth-form-container">
            <div class="auth-card">
                <div class="auth-card-header">
                    <h2>{{ $title }}</h2>
                    @if(isset($subtitle))
                        <p class="text-muted">{{ $subtitle }}</p>
                    @endif
                </div>
                {{ $slot }}
            </div>
        </div>
    </div>
</div>

<style>
.auth-split-page {
    display: grid;
    grid-template-columns: 40% 60%;
    min-height: 100vh;
}

.auth-info-panel {
    background: linear-gradient(135deg, var(--medsuite-slate) 0%, #0f4a47 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px;
}

.auth-info-content {
    max-width: 400px;
    text-align: center;
}

.auth-logo-link {
    display: inline-block;
    margin-bottom: 32px;
}

.auth-logo-link .logo-text {
    color: white !important;
}

.auth-info-content h1 {
    color: white;
    font-size: 2rem;
    margin-bottom: 16px;
}

.auth-info-content > p {
    color: rgba(255,255,255,0.8);
    font-size: 1.125rem;
}

.auth-features {
    margin-top: 32px;
    text-align: left;
}

.auth-feature-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 0;
    color: rgba(255,255,255,0.9);
}

.auth-feature-item i {
    color: var(--medsuite-teal-light);
    font-size: 1.25rem;
}

.auth-form-panel {
    background: var(--medsuite-gray-100);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px 24px;
}

.auth-form-container {
    width: 100%;
    max-width: 420px;
}

.auth-card {
    background: white;
    border-radius: var(--radius-xl);
    padding: 40px;
    box-shadow: var(--shadow-lg);
}

.auth-card-header {
    text-align: center;
    margin-bottom: 32px;
}

.auth-card-header h2 {
    font-size: 1.75rem;
    margin-bottom: 8px;
}

@media (max-width: 992px) {
    .auth-split-page {
        grid-template-columns: 1fr;
    }

    .auth-info-panel {
        display: none;
    }

    .auth-form-panel {
        min-height: 100vh;
    }
}
</style>
```

**Step 1: Create the component**

**Step 2: Commit**
```bash
git add resources/views/components/auth-layout.blade.php
git commit -m "feat: add auth layout component for split-screen pages"
```

---

### Task 10: Refactor login.blade.php

**Files:**
- Modify: `resources/views/auth/login.blade.php`

**Step 1: Read current login.blade.php**

**Step 2: Replace with clean implementation using new design system**
Use `<x-auth-layout>` component or apply landing.css classes directly. Key changes:
- Remove old inline styles
- Apply new CSS variable colors
- Update form styling to match design system
- Add new MedSuite branding

**Step 3: Commit**
```bash
git add resources/views/auth/login.blade.php
git commit -m "refactor: redesign login page with MedSuite design"
```

---

### Task 11: Refactor register-choice.blade.php

**Files:**
- Modify: `resources/views/auth/register-choice.blade.php`

**Step 1: Read current file**

**Step 2: Update with MedSuite styling**
- Apply teal/green color scheme
- Update registration card styling
- Add hover effects consistent with design system

**Step 3: Commit**
```bash
git add resources/views/auth/register-choice.blade.php
git commit -m "refactor: redesign register choice page"
```

---

### Task 12: Refactor register.blade.php

**Files:**
- Modify: `resources/views/auth/register.blade.php`

**Step 1: Read current file**

**Step 2: Update with clean split-screen layout**
- Apply new styling from landing.css
- Update form inputs to use CSS variables
- Clean up specialty dropdown styling

**Step 3: Commit**
```bash
git add resources/views/auth/register.blade.php
git commit -m "refactor: redesign healthcare provider registration page"
```

---

### Task 13: Refactor patient-register.blade.php

**Files:**
- Modify: `resources/views/auth/patient-register.blade.php`

**Step 1: Read current file**

**Step 2: Update with clean layout**
- Apply new styling
- Update color accents to use green (patient theme)

**Step 3: Commit**
```bash
git add resources/views/auth/patient-register.blade.php
git commit -m "refactor: redesign patient registration page"
```

---

### Task 14: Refactor forgot-password.blade.php

**Files:**
- Modify: `resources/views/auth/forgot-password.blade.php`

**Step 1: Read current file**

**Step 2: Update with centered card layout**
- Apply new styling from landing.css
- Update to use teal color scheme

**Step 3: Commit**
```bash
git add resources/views/auth/forgot-password.blade.php
git commit -m "refactor: redesign forgot password page"
```

---

### Task 15: Refactor reset-password.blade.php

**Files:**
- Modify: `resources/views/auth/reset-password.blade.php`

**Step 1: Read current file**

**Step 2: Update with centered card layout**
- Apply new styling from landing.css

**Step 3: Commit**
```bash
git add resources/views/auth/reset-password.blade.php
git commit -m "refactor: redesign reset password page"
```

---

### Task 16: Refactor admin/login.blade.php

**Files:**
- Modify: `resources/views/admin/auth/login.blade.php`

**Step 1: Read current file**

**Step 2: Update with MedSuite admin styling**
- Apply teal color scheme
- Update to match overall auth page redesign

**Step 3: Commit**
```bash
git add resources/views/admin/auth/login.blade.php
git commit -m "refactor: redesign admin login page"
```

---

## Verification Tasks

### Task 17: Test Landing Page

**Step 1: Open landing page in browser**
Navigate to `/` and verify:
- Navigation bar displays correctly with MedSuite branding
- Hero section loads with headline and CTAs
- Features section shows 6 cards in grid
- AI section displays with text and visual
- How It Works shows 3 steps
- Stats section shows dark bar with 4 metrics
- CTA section displays correctly
- Footer shows 4 columns

**Step 2: Test responsiveness**
Resize browser to mobile width (<576px) and verify:
- Navigation collapses to mobile menu
- Features grid becomes 1 column
- All sections stack vertically

---

### Task 18: Test Auth Pages

**Step 1: Test login page**
Navigate to `/login` and verify:
- Split-screen layout displays (desktop)
- Form inputs styled correctly
- All links work
- Password toggle functions

**Step 2: Test register pages**
Navigate to `/register`, `/register-choice`, `/patient-register` and verify:
- Consistent styling across pages
- Registration cards have hover effects
- Specialty dropdown works

**Step 3: Test password reset pages**
Navigate to `/forgot-password`, `/reset-password/{token}` and verify:
- Centered card layout displays
- Form validation works
- Email sending works (check console)

**Step 4: Test admin login**
Navigate to `/admin/login` and verify:
- Teal header displays
- Form inputs styled consistently

---

### Task 19: Cross-Browser Testing

**Test in Chrome, Firefox, Safari:**
- All pages render correctly
- Animations work smoothly
- Form submissions complete successfully
- No console errors

---

## Implementation Notes

### CSS Variable Usage
All MedSuite pages use CSS variables from `landing.css`. This ensures:
- Consistent color application
- Easy theming
- Single source of truth for design tokens

### Component Reusability
Reusable components (`hero-section`, `feature-card`, `stats-section`, `cta-section`) can be used on:
- Main landing page
- About page
- Doctor landing pages
- Marketing landing pages

### Performance Considerations
- CSS loaded via `<link>` tags (not inline)
- No JavaScript frameworks, just vanilla JS for interactions
- Minimal animations to reduce repaints

---

## Files Summary

| Action | File Path |
|--------|----------|
| Create | `public/css/landing.css` |
| Create | `resources/views/components/medsuite-logo.blade.php` |
| Create | `resources/views/components/hero-section.blade.php` |
| Create | `resources/views/components/feature-card.blade.php` |
| Create | `resources/views/components/stats-section.blade.php` |
| Create | `resources/views/components/cta-section.blade.php` |
| Create | `resources/views/components/auth-layout.blade.php` |
| Modify | `resources/views/master.blade.php` |
| Modify | `resources/views/layouts/navigation.blade.php` |
| Modify | `resources/views/main.blade.php` |
| Modify | `resources/views/auth/login.blade.php` |
| Modify | `resources/views/auth/register-choice.blade.php` |
| Modify | `resources/views/auth/register.blade.php` |
| Modify | `resources/views/auth/patient-register.blade.php` |
| Modify | `resources/views/auth/forgot-password.blade.php` |
| Modify | `resources/views/auth/reset-password.blade.php` |
| Modify | `resources/views/admin/auth/login.blade.php` |

---

**Plan complete and saved to `docs/plans/2026-04-06-medsuite-redesign-implementation-plan.md`.**

**Two execution options:**

**1. Subagent-Driven (this session)** — I dispatch fresh subagent per task, review between tasks, fast iteration

**2. Parallel Session (separate)** — Open new session with executing-plans, batch execution with checkpoints

Which approach?
