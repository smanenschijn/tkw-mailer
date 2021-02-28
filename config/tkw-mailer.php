<?php

return [
    'services' => [
        'mailjet' => [
            'name' => 'MailJet',
            'class' => \App\Mail\Services\MailJet::class,
            'url' => env('MAILJET_API_URL'),
            'username' => env('MAILJET_API_USERNAME'),
            'password' => env('MAILJET_API_PASSWORD')
        ],
        'sendgrid' => [
            'name' => 'SendGrid',
            'class' => \App\Mail\Services\SendGrid::class,
            'url' => env('SENDGRID_API_URL'),
            'token' => sprintf('Bearer %s', env('SENDGRID_API_TOKEN'))
        ]
    ],
    'settings' => [
        'email' => [
            'from' => env('TKW_MAILER_FROM'),
            'name' => env('TKW_MAILER_FROM_NAME')
        ],
        'rate_limiter' => [
            'threshold' => env('TKW_MAILER_THRESHOLD')
        ]
    ]
];
