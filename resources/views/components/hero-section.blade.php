@props([
    'headline',
    'subhead',
    'eyebrow' => null,
    'ctaPrimary' => null,
    'ctaPrimaryUrl' => '#',
    'ctaSecondary' => null,
    'ctaSecondaryUrl' => '#'
])

<section class="hero-section">
    <div class="hero-background"></div>
    <div class="container">
        <div class="hero-content">
            <!-- Text Content (Left Column) -->
            <div class="hero-text">
                @if($eyebrow)
                    <span class="eyebrow">{{ $eyebrow }}</span>
                @endif

                <h1 class="hero-headline">{{ $headline }}</h1>

                <p class="hero-subhead">{{ $subhead }}</p>

                @if($ctaPrimary || $ctaSecondary)
                    <div class="hero-cta">
                        @if($ctaPrimary)
                            <a href="{{ $ctaPrimaryUrl }}" class="btn btn-primary btn-lg">
                                {{ $ctaPrimary }}
                            </a>
                        @endif

                        @if($ctaSecondary)
                            <a href="{{ $ctaSecondaryUrl }}" class="btn btn-outline btn-lg">
                                {{ $ctaSecondary }}
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Visual Content (Right Column) -->
            <div class="hero-visual">
                @if($slot->isNotEmpty())
                    {{ $slot }}
                @else
                    <div class="hero-placeholder">
                        <div class="hero-placeholder-inner">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                                <path d="M2 17l10 5 10-5"/>
                                <path d="M2 12l10 5 10-5"/>
                            </svg>
                            <span>Hero Visual</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<style>
.hero-section {
    position: relative;
    padding: var(--space-16) 0;
    overflow: hidden;
}

.hero-background {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, var(--color-teal-50) 0%, var(--color-white) 100%);
    z-index: -1;
}

.hero-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--space-12);
    align-items: center;
}

.hero-text {
    animation: fadeInUp 0.6s ease-out forwards;
}

.hero-eyebrow {
    display: inline-block;
    font-size: var(--font-size-xs);
    font-weight: var(--font-weight-semibold);
    text-transform: uppercase;
    letter-spacing: var(--letter-spacing-wide);
    color: var(--color-teal-primary);
    margin-bottom: var(--space-3);
}

.hero-headline {
    font-size: var(--font-size-5xl);
    font-weight: var(--font-weight-bold);
    line-height: var(--line-height-tight);
    letter-spacing: var(--letter-spacing-tight);
    color: var(--text-primary);
    margin-bottom: var(--space-6);
}

.hero-subhead {
    font-size: var(--font-size-lg);
    line-height: var(--line-height-relaxed);
    color: var(--text-secondary);
    margin-bottom: var(--space-8);
}

.hero-cta {
    display: flex;
    gap: var(--space-4);
    flex-wrap: wrap;
}

.hero-visual {
    animation: fadeInUp 0.6s ease-out 200ms forwards;
    opacity: 0;
}

.hero-placeholder {
    background: linear-gradient(135deg, var(--color-gray-100) 0%, var(--color-teal-50) 100%);
    border-radius: var(--radius-2xl);
    padding: var(--space-12);
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 320px;
    box-shadow: var(--shadow-lg);
}

.hero-placeholder-inner {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: var(--space-4);
    color: var(--text-muted);
}

.hero-placeholder-inner svg {
    opacity: 0.5;
}

.hero-placeholder-inner span {
    font-size: var(--font-size-sm);
    font-weight: var(--font-weight-medium);
    text-transform: uppercase;
    letter-spacing: var(--letter-spacing-wide);
}

/* Responsive: Stack vertically on mobile */
@media (max-width: 1023px) {
    .hero-section {
        padding: var(--space-12) 0;
    }

    .hero-content {
        grid-template-columns: 1fr;
        gap: var(--space-8);
        text-align: center;
    }

    .hero-headline {
        font-size: var(--font-size-4xl);
    }

    .hero-subhead {
        font-size: var(--font-size-base);
    }

    .hero-cta {
        justify-content: center;
    }

    .hero-placeholder {
        min-height: 240px;
        margin: 0 var(--space-4);
    }
}

@media (max-width: 639px) {
    .hero-section {
        padding: var(--space-10) 0;
    }

    .hero-headline {
        font-size: var(--font-size-3xl);
    }

    .hero-cta {
        flex-direction: column;
        width: 100%;
    }

    .hero-cta .btn {
        width: 100%;
        justify-content: center;
    }

    .hero-placeholder {
        min-height: 200px;
    }
}
</style>
