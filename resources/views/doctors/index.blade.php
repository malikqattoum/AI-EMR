@extends('master')

@section('title', 'Find Doctors — MedSuite AI')

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
                Healthcare Professionals
            </div>
            <h1 class="page-hero-title">
                Find the right<br>
                <em>care for you</em>
            </h1>
            <p class="page-hero-sub">
                Browse our network of verified healthcare professionals. Book appointments, read reviews, and connect with doctors who meet your needs.
            </p>
        </div>
    </section>

    <!-- ═══════════ SEARCH & FILTERS ═══════════ -->
    <section class="page-filters-section">
        <div class="page-container">
            <div class="page-filters-card">
                <form method="GET" action="{{ route('doctors.index') }}" class="page-filters-form">

                    <!-- Search Bar -->
                    <div class="page-search-row">
                        <div class="page-search-wrap">
                            <i class="bi bi-search"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Search by doctor name or specialty..."
                                   class="page-search-input">
                        </div>
                        <button type="submit" class="page-btn-search">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>

                    <!-- Filters Row -->
                    <div class="page-filter-row">
                        <div class="page-filter-group">
                            <label>Specialty</label>
                            <div class="page-select-wrap">
                                <select name="specialty" class="page-select">
                                    <option value="">All Specialties</option>
                                    @foreach($specialties as $specialty)
                                        <option value="{{ $specialty->id }}" {{ request('specialty') == $specialty->id ? 'selected' : '' }}>
                                            {{ $specialty->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="bi bi-chevron-down page-select-arrow"></i>
                            </div>
                        </div>

                        <div class="page-filter-group">
                            <label>City</label>
                            <div class="page-select-wrap">
                                <select name="city" class="page-select">
                                    <option value="">All Cities</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                            {{ $city }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="bi bi-chevron-down page-select-arrow"></i>
                            </div>
                        </div>

                        <div class="page-filter-group">
                            <label>Language</label>
                            <div class="page-select-wrap">
                                <select name="language" class="page-select">
                                    <option value="">All Languages</option>
                                    @foreach($languages as $language)
                                        <option value="{{ $language }}" {{ request('language') == $language ? 'selected' : '' }}>
                                            {{ $language }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="bi bi-chevron-down page-select-arrow"></i>
                            </div>
                        </div>

                        <div class="page-filter-group">
                            <label>Min Rating</label>
                            <div class="page-select-wrap">
                                <select name="min_rating" class="page-select">
                                    <option value="">Any Rating</option>
                                    <option value="4" {{ request('min_rating') == '4' ? 'selected' : '' }}>4+ Stars</option>
                                    <option value="4.5" {{ request('min_rating') == '4.5' ? 'selected' : '' }}>4.5+ Stars</option>
                                </select>
                                <i class="bi bi-chevron-down page-select-arrow"></i>
                            </div>
                        </div>

                        <div class="page-filter-group">
                            <label>Sort By</label>
                            <div class="page-select-wrap">
                                <select name="sort_by" class="page-select">
                                    <option value="rating" {{ request('sort_by') == 'rating' ? 'selected' : '' }}>Rating</option>
                                    <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
                                    <option value="reviews" {{ request('sort_by') == 'reviews' ? 'selected' : '' }}>Reviews</option>
                                    <option value="fee" {{ request('sort_by') == 'fee' ? 'selected' : '' }}>Fee</option>
                                </select>
                                <i class="bi bi-chevron-down page-select-arrow"></i>
                            </div>
                        </div>

                        <button type="button" onclick="clearFilters()" class="page-btn-clear">
                            <i class="bi bi-x-lg"></i> Clear
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- ═══════════ RESULTS ═══════════ -->
    <section class="page-results-section">
        <div class="page-container">
            @if($doctors->total() > 0)
            <div class="page-results-count">
                Showing <strong>{{ $doctors->firstItem() ?? 0 }}–{{ $doctors->lastItem() ?? 0 }}</strong> of <strong>{{ $doctors->total() }}</strong> doctors
            </div>
            @endif

            <!-- Doctor Cards Grid -->
            <div class="page-doctors-grid">
                @forelse($doctors as $doctor)
                    <div class="page-doctor-card">
                        <!-- Doctor Image -->
                        <div class="page-doctor-img">
                            @if($doctor->profile_image)
                                <img src="{{ asset('storage/' . $doctor->profile_image) }}"
                                     alt="{{ $doctor->user->name }}">
                            @else
                                <div class="page-doctor-placeholder">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                            @endif
                            <div class="page-doctor-specialty">{{ $doctor->specialty->name }}</div>
                        </div>

                        <!-- Doctor Info -->
                        <div class="page-doctor-body">
                            <h3 class="page-doctor-name">{{ $doctor->user->name }}</h3>

                            <!-- Rating -->
                            <div class="page-doctor-rating">
                                <div class="page-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($doctor->average_rating))
                                            <i class="bi bi-star-fill"></i>
                                        @elseif($i - 0.5 <= $doctor->average_rating)
                                            <i class="bi bi-star-half"></i>
                                        @else
                                            <i class="bi bi-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="page-rating-val">{{ number_format($doctor->average_rating, 1) }}</span>
                                <span class="page-rating-count">({{ $doctor->total_reviews }} reviews)</span>
                            </div>

                            <!-- Meta -->
                            <div class="page-doctor-meta">
                                <div class="page-meta-item">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <span>{{ $doctor->city }}, {{ $doctor->state }}</span>
                                </div>
                                @if($doctor->languages)
                                <div class="page-meta-item">
                                    <i class="bi bi-translate"></i>
                                    <span>{{ implode(', ', $doctor->languages) }}</span>
                                </div>
                                @endif
                                <div class="page-meta-item page-meta-fee">
                                    <i class="bi bi-currency-dollar"></i>
                                    <span>{{ number_format($doctor->consultation_fee / 100, 2) }} / visit</span>
                                </div>
                            </div>

                            <!-- Bio -->
                            @if($doctor->bio)
                            <p class="page-doctor-bio">{{ $doctor->bio }}</p>
                            @endif

                            <!-- Actions -->
                            <div class="page-doctor-actions">
                                <a href="{{ route('doctors.show', $doctor) }}" class="page-btn-outline">
                                    View Profile
                                </a>
                                @auth
                                    <a href="{{ route('appointments.create', $doctor) }}" class="page-btn-teal">
                                        Book Now
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="page-btn-teal">
                                        Login to Book
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="page-empty-state">
                        <div class="page-empty-icon"><i class="bi bi-search"></i></div>
                        <h3>No doctors found</h3>
                        <p>Try adjusting your search criteria or filters.</p>
                        <button onclick="clearFilters()" class="page-btn-teal">
                            <i class="bi bi-arrow-clockwise"></i> Clear Filters
                        </button>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($doctors->hasPages())
                <div class="page-pagination">
                    {{ $doctors->links() }}
                </div>
            @endif
        </div>
    </section>

</div>

<script>
function clearFilters() {
    window.location.href = "{{ route('doctors.index') }}";
}
</script>

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
    min-height: 45vh;
    display: flex; align-items: center;
    overflow: hidden;
    padding: 7rem 2rem 4rem;
}
.page-hero-bg { position: absolute; inset: 0; pointer-events: none; }
.page-orb { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.5; }
.page-orb-1 {
    width: 600px; height: 600px;
    background: radial-gradient(circle, rgba(0,212,170,0.2) 0%, transparent 65%);
    top: -200px; right: -100px;
    animation: orbFloat 12s ease-in-out infinite;
}
.page-orb-2 {
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, transparent 65%);
    bottom: -100px; left: -100px;
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
    text-transform: uppercase; color: var(--teal); margin-bottom: 1.25rem;
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

.page-hero-sub {
    font-size: 1.05rem; color: var(--muted); line-height: 1.75;
    max-width: 500px; margin: 0 auto;
}

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   CONTAINER
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.page-container { max-width: 1100px; margin: 0 auto; }

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   FILTERS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.page-filters-section {
    padding: 0 2rem 3rem;
    margin-top: -1.5rem;
    position: relative; z-index: 3;
}
.page-filters-card {
    background: var(--navy-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 1.75rem;
    box-shadow: 0 20px 50px rgba(0,0,0,0.3);
}
.page-filters-form { display: flex; flex-direction: column; gap: 1rem; }

.page-search-row {
    display: flex; gap: 0.75rem; align-items: center;
}
.page-search-wrap {
    position: relative; flex: 1;
}
.page-search-wrap i {
    position: absolute; left: 1rem; top: 50%; transform: translateY(-50%);
    color: var(--muted); font-size: 0.9rem;
}
.page-search-input {
    width: 100%;
    padding: 0.8rem 1rem 0.8rem 2.75rem;
    background: var(--navy-input);
    border: 1px solid var(--border);
    border-radius: 12px;
    color: var(--offwhite);
    font-size: 0.9rem; font-family: var(--font-body);
    transition: all 0.2s; outline: none;
}
.page-search-input::placeholder { color: rgba(232,237,245,0.25); }
.page-search-input:focus {
    border-color: rgba(0,212,170,0.5);
    box-shadow: 0 0 0 3px rgba(0,212,170,0.08);
}
.page-btn-search {
    display: inline-flex; align-items: center; gap: 0.5rem;
    padding: 0.8rem 1.5rem;
    background: var(--teal); color: var(--navy);
    font-size: 0.875rem; font-weight: 600; font-family: var(--font-body);
    border: none; border-radius: 12px; cursor: pointer;
    white-space: nowrap;
    transition: all 0.2s;
}
.page-btn-search:hover { box-shadow: 0 0 20px rgba(0,212,170,0.3); }

.page-filter-row {
    display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;
}
.page-filter-group { flex: 1; min-width: 140px; }
.page-filter-group label {
    display: block; font-size: 0.75rem; font-weight: 500;
    color: var(--muted); margin-bottom: 0.4rem;
    text-transform: uppercase; letter-spacing: 0.05em;
}
.page-select-wrap { position: relative; }
.page-select {
    width: 100%;
    padding: 0.65rem 2.5rem 0.65rem 0.875rem;
    background: var(--navy-input);
    border: 1px solid var(--border);
    border-radius: 10px;
    color: var(--offwhite);
    font-size: 0.875rem; font-family: var(--font-body);
    transition: all 0.2s; outline: none;
    cursor: pointer; -webkit-appearance: none; appearance: none;
}
.page-select:focus { border-color: rgba(0,212,170,0.5); }
.page-select option, .page-select optgroup { background: #0c1633; color: var(--offwhite); }
.page-select-arrow {
    position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%);
    color: var(--muted); pointer-events: none; font-size: 0.7rem;
}
.page-btn-clear {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.65rem 1.25rem;
    background: transparent;
    border: 1px solid var(--border);
    border-radius: 10px;
    color: var(--muted);
    font-size: 0.875rem; font-family: var(--font-body);
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
}
.page-btn-clear:hover { border-color: var(--border-hi); color: var(--offwhite); }

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   RESULTS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.page-results-section { padding: 0 2rem 5rem; }
.page-results-count {
    font-size: 0.875rem; color: var(--muted);
    margin-bottom: 2rem;
}
.page-results-count strong { color: var(--offwhite); }

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   DOCTOR GRID
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.page-doctors-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.5rem;
}
.page-doctor-card {
    background: var(--navy-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    overflow: hidden;
    transition: border-color 0.3s, transform 0.3s;
}
.page-doctor-card:hover {
    border-color: rgba(0,212,170,0.3);
    transform: translateY(-4px);
}

.page-doctor-img {
    position: relative;
    height: 180px;
    background: linear-gradient(135deg, var(--navy-mid) 0%, var(--navy-card) 100%);
    overflow: hidden;
}
.page-doctor-img img {
    width: 100%; height: 100%;
    object-fit: cover;
    object-position: center top;
}
.page-doctor-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    color: var(--muted); font-size: 3rem;
}
.page-doctor-specialty {
    position: absolute;
    bottom: 0.75rem; left: 0.75rem;
    background: rgba(0,212,170,0.15);
    border: 1px solid rgba(0,212,170,0.25);
    color: var(--teal);
    font-size: 0.7rem; font-weight: 500;
    text-transform: uppercase; letter-spacing: 0.06em;
    padding: 0.25rem 0.6rem; border-radius: 50px;
    backdrop-filter: blur(8px);
}

.page-doctor-body { padding: 1.5rem; }

.page-doctor-name {
    font-family: var(--font-display);
    font-size: 1.2rem; font-weight: 600; color: var(--white);
    margin-bottom: 0.5rem;
}

.page-doctor-rating {
    display: flex; align-items: center; gap: 0.5rem;
    margin-bottom: 1rem;
}
.page-stars { display: flex; gap: 0.1rem; }
.page-stars i { color: var(--teal); font-size: 0.8rem; }
.page-stars i.bi-star { color: var(--border-hi); }
.page-rating-val { font-size: 0.8125rem; font-weight: 600; color: var(--white); }
.page-rating-count { font-size: 0.75rem; color: var(--muted); }

.page-doctor-meta {
    display: flex; flex-direction: column; gap: 0.4rem;
    margin-bottom: 1rem;
}
.page-meta-item {
    display: flex; align-items: center; gap: 0.4rem;
    font-size: 0.8125rem; color: var(--muted);
}
.page-meta-item i { color: var(--teal); font-size: 0.75rem; flex-shrink: 0; }
.page-meta-fee i { color: var(--teal); }
.page-meta-fee span {
    font-weight: 600; color: var(--offwhite);
    font-size: 0.875rem;
}

.page-doctor-bio {
    font-size: 0.8125rem; color: var(--muted); line-height: 1.6;
    margin-bottom: 1.25rem;
    display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
}

.page-doctor-actions {
    display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;
}
.page-btn-outline {
    display: flex; align-items: center; justify-content: center;
    padding: 0.65rem;
    border: 1px solid var(--border);
    border-radius: 10px;
    font-size: 0.8125rem; font-weight: 500; color: var(--muted);
    transition: all 0.2s;
}
.page-btn-outline:hover {
    border-color: var(--border-hi); color: var(--offwhite);
    background: var(--glass);
}
.page-btn-teal {
    display: flex; align-items: center; justify-content: center;
    padding: 0.65rem;
    background: var(--teal); color: var(--navy);
    border-radius: 10px;
    font-size: 0.8125rem; font-weight: 600;
    transition: all 0.2s;
}
.page-btn-teal:hover { box-shadow: 0 0 20px rgba(0,212,170,0.3); }

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   EMPTY STATE
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.page-empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 5rem 2rem;
    background: var(--navy-card);
    border: 1px solid var(--border);
    border-radius: 20px;
}
.page-empty-icon {
    font-size: 3rem; color: var(--muted); margin-bottom: 1rem;
}
.page-empty-state h3 {
    font-family: var(--font-display);
    font-size: 1.5rem; font-weight: 600; color: var(--white);
    margin-bottom: 0.5rem;
}
.page-empty-state p { font-size: 0.9rem; color: var(--muted); margin-bottom: 1.5rem; }

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   PAGINATION
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
.page-pagination {
    display: flex; justify-content: center; margin-top: 3rem;
}

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   RESPONSIVE
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
@media (max-width: 768px) {
    .page-filter-row { flex-direction: column; }
    .page-filter-group { min-width: 100%; }
    .page-search-row { flex-direction: column; }
    .page-btn-search { width: 100%; justify-content: center; }
}
@media (max-width: 640px) {
    .page-doctors-grid { grid-template-columns: 1fr; }
}
</style>
@endsection
