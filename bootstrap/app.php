<?php

declare(strict_types=1);

use App\Infrastructure\Providers\DatabaseServiceProvider;
use App\Infrastructure\Providers\ViewServiceProvider;
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
        LocalizationServiceProvider::class,
        DatabaseServiceProvider::class,
        AssetsServiceProvider::class,
        ViewServiceProvider::class,
    ])
    ->withSingletons([
        //
    ])
    ->withRouting(
        web: __DIR__ . '/../routes/web/web.php',
        api: __DIR__ . '/../routes/api/rest.php',
    )->return();

    $app->share(nameOrInstance: $app);

    return $app::getInstance();
} catch (TypeException|ReflectionException $e) {
    return $e->getMessage();
}
