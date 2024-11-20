<?php

return [

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],

        'parent' => [
            'driver' => 'session',
            'provider' => 'parents',
        ],

        'therapist' => [
            'driver' => 'session',
            'provider' => 'therapists',
        ],

        'cs' => [
            'driver' => 'session',
            'provider' => 'cs',
        ],

        'sales' => [
            'driver' => 'session',
            'provider' => 'sales',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\AdminInfo::class,
        ],

        'parents' => [
            'driver' => 'eloquent',
            'model' => App\Models\ParentAccount::class,
        ],

        'therapists' => [
            'driver' => 'eloquent',
            'model' => App\Models\TherapistInfo::class,
        ],

        'cs' => [
            'driver' => 'eloquent',
            'model' => App\Models\CsInfo::class,
        ],
        
        'sales' => [
            'driver' => 'eloquent',
            'model' => App\Models\SalesLeadInfo::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 120,
            'throttle' => 120,
        ],
    ],

    'password_timeout' => 10800,
];
