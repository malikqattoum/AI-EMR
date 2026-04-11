@extends('layouts.doctor')

@push('styles')
<style>
.card { background: var(--card-bg) !important; border: 1px solid var(--card-border) !important; border-radius: 16px !important; }
.card-header { background: rgba(0,212,170,0.05) !important; border-bottom: 1px solid var(--card-border) !important; color: var(--offwhite) !important; }
.card-body { background: transparent !important; }
.form-control, .form-select { background: rgba(10,20,40,0.8) !important; border: 1px solid var(--card-border) !important; color: var(--offwhite) !important; border-radius: 10px !important; }
.form-control:focus { border-color: rgba(0,212,170,0.5) !important; box-shadow: 0 0 0 3px rgba(0,212,170,0.08) !important; }
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
.btn-primary { background: var(--teal) !important; border-color: var(--teal) !important; color: var(--navy) !important; font-weight: 600; }
.btn-success { background: rgba(0,212,170,0.15) !important; border-color: rgba(0,212,170,0.3) !important; color: var(--teal) !important; }
.btn-danger { background: rgba(248,113,113,0.15) !important; border-color: rgba(248,113,113,0.3) !important; color: #f87171 !important; }
.btn-warning { background: rgba(251,191,36,0.15) !important; border-color: rgba(251,191,36,0.3) !important; color: #fbbf24 !important; }
.btn-info { background: rgba(59,130,246,0.15) !important; border-color: rgba(59,130,246,0.3) !important; color: #60a5fa !important; }
.btn-secondary { background: rgba(255,255,255,0.06) !important; border: 1px solid rgba(255,255,255,0.1) !important; color: var(--muted) !important; }
.btn-outline-primary { border-color: rgba(0,212,170,0.3) !important; color: var(--teal) !important; }
.btn-outline-secondary { border-color: rgba(255,255,255,0.15) !important; color: var(--muted) !important; }
.alert-success { background: rgba(0,212,170,0.08) !important; border: 1px solid rgba(0,212,170,0.2) !important; color: var(--teal) !important; }
.alert-danger { background: rgba(248,113,113,0.08) !important; border: 1px solid rgba(248,113,113,0.2) !important; color: #f87171 !important; }
.alert-warning { background: rgba(251,191,36,0.08) !important; border: 1px solid rgba(251,191,36,0.2) !important; color: #fbbf24 !important; }
.alert-info { background: rgba(59,130,246,0.08) !important; border: 1px solid rgba(59,130,246,0.2) !important; color: #60a5fa !important; }
.border { border-color: var(--card-border) !important; }
.border-success { border-color: rgba(0,212,170,0.2) !important; }
.border-warning { border-color: rgba(251,191,36,0.2) !important; }
.fw-bold, .fw-semibold { color: var(--offwhite) !important; }
.fw-normal { color: var(--muted) !important; }
.table { color: var(--offwhite) !important; }
.table-hover tbody tr:hover { background-color: rgba(0,212,170,0.05) !important; }
.table td { border-color: var(--card-border) !important; color: var(--offwhite) !important; }
.table th { border-color: var(--card-border) !important; color: var(--muted) !important; }
.pagination .page-link { background: rgba(10,20,40,0.8) !important; border-color: var(--card-border) !important; color: var(--offwhite) !important; }
.pagination .page-item.active .page-link { background: var(--teal) !important; border-color: var(--teal) !important; color: var(--navy) !important; }
.modal-content { background: var(--card-bg) !important; border: 1px solid var(--card-border) !important; }
.modal-header { background: rgba(0,212,170,0.05) !important; border-bottom: 1px solid var(--card-border) !important; color: var(--offwhite) !important; }
.modal-footer { background: rgba(0,212,170,0.03) !important; border-top: 1px solid var(--card-border) !important; }
.badge { color: var(--offwhite) !important; font-weight: 600; }
</style>
@endpush

@section('title', 'Ambient Listening Session Details')

@section('content')
<div class="dashboard-container">
    <div class="container-fluid px-3 px-md-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="card-title h3 mb-2">
                                <i class="fas fa-file-medical me-2"></i>
                                Ambient Listening Session Details
                            </h1>
                            <p class="card-text mb-0">
                                Patient: {{ $transcription->patient ? $transcription->patient->name : 'Unknown Patient' }} |
                                Date: {{ $transcription->session_started_at ? $transcription->session_started_at->format('M d, Y - H:i A') : 'Date not available' }}
                            </p>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('ai.ambient-listening.history') }}" class="btn btn-light">
                                <i class="fas fa-arrow-left me-2"></i>
                                Back to History
                            </a>
                            <a href="{{ route('ai.ambient-listening.index') }}" class="btn btn-outline-light">
                                <i class="fas fa-microphone me-2"></i>
                                New Session
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Session Info -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Session Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <strong>Patient:</strong><br>
                            <span class="text-muted">{{ $transcription->patient ? $transcription->patient->name : 'Unknown Patient' }}</span>
                        </div>
                        <div class="col-sm-6">
                            <strong>Doctor:</strong><br>
                            <span class="text-muted">{{ $transcription->doctor ? $transcription->doctor->name : 'Unknown Doctor' }}</span>
                        </div>
                        <div class="col-sm-6">
                            <strong>Session Started:</strong><br>
                            <span class="text-muted">{{ $transcription->session_started_at ? $transcription->session_started_at->format('M d, Y - H:i A') : 'Not available' }}</span>
                        </div>
                        <div class="col-sm-6">
                            <strong>Session Ended:</strong><br>
                            <span class="text-muted">{{ $transcription->session_ended_at ? $transcription->session_ended_at->format('M d, Y - H:i A') : 'Not available' }}</span>
                        </div>
                        <div class="col-sm-6">
                            <strong>Status:</strong><br>
                            @if($transcription->status === 'completed')
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>
                                    Completed
                                </span>
                            @elseif($transcription->status === 'active')
                                <span class="badge bg-warning">
                                    <i class="fas fa-clock me-1"></i>
                                    Active
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-pause me-1"></i>
                                    {{ ucfirst($transcription->status) }}
                                </span>
                            @endif
                        </div>
                        <div class="col-sm-6">
                            <strong>Session ID:</strong><br>
                            <small class="text-muted font-monospace">{{ $transcription->session_id }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>
                        Session Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <strong>Transcription Length:</strong><br>
                            <span class="text-muted">{{ $transcription->raw_transcription ? strlen($transcription->raw_transcription) : 0 }} characters</span>
                        </div>
                        <div class="col-sm-6">
                            <strong>Word Count:</strong><br>
                            <span class="text-muted">{{ $transcription->raw_transcription ? str_word_count($transcription->raw_transcription) : 0 }} words</span>
                        </div>
                        <div class="col-sm-6">
                            <strong>AI Analysis:</strong><br>
                            <span class="text-muted">{{ $transcription->ai_analysis ? 'Generated' : 'Not generated' }}</span>
                        </div>
                        <div class="col-sm-6">
                            <strong>Structured Data:</strong><br>
                            <span class="text-muted">{{ $transcription->structured_chart ? 'Available' : 'Not available' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Audio Recording -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-volume-up me-2"></i>
                        Session Audio
                    </h5>
                </div>
                <div class="card-body">
                    @if($transcription->audio_file)
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <audio controls class="w-100" style="max-width: 100%;">
                                    <source src="{{ asset('storage/' . $transcription->audio_file) }}" type="audio/{{ $transcription->audio_format ?? 'webm' }}">
                                    Your browser does not support the audio element.
                                </audio>
                            </div>
                            <div class="col-md-4">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <small class="text-muted">Format:</small><br>
                                        <span class="badge bg-secondary">{{ strtoupper($transcription->audio_format ?? 'WEBM') }}</span>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Size:</small><br>
                                        <span class="text-muted">{{ $transcription->audio_file_size ? number_format($transcription->audio_file_size / 1024, 1) . ' KB' : 'Unknown' }}</span>
                                    </div>
                                    @if($transcription->audio_duration)
                                        <div class="col-6">
                                            <small class="text-muted">Duration:</small><br>
                                            <span class="text-muted">{{ number_format($transcription->audio_duration, 1) }} sec</span>
                                        </div>
                                    @endif
                                    <div class="col-6">
                                        <small class="text-muted">Download:</small><br>
                                        <a href="{{ route('ai.ambient-listening.download-audio', $transcription) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download me-1"></i>
                                            Download Audio
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-microphone-slash fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No Session Audio Available</h6>
                            <p class="text-muted mb-0">This ambient listening session was recorded using live transcription only. Audio recording functionality may not have been available or enabled during this session.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Raw Transcription -->
    @if($transcription->raw_transcription)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-microphone-alt me-2"></i>
                            Raw Transcription
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto; background-color: rgba(10, 22, 40, 0.6);">
                            <p class="mb-0" style="white-space: pre-wrap;">{{ $transcription->raw_transcription }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Structured Chart Data -->
    @if($transcription->structured_chart)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-clipboard-list me-2"></i>
                            Structured Medical Chart
                        </h5>
                    </div>
                    <div class="card-body">
                        @php
                            $chartData = is_string($transcription->structured_chart)
                                ? json_decode($transcription->structured_chart, true)
                                : $transcription->structured_chart;
                        @endphp

                        @if($chartData)
                            <div class="row g-3">
                                @if(isset($chartData['symptoms']) && $chartData['symptoms'])
                                    <div class="col-md-6">
                                        <div class="card border-primary">
                                            <div class="card-header bg-primary text-white">
                                                <h6 class="mb-0">Symptoms</h6>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text">{{ $chartData['symptoms'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if(isset($chartData['medical_history']) && $chartData['medical_history'])
                                    <div class="col-md-6">
                                        <div class="card border-info">
                                            <div class="card-header bg-info text-white">
                                                <h6 class="mb-0">Medical History</h6>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text">{{ $chartData['medical_history'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if(isset($chartData['physical_findings']) && $chartData['physical_findings'])
                                    <div class="col-md-6">
                                        <div class="card border-warning">
                                            <div class="card-header bg-warning" style="color: var(--navy);">
                                                <h6 class="mb-0">Physical Findings</h6>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text">{{ $chartData['physical_findings'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if(isset($chartData['medications']) && $chartData['medications'])
                                    <div class="col-md-6">
                                        <div class="card border-success">
                                            <div class="card-header bg-success text-white">
                                                <h6 class="mb-0">Medications</h6>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text">{{ $chartData['medications'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if(isset($chartData['vital_signs']) && $chartData['vital_signs'])
                                    <div class="col-md-6">
                                        <div class="card border-danger">
                                            <div class="card-header bg-danger text-white">
                                                <h6 class="mb-0">Vital Signs</h6>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text">{{ $chartData['vital_signs'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if(isset($chartData['diagnosis']) && $chartData['diagnosis'])
                                    <div class="col-md-6">
                                        <div class="card border-dark">
                                            <div class="card-header bg-dark text-white">
                                                <h6 class="mb-0">Diagnosis</h6>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text">{{ $chartData['diagnosis'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if(isset($chartData['care_plan']) && $chartData['care_plan'])
                                    <div class="col-12">
                                        <div class="card border-secondary">
                                            <div class="card-header bg-secondary text-white">
                                                <h6 class="mb-0">Care Plan</h6>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text">{{ $chartData['care_plan'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center text-muted py-3">
                                <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                                <p>No structured chart data available</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- AI Analysis -->
    @if($transcription->ai_analysis)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-robot me-2"></i>
                            AI Clinical Analysis
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="border rounded p-3" style="max-height: 600px; overflow-y: auto; background-color: rgba(10, 22, 40, 0.6);">
                            <div style="white-space: pre-wrap;">{{ $transcription->ai_analysis }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Actions</h5>
                    <div class="btn-group" role="group">
                        <a href="{{ route('ai.ambient-listening.history') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Back to History
                        </a>
                        <a href="{{ route('ai.ambient-listening.index') }}" class="btn btn-primary">
                            <i class="fas fa-microphone me-2"></i>
                            New Ambient Listening Session
                        </a>
                        @if($transcription->patient)
                            <a href="{{ route('patient.show', $transcription->patient->id) }}" class="btn btn-info">
                                <i class="fas fa-user me-2"></i>
                                View Patient Profile
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
