@extends('layouts.doctor')

@section('title', 'Video Recordings')

@push('styles')
<style>
    .recording-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .recording-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }
    
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .status-ready { background: #10b981; color: white; }
    .status-recording { background: #ef4444; color: white; animation: pulse 1.5s infinite; }
    .status-processing, .status-transcribing, .status-ai_processing { background: #f59e0b; color: white; }
    .status-failed { background: #6b7280; color: white; }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
    }
    
    .ai-badge {
        background: #8b5cf6;
        color: white;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
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
                <li class="breadcrumb-item active" aria-current="page">Video Recordings</li>
            </ol>
        </nav>

        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2><i class="fas fa-video me-2"></i>Video Recordings</h2>
                    <p>Recorded video consultations with AI analysis</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="table-card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('doctor.video-recordings.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>Ready</option>
                            <option value="recording" {{ request('status') === 'recording' ? 'selected' : '' }}>Recording</option>
                            <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="transcribing" {{ request('status') === 'transcribing' ? 'selected' : '' }}>Transcribing</option>
                            <option value="ai_processing" {{ request('status') === 'ai_processing' ? 'selected' : '' }}>AI Processing</option>
                            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="From Date">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="To Date">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Recordings List -->
        @if($recordings->count() > 0)
            <div class="table-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Date</th>
                                    <th>Duration</th>
                                    <th>Status</th>
                                    <th>AI Analysis</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recordings as $recording)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2" style="width: 36px; height: 36px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-user text-muted"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $recording->patient?->name ?? $recording->appointment?->guest_name ?? 'Unknown' }}</div>
                                                    <small class="text-muted">Appointment #{{ $recording->appointment_id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>{{ $recording->created_at->format('M j, Y') }}</div>
                                            <small class="text-muted">{{ $recording->created_at->format('g:i A') }}</small>
                                        </td>
                                        <td>
                                            @if($recording->duration)
                                                {{ $recording->formatted_duration }}
                                            @else
                                                <span class="text-muted">--</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="status-badge status-{{ str_replace('_', '-', $recording->status) }}">
                                                @if($recording->status === 'recording')
                                                    <i class="fas fa-circle me-1"></i>Recording
                                                @elseif(in_array($recording->status, ['processing', 'transcribing', 'ai_processing']))
                                                    <i class="fas fa-spinner fa-spin me-1"></i>{{ ucfirst(str_replace('_', ' ', $recording->status)) }}
                                                @elseif($recording->status === 'ready')
                                                    <i class="fas fa-check-circle me-1"></i>Ready
                                                @elseif($recording->status === 'failed')
                                                    <i class="fas fa-times-circle me-1"></i>Failed
                                                @else
                                                    {{ ucfirst($recording->status) }}
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            @if($recording->hasAiAnalysis())
                                                <span class="ai-badge"><i class="fas fa-robot me-1"></i>Complete</span>
                                            @elseif($recording->status === 'ready')
                                                <span class="text-muted">Pending</span>
                                            @else
                                                <span class="text-muted">--</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                @if($recording->status === 'ready')
                                                    <a href="{{ route('doctor.video-recordings.show', $recording->id) }}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-play me-1"></i>View
                                                    </a>
                                                    <a href="{{ route('doctor.video-recordings.download', $recording->id) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                                                        <i class="fas fa-download me-1"></i>
                                                    </a>
                                                @elseif(in_array($recording->status, ['processing', 'transcribing', 'ai_processing']))
                                                    <span class="btn btn-sm btn-outline-secondary" disabled>
                                                        <i class="fas fa-spinner fa-spin me-1"></i>Processing...
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $recordings->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-video fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No Video Recordings</h4>
                <p class="text-muted">Video recordings from your consultations will appear here.</p>
            </div>
        @endif
    </div>
</div>
@endsection
