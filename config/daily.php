<?php

return [
    'api_key' => env('DAILY_API_KEY'),
    'domain' => env('DAILY_DOMAIN', 'medcuraai.daily.co'),
    
    // Video recording settings
    'recording_enabled' => env('DAILY_RECORDING_ENABLED', true),
    'recording_type' => env('DAILY_RECORDING_TYPE', 'cloud'), // 'cloud', 'cloud-audio-only', 'raw-tracks'
    'recording_retention_days' => env('DAILY_RECORDING_RETENTION_DAYS', 30), // Days to keep recordings
    'webhook_url' => env('DAILY_RECORDING_WEBHOOK_URL', '/webhooks/daily-recording'),
];
