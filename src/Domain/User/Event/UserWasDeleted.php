<?php

declare(strict_types=1);

namespace Domain\User\Event;

use Codefy\Domain\Aggregate\AggregateId;
use Codefy\Domain\EventSourcing\AggregateChanged;
use Codefy\Domain\EventSourcing\DomainEvent;
use Codefy\Domain\Metadata;
use Domain\User\ValueObject\UserId;
use Qubus\Exception\Data\TypeException;

use function Qubus\Support\Helpers\is_null__;

final class UserWasDeleted extends AggregateChanged
{
    private ?UserId $userId = null;

    public static function withData(
        UserId $userId,
    ): UserWasDeleted|DomainEvent|AggregateChanged {
        $event = self::occur(
            aggregateId: $userId,
            payload: [],
            metadata: [
                Metadata::AGGREGATE_TYPE => 'user'
            ]
        );

        $event->userId = $userId;

        return $event;
    }

    /**
     * @throws TypeException
     */
    public function userId(): UserId|AggregateId
    {
        if (is_null__($this->userId)) {
            $this->userId = UserId::fromString(userId: $this->aggregateId()->__toString());
        }

        return $this->userId;
    }
}
