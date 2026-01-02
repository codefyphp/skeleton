<?php

declare(strict_types=1);

use function Codefy\Framework\Helpers\env;
use function Codefy\Framework\Helpers\trans;

return [
    /*
    |--------------------------------------------------------------------------
    | User session cookie name.
    |--------------------------------------------------------------------------
    */
    'cookie_name' => 'USERSESSID',

    /**
     * Do not use the default app encryption key found in .env.example.
     * Generate a new encryption key by running this console command:
     * php codex generate:key
     */
    'encryption_key' => env(key: 'APP_ENCRYPTION_KEY'),

    /**
     * If you update this value, you will need to update the values
     * for `login_url` and `http_redirect` as well.
     */
    'login_route' => 'login',

    'login_url' => env(key: 'APP_BASE_URL') . 'admin/login/',

    /** Where should users be redirected when authentication fails? */
    //'http_redirect' => '/admin/login/',

    'admin_url' => env(key: 'APP_BASE_URL') . 'admin/',

    'pdo' => [
        /** name of the user's table */
        'table' => 'users',

        'fields' => [
            /** field name to use for identity (email, username, token) */
            'identity' => 'email',
            /** name of the role field */
            'role' => 'role',
            /** name of the token field */
            'token' => 'token',
            /** name of the password field */
            'password' => 'password',
        ],

    ],

    'redirect_guests_to' => '/admin/login/',

    'password_min_length' => 26,

    'username_min_length' => 6,

    'access_denied_message' => trans('Access denied.'),
];
