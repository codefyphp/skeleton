<?php

declare(strict_types=1);

use App\Domain\User\Services\UserProjection;
use App\Infrastructure\Persistence\OrmTransactionalEventStore;
use App\Infrastructure\Persistence\Repository\UserRepository;
use App\Infrastructure\Services\DatabaseService;
use App\Infrastructure\Services\DatabaseUserProjection;
use Codefy\CommandBus\Container;
use Codefy\CommandBus\Containers\InjectorContainer;
use Codefy\Domain\Aggregate\AggregateRepository;
use Codefy\Domain\EventSourcing\Projection;
use Codefy\Domain\EventSourcing\TransactionalEventStore;
use Codefy\Framework\Proxy\Codefy;
use Qubus\Config\Collection;
use Qubus\Expressive\Database;
use Qubus\Injector\Injector;

use function Codefy\Framework\Helpers\base_path;
use function Codefy\Framework\Helpers\config_path;
use function Codefy\Framework\Helpers\env;

return [
    'container' => [
        Injector::ARGUMENT_DEFINITIONS => [
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
        Database::class => DatabaseService::class,
        TransactionalEventStore::class => OrmTransactionalEventStore::class,
        Projection::class => DatabaseUserProjection::class,
        UserProjection::class => DatabaseUserProjection::class,
        AggregateRepository::class => UserRepository::class,
        ]
    ]
];
