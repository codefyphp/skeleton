<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use Codefy\Framework\Support\CodefyServiceProvider;
use Qubus\Routing\Router;

final class ApiRouteServiceProvider extends CodefyServiceProvider
{
    public function boot(): void
    {
        if ($this->codefy->isRunningInConsole()) {
            return;
        }

        /** @var $router Router */
        $router = $this->codefy->make(name: 'router');
    }
}
