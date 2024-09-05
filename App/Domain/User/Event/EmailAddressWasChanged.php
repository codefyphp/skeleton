<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Domain\User\ValueObject\UserId;
use Codefy\Domain\Aggregate\AggregateId;
use Codefy\Domain\EventSourcing\AggregateChanged;
use Codefy\Domain\EventSourcing\DomainEvent;
use Codefy\Domain\Metadata;
use Qubus\Exception\Data\TypeException;
use Qubus\ValueObjects\Web\EmailAddress;

use function Qubus\Support\Helpers\is_null__;

class EmailAddressWasChanged extends AggregateChanged
{
    private ?UserId $userId = null;

    private ?EmailAddress $emailAddress = null;

    public static function withData(
        UserId $userId,
        EmailAddress $emailAddress
    ): EmailAddressWasChanged|DomainEvent|AggregateChanged {
        $event = self::occur(
            aggregateId: $userId,
            payload: [
                'email' => (string) $emailAddress,
            ],
            metadata: [
                Metadata::AGGREGATE_TYPE => 'user'
            ]
        );

        $event->userId = $userId;
        $event->emailAddress = $emailAddress;

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

    public function emailAddress(): EmailAddress
    {
        if (is_null__($this->emailAddress)) {
            $this->emailAddress = EmailAddress::fromNative($this->payload()['email']);
        }

        return $this->emailAddress;
    }
}
