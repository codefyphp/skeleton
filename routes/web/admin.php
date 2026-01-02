<?php

declare(strict_types=1);

use Qubus\Routing\Route\RouteGroup;

use function Codefy\Framework\Helpers\config;

$loginRoute = config(key: 'auth.login_route');

return function (\Qubus\Routing\Psr7Router $router) use ($loginRoute) {
    $router->group(params: ['prefix' => 'admin'], callback: function (RouteGroup $group) use ($loginRoute) {
        $group->map(verbs: ['GET'], uri: '/profile/', callback: 'ProfileController@index')
            ->name('admin.profile')
            ->middleware(["gate:admin:profile,/{$loginRoute}/"]);

        $group->map(verbs: ['PUT'], uri: '/profile/update/', callback: 'ProfileController@update')
            ->name('admin.profile.update')
            ->middleware(["gate:admin:profile,/{$loginRoute}/"]);

        $group->map(verbs: ['PUT'], uri: '/user/edit/', callback: 'AdminController@edit')
            ->middleware(["gate:admin:edit:user,/{$loginRoute}/"]);

        $group->map(verbs: ['PUT'], uri: '/user/delete/', callback: 'AdminController@destroy')
            ->name('admin.user.delete')
            ->middleware(["gate:admin:edit:user,/admin/users/"]);

        $group->map(verbs: ['GET'], uri: '/', callback: 'AdminController@index')
            ->name('admin.home')
            ->middleware(['gate:admin:dashboard,/']);

        $group->map(verbs: ['POST'], uri: '/store/', callback: 'AdminController@store')
            ->name('admin.store')
            ->middleware(["gate:admin:create:user,/{$loginRoute}/"]);

        $group->map(verbs: ['GET'], uri: '/users/', callback: 'AdminController@users')
            ->name('admin.users')
            ->middleware(["gate:admin:dashboard,/{$loginRoute}/"]);
    });
};
