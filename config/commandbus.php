<?php

use App\Domain\User\Services\UserProjection;
use App\Infrastructure\Persistence\OrmTransactionalEventStore;
use App\Infrastructure\Persistence\Repository\UserRepository;
use App\Infrastructure\Services\DatabaseUserProjection;
use Codefy\CommandBus\Container;
use Codefy\CommandBus\Containers\InjectorContainer;
use Codefy\Domain\Aggregate\AggregateRepository;
use Codefy\Domain\EventSourcing\Projection;
use Codefy\Domain\EventSourcing\TransactionalEventStore;
use Qubus\Injector\Injector;

return [
    'container' => [
        Injector::STANDARD_ALIASES => [
            Container::class => InjectorContainer::class,
            Projection::class => DatabaseUserProjection::class,
            UserProjection::class => DatabaseUserProjection::class,
            AggregateRepository::class => UserRepository::class,
            TransactionalEventStore::class => OrmTransactionalEventStore::class,
        ]
    ]
];
