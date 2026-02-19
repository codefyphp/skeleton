<?php

declare(strict_types=1);

namespace Application\Provider;

use Codefy\Framework\Support\CodefyServiceProvider;
use Qubus\Exception\Data\TypeException;

class MiddlewareServiceProvider extends CodefyServiceProvider
{
    /**
     * @throws TypeException
     */
    public function register(): void
    {
        /** @var array<string, class-string> $middlewares */
        $middlewares = $this->codefy->configContainer->array(key: 'app.middlewares');
        foreach ($middlewares as $key => $value) {
            $this->codefy->alias(original: $key, alias: $value);
        }
    }
}
