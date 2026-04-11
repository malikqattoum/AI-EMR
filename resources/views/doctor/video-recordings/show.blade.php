@extends('layouts.doctor')

@section('title', 'Video Recording Playback')

@push('styles')
<style>
    .video-player-container {
        position: relative;
        background: #000;
        border-radius: 12px;
        overflow: hidden;
        aspect-ratio: 16/9;
    }
    
    .video-player-container video {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    
    .ai-section {
        background: rgba(139, 92, 246, 0.05);
        border: 1px solid rgba(139, 92, 246, 0.2);
        border-radius: 12px;
        padding: 24px;
    }
    
    .ai-section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 2px solid rgba(139, 92, 246, 0.2);
    }
    
    .ai-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #8b5cf6, #6d28d9);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
    }
    
    .transcript-box {
        background: rgba(10, 22, 40, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        padding: 16px;
        max-height: 400px;
        overflow-y: auto;
        white-space: pre-wrap;
        font-size: 14px;
        line-height: 1.6;
    }
    
    .analysis-box {
        background: rgba(10, 22, 40, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        padding: 16px;
        max-height: 600px;
        overflow-y: auto;
        white-space: pre-wrap;
        font-size: 14px;
        line-height: 1.6;
    }
    
    .summary-box {
        background: rgba(10, 22, 40, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        padding: 16px;
        white-space: pre-wrap;
        font-size: 14px;
        line-height: 1.6;
    }
    
    .loading-spinner {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        padding: 40px;
        color: #6b7280;
    }
    
    .btn-generate {
        background: linear-gradient(135deg, #8b5cf6, #6d28d9);
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .btn-generate:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.4);
        color: white;
    }
    
    .btn-generate:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
    }
    
    .meta-item i {
        color: #8b5cf6;
        width: 20px;
    }
</style>
@endpush

@section('content')
<div class="dashboard-container">
    <div class="container">
        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('doctor.video-recordings.index') }}">Video Recordings</a></li>
                <li class="breadcrumb-item active" aria-current="page">Recording #{{ $recording->id }}</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="dashboard-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2><i class="fas fa-video me-2"></i>Video Recording</h2>
                    <p>
                        @if($recording->patient)
                            Consultation with {{ $recording->patient->name }}
                        @elseif($recording->appointment?->guest_name)
                            Consultation with {{ $recording->appointment->guest_name }}
                        @else
                            Video Consultation
                        @endif
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('doctor.video-recordings.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Recordings
                    </a>
                    @if($recording->recording_url)
                        <a href="{{ route('doctor.video-recordings.download', $recording->id) }}" class="btn btn-outline-primary" target="_blank">
                            <i class="fas fa-download me-2"></i>Download
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recording Meta Information -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3"><i class="fas fa-info-circle me-2"></i>Recording Details</h5>
                        <div class="meta-item">
                            <i class="fas fa-calendar"></i>
                            <span><strong>Date:</strong> {{ $recording->created_at->format('M j, Y g:i A') }}</span>
                        </div>
                        @if($recording->duration)
                            <div class="meta-item">
                                <i class="fas fa-clock"></i>
                                <span><strong>Duration:</strong> {{ $recording->formatted_duration }}</span>
                            </div>
                        @endif
                        @if($recording->resolution)
                            <div class="meta-item">
                                <i class="fas fa-expand"></i>
                                <span><strong>Resolution:</strong> {{ $recording->resolution }}</span>
                            </div>
                        @endif
                        @if($recording->file_size)
                            <div class="meta-item">
                                <i class="fas fa-file"></i>
                                <span><strong>File Size:</strong> {{ $recording->formatted_file_size }}</span>
                            </div>
                        @endif
                        <div class="meta-item">
                            <i class="fas fa-circle-info"></i>
                            <span><strong>Status:</strong> 
                                <span class="badge bg-{{ $recording->status === 'ready' ? 'success' : ($recording->status === 'failed' ? 'danger' : 'warning') }}">
                                    {{ ucfirst(str_replace('_', ' ', $recording->status)) }}
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3"><i class="fas fa-user me-2"></i>Appointment Info</h5>
                        @if($recording->appointment)
                            <div class="meta-item">
                                <i class="fas fa-calendar-check"></i>
                                <span><strong>Appointment #:</strong> {{ $recording->appointment_id }}</span>
                            </div>
                            @if($recording->appointment->reason)
                                <div class="meta-item">
                                    <i class="fas fa-stethoscope"></i>
                                    <span><strong>Reason:</strong> {{ $recording->appointment->reason }}</span>
                                </div>
                            @endif
                            @if($recording->appointment->symptoms)
                                <div class="meta-item">
                                    <i class="fas fa-notes-medical"></i>
                                    <span><strong>Symptoms:</strong> {{ Str::limit($recording->appointment->symptoms, 100) }}</span>
                                </div>
                            @endif
                            @if($recording->appointment->patient)
                                <div class="meta-item">
                                    <i class="fas fa-user"></i>
                                    <span><strong>Patient:</strong> {{ $recording->appointment->patient->name }}</span>
                                </div>
                            @endif
                        @else
                            <p class="text-muted">No appointment information linked.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Video Player -->
        @if($recording->recording_url)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-play-circle me-2"></i>Playback
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="video-player-container">
                                <video id="videoPlayer" controls preload="metadata">
                                    <source src="{{ $recording->recording_url }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif(in_array($recording->status, ['processing', 'transcribing', 'ai_processing']))
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-spinner fa-spin fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">Processing Recording</h4>
                            <p class="text-muted">Your recording is being processed. This may take a few minutes.</p>
                            <button class="btn btn-outline-primary" onclick="location.reload()">
                                <i class="fas fa-sync-alt me-2"></i>Refresh
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- AI Summary -->
        @if($recording->ai_summary)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-robot me-2 text-primary"></i>
                                AI Summary
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="summary-box">
                                {{ $recording->ai_summary }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($recording->transcription)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="ai-section">
                        <div class="ai-section-header">
                            <div class="ai-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div>
                                <h4 class="mb-1">AI Summary</h4>
                                <p class="text-muted mb-0">Generate a concise clinical summary of the consultation</p>
                            </div>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-generate btn-lg" onclick="generateSummary()">
                                <i class="fas fa-magic me-2"></i>Generate AI Summary
                            </button>
                        </div>
                        <div id="summary-loading" class="loading-spinner" style="display: none;">
                            <i class="fas fa-spinner fa-spin fa-2x"></i>
                            <span>Generating summary...</span>
                        </div>
                        <div id="summary-result" class="summary-box mt-3" style="display: none;"></div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Full Transcription -->
        @if($recording->transcription)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-closed-captioning me-2"></i>
                                Full Transcription
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="transcript-box">{{ $recording->transcription }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- AI Clinical Analysis -->
        @if($recording->ai_analysis)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-brain me-2 text-primary"></i>
                                AI Clinical Analysis
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="analysis-box">
                                {{ $recording->ai_analysis }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($recording->transcription)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="ai-section">
                        <div class="ai-section-header">
                            <div class="ai-icon">
                                <i class="fas fa-brain"></i>
                            </div>
                            <div>
                                <h4 class="mb-1">AI Clinical Analysis</h4>
                                <p class="text-muted mb-0">Comprehensive medical analysis with diagnoses and treatment recommendations</p>
                            </div>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-generate btn-lg" onclick="generateAnalysis()">
                                <i class="fas fa-magic me-2"></i>Generate AI Analysis
                            </button>
                        </div>
                        <div id="analysis-loading" class="loading-spinner" style="display: none;">
                            <i class="fas fa-spinner fa-spin fa-2x"></i>
                            <span>Generating comprehensive analysis...</span>
                        </div>
                        <div id="analysis-result" class="analysis-box mt-3" style="display: none;"></div>
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
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('doctor.video-recordings.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Recordings
                            </a>
                            @if($recording->appointment)
                                <a href="{{ route('doctor.appointments.show', $recording->appointment_id) }}" class="btn btn-info">
                                    <i class="fas fa-calendar-alt me-2"></i>View Appointment
                                </a>
                            @endif
                            @if($recording->patient)
                                <a href="{{ route('doctor.patients.show', $recording->patient->id) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-user me-2"></i>View Patient Profile
                                </a>
                            @endif
                            <form method="POST" action="{{ route('doctor.video-recordings.destroy', $recording->id) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this recording? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fas fa-trash me-2"></i>Delete Recording
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function generateSummary() {
    const btn = event.target.closest('button');
    const loading = document.getElementById('summary-loading');
    const result = document.getElementById('summary-result');
    
    btn.style.display = 'none';
    loading.style.display = 'flex';
    result.style.display = 'none';
    
    fetch('{{ route('doctor.video-recordings.generate-summary', $recording->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.json())
    .then(data => {
        loading.style.display = 'none';
        if (data.success) {
            result.textContent = data.summary;
            result.style.display = 'block';
        } else {
            alert('Error: ' + (data.error || 'Failed to generate summary'));
            btn.style.display = 'block';
        }
    })
    .catch(err => {
        loading.style.display = 'none';
        btn.style.display = 'block';
        alert('Failed to generate summary: ' + err.message);
    });
}

function generateAnalysis() {
    const btn = event.target.closest('button');
    const loading = document.getElementById('analysis-loading');
    const result = document.getElementById('analysis-result');
    
    btn.style.display = 'none';
    loading.style.display = 'flex';
    result.style.display = 'none';
    
    fetch('{{ route('doctor.video-recordings.generate-analysis', $recording->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.json())
    .then(data => {
        loading.style.display = 'none';
        if (data.success) {
            result.textContent = data.analysis;
            result.style.display = 'block';
        } else {
            alert('Error: ' + (data.error || 'Failed to generate analysis'));
            btn.style.display = 'block';
        }
    })
    .catch(err => {
        loading.style.display = 'none';
        btn.style.display = 'block';
        alert('Failed to generate analysis: ' + err.message);
    });
}
</script>
@endpush
