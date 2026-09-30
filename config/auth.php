<?php

use App\Models\User;
use App\Models\PenggunaEvent;
use App\Models\Admin;

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // Milik main
        'pelanggan' => [
            'driver' => 'session',
            'provider' => 'pengguna_event',
        ],

        'admin' => [
            'driver' => 'session',
            'provider' => 'akun_staf',
        ],

        // Milik han (sebelumnya bernama 'admin', diganti agar tidak bentrok)
        'admin_api' => [
            'driver' => 'sanctum',
            'provider' => 'admins',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => PenggunaEvent::class,
        ],

        'pengguna_event' => [
            'driver' => 'eloquent',
            'model' => App\Models\PenggunaEvent::class,
        ],

        'akun_staf' => [
            'driver' => 'eloquent',
            'model' => App\Models\AkunStaf::class,
        ],

        // Milik han
        'admins' => [
            'driver' => 'eloquent',
            'model' => Admin::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];