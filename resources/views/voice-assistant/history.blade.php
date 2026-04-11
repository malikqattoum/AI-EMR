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

@section('title', 'Ambient Listening History')

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
                            <h1 class="card-title h3 mb-2">🎤 Ambient Listening History</h1>
                            <p class="card-text mb-0">Review your previous ambient listening sessions</p>
                        </div>
                        <a href="{{ route('ai.ambient-listening.index') }}" class="btn btn-light">
                            <i class="fas fa-microphone me-2"></i>
                            New Session
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sessions List -->
    <div class="row">
        <div class="col-12">
            @if($transcriptions->count() > 0)
                @foreach($transcriptions as $transcription)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h5 class="card-title mb-1">
                                        <i class="fas fa-user me-2 text-primary"></i>
                                        {{ $transcription->patient ? $transcription->patient->name : 'Unknown Patient' }}
                                    </h5>
                                    <p class="card-text text-muted mb-2">
                                        <i class="fas fa-calendar me-2"></i>
                                        {{ $transcription->session_started_at ? $transcription->session_started_at->format('M d, Y - H:i A') : 'Date not available' }}
                                    </p>
                                    @if($transcription->raw_transcription)
                                        <p class="card-text">
                                            <small class="text-muted">
                                                {{ Str::limit($transcription->raw_transcription, 150) }}
                                            </small>
                                        </p>
                                    @endif
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <div class="mb-2">
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
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('ai.ambient-listening.show', $transcription) }}"
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>
                                            View
                                        </a>
                                        @if($transcription->ai_analysis)
                                            <button class="btn btn-outline-info btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#analysisModal{{ $transcription->id }}">
                                                <i class="fas fa-robot me-1"></i>
                                                Analysis
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Analysis Modal -->
                    @if($transcription->ai_analysis)
                        <div class="modal fade" id="analysisModal{{ $transcription->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            <i class="fas fa-robot me-2"></i>
                                            AI Analysis - {{ $transcription->patient ? $transcription->patient->name : 'Unknown Patient' }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div style="white-space: pre-wrap; max-height: 400px; overflow-y: auto;">
                                            {{ $transcription->ai_analysis }}
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <a href="{{ route('ai.ambient-listening.show', $transcription) }}" class="btn btn-primary">
                                            View Full Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $transcriptions->links() }}
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-microphone fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">No Voice Sessions Yet</h4>
                        <p class="text-muted mb-4">You haven't recorded any voice consultations yet.</p>
                        <a href="{{ route('ai.ambient-listening.index') }}" class="btn btn-primary">
                            <i class="fas fa-microphone me-2"></i>
                            Start Your First Session
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
</div>
@endsection
