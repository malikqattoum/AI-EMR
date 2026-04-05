<a href="{{ url('/') }}" class="medsuite-logo-link" aria-label="MedSuite Home">
    <div class="medsuite-logo-container">
        <svg class="medsuite-logo-icon" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="medsuiteGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#0d9488"/>
                    <stop offset="100%" stop-color="#14b8a6"/>
                </linearGradient>
            </defs>
            <!-- Medical Shield -->
            <path d="M24 4L6 12v12c0 10 8 18 18 22 10-4 18-12 18-22V12L24 4z" fill="url(#medsuiteGradient)"/>
            <!-- Medical Cross -->
            <path d="M22 18h4v4h4v4h-4v4h-4v-4h-4v-4h4v-4z" fill="white"/>
        </svg>
        <span class="medsuite-logo-text">
            <span class="medsuite-logo-med">Med</span><span class="medsuite-logo-suite">Suite</span>
        </span>
    </div>
</a>

<style>
    .medsuite-logo-link {
        text-decoration: none;
        display: inline-block;
    }

    .medsuite-logo-container {
        display: flex;
        align-items: center;
        gap: 0.625rem;
    }

    .medsuite-logo-icon {
        width: 2.25rem;
        height: 2.25rem;
        flex-shrink: 0;
    }

    .medsuite-logo-text {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        font-size: 1.375rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        line-height: 1;
    }

    .medsuite-logo-med {
        color: #0d9488;
    }

    .medsuite-logo-suite {
        color: #1e293b;
    }

    /* Hover effect */
    .medsuite-logo-link:hover .medsuite-logo-icon {
        transform: scale(1.05);
        transition: transform 0.2s ease;
    }

    .medsuite-logo-link:hover .medsuite-logo-med {
        color: #0f766e;
    }

    .medsuite-logo-link:hover .medsuite-logo-suite {
        color: #0d9488;
    }

    /* Size variants */
    .medsuite-logo-sm .medsuite-logo-icon {
        width: 1.75rem;
        height: 1.75rem;
    }

    .medsuite-logo-sm .medsuite-logo-text {
        font-size: 1.125rem;
    }

    .medsuite-logo-lg .medsuite-logo-icon {
        width: 3rem;
        height: 3rem;
    }

    .medsuite-logo-lg .medsuite-logo-text {
        font-size: 1.75rem;
    }
</style>
