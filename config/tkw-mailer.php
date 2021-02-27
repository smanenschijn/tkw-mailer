<?php

return [
    'services' => [
        'sendgrid' => [
            'name' => 'SendGrid',
            'class' => \App\Mail\Services\SendGrid::class,
            'url' => '',
            'token' => sprintf('Bearer %s', env('SENDGRID_API_TOKEN'))
        ]
    ],
    'settings' => [
        'email' => [
            'from' => env('TKW_MAILER_FROM')
        ]
    ]
];
