<?php

return [

    /** Named or grouped permissions. */
    'permissions' => [
        'admin' => [
            'description' => 'Super Admin',
            'permissions' => [
                'admin:dashboard' => ['description' => 'Can access the dashboard.'],
                'admin:profile' => ['description' => 'Can edit own profile.'],
                'admin:create:user' => ['description' => 'Can create users.'],
                'admin:edit:user' => ['description' => 'Can edit a user profile.'],
                'admin:delete:user' => ['description' => 'Can delete a user profile.'],
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
            'permissions' => ['admin:dashboard', 'admin:profile'],
        ],
        'admin' => [
            'description' => 'Administrator',
            'permissions' => ['admin'],
        ],
    ],
];
