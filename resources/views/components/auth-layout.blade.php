@props([
    'headline' => 'Welcome to MedSuite',
    'subtext' => 'Your AI-powered healthcare companion for intelligent diagnosis and seamless patient management.',
    'features' => [],
    'showBrand' => true,
    'brandIcon' => 'bi-shield-check',
    'brandName' => 'MedSuite',
    'leftPanelClass' => '',
])

<div class="auth-layout">
    <div class="auth-layout-container">
        <!-- Left Panel - Brand Info (40%) -->
        <div class="auth-layout-left {{ $leftPanelClass }}">
            <div class="auth-layout-left-content">
                @if($showBrand)
                    <div class="auth-layout-brand">
                        <i class="bi {{ $brandIcon }} auth-layout-brand-icon"></i>
                        <span class="auth-layout-brand-name">{{ $brandName }}</span>
                    </div>
                @endif

                <h1 class="auth-layout-headline">{{ $headline }}</h1>

                <p class="auth-layout-subtext">{{ $subtext }}</p>

                @if(!empty($features))
                    <ul class="auth-layout-features">
                        @foreach($features as $feature)
                            <li class="auth-layout-feature-item">
                                <i class="bi {{ $feature['icon'] ?? 'bi-check-circle' }} auth-layout-feature-icon"></i>
                                <span>{{ $feature['text'] ?? $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <!-- Right Panel - Form Card (60%) -->
        <div class="auth-layout-right">
            <div class="auth-layout-form-card">
                @if($slot->isNotEmpty())
                    {{ $slot }}
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Auth Layout Component Styles */
.auth-layout {
    min-height: 100vh;
    background: linear-gradient(135deg, var(--color-teal-primary) 0%, #0f4a47 50%, var(--color-teal-primary) 100%);
    position: relative;
    overflow: hidden;
}

.auth-layout::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        radial-gradient(circle at 20% 80%, rgba(13, 148, 136, 0.15) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(22, 101, 52, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(20, 184, 166, 0.08) 0%, transparent 40%);
    pointer-events: none;
}

.auth-layout-container {
    display: flex;
    min-height: 100vh;
    position: relative;
    z-index: 1;
}

/* Left Panel - Brand Info (40%) */
.auth-layout-left {
    flex: 0 0 40%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: var(--space-8);
    background: linear-gradient(135deg, var(--color-gray-800) 0%, #0f4a47 100%);
    position: relative;
    overflow: hidden;
}

.auth-layout-left::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -30%;
    width: 80%;
    height: 200%;
    background: radial-gradient(ellipse, rgba(13, 148, 136, 0.15) 0%, transparent 70%);
    pointer-events: none;
}

.auth-layout-left-content {
    position: relative;
    z-index: 2;
    max-width: 480px;
    width: 100%;
    animation: fadeInUp 0.6s ease-out forwards;
}

.auth-layout-brand {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    margin-bottom: var(--space-8);
}

.auth-layout-brand-icon {
    font-size: 2.5rem;
    background: linear-gradient(135deg, var(--color-teal-primary-light) 0%, var(--color-teal-primary) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: float 3s ease-in-out infinite;
}

.auth-layout-brand-name {
    font-size: var(--font-size-2xl);
    font-weight: var(--font-weight-bold);
    color: var(--text-inverse);
    letter-spacing: var(--letter-spacing-tight);
}

.auth-layout-headline {
    font-size: var(--font-size-4xl);
    font-weight: var(--font-weight-bold);
    color: var(--text-inverse);
    line-height: var(--line-height-tight);
    letter-spacing: var(--letter-spacing-tight);
    margin-bottom: var(--space-4);
}

.auth-layout-subtext {
    font-size: var(--font-size-lg);
    color: var(--color-gray-300);
    line-height: var(--line-height-relaxed);
    margin-bottom: var(--space-8);
}

.auth-layout-features {
    list-style: none;
    padding: 0;
    margin: 0;
}

.auth-layout-feature-item {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    padding: var(--space-3) 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    color: var(--text-inverse);
    font-size: var(--font-size-base);
}

.auth-layout-feature-item:last-child {
    border-bottom: none;
}

.auth-layout-feature-icon {
    font-size: var(--font-size-lg);
    color: var(--color-teal-primary-light);
    flex-shrink: 0;
}

/* Right Panel - Form Card (60%) */
.auth-layout-right {
    flex: 0 0 60%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: var(--space-8);
    background: linear-gradient(135deg, var(--color-gray-100) 0%, var(--color-teal-50) 100%);
}

.auth-layout-form-card {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-radius: var(--radius-xl);
    padding: var(--space-10);
    box-shadow: var(--shadow-xl);
    border: 1px solid rgba(255, 255, 255, 0.6);
    width: 100%;
    max-width: 480px;
    animation: fadeInUp 0.5s ease-out 100ms forwards;
    opacity: 0;
}

/* Responsive: Stack on mobile (form on top) */
@media (max-width: 1023px) {
    .auth-layout-container {
        flex-direction: column-reverse;
    }

    .auth-layout-left {
        flex: none;
        padding: var(--space-6);
    }

    .auth-layout-left-content {
        text-align: center;
        max-width: 100%;
    }

    .auth-layout-brand {
        justify-content: center;
    }

    .auth-layout-headline {
        font-size: var(--font-size-3xl);
    }

    .auth-layout-subtext {
        font-size: var(--font-size-base);
    }

    .auth-layout-features {
        display: inline-block;
        text-align: left;
    }

    .auth-layout-right {
        flex: none;
        padding: var(--space-6);
    }

    .auth-layout-form-card {
        max-width: 100%;
        padding: var(--space-6);
    }
}

@media (max-width: 639px) {
    .auth-layout-left {
        padding: var(--space-4);
    }

    .auth-layout-brand-icon {
        font-size: 2rem;
    }

    .auth-layout-brand-name {
        font-size: var(--font-size-xl);
    }

    .auth-layout-headline {
        font-size: var(--font-size-2xl);
    }

    .auth-layout-feature-item {
        font-size: var(--font-size-sm);
        padding: var(--space-2) 0;
    }

    .auth-layout-right {
        padding: var(--space-4);
    }

    .auth-layout-form-card {
        padding: var(--space-4);
        border-radius: var(--radius-lg);
    }
}

/* Animations */
@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
