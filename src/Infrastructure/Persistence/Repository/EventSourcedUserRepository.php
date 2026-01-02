<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Repository;

use Codefy\Domain\Aggregate\AggregateId;
use Codefy\Domain\Aggregate\AggregateNotFoundException;
use Codefy\Domain\Aggregate\AggregateRepository;
use Codefy\Domain\Aggregate\RecordsEvents;
use Codefy\Domain\EventSourcing\CorruptEventStreamException;
use Codefy\Domain\EventSourcing\Projection;
use Codefy\Domain\EventSourcing\TransactionalEventStore;
use Codefy\Traits\IdentityMapAware;
use Domain\User\User;

final class EventSourcedAggregateRepository implements AggregateRepository
{
    use IdentityMapAware;

    public function __construct(
        public readonly TransactionalEventStore $eventStore,
        public readonly Projection $projection
    ) {
    }

    /**
     * @throws AggregateNotFoundException
     * @throws CorruptEventStreamException
     */
    public function loadAggregateRoot(AggregateId $aggregateId): ?RecordsEvents
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

        $events = iterator_to_array($aggregate->getRecordedEvents());

        $transaction = $this->eventStore->commit(...$events);

        $committedEvents = $transaction->committedEvents;

        $this->projection->project(...$committedEvents);

        $aggregate->clearRecordedEvents();

        $this->removeFromIdentityMap($aggregate);
    }
}
