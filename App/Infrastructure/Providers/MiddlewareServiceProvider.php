<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use Codefy\Framework\Support\CodefyServiceProvider;

class MiddlewareServiceProvider extends CodefyServiceProvider
{
    public function register(): void
    {
        if ($this->codefy->isRunningInConsole()) {
            return;
        }

        $middlewares = $this->codefy->make(name: 'codefy.config')->getConfigKey('app.middlewares');
        foreach($middlewares as $key => $value) {
            $this->codefy->alias(original: $key, alias: $value);
        }
    }
}