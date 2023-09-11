<?php

declare(strict_types=1);

use Codefy\Framework\Application;

use function Codefy\Framework\Helpers\env;

$app = new Application(
    [
        'basePath' => env(key: 'APP_BASE_PATH', default: dirname(__DIR__))
    ]
);

$app->share($app);

return $app;
