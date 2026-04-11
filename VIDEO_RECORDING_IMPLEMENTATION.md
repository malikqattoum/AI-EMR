# Video Call Recording & AI Analysis - Implementation Summary

## Overview
This implementation adds video call recording capabilities to the existing Daily.co video appointment system, along with AI-powered summarization and clinical analysis (similar to the ambient listening feature).

---

## Features Implemented

### 1. Video Recording
- **Cloud Recording**: Uses Daily.co's built-in cloud recording (MP4 video + audio)
- **Audio-Only Recording**: Simultaneous audio-only recording for faster transcription
- **Recording Controls**: Doctors can start/stop recordings during video calls
- **Recording Indicator**: Visual pulsing indicator when recording is active
- **Background Processing**: Recordings are processed asynchronously via queued jobs

### 2. AI Summarization & Analysis
- **AI Summary**: Concise clinical summary of the consultation (chief complaint, history, findings, assessment, plan)
- **AI Clinical Analysis**: Comprehensive analysis including:
  - Clinical summary
  - Physical examination findings
  - Assessment with diagnoses
  - Investigations ordered
  - Treatment plan
  - Risk considerations
  - Evidence-based recommendations
- **Medical Data Extraction**: Structured extraction of symptoms, history, findings, medications, vitals, diagnosis, care plan
- **Speaker Diarization**: Identifies doctor vs patient speakers in transcription

### 3. Recording Management
- **List View**: Filterable table of all recordings with status, duration, AI analysis status
- **Playback Page**: Embedded video player with metadata display
- **AI Generation**: On-demand AI summary and analysis generation via button clicks
- **Download**: Direct download link to Daily.co recording URL
- **Delete**: Remove recordings with confirmation

---

## Files Created/Modified

### Database & Models
1. **`database/migrations/2026_04_11_000001_create_video_recordings_table.php`** (NEW)
   - Creates `video_recordings` table with fields for recording metadata, transcription, AI results
   
2. **`app/Models/VideoRecording.php`** (NEW)
   - Eloquent model with relationships, scopes, and helper methods
   
3. **`app/Models/Appointment.php`** (MODIFIED)
   - Added `videoRecording()` relationship

### Services
4. **`app/Services/DailyService.php`** (MODIFIED)
   - Added: `startRecording()`, `stopRecording()`, `getRecording()`, `listRecordings()`, `updateRecording()`
   
5. **`app/Services/VideoTranscriptionService.php`** (NEW)
   - Handles audio transcription via AssemblyAI
   - Medical data extraction using GPT-4o
   - Speaker diarization formatting
   - Specialty-specific medical terminology boosting
   
6. **`app/Services/VideoAIAnalysisService.php`** (NEW)
   - `generateSummary()`: Creates concise clinical summary
   - `generateAnalysis()`: Comprehensive clinical analysis with AiAssistantResult creation
   - Reuses same prompt patterns as ambient listening

### Controllers
7. **`app/Http/Controllers/VideoCallController.php`** (MODIFIED)
   - Added: `startRecording()`, `stopRecording()`, `getRecordingStatus()`
   
8. **`app/Http/Controllers/VideoRecordingWebhookController.php`** (NEW)
   - Handles Daily.co `recording.ready` and `recording.error` webhooks
   - Updates VideoRecording status and triggers background processing
   
9. **`app/Http/Controllers/VideoRecordingController.php`** (NEW)
   - `index()`: List recordings with filters
   - `show()`: Playback page with AI results
   - `playback()`: Get recording URL API
   - `generateSummary()`: AI summary generation endpoint
   - `generateAnalysis()`: AI analysis endpoint
   - `download()`: Redirect to Daily.co URL
   - `destroy()`: Delete recording

### Jobs
10. **`app/Jobs/ProcessVideoRecordingTranscription.php`** (NEW)
    - Queued job for background transcription processing
    - Timeout: 10 minutes, Retries: 2
    - Calls VideoTranscriptionService

### Views
11. **`resources/views/doctor/video-recordings/index.blade.php`** (NEW)
    - Recordings list table with filters (status, date range)
    - Status badges with icons
    - AI analysis status indicator
    - Pagination
    
12. **`resources/views/doctor/video-recordings/show.blade.php`** (NEW)
    - Video player with controls
    - Recording metadata display
    - AI summary section (with generate button)
    - Full transcription display
    - AI clinical analysis (with generate button)
    - Action buttons (back, appointment, patient, delete)
    
13. **`resources/views/video/room.blade.php`** (MODIFIED)
    - Added recording controls overlay (record/stop buttons)
    - Recording indicator (pulsing red dot + "Recording..." text)
    - Toast notifications
    - JavaScript functions for start/stop/status check
    - Daily.co event listeners

14. **`resources/views/layouts/doctor.blade.php`** (MODIFIED)
    - Added "Video Recordings" link to sidebar navigation

### Routes
15. **`routes/api.php`** (MODIFIED)
    - Added recording routes under `/api/appointments/{appointment}/video/recording/`:
      - `POST /start` - Start recording
      - `POST /stop` - Stop recording
      - `GET /status` - Check recording status

16. **`routes/web.php`** (MODIFIED)
    - Added doctor routes under `/doctor/video-recordings/`:
      - `GET /` - List recordings
      - `GET /{recording}` - Show playback page
      - `GET /{recording}/playback` - Get playback URL
      - `GET /{recording}/download` - Download recording
      - `POST /{recording}/generate-summary` - Generate AI summary
      - `POST /{recording}/generate-analysis` - Generate AI analysis
      - `DELETE /{recording}` - Delete recording
    - Added webhook route:
      - `POST /webhooks/daily-recording` - Daily.co webhook handler (no auth)

### Configuration
17. **`config/daily.php`** (MODIFIED)
    - Added: `recording_enabled`, `recording_type`, `recording_retention_days`, `webhook_url`
    
18. **`.env.example`** (MODIFIED)
    - Added recording configuration variables

---

## How It Works

### Recording Flow
```
1. Doctor starts video call with patient
2. Doctor clicks "Record" button in video room
3. Backend calls Daily.co API to start cloud recording (video + audio)
4. Backend also starts audio-only recording for transcription
5. VideoRecording record created in database
6. Doctor sees recording indicator (red pulsing dot)
7. Doctor clicks "Stop" when done
8. Daily.co processes recording in background
9. Daily.co sends webhook to /webhooks/daily-recording
10. Webhook handler updates VideoRecording status to "ready"
11. Background job dispatched for transcription
```

### Transcription & AI Flow
```
1. ProcessVideoRecordingTranscription job runs
2. Downloads audio from Daily.co recording URL
3. Sends to AssemblyAI for transcription with speaker labels
4. Formats transcript with speaker identification
5. Extracts medical data using GPT-4o (symptoms, history, etc.)
6. Stores transcription and extracted data in VideoRecording
7. Doctor can then click "Generate AI Summary" or "Generate AI Analysis"
8. AI services process the transcription and store results
9. Results displayed in playback page
```

---

## Configuration

### Environment Variables
Add to your `.env` file:

```env
# Daily.co Video Recording
DAILY_RECORDING_ENABLED=true
DAILY_RECORDING_TYPE=cloud
DAILY_RECORDING_RETENTION_DAYS=30
DAILY_RECORDING_WEBHOOK_URL=/webhooks/daily-recording
```

### Daily.co Webhook Setup
You need to configure the webhook in your Daily.co dashboard:

1. Go to https://dashboard.daily.co/
2. Navigate to Developers → Webhooks
3. Add webhook endpoint: `https://your-domain.com/webhooks/daily-recording`
4. Select events: `recording.ready`, `recording.error`

### AssemblyAI Configuration
Ensure AssemblyAI is configured in your `.env`:

```env
ASSEMBLYAI_API_KEY=your_assemblyai_api_key
```

### Queue Worker
Make sure your queue worker is running for background processing:

```bash
php artisan queue:work --queue=default --timeout=600
```

---

## Usage

### During Video Call
1. Doctor joins video call with patient
2. Recording controls appear in top-right corner (doctors only)
3. Click "Record" button to start recording
4. Recording indicator shows active recording status
5. Click "Stop" when consultation is complete
6. Automatic redirect to recordings list after stopping

### Reviewing Recordings
1. Navigate to **Doctor → Video Recordings** in sidebar
2. Filter by status, date range
3. Click "View" on a ready recording
4. Watch recording playback
5. Click "Generate AI Summary" for clinical summary
6. Click "Generate AI Analysis" for comprehensive analysis
7. Download recording if needed

---

## API Endpoints

### Recording Control (API - requires auth)
- `POST /api/appointments/{id}/video/recording/start` - Start recording
- `POST /api/appointments/{id}/video/recording/stop` - Stop recording  
- `GET /api/appointments/{id}/video/recording/status` - Check status

### Recording Management (Web - requires doctor auth)
- `GET /doctor/video-recordings` - List recordings
- `GET /doctor/video-recordings/{id}` - Show playback page
- `GET /doctor/video-recordings/{id}/playback` - Get playback URL
- `GET /doctor/video-recordings/{id}/download` - Download recording
- `POST /doctor/video-recordings/{id}/generate-summary` - Generate AI summary
- `POST /doctor/video-recordings/{id}/generate-analysis` - Generate AI analysis
- `DELETE /doctor/video-recordings/{id}` - Delete recording

### Webhook (No auth required)
- `POST /webhooks/daily-recording` - Daily.co webhook handler

---

## Database Schema

### `video_recordings` Table
```
- id (bigint, primary key)
- appointment_id (FK -> appointments.id)
- doctor_id (FK -> users.id)
- patient_id (FK -> users.id, nullable)
- recording_id (string, unique) - Daily.co recording ID
- recording_url (string) - MP4 download URL
- audio_recording_url (string) - Audio-only URL
- duration (int) - seconds
- file_size (bigint) - bytes
- resolution (string) - e.g., '1280x720'
- status (string) - recording, processing, transcribing, ai_processing, ready, failed
- started_at (timestamp)
- ended_at (timestamp)
- transcription (longText)
- extracted_data (JSON) - symptoms, history, findings, medications, vitals, diagnosis, care_plan
- ai_summary (longText)
- ai_analysis (longText)
- structured_chart (JSON)
- ai_assistant_result_id (FK -> ai_assistant_results.id, nullable)
- timestamps
```

---

## Security & Permissions

- **Recording Control**: Only the appointment's doctor can start/stop recordings
- **Viewing Recordings**: Only the doctor who conducted the consultation
- **Webhook**: No auth required (Daily.co server-to-server)
- **Patient Privacy**: Recordings linked to appointments with proper authorization checks

---

## Testing

### 1. Run Migration
```bash
php artisan migrate
```

### 2. Test Recording Flow
1. Create a video appointment
2. Join video call as doctor
3. Click "Record" button
4. Verify recording indicator appears
5. Click "Stop"
6. Check `video_recordings` table for new record
7. Wait for webhook and background processing

### 3. Test AI Analysis
1. Navigate to video recording show page
2. Click "Generate AI Summary"
3. Verify summary appears
4. Click "Generate AI Analysis"
5. Verify comprehensive analysis appears

### 4. Check Queue Worker
```bash
php artisan queue:listen
```

---

## Future Enhancements

1. **Automatic Recording**: Auto-start recording for all video calls
2. **Recording Templates**: Pre-configured recording settings per doctor
3. **Analytics**: Recording usage statistics and storage metrics
4. **Export**: Export transcriptions and AI results to PDF/EMR
5. **Sharing**: Share recordings with other providers
6. **Storage**: Local caching of recordings for faster playback
7. **Search**: Full-text search across transcriptions
8. **Tags**: Custom tagging and categorization of recordings

---

## Troubleshooting

### Recording doesn't start
- Check `DAILY_API_KEY` is valid
- Verify Daily.co plan supports cloud recording
- Check browser console for errors

### Webhook not received
- Verify webhook URL is accessible (not localhost)
- Check webhook configuration in Daily.co dashboard
- Review Laravel logs for webhook handler errors

### Transcription fails
- Verify `ASSEMBLYAI_API_KEY` is configured
- Check audio URL is accessible
- Review queue worker logs for errors

### AI analysis not generating
- Verify `OPENAI_API_KEY` is configured
- Check transcription exists before generating analysis
- Review OpenAI API usage limits

---

**Built for MedcuraAI - Extending ambient listening AI capabilities to video consultations**
