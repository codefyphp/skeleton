<?php

declare(strict_types=1);

namespace Application\Provider;

use Codefy\Framework\Support\CodefyServiceProvider;
use Qubus\Routing\Psr7Router;
use Qubus\Routing\Router;

final class ApiRouteServiceProvider extends CodefyServiceProvider
{
    public function boot(): void
    {
        /** @var Router $router */
        $router = $this->codefy->make(name: Psr7Router::class);
    }
}
