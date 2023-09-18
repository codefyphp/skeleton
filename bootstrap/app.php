<?php

declare(strict_types=1);

use Codefy\Framework\Application;
use Qubus\Exception\Data\TypeException;

use function Codefy\Framework\Helpers\env;

try {
    $app = new Application(
        params: [
            'basePath' => env(key: 'APP_BASE_PATH', default: dirname(path: __DIR__))
        ]
    );

    $app->share(nameOrInstance: $app);

    return $app;
} catch (TypeException $e) {
    return $e->getMessage();
}
