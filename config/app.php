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
    'timezone' => 'America/Denver',

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
    | need to add it to a route or controller.
    */
    'middlewares' => [
        'session' => Qubus\Http\Session\Middleware\SessionMiddleware::class,
        /** Uncomment to use whoops in dev mode to override system error handler. */
        //'whoops' => Franzl\Middleware\Whoops\WhoopsMiddleware::class,
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
    ]
];
