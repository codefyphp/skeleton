<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use Codefy\Framework\Support\CodefyServiceProvider;
use Qubus\Routing\Exceptions\TooLateToAddNewRouteException;
use Qubus\Routing\Router;

final class WebRouteServiceProvider extends CodefyServiceProvider
{
    /**
     * @throws TooLateToAddNewRouteException
     */
    public function boot(): void
    {
        if ($this->codefy->isRunningInConsole()) {
            return;
        }

        /** @var Router $router*/
        $router = $this->codefy->make(name: 'router');

        $router->get(uri: '/', callback: 'HomeController@index');
        $router->get(uri: '/admin/', callback: 'AdminController@index');
        $router->get(uri: '/admin/profile/', callback: 'AdminController@profile');
        $router->post(uri: '/admin/update/', callback: 'AdminController@update');
        $router->get(uri: '/admin/login/', callback: 'AdminController@login');
        $router->post(uri: '/admin/auth/', callback: 'AdminController@auth')->middleware('user.session');
        $router->get(uri: '/admin/register/', callback: 'AdminController@register');
        $router->post(uri: '/admin/create/', callback: 'AdminController@create');
        $router->get(uri: '/admin/logout/', callback: 'AdminController@logout');
    }
}
