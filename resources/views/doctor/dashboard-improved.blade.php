@extends('layouts.doctor')

@section('title', 'Doctor Dashboard')

@push('styles')
<style>
/* Dashboard-specific styles using new design tokens */
.dashboard-page {
    padding: var(--space-xl);
    background: var(--bg-primary);
    min-height: calc(100vh - var(--topbar-height));
}

.dashboard-container {
    max-width: 1400px;
    margin: 0 auto;
}

/* Dashboard Header */
.dashboard-header-card {
    background: linear-gradient(135deg, #0a1628 0%, #0f1c3a 100%);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-2xl);
    padding: var(--space-2xl);
    margin-bottom: var(--space-xl);
    position: relative;
    overflow: hidden;
}

.dashboard-header-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--primary), transparent);
}

.header-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: var(--space-lg);
}

.greeting h1 {
    font-size: var(--font-3xl);
    font-weight: var(--font-bold);
    color: var(--text-primary);
    margin: 0 0 var(--space-xs) 0;
}

.greeting p {
    font-size: var(--font-base);
    color: var(--text-secondary);
    margin: 0;
}

.header-actions {
    display: flex;
    gap: var(--space-sm);
}

/* Quick Stats Bar */
.quick-stats-bar {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--space-md);
    margin-bottom: var(--space-xl);
}

.quick-stat {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-xl);
    padding: var(--space-lg);
    display: flex;
    align-items: center;
    gap: var(--space-md);
    transition: all var(--transition-base);
}

.quick-stat:hover {
    border-color: var(--border-color-hover);
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.quick-stat-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: var(--font-xl);
    flex-shrink: 0;
}

.quick-stat-content {
    flex: 1;
}

.quick-stat-value {
    font-size: var(--font-2xl);
    font-weight: var(--font-bold);
    color: var(--text-primary);
    line-height: 1.2;
    margin-bottom: var(--space-2xs);
}

.quick-stat-label {
    font-size: var(--font-sm);
    color: var(--text-muted);
}

/* Dashboard Grid */
.dashboard-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: var(--space-xl);
}

.dashboard-grid.single-column {
    grid-template-columns: 1fr;
}

/* Appointment Timeline */
.appointment-timeline {
    position: relative;
    padding-left: var(--space-xl);
}

.appointment-timeline::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--border-color);
}

.timeline-item {
    position: relative;
    padding-bottom: var(--space-lg);
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -28px;
    top: 8px;
    width: 16px;
    height: 16px;
    border-radius: var(--radius-full);
    background: var(--primary);
    border: 3px solid var(--bg-primary);
}

.timeline-item.completed::before {
    background: var(--success);
}

.timeline-item.pending::before {
    background: var(--warning);
}

.timeline-item.cancelled::before {
    background: var(--danger);
}

.timeline-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: var(--space-md);
    transition: all var(--transition-base);
}

.timeline-card:hover {
    border-color: var(--border-color-hover);
    box-shadow: var(--shadow-md);
}

.timeline-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: var(--space-sm);
}

.timeline-time {
    font-size: var(--font-sm);
    font-weight: var(--font-semibold);
    color: var(--primary);
}

.timeline-actions {
    display: flex;
    gap: var(--space-xs);
}

.timeline-body {
    margin-bottom: var(--space-sm);
}

.patient-name {
    font-size: var(--font-base);
    font-weight: var(--font-medium);
    color: var(--text-primary);
    margin-bottom: var(--space-2xs);
}

.appointment-type {
    font-size: var(--font-xs);
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: var(--space-2xs);
}

.timeline-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: var(--space-sm);
    border-top: 1px solid var(--border-color);
}

.risk-badge {
    padding: var(--space-2xs) var(--space-xs);
    border-radius: var(--radius-full);
    font-size: var(--font-xs);
    font-weight: var(--font-semibold);
}

.risk-badge.low {
    background: rgba(16, 185, 129, 0.12);
    color: var(--success);
}

.risk-badge.medium {
    background: rgba(245, 158, 11, 0.12);
    color: var(--warning);
}

.risk-badge.high {
    background: rgba(239, 68, 68, 0.12);
    color: var(--danger);
}

/* Side Panel Cards */
.side-panel {
    display: flex;
    flex-direction: column;
    gap: var(--space-xl);
}

/* Quick Actions Grid */
.quick-actions-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--space-md);
}

.quick-action-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: var(--space-md);
    text-decoration: none;
    color: var(--text-primary);
    transition: all var(--transition-base);
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: var(--space-sm);
}

.quick-action-card:hover {
    border-color: var(--border-color-hover);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    color: var(--text-primary);
}

.quick-action-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--radius-lg);
    background: var(--primary-dim);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    font-size: var(--font-xl);
}

.quick-action-title {
    font-size: var(--font-sm);
    font-weight: var(--font-medium);
}

.quick-action-desc {
    font-size: var(--font-xs);
    color: var(--text-muted);
    margin-top: 2px;
}

.quick-action-card.featured {
    border: 2px solid var(--primary);
    background: linear-gradient(135deg, rgba(0, 212, 170, 0.1), rgba(0, 212, 170, 0.05));
}

.quick-action-card.featured .quick-action-icon {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: var(--bg-primary);
}

.quick-action-card.featured .quick-action-title {
    color: var(--primary);
}

/* Pending List */
.pending-list {
    display: flex;
    flex-direction: column;
    gap: var(--space-sm);
}

.pending-item {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: var(--space-md);
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all var(--transition-base);
}

.pending-item:hover {
    border-color: var(--border-color-hover);
}

.pending-info {
    flex: 1;
}

.pending-info .name {
    font-size: var(--font-sm);
    font-weight: var(--font-medium);
    color: var(--text-primary);
    margin-bottom: var(--space-2xs);
}

.pending-info .time {
    font-size: var(--font-xs);
    color: var(--text-muted);
}

.pending-actions {
    display: flex;
    gap: var(--space-xs);
}

/* Activity Feed */
.activity-feed {
    display: flex;
    flex-direction: column;
    gap: var(--space-md);
}

.activity-item {
    display: flex;
    gap: var(--space-md);
    padding-bottom: var(--space-md);
    border-bottom: 1px solid var(--border-color);
}

.activity-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.activity-icon {
    width: 32px;
    height: 32px;
    border-radius: var(--radius-full);
    background: var(--primary-dim);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    font-size: var(--font-sm);
    flex-shrink: 0;
}

.activity-content {
    flex: 1;
}

.activity-title {
    font-size: var(--font-sm);
    color: var(--text-primary);
    margin-bottom: var(--space-2xs);
}

.activity-time {
    font-size: var(--font-xs);
    color: var(--text-muted);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: var(--space-3xl) var(--space-xl);
}

.empty-state-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto var(--space-md);
    border-radius: var(--radius-full);
    background: var(--bg-elevated);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    font-size: var(--font-3xl);
}

.empty-state-title {
    font-size: var(--font-lg);
    font-weight: var(--font-semibold);
    color: var(--text-primary);
    margin-bottom: var(--space-xs);
}

.empty-state-text {
    font-size: var(--font-sm);
    color: var(--text-muted);
    margin-bottom: var(--space-md);
}

/* Responsive */
@media (max-width: 1024px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
    
    .quick-stats-bar {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .dashboard-page {
        padding: var(--space-md);
    }
    
    .header-top {
        flex-direction: column;
        align-items: flex-start;
        gap: var(--space-md);
    }
    
    .quick-stats-bar {
        grid-template-columns: 1fr;
    }
    
    .quick-actions-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')
<div class="dashboard-page">
    <div class="dashboard-container">
        <!-- Dashboard Header -->
        <div class="dashboard-header-card">
            <div class="header-top">
                <div class="greeting">
                    @php
                        $hour = now()->hour;
                        $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
                    @endphp
                    <h1>{{ $greeting }}, Dr. {{ explode(' ', $doctor->user->name)[1] ?? $doctor->user->name }}!</h1>
                    <p>Here's what's happening with your practice today</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('ai.ambient-listening.index') }}" class="btn btn-primary">
                        <i class="fas fa-microphone"></i>
                        <span>Start Consultation</span>
                    </a>
                    <a href="{{ route('doctor.appointments.create') }}" class="btn btn-secondary">
                        <i class="fas fa-plus"></i>
                        <span>New Appointment</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Stats Bar - OPERATIONAL METRICS ONLY -->
        <div class="quick-stats-bar">
            <div class="quick-stat">
                <div class="quick-stat-icon primary">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="quick-stat-content">
                    <div class="quick-stat-value">{{ $stats['today_appointments'] }}</div>
                    <div class="quick-stat-label">Today's Appointments</div>
                </div>
            </div>

            <div class="quick-stat">
                <div class="quick-stat-icon warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="quick-stat-content">
                    <div class="quick-stat-value">{{ $stats['pending_appointments'] }}</div>
                    <div class="quick-stat-label">Pending Approval</div>
                </div>
            </div>

            <div class="quick-stat">
                <div class="quick-stat-icon success">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="quick-stat-content">
                    <div class="quick-stat-value">{{ $stats['completed_today'] ?? 0 }}</div>
                    <div class="quick-stat-label">Completed Today</div>
                </div>
            </div>

            <div class="quick-stat">
                <div class="quick-stat-icon info">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="quick-stat-content">
                    <div class="quick-stat-value">{{ $stats['high_risk_patients'] ?? 0 }}</div>
                    <div class="quick-stat-label">High Risk Patients</div>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Grid -->
        <div class="dashboard-grid">
            <!-- Main Column -->
            <div class="main-column">
                <!-- Today's Schedule -->
                <div class="section-card">
                    <div class="section-card-header">
                        <h3 class="section-card-title">
                            <i class="fas fa-calendar-day"></i>
                            Today's Schedule
                        </h3>
                        <a href="{{ route('doctor.on-deck') }}" class="btn btn-sm btn-outline">
                            View Full Queue
                        </a>
                    </div>
                    <div class="section-card-body">
                        @if($todayAppointments->count() > 0)
                            <div class="appointment-timeline">
                                @foreach($todayAppointments as $appointment)
                                    <div class="timeline-item {{ $appointment->status }}">
                                        <div class="timeline-card">
                                            <div class="timeline-header">
                                                <span class="timeline-time">{{ $appointment->appointment_date->format('g:i A') }}</span>
                                                <div class="timeline-actions">
                                                    @if($appointment->status == 'pending')
                                                        <form method="POST" action="{{ route('doctor.appointments.confirm', $appointment->id) }}" style="display: inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-primary">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    @if(in_array($appointment->status, ['confirmed', 'completed']))
                                                        <a href="{{ route('doctor.consultation-wizard', ['appointment_id' => $appointment->id, 'patient_id' => $appointment->patient_id]) }}" 
                                                           class="btn btn-sm btn-success" 
                                                           title="Start Guided Consultation Wizard">
                                                            <i class="fas fa-play"></i>
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('doctor.appointments.show', $appointment->id) }}" class="btn btn-sm btn-secondary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="timeline-body">
                                                <div class="patient-name">{{ $appointment->patient_name }}</div>
                                                <div class="appointment-type">
                                                    <i class="fas fa-{{ $appointment->appointment_type == 'video_call' ? 'video' : ($appointment->appointment_type == 'phone_call' ? 'phone' : 'hospital') }}"></i>
                                                    <span>{{ ucfirst(str_replace('_', ' ', $appointment->appointment_type)) }}</span>
                                                    @if($appointment->reason)
                                                        <span>• {{ Str::limit($appointment->reason, 40) }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="timeline-footer">
                                                <span class="badge badge-{{ $appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($appointment->status) }}
                                                </span>
                                                @if($appointment->patient && $appointment->patient->risk_score)
                                                    <span class="risk-badge {{ $appointment->patient->risk_score >= 70 ? 'high' : ($appointment->patient->risk_score >= 40 ? 'medium' : 'low') }}">
                                                        Risk: {{ $appointment->patient->risk_score }}%
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="empty-state-title">No appointments today</div>
                                <div class="empty-state-text">You have a clear schedule for today</div>
                                <a href="{{ route('doctor.appointments.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i>
                                    Schedule Appointment
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Side Panel -->
            <div class="side-panel">
                <!-- Quick Actions - SIMPLIFIED -->
                <div class="section-card">
                    <div class="section-card-header">
                        <h3 class="section-card-title">
                            <i class="fas fa-bolt"></i>
                            Quick Actions
                        </h3>
                    </div>
                    <div class="section-card-body">
                        <div class="quick-actions-grid">
                            <a href="{{ route('doctor.consultation-wizard') }}" class="quick-action-card featured">
                                <div class="quick-action-icon">
                                    <i class="fas fa-magic"></i>
                                </div>
                                <div class="quick-action-title">Guided Consultation</div>
                                <div class="quick-action-desc">AI-powered step-by-step wizard</div>
                            </a>
                            <a href="{{ route('doctor.on-deck') }}" class="quick-action-card">
                                <div class="quick-action-icon">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                                <div class="quick-action-title">Today's Queue</div>
                            </a>
                            <a href="{{ route('doctor.patients.index') }}" class="quick-action-card">
                                <div class="quick-action-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="quick-action-title">My Patients</div>
                            </a>
                            <a href="{{ route('doctor.notes.index') }}" class="quick-action-card">
                                <div class="quick-action-icon">
                                    <i class="fas fa-sticky-note"></i>
                                </div>
                                <div class="quick-action-title">Doctor Notes</div>
                            </a>
                        </div>
                        <div style="margin-top: 1rem; padding: 0.75rem; background: rgba(0,212,170,0.05); border-radius: 8px; border-left: 3px solid var(--primary);">
                            <p style="font-size: 0.75rem; color: var(--text-muted); margin: 0;">
                                <i class="fas fa-lightbulb" style="color: var(--warning);"></i>
                                <strong>Pro Tip:</strong> Use <kbd style="padding: 2px 6px; background: var(--bg-elevated); border-radius: 4px; font-size: 0.7rem;">Ctrl+K</kbd> to search patients instantly
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Pending Approvals -->
                @if($pendingAppointments->count() > 0)
                <div class="section-card">
                    <div class="section-card-header">
                        <h3 class="section-card-title">
                            <i class="fas fa-clock"></i>
                            Pending Approval
                        </h3>
                        <span class="badge badge-warning">{{ $pendingAppointments->count() }}</span>
                    </div>
                    <div class="section-card-body">
                        <div class="pending-list">
                            @foreach($pendingAppointments->take(3) as $appointment)
                                <div class="pending-item">
                                    <div class="pending-info">
                                        <div class="name">{{ $appointment->patient_name }}</div>
                                        <div class="time">{{ $appointment->appointment_date->format('M j, g:i A') }}</div>
                                    </div>
                                    <div class="pending-actions">
                                        <form method="POST" action="{{ route('doctor.appointments.confirm', $appointment->id) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary" title="Confirm">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('doctor.appointments.cancel', $appointment->id) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" title="Cancel">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($pendingAppointments->count() > 3)
                            <div style="text-align: center; margin-top: var(--space-md);">
                                <a href="{{ route('doctor.appointments.index') }}" class="btn btn-sm btn-outline">
                                    View All ({{ $pendingAppointments->count() }})
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Needs Attention - ACTIONABLE ITEMS -->
                <div class="section-card">
                    <div class="section-card-header">
                        <h3 class="section-card-title">
                            <i class="fas fa-bell"></i>
                            Needs Attention
                        </h3>
                        @php
                            $attentionCount = ($unreadMessages ?? 0) + ($pendingFollowUps ?? 0) + ($unreviewedLabs ?? 0);
                        @endphp
                        @if($attentionCount > 0)
                            <span class="badge badge-danger">{{ $attentionCount }}</span>
                        @endif
                    </div>
                    <div class="section-card-body">
                        <div class="activity-feed">
                            @if(($unreadMessages ?? 0) > 0)
                                <a href="{{ route('doctor.messages.index') }}" class="activity-item" style="text-decoration: none; display: flex;">
                                    <div class="activity-icon" style="background: rgba(99, 102, 241, 0.12); color: #6366f1;">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title" style="font-weight: 600;">{{ $unreadMessages }} Unread Message(s)</div>
                                        <div class="activity-time">Click to view messages</div>
                                    </div>
                                    <div style="margin-left: auto; color: var(--text-muted);">
                                        <i class="fas fa-chevron-right"></i>
                                    </div>
                                </a>
                            @endif

                            @if(($pendingFollowUps ?? 0) > 0)
                                <a href="{{ route('doctor.appointments.index', ['filter' => 'followup']) }}" class="activity-item" style="text-decoration: none; display: flex;">
                                    <div class="activity-icon" style="background: rgba(251, 191, 36, 0.12); color: var(--warning);">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title" style="font-weight: 600;">{{ $pendingFollowUps }} Pending Follow-up(s)</div>
                                        <div class="activity-time">Schedule upcoming visits</div>
                                    </div>
                                    <div style="margin-left: auto; color: var(--text-muted);">
                                        <i class="fas fa-chevron-right"></i>
                                    </div>
                                </a>
                            @endif

                            @if(($unreviewedLabs ?? 0) > 0)
                                <div class="activity-item">
                                    <div class="activity-icon" style="background: rgba(248, 113, 113, 0.12); color: var(--danger);">
                                        <i class="fas fa-flask"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title" style="font-weight: 600;">{{ $unreviewedLabs }} Lab Result(s) Pending</div>
                                        <div class="activity-time">Results will appear when available</div>
                                    </div>
                                </div>
                            @endif

                            @if((($unreadMessages ?? 0) + ($pendingFollowUps ?? 0) + ($unreviewedLabs ?? 0)) == 0)
                                <div class="empty-state" style="padding: var(--space-xl); text-align: center;">
                                    <div style="font-size: 3rem; color: var(--success); margin-bottom: var(--space-sm);">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="empty-state-title" style="color: var(--success);">All Clear!</div>
                                    <div class="empty-state-text">Nothing needs your attention right now</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
