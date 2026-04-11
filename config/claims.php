<?php

/**
 * Claims configuration settings.
 * 
 * Centralizes all claim-related configuration values
 * that were previously hardcoded throughout the application.
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Claim Status Settings
    |--------------------------------------------------------------------------
    |
    | Valid status values for insurance claims.
    |
    */

    'statuses' => [
        'draft',
        'pending',
        'submitted',
        'accepted',
        'rejected',
        'approved',
        'denied',
        'paid',
        'appealed',
    ],

    /*
    |--------------------------------------------------------------------------
    | Claim Submission Settings
    |--------------------------------------------------------------------------
    |
    | Settings for claim submission retry logic and timeouts.
    |
    */

    'submission' => [
        'max_retries' => 3,
        'retry_delays' => [5, 15, 45], // Minutes between retries
        'timeout_seconds' => 30,
        'batch_size' => 50,
    ],

    /*
    |--------------------------------------------------------------------------
    | Clearinghouse Settings
    |--------------------------------------------------------------------------
    |
    | Settings for clearinghouse integration.
    |
    */

    'clearinghouse' => [
        'providers' => [
            'availity',
            'change_healthcare',
            'waystar',
        ],
        'default_provider' => 'availity',
        'submission_format' => '837P', // Professional claims
        'response_timeout_hours' => 24,
    ],

    /*
    |--------------------------------------------------------------------------
    | Claim ID Generation
    |--------------------------------------------------------------------------
    |
    | Settings for claim ID format and generation.
    |
    */

    'id_generation' => [
        'prefix' => 'CLM',
        'use_year' => true,
        'sequence_padding' => 6, // CLM-2026-000001
        'separator' => '-',
    ],

    /*
    |--------------------------------------------------------------------------
    | Billing Thresholds
    |--------------------------------------------------------------------------
    |
    | Thresholds for underpayment detection and alerts.
    |
    */

    'billing' => [
        'underpayment_threshold_percent' => 10, // Alert if paid < 90% of expected
        'denial_risk_threshold' => 0.7, // High risk if > 70%
        'auto_submit_threshold' => 0.8, // Auto-submit if confidence > 80%
    ],

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    |
    | Validation constraints for claim data.
    |
    */

    'validation' => [
        'icd10_codes_required' => true,
        'cpt_codes_required' => true,
        'diagnosis_required' => true,
        'patient_info_required' => true,
        'insurance_info_required' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Backup Settings
    |--------------------------------------------------------------------------
    |
    | Settings for claim backup and retention.
    |
    */

    'backup' => [
        'enabled' => true,
        'retention_days' => 2555, // 7 years
        'format' => 'json',
    ],
];
