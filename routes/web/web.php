<?php

declare(strict_types=1);

use Qubus\Routing\Route\RouteGroup;

return function (\Qubus\Routing\Psr7Router $router) {
    $router->group('', function (RouteGroup $group1) {
        $group1->get(uri: '/', callback: 'HomeController@index')->name(name: 'home');
    });
};
