@extends('layouts.doctor')

@section('title', 'Ambient Listening Training Guide')

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

@section('content')
<div class="dashboard-container">
    <div class="container-fluid px-3 px-md-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-graduation-cap me-2"></i>
                        Ambient Listening Training Guide
                    </h4>
                    <small>Master the hybrid ambient listening for enhanced medical consultations</small>
                </div>
                <div class="card-body">
                    <!-- Introduction -->
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle me-2"></i>Welcome to Ambient Listening Training</h5>
                        <p class="mb-0">This guide will help you understand and effectively use our advanced ambient listening system, which combines real-time transcription with AI-powered server processing for superior medical documentation accuracy.</p>
                    </div>

                    <!-- Quick Start -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-rocket me-2"></i>Quick Start Guide</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>1. Patient Selection</h6>
                                            <p>Select a patient from the dropdown or create a new patient profile.</p>

                                            <h6>2. Start Recording</h6>
                                            <p>Click "Start Recording" to begin the hybrid session. The system will:</p>
                                            <ul>
                                                <li>🎙️ Start live voice transcription</li>
                                                <li>🎵 Begin audio recording for server processing</li>
                                                <li>🤖 Enable AI-powered analysis</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>3. Conduct Consultation</h6>
                                            <p>Speak naturally with your patient. The system automatically:</p>
                                            <ul>
                                                <li>Transcribes conversation in real-time</li>
                                                <li>Detects language switching</li>
                                                <li>Monitors audio quality</li>
                                                <li>Identifies speaker transitions</li>
                                            </ul>

                                            <h6>4. Generate Analysis</h6>
                                            <p>Stop recording and let the AI analyze the consultation for:</p>
                                            <ul>
                                                <li>Medical data extraction</li>
                                                <li>Diagnosis suggestions</li>
                                                <li>Care plan recommendations</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Feature Deep Dive -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4 class="mb-3"><i class="fas fa-cogs me-2"></i>Understanding Hybrid Features</h4>
                        </div>
                    </div>

                    <!-- Real-time Transcription -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-microphone text-primary me-2"></i>
                                        Real-time Transcription
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h6>How it works:</h6>
                                            <p>The system uses your browser's speech recognition to transcribe conversation instantly. This provides immediate feedback and allows you to correct any transcription errors in real-time.</p>

                                            <h6>Best practices:</h6>
                                            <ul>
                                                <li>Speak clearly and at a moderate pace</li>
                                                <li>Pause briefly between speakers</li>
                                                <li>Use the language selector for multilingual consultations</li>
                                                <li>Monitor the audio level indicator for optimal recording</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="alert alert-success">
                                                <h6><i class="fas fa-check-circle me-1"></i>Benefits</h6>
                                                <ul class="mb-0">
                                                    <li>Immediate transcription</li>
                                                    <li>Real-time error correction</li>
                                                    <li>Multi-language support</li>
                                                    <li>Speaker transition detection</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Server-side Processing -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-server text-success me-2"></i>
                                        Server-side AI Processing
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h6>How it works:</h6>
                                            <p>When you stop recording, the system uploads the audio file to our secure servers where advanced AI (OpenAI Whisper) re-processes the entire conversation. This often provides more accurate transcription than real-time processing.</p>

                                            <h6>What happens:</h6>
                                            <ul>
                                                <li>Audio is securely uploaded and processed</li>
                                                <li>AI extracts medical information automatically</li>
                                                <li>System compares live vs server results</li>
                                                <li>Best transcription is selected and displayed</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="alert alert-success">
                                                <h6><i class="fas fa-brain me-1"></i>AI Features</h6>
                                                <ul class="mb-0">
                                                    <li>Higher accuracy transcription</li>
                                                    <li>Automatic medical data extraction</li>
                                                    <li>Structured diagnosis suggestions</li>
                                                    <li>Care plan recommendations</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hands-free Mode -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-robot text-warning me-2"></i>
                                        Hands-free Mode
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h6>Advanced features:</h6>
                                            <ul>
                                                <li><strong>Continuous recording:</strong> System restarts automatically after pauses</li>
                                                <li><strong>Silence detection:</strong> Automatically pauses during long silences</li>
                                                <li><strong>Audio monitoring:</strong> Real-time audio level visualization</li>
                                                <li><strong>Speaker detection:</strong> Identifies when different people are speaking</li>
                                            </ul>

                                            <h6>Controls:</h6>
                                            <ul>
                                                <li><kbd>Ctrl+H</kbd> - Toggle hands-free mode</li>
                                                <li><kbd>Ctrl+P</kbd> - Pause/Resume recording</li>
                                                <li><kbd>Ctrl+Space</kbd> - Quick start/stop</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="alert alert-warning">
                                                <h6><i class="fas fa-lightbulb me-1"></i>Tips</h6>
                                                <ul class="mb-0">
                                                    <li>Use in quiet environments</li>
                                                    <li>Position microphone properly</li>
                                                    <li>Monitor audio levels</li>
                                                    <li>Test before patient consultations</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Troubleshooting -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Troubleshooting Guide</h5>
                                </div>
                                <div class="card-body">
                                    <div class="accordion" id="troubleshootingAccordion">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#microphone">
                                                    <i class="fas fa-microphone me-2"></i>Microphone Issues
                                                </button>
                                            </h2>
                                            <div id="microphone" class="accordion-collapse collapse show" data-bs-parent="#troubleshootingAccordion">
                                                <div class="accordion-body">
                                                    <ul>
                                                        <li><strong>Permission denied:</strong> Allow microphone access when prompted by your browser</li>
                                                        <li><strong>No microphone found:</strong> Check microphone connection and browser settings</li>
                                                        <li><strong>Poor audio quality:</strong> Use a quality microphone and reduce background noise</li>
                                                        <li><strong>Chrome recommended:</strong> Best compatibility with Chrome browser</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#transcription">
                                                    <i class="fas fa-file-alt me-2"></i>Transcription Problems
                                                </button>
                                            </h2>
                                            <div id="transcription" class="accordion-collapse collapse" data-bs-parent="#troubleshootingAccordion">
                                                <div class="accordion-body">
                                                    <ul>
                                                        <li><strong>Inaccurate transcription:</strong> Speak clearly and use medical terminology consistently</li>
                                                        <li><strong>Language switching:</strong> The system auto-detects language changes</li>
                                                        <li><strong>Multiple speakers:</strong> Pause between speakers for better separation</li>
                                                        <li><strong>Server processing:</strong> Wait for server-side processing to complete for best accuracy</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#performance">
                                                    <i class="fas fa-chart-line me-2"></i>Performance Issues
                                                </button>
                                            </h2>
                                            <div id="performance" class="accordion-collapse collapse" data-bs-parent="#troubleshootingAccordion">
                                                <div class="accordion-body">
                                                    <ul>
                                                        <li><strong>Slow processing:</strong> Check internet connection and try again</li>
                                                        <li><strong>Server errors:</strong> System automatically falls back to live transcription</li>
                                                        <li><strong>Memory issues:</strong> Restart browser if experiencing slowdowns</li>
                                                        <li><strong>View performance:</strong> Check the Performance page for detailed analytics</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Best Practices -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="fas fa-star me-2"></i>Best Practices for Medical Consultations</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6><i class="fas fa-user-md me-2"></i>Consultation Setup</h6>
                                            <ul>
                                                <li>Test microphone and audio levels before starting</li>
                                                <li>Ensure patient consent for voice recording</li>
                                                <li>Choose appropriate language setting</li>
                                                <li>Minimize background noise</li>
                                            </ul>

                                            <h6><i class="fas fa-comments me-2"></i>During Consultation</h6>
                                            <ul>
                                                <li>Speak clearly and at moderate pace</li>
                                                <li>Use consistent medical terminology</li>
                                                <li>Allow brief pauses between speakers</li>
                                                <li>Monitor transcription accuracy in real-time</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6><i class="fas fa-brain me-2"></i>AI Analysis</h6>
                                            <ul>
                                                <li>Review AI-extracted medical data</li>
                                                <li>Verify diagnosis suggestions</li>
                                                <li>Use AI analysis as clinical decision support</li>
                                                <li>Always apply your professional judgment</li>
                                            </ul>

                                            <h6><i class="fas fa-save me-2"></i>Documentation</h6>
                                            <ul>
                                                <li>Complete diagnosis entry after AI analysis</li>
                                                <li>Link to appointments when appropriate</li>
                                                <li>Review and edit AI-generated content</li>
                                                <li>Save comprehensive medical records</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Monitoring -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-chart-bar me-2"></i>
                                        Monitor Your Performance
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p>Use the Performance page to track your ambient listening usage and success rates. Key metrics include:</p>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="text-center">
                                                <h6>Success Rates</h6>
                                                <p>Track transcription and processing accuracy</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-center">
                                                <h6>Processing Times</h6>
                                                <p>Monitor system performance and efficiency</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-center">
                                                <h6>Error Analysis</h6>
                                                <p>Identify and resolve common issues</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center mt-3">
                                        <a href="{{ route('ai.ambient-listening.performance') }}" class="btn btn-primary">
                                            <i class="fas fa-chart-line me-2"></i>View Performance Analytics
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scrolling to accordion sections
    const accordionButtons = document.querySelectorAll('.accordion-button');
    accordionButtons.forEach(button => {
        button.addEventListener('click', function() {
            setTimeout(() => {
                this.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }, 300);
        });
    });
});
</script>
@endsection