<?php

return [

    /*
    | PayGate Global (https://paygateglobal.com) : paiements Mixx by Yas et Moov Money Flooz.
    | Mode « live » : vrais paiements avec la clé API du compte marchand.
    | Mode « simulation » : parcours complet sans argent réel, pour tester (jamais en production).
    */
    'paygate' => [
        'token' => env('PAYGATE_AUTH_TOKEN'),
        'mode' => env('PAYGATE_MODE', env('PAYGATE_AUTH_TOKEN') ? 'live' : 'simulation'),
        'url' => env('PAYGATE_URL', 'https://paygateglobal.com'),
        'timeout' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
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

];
