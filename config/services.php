<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'question_bank' => [
        'provider' => env('QUESTION_BANK_PROVIDER', 'opentdb'),
        'base_url' => env('QUESTION_BANK_BASE_URL', 'https://opentdb.com'),
        'token' => env('QUESTION_BANK_TOKEN'),
        'endpoint' => env('QUESTION_BANK_ENDPOINT', '/api.php'),
        'timeout' => (int) env('QUESTION_BANK_TIMEOUT', 15),
    ],

    'google_ai' => [
        'api_key' => env('GOOGLE_AI_API_KEY'),
        'model' => env('GOOGLE_AI_MODEL', 'gemini-1.5-flash'),
        'timeout' => (int) env('GOOGLE_AI_TIMEOUT', 20),
    ],

    'google' => [
        'api_key' => env('GOOGLE_API_KEY'),
        'timeout' => (int) env('GOOGLE_TIMEOUT', 20),
    ],

];
