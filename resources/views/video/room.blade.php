@extends('master')

@section('title', 'Video Consultation')

@push('styles')
<style>
    body { margin: 0; padding: 0; overflow: hidden; height: 100vh; }
    #video-container { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: #1a1a1a; z-index: 9999; }
    #video-container iframe { width: 100% !important; height: 100% !important; }
    
    /* Recording controls overlay */
    #recording-controls {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10000;
        display: flex;
        align-items: center;
        gap: 12px;
        background: rgba(0, 0, 0, 0.75);
        padding: 12px 16px;
        border-radius: 12px;
        backdrop-filter: blur(8px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }
    
    #recording-controls.hidden { display: none; }
    
    .recording-indicator {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #fff;
        font-size: 14px;
        font-weight: 500;
        padding-right: 12px;
        border-right: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .recording-dot {
        width: 10px;
        height: 10px;
        background: #ef4444;
        border-radius: 50%;
        animation: pulse 1.5s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.2); }
    }
    
    .record-btn, .stop-record-btn {
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }
    
    .record-btn {
        background: #ef4444;
        color: white;
    }
    
    .record-btn:hover:not(:disabled) {
        background: #dc2626;
        transform: scale(1.05);
    }
    
    .record-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .stop-record-btn {
        background: #6b7280;
        color: white;
    }
    
    .stop-record-btn:hover {
        background: #4b5563;
    }
    
    .recording-text {
        color: #ef4444;
        font-weight: 600;
    }
    
    #notification-toast {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%) translateY(100px);
        background: rgba(0, 0, 0, 0.85);
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        z-index: 10001;
        transition: transform 0.3s ease;
        font-size: 14px;
    }
    
    #notification-toast.show {
        transform: translateX(-50%) translateY(0);
    }
</style>
@endpush

@section('content')
<div id="video-container"></div>

{{-- Recording controls - only for doctors --}}
@if(auth()->user()->role === 'doctor')
<div id="recording-controls" class="hidden">
    <div id="recording-indicator" class="recording-indicator" style="display: none;">
        <div class="recording-dot"></div>
        <span class="recording-text">Recording...</span>
    </div>
    <button id="record-btn" class="record-btn" onclick="startRecording()">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
            <circle cx="12" cy="12" r="8"/>
        </svg>
        Record
    </button>
    <button id="stop-record-btn" class="stop-record-btn" style="display: none;" onclick="stopRecording()">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
            <rect x="6" y="6" width="12" height="12" rx="2"/>
        </svg>
        Stop
    </button>
</div>
@endif

<div id="notification-toast"></div>
@endsection

@push('scripts')
<script crossorigin src="https://unpkg.com/@daily-co/daily-js"></script>
<script>
    const appointmentId = {{ $appointment->id }};
    const isDoctor = {{ auth()->user()->role === 'doctor' ? 'true' : 'false' }};
    const isDebug = {{ config('app.debug') ? 'true' : 'false' }};
    const recordingsRoute = @json(route('doctor.video-recordings.index'));
    let isRecording = false;
    
    // Use Daily.co public room (no API call needed)
    const roomUrl = 'https://{{ config('daily.domain') }}/appointment-{{ $appointment->id }}';

    const callFrame = window.DailyIframe.createFrame(document.getElementById('video-container'), {
        showLeaveButton: true,
        iframeStyle: {
            position: 'fixed',
            width: '100vw',
            height: '100vh',
            border: '0',
            top: '0',
            left: '0',
            minHeight: '100vh'
        }
    });

    callFrame.join({ url: roomUrl })
        .then(() => {
            if (isDebug) console.log('✅ Successfully joined video call');
            
            // Show recording controls for doctors
            if (isDoctor) {
                document.getElementById('recording-controls').classList.remove('hidden');
                checkRecordingStatus();
            }
            
            // Listen for recording events from Daily.co
            callFrame.on('recording-started', () => {
                if (isDebug) console.log('🔴 Recording started');
                updateRecordingUI(true);
            });
            
            callFrame.on('recording-stopped', () => {
                if (isDebug) console.log('⏹️ Recording stopped');
                updateRecordingUI(false);
            });
        })
        .catch(error => {
            if (isDebug) console.error('❌ Failed to join video call:', error);
            document.getElementById('video-container').innerHTML = `
                <div style="display: flex; align-items: center; justify-content: center; min-height: 100vh; height: 100vh; background: #1a1a1a; color: white; flex-direction: column; padding: 20px; box-sizing: border-box;">
                    <h2>Unable to Join Video Call</h2>
                    <p>Network connectivity issue. Please check your internet connection.</p>
                    <p style="color: #888; font-size: 14px;">Error: ${error.errorMsg || error.message || 'Unknown error'}</p>
                    <p style="color: #888; font-size: 12px;">Room: ${roomUrl}</p>
                    <button onclick="location.reload()" style="margin-top: 20px; padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">Retry</button>
                </div>
            `;
        });

    function checkRecordingStatus() {
        fetch(`/api/appointments/${appointmentId}/video/recording/status`)
            .then(res => res.json())
            .then(data => {
                if (data.success && data.has_recording) {
                    if (data.recording.status === 'recording') {
                        updateRecordingUI(true);
                    }
                }
            })
            .catch(err => { if (isDebug) console.error('Failed to check recording status:', err); });
    }

    function startRecording() {
        const recordBtn = document.getElementById('record-btn');
        recordBtn.disabled = true;
        recordBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg> Starting...';
        
        fetch(`/api/appointments/${appointmentId}/video/recording/start`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                isRecording = true;
                updateRecordingUI(true);
                showToast('Recording started');
            } else {
                throw new Error(data.error || 'Failed to start recording');
            }
        })
        .catch(err => {
            if (isDebug) console.error('Failed to start recording:', err);
            showToast('Failed to start recording: ' + err.message, 'error');
            recordBtn.disabled = false;
            recordBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="8"/></svg> Record';
        });
    }

    function stopRecording() {
        const stopBtn = document.getElementById('stop-record-btn');
        stopBtn.disabled = true;
        stopBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg> Stopping...';
        
        fetch(`/api/appointments/${appointmentId}/video/recording/stop`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                isRecording = false;
                updateRecordingUI(false);
                showToast('Recording stopped. Processing will complete in the background.');
                
                // Redirect to recordings page after a delay
                setTimeout(() => {
                    window.location.href = recordingsRoute;
                }, 3000);
            } else {
                throw new Error(data.error || 'Failed to stop recording');
            }
        })
        .catch(err => {
            if (isDebug) console.error('Failed to stop recording:', err);
            showToast('Failed to stop recording: ' + err.message, 'error');
            stopBtn.disabled = false;
            stopBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><rect x="6" y="6" width="12" height="12" rx="2"/></svg> Stop';
        });
    }

    function updateRecordingUI(recording) {
        const recordBtn = document.getElementById('record-btn');
        const stopBtn = document.getElementById('stop-record-btn');
        const indicator = document.getElementById('recording-indicator');
        
        if (recording) {
            recordBtn.style.display = 'none';
            stopBtn.style.display = 'flex';
            stopBtn.disabled = false;
            stopBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><rect x="6" y="6" width="12" height="12" rx="2"/></svg> Stop';
            indicator.style.display = 'flex';
        } else {
            recordBtn.style.display = 'flex';
            recordBtn.disabled = false;
            recordBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="8"/></svg> Record';
            stopBtn.style.display = 'none';
            indicator.style.display = 'none';
        }
    }

    function showToast(message, type = 'info') {
        const toast = document.getElementById('notification-toast');
        toast.textContent = message;
        toast.style.background = type === 'error' 
            ? 'rgba(239, 68, 68, 0.9)' 
            : 'rgba(0, 0, 0, 0.85)';
        toast.classList.add('show');
        
        setTimeout(() => {
            toast.classList.remove('show');
        }, 4000);
    }
</script>
@endpush
