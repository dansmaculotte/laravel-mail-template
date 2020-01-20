<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mail Template Driver
    |--------------------------------------------------------------------------
    |
    | Supported: "mailjet", "mandrill", "sendgrid, "mailgun", "sendinblue", "log", null
    |
    */
    'driver' => env('MAIL_TEMPLATE_DRIVER', null),

    'mailjet' => [
        'key' => env('MJ_APIKEY_PUBLIC'),
        'secret' => env('MJ_APIKEY_PRIVATE'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'sendgrid' => [
        'key' => env('SENDGRID_KEY'),
    ],

    'mailgun' => [
        'key' => env('MAILGUN_KEY'),
        'domain' => env('MAILGUN_DOMAIN'),
    ],

    'sendinblue' => [
        'key' => env('SENDINBLUE_KEY'),
    ],
];
