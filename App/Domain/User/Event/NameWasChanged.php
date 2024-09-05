<?php

namespace App\Domain\User\Event;

use App\Domain\User\ValueObject\UserId;
use Codefy\Domain\Aggregate\AggregateId;
use Codefy\Domain\EventSourcing\AggregateChanged;
use Codefy\Domain\EventSourcing\DomainEvent;
use Codefy\Domain\Metadata;
use Qubus\Exception\Data\TypeException;
use Qubus\ValueObjects\Person\Name;

use function Qubus\Support\Helpers\is_null__;

class NameWasChanged extends AggregateChanged
{
    private ?UserId $userId = null;

    private ?Name $name = null;

    public static function withData(
        UserId $userId,
        Name $name
    ): NameWasChanged|DomainEvent|AggregateChanged {
        $event = self::occur(
            aggregateId: $userId,
            payload: [
                'first_name' => (string) $name->getFirstName()->toNative(),
                'middle_name' => (string) $name->getMiddleName()->toNative(),
                'last_name' => (string) $name->getLastName()->toNative(),
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
    public function userId(): UserId|AggregateId
    {
        if (is_null__($this->userId)) {
            $this->userId = UserId::fromString(userId: $this->aggregateId()->__toString());
        }

        return $this->userId;
    }

    /**
     * @throws TypeException
     */
    public function name(): Name
    {
        if (is_null__($this->name)) {
            $this->name = Name::fromNative($this->payload()['first_name'], $this->payload()['middle_name'], $this->payload()['last_name']);
        }

        return $this->name;
    }

    public function firstName(): string
    {
        $firstName = $this->name->getFirstName()->toNative();
        if (!empty($firstName) && !is_null__($firstName)) {
            return $firstName;
        }

        return '';
    }

    public function middleName(): string
    {
        $middleName = $this->name->getMiddleName()->toNative();
        if (!empty($middleName) && !is_null__($middleName)) {
            return $middleName;
        }

        return '';
    }

    public function lastName(): string
    {
        $lastName = $this->name->getLastName()->toNative();
        if (!empty($lastName) || !is_null__($lastName)) {
            return $lastName;
        }

        return '';
    }
}
