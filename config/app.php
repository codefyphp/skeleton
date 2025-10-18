<?php

declare(strict_types=1);

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
    'middlewares' => [
        'api' => Codefy\Framework\Http\Middleware\ApiMiddleware::class,
        //'security.headers' => Codefy\Framework\Http\Middleware\SecureHeaders\ContentSecurityPolicyMiddleware::class,
        //'content.cache' => Codefy\Framework\Http\Middleware\ContentCacheMiddleware::class,
        //'cors' => Codefy\Framework\Http\Middleware\CorsMiddleware::class,
        'csrf.token' => Codefy\Framework\Http\Middleware\Csrf\CsrfTokenMiddleware::class,
        'csrf.protection' => Codefy\Framework\Http\Middleware\Csrf\CsrfProtectionMiddleware::class,
        //'css.minify' => Codefy\Framework\Http\Middleware\CssMinifierMiddleware::class,
        //'honeypot' => Codefy\Framework\Http\Middleware\Spam\HoneyPotMiddleware::class,
        //'html.minify' => Codefy\Framework\Http\Middleware\HtmlMinifierMiddleware::class,
        //'http.cache' => Codefy\Framework\Http\Middleware\Cache\CacheMiddleware::class,
        //'http.cache.clear.data' => Codefy\Framework\Http\Middleware\Cache\ClearSiteDataMiddleware::class,
        //'http.cache.expires' => Codefy\Framework\Http\Middleware\Cache\CacheExpiresMiddleware::class,
        'http.cache.prevention' => Codefy\Framework\Http\Middleware\Cache\CachePreventionMiddleware::class,
        //'js.minify' => Codefy\Framework\Http\Middleware\JsMinifierMiddleware::class,
        'rate.limiter' => Codefy\Framework\Http\Middleware\ThrottleMiddleware::class,
        //'referrer.spam' => Codefy\Framework\Http\Middleware\Spam\ReferrerSpamMiddleware::class,
        'user.authenticate' => Codefy\Framework\Http\Middleware\Auth\AuthenticationMiddleware::class,
        'user.session' => Codefy\Framework\Http\Middleware\Auth\UserSessionMiddleware::class,
        'user.authorization' => Codefy\Framework\Http\Middleware\Auth\UserAuthorizationMiddleware::class,
        'user.session.expire' => Codefy\Framework\Http\Middleware\Auth\ExpireUserSessionMiddleware::class,
        //'php.debugbar' => Codefy\Framework\Http\Middleware\DebugBarMiddleware::class,
        'error.handler' => Middlewares\Whoops::class,
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
        'http.cache.prevention',
        //'error.handler',
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
