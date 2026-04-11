@extends('layouts.doctor')

@section('title', 'Chat Settings')

@push('styles')
<style>
/* Dark theme overrides for chat settings page */
.card {
    background: var(--card-bg) !important;
    border: 1px solid var(--card-border) !important;
    border-radius: 16px !important;
}
.card-header {
    background: rgba(0,212,170,0.05) !important;
    border-bottom: 1px solid var(--card-border) !important;
    color: var(--offwhite) !important;
}
.card-body { background: transparent !important; }
.card-footer { background: rgba(0,212,170,0.03) !important; border-top: 1px solid var(--card-border) !important; }

.card-header.bg-info { background: rgba(59,130,246,0.1) !important; }
.card-header.bg-warning { background: rgba(251,191,36,0.1) !important; }

.form-control, .form-select {
    background: rgba(10,20,40,0.8) !important;
    border: 1px solid var(--card-border) !important;
    color: var(--offwhite) !important;
    border-radius: 10px !important;
}
.form-control:focus, .form-select:focus {
    border-color: rgba(0,212,170,0.5) !important;
    box-shadow: 0 0 0 3px rgba(0,212,170,0.08) !important;
}
.form-control::placeholder { color: rgba(232,237,231,0.25) !important; }
.form-label { color: var(--offwhite) !important; }
.form-text, .text-muted { color: var(--muted) !important; }

.alert-info {
    background: rgba(59,130,246,0.08) !important;
    border: 1px solid rgba(59,130,246,0.2) !important;
    color: #93c5fd !important;
}
.alert-success { background: rgba(0,212,170,0.08) !important; border: 1px solid rgba(0,212,170,0.2) !important; color: var(--teal) !important; }

.btn-primary { background: var(--teal) !important; border-color: var(--teal) !important; color: var(--navy) !important; font-weight: 600; }
.btn-primary:hover { background: #00e8bb !important; }
.btn-secondary { background: rgba(255,255,255,0.06) !important; border: 1px solid rgba(255,255,255,0.1) !important; color: var(--muted) !important; }
.btn-secondary:hover { background: rgba(255,255,255,0.1) !important; color: var(--offwhite) !important; }

.text-dark { color: var(--offwhite) !important; }
.text-white { color: var(--offwhite) !important; }
.border-info { border-color: rgba(59,130,246,0.2) !important; }
.border-warning { border-color: rgba(251,191,36,0.2) !important; }

.card.border-info { border-color: rgba(59,130,246,0.2) !important; }
.card.border-warning { border-color: rgba(251,191,36,0.2) !important; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-cog me-2"></i>
                        Chat Settings
                    </h3>
                </div>

                <form action="{{ route('doctor.chat.update-settings') }}" method="POST">
                    @csrf

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- AI Chat Enable/Disable -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card border-info">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="mb-0">
                                            <i class="fas fa-robot me-2"></i>
                                            AI Assistant
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   name="ai_chat_enabled"
                                                   id="ai_chat_enabled"
                                                   value="1"
                                                   {{ $doctor->ai_chat_enabled ? 'checked' : '' }}>
                                            <label class="form-check-label" for="ai_chat_enabled">
                                                <strong>Enable AI Assistant</strong>
                                            </label>
                                        </div>
                                        <small class="text-muted">
                                            When enabled, the AI assistant will automatically respond to patient messages.
                                            When disabled, you'll need to manually respond to all messages.
                                        </small>

                                        <div class="mt-3">
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle me-2"></i>
                                                <strong>AI Features:</strong>
                                                <ul class="mb-0 mt-2">
                                                    <li>Automatic language detection and response</li>
                                                    <li>Appointment booking assistance</li>
                                                    <li>Basic medical information</li>
                                                    <li>Emergency situations handling</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- AI Settings (show only if AI is enabled) -->
                        <div id="ai-settings" style="{{ $doctor->ai_chat_enabled ? '' : 'display: none;' }}">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="ai_welcome_message" class="form-label">
                                        <i class="fas fa-hand-wave me-1"></i>
                                        Custom Welcome Message
                                    </label>
                                    <textarea class="form-control"
                                              id="ai_welcome_message"
                                              name="ai_welcome_message"
                                              rows="3"
                                              placeholder="Leave empty to use default welcome message">{{ $doctor->ai_chat_settings['welcome_message'] ?? '' }}</textarea>
                                    <small class="text-muted">
                                        Customize the first message patients see when they start a chat.
                                    </small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="ai_fallback_message" class="form-label">
                                        <i class="fas fa-question-circle me-1"></i>
                                        Fallback Message
                                    </label>
                                    <textarea class="form-control"
                                              id="ai_fallback_message"
                                              name="ai_fallback_message"
                                              rows="3"
                                              placeholder="Leave empty to use default fallback message">{{ $doctor->ai_chat_settings['fallback_message'] ?? '' }}</textarea>
                                    <small class="text-muted">
                                        Message shown when AI can't understand the patient's question.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Manual Chat Information -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card border-warning">
                                    <div class="card-header" style="background: rgba(251,191,36,0.15); border-color: rgba(251,191,36,0.2);">
                                        <h5 class="mb-0">
                                            <i class="fas fa-user-md me-2"></i>
                                            Manual Chat Management
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-2">
                                            <strong>Real-time Responses:</strong> When you reply to patients from the
                                            <a href="{{ route('doctor.chat.index') }}" class="text-decoration-none">
                                                Chat Management page
                                            </a>,
                                            your messages will appear instantly in the patient's chat widget.
                                        </p>
                                        <p class="mb-0">
                                            <strong>AI + Manual:</strong> You can have AI enabled and still send manual replies.
                                            Your manual messages will take priority and the AI will pause for that conversation.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Save Settings
                        </button>
                        <a href="{{ route('doctor.chat.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Back to Chat Management
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const aiToggle = document.getElementById('ai_chat_enabled');
    const aiSettings = document.getElementById('ai-settings');

    aiToggle.addEventListener('change', function() {
        if (this.checked) {
            aiSettings.style.display = 'block';
        } else {
            aiSettings.style.display = 'none';
        }
    });
});
</script>
@endsection
