<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Repository;

use Codefy\Domain\Aggregate\AggregateId;
use Codefy\Domain\Aggregate\AggregateNotFoundException;
use Codefy\Domain\Aggregate\RecordsEvents;
use Codefy\Domain\EventSourcing\CorruptEventStreamException;
use Codefy\Domain\EventSourcing\DomainEvent;
use Codefy\Domain\EventSourcing\TransactionalEventStore;
use Codefy\Traits\EventSourcedRepositoryAware;
use Domain\User\Repository\UserAggregateRepository;
use Domain\User\Service\UserProjection;
use Domain\User\User;

final class EventSourcedUserRepository implements UserAggregateRepository
{
    use EventSourcedRepositoryAware;

    public function __construct(protected TransactionalEventStore $eventStore, protected UserProjection $projection)
    {
    }

    /**
     * @throws AggregateNotFoundException
     * @throws CorruptEventStreamException
     */
    public function loadAggregateRoot(AggregateId $aggregateId): RecordsEvents
    {
        $this->retrieveFromIdentityMap($aggregateId);

        $aggregateHistory = $this->eventStore->getAggregateHistoryFor(aggregateId: $aggregateId);
        $eventSourcedAggregate = User::reconstituteFromEventStream(
            aggregateHistory: $aggregateHistory
        );

        $this->attachToIdentityMap($eventSourcedAggregate);

        return $eventSourcedAggregate;
    }

    public function saveAggregateRoot(RecordsEvents $aggregate): void
    {
        $this->attachToIdentityMap($aggregate);

        /** @var DomainEvent[] $events */
        $events = iterator_to_array($aggregate->getRecordedEvents());

        $transaction = $this->eventStore->commit(...$events);

        $committedEvents = $transaction->committedEvents;

        $this->projection->project(...$committedEvents);

        $aggregate->clearRecordedEvents();

        $this->removeFromIdentityMap($aggregate);
    }
}
