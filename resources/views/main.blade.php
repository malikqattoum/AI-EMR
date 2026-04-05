@extends('master')

@section('title', 'MedSuite AI - Modern EMR System for Healthcare Practices')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/landing.css') }}">
@endpush

@section('content')

<!-- Hero Section -->
<x-hero-section
    headline="AI-Powered Healthcare for Modern Medical Practices"
    subhead="Complete patient management, appointment scheduling, voice transcription, and billing in one secure, intelligent platform."
    eyebrow="AI-Powered EMR"
    cta-primary="Start Free Trial"
    cta-primary-url="{{ route('register.doctor') }}"
    cta-secondary="See Features"
    cta-secondary-url="#features"
>
    <div class="hero-visual-content">
        <div class="hero-icon-wrapper">
            <i class="fas fa-file-medical"></i>
        </div>
    </div>
</x-hero-section>

<!-- Features Section -->
<section id="features" class="section section-teal">
    <div class="container">
        <div class="text-center mb-12">
            <span class="eyebrow">Core Features</span>
            <h2>Everything You Need to Run a Modern Medical Practice</h2>
            <p class="text-muted" style="max-width: 600px; margin-left: auto; margin-right: auto;">
                Complete tools for patient management, scheduling, billing, and AI-powered clinical decision support.
            </p>
        </div>
        <div class="row g-4" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: var(--space-6);">
            <x-feature-card
                icon="fas fa-users"
                title="Patient Management"
                description="Complete patient records, medical history, and treatment tracking in one secure place."
            />
            <x-feature-card
                icon="fas fa-calendar-check"
                title="Smart Scheduling"
                description="Automated appointment booking with reminders and calendar integration."
            />
            <x-feature-card
                icon="fas fa-microphone"
                title="Voice Transcription"
                description="Real-time speech-to-text for clinical notes and documentation."
            />
            <x-feature-card
                icon="fas fa-file-prescription"
                title="Digital Prescriptions"
                description="Create and manage prescriptions digitally with patient history."
            />
            <x-feature-card
                icon="fas fa-file-invoice-dollar"
                title="Billing & Invoicing"
                description="Automated billing, payment tracking, and financial reporting."
            />
            <x-feature-card
                icon="fas fa-chart-line"
                title="Analytics Dashboard"
                description="Track appointments, revenue, and practice performance metrics."
            />
        </div>
    </div>
</section>

<!-- AI Features Spotlight Section -->
<section id="ai-features" class="section bg-white">
    <div class="container">
        <div class="text-center mb-12">
            <span class="eyebrow">AI-Powered Features</span>
            <h2>Intelligent Healthcare Technology</h2>
            <p class="text-muted" style="max-width: 600px; margin-left: auto; margin-right: auto;">
                Advanced AI features that transform your practice with intelligent automation.
            </p>
        </div>
        <div class="row" style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-12); align-items: center;">
            <!-- Left Column - AI Features List -->
            <div class="ai-features-list">
                <div class="ai-feature-item">
                    <div class="ai-feature-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="ai-feature-content">
                        <h4>AI Medical Copilot</h4>
                        <p>Intelligent assistant that helps with diagnosis suggestions, treatment recommendations, and clinical decision support based on patient data.</p>
                    </div>
                </div>
                <div class="ai-feature-item">
                    <div class="ai-feature-icon">
                        <i class="fas fa-microphone-alt"></i>
                    </div>
                    <div class="ai-feature-content">
                        <h4>Voice Assistant</h4>
                        <p>Hands-free clinical documentation with real-time voice transcription and automatic note generation powered by advanced AI.</p>
                    </div>
                </div>
                <div class="ai-feature-item">
                    <div class="ai-feature-icon">
                        <i class="fas fa-pills"></i>
                    </div>
                    <div class="ai-feature-content">
                        <h4>Smart Prescription Suggestions</h4>
                        <p>AI-powered medication recommendations based on diagnosis, patient history, and drug interactions analysis.</p>
                    </div>
                </div>
                <div class="ai-feature-item">
                    <div class="ai-feature-icon">
                        <i class="fas fa-chart-network"></i>
                    </div>
                    <div class="ai-feature-content">
                        <h4>Predictive Analytics</h4>
                        <p>Advanced analytics for risk assessment, patient outcome predictions, and practice performance optimization.</p>
                    </div>
                </div>
            </div>
            <!-- Right Column - AI Visual -->
            <div class="ai-visual-card">
                <div class="ai-visual-inner">
                    <i class="fas fa-brain"></i>
                    <h3>AI-Powered Healthcare</h3>
                    <p>Experience the future of medical practice management with cutting-edge artificial intelligence.</p>
                    <div class="ai-stats">
                        <div class="ai-stat">
                            <span class="ai-stat-number">24/7</span>
                            <span class="ai-stat-label">AI Availability</span>
                        </div>
                        <div class="ai-stat">
                            <span class="ai-stat-number">95%</span>
                            <span class="ai-stat-label">Accuracy Rate</span>
                        </div>
                        <div class="ai-stat">
                            <span class="ai-stat-number">50%</span>
                            <span class="ai-stat-label">Time Saved</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section id="how-it-works" class="section section-teal">
    <div class="container">
        <div class="text-center mb-12">
            <span class="eyebrow">Simple Process</span>
            <h2>How It Works</h2>
            <p class="text-muted" style="max-width: 600px; margin-left: auto; margin-right: auto;">
                Get started in three simple steps
            </p>
        </div>
        <div class="row g-4" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: var(--space-6);">
            <div class="step-card">
                <div class="step-number">1</div>
                <div class="step-content">
                    <i class="fas fa-user-doctor"></i>
                    <h4>Create Account</h4>
                    <p>Sign up and set up your practice profile in minutes.</p>
                </div>
            </div>
            <div class="step-card">
                <div class="step-number">2</div>
                <div class="step-content">
                    <i class="fas fa-cog"></i>
                    <h4>Configure Settings</h4>
                    <p>Set your availability, appointment types, and preferences.</p>
                </div>
            </div>
            <div class="step-card">
                <div class="step-number">3</div>
                <div class="step-content">
                    <i class="fas fa-rocket"></i>
                    <h4>Start Managing</h4>
                    <p>Begin accepting appointments and managing patients.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<x-stats-section
    :stats="[
        ['number' => '500+', 'label' => 'Active Doctors'],
        ['number' => '10K+', 'label' => 'Appointments Booked'],
        ['number' => '25K+', 'label' => 'Patients Served'],
        ['number' => '4.8★', 'label' => 'Average Rating']
    ]"
/>

<!-- Why Choose Us Section -->
<section class="section bg-white">
    <div class="container">
        <div class="text-center mb-12">
            <span class="eyebrow">Benefits</span>
            <h2>Why Choose MedSuite AI</h2>
            <p class="text-muted" style="max-width: 600px; margin-left: auto; margin-right: auto;">
                Built for modern healthcare professionals
            </p>
        </div>
        <div class="row" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: var(--space-6);">
            <div class="why-card">
                <div class="why-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h4>Save Time</h4>
                <p>Reduce documentation time with voice transcription and automated workflows.</p>
            </div>
            <div class="why-card">
                <div class="why-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h4>Secure & Reliable</h4>
                <p>Enterprise-grade security with encrypted data storage and backups.</p>
            </div>
            <div class="why-card">
                <div class="why-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h4>Access Anywhere</h4>
                <p>Cloud-based platform accessible from any device, anytime.</p>
            </div>
            <div class="why-card">
                <div class="why-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h4>Expert Support</h4>
                <p>Dedicated support team to help you get the most from the platform.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<x-cta-section
    headline="Ready to Transform Your Practice?"
    subtext="Join healthcare professionals using modern EMR technology. Start your free trial today."
    button-text="Start Free Trial"
    :button-url="route('register.doctor')"
/>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-brand">
                <x-medsuite-logo />
                <p>AI-powered EMR system for modern healthcare practices.</p>
            </div>
            <div class="footer-links">
                <div class="footer-column">
                    <h5>Product</h5>
                    <ul>
                        <li><a href="#features">Features</a></li>
                        <li><a href="#ai-features">AI Features</a></li>
                        <li><a href="#how-it-works">How It Works</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h5>Company</h5>
                    <ul>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h5>Legal</h5>
                    <ul>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">HIPAA Compliance</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} MedSuite AI. All rights reserved.</p>
        </div>
    </div>
</footer>

<style>
/* Hero Visual Content */
.hero-visual-content {
    display: flex;
    align-items: center;
    justify-content: center;
}

.hero-icon-wrapper {
    width: 280px;
    height: 280px;
    border-radius: var(--radius-2xl);
    background: linear-gradient(135deg, var(--color-teal-50) 0%, var(--color-white) 100%);
    box-shadow: var(--shadow-xl);
    display: flex;
    align-items: center;
    justify-content: center;
}

.hero-icon-wrapper i {
    font-size: 80px;
    color: var(--color-teal-primary);
}

/* AI Features Section */
.ai-features-list {
    display: flex;
    flex-direction: column;
    gap: var(--space-6);
}

.ai-feature-item {
    display: flex;
    gap: var(--space-4);
    align-items: flex-start;
}

.ai-feature-icon {
    width: 56px;
    height: 56px;
    border-radius: var(--radius-lg);
    background: linear-gradient(135deg, var(--color-teal-primary) 0%, var(--color-teal-primary-light) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: var(--shadow-teal);
}

.ai-feature-icon i {
    font-size: var(--font-size-xl);
    color: var(--color-white);
}

.ai-feature-content h4 {
    font-size: var(--font-size-lg);
    font-weight: var(--font-weight-semibold);
    color: var(--text-primary);
    margin-bottom: var(--space-2);
}

.ai-feature-content p {
    font-size: var(--font-size-sm);
    color: var(--text-secondary);
    line-height: var(--line-height-relaxed);
    margin-bottom: 0;
}

.ai-visual-card {
    border-radius: var(--radius-2xl);
    background: linear-gradient(135deg, var(--color-gray-800) 0%, var(--color-gray-900) 100%);
    box-shadow: var(--shadow-xl);
    overflow: hidden;
}

.ai-visual-inner {
    padding: var(--space-12);
    text-align: center;
}

.ai-visual-inner i {
    font-size: 80px;
    color: var(--color-teal-primary);
    margin-bottom: var(--space-4);
}

.ai-visual-inner h3 {
    color: var(--color-white);
    font-size: var(--font-size-2xl);
    margin-bottom: var(--space-3);
}

.ai-visual-inner p {
    color: var(--color-gray-400);
    margin-bottom: var(--space-6);
}

.ai-stats {
    display: flex;
    justify-content: center;
    gap: var(--space-8);
}

.ai-stat {
    text-align: center;
}

.ai-stat-number {
    display: block;
    font-size: var(--font-size-2xl);
    font-weight: var(--font-weight-bold);
    color: var(--color-teal-primary);
    margin-bottom: var(--space-1);
}

.ai-stat-label {
    font-size: var(--font-size-xs);
    color: var(--color-gray-400);
    text-transform: uppercase;
    letter-spacing: var(--letter-spacing-wide);
}

/* Step Cards */
.step-card {
    background: var(--color-white);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-card);
    padding: var(--space-8) var(--space-6);
    text-align: center;
    position: relative;
    transition: all var(--transition-normal);
}

.step-card:hover {
    box-shadow: var(--shadow-card-hover);
    transform: translateY(-4px);
}

.step-number {
    position: absolute;
    top: -20px;
    left: 50%;
    transform: translateX(-50%);
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--color-teal-primary) 0%, var(--color-teal-primary-light) 100%);
    border-radius: var(--radius-full);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--color-white);
    font-weight: var(--font-weight-bold);
    font-size: var(--font-size-xl);
    box-shadow: var(--shadow-teal);
}

.step-content {
    padding-top: var(--space-4);
}

.step-content i {
    font-size: var(--font-size-4xl);
    color: var(--color-teal-primary);
    margin-bottom: var(--space-4);
}

.step-content h4 {
    font-size: var(--font-size-lg);
    margin-bottom: var(--space-2);
}

.step-content p {
    font-size: var(--font-size-sm);
    color: var(--text-secondary);
    margin-bottom: 0;
}

/* Why Choose Us Cards */
.why-card {
    text-align: center;
    padding: var(--space-6);
    border-radius: var(--radius-xl);
    background: var(--color-gray-50);
    transition: all var(--transition-normal);
}

.why-card:hover {
    background: var(--color-white);
    box-shadow: var(--shadow-card);
    transform: translateY(-4px);
}

.why-icon {
    width: 72px;
    height: 72px;
    border-radius: var(--radius-full);
    background: linear-gradient(135deg, var(--color-teal-primary) 0%, var(--color-teal-primary-light) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--space-4);
    box-shadow: var(--shadow-teal);
}

.why-icon i {
    font-size: var(--font-size-2xl);
    color: var(--color-white);
}

.why-card h4 {
    font-size: var(--font-size-base);
    font-weight: var(--font-weight-semibold);
    margin-bottom: var(--space-2);
}

.why-card p {
    font-size: var(--font-size-sm);
    color: var(--text-secondary);
    margin-bottom: 0;
    line-height: var(--line-height-relaxed);
}

/* Footer */
.footer {
    background: var(--color-gray-900);
    color: var(--color-gray-300);
    padding: var(--space-16) 0 var(--space-8);
}

.footer-content {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: var(--space-12);
    margin-bottom: var(--space-12);
}

.footer-brand p {
    margin-top: var(--space-4);
    color: var(--color-gray-400);
    font-size: var(--font-size-sm);
}

.footer-links {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: var(--space-8);
}

.footer-column h5 {
    color: var(--color-white);
    font-size: var(--font-size-sm);
    font-weight: var(--font-weight-semibold);
    margin-bottom: var(--space-4);
}

.footer-column ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-column li {
    margin-bottom: var(--space-2);
}

.footer-column a {
    color: var(--color-gray-400);
    font-size: var(--font-size-sm);
    transition: color var(--transition-fast);
}

.footer-column a:hover {
    color: var(--color-teal-primary);
}

.footer-bottom {
    border-top: 1px solid var(--color-gray-700);
    padding-top: var(--space-6);
    text-align: center;
}

.footer-bottom p {
    color: var(--color-gray-500);
    font-size: var(--font-size-sm);
    margin-bottom: 0;
}

/* Responsive Grid Adjustments */
@media (max-width: 1023px) {
    .ai-features-list {
        grid-template-columns: 1fr;
    }

    [style*="grid-template-columns: repeat(3, 1fr)"] {
        grid-template-columns: repeat(2, 1fr) !important;
    }

    [style*="grid-template-columns: repeat(4, 1fr)"] {
        grid-template-columns: repeat(2, 1fr) !important;
    }

    .footer-content {
        grid-template-columns: 1fr;
    }

    .footer-links {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 639px) {
    .hero-icon-wrapper {
        width: 200px;
        height: 200px;
    }

    .hero-icon-wrapper i {
        font-size: 50px;
    }

    [style*="grid-template-columns: 1fr 1fr"] {
        grid-template-columns: 1fr !important;
    }

    [style*="grid-template-columns: repeat(2, 1fr)"] {
        grid-template-columns: 1fr !important;
    }

    .ai-stats {
        flex-direction: column;
        gap: var(--space-4);
    }

    .footer-links {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
