<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\Models\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'paytm-wallet' => [
        'env' => 'production', // values : (local | production)
        'merchant_id' => env('PAYTM_MERCHANT_ID'),
        'merchant_key' => env('PAYTM_MERCHANT_KEY'),
        'merchant_website' => env('PAYTM_WEBSITE'),
        'channel' => env('PAYTM_CHANNEL'),
        'industry_type' => env('PAYTM_INDUSTRY_TYPE'),
    ],

    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),         // Your GitHub Client ID
        'client_secret' => env('GITHUB_CLIENT_SECRET'), // Your GitHub Client Secret
        'redirect' => env('GITHUB_CLIENT_REDIRECT'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),         // Your GitHub Client ID
        'client_secret' => env('GOOGLE_CLIENT_SECRET'), // Your GitHub Client Secret
        'redirect' => env('GOOGLE_CLIENT_REDIRECT'),
    ],

    'linkedin' => [
        'client_id' => env('LINKEDIN_CLIENT_ID'),         // Your GitHub Client ID
        'client_secret' => env('LINKEDIN_CLIENT_SECRET'), // Your GitHub Client Secret
        'redirect' => env('LINKEDIN_CLIENT_REDIRECT'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),         // Your GitHub Client ID
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'), // Your GitHub Client Secret
        'redirect' => env('FACEBOOK_CLIENT_REDIRECT'),
    ],

    'twitter' => [
        'client_id' => env('TWITTER_CLIENT_ID'),         // Your GitHub Client ID
        'client_secret' => env('TWITTER_CLIENT_SECRET'), // Your GitHub Client Secret
        'redirect' => env('TWITTER_CLIENT_REDIRECT'),
    ],

];
