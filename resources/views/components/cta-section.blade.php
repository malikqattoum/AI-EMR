@props([
    'headline',
    'subtext',
    'buttonText',
    'buttonUrl' => '#'
])

<section class="cta-section">
    <div class="cta-background"></div>
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-headline">{{ $headline }}</h2>
            <p class="cta-subtext">{{ $subtext }}</p>
            <a href="{{ $buttonUrl }}" class="btn btn-primary btn-lg">
                {{ $buttonText }}
            </a>
        </div>
    </div>
</section>

<style>
.cta-section {
    position: relative;
    padding: var(--space-16) 0;
    overflow: hidden;
}

.cta-background {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, var(--color-teal-50) 0%, var(--color-white) 100%);
    z-index: -1;
}

.cta-content {
    max-width: 600px;
    margin: 0 auto;
    text-align: center;
}

.cta-headline {
    font-size: var(--font-size-4xl);
    font-weight: var(--font-weight-bold);
    line-height: var(--line-height-tight);
    letter-spacing: var(--letter-spacing-tight);
    color: var(--text-primary);
    margin-bottom: var(--space-4);
}

.cta-subtext {
    font-size: var(--font-size-lg);
    line-height: var(--line-height-relaxed);
    color: var(--text-secondary);
    margin-bottom: var(--space-8);
}

@media (max-width: 639px) {
    .cta-section {
        padding: var(--space-12) 0;
    }

    .cta-headline {
        font-size: var(--font-size-3xl);
    }

    .cta-subtext {
        font-size: var(--font-size-base);
    }

    .cta-content .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
