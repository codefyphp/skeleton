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

        /** @var $router Router */
        $router = $this->codefy->make(name: 'router');

        $router->get('/', 'HomeController@index');
    }
}
