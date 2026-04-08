<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Chatbot AI Configuration
    |--------------------------------------------------------------------------
    |
    | This option controls the AI-powered intent recognition for the chatbot.
    | When enabled, OpenAI GPT-4o-mini will be used to classify user intents.
    |
    */

    'ai_enabled' => env('CHATBOT_AI_ENABLED', true),

    'ai_model' => env('CHATBOT_DEFAULT_MODEL', 'gpt-4o-mini'),

    'intent_confidence_threshold' => env('CHATBOT_INTENT_CONFIDENCE_THRESHOLD', 0.7),

    /*
    |--------------------------------------------------------------------------
    | Conversation Settings
    |--------------------------------------------------------------------------
    |
    | Controls conversation lifecycle and state management.
    |
    */

    'max_conversation_age_hours' => env('CHATBOT_MAX_CONVERSATION_AGE_HOURS', 24),

    'idle_timeout_minutes' => 30,

    /*
    |--------------------------------------------------------------------------
    | Platform Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for WhatsApp and Messenger platforms.
    |
    */

    'platforms' => [
        'whatsapp' => [
            'enabled' => env('WHATSAPP_BUSINESS_ACCESS_TOKEN') !== null,
            'access_token' => env('WHATSAPP_BUSINESS_ACCESS_TOKEN'),
            'phone_number_id' => env('WHATSAPP_BUSINESS_PHONE_NUMBER_ID'),
            'verify_token' => env('WHATSAPP_WEBHOOK_VERIFY_TOKEN', 'medcura-webhook-verify'),
        ],
        'messenger' => [
            'enabled' => env('MESSENGER_ACCESS_TOKEN') !== null,
            'access_token' => env('MESSENGER_ACCESS_TOKEN'),
            'app_secret' => env('MESSENGER_APP_SECRET'),
            'verify_token' => env('MESSENGER_VERIFY_TOKEN', 'medcura-messenger-verify'),
            'page_id' => env('MESSENGER_PAGE_ID'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed Actions
    |--------------------------------------------------------------------------
    |
    | Controls which actions are available to patients via chatbot.
    |
    */

    'allowed_actions' => [
        'check_availability' => true,
        'book_appointment' => true,
        'view_appointments' => true,
        'cancel_appointment' => true,
        'reschedule_appointment' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Patient Identification
    |--------------------------------------------------------------------------
    |
    | How to identify patients on different platforms.
    | Options: 'phone', 'email', 'manual'
    |
    */

    'patient_identification' => [
        'whatsapp' => 'phone', // Auto-identify by phone number
        'messenger' => 'manual', // Require manual identification
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    |
    | Controls chatbot logging behavior.
    |
    */

    'log_level' => env('CHATBOT_LOG_LEVEL', 'info'), // debug, info, warning, error

    'log_messages' => true,

    'log_intent_recognition' => true,

    'log_errors' => true,
];
