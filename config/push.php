<?php

/**
 * Configuration for push notification service
 */
return [
    /**
     * If we went to debug push notifications
     */
    'IS_DEBUG' => env('PUSH_IS_DEBUG', 1),

    /**
     * If notifications should be pushed to sandbox or to production
     */
    'IS_SANDBOX' => env('PUSH_IS_SANDBOX', 0),

    /**
     * Where to log all push notifications
     */
    'LOG_FILE' => env('PUSH_LOG_FILE', storage_path('logs/push.log')),

    /**
     * API Key for Google Cloud Messaging service
     */
    'GOOGLE_API_KEY' => env('PUSH_GOOGLE_API_KEY', ''),

    /**
     * Passphrase for Apple Push Notifications service sandbox certificate.
     */
    'IOS_SANDBOX_PASSPHARSE' => env('PUSH_IOS_SANDBOX_PASSPHARSE', ''),

    /**
     * Passphrase for Apple Push Notifications production certificate
     */
    'IOS_PRODUCTION_PASSPHARSE' => env('PUSH_IOS_PRODUCTION_PASSPHARSE', ''),
];
