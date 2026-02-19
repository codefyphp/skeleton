<?php

declare(strict_types=1);

namespace Domain\User\Event;

use Codefy\Domain\EventSourcing\AggregateChanged;
use Codefy\Domain\EventSourcing\DomainEvent;
use Codefy\Domain\Metadata;
use Domain\User\ValueObject\UserId;
use Qubus\Exception\Data\TypeException;
use Qubus\ValueObjects\Person\Name;

class NameWasChanged extends AggregateChanged
{
    private UserId $userId;

    private Name $name;

    public static function withData(
        UserId $userId,
        Name $name
    ): NameWasChanged|DomainEvent|AggregateChanged {
        $event = self::occur(
            aggregateId: $userId,
            payload: [
                'first_name' => $name->getFirstName()->toNative(),
                'middle_name' => $name->getMiddleName()->toNative(),
                'last_name' => $name->getLastName()->toNative(),
            ],
            metadata: [
                Metadata::AGGREGATE_TYPE => 'user'
            ]
        );

        $event->userId = $userId;
        $event->name = $name;

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

    public function name(): Name
    {
        /** @var array<string> $name */
        $name = [$this->payload()['first_name'], $this->payload()['middle_name'], $this->payload()['last_name']];

        if (!isset($this->name)) {
            $this->name = Name::fromNative($name[0], $name[1], $name[2]);
        }

        return $this->name;
    }

    public function firstName(): string
    {
        $firstName = $this->name->getFirstName();
        if (!$firstName->isEmpty()) {
            return $firstName->toNative();
        }

        return '';
    }

    public function middleName(): string
    {
        $middleName = $this->name->getMiddleName();
        if (!$middleName->isEmpty()) {
            return $middleName->toNative();
        }

        return '';
    }

    public function lastName(): string
    {
        $lastName = $this->name->getLastName();
        if (!$lastName->isEmpty()) {
            return $lastName->toNative();
        }

        return '';
    }
}
