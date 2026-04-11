@extends('layouts.doctor')

@section('title', 'Clinical Early Warning System')

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
.badge { color: var(--offwhite) !important; font-weight: 600; }
.text-truncate { color: var(--offwhite) !important; }
.border-0 { border-color: transparent !important; }
.shadow-sm { box-shadow: none !important; }
.h4, h4 { color: var(--offwhite) !important; }
.display-4 { color: var(--offwhite) !important; }
</style>
@endpush

@push('styles')
<style>
/* Add some Tailwind-like utilities if they are missing from the project's CSS */
.font-bold { font-weight: 700; }
.text-gray-900 { color: var(--offwhite) !important; }
.text-gray-600 { color: var(--muted) !important; }
.text-gray-700 { color: var(--offwhite) !important; }
.text-gray-500 { color: var(--muted) !important; }
.bg-white { background-color: var(--card-bg) !important; }
.bg-gray-50 { background-color: rgba(255,255,255,0.04) !important; }
.rounded-xl { border-radius: 0.75rem; }
.shadow-sm { box-shadow: none !important; }
.border-gray-100 { border-color: var(--card-border) !important; }

/* Override any light text inside the container */
#clinical-dashboard-root,
#alert-management-root,
#clinical-config-root {
    color: var(--offwhite) !important;
}
#clinical-dashboard-root *,
#alert-management-root *,
#clinical-config-root * {
    color: var(--offwhite) !important;
}
</style>
@endpush

@section('content')
<div class="dashboard-container">
<div class="container-fluid px-3 px-md-4">
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-4 font-bold text-gray-900">Clinical Monitoring</h1>
            <p class="lead text-gray-600">Real-time patient risk assessment and early warning system.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Dashboard for a specific patient (if patientId is provided) -->
        @if($patientId)
        <div class="col-12">
            <div id="clinical-dashboard-root" data-patient-id="{{ $patientId }}">
                <!-- React will mount here -->
                <div class="p-8 text-center bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="spinner-border text-primary mb-3" role="status"></div>
                    <p>Loading Patient Dashboard...</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Global Alert Management -->
        <div class="col-12 col-xl-8">
            <div id="alert-management-root">
                <!-- React will mount here -->
                <div class="p-8 text-center bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="spinner-border text-primary mb-3" role="status"></div>
                    <p>Loading Alert Manager...</p>
                </div>
            </div>
        </div>

        <!-- Configuration Panel -->
        <div class="col-12 col-xl-4">
            <div id="clinical-config-root">
                <!-- React will mount here -->
                <div class="p-8 text-center bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="spinner-border text-primary mb-3" role="status"></div>
                    <p>Loading Configuration...</p>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection

@push('styles')
<style>
    /* Add some Tailwind-like utilities if they are missing from the project's CSS */
</style>
@endpush
