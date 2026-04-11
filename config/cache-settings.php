<?php

/**
 * Cache configuration for application-specific caching.
 * 
 * Centralizes all cache TTL (Time To Live) values and cache key patterns
 * that were previously hardcoded throughout the application.
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Dashboard Cache Settings
    |--------------------------------------------------------------------------
    |
    | Cache TTL values for dashboard statistics and data.
    |
    */

    'dashboard' => [
        'stats_ttl' => 300, // 5 minutes
        'appointments_ttl' => 120, // 2 minutes
        'patients_ttl' => 600, // 10 minutes
    ],

    /*
    |--------------------------------------------------------------------------
    | Appointment Cache Settings
    |--------------------------------------------------------------------------
    |
    | Cache settings for appointment-related data.
    |
    */

    'appointments' => [
        'available_slots_ttl' => 1800, // 30 minutes
        'calendar_events_ttl' => 300, // 5 minutes
        'patient_search_ttl' => 600, // 10 minutes
        'risk_predictions_success_ttl' => 3600, // 1 hour
        'risk_predictions_failure_ttl' => 300, // 5 minutes
    ],

    /*
    |--------------------------------------------------------------------------
    | Patient Cache Settings
    |--------------------------------------------------------------------------
    |
    | Cache settings for patient-related data.
    |
    */

    'patients' => [
        'profile_ttl' => 900, // 15 minutes
        'medical_records_ttl' => 600, // 10 minutes
        'appointments_history_ttl' => 300, // 5 minutes
    ],

    /*
    |--------------------------------------------------------------------------
    | Doctor Cache Settings
    |--------------------------------------------------------------------------
    |
    | Cache settings for doctor-related data.
    |
    */

    'doctors' => [
        'profile_ttl' => 1800, // 30 minutes
        'availability_ttl' => 900, // 15 minutes
        'statistics_ttl' => 600, // 10 minutes
    ],

    /*
    |--------------------------------------------------------------------------
    | Claims Cache Settings
    |--------------------------------------------------------------------------
    |
    | Cache settings for insurance claims data.
    |
    */

    'claims' => [
        'eligibility_check_ttl' => 3600, // 1 hour
        'denial_prediction_ttl' => 1800, // 30 minutes
        'statistics_ttl' => 600, // 10 minutes
    ],

    /*
    |--------------------------------------------------------------------------
    | AI/ML Cache Settings
    |--------------------------------------------------------------------------
    |
    | Cache settings for AI predictions and suggestions.
    |
    */

    'ai' => [
        'prescription_suggestions_ttl' => 3600, // 1 hour
        'clinical_notes_ttl' => 1800, // 30 minutes
        'risk_analysis_ttl' => 3600, // 1 hour
        'transcription_ttl' => 7200, // 2 hours
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Cache Settings
    |--------------------------------------------------------------------------
    |
    | Cache settings for notification rate limiting and batching.
    |
    */

    'notifications' => [
        'rate_limit_ttl' => 3600, // 1 hour
        'batch_window_ttl' => 300, // 5 minutes
        'digest_ttl' => 86400, // 24 hours
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Cache Settings
    |--------------------------------------------------------------------------
    |
    | Cache settings for user session data.
    |
    */

    'session' => [
        'user_preferences_ttl' => 3600, // 1 hour
        'navigation_state_ttl' => 1800, // 30 minutes
        'form_data_ttl' => 900, // 15 minutes
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Key Patterns
    |--------------------------------------------------------------------------
    |
    | Standardized cache key patterns for consistency.
    | Usage: sprintf(config('cache.patterns.appointment_slots'), $doctorId, $date)
    |
    */

    'patterns' => [
        'appointment_slots' => 'appointment:slots:%d:%s', // doctor_id, date
        'patient_search' => 'patient:search:%s', // query
        'doctor_availability' => 'doctor:availability:%d:%s', // doctor_id, date
        'risk_predictions' => 'risk:predictions:%d:%d', // patient_id, appointment_id
        'claims_eligibility' => 'claims:eligibility:%d', // claim_id
        'user_session' => 'user:session:%d:%s', // user_id, session_key
        'dashboard_stats' => 'dashboard:stats:%d:%s', // user_id, role
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Tags
    |--------------------------------------------------------------------------
    |
    | Cache tags for grouped invalidation (requires Redis/Memcached).
    |
    */

    'tags' => [
        'appointments' => 'appointments',
        'patients' => 'patients',
        'doctors' => 'doctors',
        'claims' => 'claims',
        'statistics' => 'statistics',
        'user_data' => 'user_data',
    ],
];
