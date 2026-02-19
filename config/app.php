<?php

declare(strict_types=1);

use Codefy\Framework\Configuration\Middleware;
use Codefy\Framework\Support\CodefyServiceProvider;

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
    'url' => env(key: 'APP_URL', default: 'https://codefy.ddev.site/'),

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
    | Application HTML Charset
    |--------------------------------------------------------------------------
    */
    'charset' => 'UTF-8',

    /*
    |--------------------------------------------------------------------------
    | Application HTML Language
    |--------------------------------------------------------------------------
    */
    'language' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Domain
    |--------------------------------------------------------------------------
    */
    'locale_domain' => 'codefy',

    /*
    |--------------------------------------------------------------------------
    | API key for restful routes.
    |--------------------------------------------------------------------------
    */
    'api_key' => env(key: 'APP_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    */
    'crypto_key' => file_get_contents(filename: __DIR__ . '/../.enc.key'),

    /*
    |--------------------------------------------------------------------------
    | Event Listener Provider and Dispatcher
    |--------------------------------------------------------------------------
    */
    'event_listener' => Qubus\EventDispatcher\Providers\PrioritizedProvider::class,
    'event_dispatcher' => Qubus\EventDispatcher\EventDispatcher::class,

    /*
    |--------------------------------------------------------------------------
    | Controller Namespace
    |--------------------------------------------------------------------------
    */
    'controller_namespace' => 'Application\\Http\\Controller',

    /*
    |--------------------------------------------------------------------------
    | Application Configured Service Providers
    |--------------------------------------------------------------------------
    | These service providers will automatically load when the application is
    | requested. Feel free to add your own service providers.
    */
    'providers' => CodefyServiceProvider::defaultProviders()->merge([
        // Application Service Providers...
        // App\Infrastructure\Providers\AppServiceProvider::class,
    ])->toArray(),

    /*
    |--------------------------------------------------------------------------
    | Middleware Aliases
    |--------------------------------------------------------------------------
    | Middleware aliases are registered here, but to use a middleware, you
    | can add them to a route, a group of routes or controllers.
    */
    'middlewares' => Middleware::defaultMiddlewares()->merge([
        // Application Middleware Aliases...
    ])->toArray(),

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
        'http.cache.prevention',
        'user.cookie.decrypt',
        'bind.request',
        'php.debugbar',
        //'http.exception', //uncomment in production
    ],

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Console Commands
    |--------------------------------------------------------------------------
    | These console commands will automatically load when the application is
    | requested. Feel free to add your own console commands.
    */
    'commands' => [

    ]
];
