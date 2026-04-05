@props([
    'stats' => []
])

<section class="stats-section">
    <div class="container">
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
    background-color: var(--color-gray-800);
    padding: var(--space-12) 0;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: var(--space-8);
}

.stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.stat-number {
    font-size: var(--font-size-4xl);
    font-weight: var(--font-weight-bold);
    color: var(--color-white);
    line-height: var(--line-height-tight);
    margin-bottom: var(--space-2);
}

.stat-label {
    font-size: var(--font-size-sm);
    font-weight: var(--font-weight-medium);
    color: var(--color-teal-100);
    text-transform: uppercase;
    letter-spacing: var(--letter-spacing-wide);
}

/* Tablet: 2-column layout */
@media (max-width: 1023px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--space-6);
    }
}

/* Mobile: 1-column layout */
@media (max-width: 639px) {
    .stats-section {
        padding: var(--space-10) 0;
    }

    .stats-grid {
        grid-template-columns: 1fr;
        gap: var(--space-6);
    }

    .stat-number {
        font-size: var(--font-size-3xl);
    }
}
</style>
