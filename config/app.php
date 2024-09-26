<?php

declare(strict_types=1);

use function Codefy\Framework\Helpers\env;

return [
    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    */
    'name' => env(key: 'APP_NAME', default: 'CodefyPHP Framework'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    */

    'env' => env(key: 'APP_ENV', default: 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    */
    'debug' => env(key: 'APP_DEBUG'),

    /*
    |--------------------------------------------------------------------------
    | Application Base Url
    |--------------------------------------------------------------------------
    */
    'url' => env(key: 'APP_URL', default: 'localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    */
    'timezone' => 'America/Los_Angeles',

    /*
    |--------------------------------------------------------------------------
    | Application Locale
    |--------------------------------------------------------------------------
    */
    'locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Application Configured Service Providers
    |--------------------------------------------------------------------------
    | These service providers will automatically load when the application is
    | requested. Feel free to add your own service providers.
    */
    'providers' => [
        /*
         * Application Service Providers.
         */
        Codefy\Framework\Providers\PdoServiceProvider::class,
        App\Infrastructure\Providers\RbacServiceProvider::class,
        App\Infrastructure\Providers\Psr16ServiceProvider::class,
        App\Infrastructure\Providers\MiddlewareServiceProvider::class,
        App\Infrastructure\Providers\AppServiceProvider::class,
        App\Infrastructure\Providers\ApiRouteServiceProvider::class,
        App\Infrastructure\Providers\WebRouteServiceProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Middleware Aliases
    |--------------------------------------------------------------------------
    | Middleware aliases are registered here, but to use a middleware, you
    | can add them to a route, a group of routes or controllers.
    */
    'middlewares' => [
        /** Uncomment to use whoops in dev mode to override system error handler. */
        //'whoops' => Franzl\Middleware\Whoops\WhoopsMiddleware::class,
        'security.headers' => App\Infrastructure\Http\Middleware\SecureHeaders\ContentSecurityPolicyMiddleware::class,
        'csrf.token' => App\Infrastructure\Http\Middleware\Csrf\CsrfTokenMiddleware::class,
        'csrf.protection' => App\Infrastructure\Http\Middleware\Csrf\CsrfProtectionMiddleware::class,
        'cors' => App\Infrastructure\Http\Middleware\CorsMiddleware::class,
        'file.logger' => App\Infrastructure\Http\Middleware\LoggingMiddleware::class,
        'honeypot' => App\Infrastructure\Http\Middleware\HoneyPotMiddleware::class,
        'user.authenticate' => Codefy\Framework\Auth\Middleware\AuthenticationMiddleware::class,
        'user.session' => Codefy\Framework\Auth\Middleware\UserSessionMiddleware::class,
        'user.authorization' => App\Infrastructure\Http\Middleware\UserAuthorizationMiddleware::class,
        'user.session.expire' => App\Infrastructure\Http\Middleware\ExpireUserSessionMiddleware::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Base Middlewares
    |--------------------------------------------------------------------------
    | Register middleware class strings or aliases to be applied to the entire
    | application.
    */
    'base_middlewares' => [
        'csrf.token',
        'csrf.protection',
    ],

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Console Commands
    |--------------------------------------------------------------------------
    | These console commands will automatically load when the application is
    | requested. Feel free to add your own console commands.
    */
    'commands' => [
        /*
         * Codefy Framework Console Commands . . .
         */
        Codefy\Framework\Console\Commands\MakeCommand::class,
        Codefy\Framework\Console\Commands\ScheduleRunCommand::class,
        Codefy\Framework\Console\Commands\PasswordHashCommand::class,
        Codefy\Framework\Console\Commands\InitCommand::class,
        Codefy\Framework\Console\Commands\StatusCommand::class,
        Codefy\Framework\Console\Commands\CheckCommand::class,
        Codefy\Framework\Console\Commands\GenerateCommand::class,
        Codefy\Framework\Console\Commands\UpCommand::class,
        Codefy\Framework\Console\Commands\DownCommand::class,
        Codefy\Framework\Console\Commands\MigrateCommand::class,
        Codefy\Framework\Console\Commands\RollbackCommand::class,
        Codefy\Framework\Console\Commands\RedoCommand::class,
        Codefy\Framework\Console\Commands\ListCommand::class,
        Codefy\Framework\Console\Commands\ServeCommand::class,
        Codefy\Framework\Console\Commands\UuidCommand::class,
        Codefy\Framework\Console\Commands\UlidCommand::class,

        /*
         * Application Console Commands . . .
         */
        App\Application\Console\Commands\GenerateEncryptionKey::class,
    ]
];
