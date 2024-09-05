<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Domain\User\ValueObject\UserId;
use Codefy\Domain\Aggregate\AggregateId;
use Codefy\Domain\EventSourcing\AggregateChanged;
use Codefy\Domain\EventSourcing\DomainEvent;
use Codefy\Domain\Metadata;
use Codefy\Framework\Support\Password;
use DateTimeInterface;
use Qubus\Exception\Data\TypeException;
use Qubus\Support\DateTime\QubusDateTimeImmutable;
use Qubus\ValueObjects\Person\Name;
use Qubus\ValueObjects\StringLiteral\StringLiteral;
use Qubus\ValueObjects\Web\EmailAddress;
use SensitiveParameter;

use function Qubus\Support\Helpers\is_null__;

class UserWasCreated extends AggregateChanged
{
    private ?UserId $userId = null;

    private ?StringLiteral $username = null;

    private ?Name $name = null;

    private ?EmailAddress $emailAddress = null;

    private ?StringLiteral $password = null;

    private ?DateTimeInterface $createdOn = null;

    public static function withData(
        UserId $userId,
        StringLiteral $username,
        Name $name,
        EmailAddress $emailAddress,
        #[SensitiveParameter] StringLiteral $password,
        DateTimeInterface $createdOn,
    ): UserWasCreated|DomainEvent|AggregateChanged {
        $event = self::occur(
            aggregateId: $userId,
            payload: [
                'user_id' => $userId->toNative(),
                'username' => $username->toNative(),
                'first_name' => $name->getFirstName()->toNative(),
                'middle_name' => $name->getMiddleName()->toNative(),
                'last_name' => $name->getLastName()->toNative(),
                'email' => $emailAddress->toNative(),
                'password' => Password::hash($password->toNative()),
                'created_on' => $createdOn,
            ],
            metadata: [
                Metadata::AGGREGATE_TYPE => 'user'
            ]
        );

        $event->userId = $userId;
        $event->username = $username;
        $event->name = $name;
        $event->emailAddress = $emailAddress;
        $event->password = $password;
        $event->createdOn = $createdOn;

        return $event;
    }

    /**
     * @throws TypeException
     */
    public function userId(): UserId|AggregateId
    {
        if (is_null__($this->userId)) {
            $this->userId = UserId::fromString(userId: $this->payload()['user_id']);
        }

        return $this->userId;
    }

    /**
     * @throws TypeException
     */
    public function username(): StringLiteral
    {
        if (is_null__($this->username)) {
            $this->username = StringLiteral::fromNative($this->payload()['username']);
        }

        return $this->username;
    }

    /**
     * @throws TypeException
     */
    public function name(): Name
    {
        if (is_null__($this->name)) {
            $this->name = Name::fromNative(
                $this->payload()['first_name'],
                $this->payload()['middle_name'],
                $this->payload()['last_name']
            );
        }

        return $this->name;
    }

    public function emailAddress(): EmailAddress
    {
        if (is_null__($this->emailAddress)) {
            $this->emailAddress = EmailAddress::fromNative($this->payload()['email']);
        }

        return $this->emailAddress;
    }

    /**
     * @throws TypeException
     */
    public function password(): StringLiteral
    {
        if (is_null__($this->password)) {
            $this->password = StringLiteral::fromNative($this->payload()['password']);
        }

        return $this->password;
    }

    public function createdOn(): DateTimeInterface
    {
        if(is_null__($this->createdOn)) {
            $this->createdOn = QubusDateTimeImmutable::now();
        }

        return $this->createdOn;
    }
}
