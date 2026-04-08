@extends('layouts.doctor')

@section('title', 'Cases Overview')

@push('styles')
<style>
/* Dark theme overrides */
body { background: var(--navy) !important; }
.card { background: var(--card-bg) !important; border: 1px solid var(--card-border) !important; border-radius: 16px !important; }
.card-header { background: rgba(0,212,170,0.05) !important; border-bottom: 1px solid var(--card-border) !important; color: var(--offwhite) !important; }
.card-body { background: transparent !important; }
.card-footer { background: rgba(0,212,170,0.03) !important; border-top: 1px solid var(--card-border) !important; }
.form-control, .form-select { background: rgba(10,20,40,0.8) !important; border: 1px solid var(--card-border) !important; color: var(--offwhite) !important; border-radius: 10px !important; }
.form-control:focus, .form-select:focus { border-color: rgba(0,212,170,0.5) !important; box-shadow: 0 0 0 3px rgba(0,212,170,0.08) !important; }
.form-control::placeholder { color: rgba(232,237,231,0.25) !important; }
.form-label { color: var(--offwhite) !important; }
.text-muted { color: var(--muted) !important; }
.bg-primary { background: rgba(0,212,170,0.15) !important; }
.bg-success { background: rgba(0,212,170,0.15) !important; }
.bg-warning { background: rgba(251,191,36,0.15) !important; }
.bg-info { background: rgba(59,130,246,0.15) !important; }
.bg-light { background: rgba(255,255,255,0.04) !important; }
.bg-white { background: var(--card-bg) !important; }
.bg-secondary { background: rgba(255,255,255,0.06) !important; }
.text-primary { color: var(--teal) !important; }
.text-success { color: var(--teal) !important; }
.text-dark { color: var(--offwhite) !important; }
.text-white { color: var(--offwhite) !important; }
.text-danger { color: #f87171 !important; }
.text-warning { color: #fbbf24 !important; }
.btn-primary { background: var(--teal) !important; border-color: var(--teal) !important; color: var(--navy) !important; font-weight: 600; }
.btn-success { background: rgba(0,212,170,0.15) !important; border-color: rgba(0,212,170,0.3) !important; color: var(--teal) !important; }
.btn-danger { background: rgba(248,113,113,0.15) !important; border-color: rgba(248,113,113,0.3) !important; color: #f87171 !important; }
.btn-warning { background: rgba(251,191,36,0.15) !important; border-color: rgba(251,191,36,0.3) !important; color: #fbbf24 !important; }
.btn-info { background: rgba(59,130,246,0.15) !important; border-color: rgba(59,130,246,0.3) !important; color: #60a5fa !important; }
.btn-secondary { background: rgba(255,255,255,0.06) !important; border: 1px solid rgba(255,255,255,0.1) !important; color: var(--muted) !important; }
.btn-outline-primary { border-color: rgba(0,212,170,0.3) !important; color: var(--teal) !important; }
.alert-success { background: rgba(0,212,170,0.08) !important; border: 1px solid rgba(0,212,170,0.2) !important; color: var(--teal) !important; }
.alert-danger { background: rgba(248,113,113,0.08) !important; border: 1px solid rgba(248,113,113,0.2) !important; color: #f87171 !important; }
.alert-warning { background: rgba(251,191,36,0.08) !important; border: 1px solid rgba(251,191,36,0.2) !important; color: #fbbf24 !important; }
.alert-info { background: rgba(59,130,246,0.08) !important; border: 1px solid rgba(59,130,246,0.2) !important; color: #60a5fa !important; }
.border { border-color: var(--card-border) !important; }
.border-success { border-color: rgba(0,212,170,0.2) !important; }
.border-warning { border-color: rgba(251,191,36,0.2) !important; }
.fw-bold, .fw-semibold { color: var(--offwhite) !important; }
.table { color: var(--offwhite) !important; }
.table-hover tbody tr:hover { background-color: rgba(0,212,170,0.05) !important; }
.table td, .table th { border-color: var(--card-border) !important; color: var(--offwhite) !important; }
.pagination .page-link { background: rgba(10,20,40,0.8) !important; border-color: var(--card-border) !important; color: var(--offwhite) !important; }
.pagination .page-item.active .page-link { background: var(--teal) !important; border-color: var(--teal) !important; color: var(--navy) !important; }
.modal-content { background: var(--card-bg) !important; border: 1px solid var(--card-border) !important; }
.modal-header { background: rgba(0,212,170,0.05) !important; border-bottom: 1px solid var(--card-border) !important; color: var(--offwhite) !important; }
.modal-footer { background: rgba(0,212,170,0.03) !important; border-top: 1px solid var(--card-border) !important; }
.nav-pills .nav-link { color: var(--muted) !important; }
.nav-pills .nav-link.active { background: var(--teal) !important; color: var(--navy) !important; }
.badge { color: var(--navy) !important; font-weight: 600; }
.text-truncate { color: var(--offwhite) !important; }
.border-0 { border-color: transparent !important; }
.shadow-sm { box-shadow: none !important; }
.h4, h4 { color: var(--offwhite) !important; }
.display-4 { color: var(--offwhite) !important; }
.gender-badge-male { background: var(--teal) !important; color: var(--navy) !important; border-radius: 15px; padding: 0.4rem 0.8rem; }
.gender-badge-female { background: rgba(0,212,170,0.7) !important; color: var(--navy) !important; border-radius: 15px; padding: 0.4rem 0.8rem; }
</style>
@endpush

@section('content')
<div class="dashboard-container"><div class="container-fluid px-3 px-md-4">
<div class="dashboard-header">
    <h2>Diagnosed Cases</h2>
    <p>View and manage all patient diagnoses and medical records</p>
</div>

<!-- Quick Navigation Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <a href="{{ route('ai.ambient-listening.index') }}" class="text-decoration-none">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center py-4">
                    <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-microphone text-success fs-4"></i>
                    </div>
                    <h5 class="text-white">New Consultation</h5>
                    <p class="text-muted small mb-0">Start AI-powered</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('doctor.appointments.index') }}" class="text-decoration-none">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center py-4">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-calendar-check text-primary fs-4"></i>
                    </div>
                    <h5 class="text-white">Appointments</h5>
                    <p class="text-muted small mb-0">Manage schedule</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('doctor.patients.index') }}" class="text-decoration-none">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center py-4">
                    <div class="rounded-circle bg-info bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-users text-info fs-4"></i>
                    </div>
                    <h5 class="text-white">Patients</h5>
                    <p class="text-muted small mb-0">View records</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('diagnosis.index') }}" class="text-decoration-none">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center py-4">
                    <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-list text-warning fs-4"></i>
                    </div>
                    <h5 class="text-white">All Diagnoses</h5>
                    <p class="text-muted small mb-0">Full list view</p>
                </div>
            </div>
        </a>
    </div>
</div>

@push('styles')
<style>
/* Dark theme overrides */
body { background: var(--navy) !important; }
.card { background: var(--card-bg) !important; border: 1px solid var(--card-border) !important; border-radius: 16px !important; }
.card-header { background: rgba(0,212,170,0.05) !important; border-bottom: 1px solid var(--card-border) !important; color: var(--offwhite) !important; }
.card-body { background: transparent !important; }
.card-footer { background: rgba(0,212,170,0.03) !important; border-top: 1px solid var(--card-border) !important; }
.form-control, .form-select { background: rgba(10,20,40,0.8) !important; border: 1px solid var(--card-border) !important; color: var(--offwhite) !important; border-radius: 10px !important; }
.form-control:focus, .form-select:focus { border-color: rgba(0,212,170,0.5) !important; box-shadow: 0 0 0 3px rgba(0,212,170,0.08) !important; }
.form-control::placeholder { color: rgba(232,237,231,0.25) !important; }
.form-label { color: var(--offwhite) !important; }
.text-muted { color: var(--muted) !important; }
.bg-primary { background: rgba(0,212,170,0.15) !important; }
.bg-success { background: rgba(0,212,170,0.15) !important; }
.bg-warning { background: rgba(251,191,36,0.15) !important; }
.bg-info { background: rgba(59,130,246,0.15) !important; }
.bg-light { background: rgba(255,255,255,0.04) !important; }
.bg-white { background: var(--card-bg) !important; }
.bg-secondary { background: rgba(255,255,255,0.06) !important; }
.text-primary { color: var(--teal) !important; }
.text-success { color: var(--teal) !important; }
.text-dark { color: var(--offwhite) !important; }
.text-white { color: var(--offwhite) !important; }
.text-danger { color: #f87171 !important; }
.text-warning { color: #fbbf24 !important; }
.btn-primary { background: var(--teal) !important; border-color: var(--teal) !important; color: var(--navy) !important; font-weight: 600; }
.btn-success { background: rgba(0,212,170,0.15) !important; border-color: rgba(0,212,170,0.3) !important; color: var(--teal) !important; }
.btn-danger { background: rgba(248,113,113,0.15) !important; border-color: rgba(248,113,113,0.3) !important; color: #f87171 !important; }
.btn-warning { background: rgba(251,191,36,0.15) !important; border-color: rgba(251,191,36,0.3) !important; color: #fbbf24 !important; }
.btn-info { background: rgba(59,130,246,0.15) !important; border-color: rgba(59,130,246,0.3) !important; color: #60a5fa !important; }
.btn-secondary { background: rgba(255,255,255,0.06) !important; border: 1px solid rgba(255,255,255,0.1) !important; color: var(--muted) !important; }
.btn-outline-primary { border-color: rgba(0,212,170,0.3) !important; color: var(--teal) !important; }
.alert-success { background: rgba(0,212,170,0.08) !important; border: 1px solid rgba(0,212,170,0.2) !important; color: var(--teal) !important; }
.alert-danger { background: rgba(248,113,113,0.08) !important; border: 1px solid rgba(248,113,113,0.2) !important; color: #f87171 !important; }
.alert-warning { background: rgba(251,191,36,0.08) !important; border: 1px solid rgba(251,191,36,0.2) !important; color: #fbbf24 !important; }
.alert-info { background: rgba(59,130,246,0.08) !important; border: 1px solid rgba(59,130,246,0.2) !important; color: #60a5fa !important; }
.border { border-color: var(--card-border) !important; }
.border-success { border-color: rgba(0,212,170,0.2) !important; }
.border-warning { border-color: rgba(251,191,36,0.2) !important; }
.fw-bold, .fw-semibold { color: var(--offwhite) !important; }
.table { color: var(--offwhite) !important; }
.table-hover tbody tr:hover { background-color: rgba(0,212,170,0.05) !important; }
.table td, .table th { border-color: var(--card-border) !important; color: var(--offwhite) !important; }
.pagination .page-link { background: rgba(10,20,40,0.8) !important; border-color: var(--card-border) !important; color: var(--offwhite) !important; }
.pagination .page-item.active .page-link { background: var(--teal) !important; border-color: var(--teal) !important; color: var(--navy) !important; }
.modal-content { background: var(--card-bg) !important; border: 1px solid var(--card-border) !important; }
.modal-header { background: rgba(0,212,170,0.05) !important; border-bottom: 1px solid var(--card-border) !important; color: var(--offwhite) !important; }
.modal-footer { background: rgba(0,212,170,0.03) !important; border-top: 1px solid var(--card-border) !important; }
.nav-pills .nav-link { color: var(--muted) !important; }
.nav-pills .nav-link.active { background: var(--teal) !important; color: var(--navy) !important; }
.badge { color: var(--navy) !important; font-weight: 600; }
.text-truncate { color: var(--offwhite) !important; }
.border-0 { border-color: transparent !important; }
.shadow-sm { box-shadow: none !important; }
.h4, h4 { color: var(--offwhite) !important; }
.display-4 { color: var(--offwhite) !important; }
</style>
@endpush

@push('styles')
<style>
/* Professional Dashboard Header Styling */
.dashboard-header {
    background: linear-gradient(135deg, rgba(0,212,170,0.1) 0%, rgba(0,212,170,0.04) 100%) !important;
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    border: 1px solid rgba(0, 212, 170, 0.15);
    position: relative;
    overflow: hidden;
}

.dashboard-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, transparent, var(--teal), transparent);
}

.dashboard-header h2 {
    color: var(--offwhite) !important;
    font-weight: 700;
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.dashboard-header p {
    color: var(--muted) !important;
    font-size: 1.1rem;
    font-weight: 500;
    margin-bottom: 0;
}

/* Stats cards - dark theme */
.stats-card {
    background: var(--card-bg) !important;
    border: 1px solid var(--card-border) !important;
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: none;
    height: 100%;
    text-align: center;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, transparent, var(--teal), transparent);
}

.stats-card:hover {
    transform: translateY(-2px);
    border-color: rgba(0,212,170,0.25) !important;
    box-shadow: 0 8px 25px rgba(0,212,170,0.1) !important;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    background: linear-gradient(135deg, var(--offwhite) 0%, var(--teal) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    display: block;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.9rem;
    color: var(--muted) !important;
    font-weight: 500;
}

/* Category tabs */
.category-tabs {
    background: var(--card-bg) !important;
    border: 1px solid var(--card-border) !important;
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.nav-tabs .nav-link {
    border: 1px solid var(--card-border) !important;
    border-radius: 10px;
    margin-right: 0.5rem;
    color: var(--muted) !important;
    font-weight: 600;
    padding: 0.75rem 1.5rem;
    transition: all 0.3s ease;
    background: rgba(255,255,255,0.04) !important;
}

.nav-tabs .nav-link:hover {
    background: rgba(0, 212, 170, 0.15) !important;
    color: var(--teal) !important;
    border-color: rgba(0,212,170,0.3) !important;
}

.nav-tabs .nav-link.active {
    background: var(--teal) !important;
    color: var(--navy) !important;
    border-color: var(--teal) !important;
}

/* Custom table */
.table-custom {
    border-radius: 12px;
    overflow: hidden;
    background: var(--card-bg) !important;
    border: 1px solid var(--card-border) !important;
}

.table-custom thead th {
    background: rgba(0,212,170,0.08) !important;
    color: var(--muted) !important;
    font-weight: 600;
    border-bottom: 2px solid rgba(0,212,170,0.15) !important;
    border: none;
    padding: 1rem 0.75rem;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table-custom tbody td {
    padding: 1rem 0.75rem;
    border-color: var(--card-border) !important;
    vertical-align: middle;
    font-size: 0.9rem;
    color: var(--offwhite) !important;
}

.table-custom tbody tr:hover {
    background-color: rgba(0, 212, 170, 0.03) !important;
}

.patient-row {
    cursor: pointer;
    transition: background-color 0.3s ease;
    color: var(--offwhite) !important;
}

.patient-row:hover {
    background-color: rgba(0, 212, 170, 0.05) !important;
}

/* Badge overrides for dark theme */
.badge-diagnosed {
    background: rgba(16,185,129,0.15) !important;
    color: #10b981 !important;
}

.badge-pending {
    background: rgba(251,191,36,0.15) !important;
    color: #fbbf24 !important;
}

.badge-scheduled {
    background: rgba(59,130,246,0.15) !important;
    color: #60a5fa !important;
}

/* Custom buttons */
.btn-custom-primary {
    background: linear-gradient(135deg, var(--teal) 0%, rgba(0,212,170,0.7) 100%) !important;
    border: none;
    color: var(--navy) !important;
    font-weight: 600;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    box-shadow: 0 4px 15px rgba(0,212,170,0.2);
    transition: all 0.3s ease;
    text-decoration: none;
    font-size: 0.85rem;
}

.btn-custom-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,212,170,0.3);
    color: var(--navy) !important;
    text-decoration: none;
}

.btn-custom-secondary {
    background: rgba(255,255,255,0.04) !important;
    border: 1px solid var(--card-border) !important;
    color: var(--muted) !important;
    font-weight: 600;
    padding: 0.4rem 0.8rem;
    border-radius: 25px;
    transition: all 0.3s ease;
    text-decoration: none;
    font-size: 0.8rem;
}

.btn-custom-secondary:hover {
    border-color: var(--teal) !important;
    color: var(--teal) !important;
    background: rgba(0, 212, 170, 0.05) !important;
    transform: translateY(-1px);
    text-decoration: none;
}

/* Expand visit buttons */
.btn-expand-visit {
    border-radius: 20px;
    box-shadow: none;
    transition: all 0.3s ease;
    background: rgba(255,255,255,0.04) !important;
    color: var(--teal) !important;
    border: 1px solid var(--card-border) !important;
}

.btn-expand-visit:hover {
    background: rgba(0,212,170,0.1) !important;
    border-color: var(--teal) !important;
    color: var(--teal) !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .dashboard-header {
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .dashboard-header h2 {
        font-size: 2rem;
    }

    .dashboard-header p {
        font-size: 1rem;
    }
}
</style>
@endpush

@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/custom-openai.css') }}">

.btn-expand-visit:hover {
    background: rgba(0,212,170,0.15) !important;
    color: var(--teal) !important;
    transform: translateY(-1px);
    border-color: var(--teal) !important;
}
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--muted) !important;
    background: var(--card-bg) !important;
    border-radius: 20px;
    border: 1px solid var(--card-border) !important;
    margin: 2rem 0;
}
.empty-state i {
    font-size: 5rem;
    color: rgba(0,212,170,0.2) !important;
    margin-bottom: 1.5rem;
}
.empty-state h5 {
    color: var(--offwhite) !important;
    font-weight: 700;
    margin-bottom: 1rem;
}
.visits-row {
    background-color: rgba(0,212,170,0.03) !important;
}
.visits-container {
    padding: 1.5rem;
    border-top: 1px solid var(--card-border) !important;
    background: rgba(0,212,170,0.02) !important;
    border-radius: 12px;
    margin-top: 1rem;
}
.visits-section {
    padding: 1.5rem;
    background: var(--card-bg) !important;
    border-radius: 12px;
    border: 1px solid var(--card-border) !important;
}
.visits-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--card-border) !important;
}
.visits-header h6 {
    color: var(--offwhite) !important;
    font-weight: 600;
    margin: 0;
}
.visit-item {
    border: 1px solid var(--card-border) !important;
    border-radius: 8px;
    margin-bottom: 0.75rem;
    background: var(--card-bg) !important;
    overflow: hidden;
    transition: box-shadow 0.3s ease;
}
.visit-item:hover {
    box-shadow: 0 4px 16px rgba(0,212,170,0.1) !important;
}
.visit-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: rgba(0,212,170,0.04) !important;
    border-bottom: 1px solid var(--card-border) !important;
}
.visit-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}
.visit-number {
    font-weight: 600;
    color: var(--offwhite) !important;
    font-size: 0.9rem;
}
.visit-date {
    font-size: 0.8rem;
    color: var(--muted) !important;
}
.visit-details {
    padding: 1rem;
    background: var(--card-bg) !important;
}
.expand-icon {
    transition: transform 0.3s ease;
    font-size: 0.8rem;
}
.expand-icon.rotated {
    transform: rotate(180deg);
}
.table-pagination {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 1rem;
}
.table-pagination button {
    padding: 0.25rem 0.5rem;
    border: 1px solid var(--card-border) !important;
    background: var(--card-bg) !important;
    color: var(--offwhite) !important;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
}
.table-pagination button:hover:not(:disabled) {
    background: var(--teal) !important;
    color: var(--navy) !important;
    border-color: var(--teal) !important;
}
.table-pagination button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
.showing-entries {
    font-size: 0.9rem;
    color: var(--muted) !important;
    margin-top: 1rem;
}
</style>
@endpush

<div class="dashboard-container">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                @php
                    $hasRecords = $records->count() > 0;
                @endphp

                <!-- Page Header -->
                <div class="page-header">
                    <!-- Breadcrumb Navigation -->
                    <nav aria-label="breadcrumb" class="mb-3">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Medical Records</li>
                        </ol>
                    </nav>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1><i class="fas fa-user-injured me-2"></i>Cases Overview</h1>
                            <p class="text-muted mb-0">All patient cases including diagnoses, legacy records, and pending cases</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('ai.ambient-listening.index') }}" class="btn btn-success">
                                <i class="fas fa-microphone me-2"></i>New Consultation
                            </a>
                            <button class="btn btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                                <i class="fas fa-filter"></i> Filters
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Subscription Status Notifications -->
                @include('partials.subscription-notifications')

                @if($hasRecords)
                <!-- Patient Categories Overview -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="stats-card">
                            <div class="stat-number">
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
                            </div>
                            <div class="stat-label">Total Patients</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stats-card">
                            <div class="stat-number">
                                @php
                                    // Count diagnosed patients (those with AI responses)
                                    $diagnosedCount = 0;
                                    foreach ($records as $record) {
                                        if (!empty($record->ai_response) && $record->ai_response !== 'No diagnosis available') {
                                            $diagnosedCount++;
                                        }
                                    }
                                    echo $diagnosedCount;
                                @endphp
                            </div>
                            <div class="stat-label">Diagnosed</div>
                            <div class="badge badge-diagnosed mt-2">Active Cases</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stats-card">
                            <div class="stat-number">
                                @php
                                    // Count pending diagnosis (records without AI responses or with default text)
                                    $pendingCount = 0;
                                    foreach ($records as $record) {
                                        if (empty($record->ai_response) || $record->ai_response === 'No diagnosis available') {
                                            $pendingCount++;
                                        }
                                    }
                                    echo $pendingCount;
                                @endphp
                            </div>
                            <div class="stat-label">Pending Diagnosis</div>
                            <div class="badge badge-pending mt-2">Awaiting Review</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stats-card">
                            <div class="stat-number">{{ $records->where('created_at', '>=', now()->subDays(7))->count() }}</div>
                            <div class="stat-label">Recent Activity</div>
                            <div class="badge badge-scheduled mt-2">This Week</div>
                        </div>
                    </div>
                </div>

                <!-- Category Tabs -->
                <div class="category-tabs">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Patient Categories</h5>
                    </div>
                    <ul class="nav nav-tabs" id="patientTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-patients" type="button" role="tab" aria-controls="all-patients" aria-selected="true">
                                <i class="fas fa-users me-1"></i>All Patients
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="diagnosed-tab" data-bs-toggle="tab" data-bs-target="#diagnosed-patients" type="button" role="tab" aria-controls="diagnosed-patients" aria-selected="false">
                                <i class="fas fa-check-circle me-1"></i>Diagnosed
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending-patients" type="button" role="tab" aria-controls="pending-patients" aria-selected="false">
                                <i class="fas fa-clock me-1"></i>Pending Diagnosis
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="scheduled-tab" data-bs-toggle="tab" data-bs-target="#scheduled-patients" type="button" role="tab" aria-controls="scheduled-patients" aria-selected="false">
                                <i class="fas fa-calendar-alt me-1"></i>Scheduled
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="insurance-tab" data-bs-toggle="tab" data-bs-target="#insurance-eligibility" type="button" role="tab" aria-controls="insurance-eligibility" aria-selected="false">
                                <i class="fas fa-shield-alt me-1"></i>Insurance & Eligibility
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Patient Tables -->
                <div class="tab-content" id="patientTabContent">
                    <!-- All Patients Tab -->
                    <div class="tab-pane fade show active" id="all-patients" role="tabpanel" aria-labelledby="all-tab">
                        @include('cases.partials.patient-table', ['patients' => $patientGroups, 'category' => 'all'])
                    </div>

                    <!-- Diagnosed Patients Tab -->
                    <div class="tab-pane fade" id="diagnosed-patients" role="tabpanel" aria-labelledby="diagnosed-tab">
                        @include('cases.partials.patient-table', ['patients' => $patientGroups, 'category' => 'diagnosed'])
                    </div>

                    <!-- Pending Diagnosis Tab -->
                    <div class="tab-pane fade" id="pending-patients" role="tabpanel" aria-labelledby="pending-tab">
                        @include('cases.partials.patient-table', ['patients' => $patientGroups, 'category' => 'pending'])
                    </div>

                    <!-- Scheduled Patients Tab -->
                    <div class="tab-pane fade" id="scheduled-patients" role="tabpanel" aria-labelledby="scheduled-tab">
                        @include('cases.partials.patient-table', ['patients' => $patientGroups, 'category' => 'scheduled'])
                    </div>

                    <!-- Insurance & Eligibility Tab -->
                    <div class="tab-pane fade" id="insurance-eligibility" role="tabpanel" aria-labelledby="insurance-tab">
                        <div class="insurance-eligibility-content">
                            <!-- Eligibility Status Dashboard -->
                            <x-eligibility-status-dashboard :patientId="null" />

                            <!-- Insurance Management Section -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">
                                                <i class="fas fa-id-card me-2"></i>Insurance Information
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div id="insuranceListContainer">
                                                <!-- Insurance list will be loaded here -->
                                                <div class="text-center py-4">
                                                    <div class="spinner-border text-primary" role="status">
                                                        <span class="visually-hidden">Loading insurance information...</span>
                                                    </div>
                                                    <p class="mt-2 text-muted">Loading insurance information...</p>
                                                </div>
                                            </div>

                                            <div class="mt-3">
                                                <button type="button" class="btn btn-primary" onclick="showAddInsuranceModal()">
                                                    <i class="fas fa-plus me-2"></i>Add Insurance
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <!-- Empty State -->
                <div class="empty-state">
                    <i class="fas fa-user-injured"></i>
                    <h5>No Patient Records Found</h5>
                    <p>You haven't created any patient records yet. Start by adding a new patient analysis or diagnosis.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
@include('cases.partials.modals')

<!-- Insurance Management Modal -->
<div class="modal fade" id="insuranceModal" tabindex="-1" aria-labelledby="insuranceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="insuranceModalLabel">
                    <i class="fas fa-id-card me-2"></i>Insurance Information
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <x-insurance-management-form />
            </div>
        </div>
    </div>
</div>

<!-- Eligibility Check Progress Modal -->
<div class="modal fade" id="eligibilityProgressModal" tabindex="-1" aria-labelledby="eligibilityProgressModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eligibilityProgressModalLabel">
                    <i class="fas fa-shield-check me-2"></i>Checking Eligibility
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Checking eligibility...</span>
                    </div>
                    <h6>Verifying Insurance Eligibility</h6>
                    <p class="text-muted">This may take a few moments...</p>

                    <div class="progress mt-3">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                             role="progressbar" style="width: 100%"></div>
                    </div>

                    <div class="mt-3">
                        <small class="text-muted" id="progressStatus">Connecting to insurance provider...</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div></div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    // Tab filtering functionality
    $(document).on('click', '.nav-link', function() {
        const category = $(this).attr('id').replace('-tab', '');
        filterPatientsByCategory(category);
    });

    // Patient row expansion
    $(document).on('click', '.btn-expand-visits', function() {
        const patientKey = $(this).data('patient-key');
        const visitsRow = $(`.visits-row[data-patient-key="${patientKey}"]`);
        const button = $(this);
        const icon = button.find('.expand-icon');
        const textSpan = button.find('.btn-text');

        if (visitsRow.is(':visible')) {
            visitsRow.slideUp(300);
            icon.removeClass('fa-chevron-up').addClass('fa-chevron-down').removeClass('rotated');
            textSpan.text('View Details');
        } else {
            visitsRow.slideDown(300);
            icon.removeClass('fa-chevron-down').addClass('fa-chevron-up').addClass('rotated');
            textSpan.text('Hide Details');
        }
    });

    // Visit details expansion
    $(document).on('click', '.btn-expand-visit', function() {
        const visitId = $(this).data('visit-id');
        const visitDetails = $(`.visit-item[data-visit-id="${visitId}"] .visit-details`);
        const expandIcon = $(this).find('.visit-expand-icon');

        if (visitDetails.is(':visible')) {
            visitDetails.slideUp(300);
            expandIcon.removeClass('rotated');
            $(this).attr('aria-expanded', 'false');
        } else {
            visitDetails.slideDown(300);
            expandIcon.addClass('rotated');
            loadVisitDetails(visitId, $(this).data());
            $(this).attr('aria-expanded', 'true');
        }
    });

    // Schedule appointment functionality
    $(document).on('click', '.btn-schedule-appointment', function() {
        const patientData = {
            name: $(this).data('patient-name'),
            age: $(this).data('patient-age'),
            gender: $(this).data('patient-gender'),
            key: $(this).data('patient-key')
        };

        alert(`Schedule appointment for ${patientData.name} (${patientData.age} years old, ${patientData.gender})`);
    });

    // Show summary functionality
    $(document).on('click', '.btn-show-summary', function() {
        const patientData = {
            name: $(this).data('patient-name'),
            age: $(this).data('patient-age'),
            gender: $(this).data('patient-gender'),
            key: $(this).data('patient-key')
        };

        showPatientSummary(patientData);
    });

    // Search functionality
    $(document).on('keyup', '#patient-search', function() {
        const searchTerm = $(this).val().toLowerCase();
        filterPatients(searchTerm);
    });

    // Sorting functionality
    $(document).on('click', '.sort-link', function(e) {
        e.preventDefault();
        const sortBy = $(this).data('sort');
        sortPatients(sortBy);
    });


    // When modal is about to show, handle scrolling without sidebar manipulation to prevent conflicts
    $(document).on('show.bs.modal', '.modal', function() {
        // Store original body scrollbar width to restore later
        const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;

        // Prevent body from scrolling when modal is open
        $('body').css({
            'overflow': 'hidden',
            'padding-right': scrollbarWidth + 'px'
        });

        // Also add class to prevent scroll
        $('body').addClass('modal-open');
    });

    // When modal is completely shown, ensure it has the highest z-index and correct positioning
    $(document).on('shown.bs.modal', '.modal', function() {
        // Make sure modal has very high z-index and is positioned correctly
        $(this).css({
            'z-index': '999999999',
            'position': 'fixed',
            'top': '0',
            'left': '0',
            'right': '0',
            'bottom': '0'
        });

        // Make sure backdrop has high z-index and covers full viewport
        $('.modal-backdrop').css({
            'z-index': '999999998',
            'position': 'fixed',
            'top': '0',
            'left': '0',
            'right': '0',
            'bottom': '0'
        });

        // Ensure modal dialog is centered within viewport
        $(this).find('.modal-dialog').css({
            'position': 'absolute',
            'top': '50%',
            'left': '50%',
            'transform': 'translate(-50%, -50%)',
            'z-index': '1000000000'
        });

        // Note: Not moving elements in DOM as this might interfere with other components
    });

    // When modal is hidden, restore body scrolling only
    $(document).on('hidden.bs.modal', '.modal', function() {
        // Restore body scrolling
        $('body').css({
            'overflow': '',
            'padding-right': ''
        });

        // Remove modal-open class
        $('body').removeClass('modal-open');
    });
});

function filterPatientsByCategory(category) {
    const rows = $('.patient-row');
    let visibleCount = 0;

    rows.each(function() {
        const rowCategory = $(this).data('category');
        if (category === 'all' || rowCategory === category) {
            $(this).show();
            visibleCount++;
        } else {
            $(this).hide();
        }
    });

    updateShowingCount(visibleCount);
}

function filterPatients(searchTerm) {
    const rows = $('.patient-row:visible');
    let visibleCount = 0;

    rows.each(function() {
        const patientName = $(this).find('td:first').text().toLowerCase();
        if (patientName.includes(searchTerm)) {
            $(this).show();
            visibleCount++;
        } else {
            $(this).hide();
        }
    });

    updateShowingCount(visibleCount);
}

function sortPatients(sortBy) {
    const table = $('.table-custom tbody');
    const rows = table.find('.patient-row').get();

    rows.sort((a, b) => {
        let aVal, bVal;

        switch(sortBy) {
            case 'name':
                aVal = $(a).find('td:first').text().toLowerCase();
                bVal = $(b).find('td:first').text().toLowerCase();
                break;
            case 'age':
                aVal = parseInt($(a).find('td:nth-child(2)').text()) || 0;
                bVal = parseInt($(b).find('td:nth-child(2)').text()) || 0;
                break;
            case 'gender':
                aVal = $(a).find('td:nth-child(3)').text().toLowerCase();
                bVal = $(b).find('td:nth-child(3)').text().toLowerCase();
                break;
            case 'visits':
                aVal = parseInt($(a).data('visits')) || 0;
                bVal = parseInt($(b).data('visits')) || 0;
                break;
            case 'last-visit':
                aVal = parseInt($(a).data('last-visit')) || 0;
                bVal = parseInt($(b).data('last-visit')) || 0;
                break;
            default:
                return 0;
        }

        if (aVal < bVal) return -1;
        if (aVal > bVal) return 1;
        return 0;
    });

    // Re-append sorted rows
    $.each(rows, function(index, row) {
        table.append(row);
    });
}

function updateShowingCount(count) {
    $('.showing-entries').text(`Showing ${count} patients`);
}

function loadVisitDetails(visitId, buttonData) {
    const visitDetailsContent = document.querySelector(`.visit-item[data-visit-id="${visitId}"] .visit-details-content`);

    // Check if already loaded
    if (visitDetailsContent.querySelector('.diagnosis-content')) {
        return;
    }

    // Show loading state
    visitDetailsContent.innerHTML = `
        <div class="text-center py-3">
            <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 mb-0">Loading visit details...</p>
        </div>
    `;

    // Make AJAX call to get visit details
    $.ajax({
        url: `/api/doctor/patient-management/visit-history/${visitId}`,
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        success: function(response) {
            if (response.success) {
                const diagnosisText = response.visit.diagnosis || 'No diagnosis available';
                const formattedContent = formatAIResponse(diagnosisText);

                visitDetailsContent.innerHTML = `
                    <div class="diagnosis-content">
                        <div class="visit-diagnosis-header mb-3">
                            <h6 class="mb-0"><i class="fas fa-stethoscope me-2"></i>Diagnosis Details</h6>
                        </div>
                        <div class="response-text">
                            ${formattedContent}
                        </div>
                    </div>
                `;
            } else {
                visitDetailsContent.innerHTML = '<div class="alert alert-warning">Failed to load visit details.</div>';
            }
        },
        error: function(xhr, status, error) {
            // console.error('Error loading visit details:', error);
            visitDetailsContent.innerHTML = '<div class="alert alert-danger">Error loading visit details. Please try again.</div>';
        }
    });
}

function showPatientSummary(patientData) {
    // Show loading modal
    bootstrap.Modal.getOrCreateInstance(document.getElementById('summaryModal')).show();

    // Update modal header
    document.getElementById('summaryModalLabel').innerHTML = `<i class="fas fa-user-doctor me-2"></i>${patientData.name}'s Medical Summary`;

    // Update patient info
    document.getElementById('summaryPatientName').textContent = patientData.name;
    document.getElementById('summaryPatientAge').textContent = patientData.age;
    document.getElementById('summaryPatientGender').textContent = patientData.gender.charAt(0).toUpperCase() + patientData.gender.slice(1);

    // Load summary data
    loadPatientSummary(patientData);
}

function loadPatientSummary(patientData) {
    // Reset containers
    document.getElementById('visitSummaryContainer').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading patient history...</p>
        </div>
    `;

    document.getElementById('aiSummaryContainer').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Generating summary...</p>
        </div>
    `;

    // Find patient records
    const allRecords = @json($records);
    let patientRecords = [];

    if (patientData.key) {
        patientRecords = allRecords.filter(record => record.patient_key === patientData.key);
    }
    if (patientRecords.length === 0) {
        patientRecords = allRecords.filter(record =>
            record.name === patientData.name &&
            record.age == patientData.age &&
            record.gender === patientData.gender
        );
    }

    // Sort records by date
    patientRecords.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));

    // Generate visit summary
    if (patientRecords.length > 0) {
        // Create table using jQuery DOM methods to prevent XSS
        const tableContainer = $('<div class="table-responsive"></div>');
        const table = $('<table class="table table-hover table-sm"></table>');
        const thead = $('<thead></thead>');
        const headerRow = $('<tr></tr>');

        headerRow.append('<th>Record #</th>');
        headerRow.append('<th>Date</th>');
        headerRow.append('<th>Diagnosis Summary</th>');
        thead.append(headerRow);
        table.append(thead);

        const tbody = $('<tbody></tbody>');

        patientRecords.forEach((record, index) => {
            const visitDate = new Date(record.created_at);
            const diagnosisText = record.ai_response || record.diagnosis_text || 'No diagnosis available';
            const diagnosisSummary = diagnosisText.length > 80 ?
                diagnosisText.substring(0, 80) + '...' :
                diagnosisText;

            // Determine record type label
            const recordType = record.source_model || 'Appointment';
            const typeLabel = recordType === 'Appointment' ? 'Appointment' :
                             recordType === 'Diagnosis' ? 'Diagnosis' :
                             recordType === 'PatientAnalysis' ? 'Analysis' : 'Record';

            const tr = $('<tr></tr>');

            // Create cells with proper text escaping
            const visitTd = $('<td></td>').append(
                $('<span class="badge bg-light text-dark"></span>').text(typeLabel + ' #' + (index + 1))
            );
            const dateTd = $('<td></td>').text(visitDate.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            }));
            const summaryTd = $('<td class="small"></td>').text(diagnosisSummary);

            tr.append(visitTd, dateTd, summaryTd);
            tbody.append(tr);
        });

        table.append(tbody);
        tableContainer.append(table);

        document.getElementById('visitSummaryContainer').innerHTML = tableContainer.html();

        // Generate AI-powered patient summary
        generatePatientSummary(patientRecords);
    } else {
        document.getElementById('visitSummaryContainer').innerHTML = '<div class="alert alert-info">No visit history found for this patient.</div>';
        document.getElementById('aiSummaryContainer').innerHTML = '<div class="alert alert-info">Cannot generate summary without patient history.</div>';
    }
}

function generatePatientSummary(patientRecords) {
    // Prepare data for AI summary
    const summaryData = {
        patient_id: patientRecords.length > 0 ? patientRecords[0].id : 0,
        patient_name: document.getElementById('summaryPatientName').textContent,
        patient_age: document.getElementById('summaryPatientAge').textContent,
        patient_gender: document.getElementById('summaryPatientGender').textContent.toLowerCase(),
        visit_count: patientRecords.length,
        visits: patientRecords.map(record => ({
            visit_number: record.visit_number || 'unknown',
            date: new Date(record.created_at).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            }),
            diagnosis: record.ai_response || record.diagnosis_text || 'No diagnosis available'
        }))
    };

    // Show loading state
    document.getElementById('aiSummaryContainer').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary mb-3" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="text-muted">Generating AI-powered patient summary...</p>
            <div class="progress mt-3" style="height: 6px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                      role="progressbar" style="width: 100%"></div>
            </div>
        </div>
    `;

    // Call AI summary generation API
    $.ajax({
        url: '/ai/patient-summary',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        data: JSON.stringify(summaryData),
        success: function(response) {
            if (response.success) {
                const formattedSummary = formatAIResponse(response.summary);
                document.getElementById('aiSummaryContainer').innerHTML = `<div class="response-text">${formattedSummary}</div>`;
            } else {
                document.getElementById('aiSummaryContainer').innerHTML = `
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        ${response.message || 'Failed to generate summary'}
                    </div>
                `;
            }
        },
        error: function(xhr, status, error) {
            document.getElementById('aiSummaryContainer').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Failed to generate AI summary. Please try again.
                </div>
            `;
        }
    });
}

// AI Response formatting function (simplified version)
function formatAIResponse(text) {
    if (!text) return '';

    // Basic formatting for common patterns
    let formatted = text
        .replace(/^📋\s*PATIENT CASE SUMMARY:?$/gm, '<div class="medcura-section patient-summary"><h4 class="section-header">📋 PATIENT CASE SUMMARY</h4><div class="section-content">')
        .replace(/^🔬\s*KEY MEDICAL ISSUES IDENTIFIED:?$/gm, '</div></div><div class="medcura-section key-medical-issues"><h4 class="section-header">🔬 KEY MEDICAL ISSUES IDENTIFIED</h4><div class="section-content">')
        .replace(/^📈\s*IMPORTANT TRENDS IN SYMPTOMS OR TEST RESULTS:?$/gm, '</div></div><div class="medcura-section symptom-trends"><h4 class="section-header">📈 IMPORTANT TRENDS IN SYMPTOMS OR TEST RESULTS</h4><div class="section-content">')
        .replace(/^💊\s*TREATMENT EFFECTIVENESS BASED ON VISIT PROGRESSION:?$/gm, '</div></div><div class="medcura-section treatment-effectiveness"><h4 class="section-header">💊 TREATMENT EFFECTIVENESS BASED ON VISIT PROGRESSION</h4><div class="section-content">')
        .replace(/^🩺\s*RECOMMENDATIONS FOR FUTURE CARE:?$/gm, '</div></div><div class="medcura-section future-care"><h4 class="section-header">🩺 RECOMMENDATIONS FOR FUTURE CARE</h4><div class="section-content">')
        .replace(/^- /gm, '<li class="bullet-item">')
        .replace(/\n\n/g, '</p><p>')
        .replace(/\n/g, '<br>');

    // Close sections
    formatted += '</div></div>';

    return formatted;
}

// Insurance and Eligibility Management Functions
function showAddInsuranceModal() {
    document.getElementById('insuranceModalLabel').textContent = 'Add Insurance Information';
    // Reset form
    document.getElementById('insuranceForm').reset();
    // Clear any existing insurance ID
    const insuranceIdInput = document.querySelector('input[name="insurance_id"]');
    if (insuranceIdInput) {
        insuranceIdInput.value = '';
    }

    const modal = new bootstrap.Modal(document.getElementById('insuranceModal'));
    modal.show();
}

function editInsurance(insuranceId) {
    // Load insurance data and show modal
    fetch(`/api/patient-insurance/${insuranceId}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Populate form with insurance data
            populateInsuranceForm(data.insurance);
            document.getElementById('insuranceModalLabel').textContent = 'Edit Insurance Information';
            const modal = new bootstrap.Modal(document.getElementById('insuranceModal'));
            modal.show();
        } else {
            alert('Failed to load insurance information');
        }
    })
    .catch(error => {
        // console.error('Error loading insurance:', error);
        alert('Error loading insurance information');
    });
}

function populateInsuranceForm(insurance) {
    document.getElementById('insurance_provider_id').value = insurance.insurance_provider_id;
    document.getElementById('policy_number').value = insurance.policy_number;
    document.getElementById('group_number').value = insurance.group_number || '';
    document.getElementById('member_id').value = insurance.member_id;
    document.getElementById('effective_date').value = insurance.effective_date ? new Date(insurance.effective_date).toISOString().split('T')[0] : '';
    document.getElementById('expiration_date').value = insurance.expiration_date ? new Date(insurance.expiration_date).toISOString().split('T')[0] : '';
    document.getElementById('notes').value = insurance.notes || '';

    const insuranceIdInput = document.querySelector('input[name="insurance_id"]');
    if (insuranceIdInput) {
        insuranceIdInput.value = insurance.id;
    }
}

function deleteInsurance(insuranceId) {
    if (confirm('Are you sure you want to delete this insurance information?')) {
        fetch(`/api/patient-insurance/${insuranceId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadInsuranceList();
                alert('Insurance information deleted successfully');
            } else {
                alert('Failed to delete insurance information');
            }
        })
        .catch(error => {
            // console.error('Error deleting insurance:', error);
            alert('Error deleting insurance information');
        });
    }
}

function loadInsuranceList() {
    const container = document.getElementById('insuranceListContainer');

    fetch('/api/patient-insurance', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderInsuranceList(data.insurances);
        } else {
            container.innerHTML = '<div class="alert alert-warning">Failed to load insurance information</div>';
        }
    })
    .catch(error => {
        // console.error('Error loading insurance list:', error);
        container.innerHTML = '<div class="alert alert-danger">Error loading insurance information</div>';
    });
}

function renderInsuranceList(insurances) {
    const container = document.getElementById('insuranceListContainer');

    if (!insurances || insurances.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-id-card fa-3x text-muted mb-3"></i>
                <h6 class="text-muted">No Insurance Information</h6>
                <p class="text-muted small">Add insurance information to enable eligibility checking</p>
            </div>
        `;
        return;
    }

    // Clear the container
    container.innerHTML = '';
    const insuranceList = document.createElement('div');
    insuranceList.className = 'insurance-list';

    insurances.forEach(insurance => {
        const provider = insurance.insurance_provider ? insurance.insurance_provider.name : 'Unknown Provider';
        const expiryDate = new Date(insurance.expiration_date);
        const isExpired = expiryDate < new Date();

        // Create insurance item using DOM methods to prevent XSS
        const insuranceItem = document.createElement('div');
        insuranceItem.className = 'insurance-item card mb-3';

        const cardBody = document.createElement('div');
        cardBody.className = 'card-body';

        // Create header row
        const headerRow = document.createElement('div');
        headerRow.className = 'd-flex justify-content-between align-items-start';

        // Create insurance info section
        const insuranceInfo = document.createElement('div');
        insuranceInfo.className = 'insurance-info';

        const cardTitle = document.createElement('h6');
        cardTitle.className = 'card-title mb-2';

        // Create the title with icon and provider name
        const icon = document.createElement('i');
        icon.className = 'fas fa-building me-2';
        cardTitle.appendChild(icon);

        // Add provider text
        cardTitle.appendChild(document.createTextNode(provider));

        // Add status badge
        if (insurance.insurance_provider) {
            const statusSpan = document.createElement('span');
            statusSpan.className = getInsuranceStatusClass(insurance);
            statusSpan.textContent = getInsuranceStatusText(insurance);
            statusSpan.style.marginLeft = '10px';
            cardTitle.appendChild(statusSpan);
        }

        insuranceInfo.appendChild(cardTitle);

        // Create row for details
        const rowDiv = document.createElement('div');
        rowDiv.className = 'row';

        // Left column
        const leftCol = document.createElement('div');
        leftCol.className = 'col-md-6';

        const policyText = document.createElement('small');
        policyText.className = 'text-muted';
        policyText.textContent = `Policy #: ${insurance.policy_number}`;
        leftCol.appendChild(policyText);
        leftCol.appendChild(document.createElement('br'));

        const memberIdText = document.createElement('small');
        memberIdText.className = 'text-muted';
        memberIdText.textContent = `Member ID: ${insurance.member_id}`;
        leftCol.appendChild(memberIdText);

        // Right column
        const rightCol = document.createElement('div');
        rightCol.className = 'col-md-6';

        const effectiveText = document.createElement('small');
        effectiveText.className = 'text-muted';
        effectiveText.textContent = `Effective: ${new Date(insurance.effective_date).toLocaleDateString()}`;
        rightCol.appendChild(effectiveText);
        rightCol.appendChild(document.createElement('br'));

        const expiryText = document.createElement('small');
        expiryText.className = isExpired ? 'text-muted text-danger' : 'text-muted';
        expiryText.textContent = `Expires: ${expiryDate.toLocaleDateString()}${isExpired ? ' (Expired)' : ''}`;
        rightCol.appendChild(expiryText);

        rowDiv.appendChild(leftCol);
        rowDiv.appendChild(rightCol);
        insuranceInfo.appendChild(rowDiv);

        // Add notes if available
        if (insurance.notes) {
            const notesDiv = document.createElement('div');
            notesDiv.className = 'mt-2';
            const notesText = document.createElement('small');
            notesText.className = 'text-muted';
            notesText.textContent = insurance.notes;
            notesDiv.appendChild(notesText);
            insuranceInfo.appendChild(notesDiv);
        }

        headerRow.appendChild(insuranceInfo);

        // Create action buttons
        const actionDiv = document.createElement('div');
        actionDiv.className = 'insurance-actions';

        // Edit button
        const editBtn = document.createElement('button');
        editBtn.type = 'button';
        editBtn.className = 'btn btn-sm btn-outline-primary me-2';
        editBtn.innerHTML = '<i class="fas fa-edit"></i>';
        editBtn.onclick = function() { editInsurance(insurance.id); };

        // Delete button
        const deleteBtn = document.createElement('button');
        deleteBtn.type = 'button';
        deleteBtn.className = 'btn btn-sm btn-outline-danger';
        deleteBtn.innerHTML = '<i class="fas fa-trash"></i>';
        deleteBtn.onclick = function() { deleteInsurance(insurance.id); };

        actionDiv.appendChild(editBtn);
        actionDiv.appendChild(deleteBtn);

        headerRow.appendChild(actionDiv);

        cardBody.appendChild(headerRow);
        insuranceItem.appendChild(cardBody);
        insuranceList.appendChild(insuranceItem);
    });

    container.appendChild(insuranceList);
}

function getInsuranceStatusClass(insurance) {
    const expiryDate = new Date(insurance.expiration_date);
    const now = new Date();
    const daysUntilExpiry = Math.ceil((expiryDate - now) / (1000 * 60 * 60 * 24));

    if (expiryDate < now) {
        return 'badge bg-danger';
    } else if (daysUntilExpiry <= 30) {
        return 'badge bg-warning';
    } else {
        return 'badge bg-success';
    }
}

function getInsuranceStatusText(insurance) {
    const expiryDate = new Date(insurance.expiration_date);
    const now = new Date();
    const daysUntilExpiry = Math.ceil((expiryDate - now) / (1000 * 60 * 60 * 24));

    if (expiryDate < now) {
        return 'Expired';
    } else if (daysUntilExpiry <= 30) {
        return 'Expiring Soon';
    } else {
        return 'Active';
    }
}

// Initialize insurance tab when it's shown
document.addEventListener('DOMContentLoaded', function() {
    const insuranceTab = document.getElementById('insurance-tab');
    if (insuranceTab) {
        insuranceTab.addEventListener('shown.bs.tab', function() {
            loadInsuranceList();
        });
    }

    // Handle insurance form submission
    const insuranceForm = document.getElementById('insuranceForm');
    if (insuranceForm) {
        insuranceForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitInsuranceForm();
        });
    }
});

function submitInsuranceForm() {
    const form = document.getElementById('insuranceForm');
    const formData = new FormData(form);
    const insuranceId = formData.get('insurance_id');
    const isEdit = insuranceId && insuranceId !== '';

    const url = isEdit ? `/api/patient-insurance/${insuranceId}` : '/api/patient-insurance';
    const method = isEdit ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('insuranceModal'));
            modal.hide();

            // Reload insurance list
            loadInsuranceList();

            // Show success message
            alert(isEdit ? 'Insurance information updated successfully' : 'Insurance information added successfully');
        } else {
            alert(data.message || 'Failed to save insurance information');
        }
    })
    .catch(error => {
        // console.error('Error saving insurance:', error);
        alert('Error saving insurance information');
    });
}
</script>
@endpush
