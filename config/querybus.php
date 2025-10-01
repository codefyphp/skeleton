<?php

declare(strict_types=1);

use App\Infrastructure\Services\DatabaseService;
use Codefy\CommandBus\Container;
use Codefy\CommandBus\Containers\InjectorContainer;
use Codefy\Framework\Proxy\Codefy;
use Qubus\Config\Collection;
use Qubus\Config\ConfigContainer;
use Qubus\Expressive\Database;
use Qubus\Injector\Injector;

use function Codefy\Framework\Helpers\base_path;
use function Codefy\Framework\Helpers\config_path;
use function Codefy\Framework\Helpers\env;

return [
    /*
    |--------------------------------------------------------------------------
    | Aliases for the query bus.
    |--------------------------------------------------------------------------
    */
    'aliases' => [
        Injector::ARGUMENT_DEFINITIONS => [
            /*PDO::class => [
                'dsn' => sprintf(
                    '%s:dbname=%s;host=%s;charset=utf8mb4',
                    env(key: 'DB_DRIVER'),
                    env(key: 'DB_NAME'),
                    env(key: 'DB_HOST')
                ),
                'username' => env(key: 'DB_USER'),
                'password' => env(key: 'DB_PASSWORD'),
            ],*/
            Collection::class => [
                'config' => [
                    'path' => config_path(),
                    'dotenv' => base_path(),
                    'environment' => env(key: 'APP_ENV', default: 'local'),
                ],
            ],
            DatabaseService::class => [
                'connection' => Codefy::$PHP->getDbConnection(),
                'tablePrefix' => env(key: 'DB_TABLE_PREFIX', default: ''),
                'primaryKeyName' => 'id',
                'foreignKeyName' => '%s_id'
            ],
        ],
        Injector::STANDARD_ALIASES => [
            Container::class => InjectorContainer::class,
            ConfigContainer::class => Collection::class,
            Database::class => DatabaseService::class,
        ],
    ],
];
