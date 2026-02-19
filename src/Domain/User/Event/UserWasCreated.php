<?php

declare(strict_types=1);

namespace Domain\User\Event;

use Codefy\Domain\EventSourcing\AggregateChanged;
use Codefy\Domain\EventSourcing\DomainEvent;
use Codefy\Domain\Metadata;
use Domain\User\ValueObject\UserId;
use Domain\User\ValueObject\Username;
use Domain\User\ValueObject\UserRole;
use Domain\User\ValueObject\UserToken;
use Qubus\Exception\Data\TypeException;
use Qubus\Support\DateTime\QubusDateTimeImmutable;
use Qubus\ValueObjects\Person\Name;
use Qubus\ValueObjects\StringLiteral\StringLiteral;
use Qubus\ValueObjects\Web\EmailAddress;

class UserWasCreated extends AggregateChanged
{
    private UserId $userId;

    private Username $username;

    private UserToken $token;

    private Name $name;

    private EmailAddress $emailAddress;

    private UserRole $role;

    private StringLiteral $password;

    private \DateTimeInterface $createdOn;

    public static function withData(
        UserId $userId,
        Username $username,
        UserToken $token,
        Name $name,
        EmailAddress $emailAddress,
        UserRole $role,
        #[\SensitiveParameter] StringLiteral $password,
        \DateTimeInterface $createdOn,
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
                'password' => $password->toNative(),
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
    public function userId(): UserId
    {
        /** @var string $userId */
        $userId = $this->payload()['user_id'];

        if (!isset($this->userId)) {
            $this->userId = UserId::fromString(userId: $userId);
        }

        return $this->userId;
    }

    public function username(): Username
    {
        /** @var string $username */
        $username = $this->payload()['username'];

        if (!isset($this->username)) {
            $this->username = Username::fromNative($username);
        }

        return $this->username;
    }

    /**
     * @throws TypeException
     */
    public function token(): UserToken
    {
        /** @var string $token */
        $token = $this->payload()['token'];

        if (!isset($this->token)) {
            $this->token = UserToken::fromString($token);
        }
        return $this->token;
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

    public function emailAddress(): EmailAddress
    {
        /** @var string $email */
        $email = $this->payload()['email'];

        if (!isset($this->emailAddress)) {
            $this->emailAddress = EmailAddress::fromNative($email);
        }

        return $this->emailAddress;
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

    public function password(): StringLiteral
    {
        /** @var string $password */
        $password = $this->payload()['password'];

        if (!isset($this->password)) {
            $this->password = StringLiteral::fromNative($password);
        }

        return $this->password;
    }

    public function createdOn(): \DateTimeInterface
    {
        if (!isset($this->createdOn)) {
            $this->createdOn = QubusDateTimeImmutable::now();
        }

        return $this->createdOn;
    }
}
