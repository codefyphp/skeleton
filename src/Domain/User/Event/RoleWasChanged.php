<?php

declare(strict_types=1);

namespace Domain\User\Event;

use Codefy\Domain\EventSourcing\AggregateChanged;
use Codefy\Domain\EventSourcing\DomainEvent;
use Codefy\Domain\Metadata;
use Domain\User\ValueObject\UserId;
use Domain\User\ValueObject\UserRole;
use Qubus\Exception\Data\TypeException;

class RoleWasChanged extends AggregateChanged
{
    private UserId $userId;

    private UserRole $role;

    public static function withData(
        UserId $userId,
        UserRole $role
    ): RoleWasChanged|DomainEvent|AggregateChanged {
        $event = self::occur(
            aggregateId: $userId,
            payload: [
                'role' => $role->toNative(),
            ],
            metadata: [
                Metadata::AGGREGATE_TYPE => 'user'
            ]
        );

        $event->userId = $userId;
        $event->role = $role;

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

    public function role(): UserRole
    {
        /** @var string $role */
        $role = $this->payload()['role'];

        if (!isset($this->role)) {
            $this->role = UserRole::fromNative($role);
        }

        return $this->role;
    }
}
