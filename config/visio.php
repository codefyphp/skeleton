<?php

declare(strict_types=1);

use function Codefy\Framework\Helpers\env;
use function Codefy\Framework\Helpers\public_path;
use function Codefy\Framework\Helpers\storage_path;

return [
    /*
     |--------------------------------------------------------------------------
     | General settings
     |--------------------------------------------------------------------------
     |
     | General settings for configuring the PageBuilder.
     |
     */
        'general' => [
            'base_url' => env('APP_BASE_URL'),
            'language' => 'en',
            'assets_url' => '/assets',
            'uploads_url' => '/uploads'
        ],

    /*
     |--------------------------------------------------------------------------
     | Storage settings
     |--------------------------------------------------------------------------
     |
     | Database and file storage settings.
     |
     */
        'storage' => [
            'use_database' => true,
            'database' => [
                'dsn'    => env(key: 'DB_DSN'),
                'username'  => env('DB_USERNAME'),
                'password'  => env('DB_PASSWORD'),
                'options' => [
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_PERSISTENT => env(key: 'DB_PERSISTENT'),
                ],
                'prefix'    => '',
            ],
            'uploads_folder' => storage_path('app/visio/uploads')
        ],

    /*
     |--------------------------------------------------------------------------
     | Auth settings
     |--------------------------------------------------------------------------
     |
     | By default an authentication class is provided which checks for the
     | credentials configured in this setting block.
     |
     */
        'auth' => [
            'use_login' => true,
            'class' => Application\Service\VisioAuth::class,
            'url' => '/logout/',
        ],

    /*
     |--------------------------------------------------------------------------
     | WebsiteManager settings
     |--------------------------------------------------------------------------
     |
     | By default a basic WebsiteManager is provided for creating/editing pages.
     |
     */
        'website_manager' => [
            'use_website_manager' => true,
            'class' => Visio\Modules\WebsiteManager\WebsiteManager::class,
            'url' => '/manager'
        ],

    /*
     |--------------------------------------------------------------------------
     | Website settings
     |--------------------------------------------------------------------------
     |
     | By default a setting class is provided for accessing website settings.
     |
     */
        'setting' => [
            'class' => Visio\Setting::class
        ],

    /*
     |--------------------------------------------------------------------------
     | PageBuilder settings
     |--------------------------------------------------------------------------
     |
     | By default a PageBuilder is provided based on GrapesJS.
     |
     */
        'pagebuilder' => [
            'class' => Visio\Modules\GrapesJS\PageBuilder::class,
            'url' => '/manager/pagebuilder',
            'actions' => [
                'back' => '/manager'
            ]
        ],

    /*
     |--------------------------------------------------------------------------
     | Page settings
     |--------------------------------------------------------------------------
     |
     | By default a Page class is provided with knowledge about its layout and URL.
     |
     */
        'page' => [
            'class' => Visio\Page::class,
            'table' => 'pages',
            'translation' => [
                'class' => Visio\PageTranslation::class,
                'table' => 'page_translations',
                'foreign_key' => 'page_id',
            ]
        ],

    /*
     |--------------------------------------------------------------------------
     | Cache settings
     |--------------------------------------------------------------------------
     |
     | Faster load time by skipping block parsing if the page has been requested before.
     | A page will be cached, except if it contains a block with caching set to false.
     | This can be used to prevent caching pages with content that varies per page load.
     | The cached html is removed if the page is saved again in the page builder.
     |
     */
        'cache' => [
            'enabled' => false,
            'folder' => storage_path('framework/cache'),
            'class' => Visio\Cache::class
        ],

    /*
     |--------------------------------------------------------------------------
     | Theme settings
     |--------------------------------------------------------------------------
     |
     | PageBuilder requires a themes folder in which for each theme the individual
     | theme blocks are defined. A theme block is a sub folder in the themes folder
     | containing a view, model (optional) and controller (optional).
     |
     */
        'theme' => [
            'class' => Visio\Theme::class,
            'folder' => public_path('themes'),
            'folder_url' => '/themes',
            'active_theme' => 'bootstrap-business'
        ],

    /*
     |--------------------------------------------------------------------------
     | Routing settings
     |--------------------------------------------------------------------------
     |
     | Settings for resolving pages based on the current URI.
     |
     */
        'router' => [
            'class' => Visio\Modules\Router\DatabasePageRouter::class,
            'use_router' => true
        ],

    /*
     |--------------------------------------------------------------------------
     | Class replacements
     |--------------------------------------------------------------------------
     |
     | Allows mapping a class namespace to an alternative namespace,
     | useful for replacing implementations of specific pagebuilder classes.
     | Example: Visio\UploadedFile::class => Alternative\UploadedFile::class
     | Important: when overriding a class always extend the original class.
     |
     */
        'class_replacements' => [
        ],
];
