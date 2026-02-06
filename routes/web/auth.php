<?php

declare(strict_types=1);

use Qubus\Routing\Route\RouteGroup;

use function Codefy\Framework\Helpers\config;

//Dynamic route for login.
$loginRoute = config(key: 'auth.login_route');

return function (\Qubus\Routing\Psr7Router $router) use ($loginRoute) {
    $router->group('', function (RouteGroup $group1) use ($loginRoute) {
        $group1->map(verbs: ['GET'], uri: "/{$loginRoute}/", callback: 'AuthController@login')
            ->name('auth.login');

        $group1->map(verbs: ['POST'], uri: '/auth/', callback: 'AuthController@auth')
            ->name('auth')
            ->middleware(['user.authenticate', 'user.session']);

        $group1->map(verbs: ['GET'], uri: '/logout/', callback: 'AuthController@logout')
            ->name('auth.logout')
            ->middleware(['user.session.expire']);
    });
};
