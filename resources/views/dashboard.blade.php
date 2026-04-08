@extends('layouts.doctor')

@section('title', 'Doctor Dashboard')

@push('styles')
<style>
/* ================================================
   DASHBOARD-SPECIFIC STYLES
   Note: Theme colors are now handled by global theme CSS files
   ================================================ */

/* Dashboard-specific layout styles */
.dashboard-container,
.container-fluid {
    /* Remove hardcoded background - let theme CSS handle it */
}

/* Card elements - theme-aware */
.card {
    border-radius: 16px !important;
}

/* Forms - theme-aware */
.form-control, .form-select {
    border-radius: 10px !important;
}

/* Dashboard-specific component styles */
.alert {
    border-radius: 14px !important;
    backdrop-filter: blur(10px);
}

/* Pagination styling */
.pagination .page-link {
    border-radius: 8px !important;
    margin: 0 2px;
}

/* Card hover effects */
.card:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease;
}

/* Table hover */
.table-hover tbody tr:hover {
    background-color: rgba(0,212,170,0.05) !important;
}

/* Modal styling */
.modal-content {
    border-radius: 16px !important;
}

/* Badge styling */
.badge {
    font-weight: 600;
    padding: 0.35em 0.65em;
}

/* Dashboard grid */
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

/* Stat cards */
.stat-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.5rem;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    line-height: 1;
}

.stat-label {
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

/* Quick actions */
.quick-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
}

/* Activity timeline */
.activity-timeline {
    position: relative;
    padding-left: 2rem;
}

.activity-timeline::before {
    content: '';
    position: absolute;
    left: 0.5rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: rgba(0,212,170,0.2);
}

.activity-item {
    position: relative;
    padding-bottom: 1.5rem;
}

.activity-item::before {
    content: '';
    position: absolute;
    left: -1.75rem;
    top: 0.25rem;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #00d4aa;
    border: 2px solid #fff;
}

/* Chart containers */
.chart-container {
    position: relative;
    min-height: 300px;
}

/* Loading states */
.loading-skeleton {
    background: linear-gradient(90deg, rgba(0,212,170,0.05) 25%, rgba(0,212,170,0.08) 50%, rgba(0,212,170,0.05) 75%);
    background-size: 200% 100%;
    animation: skeleton-loading 1.6s ease-in-out infinite;
    border-radius: 8px;
}

@keyframes skeleton-loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
</style>
@endpush

@push('styles')
@vite(['resources/css/dashboard-enhancements.css'])
<link rel="stylesheet" href="{{ asset('css/custom-openai.css') }}">
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom-dashboard.css') }}">
<style>
/* ================================================
   CSS VARIABLES - DESIGN TOKENS
   ================================================ */
:root {
    --navy: #0a1428;
    --navy-light: #111d35;
    --teal: #00d4aa;
    --teal-dark: #00b894;
    --offwhite: #e8ede7;
    --muted: rgba(232, 237, 231, 0.5);
    --card-bg: rgba(10, 20, 40, 0.6);
    --card-border: rgba(0, 212, 170, 0.12);
}

/* ================================================
   CLINICAL COMMAND CENTER — DASHBOARD THEME
   Deep navy base with teal glow accents
   ================================================ */

/* --- Global Text Override - Force all text to be visible --- */
.dashboard-container,
.dashboard-container * {
    color: #e8ede7 !important;
}

/* Specific element colors */
.dashboard-container strong,
.dashboard-container b,
.dashboard-container .fw-bold {
    color: #ffffff !important;
}

.dashboard-container .text-muted {
    color: rgba(232, 237, 231, 0.5) !important;
}

.dashboard-container .text-success {
    color: #00d4aa !important;
}

.dashboard-container .text-warning {
    color: #fbbf24 !important;
}

.dashboard-container .text-danger {
    color: #f87171 !important;
}

.dashboard-container .text-info {
    color: #60a5fa !important;
}

.dashboard-container .text-primary {
    color: #00d4aa !important;
}

/* Alert headings */
.alert h1, .alert h2, .alert h3, .alert h4, .alert h5, .alert h6,
.alert .alert-heading {
    color: inherit !important;
}

/* Alert text */
.alert p, .alert span, .alert div {
    color: inherit !important;
}

/* --- Force Colors on Specific Problem Elements --- */
.doctor-section-header h3,
.doctor-section-header p,
.quick-actions-card h4,
.quick-actions-title,
.dashboard-header h1,
.dashboard-header p {
    color: #e8ede7 !important;
}

/* Force all heading elements to light color */
h1, h2, h3, h4, h5, h6,
.h1, .h2, .h3, .h4, .h5, .h6 {
    color: #e8ede7 !important;
}

/* Force all card content to light color */
.card *,
.card-body *,
.card-header *,
.table-card *,
.stats-card *,
.quick-actions-card *,
.doctor-section-header * {
    color: #e8ede7 !important;
}

/* Except for specific colored elements */
.card .text-muted,
.card-body .text-muted,
.table-card .text-muted,
.stats-card .text-muted {
    color: rgba(232, 237, 231, 0.5) !important;
}

/* --- Atmospheric Background Grid --- */
.dashboard-container {
    position: relative;
    background: #060d1f !important;
}
.dashboard-container::before {
    content: '';
    position: fixed;
    inset: 0;
    background-image:
        linear-gradient(rgba(0,212,170,0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(0,212,170,0.03) 1px, transparent 1px);
    background-size: 48px 48px;
    pointer-events: none;
    z-index: 0;
}

/* --- Hero Dashboard Header --- */
.dashboard-header {
    position: relative;
    background: linear-gradient(135deg, var(--navy) 0%, rgba(0,212,170,0.08) 50%, var(--navy) 100%) !important;
    border: 1px solid rgba(0,212,170,0.15) !important;
    border-radius: 20px !important;
    padding: 3rem 3rem 2.5rem !important;
    margin-bottom: 2.5rem !important;
    overflow: hidden;
}
.dashboard-header::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--teal), transparent);
    animation: headerGlow 3s ease-in-out infinite;
}
.dashboard-header::after {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at 20% 50%, rgba(0,212,170,0.06) 0%, transparent 60%);
    pointer-events: none;
}
@keyframes headerGlow {
    0%, 100% { opacity: 0.4; transform: scaleX(0.8); }
    50% { opacity: 1; transform: scaleX(1); }
}
.dashboard-header .header-content { position: relative; z-index: 1; }
.dashboard-header h1 {
    font-size: clamp(2rem, 4vw, 3rem) !important;
    font-weight: 800 !important;
    letter-spacing: -0.03em;
    background: linear-gradient(135deg, var(--offwhite) 30%, var(--teal) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: fadeSlideUp 0.6s ease-out both;
}
.dashboard-header p {
    color: var(--muted) !important;
    font-size: 1.1rem;
    animation: fadeSlideUp 0.6s ease-out 0.1s both;
}

/* --- Header Avatar --- */
.header-avatar {
    flex-shrink: 0;
}
.avatar-circle {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--teal) 0%, rgba(0,212,170,0.7) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--navy);
    box-shadow: 0 8px 24px rgba(0,212,170,0.3);
    border: 3px solid rgba(255,255,255,0.1);
    animation: fadeSlideUp 0.6s ease-out both;
}

/* --- Header Badges --- */
.header-actions {
    animation: fadeSlideUp 0.6s ease-out 0.2s both;
}
.header-badge {
    background: rgba(10, 20, 40, 0.6);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(0,212,170,0.15);
    border-radius: 12px;
    padding: 0.5rem 1rem;
    font-size: 0.85rem;
    font-weight: 500;
    color: var(--offwhite);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}
.header-badge:hover {
    border-color: rgba(0,212,170,0.3);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,212,170,0.15);
}
.header-badge.success {
    border-color: rgba(0,212,170,0.3);
    background: rgba(0,212,170,0.1);
}
.header-badge i {
    color: var(--teal);
}
.header-badge.success i {
    color: #10b981;
}

/* --- Staggered Entrance Animations --- */
@keyframes fadeSlideUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes fadeIn {
    from { opacity: 0; }
    to   { opacity: 1; }
}
@keyframes tealPulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(0,212,170,0); }
    50%       { box-shadow: 0 0 20px 2px rgba(0,212,170,0.15); }
}
.animate-in { animation: fadeSlideUp 0.5s ease-out both; }
.animate-in-1 { animation-delay: 0.05s; }
.animate-in-2 { animation-delay: 0.10s; }
.animate-in-3 { animation-delay: 0.15s; }
.animate-in-4 { animation-delay: 0.20s; }
.animate-in-5 { animation-delay: 0.25s; }
.animate-in-6 { animation-delay: 0.30s; }

/* --- Glassmorphic Stats Cards --- */
.stats-card {
    background: rgba(10, 20, 40, 0.6) !important;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(0,212,170,0.12) !important;
    border-radius: 20px !important;
    padding: 2rem 1.75rem !important;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
.stats-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, transparent, var(--teal), transparent);
    opacity: 0.6;
    transition: opacity 0.3s ease;
}
.stats-card::after {
    content: '';
    position: absolute;
    top: -50%; right: -30%;
    width: 200px; height: 200px;
    background: radial-gradient(circle, rgba(0,212,170,0.06) 0%, transparent 70%);
    pointer-events: none;
    transition: opacity 0.3s ease;
    opacity: 0;
}
.stats-card:hover {
    transform: translateY(-8px) scale(1.02);
    border-color: rgba(0,212,170,0.35) !important;
    box-shadow: 0 12px 48px rgba(0,212,170,0.15), 0 0 0 1px rgba(0,212,170,0.08) !important;
    background: rgba(10, 20, 40, 0.75) !important;
}
.stats-card:hover::before {
    opacity: 1;
}
.stats-card:hover::after {
    opacity: 1;
}
.stats-icon {
    width: 64px; height: 64px;
    border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem;
    background: linear-gradient(135deg, rgba(0,212,170,0.2) 0%, rgba(0,212,170,0.08) 100%) !important;
    border: 2px solid rgba(0,212,170,0.25) !important;
    color: var(--teal) !important;
    margin-bottom: 1.25rem;
    box-shadow: 0 4px 20px rgba(0,212,170,0.15);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}
.stats-icon::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 50%);
    opacity: 0;
    transition: opacity 0.3s ease;
}
.stats-card:hover .stats-icon {
    box-shadow: 0 8px 32px rgba(0,212,170,0.3);
    transform: scale(1.1) rotate(-5deg);
    border-color: rgba(0,212,170,0.4) !important;
}
.stats-card:hover .stats-icon::before {
    opacity: 1;
}
.stats-number {
    font-size: 2.8rem !important;
    font-weight: 800 !important;
    letter-spacing: -0.04em;
    background: linear-gradient(135deg, var(--offwhite) 0%, var(--teal) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1;
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
}
.stats-card:hover .stats-number {
    transform: scale(1.05);
}
.stats-label {
    font-size: 0.8rem !important;
    font-weight: 600 !important;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--muted) !important;
    margin-bottom: 0.75rem;
}

/* --- Trend Indicators --- */
.trend-indicator {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: 8px;
    margin-top: 0.5rem;
}
.trend-indicator.up {
    background: rgba(16, 185, 129, 0.15);
    color: #10b981;
}
.trend-indicator.down {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
}
.trend-indicator.neutral {
    background: rgba(107, 114, 128, 0.15);
    color: #9ca3af;
}

/* --- Quick Actions Card --- */
.quick-actions-card {
    background: rgba(10, 20, 40, 0.5) !important;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(0,212,170,0.12) !important;
    border-radius: 20px !important;
    padding: 2rem !important;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}
.quick-actions-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, rgba(251,191,36,0.5), transparent);
}
.quick-actions-card:hover {
    border-color: rgba(0,212,170,0.2) !important;
    box-shadow: 0 8px 32px rgba(0,212,170,0.08) !important;
}
.quick-actions-title {
    font-size: 1.25rem !important;
    font-weight: 700 !important;
    color: var(--offwhite) !important;
    margin-bottom: 1.5rem !important;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.quick-actions-title i {
    color: #fbbf24;
    animation: pulse 2s ease-in-out infinite;
}
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

/* --- Quick Action Buttons --- */
.quick-action-btn {
    background: rgba(10, 20, 40, 0.6) !important;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(0,212,170,0.15) !important;
    border-radius: 14px !important;
    padding: 1rem 1.5rem !important;
    font-weight: 600;
    color: var(--offwhite) !important;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    min-width: 200px;
}
.quick-action-btn::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(0,212,170,0.1) 0%, transparent 50%);
    opacity: 0;
    transition: opacity 0.3s ease;
}
.quick-action-btn i {
    font-size: 1.2rem;
    color: var(--teal);
    transition: all 0.3s ease;
}
.quick-action-btn:hover {
    transform: translateY(-4px);
    border-color: rgba(0,212,170,0.3) !important;
    box-shadow: 0 8px 24px rgba(0,212,170,0.15) !important;
    color: var(--offwhite) !important;
    text-decoration: none;
    background: rgba(10, 20, 40, 0.8) !important;
}
.quick-action-btn:hover::before {
    opacity: 1;
}
.quick-action-btn:hover i {
    transform: scale(1.2) rotate(-5deg);
    color: var(--teal);
}
.quick-action-btn:active {
    transform: translateY(-2px);
}
.quick-action-btn.primary {
    background: linear-gradient(135deg, rgba(0,212,170,0.2) 0%, rgba(0,212,170,0.1) 100%) !important;
    border-color: rgba(0,212,170,0.3) !important;
}
.quick-action-btn.primary:hover {
    background: linear-gradient(135deg, rgba(0,212,170,0.3) 0%, rgba(0,212,170,0.15) 100%) !important;
}

/* --- Glassmorphic Table Cards --- */
.table-card {
    background: rgba(10, 20, 40, 0.5) !important;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(0,212,170,0.1) !important;
    border-radius: 20px !important;
    padding: 2rem !important;
    margin-bottom: 1.5rem;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    color: var(--offwhite) !important;
}
.table-card h1, .table-card h2, .table-card h3, .table-card h4, .table-card h5, .table-card h6,
.table-card p, .table-card span, .table-card label { color: var(--offwhite) !important; }
.table-card .text-muted { color: var(--muted) !important; }
.table-pagination { color: var(--muted) !important; }
.table-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, rgba(0,212,170,0.3), transparent);
    opacity: 0.6;
    transition: opacity 0.3s ease;
}
.table-card:hover {
    border-color: rgba(0,212,170,0.25) !important;
    box-shadow: 0 12px 40px rgba(0,0,0,0.25), 0 0 30px rgba(0,212,170,0.08) !important;
    transform: translateY(-4px);
}
.table-card:hover::before {
    opacity: 1;
}
.table-title {
    font-size: 1.15rem !important;
    font-weight: 700 !important;
    letter-spacing: 0.02em;
    color: var(--offwhite) !important;
    margin-bottom: 1.5rem !important;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(0,212,170,0.1);
}
.table-title i { 
    color: var(--teal) !important;
    font-size: 1.2rem;
}
.table-title .badge {
    margin-left: auto;
    font-size: 0.75rem;
    padding: 0.4rem 0.8rem;
    border-radius: 10px;
    background: rgba(0,212,170,0.15);
    color: var(--teal);
    border: 1px solid rgba(0,212,170,0.2);
}

/* --- Enhanced Custom Table --- */
.custom-table { 
    border-collapse: separate; 
    border-spacing: 0; 
    width: 100%; 
    overflow: hidden;
    border-radius: 12px;
}
.custom-table thead th {
    background: linear-gradient(180deg, rgba(0,212,170,0.08) 0%, rgba(0,212,170,0.04) 100%) !important;
    border-bottom: 2px solid rgba(0,212,170,0.15) !important;
    font-weight: 700 !important;
    text-transform: uppercase;
    font-size: 0.7rem !important;
    letter-spacing: 0.1em;
    color: var(--muted) !important;
    padding: 1rem 1.25rem !important;
    position: sticky;
    top: 0;
    z-index: 1;
}
.custom-table tbody tr {
    border-bottom: 1px solid rgba(255,255,255,0.04) !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}
.custom-table tbody tr::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: var(--teal);
    transform: scaleY(0);
    transition: transform 0.3s ease;
}
.custom-table tbody tr:hover {
    background: linear-gradient(90deg, rgba(0,212,170,0.08) 0%, transparent 100%) !important;
    transform: translateX(4px);
}
.custom-table tbody tr:hover::before {
    transform: scaleY(1);
}
.custom-table tbody tr:last-child { border-bottom: none !important; }
.custom-table td {
    padding: 1.125rem 1.25rem !important;
    vertical-align: middle;
    color: var(--offwhite) !important;
    transition: all 0.2s ease;
}
.custom-table tbody tr:hover td:first-child {
    padding-left: calc(1.25rem + 3px);
}

/* --- Custom Buttons --- */
.btn-custom-secondary {
    background: rgba(255,255,255,0.04) !important;
    border: 1px solid rgba(255,255,255,0.08) !important;
    border-radius: 12px !important;
    color: var(--muted) !important;
    padding: 0.5rem 1.2rem;
    font-weight: 500;
    transition: all 0.25s ease;
    display: inline-flex;
    align-items: center;
    text-decoration: none;
}
.btn-custom-secondary:hover {
    background: rgba(255,255,255,0.08) !important;
    border-color: rgba(0,212,170,0.2) !important;
    color: var(--teal) !important;
    transform: translateY(-1px);
}
.btn-primary-custom {
    background: linear-gradient(135deg, var(--teal) 0%, rgba(0,212,170,0.7) 100%) !important;
    border: none !important;
    border-radius: 12px !important;
    padding: 0.5rem 1.2rem;
    font-weight: 600;
    color: var(--navy) !important;
    transition: all 0.25s ease;
    position: relative;
    overflow: hidden;
}
.btn-primary-custom::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, transparent 50%);
    opacity: 0;
    transition: opacity 0.25s ease;
}
.btn-primary-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,212,170,0.3) !important;
    color: var(--navy) !important;
}
.btn-primary-custom:hover::before { opacity: 1; }

.btn-secondary-custom {
    background: rgba(255,255,255,0.04) !important;
    border: 1px solid rgba(255,255,255,0.08) !important;
    border-radius: 12px !important;
    color: var(--muted) !important;
    padding: 0.5rem 1.2rem;
    font-weight: 500;
    transition: all 0.25s ease;
}
.btn-secondary-custom:hover {
    background: rgba(255,255,255,0.08) !important;
    border-color: rgba(0,212,170,0.2) !important;
    color: var(--teal) !important;
    transform: translateY(-1px);
}

/* --- Empty State --- */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
}
.empty-state i {
    font-size: 3.5rem;
    margin-bottom: 1rem;
    color: rgba(0,212,170,0.2);
}
.empty-state h5 {
    color: var(--offwhite) !important;
    font-weight: 700;
    margin-bottom: 0.5rem;
}
.empty-state p { color: var(--muted) !important; }

/* --- Appointment & Review Items (override external CSS) --- */
.appointment-card {
    background: linear-gradient(135deg, rgba(10, 20, 40, 0.5) 0%, rgba(10, 20, 40, 0.4) 100%) !important;
    border: 1px solid rgba(0,212,170,0.12) !important;
    border-radius: 16px !important;
    color: var(--offwhite) !important;
    padding: 1.25rem !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    position: relative;
    overflow: hidden;
}
.appointment-card::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: linear-gradient(180deg, var(--teal) 0%, rgba(0,212,170,0.5) 100%);
    border-radius: 4px 0 0 4px;
    transform: scaleY(0);
    transition: transform 0.3s ease;
}
.appointment-card:hover {
    transform: translateY(-4px) scale(1.01);
    border-color: rgba(0,212,170,0.25) !important;
    box-shadow: 0 8px 32px rgba(0,212,170,0.12), 0 0 0 1px rgba(0,212,170,0.08) !important;
    background: linear-gradient(135deg, rgba(10, 20, 40, 0.7) 0%, rgba(10, 20, 40, 0.6) 100%) !important;
}
.appointment-card:hover::before {
    transform: scaleY(1);
}
.appointment-item {
    background: rgba(10, 20, 40, 0.5) !important;
    border: 1px solid rgba(0,212,170,0.12) !important;
    border-radius: 14px !important;
    color: var(--offwhite) !important;
    padding: 1rem !important;
    margin-bottom: 0.75rem;
    transition: all 0.3s ease;
}
.appointment-item:hover {
    background: rgba(10, 20, 40, 0.7) !important;
    border-color: rgba(0,212,170,0.2) !important;
    transform: translateX(4px);
    box-shadow: 0 4px 16px rgba(0,212,170,0.08);
}
.review-item {
    background: rgba(10, 20, 40, 0.5) !important;
    border: 1px solid rgba(0,212,170,0.12) !important;
    border-radius: 14px !important;
    color: var(--offwhite) !important;
    padding: 1.25rem !important;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}
.review-item:hover {
    background: rgba(10, 20, 40, 0.7) !important;
    border-color: rgba(0,212,170,0.2) !important;
    box-shadow: 0 6px 20px rgba(0,212,170,0.1);
}
.review-item strong, .review-item .text-warning { color: var(--offwhite) !important; }

/* --- Time Circle Enhancement --- */
.time-circle {
    background: linear-gradient(135deg, var(--teal) 0%, rgba(0,212,170,0.8) 100%) !important;
    border-radius: 50% !important;
    box-shadow: 0 4px 16px rgba(0,212,170,0.3) !important;
    transition: all 0.3s ease;
}
.appointment-card:hover .time-circle {
    transform: scale(1.1);
    box-shadow: 0 6px 24px rgba(0,212,170,0.4) !important;
}

/* --- Doctor Dashboard Content Cards --- */
.card {
    background: rgba(10, 20, 40, 0.4) !important;
    border: 1px solid rgba(0,212,170,0.12) !important;
}
.card-header { color: var(--offwhite) !important; }
.card-body { color: var(--offwhite) !important; }
.card-body strong { color: var(--offwhite) !important; }
.card-body .badge { background: rgba(0,212,170,0.15) !important; color: var(--offwhite) !important; }
.patient-info-card h6 { color: var(--muted) !important; }
.patient-info-card .fs-5 { color: var(--offwhite) !important; }
.response-text { color: var(--offwhite) !important; }
.modal-body { color: var(--offwhite) !important; }
.modal-body .text-muted { color: var(--muted) !important; }
.list-group-item {
    background: rgba(10, 20, 40, 0.3) !important;
    border-color: rgba(0,212,170,0.08) !important;
    color: var(--offwhite) !important;
}
.list-group-item strong { color: var(--offwhite) !important; }

/* --- Sort Links --- */
.sort-link {
    color: var(--teal) !important;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    transition: color 0.2s ease;
}
.sort-link:hover { color: var(--offwhite) !important; }

/* --- Progress bars --- */
.progress {
    height: 6px !important;
    border-radius: 3px !important;
    background: rgba(255,255,255,0.05) !important;
    overflow: hidden;
}
.progress-bar {
    border-radius: 3px;
    position: relative;
    overflow: visible;
}
.progress-bar::after {
    content: '';
    position: absolute;
    top: -2px; right: -1px;
    width: 6px; height: 6px;
    border-radius: 50%;
    background: currentColor;
    opacity: 0.6;
}

/* --- Doctor Dashboard Section Header --- */
.doctor-section-header {
    background: linear-gradient(135deg, rgba(0,212,170,0.06) 0%, rgba(0,212,170,0.02) 100%) !important;
    border: 1px solid rgba(0,212,170,0.12) !important;
    border-radius: 16px;
    padding: 1.75rem 2rem;
    position: relative;
    overflow: hidden;
}
.doctor-section-header::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(0,212,170,0.3), transparent);
}
.doctor-section-header h3 {
    background: linear-gradient(135deg, var(--offwhite) 40%, var(--teal) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* --- Badges in tables --- */
.custom-table .badge {
    font-size: 0.7rem !important;
    letter-spacing: 0.03em;
}

/* --- Alerts / subscription banners --- */
.alert {
    border-radius: 14px !important;
    border: none !important;
    backdrop-filter: blur(10px);
}

/* --- Responsive Design --- */
@media (max-width: 1200px) {
    .stats-card {
        padding: 1.75rem !important;
    }
    .stats-icon {
        width: 56px;
        height: 56px;
        font-size: 1.4rem;
    }
    .stats-number {
        font-size: 2.4rem !important;
    }
}

@media (max-width: 992px) {
    .dashboard-header {
        padding: 2rem 1.5rem !important;
    }
    .avatar-circle {
        width: 56px;
        height: 56px;
        font-size: 1.5rem;
    }
    .dashboard-header h1 {
        font-size: 1.75rem !important;
    }
    .stats-card {
        padding: 1.5rem !important;
    }
    .stats-number {
        font-size: 2.2rem !important;
    }
    .table-card {
        padding: 1.5rem !important;
    }
    .quick-action-btn {
        min-width: 180px;
        padding: 0.875rem 1.25rem !important;
    }
}

@media (max-width: 768px) {
    .dashboard-header {
        padding: 1.5rem 1.25rem !important;
    }
    .header-content {
        flex-direction: column;
        gap: 1rem;
    }
    .avatar-circle {
        width: 48px;
        height: 48px;
        font-size: 1.3rem;
    }
    .dashboard-header h1 {
        font-size: 1.5rem !important;
    }
    .dashboard-header p {
        font-size: 0.95rem !important;
    }
    .header-badge {
        font-size: 0.75rem;
        padding: 0.4rem 0.75rem;
    }
    .stats-card {
        padding: 1.25rem !important;
        margin-bottom: 1rem;
    }
    .stats-icon {
        width: 48px;
        height: 48px;
        font-size: 1.2rem;
        margin-bottom: 1rem;
    }
    .stats-number {
        font-size: 2rem !important;
    }
    .stats-label {
        font-size: 0.7rem !important;
    }
    .table-card {
        padding: 1.25rem !important;
    }
    .table-title {
        font-size: 1rem !important;
    }
    .quick-actions-card {
        padding: 1.5rem !important;
    }
    .quick-action-btn {
        min-width: 100%;
        padding: 0.875rem 1rem !important;
    }
    .custom-table td {
        padding: 0.875rem 1rem !important;
        font-size: 0.9rem;
    }
    .custom-table thead th {
        padding: 0.875rem 1rem !important;
        font-size: 0.65rem !important;
    }
}

@media (max-width: 576px) {
    .dashboard-container {
        padding: 0.75rem !important;
    }
    .dashboard-header {
        padding: 1.25rem 1rem !important;
        border-radius: 16px !important;
    }
    .dashboard-header h1 {
        font-size: 1.35rem !important;
    }
    .d-flex.gap-3 {
        gap: 0.75rem !important;
    }
    .stats-card {
        padding: 1rem !important;
        border-radius: 16px !important;
    }
    .stats-icon {
        width: 44px;
        height: 44px;
        font-size: 1.1rem;
    }
    .stats-number {
        font-size: 1.75rem !important;
    }
    .stats-label {
        font-size: 0.65rem !important;
        letter-spacing: 0.05em;
    }
    .table-card {
        padding: 1rem !important;
        border-radius: 16px !important;
    }
    .quick-actions-card {
        padding: 1.25rem !important;
        border-radius: 16px !important;
    }
    .trend-indicator {
        font-size: 0.7rem;
        padding: 0.2rem 0.4rem;
    }
}

/* --- Scrollbar Styling --- */
::-webkit-scrollbar {
    width: 10px;
    height: 10px;
}
::-webkit-scrollbar-track {
    background: rgba(10, 20, 40, 0.3);
    border-radius: 5px;
}
::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, var(--teal) 0%, rgba(0,212,170,0.6) 100%);
    border-radius: 5px;
    border: 2px solid rgba(10, 20, 40, 0.5);
}
::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(180deg, rgba(0,212,170,0.9) 0%, var(--teal) 100%);
}

/* --- Loading Skeleton Animation --- */
@keyframes shimmer {
    0% { background-position: -1000px 0; }
    100% { background-position: 1000px 0; }
}
.skeleton {
    background: linear-gradient(90deg, 
        rgba(10, 20, 40, 0.4) 25%, 
        rgba(0,212,170,0.08) 50%, 
        rgba(10, 20, 40, 0.4) 75%
    );
    background-size: 1000px 100%;
    animation: shimmer 2s infinite;
    border-radius: 8px;
}

/* --- Floating Action Button Pulse --- */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}
.floating {
    animation: float 3s ease-in-out infinite;
}

/* ================================================
   FINAL COMPREHENSIVE TEXT COLOR OVERRIDES
   This section ensures ALL text is visible
   ================================================ */

/* Universal text override - highest priority */
[class*="card"] *,
[class*="header"] *,
[class*="title"] *,
[class*="heading"] *,
[class*="label"] *,
[class*="text"] * {
    color: #e8ede7 !important;
}

/* Specific component text colors */
.dashboard-header h1,
.dashboard-header .h1,
.dashboard-header p,
.dashboard-header span,
.dashboard-header div {
    color: #e8ede7 !important;
}

.doctor-section-header h3,
.doctor-section-header .h3,
.doctor-section-header p,
.doctor-section-header span {
    color: #e8ede7 !important;
}

.quick-actions-card h4,
.quick-actions-card .h4,
.quick-actions-title,
.quick-actions-title span {
    color: #e8ede7 !important;
}

.table-card h1,
.table-card h2,
.table-card h3,
.table-card h4,
.table-card h5,
.table-card h6,
.table-card p,
.table-card span,
.table-card div,
.table-card label {
    color: #e8ede7 !important;
}

.stats-card .stats-number,
.stats-card .stats-label,
.stats-card p,
.stats-card span,
.stats-card div {
    color: #e8ede7 !important;
}

.stats-card .stats-label {
    color: rgba(232, 237, 231, 0.5) !important;
}

/* Table content */
.custom-table td,
.custom-table th,
.custom-table tbody tr td,
.custom-table thead tr th {
    color: #e8ede7 !important;
}

/* Card content */
.card-body p,
.card-body span,
.card-body div,
.card-body strong,
.card-body b {
    color: #e8ede7 !important;
}

/* Appointment cards */
.appointment-card p,
.appointment-card span,
.appointment-card div,
.appointment-card strong {
    color: #e8ede7 !important;
}

/* Review items */
.review-item p,
.review-item span,
.review-item div,
.review-item strong {
    color: #e8ede7 !important;
}

/* Muted text exception */
.text-muted {
    color: rgba(232, 237, 231, 0.5) !important;
}

/* Icon colors in headers */
.dashboard-header i,
.doctor-section-header i,
.table-title i {
    color: #00d4aa !important;
}

/* Button text */
.btn span,
.btn i {
    color: inherit !important;
}

/* Badge text */
.badge span,
.badge {
    color: #0a1428 !important;
}

/* Alert content */
.alert p,
.alert span,
.alert strong,
.alert div {
    color: inherit !important;
}
</style>

<!-- Dashboard-Specific Styles (Theme-Aware) -->
<style>
/* Dashboard container spacing - colors handled by global theme CSS */
.dashboard-container {
    /* Theme colors applied automatically */
}

/* Stats text - inherits from theme */
.dashboard-container .stats-number {
    font-size: 2.5rem;
    font-weight: 700;
    line-height: 1;
}

.dashboard-container .stats-label {
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

/* Quick actions text */
.quick-actions-title {
    font-weight: 600;
}

/* Header text */
.dashboard-header h1,
.dashboard-header p {
    /* Inherits theme colors */
}

/* Doctor section header */
.doctor-section-header h3,
.doctor-section-header p {
    /* Inherits theme colors */
}

/* Table card text */
.table-title {
    font-weight: 600;
}

/* Icon sizes */
.dashboard-container i {
    font-size: inherit;
}

/* Card transitions */
.dashboard-container .card {
    transition: all 0.3s ease;
}
</style>
@endpush

@section('content')
<div class="dashboard-container">
    <div class="container-fluid px-3 px-md-4">
        <!-- Enhanced Page Header -->
        <header class="dashboard-header">
            <div class="header-content">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="header-avatar">
                                @auth
                                    <div class="avatar-circle">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endauth
                            </div>
                            <div>
                                <h1 class="h1 mb-0" style="color: #e8ede7 !important;">
                                    @auth
                                        <span style="color: #e8ede7 !important;">Welcome back, {{ Auth::user()->first_name ?? Auth::user()->name }}</span>
                                    @else
                                        <span style="color: #e8ede7 !important;">Dashboard</span>
                                    @endauth
                                </h1>
                                <p class="mb-0 mt-1" style="color: #e8ede7 !important;">
                                    <i class="fas fa-calendar-day me-1" style="color: #00d4aa !important;"></i>
                                    <span style="color: #e8ede7 !important;">{{ now()->format('l, F j, Y') }}</span>
                                    <span class="mx-2" style="color: rgba(232, 237, 231, 0.5) !important;">•</span>
                                    <i class="fas fa-clock me-1" style="color: #00d4aa !important;"></i>
                                    <span id="current-time" style="color: #e8ede7 !important;">{{ now()->format('g:i A') }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="header-actions d-flex gap-2 flex-wrap">
                        @auth
                            <div class="header-badge" style="color: #e8ede7 !important;">
                                <i class="fas fa-bell me-1" style="color: #00d4aa !important;"></i>
                                <span style="color: #e8ede7 !important;">3 Notifications</span>
                            </div>
                            <div class="header-badge success" style="color: #e8ede7 !important;">
                                <i class="fas fa-check-circle me-1" style="color: #10b981 !important;"></i>
                                <span style="color: #e8ede7 !important;">System Online</span>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        @auth
            <!-- Subscription CTA (no free trial) -->
            @if(isset($trialInfo) && $trialInfo['is_in_trial'])
                <!-- If some legacy users still in trial, show a neutral banner -->
                <div class="alert alert-info alert-dismissible fade show" role="alert" style="border-radius: 20px; border: none; box-shadow: 0 8px 25px rgba(13, 202, 240, 0.2);">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-info-circle fa-2x text-info"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="alert-heading mb-2">
                                <i class="fas fa-clock me-2"></i>Trial Period Active
                            </h5>
                            <p class="mb-2">Some accounts may still have an active trial. You can subscribe anytime to Monthly or Yearly.</p>
                            <div class="d-flex gap-2">
                                <a href="{{ route('subscription.pricing') }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-credit-card me-1"></i>Choose Monthly or Yearly
                                </a>
                                <a href="{{ route('subscription.manage') }}" class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-cog me-1"></i>Manage Subscription
                                </a>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @elseif($trialInfo['has_active_subscription'] && !$trialInfo['is_in_trial'])
                <!-- Active Subscription Banner -->
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 20px; border: none; box-shadow: 0 8px 25px rgba(25, 135, 84, 0.2);">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="alert-heading mb-2">
                                <i class="fas fa-star me-2"></i>Subscription Active
                            </h5>
                            <p class="mb-2">
                                Your subscription is active and all features are available.
                                @if(Auth::user()->monthlyInvoiceSetting && Auth::user()->monthlyInvoiceSetting->subscription_ends_at)
                                    <strong>Expires: {{ Auth::user()->monthlyInvoiceSetting->subscription_ends_at->format('M d, Y') }}</strong>
                                @endif
                            </p>

                            @if(config('app.debug'))
                                <div class="alert alert-warning mt-2 p-2 small">
                                    <strong>DEBUG:</strong> has_active_subscription=true, is_in_trial=false, sub_ends={{ Auth::user()->monthlyInvoiceSetting ? Auth::user()->monthlyInvoiceSetting->subscription_ends_at : 'null' }}
                                </div>
                            @endif

                            <div class="d-flex gap-2">
                                <a href="{{ route('subscription.manage') }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-cog me-1"></i>Manage Subscription
                                </a>
                                <a href="{{ route('invoices.index') }}" class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-file-invoice me-1"></i>View Invoices
                                </a>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @elseif($trialInfo['trial_status'] === 'expired' && Auth::user()->isRestricted())
                <!-- Restriction Warning -->
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 20px; border: none; box-shadow: 0 8px 25px rgba(220, 53, 69, 0.2);">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-ban fa-2x text-danger"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="alert-heading mb-2">
                                <i class="fas fa-exclamation-triangle me-2"></i>Free Trial Expired - Account Restricted
                            </h5>
                            <p class="mb-2">Your free trial has ended. {{ Auth::user()->getRestrictionMessage() }}</p>
                            <div class="d-flex gap-2">
                                <a href="{{ route('subscription.pricing') }}" class="btn btn-danger btn-sm">
                                    <i class="fas fa-credit-card me-1"></i> Pay Outstanding Invoices
                                </a>
                                <a href="{{ route('access.restricted') }}" class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-info-circle me-1"></i> View Details
                                </a>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @elseif(Auth::user()->isInGracePeriod())
                <!-- Grace Period Warning -->
                <div class="alert alert-warning alert-dismissible fade show" role="alert" style="border-radius: 20px; border: none; box-shadow: 0 8px 25px rgba(255, 193, 7, 0.2);">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="alert-heading mb-2">
                                <i class="fas fa-exclamation-triangle me-2"></i>Subscription Expired - Grace Period
                            </h5>
                            <p class="mb-2">
                                <strong>Your subscription expired on {{ Auth::user()->getSubscriptionEndDate() ? Auth::user()->getSubscriptionEndDate()->format('M d, Y') : 'Unknown Date' }}</strong>
                                <br>
                                You have <strong>{{ Auth::user()->getDaysRemainingInCurrentPeriod() }} days remaining</strong> in your grace period
                            </p>
                            <div class="d-flex gap-2">
                                <a href="{{ route('subscription.manage') }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-credit-card me-1"></i> Renew Subscription
                                </a>
                                <a href="{{ route('invoices.index') }}" class="btn btn-outline-warning btn-sm">
                                    <i class="fas fa-file-invoice-dollar me-1"></i> View Invoices
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Note: No close button - notification persists until payment -->
                </div>
            @elseif(Auth::user()->isInWarningPeriod())
                <!-- Warning Period Alert -->
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 20px; border: none; box-shadow: 0 8px 25px rgba(220, 53, 69, 0.2);">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="alert-heading mb-2">
                                <i class="fas fa-exclamation-triangle me-2"></i>Final Warning - Account Will Be Restricted Soon
                            </h5>
                            <p class="mb-2">
                                <strong>Your subscription expired on {{ Auth::user()->getSubscriptionEndDate() ? Auth::user()->getSubscriptionEndDate()->format('M d, Y') : 'Unknown Date' }}</strong>
                                <br>
                                You have <strong>{{ Auth::user()->getDaysRemainingInCurrentPeriod() }} days remaining</strong> before your account is restricted
                            </p>
                            <div class="d-flex gap-2">
                                <a href="{{ route('subscription.manage') }}" class="btn btn-danger btn-sm">
                                    <i class="fas fa-credit-card me-1"></i> Renew Now
                                </a>
                                <a href="{{ route('invoices.index') }}" class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-file-invoice-dollar me-1"></i> Pay Invoices
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Note: No close button - notification persists until payment -->
                </div>
            @elseif(Auth::user()->getOverdueInvoicesCount() > 0)
                <!-- Overdue Warning -->
                <div class="alert alert-warning alert-dismissible fade show" role="alert" style="border-radius: 20px; border: none; box-shadow: 0 8px 25px rgba(255, 193, 7, 0.2);">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="alert-heading mb-2">
                                <i class="fas fa-clock me-2"></i>Overdue Invoices
                            </h5>
                            <p class="mb-2">You have {{ Auth::user()->getOverdueInvoicesCount() }} overdue invoice(s). Please pay them to avoid service interruption.</p>
                            <a href="{{ route('invoices.index') }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-file-invoice-dollar me-1"></i> View Invoices
                            </a>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @elseif(Auth::user()->getTotalUnpaidMonthlyAmount() > 0)
                <!-- Monthly Invoice Reminder -->
                <div class="alert alert-info alert-dismissible fade show" role="alert" style="border-radius: 20px; border: none; box-shadow: 0 8px 25px rgba(13, 202, 240, 0.2);">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-calendar-alt fa-2x text-info"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="alert-heading mb-2">
                                <i class="fas fa-info-circle me-2"></i>Monthly Service Fee Due
                            </h5>
                            <p class="mb-2">You have ${{ number_format(Auth::user()->getTotalUnpaidMonthlyAmount(), 2) }} in unpaid monthly service fees.</p>
                            <a href="{{ route('invoices.index') }}" class="btn btn-info btn-sm">
                                <i class="fas fa-credit-card me-1"></i> Pay Now
                            </a>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        @endauth

        <!-- Quick Actions Card -->
        <div class="quick-actions-card animate-in" style="color: #e8ede7 !important;">
            <h4 class="quick-actions-title" style="color: #e8ede7 !important;">
                <i class="fas fa-bolt" style="color: #fbbf24 !important;"></i>
                <span style="color: #e8ede7 !important;">Quick Actions</span>
            </h4>
            <div class="d-flex flex-wrap gap-3">
                {{-- AI Ask temporarily disabled --}}
                {{-- @if(auth()->user()->canAccessRoute('ai.ask-ai'))
                    <a href="{{ route('ai.ask-ai') }}" class="quick-action-btn primary">
                        <i class="fas fa-user-plus"></i>
                        <span>Add New Patient</span>
                    </a>
                @endif --}}

                @if(auth()->user()->canAccessRoute('diagnosis'))
                    <a href="{{ route('diagnosis.create') }}" class="quick-action-btn primary">
                        <i class="fas fa-file-medical"></i>
                        <span>Create Diagnosis</span>
                    </a>
                @endif

                @if(auth()->user()->canAccessRoute('doctor.cases.overview'))
                    <a href="{{ route('doctor.cases.overview') }}" class="quick-action-btn">
                        <i class="fas fa-list"></i>
                        <span>View Patient Cases</span>
                    </a>
                @endif

                @if(auth()->user()->canAccessRoute('diagnosis'))
                    <a href="{{ route('diagnosis.index') }}" class="quick-action-btn">
                        <i class="fas fa-clipboard-list"></i>
                        <span>View Diagnoses</span>
                    </a>
                @endif

                @if(auth()->user()->canAccessRoute('doctor.appointments.index'))
                    <a href="{{ route('doctor.appointments.index') }}" class="quick-action-btn">
                        <i class="fas fa-calendar"></i>
                        <span>Appointments</span>
                    </a>
                @endif

                @if(auth()->user()->canAccessRoute('settings'))
                    <a href="{{ route('settings') }}" class="quick-action-btn">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Statistics Section -->
        <section class="row mb-4 mb-md-5" aria-labelledby="statistics-heading">
            <h2 id="statistics-heading" class="visually-hidden">Patient Statistics Overview</h2>
            <div class="col-md-3 mb-4">
                <div class="stats-card animate-in animate-in-1">
                    <div class="stats-icon">
                        <i class="fas fa-users" aria-hidden="true"></i>
                    </div>
                    <p class="stats-number">
                        @php
                            // Count distinct patients from combined records
                            $patientKeys = [];
                            foreach ($records as $record) {
                                if (isset($record->patient_key) && $record->patient_key) {
                                    $patientKeys[$record->patient_key] = true;
                                } elseif (isset($record->patient_id)) {
                                    $patientKeys['diagnosis_' . $record->patient_id] = true;
                                }
                            }
                            echo count($patientKeys);
                        @endphp
                    </p>
                    <p class="stats-label">Total Patients</p>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="stats-card animate-in animate-in-2">
                    <div class="stats-icon">
                        <i class="fas fa-calendar-days"></i>
                    </div>
                    <p class="stats-number" style="color: #e8ede7 !important;">
                        @if(count($records) > 0)
                            {{ $records->first()->created_at->format('M d') }}
                        @else
                            N/A
                        @endif
                    </p>
                    <p class="stats-label" style="color: rgba(232, 237, 231, 0.5) !important;">Latest Case</p>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="stats-card animate-in animate-in-4">
                    <div class="stats-icon">
                        <i class="fas fa-venus-mars" style="color: #00d4aa !important;"></i>
                    </div>
                    <p class="stats-number">
                        @php
                            // Calculate male percentage based on distinct patients
                            $uniquePatients = [];
                            $maleCount = 0;
                            foreach ($records as $record) {
                                $key = isset($record->patient_key) && $record->patient_key ? $record->patient_key : ('diagnosis_' . ($record->patient_id ?? 'unknown'));
                                if (!isset($uniquePatients[$key])) {
                                    $uniquePatients[$key] = $record;
                                    if (($record->gender ?? null) === 'male') {
                                        $maleCount++;
                                    }
                                }
                            }
                            $totalUniquePatients = count($uniquePatients);
                            $ratio = $totalUniquePatients > 0 ? round(($maleCount / $totalUniquePatients) * 100) : 0;
                        @endphp
                        {{ $ratio }}%
                    </p>
                    <p class="stats-label">Male Patients</p>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="stats-card animate-in animate-in-5">
                    <div class="stats-icon">
                        <i class="fas fa-user-doctor"></i>
                    </div>
                    <p class="stats-number">
                        @php
                            // Calculate average age based on distinct patients
                            $uniquePatients = [];
                            $ages = [];
                            foreach ($records as $record) {
                                $key = isset($record->patient_key) && $record->patient_key ? $record->patient_key : ('diagnosis_' . ($record->patient_id ?? 'unknown'));
                                if (!isset($uniquePatients[$key]) && isset($record->age) && $record->age) {
                                    $uniquePatients[$key] = true;
                                    $ages[] = (float) $record->age;
                                }
                            }
                            $avgAge = count($ages) > 0 ? round(array_sum($ages) / count($ages)) : 0;
                        @endphp
                        {{ $avgAge }}
                    </p>
                    <p class="stats-label">Avg. Patient Age</p>
                </div>
            </div>
        </section>

        @if($doctorData)
        <!-- Doctor-Specific Dashboard Sections -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="doctor-section-header" style="color: #e8ede7 !important;">
                    <h3 class="mb-2" style="color: #e8ede7 !important;">
                        <i class="fas fa-stethoscope me-2" style="color: #00d4aa !important;"></i>
                        <span style="color: #e8ede7 !important;">Doctor Dashboard</span>
                    </h3>
                    <p class="mb-0 text-muted" style="color: rgba(232, 237, 231, 0.5) !important;">
                        <span style="color: rgba(232, 237, 231, 0.5) !important;">Manage your practice and appointments</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Doctor Statistics Cards -->
        <div class="row mb-5">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card h-100 animate-in animate-in-6">
                    <div class="stats-icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <p class="stats-number">{{ $doctorData['stats']['today_appointments'] }}</p>
                    <p class="stats-label">Today's Appointments</p>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar bg-info" role="progressbar"
                             style="width: {{ $doctorData['stats']['today_appointments'] > 10 ? 100 : ($doctorData['stats']['today_appointments'] * 10) }}%">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card h-100 animate-in animate-in-7">
                    <div class="stats-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <p class="stats-number">{{ $doctorData['stats']['pending_appointments'] }}</p>
                    <p class="stats-label">Pending Approval</p>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar bg-warning" role="progressbar"
                             style="width: {{ $doctorData['stats']['pending_appointments'] > 10 ? 100 : ($doctorData['stats']['pending_appointments'] * 10) }}%">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card h-100 animate-in animate-in-8">
                    <div class="stats-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="stats-number">{{ number_format($doctorData['stats']['average_rating'], 1) }}</p>
                    <p class="stats-label">Average Rating</p>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar bg-success" role="progressbar"
                             style="width: {{ $doctorData['stats']['average_rating'] * 20 }}%">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card h-100 animate-in animate-in-9">
                    <div class="stats-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <p class="stats-number">${{ number_format($doctorData['stats']['revenue_this_month'], 0) }}</p>
                    <p class="stats-label">This Month Revenue</p>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar bg-danger" role="progressbar"
                             style="width: {{ $doctorData['stats']['revenue_this_month'] > 10000 ? 100 : ($doctorData['stats']['revenue_this_month'] / 100) }}%">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Diagnosis Statistics Cards -->
        <div class="row mb-5">
            <div class="col-md-3 mb-4">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-file-medical"></i>
                    </div>
                    <p class="stats-number">{{ auth()->user()->doctorDiagnoses()->count() }}</p>
                    <p class="stats-label">Total Diagnoses</p>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <p class="stats-number">{{ auth()->user()->doctorDiagnoses()->whereDate('created_at', today())->count() }}</p>
                    <p class="stats-label">Today's Diagnoses</p>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-comments" style="color: #00d4aa !important;"></i>
                    </div>
                    <p class="stats-number" style="color: #e8ede7 !important;">{{ auth()->user()->doctorDiagnoses()->withCount('followUps')->get()->sum('follow_ups_count') }}</p>
                    <p class="stats-label" style="color: rgba(232, 237, 231, 0.5) !important;">Follow-up Questions</p>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-star" style="color: #00d4aa !important;"></i>
                    </div>
                    <p class="stats-number">
                        @php
                            // Use existing review system instead of diagnosis-specific ratings
                            $doctorReviews = auth()->user()->doctor ? auth()->user()->doctor->reviews() : collect();
                            $avgRating = $doctorReviews->avg('rating');
                        @endphp
                        {{ $avgRating ? number_format($avgRating, 1) : 'N/A' }}
                    </p>
                    <p class="stats-label">Doctor Rating</p>
                </div>
            </div>
        </div>

        <!-- Doctor Dashboard Content -->
        <div class="row mb-5">
            <!-- Today's Schedule -->
            <div class="col-lg-8 mb-4">
                <div class="table-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="table-title mb-0">
                            <i class="fas fa-calendar-check me-2" aria-hidden="true"></i>Today's Schedule
                        </h6>
                        <span class="badge bg-primary bg-gradient rounded-pill px-3 py-2">{{ now()->format('l, F j, Y') }}</span>
                    </div>

                    @if($doctorData['todayAppointments']->count() > 0)
                        <div class="appointment-cards">
                            @foreach($doctorData['todayAppointments'] as $appointment)
                                <div class="appointment-card mb-3 p-3 border rounded">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="time-circle me-3" style="background: linear-gradient(135deg, #3498db, #2980b9); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                                    {{ $appointment->appointment_date->format('g:i A') }}
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">{{ $appointment->patient->name ?? 'Unknown Patient' }}</h6>
                                                    <p class="text-muted small mb-1">{{ $appointment->reason }}</p>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-{{ $appointment->appointment_type == 'video_call' ? 'video' : ($appointment->appointment_type == 'phone_call' ? 'phone' : 'hospital') }} me-1 small text-primary"></i>
                                                        <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $appointment->appointment_type)) }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <small class="text-muted d-block">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $appointment->appointment_date->diffInMinutes($appointment->appointment_end) }} min appointment
                                            </small>
                                        </div>
                                        <div>
                                            <span class="badge {{ $appointment->status == 'confirmed' ? 'bg-success' : 'bg-warning' }} rounded-pill px-3 py-2">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                            <div class="mt-2">
                                                <a href="{{ route('doctor.appointments.show', $appointment) }}"
                                                   class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                    <i class="fas fa-eye me-1"></i>View
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state py-5">
                            <i class="fas fa-calendar-check" style="font-size: 3rem; opacity: 0.3;"></i>
                            <h5 class="text-muted">No appointments today</h5>
                            <p class="text-muted">Your schedule is clear for today</p>
                            <a href="{{ route('doctor.appointments.index') }}" class="btn btn-primary-custom rounded-pill px-4">
                                <i class="fas fa-calendar-plus me-1"></i>View All Appointments
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Doctor Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="table-card mb-4">
                    <h6 class="table-title mb-3">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                    <div class="row g-2">
                        @if(auth()->user()->canAccessRoute('doctor.appointments.index'))
                            <div class="col-md-6 col-12">
                                <a href="{{ route('doctor.appointments.index') }}" class="btn btn-primary-custom btn-sm w-100 rounded-pill px-3 py-2">
                                    <i class="fas fa-calendar me-1"></i>Appointments
                                </a>
                            </div>
                        @endif

                        @if(auth()->user()->canAccessRoute('diagnosis'))
                            <div class="col-md-6 col-12">
                                <a href="{{ route('diagnosis.create') }}" class="btn btn-primary-custom btn-sm w-100 rounded-pill px-3 py-2">
                                    <i class="fas fa-file-medical me-1"></i>Create
                                </a>
                            </div>
                        @endif

                        @if(auth()->user()->canAccessRoute('diagnosis'))
                            <div class="col-md-6 col-12">
                                <a href="{{ route('diagnosis.index') }}" class="btn btn-secondary-custom btn-sm w-100 rounded-pill px-3 py-2">
                                    <i class="fas fa-clipboard-list me-1"></i>Diagnoses
                                </a>
                            </div>
                        @endif

                        @if(auth()->user()->canAccessRoute('doctor.availability.index'))
                            <div class="col-md-6 col-12">
                                <a href="{{ route('doctor.availability.index') }}" class="btn btn-secondary-custom btn-sm w-100 rounded-pill px-3 py-2">
                                    <i class="fas fa-clock me-1"></i>Availability
                                </a>
                            </div>
                        @endif

                        @if(auth()->user()->canAccessRoute('doctor.reviews.index'))
                            <div class="col-md-6 col-12">
                                <a href="{{ route('doctor.reviews.index') }}" class="btn btn-secondary-custom btn-sm w-100 rounded-pill px-3 py-2">
                                    <i class="fas fa-star me-1"></i>Reviews
                                </a>
                            </div>
                        @endif

                        @if(auth()->user()->canAccessRoute('doctor.profile.edit'))
                            <div class="col-md-6 col-12">
                                <a href="{{ route('doctor.profile.edit') }}" class="btn btn-secondary-custom btn-sm w-100 rounded-pill px-3 py-2">
                                    <i class="fas fa-user-edit me-1"></i>Profile
                                </a>
                            </div>
                        @endif

                        @if(auth()->user()->canAccessRoute('doctor.settings.appointments'))
                            <div class="col-md-6 col-12">
                                <a href="{{ route('doctor.settings.appointments') }}" class="btn btn-secondary-custom btn-sm w-100 rounded-pill px-3 py-2">
                                    <i class="fas fa-cog me-1"></i>Settings
                                </a>
                            </div>
                        @endif

                        @if(auth()->user()->canAccessRoute('doctor.notes.index'))
                            <div class="col-md-6 col-12">
                                <a href="{{ route('doctor.notes.index') }}" class="btn btn-secondary-custom btn-sm w-100 rounded-pill px-3 py-2">
                                    <i class="fas fa-sticky-note me-1"></i>Notes
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Pending Appointments -->
                @if(auth()->user()->canAccessRoute('doctor.appointments.index') && $doctorData['pendingAppointments']->count() > 0)
                    <div class="table-card" style="margin-bottom: 2rem; position: relative; z-index: 2;">
                        <h6 class="table-title mb-3">
                            <i class="fas fa-clock me-2"></i>Pending Appointments
                        </h6>
                        <div class="appointment-list">
                            @foreach($doctorData['pendingAppointments'] as $appointment)
                                <div class="appointment-item p-3 mb-2 rounded" style="background: rgba(251,191,36,0.08); border: 1px solid rgba(251,191,36,0.2);">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong class="text-white">{{ $appointment->patient->name ?? 'Unknown Patient' }}</strong><br>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $appointment->appointment_date->format('M j, g:i A') }}
                                            </small>
                                        </div>
                                        <div class="btn-group">
                                            <form method="POST" action="{{ route('doctor.appointments.confirm', $appointment) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success rounded-pill px-3" title="Confirm">
                                                    <i class="fas fa-check me-1"></i>Confirm
                                                </button>
                                            </form>
                                            <a href="{{ route('doctor.appointments.show', $appointment) }}"
                                               class="btn btn-sm btn-outline-primary rounded-pill px-3" title="View Details">
                                                <i class="fas fa-eye me-1"></i>View
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('doctor.appointments.index', ['status' => 'pending']) }}"
                               class="btn btn-sm btn-primary-custom rounded-pill px-4">
                                <i class="fas fa-arrow-right me-1"></i>View all pending
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Recent Reviews -->
                @if($doctorData['recentReviews']->count() > 0)
                    <div class="table-card" style="margin-bottom: 2rem; position: relative; z-index: 2;">
                        <h6 class="table-title mb-3">
                            <i class="fas fa-star me-2"></i>Recent Reviews
                        </h6>
                        <div class="review-list">
                            @foreach($doctorData['recentReviews'] as $review)
                                <div class="review-item p-3 mb-2 rounded" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="badge bg-light rounded-pill px-2 py-1">
                                            {{ $review->rating }}/5
                                        </span>
                                    </div>
                                    @if($review->comment)
                                        <p class="mb-2 small text-white">{{ Str::limit($review->comment, 80) }}</p>
                                    @endif
                                    <small class="text-muted d-block">
                                        <i class="fas fa-user me-1"></i>
                                        {{ $review->is_anonymous ? 'Anonymous' : ($review->patient->name ?? 'Unknown Patient') }}
                                        <span class="ms-2">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $review->created_at->diffForHumans() }}
                                        </span>
                                    </small>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('doctor.reviews.index') }}"
                               class="btn btn-sm btn-primary-custom rounded-pill px-4">
                                <i class="fas fa-arrow-right me-1"></i>View all reviews
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @endif

@if(isset($appointments))
<div class="row mb-5">
    <div class="col-12">
        <div class="table-card">
            <h6 class="table-title mb-0">
                <i class="fas fa-calendar-check me-2"></i>My Appointments
            </h6>
        </div>
    </div>
</div>

@if($appointments->count() > 0)
<div class="row">
    @foreach($appointments as $appointment)
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0">{{ $appointment->appointment_date->format('M d, Y g:i A') }} - {{ optional($appointment->doctor->user)->name ?? 'Unknown Doctor' }}</h6>
                <p class="mb-0 small opacity-75">{{ $appointment->reason }}</p>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>Status:</strong>
                    <span class="badge {{ $appointment->status == 'completed' ? 'bg-success' : ($appointment->status == 'cancelled' ? 'bg-danger' : 'bg-warning') }}">
                        {{ ucfirst($appointment->status) }}
                    </span>
                </p>

                @if($appointment->prescription_given == true)
                <div class="prescriptions-section mt-3 border-top pt-3">
                    <h6 class="mb-3"><i class="fas fa-pills me-2 text-primary"></i>Prescriptions</h6>

                    @if($appointment->prescriptions->count() > 0)
                    <div class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
                        @foreach($appointment->prescriptions as $prescription)
                        <div class="list-group-item px-0 py-2">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">{{ $prescription->medication_name }}</h6>
                                    <div class="row g-2 small text-muted">
                                        <div class="col-6">
                                            <strong>Dosage:</strong> {{ $prescription->dosage }}
                                        </div>
                                        <div class="col-6">
                                            <strong>Frequency:</strong> {{ $prescription->frequency }}
                                        </div>
                                        <div class="col-6">
                                            <strong>Duration:</strong> {{ $prescription->duration }}
                                        </div>
                                        <div class="col-6">
                                            <strong>Issued:</strong> {{ \Carbon\Carbon::parse($prescription->created_at)->format('M d, Y') }}
                                        </div>
                                    </div>
                                    @if($prescription->notes)
                                    <p class="mt-2 mb-0 small"><strong>Notes:</strong> {{ $prescription->notes }}</p>
                                    @endif
                                </div>
                                <div class="ms-2 mt-1">
                                    <a href="{{ route('prescriptions.pdf', $prescription->id) }}" class="btn btn-primary btn-sm" target="_blank" title="Download Prescription PDF">
                                        <i class="fas fa-download"></i> PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted mb-0">No prescriptions yet.</p>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="col-12">
    <div class="empty-state">
        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
        <h5>No appointments found</h5>
        <p class="text-muted">You don't have any scheduled appointments at the moment.</p>
    </div>
</div>
@endif
@endif
        <!-- Cases Over Time Chart -->
        <div class="row mb-5">
            <div class="col-lg-8 mb-4">
                <div class="table-card animate-in">
                    <h6 class="table-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>Diagnosed Cases Over Time
                    </h6>
                    <div id="patientManagementChart" style="height: 300px; padding: 1rem 0;"></div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="stats-card h-100 animate-in animate-in-10">
                    <div class="stats-icon">
                        <i class="fas fa-calendar-week"></i>
                    </div>
                    <p class="stats-number">{{ $weeklyCount }}</p>
                    <p class="stats-label">Diagnosed Cases This Week</p>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar bg-info" role="progressbar"
                             style="width: {{ $weeklyCount > 20 ? 100 : ($weeklyCount * 5) }}%">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Statistics & Filters -->
        <div class="table-card mb-5 animate-in">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="table-title mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Advanced Statistics
                </h6>
                <div class="filter-controls">
                    <button class="btn btn-outline-primary btn-sm rounded-pill px-3" id="refresh-stats">
                        <i class="fas fa-sync-alt me-1"></i> Refresh
                    </button>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card filter-card">
                        <div class="card-body">
                            <h6 class="mb-3"><i class="fas fa-filter me-2"></i>Filter Data</h6>
                            <form id="stats-filter-form" class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Date Range</label>
                                    <select class="form-select" id="date-range-select">
                                        <option value="7">Last 7 days</option>
                                        <option value="30" selected>Last 30 days</option>
                                        <option value="90">Last 3 months</option>
                                        <option value="180">Last 6 months</option>
                                        <option value="365">Last year</option>
                                        <option value="custom">Custom range</option>
                                    </select>
                                </div>
                                <div class="col-md-3 custom-date-range" style="display: none;">
                                    <label class="form-label">From</label>
                                    <input type="date" class="form-control" id="date-from">
                                </div>
                                <div class="col-md-3 custom-date-range" style="display: none;">
                                    <label class="form-label">To</label>
                                    <input type="date" class="form-control" id="date-to">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Gender</label>
                                    <select class="form-select" id="gender-filter">
                                        <option value="all" selected>All</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Age Group</label>
                                    <select class="form-select" id="age-filter">
                                        <option value="all" selected>All</option>
                                        <option value="0-18">0-18</option>
                                        <option value="19-35">19-35</option>
                                        <option value="36-50">36-50</option>
                                        <option value="51-65">51-65</option>
                                        <option value="66+">66+</option>
                                    </select>
                                </div>
                                <div class="col-md-12 text-end">
                                    <button type="submit" class="btn btn-primary-custom btn-sm">
                                        <i class="fas fa-search me-1"></i> Apply Filters
                                    </button>
                                    <button type="reset" class="btn btn-secondary-custom btn-sm">
                                        <i class="fas fa-undo me-1"></i> Reset
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="stats-card">
                        <h6 class="mb-3"><i class="fas fa-chart-pie me-2"></i>Patient Demographics</h6>
                        <div id="demographicsChart" style="height: 250px;"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stats-card">
                        <h6 class="mb-3"><i class="fas fa-chart-bar me-2"></i>Age Distribution</h6>
                        <div id="ageDistributionChart" style="height: 250px;"></div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="stats-card">
                        <h6 class="mb-3"><i class="fas fa-calendar-days me-2"></i>Patient Visits Over Time</h6>
                        <div id="visitsTimelineChart" style="height: 250px;"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="stats-card text-center">
                        <div class="stats-icon mx-auto">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h3 class="stats-number" id="new-patients-count">{{ $records->where('created_at', '>=', now()->subDays(30))->groupBy('patient_key')->count() }}</h3>
                        <p class="stats-label">New Patients (30 days)</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card text-center">
                        <div class="stats-icon mx-auto">
                            <i class="fas fa-redo"></i>
                        </div>
                        <h3 class="stats-number" id="return-visits-count">
                            @php
                                $returnVisits = $records->where('created_at', '>=', now()->subDays(30))->count() - $records->where('created_at', '>=', now()->subDays(30))->groupBy('patient_key')->count();
                                echo $returnVisits > 0 ? $returnVisits : 0;
                            @endphp
                        </h3>
                        <p class="stats-label">Return Visits (30 days)</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card text-center">
                        <div class="stats-icon mx-auto">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="stats-number" id="growth-rate">
                            @php
                                $currentMonth = $records->where('created_at', '>=', now()->startOfMonth())->count();
                                $lastMonth = $records->where('created_at', '>=', now()->subMonth()->startOfMonth())
                                    ->where('created_at', '<', now()->startOfMonth())->count();
                                
                                // FIXED: Proper growth rate calculation v2
                                if ($currentMonth == 0 && $lastMonth == 0) {
                                    $growthRate = 0;
                                } elseif ($lastMonth == 0) {
                                    $growthRate = 100;
                                } else {
                                    $growthRate = round((($currentMonth - $lastMonth) / $lastMonth) * 100);
                                }
                                
                                echo $growthRate > 0 ? '+'.$growthRate : $growthRate;
                            @endphp%
                        </h3>
                        <p class="stats-label">Monthly Growth Rate</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Consolidated Patient List with Advanced Features -->
        <div class="table-card mb-5">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <h6 class="table-title mb-0">
                    <i class="fas fa-user-injured me-2"></i>Cases Overview
                </h6>
                <div class="d-flex flex-wrap gap-2 mt-2 mt-md-0">
                    <div class="input-group input-group-sm me-2" style="min-width: 200px;">
                        <input type="text" class="form-control rounded-start-pill" id="patient-search" placeholder="Search patients...">
                        <button class="btn btn-outline-primary rounded-end-pill" type="button" id="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <a href="{{ route('doctor.cases.overview') }}" class="btn btn-outline-primary btn-sm rounded-pill px-4">
                        <i class="fas fa-external-link-alt me-1"></i> View All Patient Cases
                    </a>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card filter-card">
                        <div class="card-body">
                            <h6 class="mb-3"><i class="fas fa-filter me-2"></i>Filter Patients</h6>
                            <form id="patient-filter-form" class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Date Range</label>
                                    <select class="form-select" id="patient-date-range">
                                        <option value="all" selected>All Time</option>
                                        <option value="7">Last 7 days</option>
                                        <option value="30">Last 30 days</option>
                                        <option value="90">Last 3 months</option>
                                        <option value="custom">Custom range</option>
                                    </select>
                                </div>
                                <div class="col-md-3 patient-custom-date" style="display: none;">
                                    <label class="form-label">From</label>
                                    <input type="date" class="form-control" id="patient-date-from">
                                </div>
                                <div class="col-md-3 patient-custom-date" style="display: none;">
                                    <label class="form-label">To</label>
                                    <input type="date" class="form-control" id="patient-date-to">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Gender</label>
                                    <select class="form-select" id="patient-gender-filter">
                                        <option value="all" selected>All</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Age Group</label>
                                    <select class="form-select" id="patient-age-filter">
                                        <option value="all" selected>All</option>
                                        <option value="0-18">0-18</option>
                                        <option value="19-35">19-35</option>
                                        <option value="36-50">36-50</option>
                                        <option value="51-65">51-65</option>
                                        <option value="66+">66+</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Visit Count</label>
                                    <select class="form-select" id="patient-visit-filter">
                                        <option value="all" selected>All</option>
                                        <option value="1">Single Visit</option>
                                        <option value="multiple">Multiple Visits</option>
                                    </select>
                                </div>
                                <div class="col-md-12 text-end">
                                    <button type="submit" class="btn btn-primary-custom btn-sm">
                                        <i class="fas fa-search me-1"></i> Apply Filters
                                    </button>
                                    <button type="reset" class="btn btn-secondary-custom btn-sm">
                                        <i class="fas fa-undo me-1"></i> Reset
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            @if(count($records) > 0)
                @php
                    // Group patients by patient_key to avoid duplication
                    $patientGroups = [];

                    foreach ($records as $record) {
                        $key = $record->patient_key ?? ($record->name . '-' . $record->age . '-' . $record->gender);

                        if (!isset($patientGroups[$key])) {
                            // Initialize with the first record
                            $patientGroups[$key] = [
                                'patient' => $record,
                                'visits' => [],
                                'visit_count' => 0,
                                'last_visit' => $record->created_at
                            ];
                        }

                        // Add this record to the visits array
                        $patientGroups[$key]['visits'][] = $record;
                        $patientGroups[$key]['visit_count']++;

                        // Update last visit date if this record is more recent
                        if ($record->created_at > $patientGroups[$key]['last_visit']) {
                            $patientGroups[$key]['last_visit'] = $record->created_at;
                        }
                    }

                    // Sort by most recent visit
                    uasort($patientGroups, function($a, $b) {
                        return $b['last_visit'] <=> $a['last_visit'];
                    });

                    // Take only the first 10 for display
                    $patientGroups = array_slice($patientGroups, 0, 10, true);
                @endphp

                <div class="table-responsive">
                    <table class="table custom-table mb-0" id="patients-table">
                        <thead>
                            <tr>
                                <th><a href="#" class="sort-link" data-sort="name">Patient Name <i class="fas fa-sort"></i></a></th>
                                <th><a href="#" class="sort-link" data-sort="age">Age <i class="fas fa-sort"></i></a></th>
                                <th><a href="#" class="sort-link" data-sort="gender">Gender <i class="fas fa-sort"></i></a></th>
                                <th><a href="#" class="sort-link" data-sort="visits">Total Visits <i class="fas fa-sort"></i></a></th>
                                <th><a href="#" class="sort-link" data-sort="last-visit">Last Visit <i class="fas fa-sort"></i></a></th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($patientGroups as $key => $group)
                                <tr data-patient-key="{{ $key }}" data-visits="{{ count($group['visits']) }}" data-last-visit="{{ $group['last_visit']->timestamp }}">
                                    <td>{{ $group['patient']->name ?? 'N/A' }}</td>
                                    <td>{{ $group['patient']->age ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge" style="background-color: {{ $group['patient']->gender == 'male' ? '#3498db' : '#e74c3c' }}; color: white;">
                                            {{ ucfirst($group['patient']->gender ?? 'N/A') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $group['visit_count'] }}</span>
                                    </td>
                                    <td data-date="{{ $group['last_visit']->timestamp }}">{{ $group['last_visit'] ? $group['last_visit']->format('M d, Y') : 'N/A' }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-view-patient btn-primary-custom"
                                                    data-patient-key="{{ $key }}"
                                                    data-patient-name="{{ $group['patient']->name }}"
                                                    data-patient-age="{{ $group['patient']->age }}"
                                                    data-patient-gender="{{ $group['patient']->gender }}">
                                                <i class="fas fa-eye me-1"></i>View
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="showing-entries">
                        Showing <span id="showing-count">{{ count($patientGroups) }}</span> of {{ count(array_unique($records->pluck('patient_key')->toArray())) }} patients
                    </div>
                    <div class="table-pagination">
                        <button class="btn btn-sm btn-outline-secondary me-1" id="prev-page" disabled>
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <span id="current-page">1</span> / <span id="total-pages">1</span>
                        <button class="btn btn-sm btn-outline-secondary ms-1" id="next-page" disabled>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Patient Details Modal -->
                <div class="modal fade" id="patientModal" tabindex="-1" aria-labelledby="patientModalLabel" aria-hidden="true" role="dialog">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="patientModalLabel">Patient Details</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close patient details modal"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Loading State -->
                                <div id="patient-modal-loading" class="text-center py-4" style="display: none;" role="status" aria-live="polite">
                                    <div class="spinner-border text-primary" role="status" aria-hidden="true"></div>
                                    <p class="mt-2 text-muted">Loading patient details...</p>
                                </div>
                                <!-- Content Container -->
                                <div id="patient-modal-content">
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <div class="patient-info-card">
                                            <h6 class="text-muted">Patient Name</h6>
                                            <p class="patient-name fs-5 fw-bold">-</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="patient-info-card">
                                            <h6 class="text-muted">Age</h6>
                                            <p class="patient-age fs-5 fw-bold">-</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="patient-info-card">
                                            <h6 class="text-muted">Gender</h6>
                                            <p class="patient-gender fs-5 fw-bold">-</p>
                                        </div>
                                    </div>
                                </div>

                                <h6 class="mb-3 border-bottom pb-2">Visit History</h6>
                                <div class="table-responsive">
                                    <table class="table table-hover" id="visit-history-table">
                                        <thead>
                                            <tr>
                                                <th>Visit #</th>
                                                <th>Date</th>
                                                <th>Symptoms</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="visit-history-body">
                                            <!-- Visit history will be populated dynamically -->
                                        </tbody>
                                    </table>
                                </div>

                                <div id="visit-details-section" class="mt-4" style="display: none;">
                                    <h6 class="mb-3 border-bottom pb-2">Visit Details</h6>
                                    <div id="visit-details-content" class="response-text">
                                        <!-- Visit details will be populated dynamically -->
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                {{-- AI Ask temporarily disabled --}}
                                {{-- <a href="{{ route('ai.ask-ai') }}" id="new-visit-btn" class="btn btn-primary-custom">
                                    <i class="fas fa-plus me-1"></i> New Visit
                                </a> --}}
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-user-doctor"></i>
                    <h5>No patients yet</h5>
                    <p>Start by adding your first patient</p>
                    {{-- AI Ask temporarily disabled --}}
                    {{-- <a href="{{ route('ai.ask-ai') }}" class="btn-primary-custom mt-3">
                        <i class="fas fa-plus me-2"></i> Add First Patient
                    </a> --}}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Make PHP variables available to JavaScript
    window.chartLabels = @json($chartLabels ?? []);
    window.chartData = @json($chartData ?? []);
    window.records = @json($records ?? []);
    window.weeklyCount = @json($weeklyCount ?? 0);
    window.doctorData = @json($doctorData ?? null);
    window.trialInfo = @json($trialInfo ?? null);
</script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="{{ asset('js/dashboard.js') }}"></script>
@vite(['resources/js/dashboard-enhancements.js'])

<script>
// Initialize charts when the page loads
document.addEventListener('DOMContentLoaded', function() {
    // Initialize main patient management chart
    if (window.chartLabels && window.chartData) {
        const patientChartOptions = {
            chart: {
                type: 'line',
                height: 300,
                toolbar: {
                    show: false
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                }
            },
            series: [{
                name: 'Diagnosed Cases',
                data: window.chartData
            }],
            xaxis: {
                categories: window.chartLabels,
                labels: {
                    style: {
                        fontSize: '12px',
                        fontWeight: 400,
                        colors: '#7f8c8d'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '12px',
                        fontWeight: 400,
                        colors: '#7f8c8d'
                    }
                }
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    gradientToColors: ['#0a1628'],
                    shadeIntensity: 1,
                    type: 'horizontal',
                    opacityFrom: 0.4,
                    opacityTo: 0.1,
                    stops: [0, 100]
                }
            },
            colors: ['#0a1628'],
            tooltip: {
                theme: 'dark',
                style: {
                    fontSize: '12px',
                }
            }
        };

        if (document.getElementById('patientManagementChart')) {
            const patientChart = new ApexCharts(document.getElementById('patientManagementChart'), patientChartOptions);
            patientChart.render();
        }
    }

    // Initialize demographics chart
    const demographicsChartOptions = {
        chart: {
            type: 'pie',
            height: 250,
            toolbar: {
                show: false
            }
        },
        series: [], // Example data - would be dynamic in real implementation
        labels: ['Male', 'Female', 'Other'],
        colors: ['#3498db', '#e74c3c', '#9b59b6'],
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }],
        tooltip: {
            theme: 'dark',
            style: {
                fontSize: '12px',
            }
        },
        dataLabels: {
            enabled: true,
            style: {
                fontSize: '10px',
                fontWeight: 'bold',
                colors: ['#000']
            },
            dropShadow: {
                enabled: true,
                color: '#fff',
                top: 1,
                left: 1,
                blur: 1,
                opacity: 0.5
            }
        }
    };

    if (document.getElementById('demographicsChart')) {
        const demographicsChart = new ApexCharts(document.getElementById('demographicsChart'), demographicsChartOptions);
        demographicsChart.render();
    }

    // Initialize age distribution chart
    const ageDistributionChartOptions = {
        chart: {
            type: 'bar',
            height: 250,
            toolbar: {
                show: false
            }
        },
        series: [{
            name: 'Patients',
            data: [10, 25, 35, 20, 15] // Example data - would be dynamic in real implementation
        }],
        xaxis: {
            categories: ['0-18', '19-35', '36-50', '51-65', '66+'],
            labels: {
                style: {
                    fontSize: '10px',
                    fontWeight: 400,
                    colors: '#7f8c8d'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    fontSize: '10px',
                    fontWeight: 400,
                    colors: '#7f8c8d'
                }
            }
        },
        plotOptions: {
            bar: {
                borderRadius: 4,
                horizontal: false,
            }
        },
        colors: ['#2ecc71'],
        tooltip: {
            theme: 'dark',
            style: {
                fontSize: '12px',
            }
        }
    };

    if (document.getElementById('ageDistributionChart')) {
        const ageDistributionChart = new ApexCharts(document.getElementById('ageDistributionChart'), ageDistributionChartOptions);
        ageDistributionChart.render();
    }

    // Initialize visits timeline chart
    const visitsTimelineChartOptions = {
        chart: {
            type: 'area',
            height: 250,
            toolbar: {
                show: false
            }
        },
        series: [{
            name: 'Visits',
            data: [30, 40, 35, 50, 49, 60, 70, 91, 125] // Example data - would be dynamic in real implementation
        }],
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
            labels: {
                style: {
                    fontSize: '10px',
                    fontWeight: 400,
                    colors: '#7f8c8d'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    fontSize: '10px',
                    fontWeight: 400,
                    colors: '#7f8c8d'
                }
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                gradientToColors: ['#3498db'],
                shadeIntensity: 1,
                type: 'horizontal',
                opacityFrom: 0.4,
                opacityTo: 0.1,
                stops: [0, 100]
            }
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        colors: ['#3498db'],
        tooltip: {
            theme: 'dark',
            style: {
                fontSize: '12px',
            }
        }
    };

    if (document.getElementById('visitsTimelineChart')) {
        const visitsTimelineChart = new ApexCharts(document.getElementById('visitsTimelineChart'), visitsTimelineChartOptions);
        visitsTimelineChart.render();
    }

    // Focus management for modals and dynamic content
    const patientModal = document.getElementById('patientModal');
    let lastFocusedElement = null;

    if (patientModal) {
        patientModal.addEventListener('show.bs.modal', function(event) {
            // Store the element that triggered the modal
            lastFocusedElement = event.relatedTarget;

            // Focus the modal when it opens
            setTimeout(() => {
                const firstFocusable = patientModal.querySelector('.modal-body input, .modal-body button, .modal-body a, .modal-body [tabindex]:not([tabindex="-1"])');
                if (firstFocusable) {
                    firstFocusable.focus();
                } else {
                    patientModal.focus();
                }
            }, 100);
        });

        patientModal.addEventListener('hidden.bs.modal', function() {
            // Return focus to the triggering element
            if (lastFocusedElement) {
                lastFocusedElement.focus();
            }
        });

        // Keyboard navigation within modal
        patientModal.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modalInstance = bootstrap.Modal.getInstance(patientModal);
                if (modalInstance) {
                    modalInstance.hide();
                }
            }

            // Tab navigation within modal
            if (e.key === 'Tab') {
                const focusableElements = patientModal.querySelectorAll(
                    'input, button, a, select, textarea, [tabindex]:not([tabindex="-1"])'
                );
                const firstElement = focusableElements[0];
                const lastElement = focusableElements[focusableElements.length - 1];

                if (e.shiftKey) {
                    // Shift + Tab
                    if (document.activeElement === firstElement) {
                        e.preventDefault();
                        lastElement.focus();
                    }
                } else {
                    // Tab
                    if (document.activeElement === lastElement) {
                        e.preventDefault();
                        firstElement.focus();
                    }
                }
            }
        });
    }

    // Enhanced patient view button functionality
    document.querySelectorAll('.btn-view-patient').forEach(button => {
        button.addEventListener('click', function() {
            const patientKey = this.dataset.patientKey;
            const patientName = this.dataset.patientName;
            const patientAge = this.dataset.patientAge;
            const patientGender = this.dataset.patientGender;

            // Show loading state
            const loadingDiv = document.getElementById('patient-modal-loading');
            const contentDiv = document.getElementById('patient-modal-content');

            if (loadingDiv && contentDiv) {
                loadingDiv.style.display = 'block';
                contentDiv.style.display = 'none';
            }

            // Update modal title
            const modalTitle = document.getElementById('patientModalLabel');
            if (modalTitle) {
                modalTitle.textContent = `Patient Details - ${patientName}`;
            }

            // Populate basic info
            const nameElement = document.querySelector('.patient-name');
            const ageElement = document.querySelector('.patient-age');
            const genderElement = document.querySelector('.patient-gender');

            if (nameElement) nameElement.textContent = patientName || 'N/A';
            if (ageElement) ageElement.textContent = patientAge || 'N/A';
            if (genderElement) genderElement.textContent = patientGender || 'N/A';

            // Fetch patient visit history
            fetch(`/api/patients/${patientKey}/visits`)
                .then(response => response.json())
                .then(data => {
                    const visitBody = document.getElementById('visit-history-body');
                    if (visitBody && data.visits) {
                        let html = '';
                        data.visits.forEach((visit, index) => {
                            html += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${new Date(visit.created_at).toLocaleDateString()}</td>
                                    <td>${visit.symptoms || 'N/A'}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary view-visit-btn rounded-pill px-3"
                                                data-visit-id="${visit.id}"
                                                data-visit-data="${JSON.stringify(visit).replace(/"/g, '"')}">
                                            <i class="fas fa-eye me-1"></i>View
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                        visitBody.innerHTML = html;

                        // Add event listeners for visit detail buttons
                        document.querySelectorAll('.view-visit-btn').forEach(btn => {
                            btn.addEventListener('click', function() {
                                const visitData = JSON.parse(this.dataset.visitData);
                                showVisitDetails(visitData);
                            });
                        });
                    }
                })
                .catch(error => {
                    // console.error('Error loading patient visits:', error);
                })
                .finally(() => {
                    // Hide loading state
                    if (loadingDiv && contentDiv) {
                        loadingDiv.style.display = 'none';
                        contentDiv.style.display = 'block';
                    }
                });
        });
    });

    function showVisitDetails(visitData) {
        const detailsSection = document.getElementById('visit-details-section');
        const detailsContent = document.getElementById('visit-details-content');

        if (detailsSection && detailsContent) {
            detailsContent.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary mb-2"><i class="fas fa-stethoscope me-1"></i> Symptoms</h6>
                        <p class="bg-light p-3 rounded" style="color: var(--offwhite);">${visitData.symptoms || 'Not specified'}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary mb-2"><i class="fas fa-file-medical me-1"></i> Diagnosis</h6>
                        <p class="bg-light p-3 rounded" style="color: var(--offwhite);">${visitData.diagnosis || 'Not specified'}</p>
                    </div>
                </div>
                ${visitData.notes ? `
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-primary mb-2"><i class="fas fa-sticky-note me-1"></i> Notes</h6>
                            <p class="bg-light p-3 rounded" style="color: var(--offwhite);">${visitData.notes}</p>
                        </div>
                    </div>
                ` : ''}
            `;
            detailsSection.style.display = 'block';
            detailsSection.scrollIntoView({ behavior: 'smooth' });
        }
    }

    // Loading states for dynamic content updates
    function showLoadingState(elementId, message = 'Loading...') {
        const element = document.getElementById(elementId);
        if (element) {
            element.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status" aria-hidden="true"></div>
                    <p class="mt-2 text-muted">${message}</p>
                </div>
            `;
            element.style.display = 'block';
        }
    }

    function hideLoadingState(elementId) {
        const element = document.getElementById(elementId);
        if (element) {
            element.style.display = 'none';
        }
    }

    // Make functions globally available
    window.showLoadingState = showLoadingState;
    window.hideLoadingState = hideLoadingState;
});
</script>

