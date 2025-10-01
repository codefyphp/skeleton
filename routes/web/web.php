<?php

declare(strict_types=1);

use Qubus\Routing\Route\RouteGroup;

use function Codefy\Framework\Helpers\config;

//Dynamic route for login.
$loginRoute = config(key: 'auth.login_route');

return function (\Qubus\Routing\Psr7Router $router) use ($loginRoute) {
    $router->group('', function (RouteGroup $group1) use ($loginRoute) {
        $group1->get(uri: '/', callback: 'HomeController@index');

        $group1->group(
            params: ['prefix' => 'admin'],
            callback: function (RouteGroup $group2) use ($loginRoute) {
                $group2->map(verbs: ['GET'], uri: '/', callback: 'AdminController@index')
                        ->name('admin.home');

                $group2->map(verbs: ['GET'], uri: '/register/', callback: 'AdminController@register')
                        ->name('admin.register');

                $group2->map(verbs: ['GET'], uri: '/logout/', callback: 'AdminController@logout')
                        ->name('admin.logout')
                        ->middleware(['user.session.expire']);

                $group2->map(verbs: ['GET'], uri: '/profile/', callback: 'AdminController@profile')
                        ->name('admin.profile');

                $group2->map(verbs: ['POST'], uri: '/update/', callback: 'AdminController@update')
                        ->name('admin.update');

                $group2->map(verbs: ['GET'], uri: "/{$loginRoute}/", callback: 'AdminController@login')
                        ->name('admin.login');

                $group2->map(verbs: ['POST'], uri: '/auth/', callback: 'AdminController@auth')
                        ->name('admin.auth')
                        ->middleware(['user.authenticate', 'user.session']);

                $group2->map(verbs: ['POST'], uri: '/create/', callback: 'AdminController@create')
                        ->name('admin.create');
            }
        );
    });
};
