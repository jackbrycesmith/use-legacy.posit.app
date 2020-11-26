<?php

return [
    'skip-route-function' => true,
    'except' => [
        '_debugbar.*',
        'debugbar.*',
        'horizon.*',
        'cypress.*',
        'telescope',
        'ignition.*',
        'laravel-websockets.*',
    ],

    'groups' => [
        'pub' => [
            'pub.*',
        ],
        'use-posit-app' => [
            'use.*',
            'pub.*',
            'teams.*',
            'current-team.*',
            'team-members.*',
            'password.*',
            'two-factor.*',
            'api-tokens.*',
            'verification.*',
            'user-profile-information.*',
            'user-password.*',
            'current-user.*',
            'current-user-photo.*',
            'other-browser-sessions.*',
            'profile.*',
            'register',
            'login',
            'logout',
        ],
    ],
];
