@extends('master')

@section('title', $doctor->user->name . ' - Doctor Profile')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root {
    --navy: #060d1f;
    --navy-light: #0a1628;
    --teal: #00d4aa;
    --teal-dark: #00a88a;
    --teal-glow: rgba(0, 212, 170, 0.3);
    --offwhite: #e8efe7;
    --muted: rgba(232, 239, 231, 0.6);
    --card-bg: rgba(10, 22, 40, 0.85);
    --card-border: rgba(0, 212, 170, 0.15);
    --glass-bg: rgba(10, 22, 40, 0.6);
    --font-display: 'Cormorant Garamond', Georgia, serif;
    --font-body: 'DM Sans', -apple-system, sans-serif;
}

body {
    background: var(--navy) !important;
    color: var(--offwhite) !important;
    font-family: var(--font-body) !important;
}

/* Page Hero */
.page-hero {
    position: relative;
    background: var(--navy);
    padding: 4rem 0 3rem;
    overflow: hidden;
}

.page-hero::before {
    content: '';
    position: absolute;
    top: -120px;
    right: -80px;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(0, 212, 170, 0.12) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}

.page-hero::after {
    content: '';
    position: absolute;
    bottom: -100px;
    left: -60px;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(0, 212, 170, 0.08) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}

.hero-orb {
    position: absolute;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(0, 212, 170, 0.15), transparent 70%);
    pointer-events: none;
}

.hero-orb-1 { top: -60px; right: 15%; width: 300px; height: 300px; }
.hero-orb-2 { bottom: -80px; left: 10%; width: 250px; height: 250px; background: radial-gradient(circle, rgba(0, 180, 220, 0.1), transparent 70%); }

.hero-grid-overlay {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(0, 212, 170, 0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(0, 212, 170, 0.04) 1px, transparent 1px);
    background-size: 60px 60px;
    pointer-events: none;
}

/* Hero Content */
.page-hero .breadcrumb {
    background: transparent;
    padding: 0;
    margin-bottom: 2rem;
}

.page-hero .breadcrumb-item a {
    color: var(--teal);
    text-decoration: none;
    font-size: 0.9rem;
    transition: opacity 0.2s;
}

.page-hero .breadcrumb-item a:hover { opacity: 0.75; }

.page-hero .breadcrumb-item + .breadcrumb-item::before {
    color: var(--muted);
}

.page-hero .breadcrumb-item {
    color: var(--muted);
    font-size: 0.9rem;
}

.page-hero .breadcrumb-item.active { color: var(--offwhite); }

.hero-doc-card {
    display: flex;
    align-items: center;
    gap: 2rem;
    position: relative;
    z-index: 1;
}

.doc-avatar {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    border: 3px solid var(--teal);
    box-shadow: 0 0 30px var(--teal-glow);
    overflow: hidden;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--navy-light);
}

.doc-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.doc-avatar .avatar-initials {
    font-family: var(--font-display);
    font-size: 2.5rem;
    font-weight: 600;
    color: var(--teal);
}

.doc-info h1 {
    font-family: var(--font-display);
    font-size: 2.5rem;
    font-weight: 600;
    color: var(--offwhite);
    margin-bottom: 0.25rem;
    line-height: 1.1;
}

.doc-specialty {
    font-size: 1.1rem;
    color: var(--teal);
    margin-bottom: 0.75rem;
    font-weight: 500;
}

.rating-row {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.stars {
    display: flex;
    gap: 2px;
}

.star {
    color: var(--teal);
    font-size: 0.9rem;
}

.star.empty { color: rgba(0, 212, 170, 0.2); }

.rating-text {
    color: var(--muted);
    font-size: 0.9rem;
}

.rating-text span { color: var(--teal); font-weight: 600; }

.badge-verified {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: rgba(0, 212, 170, 0.15);
    border: 1px solid rgba(0, 212, 170, 0.3);
    color: var(--teal);
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.badge-verified i { font-size: 0.75rem; }

.hero-fee {
    text-align: right;
    position: relative;
    z-index: 1;
}

.hero-fee .fee-amount {
    font-family: var(--font-display);
    font-size: 2.2rem;
    font-weight: 700;
    color: var(--teal);
    line-height: 1;
}

.hero-fee .fee-label {
    color: var(--muted);
    font-size: 0.85rem;
    margin-top: 0.25rem;
}

/* Stats Row */
.stats-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: 16px;
    padding: 1.5rem;
    text-align: center;
    backdrop-filter: blur(12px);
    transition: border-color 0.3s;
}

.stat-card:hover { border-color: rgba(0, 212, 170, 0.35); }

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(0, 212, 170, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.75rem;
    color: var(--teal);
    font-size: 1.1rem;
}

.stat-value {
    font-family: var(--font-display);
    font-size: 1.6rem;
    font-weight: 600;
    color: var(--offwhite);
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stat-label {
    color: var(--muted);
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Content Cards */
.content-card {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: 20px;
    padding: 2rem;
    backdrop-filter: blur(12px);
    margin-bottom: 1.5rem;
}

.section-heading {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--offwhite);
    margin-bottom: 1.25rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--card-border);
    display: flex;
    align-items: center;
    gap: 0.6rem;
}

.section-heading i { color: var(--teal); font-size: 1.1rem; }

.bio-text {
    color: var(--muted);
    line-height: 1.8;
    font-size: 0.95rem;
}

/* Language badges */
.lang-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 1rem;
}

.lang-badge {
    background: rgba(0, 212, 170, 0.1);
    border: 1px solid rgba(0, 212, 170, 0.2);
    color: var(--teal);
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

/* Contact items */
.contact-list { display: flex; flex-direction: column; gap: 0; }

.contact-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.9rem 0;
    border-bottom: 1px solid var(--card-border);
}

.contact-row:last-child { border-bottom: none; }

.contact-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: rgba(0, 212, 170, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--teal);
    font-size: 0.85rem;
    flex-shrink: 0;
}

.contact-row span { color: var(--offwhite); font-size: 0.9rem; }

/* Schedule */
.schedule-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
}

.schedule-day {
    background: rgba(0, 212, 170, 0.05);
    border: 1px solid rgba(0, 212, 170, 0.1);
    border-radius: 12px;
    padding: 1rem;
}

.schedule-day .day-name {
    font-weight: 600;
    color: var(--offwhite);
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
}

.time-badge {
    display: inline-block;
    background: rgba(0, 212, 170, 0.1);
    border: 1px solid rgba(0, 212, 170, 0.2);
    color: var(--teal);
    padding: 0.25rem 0.6rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 500;
    margin: 0.15rem 0.1rem;
}

.day-unavailable {
    color: rgba(232, 239, 231, 0.3);
    font-size: 0.8rem;
}

/* Reviews */
.review-card {
    background: rgba(0, 212, 170, 0.04);
    border: 1px solid rgba(0, 212, 170, 0.1);
    border-radius: 12px;
    padding: 1.25rem;
    margin-bottom: 1rem;
}

.review-card:last-child { margin-bottom: 0; }

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.75rem;
}

.review-stars { display: flex; gap: 2px; }
.review-stars .star { font-size: 0.8rem; }

.review-date { color: var(--muted); font-size: 0.78rem; }

.review-card p {
    color: var(--muted);
    font-size: 0.9rem;
    line-height: 1.6;
    margin-bottom: 0.5rem;
}

.review-author {
    color: rgba(232, 239, 231, 0.4);
    font-size: 0.8rem;
}

/* Sidebar */
.sidebar-card {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: 20px;
    padding: 2rem;
    backdrop-filter: blur(12px);
    position: sticky;
    top: 1.5rem;
}

.sidebar-title {
    font-family: var(--font-display);
    font-size: 1.4rem;
    font-weight: 600;
    color: var(--offwhite);
    text-align: center;
    margin-bottom: 1.5rem;
}

.slot-date {
    font-weight: 600;
    color: var(--offwhite);
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.slot-date small { color: var(--muted); font-weight: 400; }

.slot-times {
    display: flex;
    flex-wrap: wrap;
    gap: 0.4rem;
    margin-bottom: 1.25rem;
}

.slot-btn {
    background: rgba(0, 212, 170, 0.08);
    border: 1px solid rgba(0, 212, 170, 0.2);
    color: var(--teal);
    padding: 0.35rem 0.75rem;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.slot-btn:hover {
    background: var(--teal);
    color: var(--navy);
}

.btn-teal {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    background: var(--teal);
    color: var(--navy);
    padding: 0.9rem 2rem;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.95rem;
    text-decoration: none;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
    width: 100%;
    letter-spacing: 0.02em;
}

.btn-teal:hover {
    background: #00e8bb;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 212, 170, 0.3);
    color: var(--navy);
}

.quick-actions { margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--card-border); }

.quick-action {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    border-radius: 10px;
    text-decoration: none;
    color: var(--muted);
    transition: all 0.2s;
    border: 1px solid transparent;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.quick-action:hover {
    background: rgba(0, 212, 170, 0.08);
    border-color: var(--card-border);
    color: var(--offwhite);
}

.quick-action i { color: var(--teal); width: 18px; text-align: center; }

.empty-state {
    text-align: center;
    padding: 2rem 1rem;
}

.empty-state i { font-size: 2.5rem; color: rgba(232, 239, 231, 0.2); margin-bottom: 1rem; }
.empty-state h4 { color: var(--offwhite); font-size: 1.1rem; margin-bottom: 0.5rem; }
.empty-state p { color: var(--muted); font-size: 0.85rem; }

/* Responsive */
@media (max-width: 991px) {
    .hero-doc-card { flex-direction: column; text-align: center; }
    .hero-fee { text-align: center; margin-top: 1rem; }
    .stats-row { grid-template-columns: repeat(3, 1fr); }
    .schedule-grid { grid-template-columns: 1fr; }
    .sidebar-card { position: static; margin-bottom: 1.5rem; }
}

@media (max-width: 767px) {
    .stats-row { grid-template-columns: 1fr; }
    .doc-avatar { width: 110px; height: 110px; }
    .doc-info h1 { font-size: 1.8rem; }
    .content-card { padding: 1.25rem; }
}

/* Main layout */
.ms-main { background: var(--navy) !important; }
#main-content { background: var(--navy) !important; }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="hero-orb hero-orb-1"></div>
    <div class="hero-orb hero-orb-2"></div>
    <div class="hero-grid-overlay"></div>
    <div class="container" style="position: relative; z-index: 1;">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('doctors.index') }}"><i class="fas fa-arrow-left me-2"></i>All Doctors</a></li>
                <li class="breadcrumb-item active">{{ $doctor->user->name }}</li>
            </ol>
        </nav>

        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="hero-doc-card">
                    <div class="doc-avatar">
                        @if($doctor->profile_image)
                            <img src="{{ asset('storage/' . $doctor->profile_image) }}" alt="{{ $doctor->user->name }}">
                        @else
                            <span class="avatar-initials">{{ strtoupper(substr($doctor->user->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <div class="doc-info">
                        <h1>Dr. {{ $doctor->user->name }}</h1>
                        <div class="doc-specialty">{{ $doctor->specialty->name }}</div>
                        <div class="rating-row">
                            <div class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star star{{ $i > floor($doctor->average_rating) ? ' empty' : '' }}"></i>
                                @endfor
                            </div>
                            <span class="rating-text"><span>{{ number_format($doctor->average_rating, 1) }}</span> ({{ $doctor->total_reviews }} reviews)</span>
                        </div>
                        @if($doctor->is_verified)
                            <span class="badge-verified"><i class="fas fa-check-circle"></i>Verified Doctor</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="hero-fee">
                    <div class="fee-amount">${{ number_format($doctor->consultation_fee / 100, 2) }}</div>
                    <div class="fee-label">Consultation Fee</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container" style="padding-top: 2rem; padding-bottom: 3rem;">
    <!-- Stats -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <div class="stat-value">{{ $doctor->appointment_duration }}<span style="font-size:0.9rem;opacity:0.6;">min</span></div>
            <div class="stat-label">Appointment Duration</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
            <div class="stat-value">{{ count($availableSlots) }}</div>
            <div class="stat-label">Available Days</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-language"></i></div>
            <div class="stat-value">{{ count($doctor->languages ?? ['English']) }}</div>
            <div class="stat-label">Languages</div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- About -->
            <div class="content-card">
                <h2 class="section-heading"><i class="fas fa-user-md"></i>About Dr. {{ explode(' ', $doctor->user->name)[1] ?? $doctor->user->name }}</h2>
                <p class="bio-text">{{ $doctor->bio }}</p>
                <div>
                    <div class="lang-badges">
                        @foreach($doctor->languages ?? ['English'] as $language)
                            <span class="lang-badge">{{ $language }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Contact -->
            <div class="content-card">
                <h2 class="section-heading"><i class="fas fa-phone-alt"></i>Contact Information</h2>
                <div class="contact-list">
                    @if($doctor->phone)
                        <div class="contact-row">
                            <div class="contact-icon"><i class="fas fa-phone"></i></div>
                            <span>{{ $doctor->phone }}</span>
                        </div>
                    @endif
                    <div class="contact-row">
                        <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                        <span>{{ $doctor->user->email }}</span>
                    </div>
                    <div class="contact-row">
                        <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <span>{{ $doctor->full_address }}</span>
                    </div>
                </div>
            </div>

            <!-- Schedule -->
            <div class="content-card">
                <h2 class="section-heading"><i class="fas fa-calendar-week"></i>Weekly Schedule</h2>
                @php
                    $daysOfWeek = ['monday'=>'Monday','tuesday'=>'Tuesday','wednesday'=>'Wednesday','thursday'=>'Thursday','friday'=>'Friday','saturday'=>'Saturday','sunday'=>'Sunday'];
                    $groupedSlots = $doctor->availabilitySlots->groupBy('day_of_week');
                @endphp
                <div class="schedule-grid">
                    @foreach($daysOfWeek as $day => $dayName)
                        <div class="schedule-day">
                            <div class="day-name">{{ $dayName }}</div>
                            @if($groupedSlots->has($day))
                                @foreach($groupedSlots[$day] as $timeSlot)
                                    <span class="time-badge">{{ date('g:i A', strtotime($timeSlot->start_time)) }} – {{ date('g:i A', strtotime($timeSlot->end_time)) }}</span>
                                @endforeach
                            @else
                                <span class="day-unavailable">Unavailable</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Reviews -->
            <div class="content-card">
                <div class="d-flex justify-content-between align-items-center mb-4" style="border-bottom: 1px solid var(--card-border); padding-bottom: 1rem; margin-bottom: 0;">
                    <h2 class="section-heading mb-0" style="border: none; padding: 0;"><i class="fas fa-star"></i>Patient Reviews</h2>
                    <a href="{{ route('doctors.reviews', $doctor) }}" class="btn-teal" style="width: auto; padding: 0.5rem 1.25rem; font-size: 0.8rem;">View All</a>
                </div>

                @if($doctor->approvedReviews->count() > 0)
                    @foreach($doctor->approvedReviews->take(3) as $review)
                        <div class="review-card">
                            <div class="review-header">
                                <div class="review-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star star{{ $i > $review->rating ? ' empty' : '' }}"></i>
                                    @endfor
                                </div>
                                <span class="review-date">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            @if($review->comment)
                                <p>{{ $review->comment }}</p>
                            @endif
                            <div class="review-author">— {{ $review->is_anonymous ? 'Anonymous Patient' : ($review->patient->name ?? 'Unknown Patient') }}</div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <i class="fas fa-comments"></i>
                        <h4>No Reviews Yet</h4>
                        <p>Be the first to review this doctor.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="sidebar-card">
                @auth
                    @if(count($availableSlots) > 0)
                        <h3 class="sidebar-title">Book Appointment</h3>
                        <p style="color: var(--muted); font-size: 0.85rem; text-align: center; margin-bottom: 1.25rem;">Next available slots:</p>
                        <div style="max-height: 300px; overflow-y: auto; margin-bottom: 1.5rem;">
                            @foreach($availableSlots as $date => $slots)
                                <div style="margin-bottom: 1rem; padding: 0.9rem; background: rgba(0,212,170,0.04); border: 1px solid rgba(0,212,170,0.1); border-radius: 12px;">
                                    <div class="slot-date">{{ \Carbon\Carbon::parse($date)->format('M j, Y') }} <small>({{ \Carbon\Carbon::parse($date)->format('l') }})</small></div>
                                    <div class="slot-times">
                                        @foreach($slots->take(6) as $timeSlot)
                                            <button class="slot-btn">{{ \Carbon\Carbon::parse($timeSlot['start_time'])->format('g:i A') }}</button>
                                        @endforeach
                                        @if($slots->count() > 6)
                                            <small style="color: var(--muted); align-self: center; font-size: 0.75rem;">+{{ $slots->count() - 6 }} more</small>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <a href="{{ route('appointments.create', $doctor) }}" class="btn-teal">
                            <i class="fas fa-calendar-plus"></i>Schedule Appointment
                        </a>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-calendar-times"></i>
                            <h4>No Available Slots</h4>
                            <p>Please check back later or contact the office directly.</p>
                        </div>
                    @endif
                @else
                    <div class="empty-state">
                        <i class="fas fa-user-lock"></i>
                        <h4>Login Required</h4>
                        <p style="margin-bottom: 1.5rem;">Please sign in to book an appointment</p>
                        <a href="{{ route('login') }}" class="btn-teal"><i class="fas fa-sign-in-alt"></i>Sign In to Book</a>
                    </div>
                @endauth

                <div class="quick-actions">
                    <h4 style="color: var(--muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.75rem;">Quick Actions</h4>
                    <a href="{{ route('doctors.reviews', $doctor) }}" class="quick-action">
                        <i class="fas fa-star"></i><span>Read Reviews</span>
                    </a>
                    <a href="mailto:{{ $doctor->user->email }}" class="quick-action">
                        <i class="fas fa-envelope"></i><span>Send Email</span>
                    </a>
                    @if($doctor->phone)
                        <a href="tel:{{ $doctor->phone }}" class="quick-action">
                            <i class="fas fa-phone"></i><span>Call Office</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
