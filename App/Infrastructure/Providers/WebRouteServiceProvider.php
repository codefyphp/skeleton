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

        $router->group(params: ['prefix' => '/admin'], callback: function ($group) {
            $group->get(uri: '/', callback: 'AdminController@index')
                    ->name('admin.home');

            $group->get(uri: '/profile/', callback: 'AdminController@profile')
                    ->name('admin.profile');

            $group->post(uri: '/update/', callback: 'AdminController@update')
                    ->name('admin.update');

            $group->get(uri: '/login/', callback: 'AdminController@login')
                    ->name('admin.login');

            $group->post(uri: '/auth/', callback: 'AdminController@auth')
                    ->name('admin.auth')
                    ->middleware(['user.authenticate', 'user.session']);

            $group->get(uri: '/register/', callback: 'AdminController@register')
                    ->name('admin.register');

            $group->post(uri: '/create/', callback: 'AdminController@create')
                    ->name('admin.create');

            $group->get(uri: '/logout/', callback: 'AdminController@logout')
                    ->name('admin.logout')
                    ->middleware(['user.session.expire']);
        });
    }
}
