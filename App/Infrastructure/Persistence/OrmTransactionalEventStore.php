<?php

/**
 * This is an example event store database class implementation
 * using Qubus OrmBuilder. It is not recommended to use this
 * implementation in production as is. Feel free to edit,
 * rename or clone for your needs.
 */

namespace App\Infrastructure\Persistence;

use Codefy\Domain\Aggregate\AggregateId;
use Codefy\Domain\EventSourcing\DomainEvent;
use Codefy\Domain\EventSourcing\DomainEvents;
use Codefy\Domain\EventSourcing\EventId;
use Codefy\Domain\EventSourcing\EventStoreTransaction;
use Codefy\Domain\EventSourcing\EventStream;
use Codefy\Domain\EventSourcing\Transactional;
use Codefy\Domain\EventSourcing\TransactionalEventStore;
use Codefy\Domain\EventSourcing\TransactionId;
use Codefy\Domain\Metadata;
use Exception as NativeException;
use Qubus\Dbal\DB;
use Qubus\Exception\Data\TypeException;
use Qubus\Exception\Exception;
use Qubus\Expressive\OrmBuilder;
use Qubus\Expressive\OrmException;

use function Codefy\Framework\Helpers\config;

final class OrmTransactionalEventStore implements TransactionalEventStore
{
    private ?OrmBuilder $orm = null;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->orm = new OrmBuilder(
            DB::connection((array)config('database.connections.default'))
        );
    }

    /**
     * @inheritDoc
     * @throws NativeException
     */
    public function append(DomainEvent $event, TransactionId $transactionId): void
    {
        try {
            $this->orm->transactional(callback: function () use ($event, $transactionId) {
                $this->orm
                    ->table(tableName: 'event_store')
                    ->set([
                        'event_id' => $event->eventId()->__toString(),
                        'transaction_id' => $transactionId::fromString(transactionId: $transactionId->toNative()),
                        'event_type' => $event->eventType(),
                        'event_classname' => get_class($event),
                        'payload' => json_encode(value: $event->payload(), flags: JSON_PRETTY_PRINT),
                        'metadata' => json_encode(value: [
                            '__aggregate_type' => $event->metaParam(name: Metadata::AGGREGATE_TYPE),
                            '__aggregate_id' => (string) $event->aggregateId(),
                            '__aggregate_playhead' => $event->playhead(),
                            '__event_id' => (string) $event->eventId(),
                            '__event_type' => $event->eventType(),
                            '__recorded_at' => (string) $event->recordedAt()
                        ], flags: JSON_PRETTY_PRINT),
                        'aggregate_id' => $event->aggregateId()->__toString(),
                        'aggregate_type' => $event->metadata()[Metadata::AGGREGATE_TYPE],
                        'aggregate_playhead' => $event->playhead(),
                        'recorded_at' => (string) $event->recordedAt(),
                ])
                ->save();
            });
        } catch (OrmException $e) {
            throw new NativeException(message: $e->getMessage());
        }
    }

    /**
     * @inheritDoc
     * @throws TypeException
     * @throws NativeException
     */
    public function commit(DomainEvent ...$events): Transactional
    {
        $eventStream = DomainEvents::fromArray(events: $events);
        $transactionId = new TransactionId();

        if (count($events) === 0) {
            return new EventStoreTransaction(
                transactionId: $transactionId,
                eventStream: $eventStream,
                committedEvents: $events
            );
        }

        foreach ($events as $event) {
            $this->append(event: $event, transactionId: $transactionId);
        }

        return new EventStoreTransaction(
            transactionId: $transactionId,
            eventStream: $eventStream,
            committedEvents: $events
        );
    }

    /**
     * @inheritDoc
     * @throws TypeException
     */
    public function getAggregateHistoryFor(AggregateId $aggregateId): EventStream
    {
        $stream = [];

        $query = $this->orm->table(tableName: 'event_store')
        ->select(columns: '*')
        ->where(condition: 'aggregate_id', parameters: (string) $aggregateId);

        $eventStream = iterator_to_array(iterator: $query->find());

        foreach ($eventStream as $event) {
            $metadata = json_decode(json: $event->metadata, associative: true);

            $stream[] = $event->event_classname::fromArray([
                'aggregateId' => $aggregateId::fromString($event->aggregate_id),
                'payload' => json_decode(json: $event->payload, associative: true),
                'metadata' => [
                    Metadata::AGGREGATE_TYPE => $metadata['__aggregate_type'],
                    Metadata::AGGREGATE_ID => $aggregateId::fromString($metadata['__aggregate_id']),
                    Metadata::AGGREGATE_PLAYHEAD => $metadata['__aggregate_playhead'],
                    Metadata::EVENT_ID => new EventId(value: $event->event_id),
                    Metadata::EVENT_TYPE => $metadata['__event_type'],
                    Metadata::RECORDED_AT => $metadata['__recorded_at']
                ],
            ]);
        }

        return new EventStream(aggregateId: $aggregateId, events: $stream);
    }

    /**
     * @inheritDoc
     * @throws TypeException
     */
    public function loadFromPlayhead(AggregateId $aggregateId, int $playhead): EventStream
    {
        $stream = [];

        $query = $this->orm->table(tableName: 'event_store')
            ->select(columns: '*')
            ->where(condition: 'aggregate_id', parameters: (string) $aggregateId)
            ->and__()
            ->where(condition: 'aggregate_playhead = ?', parameters: $playhead);

        $eventStream = iterator_to_array(iterator: $query->find());

        foreach ($eventStream as $event) {
            $metadata = json_decode(json: $event->metadata, associative: true);

            $stream[] = $event->event_classname::fromArray([
                'aggregateId' => $aggregateId::fromString($event->aggregate_id),
                'payload' => json_decode(json: $event->payload, associative: true),
                'metadata' => [
                    Metadata::AGGREGATE_TYPE => $metadata['__aggregate_type'],
                    Metadata::AGGREGATE_ID => $aggregateId::fromString($metadata['__aggregate_id']),
                    Metadata::AGGREGATE_PLAYHEAD => $metadata['__aggregate_playhead'],
                    Metadata::EVENT_ID => new EventId(value: $event->event_id),
                    Metadata::EVENT_TYPE => $metadata['__event_type'],
                    Metadata::RECORDED_AT => $metadata['__recorded_at']
                ],
            ]);
        }

        return new EventStream(aggregateId: $aggregateId, events: $stream);
    }
}
