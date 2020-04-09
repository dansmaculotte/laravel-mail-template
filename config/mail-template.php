<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mail Template Driver
    |--------------------------------------------------------------------------
    |
    | Supported: "mailjet", "mandrill", "sendgrid, "mailgun", "log", null
    |
    */
    'driver' => env('MAIL_TEMPLATE_DRIVER', null),

    'mailjet' => [
        'key' => env('MJ_APIKEY_PUBLIC'),
        'secret' => env('MJ_APIKEY_PRIVATE'),
        // Uncomment to send mailjet template debug email to configured address.
        // 'debug_email' => [
        //     'Name' => 'John Doe',
        //     'Email' => 'john@example.com'
        // ],
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
