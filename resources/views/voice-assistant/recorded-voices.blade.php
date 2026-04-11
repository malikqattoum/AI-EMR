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
/* Professional Dashboard Header Styling */
.dashboard-header {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 212, 170, 0.2);
    position: relative;
    overflow: hidden;
}

.dashboard-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #00d4aa 0%, #2c3e50 100%);
}

.dashboard-header h2 {
    color: #ffffff;
    font-weight: 700;
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.dashboard-header h2::before {
    content: '🎙️';
    font-size: 2rem;
}

.dashboard-header p {
    color: rgba(255, 255, 255, 0.9);
    font-size: 1.1rem;
    font-weight: 500;
    margin-bottom: 0;
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

@section('title', 'Recorded Voices')

@section('content')
<div class="dashboard-container">
    <div class="container-fluid px-3 px-md-4">
<!-- Breadcrumb Navigation -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('ai.ambient-listening.index') }}">Ambient Listening</a></li>
        <li class="breadcrumb-item active" aria-current="page">History</li>
    </ol>
</nav>

<div class="dashboard-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2>Consultation History</h2>
            <p>View all your saved consultation recordings and transcripts</p>
        </div>
        <a href="{{ route('ai.ambient-listening.index') }}" class="btn btn-light btn-lg">
            <i class="fas fa-microphone me-2"></i>New Consultation
        </a>
    </div>
</div>
<div class="container-fluid py-4">
    <!-- Transcriptions List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($transcriptions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Transcription</th>
                                        <th>Status</th>
                                        <th>Duration</th>
                                        <th>Recorded At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transcriptions as $transcription)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-3">
                                                        <span class="avatar-title rounded-circle bg-primary text-white">
                                                            {{ substr($transcription->patient->name ?? 'N/A', 0, 1) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $transcription->patient->name ?? 'Unknown Patient' }}</h6>
                                                        <small class="text-muted">{{ $transcription->patient->email ?? '' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 300px;" title="{{ $transcription->raw_transcription }}">
                                                    {{ Str::limit($transcription->raw_transcription, 50) }}
                                                </div>
                                            </td>
                                            <td>
                                                @switch($transcription->status)
                                                    @case('active')
                                                        <span class="badge bg-success">Active</span>
                                                        @break
                                                    @case('completed')
                                                        <span class="badge bg-primary">Completed</span>
                                                        @break
                                                    @case('ai_analysis_complete')
                                                        <span class="badge bg-info">AI Analyzed</span>
                                                        @break
                                                    @case('diagnosis_created')
                                                        <span class="badge bg-success">Diagnosis Created</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ ucfirst($transcription->status) }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                @if($transcription->session_started_at && $transcription->session_ended_at)
                                                    {{ $transcription->session_started_at->diffInSeconds($transcription->session_ended_at) }}s
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $transcription->created_at->format('M d, Y H:i') }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('ai.ambient-listening.show', $transcription) }}"
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($transcription->diagnosis)
                                                        <a href="{{ route('diagnosis.show', $transcription->diagnosis) }}"
                                                           class="btn btn-sm btn-outline-success"
                                                           title="View Diagnosis">
                                                            <i class="fas fa-file-medical"></i>
                                                        </a>
                                                    @endif
                                                    @if($transcription->audio_file)
                                                        <a href="{{ route('ai.ambient-listening.download-audio', $transcription) }}"
                                                           class="btn btn-sm btn-outline-info"
                                                           title="Download Audio">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $transcriptions->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-microphone-slash fa-4x text-muted"></i>
                            </div>
                            <h4 class="text-muted">No Session Recordings Yet</h4>
                            <p class="text-muted mb-4">Start ambient listening sessions to see them here.</p>
                            <a href="{{ route('ai.ambient-listening.index') }}" class="btn btn-primary">
                                <i class="fas fa-microphone me-2"></i>
                                Start Ambient Listening Session
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection