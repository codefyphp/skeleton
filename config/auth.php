<?php

use function Codefy\Framework\Helpers\env;

return [
    /**
     * Do not use the default app encryption key found in .env.example.
     * Generate a new encryption key by running this console command:
     * php codex generate:key
     */
    'encryption_key' => env(key: 'APP_ENCRYPTION_KEY'),

    'login_url' => env(key: 'APP_BASE_URL') . '/admin/login/',

    'admin_url' => env(key: 'APP_BASE_URL') . '/admin/',

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

    /** Where should users be redirected when authentication fails? */
    'http_redirect' => '',
];
