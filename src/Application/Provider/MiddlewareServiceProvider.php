<?php

declare(strict_types=1);

namespace Application\Providers;

use Codefy\Framework\Support\CodefyServiceProvider;

class MiddlewareServiceProvider extends CodefyServiceProvider
{
    public function register(): void
    {
        $middlewares = $this->codefy->make(name: 'codefy.config')->getConfigKey('app.middlewares');
        foreach ($middlewares as $key => $value) {
            $this->codefy->alias(original: $key, alias: $value);
        }
    }
}
