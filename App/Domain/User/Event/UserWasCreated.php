<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Domain\User\ValueObject\UserId;
use App\Domain\User\ValueObject\Username;
use App\Domain\User\ValueObject\UserToken;
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

    private ?Username $username = null;

    private ?UserToken $token = null;

    private ?Name $name = null;

    private ?EmailAddress $emailAddress = null;

    private ?StringLiteral $role = null;

    private ?StringLiteral $password = null;

    private ?DateTimeInterface $createdOn = null;

    public static function withData(
        UserId $userId,
        Username $username,
        UserToken $token,
        Name $name,
        EmailAddress $emailAddress,
        StringLiteral $role,
        #[SensitiveParameter] StringLiteral $password,
        DateTimeInterface $createdOn,
    ): UserWasCreated|DomainEvent|AggregateChanged {
        $event = self::occur(
            aggregateId: $userId,
            payload: [
                'user_id' => $userId->toNative(),
                'username' => $username->toNative(),
                'token' => $token->toNative(),
                'first_name' => $name->getFirstName()->toNative(),
                'middle_name' => $name->getMiddleName()->toNative(),
                'last_name' => $name->getLastName()->toNative(),
                'email' => $emailAddress->toNative(),
                'role' => $role->toNative(),
                'password' => Password::hash($password->toNative()),
                'created_on' => $createdOn,
            ],
            metadata: [
                Metadata::AGGREGATE_TYPE => 'user'
            ]
        );

        $event->userId = $userId;
        $event->username = $username;
        $event->token = $token;
        $event->name = $name;
        $event->emailAddress = $emailAddress;
        $event->role = $role;
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
    public function username(): Username
    {
        if (is_null__($this->username)) {
            $this->username = Username::fromNative($this->payload()['username']);
        }

        return $this->username;
    }

    /**
     * @throws TypeException
     */
    public function token(): UserToken
    {
        if (is_null__($this->token)) {
            $this->token = UserToken::fromString($this->payload()['token']);
        }
        return $this->token;
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
    public function role(): StringLiteral
    {
        if (is_null__($this->role)) {
            $this->role = StringLiteral::fromNative($this->payload()['role']);
        }

        return $this->role;
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
        if (is_null__($this->createdOn)) {
            $this->createdOn = QubusDateTimeImmutable::now();
        }

        return $this->createdOn;
    }
}
