<?php

declare(strict_types=1);

use Application\Service\DatabaseService;
use Codefy\CommandBus\Container;
use Codefy\CommandBus\Containers\InjectorContainer;
use Codefy\Domain\EventSourcing\TransactionalEventStore;
use Codefy\Framework\Proxy\Codefy;
use Domain\User\Repository\UserAggregateRepository;
use Domain\User\Service\UserProjection;
use Infrastructure\Persistence\PdoTransactionalEventStore;
use Infrastructure\Persistence\Repository\EventSourcedUserRepository;
use Infrastructure\Projection\ExpressiveDbalUserProjection;
use Qubus\Config\Collection;
use Qubus\Expressive\Connection;
use Qubus\Expressive\Database;
use Qubus\Injector\Injector;

use function Codefy\Framework\Helpers\app;
use function Codefy\Framework\Helpers\base_path;
use function Codefy\Framework\Helpers\config_path;
use function Codefy\Framework\Helpers\dbal;
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
                'connection' => dbal(),
                'tablePrefix' => env(key: 'DB_TABLE_PREFIX', default: ''),
                'primaryKeyName' => 'id',
                'foreignKeyName' => '%s_id'
            ],
        ],
        Injector::STANDARD_ALIASES => [
        Container::class => InjectorContainer::class,
        Database::class => DatabaseService::class,
        TransactionalEventStore::class => PdoTransactionalEventStore::class,
        UserProjection::class => ExpressiveDbalUserProjection::class,
        UserAggregateRepository::class => EventSourcedUserRepository::class,
        ]
    ]
];
