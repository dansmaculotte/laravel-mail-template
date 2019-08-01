<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mail Template Driver
    |--------------------------------------------------------------------------
    |
    | Supported: "mailjet", "mandrill", "log", null
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

    'sendinblue' => [
        'key' => env('SENDINBLUE_KEY'),
    ]
];
