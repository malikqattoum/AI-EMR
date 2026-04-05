@props([
    'icon',
    'title',
    'description'
])

<div class="feature-card">
    <div class="feature-card-icon">
        <i class="{{ $icon }}"></i>
    </div>
    <h3 class="feature-card-title">{{ $title }}</h3>
    <p class="feature-card-description">{{ $description }}</p>
</div>

<style>
.feature-card {
    background-color: var(--color-white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-card);
    padding: var(--space-6);
    text-align: center;
    transition: all var(--transition-normal);
}

.feature-card:hover {
    box-shadow: var(--shadow-card-hover);
    transform: translateY(-4px);
}

.feature-card-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 64px;
    height: 64px;
    border-radius: var(--radius-full);
    background: linear-gradient(135deg, var(--color-teal-primary) 0%, var(--color-teal-primary-light) 100%);
    margin-bottom: var(--space-4);
    box-shadow: var(--shadow-teal);
}

.feature-card-icon i {
    font-size: var(--font-size-2xl);
    color: var(--color-white);
}

.feature-card-title {
    font-size: var(--font-size-lg);
    font-weight: var(--font-weight-semibold);
    color: var(--text-primary);
    margin-bottom: var(--space-2);
    line-height: var(--line-height-snug);
}

.feature-card-description {
    font-size: var(--font-size-sm);
    color: var(--text-secondary);
    line-height: var(--line-height-relaxed);
    margin-bottom: 0;
}
</style>
