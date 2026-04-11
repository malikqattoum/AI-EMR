@extends('layouts.doctor')

@section('title', 'Doctor Dashboard')

@push('styles')
<style>
/* ============================================
   DOCTOR DASHBOARD - CLEAN DARK DESIGN
   ============================================ */

body {
    background: #060d1f !important;
}

.dash-page,
.doctor-page,
.doctor-container {
    background: #060d1f !important;
}

.dash-page {
    padding: 2rem;
    min-height: 100vh;
}

.dash-page .container {
    max-width: 1100px;
    margin: 0 auto;
}

/* Header */
.dash-header {
    margin-bottom: 2rem;
}

.dash-header h1 {
    font-size: 1.6rem;
    font-weight: 700;
    color: #e8ede7 !important;
    margin-bottom: 0.25rem;
}

.dash-header p {
    color: rgba(232, 237, 231, 0.55) !important;
    margin: 0;
    font-size: 0.9rem;
}

/* Doctor Card */
.dash-doctor-card {
    background: rgba(10, 22, 40, 0.9) !important;
    border: 1px solid rgba(0, 212, 170, 0.15) !important;
    border-radius: 14px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
}

.dash-doctor-avatar {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: rgba(0, 212, 170, 0.15) !important;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid rgba(0, 212, 170, 0.3) !important;
    flex-shrink: 0;
}

.dash-doctor-avatar i {
    font-size: 1.5rem;
    color: #00d4aa !important;
}

.dash-doctor-info h3 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #e8ede7 !important;
    margin-bottom: 0.2rem;
}

.dash-doctor-info .specialty {
    color: #00d4aa !important;
    font-size: 0.85rem;
    margin-bottom: 0.15rem;
}

.dash-doctor-info .date {
    color: rgba(232, 237, 231, 0.5) !important;
    font-size: 0.8rem;
}

/* Stats Grid */
.dash-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
}

.dash-stat-card {
    background: rgba(10, 22, 40, 0.85) !important;
    border: 1px solid rgba(0, 212, 170, 0.1) !important;
    border-radius: 12px;
    padding: 1.25rem;
    text-align: center;
    transition: transform 0.2s ease, border-color 0.2s ease;
}

.dash-stat-card:hover {
    transform: translateY(-2px);
    border-color: rgba(0, 212, 170, 0.25) !important;
}

.dash-stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.75rem;
}

.dash-stat-icon i {
    font-size: 1.2rem;
}

.dash-stat-icon.blue { background: rgba(59, 130, 246, 0.15); }
.dash-stat-icon.blue i { color: #60a5fa !important; }
.dash-stat-icon.yellow { background: rgba(251, 191, 36, 0.15); }
.dash-stat-icon.yellow i { color: #fbbf24 !important; }
.dash-stat-icon.teal { background: rgba(0, 212, 170, 0.12); }
.dash-stat-icon.teal i { color: #00d4aa !important; }
.dash-stat-icon.purple { background: rgba(168, 85, 247, 0.15); }
.dash-stat-icon.purple i { color: #a78bfa !important; }
.dash-stat-icon.orange { background: rgba(253, 126, 20, 0.15); }
.dash-stat-icon.orange i { color: #fd7e14 !important; }

.dash-stat-number {
    font-size: 1.6rem;
    font-weight: 700;
    color: #e8ede7 !important;
    line-height: 1.2;
    margin-bottom: 0.25rem;
}

.dash-stat-label {
    font-size: 0.8rem;
    color: rgba(232, 237, 231, 0.5) !important;
}

/* Section Card */
.dash-section {
    background: rgba(10, 22, 40, 0.85) !important;
    border: 1px solid rgba(0, 212, 170, 0.1) !important;
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 1.5rem;
}

.dash-section-header {
    background: rgba(0, 212, 170, 0.06) !important;
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid rgba(0, 212, 170, 0.08) !important;
}

.dash-section-title {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    font-size: 0.95rem;
    font-weight: 600;
    color: #e8ede7 !important;
    margin: 0;
}

.dash-section-title i {
    color: #00d4aa !important;
}

.dash-section-body {
    padding: 1.25rem;
}

/* Today's Schedule */
.dash-appt-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.9rem 0;
    border-bottom: 1px solid rgba(0, 212, 170, 0.06) !important;
}

.dash-appt-item:last-child {
    border-bottom: none !important;
}

.dash-appt-time {
    min-width: 70px;
    text-align: center;
}

.dash-appt-time .time {
    font-size: 0.95rem;
    font-weight: 600;
    color: #e8ede7 !important;
}

.dash-appt-time .duration {
    font-size: 0.75rem;
    color: rgba(232, 237, 231, 0.4) !important;
}

.dash-appt-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #00d4aa;
    flex-shrink: 0;
}

.dash-appt-dot.confirmed { background: #00d4aa; }
.dash-appt-dot.pending { background: #fbbf24; }

.dash-appt-info {
    flex: 1;
}

.dash-appt-info .name {
    font-weight: 500;
    color: #e8ede7 !important;
    font-size: 0.9rem;
    margin-bottom: 0.15rem;
}

.dash-appt-info .reason {
    font-size: 0.8rem;
    color: rgba(232, 237, 231, 0.5) !important;
    margin-bottom: 0.15rem;
}

.dash-appt-info .type {
    font-size: 0.75rem;
    color: rgba(232, 237, 231, 0.4) !important;
}

.dash-appt-type-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.25rem 0.6rem;
    border-radius: 20px;
    font-size: 0.7rem;
    background: rgba(0, 212, 170, 0.1) !important;
    color: #00d4aa !important;
}

.dash-appt-type-badge.video {
    background: rgba(59, 130, 246, 0.1) !important;
    color: #60a5fa !important;
}

.dash-appt-type-badge.phone {
    background: rgba(167, 139, 250, 0.1) !important;
    color: #a78bfa !important;
}

.dash-appt-status {
    padding: 0.25rem 0.6rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 500;
}

.dash-appt-status.confirmed {
    background: rgba(0, 212, 170, 0.12) !important;
    color: #00d4aa !important;
}

.dash-appt-status.pending {
    background: rgba(251, 191, 36, 0.12) !important;
    color: #fbbf24 !important;
}

.dash-appt-action {
    padding: 0.4rem 0.8rem;
    background: rgba(0, 212, 170, 0.08) !important;
    border: 1px solid rgba(0, 212, 170, 0.15) !important;
    border-radius: 8px;
    color: #00d4aa !important;
    font-size: 0.8rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
}

.dash-appt-action:hover {
    background: #00d4aa !important;
    color: #060d1f !important;
}

/* Quick Actions Grid */
.dash-quick-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
}

.dash-quick-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: rgba(6, 13, 31, 0.6) !important;
    border: 1px solid rgba(0, 212, 170, 0.1) !important;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.2s ease;
}

.dash-quick-btn:hover {
    border-color: rgba(0, 212, 170, 0.3) !important;
    background: rgba(0, 212, 170, 0.05) !important;
    transform: translateX(4px);
}

.dash-quick-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.dash-quick-icon i {
    font-size: 1rem;
}

.dash-quick-icon.blue { background: rgba(59, 130, 246, 0.15); }
.dash-quick-icon.blue i { color: #60a5fa !important; }
.dash-quick-icon.teal { background: rgba(0, 212, 170, 0.12); }
.dash-quick-icon.teal i { color: #00d4aa !important; }
.dash-quick-icon.yellow { background: rgba(251, 191, 36, 0.12); }
.dash-quick-icon.yellow i { color: #fbbf24 !important; }

.dash-quick-text h4 {
    font-size: 0.85rem;
    font-weight: 600;
    color: #e8ede7 !important;
    margin-bottom: 0.1rem;
}

.dash-quick-text p {
    font-size: 0.75rem;
    color: rgba(232, 237, 231, 0.5) !important;
    margin: 0;
}

/* Pending Item */
.dash-pending-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.85rem 0;
    border-bottom: 1px solid rgba(0, 212, 170, 0.06) !important;
}

.dash-pending-item:last-child {
    border-bottom: none !important;
}

.dash-pending-info .name {
    font-weight: 500;
    color: #e8ede7 !important;
    font-size: 0.9rem;
}

.dash-pending-info .time {
    font-size: 0.8rem;
    color: rgba(232, 237, 231, 0.5) !important;
}

.dash-pending-actions {
    display: flex;
    gap: 0.4rem;
}

.dash-pending-actions .btn {
    padding: 0.35rem 0.65rem;
    font-size: 0.75rem;
    border-radius: 6px;
}

.dash-btn-confirm {
    background: rgba(0, 212, 170, 0.12) !important;
    border: 1px solid rgba(0, 212, 170, 0.2) !important;
    color: #00d4aa !important;
}

.dash-btn-confirm:hover {
    background: #00d4aa !important;
    color: #060d1f !important;
}

.dash-btn-view {
    background: rgba(59, 130, 246, 0.1) !important;
    border: 1px solid rgba(59, 130, 246, 0.15) !important;
    color: #60a5fa !important;
}

.dash-btn-view:hover {
    background: #60a5fa !important;
    color: #060d1f !important;
}

/* Review Item */
.dash-review-item {
    padding: 0.85rem 0;
    border-bottom: 1px solid rgba(0, 212, 170, 0.06) !important;
}

.dash-review-item:last-child {
    border-bottom: none !important;
}

.dash-review-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.4rem;
}

.dash-review-stars {
    display: flex;
    gap: 2px;
}

.dash-review-stars i {
    font-size: 0.75rem;
    color: #fbbf24 !important;
}

.dash-review-stars i.empty {
    color: rgba(232, 237, 231, 0.2) !important;
}

.dash-review-time {
    font-size: 0.75rem;
    color: rgba(232, 237, 231, 0.4) !important;
}

.dash-review-comment {
    font-size: 0.85rem;
    color: rgba(232, 237, 231, 0.7) !important;
    margin-bottom: 0.25rem;
}

.dash-review-author {
    font-size: 0.75rem;
    color: rgba(232, 237, 231, 0.45) !important;
}

/* Empty State */
.dash-empty {
    text-align: center;
    padding: 2.5rem 1rem;
}

.dash-empty i {
    font-size: 2.5rem;
    color: rgba(232, 237, 231, 0.2) !important;
    margin-bottom: 0.75rem;
}

.dash-empty h4 {
    font-size: 1rem;
    font-weight: 600;
    color: #e8ede7 !important;
    margin-bottom: 0.3rem;
}

.dash-empty p {
    font-size: 0.85rem;
    color: rgba(232, 237, 231, 0.5) !important;
    margin-bottom: 1rem;
}

.dash-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.6rem 1rem;
    background: rgba(0, 212, 170, 0.1) !important;
    border: 1px solid rgba(0, 212, 170, 0.2) !important;
    border-radius: 8px;
    color: #00d4aa !important;
    font-size: 0.85rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
}

.dash-btn-primary:hover {
    background: #00d4aa !important;
    color: #060d1f !important;
}

/* Completed Item */
.dash-completed-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.85rem 0;
    border-bottom: 1px solid rgba(0, 212, 170, 0.06) !important;
}

.dash-completed-item:last-child {
    border-bottom: none !important;
}

.dash-completed-check {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(0, 212, 170, 0.1) !important;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.dash-completed-check i {
    color: #00d4aa !important;
    font-size: 0.9rem;
}

.dash-completed-info {
    flex: 1;
}

.dash-completed-info .name {
    font-weight: 500;
    color: #e8ede7 !important;
    font-size: 0.9rem;
}

.dash-completed-info .reason {
    font-size: 0.8rem;
    color: rgba(232, 237, 231, 0.5) !important;
}

.dash-completed-time {
    text-align: right;
}

.dash-completed-time .date {
    font-size: 0.8rem;
    color: rgba(232, 237, 231, 0.6) !important;
    font-weight: 500;
}

.dash-completed-time .when {
    font-size: 0.7rem;
    color: rgba(232, 237, 231, 0.4) !important;
}

/* Two Column Layout */
.dash-row {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 1.5rem;
}

.dash-col-main {
    min-width: 0;
}

.dash-col-side {
    min-width: 0;
}

/* Note Item */
.dash-note-item {
    padding: 0.85rem 0;
    border-bottom: 1px solid rgba(0, 212, 170, 0.06) !important;
}

.dash-note-item:last-child {
    border-bottom: none !important;
}

.dash-note-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.35rem;
}

.dash-note-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.2rem 0.5rem;
    border-radius: 20px;
    font-size: 0.7rem;
    background: rgba(0, 212, 170, 0.1) !important;
    color: #00d4aa !important;
}

.dash-note-badge.general {
    background: rgba(59, 130, 246, 0.1) !important;
    color: #60a5fa !important;
}

.dash-note-badge.diagnosis {
    background: rgba(251, 191, 36, 0.1) !important;
    color: #fbbf24 !important;
}

.dash-note-badge.treatment {
    background: rgba(168, 85, 247, 0.1) !important;
    color: #a78bfa !important;
}

.dash-note-time {
    font-size: 0.7rem;
    color: rgba(232, 237, 231, 0.4) !important;
}

.dash-note-title {
    font-size: 0.9rem;
    font-weight: 500;
    color: #e8ede7 !important;
    margin-bottom: 0.2rem;
}

.dash-note-preview {
    font-size: 0.8rem;
    color: rgba(232, 237, 231, 0.5) !important;
    margin-bottom: 0.25rem;
}

.dash-note-patient {
    font-size: 0.75rem;
    color: rgba(232, 237, 231, 0.4) !important;
}

/* View All Link */
.dash-view-all {
    display: block;
    text-align: center;
    padding: 0.75rem;
    margin-top: 0.5rem;
    color: #00d4aa !important;
    font-size: 0.8rem;
    text-decoration: none;
    border-top: 1px solid rgba(0, 212, 170, 0.08) !important;
}

.dash-view-all:hover {
    color: #00eabb !important;
}

/* Alert Styles */
.dash-alert {
    background: rgba(59, 130, 246, 0.08) !important;
    border: 1px solid rgba(59, 130, 246, 0.2) !important;
    border-radius: 10px;
    padding: 0.85rem 1rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.dash-alert.warning {
    background: rgba(251, 191, 36, 0.08) !important;
    border-color: rgba(251, 191, 36, 0.2) !important;
}

.dash-alert-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.dash-alert-info i {
    color: #60a5fa !important;
    font-size: 1.1rem;
}

.dash-alert.warning i {
    color: #fbbf24 !important;
}

.dash-alert-text strong {
    color: #e8ede7 !important;
    font-size: 0.9rem;
}

.dash-alert-text p {
    color: rgba(232, 237, 231, 0.6) !important;
    font-size: 0.8rem;
    margin: 0;
}

.dash-alert-actions {
    display: flex;
    gap: 0.5rem;
}

.dash-alert-btn {
    padding: 0.4rem 0.8rem;
    border-radius: 6px;
    font-size: 0.75rem;
    text-decoration: none;
}

.dash-alert-btn.outline {
    background: transparent !important;
    border: 1px solid rgba(0, 212, 170, 0.3) !important;
    color: #00d4aa !important;
}

.dash-alert-btn.outline:hover {
    background: rgba(0, 212, 170, 0.1) !important;
}

/* Responsive */
@media (max-width: 991px) {
    .dash-row {
        grid-template-columns: 1fr;
    }

    .dash-stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 575px) {
    .dash-page {
        padding: 1rem;
    }

    .dash-stats-grid {
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }

    .dash-quick-grid {
        grid-template-columns: 1fr;
    }

    .dash-appt-item {
        flex-wrap: wrap;
    }

    .dash-appt-action {
        width: 100%;
        text-align: center;
        margin-top: 0.5rem;
    }
}
</style>
@endpush

@section('content')
<div class="doctor-page" style="background: #060d1f !important;">
<div class="doctor-container" style="background: #060d1f !important;">
<div class="dash-page" style="background: #060d1f !important;">

    <!-- Impersonation Alerts -->
    @if(session('impersonating_admin_id') && session('impersonating_hospital_admin_id') && session('hospital_admin_impersonation_started_at') && !empty(session('hospital_admin_impersonation_started_at')))
        <div class="dash-alert">
            <div class="dash-alert-info">
                <i class="fas fa-users"></i>
                <div class="dash-alert-text">
                    <strong>Chain Impersonation Mode</strong>
                    <p class="mb-0">{{ session('impersonating_admin_name', 'Admin') }} → {{ session('impersonating_hospital_admin_name') }} → Dr. {{ auth()->user()->name }}</p>
                </div>
            </div>
            <div class="dash-alert-actions">
                <form method="POST" action="{{ route('return-to-hospital-admin') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="dash-alert-btn outline">Return to Hospital Admin</button>
                </form>
                <form method="POST" action="{{ route('return-to-admin') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="dash-alert-btn outline" style="background: rgba(0,212,170,0.1) !important;">Return to Admin</button>
                </form>
            </div>
        </div>
    @elseif(session('impersonating_hospital_admin_id') && empty(session('impersonating_admin_id')))
        <div class="dash-alert warning">
            <div class="dash-alert-info">
                <i class="fas fa-user-shield"></i>
                <div class="dash-alert-text">
                    <strong>Hospital Admin Mode</strong>
                    <p class="mb-0">You are viewing this dashboard as {{ session('impersonating_hospital_admin_name') }}</p>
                </div>
            </div>
            <div class="dash-alert-actions">
                <form method="POST" action="{{ route('return-to-hospital-admin') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="dash-alert-btn outline">Return to Hospital Admin</button>
                </form>
            </div>
        </div>
    @endif

    <!-- Header -->
    <div class="dash-header">
        <h1>Welcome back, Dr. {{ explode(' ', $doctor->user->name)[1] ?? $doctor->user->name }}</h1>
        <p>Here's what's happening with your practice today</p>
    </div>

    <!-- Doctor Card -->
    <div class="dash-doctor-card">
        <div class="dash-doctor-avatar">
            <i class="fas fa-user-md"></i>
        </div>
        <div class="dash-doctor-info">
            <h3>Dr. {{ $doctor->user->name }}</h3>
            <div class="specialty">{{ $doctor->specialty->name }}</div>
            <div class="date">{{ now()->format('l, F j, Y') }}</div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="dash-stats-grid">
        <div class="dash-stat-card">
            <div class="dash-stat-icon blue">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div class="dash-stat-number">{{ $stats['today_appointments'] }}</div>
            <div class="dash-stat-label">Today's Appointments</div>
        </div>
        <div class="dash-stat-card">
            <div class="dash-stat-icon yellow">
                <i class="fas fa-clock"></i>
            </div>
            <div class="dash-stat-number">{{ $stats['pending_appointments'] }}</div>
            <div class="dash-stat-label">Pending Approval</div>
        </div>
        <div class="dash-stat-card">
            <div class="dash-stat-icon teal">
                <i class="fas fa-star"></i>
            </div>
            <div class="dash-stat-number">{{ number_format($stats['average_rating'], 1) }}</div>
            <div class="dash-stat-label">Average Rating</div>
        </div>
        <div class="dash-stat-card">
            <div class="dash-stat-icon purple">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="dash-stat-number">${{ number_format($stats['revenue_this_month'], 0) }}</div>
            <div class="dash-stat-label">This Month</div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="dash-row">
        <div class="dash-col-main">

            <!-- Today's Schedule -->
            <div class="dash-section">
                <div class="dash-section-header">
                    <h3 class="dash-section-title">
                        <i class="fas fa-calendar-day"></i>
                        Today's Schedule
                    </h3>
                    <small style="color: rgba(232,237,231,0.4) !important;">{{ now()->format('l, F j') }}</small>
                </div>
                <div class="dash-section-body">
                    @if($todayAppointments->count() > 0)
                        @foreach($todayAppointments as $appointment)
                            <div class="dash-appt-item">
                                <div class="dash-appt-time">
                                    <div class="time">{{ $appointment->appointment_date->format('g:i A') }}</div>
                                    <div class="duration">{{ $appointment->appointment_date->diffInMinutes($appointment->appointment_end) }}min</div>
                                </div>
                                <div class="dash-appt-dot {{ $appointment->status }}"></div>
                                <div class="dash-appt-info">
                                    <div class="name">{{ $appointment->patient_name }}</div>
                                    <div class="reason">{{ Str::limit($appointment->reason, 50) }}</div>
                                    <div class="dash-appt-type-badge {{ $appointment->appointment_type == 'video_call' ? 'video' : ($appointment->appointment_type == 'phone_call' ? 'phone' : '') }}">
                                        <i class="fas fa-{{ $appointment->appointment_type == 'video_call' ? 'video' : ($appointment->appointment_type == 'phone_call' ? 'phone' : 'hospital') }}"></i>
                                        {{ ucfirst(str_replace('_', ' ', $appointment->appointment_type)) }}
                                    </div>
                                </div>
                                <span class="dash-appt-status {{ $appointment->status }}">{{ ucfirst($appointment->status) }}</span>
                                <a href="{{ route('doctor.appointments.show', $appointment) }}" class="dash-appt-action">View</a>
                            </div>
                        @endforeach
                    @else
                        <div class="dash-empty">
                            <i class="fas fa-calendar-check"></i>
                            <h4>No appointments today</h4>
                            <p>Your schedule is clear. Time to catch up on other tasks!</p>
                            <a href="{{ route('doctor.appointments.create') }}" class="dash-btn-primary">
                                <i class="fas fa-plus"></i>Schedule Appointment
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recently Completed -->
            <div class="dash-section">
                <div class="dash-section-header">
                    <h3 class="dash-section-title">
                        <i class="fas fa-check-circle"></i>
                        Recently Completed
                    </h3>
                </div>
                <div class="dash-section-body">
                    @if(isset($recentCompletedAppointments) && $recentCompletedAppointments->count() > 0)
                        @foreach($recentCompletedAppointments->take(5) as $appointment)
                            <div class="dash-completed-item">
                                <div class="dash-completed-check">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="dash-completed-info">
                                    <div class="name">{{ $appointment->patient_name }}</div>
                                    <div class="reason">{{ Str::limit($appointment->reason, 45) }}</div>
                                </div>
                                <div class="dash-completed-time">
                                    <div class="date">{{ $appointment->completed_at ? $appointment->completed_at->format('M j') : $appointment->appointment_end->format('M j') }}</div>
                                    <div class="when">{{ $appointment->completed_at ? $appointment->completed_at->diffForHumans() : $appointment->appointment_end->diffForHumans() }}</div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="dash-empty">
                            <i class="fas fa-check-circle"></i>
                            <h4>No completed appointments</h4>
                            <p>Completed appointments will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <div class="dash-col-side">

            <!-- Quick Actions -->
            <div class="dash-section">
                <div class="dash-section-header">
                    <h3 class="dash-section-title">
                        <i class="fas fa-bolt"></i>
                        Quick Actions
                    </h3>
                </div>
                <div class="dash-section-body">
                    <div class="dash-quick-grid">
                        <a href="{{ route('doctor.appointments.index') }}" class="dash-quick-btn">
                            <div class="dash-quick-icon blue">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="dash-quick-text">
                                <h4>Appointments</h4>
                                <p>Manage schedule</p>
                            </div>
                        </a>
                        <a href="{{ route('doctor.availability.index') }}" class="dash-quick-btn">
                            <div class="dash-quick-icon teal">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="dash-quick-text">
                                <h4>Availability</h4>
                                <p>Set hours</p>
                            </div>
                        </a>
                        <a href="{{ route('doctor.notes.create') }}" class="dash-quick-btn">
                            <div class="dash-quick-icon teal">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="dash-quick-text">
                                <h4>Add Note</h4>
                                <p>New patient note</p>
                            </div>
                        </a>
                        <a href="{{ route('doctor.reviews.index') }}" class="dash-quick-btn">
                            <div class="dash-quick-icon yellow">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="dash-quick-text">
                                <h4>Reviews</h4>
                                <p>Patient feedback</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Pending Appointments -->
            @if($pendingAppointments->count() > 0)
                <div class="dash-section">
                    <div class="dash-section-header">
                        <h3 class="dash-section-title">
                            <i class="fas fa-clock"></i>
                            Pending
                        </h3>
                    </div>
                    <div class="dash-section-body">
                        @foreach($pendingAppointments as $appointment)
                            <div class="dash-pending-item">
                                <div class="dash-pending-info">
                                    <div class="name">{{ $appointment->patient_name }}</div>
                                    <div class="time">{{ $appointment->appointment_date->format('M j, g:i A') }}</div>
                                </div>
                                <div class="dash-pending-actions">
                                    <form method="POST" action="{{ route('doctor.appointments.confirm', $appointment) }}">
                                        @csrf
                                        <button type="submit" class="btn dash-btn-confirm" title="Confirm">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('doctor.appointments.show', $appointment) }}" class="btn dash-btn-view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                        <a href="{{ route('doctor.appointments.index', ['status' => 'pending']) }}" class="dash-view-all">View all pending →</a>
                    </div>
                </div>
            @endif

            <!-- Recent Reviews -->
            @if($recentReviews->count() > 0)
                <div class="dash-section">
                    <div class="dash-section-header">
                        <h3 class="dash-section-title">
                            <i class="fas fa-star"></i>
                            Recent Reviews
                        </h3>
                    </div>
                    <div class="dash-section-body">
                        @foreach($recentReviews as $review)
                            <div class="dash-review-item">
                                <div class="dash-review-header">
                                    <div class="dash-review-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rating ? '' : 'empty' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="dash-review-time">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                @if($review->comment)
                                    <div class="dash-review-comment">{{ Str::limit($review->comment, 60) }}</div>
                                @endif
                                <div class="dash-review-author">by {{ $review->is_anonymous ? 'Anonymous' : $review->patient_name }}</div>
                            </div>
                        @endforeach
                        <a href="{{ route('doctor.reviews.index') }}" class="dash-view-all">View all reviews →</a>
                    </div>
                </div>
            @endif

            <!-- Recent Notes -->
            @if($recentNotes->count() > 0)
                <div class="dash-section">
                    <div class="dash-section-header">
                        <h3 class="dash-section-title">
                            <i class="fas fa-sticky-note"></i>
                            Recent Notes
                        </h3>
                    </div>
                    <div class="dash-section-body">
                        @foreach($recentNotes as $note)
                            <div class="dash-note-item">
                                <div class="dash-note-header">
                                    <span class="dash-note-badge {{ $note->note_type ?? 'general' }}">
                                        <i class="{{ $note->getTypeIcon() ?? 'fas fa-file' }}"></i>
                                        {{ ucfirst($note->note_type ?? 'General') }}
                                    </span>
                                    <span class="dash-note-time">{{ $note->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="dash-note-title">{{ $note->getDisplayTitle() }}</div>
                                <div class="dash-note-preview">{{ $note->getPreview(50) }}</div>
                                <div class="dash-note-patient">
                                    <i class="fas fa-user"></i>
                                    {{ $note->patient ? $note->patient->name : 'General Note' }}
                                </div>
                            </div>
                        @endforeach
                        <a href="{{ route('doctor.notes.index') }}" class="dash-view-all">View all notes →</a>
                    </div>
                </div>
            @endif

        </div>
    </div>

</div>
</div>
</div>

<script>
const chartLabels = @json($chartLabels ?? []);
const chartData = @json($chartData ?? []);
const records = @json($records ?? []);
</script>
@endsection
