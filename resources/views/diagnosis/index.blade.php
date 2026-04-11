@extends('layouts.doctor')

@section('title', 'My Diagnoses')

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

/* Override table-light for dark theme */
.table-light {
    background: rgba(0,212,170,0.06) !important;
    color: var(--offwhite) !important;
}
.table-light th {
    color: var(--muted) !important;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
}

/* Avatar styling */
.avatar-sm {
    width: 40px;
    height: 40px;
}

.table td {
    vertical-align: middle;
}

.badge {
    font-size: 0.75rem;
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

@section('content')
<div class="dashboard-container"><div class="container-fluid px-3 px-md-4">
<div class="dashboard-header">
    <h2>Diagnosed Cases</h2>
    <p>View and manage all patient diagnoses and medical records</p>
</div>
<div class="container-fluid px-2 px-md-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Breadcrumb Navigation -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Diagnoses</li>
                </ol>
            </nav>
            
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="fas fa-clipboard-list me-2"></i>All Diagnoses</h2>
                    <p class="text-muted">Manage and view all diagnoses you've created</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('ai.ambient-listening.index') }}" class="btn btn-success">
                        <i class="fas fa-microphone me-2"></i>New Consultation
                    </a>
                    <a href="{{ route('diagnosis.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Manual Diagnosis
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Diagnoses List -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Diagnoses ({{ $diagnoses->total() }})</h5>
                </div>
                <div class="card-body p-0">
                    @if($diagnoses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Patient</th>
                                        <th>Type</th>
                                        <th>Created</th>
                                        <th>Status</th>
                                        <th>Follow-ups</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($diagnoses as $diagnosis)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                                        <i class="fas fa-user text-white"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $diagnosis->patient->name }}</h6>
                                                        <small class="text-muted">{{ $diagnosis->patient->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $diagnosis->type === 'ai' ? 'info' : 'success' }}">
                                                    <i class="fas fa-{{ $diagnosis->type === 'ai' ? 'robot' : 'user-md' }} me-1"></i>
                                                    {{ ucfirst($diagnosis->type) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div>
                                                    <small class="text-muted">{{ $diagnosis->created_at->format('M j, Y') }}</small><br>
                                                    <small class="text-muted">{{ $diagnosis->created_at->format('g:i A') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    @if($diagnosis->patient_viewed_at)
                                                        <span class="badge bg-success mb-1">
                                                            <i class="fas fa-eye me-1"></i>Viewed
                                                        </span>
                                                        <small class="text-muted">{{ $diagnosis->patient_viewed_at->format('M j, g:i A') }}</small>
                                                    @else
                                                        <span class="badge bg-warning">
                                                            <i class="fas fa-clock me-1"></i>Pending
                                                        </span>
                                                    @endif

                                                    @if($diagnosis->patient_reviewed)
                                                        <span class="badge bg-info mt-1">
                                                            <i class="fas fa-star me-1"></i>Reviewed
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    <span class="badge bg-secondary">
                                                        {{ $diagnosis->follow_up_count }}/5
                                                    </span>
                                                    @if($diagnosis->follow_up_count > 0)
                                                        <br><small class="text-muted">questions</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('diagnosis.show', $diagnosis) }}"
                                                       class="btn btn-sm btn-outline-primary" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($diagnosis->voice_file_path)
                                                        <button class="btn btn-sm btn-outline-info"
                                                                onclick="playVoice('{{ $diagnosis->id }}')" title="Play Voice">
                                                            <i class="fas fa-play"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($diagnoses->hasPages())
                            <div class="card-footer">
                                {{ $diagnoses->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No diagnoses yet</h5>
                            <p class="text-muted">Start by creating your first diagnosis for a patient.</p>
                            <a href="{{ route('diagnosis.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Create First Diagnosis
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function playVoice(diagnosisId) {
    const audio = new Audio();
    audio.src = `/diagnosis/${diagnosisId}/voice`;
    const playButton = document.querySelector(`button[onclick="playVoice('${diagnosisId}')"]`);
    if (playButton) {
        const originalContent = playButton.innerHTML;
        playButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        playButton.disabled = true;
        const resetButton = () => {
            playButton.innerHTML = originalContent;
            playButton.disabled = false;
        };
        audio.addEventListener('ended', resetButton);
        audio.addEventListener('error', () => { resetButton(); alert('Error playing voice file.'); });
        audio.addEventListener('loadeddata', resetButton);
    }
    audio.play().catch(error => {
        if (playButton) { playButton.innerHTML = '<i class="fas fa-volume-up"></i>'; playButton.disabled = false; }
        alert('Could not play voice file.');
    });
}
</script>
@endpush

@endsection
