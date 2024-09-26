<?php

return [

    /** Alternative way to set permissions instead of named/grouped permissions. */
    /*'permissions' => [
        'admin:dashboard' => ['description' => 'Access to the dashboard.'],
        'admin:profile' => ['description' => 'Access to profile edit.'],
    ],*/

    /** Named or grouped permissions. */
    'permissions' => [
        'admin' => [
            'description' => 'Super Admin',
            'permissions' => [
                'admin:dashboard' => ['description' => 'Access to the dashboard.'],
                'admin:profile' => ['description' => 'Access to profile edit.'],
            ],
        ],
    ],

    'roles' => [
        'user' => [
            'description' => 'Regular user',
            'permissions' => [],
        ],
        'manager' => [
            'description' => 'Editor',
            'permissions' => ['admin:dashboard'],
        ],
        'admin' => [
            'description' => 'Administrator',
            'permissions' => ['admin'],
        ],
    ],
];
