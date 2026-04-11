<?php

/**
 * Appointment configuration settings.
 * 
 * Centralizes all appointment-related configuration values
 * that were previously hardcoded throughout the application.
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Appointment Duration Defaults
    |--------------------------------------------------------------------------
    |
    | Default duration values for different appointment types in minutes.
    |
    */

    'default_durations' => [
        'in_person' => 30,
        'video_call' => 30,
        'phone_call' => 20,
        'follow_up' => 20,
        'consultation' => 45,
    ],

    'min_duration' => 15,
    'max_duration' => 240,

    /*
    |--------------------------------------------------------------------------
    | Appointment Status Settings
    |--------------------------------------------------------------------------
    |
    | Valid status values and their transitions.
    |
    */

    'statuses' => [
        'pending',
        'confirmed',
        'check_in',
        'in_progress',
        'completed',
        'cancelled',
        'no_show',
    ],

    'status_transitions' => [
        'pending' => ['confirmed', 'cancelled'],
        'confirmed' => ['check_in', 'cancelled', 'no_show'],
        'check_in' => ['in_progress', 'no_show'],
        'in_progress' => ['completed', 'no_show'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Appointment Colors
    |--------------------------------------------------------------------------
    |
    | Display colors for different appointment statuses.
    |
    */

    'status_colors' => [
        'pending' => '#f59e0b',
        'confirmed' => '#10b981',
        'check_in' => '#3b82f6',
        'in_progress' => '#8b5cf6',
        'completed' => '#10b981',
        'cancelled' => '#ef4444',
        'no_show' => '#6b7280',
    ],

    /*
    |--------------------------------------------------------------------------
    | Scheduling Settings
    |--------------------------------------------------------------------------
    |
    | Settings related to appointment scheduling.
    |
    */

    'scheduling' => [
        'advance_booking_days' => 30,
        'cancellation_hours' => 24,
        'working_hours_start' => '09:00',
        'working_hours_end' => '17:00',
        'slot_interval_minutes' => 15,
    ],

    /*
    |--------------------------------------------------------------------------
    | Risk Prediction Settings
    |--------------------------------------------------------------------------
    |
    | Settings for appointment risk predictions.
    |
    */

    'risk_prediction' => [
        'cache_ttl_success' => 3600, // 1 hour
        'cache_ttl_failure' => 300,  // 5 minutes
        'risk_levels' => [
            'low' => ['min' => 0, 'max' => 0.3],
            'medium' => ['min' => 0.3, 'max' => 0.7],
            'high' => ['min' => 0.7, 'max' => 1.0],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Settings
    |--------------------------------------------------------------------------
    |
    | Settings for appointment notifications.
    |
    */

    'notifications' => [
        'reminder_hours_before' => 24,
        'follow_up_request_days' => 3,
    ],

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    |
    | Validation constraints for appointment data.
    |
    */

    'validation' => [
        'reason_max_length' => 500,
        'notes_max_length' => 2000,
        'cancellation_reason_max_length' => 500,
    ],
];
