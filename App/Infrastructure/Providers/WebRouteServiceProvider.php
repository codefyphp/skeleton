<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use Codefy\Framework\Support\CodefyServiceProvider;

final class WebRouteServiceProvider extends CodefyServiceProvider
{
    public function boot(): void
    {
        if ($this->codefy->isRunningInConsole()) {
            return;
        }

        $router = $this->codefy->make(name: 'router');

        $router->get('/', 'HomeController@index');
    }
}
