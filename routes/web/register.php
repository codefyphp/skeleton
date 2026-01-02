<?php

declare(strict_types=1);

use Qubus\Routing\Route\RouteGroup;

return function (\Qubus\Routing\Psr7Router $router) {
    $router->group('', function (RouteGroup $group1) {
        $group1->map(verbs: ['GET'], uri: '/register/', callback: 'RegisterController@show')
            ->name('register.show');

        $group1->map(verbs: ['POST'], uri: '/create/', callback: 'RegisterController@create')
            ->name('register.create');
    });
};
