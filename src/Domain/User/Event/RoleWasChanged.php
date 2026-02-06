<?php

declare(strict_types=1);

namespace Domain\User\Event;

use Codefy\Domain\EventSourcing\AggregateChanged;
use Codefy\Domain\EventSourcing\DomainEvent;
use Codefy\Domain\Metadata;
use Domain\User\ValueObject\UserId;
use Domain\User\ValueObject\UserRole;
use Qubus\Exception\Data\TypeException;

use function Qubus\Support\Helpers\is_null__;

class RoleWasChanged extends AggregateChanged
{
    private ?UserId $userId = null;

    private ?UserRole $role = null;

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
        if (is_null__(var: $this->userId)) {
            $this->userId = UserId::fromString(userId: (string) $this->aggregateId());
        }

        return $this->userId;
    }

    public function role(): UserRole
    {
        if (is_null__(var: $this->role)) {
            $this->role = UserRole::fromNative($this->payload()['role']);
        }

        return $this->role;
    }
}
