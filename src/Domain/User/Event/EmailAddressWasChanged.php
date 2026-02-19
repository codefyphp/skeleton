<?php

declare(strict_types=1);

namespace Domain\User\Event;

use Codefy\Domain\EventSourcing\AggregateChanged;
use Codefy\Domain\EventSourcing\DomainEvent;
use Codefy\Domain\Metadata;
use Domain\User\ValueObject\UserId;
use Qubus\Exception\Data\TypeException;
use Qubus\ValueObjects\Web\EmailAddress;

class EmailAddressWasChanged extends AggregateChanged
{
    private UserId $userId;

    private EmailAddress $emailAddress;

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
    public function userId(): UserId
    {
        if (!isset($this->userId)) {
            $this->userId = UserId::fromString(userId: $this->aggregateId()->__toString());
        }

        return $this->userId;
    }

    public function emailAddress(): EmailAddress
    {
        /** @var string $email */
        $email = $this->payload()['email'];

        if (!isset($this->emailAddress)) {
            $this->emailAddress = EmailAddress::fromNative($email);
        }

        return $this->emailAddress;
    }
}
