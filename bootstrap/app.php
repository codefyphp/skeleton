<?php

declare(strict_types=1);

use Application\Provider\DatabaseServiceProvider;
use Application\Provider\RbacServiceProvider;
use Application\Provider\ViewServiceProvider;
use Codefy\Framework\Application as CodefyApp;
use Codefy\Framework\Providers\AssetsServiceProvider;
use Codefy\Framework\Providers\LocalizationServiceProvider;
use Qubus\Exception\Data\TypeException;

use function Codefy\Framework\Helpers\env;

try {
    $app = CodefyApp::create(
        config: [
            'basePath' => env(key: 'APP_BASE_PATH', default: dirname(path: __DIR__))
        ]
    )
    //->withEncryptedEnv(bool: true)
    ->withProviders([
        RbacServiceProvider::class,
        LocalizationServiceProvider::class,
        DatabaseServiceProvider::class,
        AssetsServiceProvider::class,
        ViewServiceProvider::class,
    ])
    ->withSingletons([
        //
    ])
    ->withRouting(
        web: [
            dirname(path: __DIR__) . '/routes/web/register.php',
            dirname(path: __DIR__) . '/routes/web/admin.php',
            dirname(path: __DIR__) . '/routes/web/auth.php',
            dirname(path: __DIR__) . '/routes/web/web.php',
        ],
        api: dirname(path: __DIR__) . '/routes/api/rest.php',
    )->return();

    $app->share(nameOrInstance: $app);

    return $app::getInstance();
} catch (TypeException|ReflectionException $e) {
    return $e->getMessage();
}
