@extends('layouts.doctor')

@section('title', 'Messages')

@push('styles')
<style>
/* Page-specific styles for message list */
.list-group-item {
    background: transparent !important;
    border-bottom: 1px solid var(--card-border) !important;
    padding: 1rem 1.25rem !important;
    transition: background 0.2s;
}
.list-group-item:hover { background: rgba(0,212,170,0.05) !important; }
.list-group-item:last-child { border-bottom: none !important; }
.list-group-item h6 { color: var(--offwhite) !important; font-weight: 600; }
.list-group-item .text-muted { color: var(--muted) !important; }
.card-footer {
    background: rgba(0,212,170,0.03) !important;
    border-top: 1px solid var(--card-border) !important;
    padding: 1rem !important;
}
.badge.bg-info { background: rgba(59,130,246,0.15) !important; color: #60a5fa !important; }
.badge.bg-secondary { background: rgba(255,255,255,0.08) !important; color: var(--muted) !important; }
.badge.bg-success { background: rgba(0,212,170,0.15) !important; color: var(--teal) !important; }
</style>
@endpush

@section('content')
<div class="dashboard-container">
    <div class="container-fluid px-3 px-md-4">
        <div class="dashboard-header d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center">
                <div>
                    <h2 class="h1 mb-1">
                        <i class="fas fa-comments me-2"></i>
                        Patient Messages
                    </h2>
                    <p class="text-muted mb-0">Conversations with your patients</p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($threads->count() > 0)
            <div class="card">
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($threads as $thread)
                            <li class="list-group-item">
                                <a href="{{ route('doctor.messages.show', $thread) }}" class="text-decoration-none text-white">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="me-auto">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <h6 class="mb-0">{{ $thread->subject }}</h6>
                                                <span class="badge bg-{{ $thread->type === 'follow_up' ? 'info' : 'secondary' }}">
                                                    {{ $thread->type === 'follow_up' ? 'Follow-up' : 'General' }}
                                                </span>
                                            </div>
                                            <p class="text-muted small mb-1">
                                                {{ $thread->patient->name ?? 'Unknown Patient' }}
                                            </p>
                                            <small class="text-muted">
                                                {{ $thread->last_message_at?->diffForHumans() ?? '' }}
                                            </small>
                                        </div>
                                        @if($thread->aiSuggestions()->pending()->exists())
                                            <span class="badge bg-success">AI Ready</span>
                                        @endif
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @if($threads->hasPages())
                    <div class="card-footer text-center">
                        {{ $threads->links() }}
                    </div>
                @endif
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No messages yet</h4>
                <p class="text-muted">Patient messages will appear here.</p>
            </div>
        @endif
    </div>
</div>
@endsection