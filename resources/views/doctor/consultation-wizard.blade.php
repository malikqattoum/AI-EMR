@extends('layouts.doctor')

@section('title', 'Consultation Wizard')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/consultation-wizard.css') }}">
@endpush

@section('content')
<div class="wizard-overlay" id="consultationWizard">
    <!-- Wizard Header -->
    <div class="wizard-header">
        <div class="wizard-progress">
            <div class="progress-steps">
                <div class="progress-step active" data-step="1">
                    <div class="step-number">1</div>
                    <div class="step-label">Patient Prep</div>
                </div>
                <div class="progress-line"></div>
                <div class="progress-step" data-step="2">
                    <div class="step-number">2</div>
                    <div class="step-label">Consultation</div>
                </div>
                <div class="progress-line"></div>
                <div class="progress-step" data-step="3">
                    <div class="step-number">3</div>
                    <div class="step-label">AI Review</div>
                </div>
                <div class="progress-line"></div>
                <div class="progress-step" data-step="4">
                    <div class="step-number">4</div>
                    <div class="step-label">Diagnosis</div>
                </div>
                <div class="progress-line"></div>
                <div class="progress-step" data-step="5">
                    <div class="step-number">5</div>
                    <div class="step-label">Complete</div>
                </div>
            </div>
        </div>
        <button class="wizard-exit-btn" onclick="exitWizard()" title="Exit Wizard (Esc)">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Wizard Body -->
    <div class="wizard-body">
        <!-- Step 1: Patient Pre-Consultation Prep -->
        <div class="wizard-step-content" id="step-1" style="display: none;">
            <div class="step-container">
                <div class="step-header">
                    <h2><i class="fas fa-user-md"></i> Patient Pre-Consultation</h2>
                    <p class="step-description">Review patient information before starting the consultation</p>
                </div>

                <div class="patient-card">
                    <div class="patient-header-info">
                        <div class="patient-avatar" id="patientAvatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="patient-details">
                            <h3 id="patientName">Loading...</h3>
                            <div class="patient-meta">
                                <span id="patientAge"><i class="fas fa-birthday-cake"></i> -- years</span>
                                <span id="patientGender"><i class="fas fa-venus-mars"></i> --</span>
                                <span id="patientPhone"><i class="fas fa-phone"></i> --</span>
                            </div>
                        </div>
                        <div class="patient-risk-badge" id="patientRiskBadge">
                            <i class="fas fa-shield-alt"></i> Loading
                        </div>
                    </div>
                </div>

                <div class="ai-summary-card">
                    <div class="ai-summary-header">
                        <i class="fas fa-robot"></i>
                        <h4>AI Case Summary</h4>
                    </div>
                    <div class="ai-summary-content" id="aiCaseSummary">
                        <p class="loading-summary"><i class="fas fa-spinner fa-spin"></i> Generating case summary...</p>
                    </div>
                </div>

                <div class="checklist-card">
                    <h4><i class="fas fa-clipboard-check"></i> Pre-Visit Checklist</h4>
                    <div class="checklist-items" id="preVisitChecklist">
                        <div class="checklist-item">
                            <input type="checkbox" id="check-allergies" checked disabled>
                            <label for="check-allergies">Allergies reviewed</label>
                        </div>
                        <div class="checklist-item">
                            <input type="checkbox" id="check-medications" checked disabled>
                            <label for="check-medications">Current medications loaded</label>
                        </div>
                        <div class="checklist-item">
                            <input type="checkbox" id="check-history" checked disabled>
                            <label for="check-history">Medical history available</label>
                        </div>
                        <div class="checklist-item">
                            <input type="checkbox" id="check-labs">
                            <label for="check-labs">Pending lab results reviewed</label>
                        </div>
                    </div>
                </div>

                <div class="step-actions">
                    <button class="btn-secondary" onclick="exitWizard()">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </button>
                    <button class="btn-primary" onclick="goToStep(2)">
                        I'm Ready <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Step 2: Consultation Recording -->
        <div class="wizard-step-content" id="step-2" style="display: none;">
            <div class="step-container">
                <div class="step-header">
                    <h2><i class="fas fa-microphone"></i> Consultation Recording</h2>
                    <p class="step-description">Record the consultation - AI will transcribe and analyze in real-time</p>
                </div>

                <div class="recording-status-card" id="recordingStatusCard">
                    <div class="recording-indicator" id="recordingIndicator" style="display: none;">
                        <div class="recording-dot"></div>
                        <span class="recording-text">Recording in Progress</span>
                        <span class="recording-timer" id="recordingTimer">00:00</span>
                    </div>
                    <div class="not-recording" id="notRecording">
                        <i class="fas fa-microphone-alt"></i>
                        <p>Ready to start recording</p>
                    </div>
                </div>

                <div class="recording-controls">
                    <button class="btn-record-start" id="startRecordingBtn" onclick="startRecording()">
                        <i class="fas fa-microphone"></i> Start Recording
                    </button>
                    <button class="btn-record-stop" id="stopRecordingBtn" onclick="stopRecording()" style="display: none;">
                        <i class="fas fa-stop"></i> Stop Recording
                    </button>
                </div>

                <div class="transcript-preview" id="transcriptPreview">
                    <h4><i class="fas fa-file-alt"></i> Live Transcription</h4>
                    <div class="transcript-text" id="liveTranscript">
                        <p class="transcript-placeholder">Transcription will appear here when recording starts...</p>
                    </div>
                </div>

                <div class="step-actions">
                    <button class="btn-secondary" onclick="goToStep(1)">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                    <button class="btn-primary" id="stopAndAnalyzeBtn" onclick="stopRecordingAndAnalyze()" style="display: none;">
                        Stop & Analyze <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Step 3: AI Analysis Review -->
        <div class="wizard-step-content" id="step-3" style="display: none;">
            <div class="step-container">
                <div class="step-header">
                    <h2><i class="fas fa-brain"></i> AI Analysis Review</h2>
                    <p class="step-description">Review and edit AI-extracted clinical information</p>
                </div>

                <div class="ai-processing" id="aiProcessing">
                    <div class="spinner-large">
                        <i class="fas fa-cog fa-spin"></i>
                    </div>
                    <h3>AI is analyzing your consultation...</h3>
                    <p class="processing-status">Extracting clinical data and generating analysis</p>
                </div>

                <div class="clinical-chart" id="clinicalChart" style="display: none;">
                    <div class="chart-grid">
                        <div class="chart-field">
                            <label><i class="fas fa-stethoscope"></i> Symptoms</label>
                            <textarea id="chartSymptoms" rows="3" placeholder="AI will extract symptoms from consultation..."></textarea>
                        </div>
                        <div class="chart-field">
                            <label><i class="fas fa-history"></i> Medical History</label>
                            <textarea id="chartHistory" rows="3" placeholder="Medical history discussed..."></textarea>
                        </div>
                        <div class="chart-field">
                            <label><i class="fas fa-search"></i> Physical Findings</label>
                            <textarea id="chartFindings" rows="3" placeholder="Physical examination findings..."></textarea>
                        </div>
                        <div class="chart-field">
                            <label><i class="fas fa-pills"></i> Medications</label>
                            <textarea id="chartMedications" rows="3" placeholder="Medications mentioned or prescribed..."></textarea>
                        </div>
                        <div class="chart-field">
                            <label><i class="fas fa-heartbeat"></i> Vital Signs</label>
                            <textarea id="chartVitals" rows="2" placeholder="Vital signs discussed..."></textarea>
                        </div>
                        <div class="chart-field">
                            <label><i class="fas fa-diagnoses"></i> Preliminary Diagnosis</label>
                            <textarea id="chartDiagnosis" rows="3" placeholder="AI-suggested diagnosis..."></textarea>
                        </div>
                        <div class="chart-field full-width">
                            <label><i class="fas fa-clipboard-list"></i> Care Plan</label>
                            <textarea id="chartCarePlan" rows="4" placeholder="Recommended care plan..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="ai-analysis-panel" id="aiAnalysisPanel" style="display: none;">
                    <h4><i class="fas fa-robot"></i> Full AI Clinical Analysis</h4>
                    <div class="analysis-content" id="fullAnalysis">
                        <!-- AI analysis will be displayed here -->
                    </div>
                </div>

                <div class="step-actions">
                    <button class="btn-secondary" onclick="goToStep(2)">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                    <button class="btn-primary" onclick="goToStep(4)">
                        Chart Looks Good <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Step 4: Diagnosis Entry -->
        <div class="wizard-step-content" id="step-4" style="display: none;">
            <div class="step-container">
                <div class="step-header">
                    <h2><i class="fas fa-file-medical"></i> Diagnosis Entry</h2>
                    <p class="step-description">Write your professional diagnosis (AI has pre-filled a suggestion)</p>
                </div>

                <div class="diagnosis-card">
                    <label for="diagnosisText">Professional Diagnosis *</label>
                    <textarea id="diagnosisText" rows="6" placeholder="Enter your diagnosis here... AI has pre-filled a suggestion based on the consultation."></textarea>
                    
                    <div class="icd-suggestions">
                        <h5><i class="fas fa-tags"></i> Suggested ICD-10 Codes</h5>
                        <div class="icd-codes" id="icdSuggestions">
                            <!-- ICD codes will be suggested here -->
                            <span class="icd-tag" onclick="insertICDCode('J06.9 - Acute upper respiratory infection')">J06.9</span>
                            <span class="icd-tag" onclick="insertICDCode('J18.9 - Pneumonia, unspecified')">J18.9</span>
                            <span class="icd-tag" onclick="insertICDCode('R50.9 - Fever, unspecified')">R50.9</span>
                        </div>
                    </div>
                </div>

                <div class="completion-type-card">
                    <label>Consultation Type</label>
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type="radio" name="completionType" value="completed" checked>
                            <span><i class="fas fa-check-circle"></i> Completed</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="completionType" value="referral">
                            <span><i class="fas fa-share"></i> Referred to Specialist</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="completionType" value="followup">
                            <span><i class="fas fa-calendar-check"></i> Follow-up Required</span>
                        </label>
                    </div>
                </div>

                <div class="step-actions">
                    <button class="btn-secondary" onclick="goToStep(3)">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                    <button class="btn-primary" onclick="goToStep(5)">
                        Save Diagnosis <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Step 5: Completion & Next Steps -->
        <div class="wizard-step-content" id="step-5" style="display: none;">
            <div class="step-container">
                <div class="step-header">
                    <h2><i class="fas fa-check-circle"></i> Complete Appointment</h2>
                    <p class="step-description">Review summary and finalize the consultation</p>
                </div>

                <div class="completion-summary-card">
                    <h4>Consultation Summary</h4>
                    <div class="summary-details">
                        <div class="summary-row">
                            <span class="summary-label">Patient:</span>
                            <span class="summary-value" id="summaryPatientName">--</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Diagnosis:</span>
                            <span class="summary-value" id="summaryDiagnosis">--</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Duration:</span>
                            <span class="summary-value" id="summaryDuration">--</span>
                        </div>
                    </div>
                </div>

                <div class="quick-actions-card">
                    <h4><i class="fas fa-bolt"></i> Quick Actions</h4>
                    <div class="quick-actions-grid">
                        <label class="action-checkbox">
                            <input type="checkbox" id="scheduleFollowup">
                            <div class="action-content">
                                <i class="fas fa-calendar-plus"></i>
                                <span>Schedule Follow-up</span>
                            </div>
                        </label>
                        <label class="action-checkbox">
                            <input type="checkbox" id="prescribeMeds">
                            <div class="action-content">
                                <i class="fas fa-prescription"></i>
                                <span>Prescribe Medications</span>
                            </div>
                        </label>
                        <label class="action-checkbox">
                            <input type="checkbox" id="orderLabs">
                            <div class="action-content">
                                <i class="fas fa-flask"></i>
                                <span>Order Lab Tests</span>
                            </div>
                        </label>
                        <label class="action-checkbox">
                            <input type="checkbox" id="sendSummary" checked>
                            <div class="action-content">
                                <i class="fas fa-envelope"></i>
                                <span>Send Summary to Patient</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="followup-date" id="followupDateSection" style="display: none;">
                    <label for="followupDate">Follow-up Date</label>
                    <input type="datetime-local" id="followupDate" class="form-input">
                </div>

                <div class="step-actions">
                    <button class="btn-secondary" onclick="goToStep(4)">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                    <button class="btn-success-complete" onclick="completeConsultation()">
                        <i class="fas fa-check"></i> Complete Appointment
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/consultation-wizard.js') }}"></script>
<script>
// Initialize wizard with patient data from dashboard
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const appointmentId = urlParams.get('appointment_id');
    const patientId = urlParams.get('patient_id');
    
    if (patientId) {
        // Update wizard routes to use patient-specific endpoints
        Wizard.loadPatientData = async function(patientId) {
            try {
                const response = await fetch(`/doctor/consultation-wizard/patient/${patientId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                });
                
                if (!response.ok) throw new Error('Failed to load patient');
                
                const patient = await response.json();
                this.patientData = patient;
                
                // Update UI
                this.updatePatientDisplay(patient);
                
                // Load AI case summary
                await this.loadAICaseSummary(patientId);
                
            } catch (error) {
                console.error('Error loading patient:', error);
                throw error;
            }
        };
        
        Wizard.loadAICaseSummary = async function(patientId) {
            try {
                const response = await fetch(`/doctor/consultation-wizard/patient/${patientId}/ai-summary`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                });
                
                if (response.ok) {
                    const analyses = await response.json();
                    if (analyses && analyses.length > 0) {
                        const latestAnalysis = analyses[0];
                        const summary = this.generateCaseSummary(latestAnalysis);
                        document.getElementById('aiCaseSummary').innerHTML = `<p>${summary}</p>`;
                    } else {
                        document.getElementById('aiCaseSummary').innerHTML = `
                            <p>No previous AI analyses found for this patient. This appears to be a new case.</p>
                        `;
                    }
                }
            } catch (error) {
                console.error('Error loading AI summary:', error);
                document.getElementById('aiCaseSummary').innerHTML = `
                    <p>Could not load AI case summary. You can proceed with the consultation.</p>
                `;
            }
        };
        
        initializeWizard(appointmentId, patientId);
    } else {
        showError('Missing patient information. Please start from the dashboard.');
    }
});
</script>
@endsection
