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

    'paystack' => [
        'secret' => env('PAYSTACK_SECRET_KEY'),
        'public' => env('PAYSTACK_PUBLIC_KEY'),
        'test_secret' => env('PAYSTACK_TEST_SECRET_KEY'),
        'test_public' => env('PAYSTACK_TEST_PUBLIC_KEY'),
    ],
    "tigo" =>[
        "key" => env("TIGO_KEY"),
        "secret" => env("TIGO_SECRET")
    ],

    "sms" => [
        "key" => env("SMS_KEY"),
        "sender_id" => env("SENDER_ID"),
    ],

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    "secret" => [
    "balance-key" => env("BALANCE_KEY"),
    ],
    "unimarket" => [
        "key" => env("UNIMARKET_API_KEY"),
        "base_url" => env("UNIMARKET_BASE_URL")
    ],
    "other" => [
        "key" => env("OTHER_API_KEY"),
        "base_url" => env("OTHER_BASE_URL")
    ],
    "realest" => [
        "api_key" => env("REALEST_API_KEY"),
        "base_url" => env("REALEST_BASE_URL"),
    ],


];
