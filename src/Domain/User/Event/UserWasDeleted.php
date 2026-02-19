<?php

declare(strict_types=1);

namespace Domain\User\Event;

use Codefy\Domain\EventSourcing\AggregateChanged;
use Codefy\Domain\EventSourcing\DomainEvent;
use Codefy\Domain\Metadata;
use Domain\User\ValueObject\UserId;
use Qubus\Exception\Data\TypeException;

final class UserWasDeleted extends AggregateChanged
{
    private UserId $userId;

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
    public function userId(): UserId
    {
        if (!isset($this->userId)) {
            $this->userId = UserId::fromString(userId: (string) $this->aggregateId());
        }

        return $this->userId;
    }
}
