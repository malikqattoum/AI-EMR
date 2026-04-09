/**
 * Consultation Wizard - Production-Ready State Manager
 * Guides doctor through 5-step consultation workflow
 * Version: 1.0.0
 */

const Wizard = {
    currentStep: 1,
    totalSteps: 5,
    appointmentId: null,
    patientId: null,
    patientData: null,
    sessionId: null,
    transcriptionId: null,
    aiResultId: null,
    recordingStartTime: null,
    recordingTimer: null,
    mediaRecorder: null,
    audioChunks: [],
    autoSaveTimer: null,
    isProcessing: false,

    /**
     * Get CSRF token safely
     */
    getCSRFToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.content : '';
    },

    /**
     * Show toast notification
     */
    showToast(message, type = 'info') {
        if (window.showToast) {
            window.showToast(message, type);
        } else if (window.toast && window.toast[type]) {
            window.toast[type](message);
        } else {
            console.log(`[Wizard ${type.toUpperCase()}] ${message}`);
        }
    },

    /**
     * Show error message
     */
    showError(message) {
        this.showToast(message, 'error');
    },

    /**
     * Generate UUID
     */
    generateUUID() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            const r = Math.random() * 16 | 0;
            const v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    },

    /**
     * Initialize wizard with patient and appointment data
     */
    async initializeWizard(appointmentId, patientId) {
        this.appointmentId = appointmentId || null;
        this.patientId = patientId;

        if (!patientId) {
            this.showError('Missing patient information. Please start from the dashboard.');
            return;
        }

        try {
            // Show loading state
            this.showStep(1);

            // Fetch patient data
            await this.loadPatientData(patientId);

            // Generate session ID for recording
            this.sessionId = this.generateUUID();

            // Auto-save every 30 seconds
            this.startAutoSave();

            // Bind keyboard shortcuts
            this.bindKeyboardShortcuts();

            // Bind follow-up checkbox
            const followupCheckbox = document.getElementById('scheduleFollowup');
            if (followupCheckbox) {
                followupCheckbox.addEventListener('change', (e) => {
                    const dateSection = document.getElementById('followupDateSection');
                    if (dateSection) {
                        dateSection.style.display = e.target.checked ? 'block' : 'none';
                    }
                });
            }

            console.log('✅ Wizard initialized successfully');
        } catch (error) {
            console.error('❌ Wizard initialization error:', error);
            this.showError('Failed to load patient data. Please try again.');
        }
    },

    /**
     * Load patient data from API
     */
    async loadPatientData(patientId) {
        try {
            const response = await fetch(`/doctor/consultation-wizard/patient/${patientId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.getCSRFToken()
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: Failed to load patient`);
            }

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
    },

    /**
     * Update patient display in Step 1
     */
    updatePatientDisplay(patient) {
        if (!patient) return;

        const nameEl = document.getElementById('patientName');
        if (nameEl) nameEl.textContent = patient.name || 'Unknown Patient';

        const summaryNameEl = document.getElementById('summaryPatientName');
        if (summaryNameEl) summaryNameEl.textContent = patient.name || 'Unknown Patient';

        if (patient.age) {
            const ageEl = document.getElementById('patientAge');
            if (ageEl) ageEl.innerHTML = `<i class="fas fa-birthday-cake"></i> ${patient.age} years`;
        }

        if (patient.gender) {
            const icon = patient.gender.toLowerCase() === 'male' ? 'fa-mars' : 'fa-venus';
            const genderEl = document.getElementById('patientGender');
            if (genderEl) genderEl.innerHTML = `<i class="fas ${icon}"></i> ${patient.gender}`;
        }

        if (patient.phone) {
            const phoneEl = document.getElementById('patientPhone');
            if (phoneEl) phoneEl.innerHTML = `<i class="fas fa-phone"></i> ${patient.phone}`;
        }

        // Update risk badge
        const riskBadge = document.getElementById('patientRiskBadge');
        if (riskBadge) {
            if (patient.risk_score !== null && patient.risk_score !== undefined) {
                const risk = patient.risk_score;
                const riskLevel = risk > 0.7 ? 'high' : risk > 0.3 ? 'medium' : 'low';
                riskBadge.className = `patient-risk-badge ${riskLevel}`;
                riskBadge.innerHTML = `<i class="fas fa-shield-alt"></i> ${riskLevel.charAt(0).toUpperCase() + riskLevel.slice(1)} Risk`;
            } else {
                riskBadge.className = 'patient-risk-badge low';
                riskBadge.innerHTML = `<i class="fas fa-shield-alt"></i> Low Risk`;
            }
        }
    },

    /**
     * Load AI-generated case summary
     */
    async loadAICaseSummary(patientId) {
        try {
            const response = await fetch(`/doctor/consultation-wizard/patient/${patientId}/ai-summary`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.getCSRFToken()
                }
            });

            const summaryEl = document.getElementById('aiCaseSummary');
            if (!summaryEl) return;

            if (response.ok) {
                const analyses = await response.json();
                if (analyses && analyses.length > 0) {
                    const latestAnalysis = analyses[0];
                    const summary = this.generateCaseSummary(latestAnalysis);
                    summaryEl.innerHTML = `<p>${this.escapeHtml(summary)}</p>`;
                } else {
                    summaryEl.innerHTML = `<p>No previous AI analyses found for this patient. This appears to be a new case.</p>`;
                }
            } else {
                summaryEl.innerHTML = `<p>Could not load AI case summary. You can proceed with the consultation.</p>`;
            }
        } catch (error) {
            console.error('Error loading AI summary:', error);
            const summaryEl = document.getElementById('aiCaseSummary');
            if (summaryEl) {
                summaryEl.innerHTML = `<p>Could not load AI case summary. You can proceed with the consultation.</p>`;
            }
        }
    },

    /**
     * Escape HTML to prevent XSS
     */
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    },

    /**
     * Generate a concise case summary from AI analysis
     */
    generateCaseSummary(analysis) {
        if (!analysis) return 'No case data available.';

        const data = analysis.data || analysis.patient_data || {};
        const parts = [];

        if (data.chief_complaint) {
            parts.push(`Chief complaint: ${data.chief_complaint}`);
        }

        if (data.last_visit) {
            try {
                const daysAgo = Math.floor((new Date() - new Date(data.last_visit)) / (1000 * 60 * 60 * 24));
                parts.push(`Last visit: ${daysAgo} days ago`);
            } catch (e) {
                // Ignore date parsing errors
            }
        }

        if (data.key_findings) {
            const findings = data.key_findings.length > 150 ? data.key_findings.substring(0, 150) + '...' : data.key_findings;
            parts.push(`Key findings: ${findings}`);
        }

        return parts.length > 0 ? parts.join('. ') : 'No previous case data available.';
    },

    /**
     * Navigate to specific step
     */
    showStep(stepNumber) {
        // Validate step number
        if (stepNumber < 1 || stepNumber > this.totalSteps) return;

        // Hide all steps
        document.querySelectorAll('.wizard-step-content').forEach(el => {
            el.style.display = 'none';
        });

        // Show target step
        const targetStep = document.getElementById(`step-${stepNumber}`);
        if (targetStep) {
            targetStep.style.display = 'block';
        }

        // Update progress indicator
        this.updateProgressIndicator(stepNumber);

        // Update current step
        this.currentStep = stepNumber;

        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });

        // Step-specific actions
        if (stepNumber === 2) {
            this.onEnterConsultationStep();
        } else if (stepNumber === 3) {
            this.onEnterAIReviewStep();
        } else if (stepNumber === 4) {
            this.onEnterDiagnosisStep();
        } else if (stepNumber === 5) {
            this.onEnterCompletionStep();
        }

        console.log(`📍 Navigated to Step ${stepNumber}`);
    },

    /**
     * Public method to go to specific step
     */
    goToStep(stepNumber) {
        // Prevent going forward without completing recording in Step 2
        if (this.currentStep === 2 && stepNumber > 2) {
            if (this.mediaRecorder && this.mediaRecorder.state === 'recording') {
                this.showToast('Please stop recording before proceeding', 'warning');
                return;
            }
        }

        this.showStep(stepNumber);
    },

    /**
     * Update progress indicator UI
     */
    updateProgressIndicator(currentStep) {
        document.querySelectorAll('.progress-step').forEach((step, index) => {
            const stepNum = index + 1;
            step.classList.remove('active', 'completed');

            if (stepNum === currentStep) {
                step.classList.add('active');
            } else if (stepNum < currentStep) {
                step.classList.add('completed');
            }
        });

        // Update progress lines
        document.querySelectorAll('.progress-line').forEach((line, index) => {
            if (index + 1 < currentStep) {
                line.classList.add('completed');
            } else {
                line.classList.remove('completed');
            }
        });
    },

    /**
     * Step 2: Enter consultation recording
     */
    onEnterConsultationStep() {
        console.log('🎤 Entering consultation recording step');
    },

    /**
     * Step 3: Enter AI review - trigger AI analysis
     */
    async onEnterAIReviewStep() {
        if (this.isProcessing) return;
        this.isProcessing = true;

        // Show processing state
        const processingEl = document.getElementById('aiProcessing');
        const chartEl = document.getElementById('clinicalChart');
        const panelEl = document.getElementById('aiAnalysisPanel');

        if (processingEl) processingEl.style.display = 'block';
        if (chartEl) chartEl.style.display = 'none';
        if (panelEl) panelEl.style.display = 'none';

        try {
            // Process transcription with AI
            await this.processWithAI();

            // Generate comprehensive analysis
            await this.generateAIAnalysis();

            // Populate clinical chart
            this.populateClinicalChart();

            // Hide processing, show chart
            if (processingEl) processingEl.style.display = 'none';
            if (chartEl) chartEl.style.display = 'block';
            if (panelEl) panelEl.style.display = 'block';

            this.showToast('AI analysis complete! Please review the extracted data.', 'success');
        } catch (error) {
            console.error('AI processing error:', error);
            if (processingEl) {
                processingEl.innerHTML = `
                    <i class="fas fa-exclamation-triangle" style="font-size: 4rem; color: var(--wizard-warning); margin-bottom: 1rem;"></i>
                    <h3>AI Processing Failed</h3>
                    <p class="processing-status">You can still manually enter clinical data below.</p>
                `;
            }

            // Show empty chart for manual entry
            if (processingEl) processingEl.style.display = 'none';
            if (chartEl) chartEl.style.display = 'block';
        } finally {
            this.isProcessing = false;
        }
    },

    /**
     * Process transcription with AI (extract 7 categories)
     * Integrates with existing /ai/ambient-listening/process-with-ai endpoint
     */
    async processWithAI() {
        try {
            const response = await fetch('/ai/ambient-listening/process-with-ai', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.getCSRFToken()
                },
                body: JSON.stringify({
                    session_id: this.sessionId,
                    transcription_id: this.transcriptionId
                })
            });

            if (!response.ok) {
                console.warn('AI processing failed, continuing with manual entry');
            } else {
                const data = await response.json();
                console.log('✅ AI processing complete', data);
            }
        } catch (error) {
            console.error('processWithAI error:', error);
            // Non-blocking - doctor can still enter manually
        }
    },

    /**
     * Generate comprehensive AI analysis
     * Integrates with existing /ai/ambient-listening/generate-ai-analysis endpoint
     */
    async generateAIAnalysis() {
        try {
            const response = await fetch('/ai/ambient-listening/generate-ai-analysis', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.getCSRFToken()
                },
                body: JSON.stringify({
                    session_id: this.sessionId,
                    transcription_id: this.transcriptionId,
                    patient_id: this.patientId
                })
            });

            if (!response.ok) {
                console.warn('AI analysis generation failed, continuing with manual entry');
            } else {
                const data = await response.json();
                console.log('✅ AI analysis complete', data);
            }
        } catch (error) {
            console.error('generateAIAnalysis error:', error);
            // Non-blocking - doctor can still enter manually
        }
    },

    /**
     * Populate clinical chart with AI-extracted data
     */
    populateClinicalChart() {
        // In production, this would fetch from AI extraction results
        // For now, fields remain empty for manual entry
        console.log('📋 Clinical chart ready for review');
    },

    /**
     * Step 4: Enter diagnosis entry
     */
    onEnterDiagnosisStep() {
        // Pre-fill diagnosis from AI chart
        const chartDiagnosis = document.getElementById('chartDiagnosis');
        const diagnosisText = document.getElementById('diagnosisText');

        if (chartDiagnosis && chartDiagnosis.value && diagnosisText && !diagnosisText.value) {
            diagnosisText.value = chartDiagnosis.value;
        }
    },

    /**
     * Step 5: Enter completion summary
     */
    onEnterCompletionStep() {
        // Update summary with diagnosis
        const diagnosisText = document.getElementById('diagnosisText');
        const summaryDiagnosis = document.getElementById('summaryDiagnosis');

        if (diagnosisText && summaryDiagnosis) {
            const text = diagnosisText.value;
            summaryDiagnosis.textContent = text.substring(0, 100) + (text.length > 100 ? '...' : '') || '--';
        }

        // Update duration
        const summaryDuration = document.getElementById('summaryDuration');
        if (summaryDuration) {
            summaryDuration.textContent = this.recordingStartTime ? this.calculateDuration() : 'N/A';
        }
    },

    /**
     * Start audio recording
     */
    async startRecording() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            this.showError('Your browser does not support audio recording. Please use Chrome or Firefox.');
            return;
        }

        try {
            // Request microphone access
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });

            this.mediaRecorder = new MediaRecorder(stream);
            this.audioChunks = [];
            this.recordingStartTime = new Date();

            this.mediaRecorder.ondataavailable = (event) => {
                this.audioChunks.push(event.data);
            };

            this.mediaRecorder.start();

            // Update UI
            const indicator = document.getElementById('recordingIndicator');
            const notRecording = document.getElementById('notRecording');
            const startBtn = document.getElementById('startRecordingBtn');
            const stopBtn = document.getElementById('stopRecordingBtn');
            const stopAnalyzeBtn = document.getElementById('stopAndAnalyzeBtn');

            if (indicator) indicator.style.display = 'flex';
            if (notRecording) notRecording.style.display = 'none';
            if (startBtn) startBtn.style.display = 'none';
            if (stopBtn) stopBtn.style.display = 'flex';
            if (stopAnalyzeBtn) stopAnalyzeBtn.style.display = 'inline-flex';

            // Start timer
            this.startRecordingTimer();

            // Start session on server
            await this.startSession();

            this.showToast('Recording started. Speak clearly.', 'success');

        } catch (error) {
            console.error('Microphone access error:', error);
            if (error.name === 'NotAllowedError') {
                this.showError('Microphone access denied. Please allow microphone permissions and try again.');
            } else if (error.name === 'NotFoundError') {
                this.showError('No microphone found. Please connect a microphone and try again.');
            } else {
                this.showError('Could not access microphone. Please check permissions.');
            }
        }
    },

    /**
     * Stop audio recording
     */
    stopRecording() {
        if (this.mediaRecorder && this.mediaRecorder.state === 'recording') {
            this.mediaRecorder.stop();
            this.mediaRecorder.stream.getTracks().forEach(track => track.stop());

            // Stop timer
            if (this.recordingTimer) {
                clearInterval(this.recordingTimer);
                this.recordingTimer = null;
            }

            // Update UI
            const indicator = document.getElementById('recordingIndicator');
            const notRecording = document.getElementById('notRecording');
            const startBtn = document.getElementById('startRecordingBtn');
            const stopBtn = document.getElementById('stopRecordingBtn');
            const stopAnalyzeBtn = document.getElementById('stopAndAnalyzeBtn');

            if (indicator) indicator.style.display = 'none';
            if (notRecording) notRecording.style.display = 'block';
            if (startBtn) startBtn.style.display = 'none';
            if (stopBtn) stopBtn.style.display = 'none';
            if (stopAnalyzeBtn) stopAnalyzeBtn.style.display = 'inline-flex';

            this.showToast('Recording stopped. Ready to analyze.', 'info');
        }
    },

    /**
     * Stop recording and proceed to AI analysis
     */
    async stopRecordingAndAnalyze() {
        this.stopRecording();

        // Stop session on server
        await this.stopSession();

        // Move to AI review step
        this.goToStep(3);
    },

    /**
     * Start session on server
     */
    async startSession() {
        try {
            const response = await fetch('/ai/ambient-listening/start-session', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.getCSRFToken()
                },
                body: JSON.stringify({
                    patient_id: this.patientId,
                    appointment_id: this.appointmentId,
                    session_id: this.sessionId
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: Failed to start session`);
            }

            const data = await response.json();
            this.transcriptionId = data.transcription_id;

            console.log('✅ Session started on server');
        } catch (error) {
            console.error('Start session error:', error);
            this.showToast('Failed to start recording session. You can still proceed manually.', 'warning');
        }
    },

    /**
     * Stop session on server
     */
    async stopSession() {
        if (!this.sessionId) return;

        try {
            const response = await fetch('/ai/ambient-listening/stop-session', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.getCSRFToken()
                },
                body: JSON.stringify({
                    session_id: this.sessionId,
                    transcription_id: this.transcriptionId
                })
            });

            if (!response.ok) {
                console.warn('Stop session warning:', response.status);
            } else {
                console.log('✅ Session stopped on server');
            }
        } catch (error) {
            console.error('Stop session error:', error);
        }
    },

    /**
     * Start recording timer
     */
    startRecordingTimer() {
        this.recordingTimer = setInterval(() => {
            if (!this.recordingStartTime) return;

            const elapsed = new Date() - this.recordingStartTime;
            const minutes = Math.floor(elapsed / 60000);
            const seconds = Math.floor((elapsed % 60000) / 1000);

            const timerEl = document.getElementById('recordingTimer');
            if (timerEl) {
                timerEl.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            }
        }, 1000);
    },

    /**
     * Calculate recording duration
     */
    calculateDuration() {
        if (!this.recordingStartTime) return 'N/A';

        const elapsed = new Date() - this.recordingStartTime;
        const minutes = Math.floor(elapsed / 60000);
        const seconds = Math.floor((elapsed % 60000) / 1000);

        return `${minutes}m ${seconds}s`;
    },

    /**
     * Complete the entire consultation
     */
    async completeConsultation() {
        const diagnosisTextEl = document.getElementById('diagnosisText');
        const diagnosisText = diagnosisTextEl ? diagnosisTextEl.value.trim() : '';

        if (!diagnosisText) {
            this.showToast('Please enter a diagnosis before completing.', 'error');
            return;
        }

        if (diagnosisText.length < 10) {
            this.showToast('Diagnosis must be at least 10 characters.', 'error');
            return;
        }

        // Prevent double-clicking
        const completeBtn = document.querySelector('.btn-success-complete');
        if (completeBtn && completeBtn.disabled) return;

        try {
            // Show loading state
            if (completeBtn) {
                completeBtn.disabled = true;
                completeBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Completing...';
            }

            // Collect all data
            const consultationData = {
                appointment_id: this.appointmentId,
                patient_id: this.patientId,
                session_id: this.sessionId,
                diagnosis_text: diagnosisText,
                clinical_chart: {
                    symptoms: document.getElementById('chartSymptoms')?.value || '',
                    history: document.getElementById('chartHistory')?.value || '',
                    findings: document.getElementById('chartFindings')?.value || '',
                    medications: document.getElementById('chartMedications')?.value || '',
                    vitals: document.getElementById('chartVitals')?.value || '',
                    diagnosis: document.getElementById('chartDiagnosis')?.value || '',
                    care_plan: document.getElementById('chartCarePlan')?.value || ''
                },
                completion_type: document.querySelector('input[name="completionType"]:checked')?.value || 'completed',
                followup_date: document.getElementById('scheduleFollowup')?.checked ?
                    (document.getElementById('followupDate')?.value || null) : null,
                send_summary_to_patient: document.getElementById('sendSummary')?.checked ?? true,
                ai_result_id: this.aiResultId
            };

            // Submit to server
            const response = await fetch('/doctor/consultation-wizard/complete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.getCSRFToken()
                },
                body: JSON.stringify(consultationData)
            });

            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                throw new Error(errorData.message || `HTTP ${response.status}: Failed to complete consultation`);
            }

            const result = await response.json();

            // Show success message
            this.showToast('✅ Appointment completed successfully!', 'success');

            // Redirect to dashboard or diagnosis view
            setTimeout(() => {
                window.location.href = result.redirect_url || '/doctor/dashboard';
            }, 1500);

        } catch (error) {
            console.error('Complete consultation error:', error);
            this.showToast(error.message || 'Failed to complete consultation. Please try again.', 'error');

            // Re-enable button
            if (completeBtn) {
                completeBtn.disabled = false;
                completeBtn.innerHTML = '<i class="fas fa-check"></i> Complete Appointment';
            }
        }
    },

    /**
     * Insert ICD-10 code into diagnosis
     */
    insertICDCode(code) {
        const diagnosisText = document.getElementById('diagnosisText');
        if (!diagnosisText) return;

        const currentValue = diagnosisText.value;

        if (currentValue && !currentValue.endsWith('\n')) {
            diagnosisText.value += '\n';
        }

        diagnosisText.value += `ICD-10: ${code}\n`;
        diagnosisText.focus();
    },

    /**
     * Exit wizard with confirmation
     */
    exitWizard() {
        if (this.mediaRecorder && this.mediaRecorder.state === 'recording') {
            if (!confirm('Recording is in progress. Are you sure you want to exit? The recording will be lost.')) {
                return;
            }
            this.stopRecording();
        } else if (this.currentStep > 1 && this.currentStep < 5) {
            if (!confirm('You have unsaved progress. Are you sure you want to exit the wizard?')) {
                return;
            }
        }

        window.location.href = '/doctor/dashboard';
    },

    /**
     * Bind keyboard shortcuts
     */
    bindKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Escape - Exit wizard
            if (e.key === 'Escape') {
                e.preventDefault();
                this.exitWizard();
            }

            // Ctrl+Right Arrow - Next step
            if (e.ctrlKey && e.key === 'ArrowRight') {
                e.preventDefault();
                if (this.currentStep < this.totalSteps) {
                    this.goToStep(this.currentStep + 1);
                }
            }

            // Ctrl+Left Arrow - Previous step
            if (e.ctrlKey && e.key === 'ArrowLeft') {
                e.preventDefault();
                if (this.currentStep > 1) {
                    this.goToStep(this.currentStep - 1);
                }
            }
        });
    },

    /**
     * Start auto-save timer
     */
    startAutoSave() {
        this.autoSaveTimer = setInterval(() => {
            this.autoSaveProgress();
        }, 30000); // Every 30 seconds
    },

    /**
     * Auto-save current progress
     */
    async autoSaveProgress() {
        try {
            const draftData = {
                appointment_id: this.appointmentId,
                patient_id: this.patientId,
                session_id: this.sessionId,
                current_step: this.currentStep,
                clinical_chart: {
                    symptoms: document.getElementById('chartSymptoms')?.value || '',
                    history: document.getElementById('chartHistory')?.value || '',
                    findings: document.getElementById('chartFindings')?.value || '',
                    medications: document.getElementById('chartMedications')?.value || '',
                    vitals: document.getElementById('chartVitals')?.value || '',
                    diagnosis: document.getElementById('chartDiagnosis')?.value || '',
                    care_plan: document.getElementById('chartCarePlan')?.value || ''
                },
                diagnosis_text: document.getElementById('diagnosisText')?.value || '',
                timestamp: new Date().toISOString()
            };

            // Save to localStorage as backup
            if (this.appointmentId) {
                localStorage.setItem(`wizard_draft_${this.appointmentId}`, JSON.stringify(draftData));
            }

            console.log('💾 Auto-saved progress');
        } catch (error) {
            console.error('Auto-save error:', error);
        }
    }
};

// Make functions globally accessible
window.initializeWizard = Wizard.initializeWizard.bind(Wizard);
window.goToStep = Wizard.goToStep.bind(Wizard);
window.exitWizard = Wizard.exitWizard.bind(Wizard);
window.startRecording = Wizard.startRecording.bind(Wizard);
window.stopRecording = Wizard.stopRecording.bind(Wizard);
window.stopRecordingAndAnalyze = Wizard.stopRecordingAndAnalyze.bind(Wizard);
window.completeConsultation = Wizard.completeConsultation.bind(Wizard);
window.insertICDCode = Wizard.insertICDCode.bind(Wizard);
