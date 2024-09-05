<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\User\Event\EmailAddressWasChanged;
use App\Domain\User\Event\NameWasChanged;
use App\Domain\User\Event\PasswordWasChanged;
use App\Domain\User\Event\UserWasCreated;
use App\Domain\User\ValueObject\UserId;
use App\Domain\User\ValueObject\UserToken;
use Codefy\Domain\Aggregate\AggregateId;
use Codefy\Domain\Aggregate\AggregateRoot;
use Codefy\Domain\Aggregate\EventSourcedAggregate;
use Codefy\Framework\Support\Password;
use DateTimeInterface;
use Exception;
use Qubus\Exception\Data\TypeException;
use Qubus\ValueObjects\Person\Name;
use Qubus\ValueObjects\StringLiteral\StringLiteral;
use Qubus\ValueObjects\Web\EmailAddress;

final class User extends EventSourcedAggregate implements AggregateRoot
{
    private ?UserId $userId = null;

    private ?StringLiteral $username = null;

    private ?Name $name = null;

    private ?EmailAddress $emailAddress = null;

    private ?StringLiteral $password = null;

    public static function createUser(
        UserId $userId,
        StringLiteral $username,
        Name $name,
        EmailAddress $emailAddress,
        StringLiteral $password,
        DateTimeInterface $createdOn,
    ): User {
        $user = self::root(aggregateId: $userId);

        $user->recordApplyAndPublishThat(
            event: UserWasCreated::withData(
                userId: $userId,
                username: $username,
                name: $name,
                emailAddress: $emailAddress,
                password: $password,
                createdOn: $createdOn,
            )
        );

        return $user;
    }

    public static function fromNative(UserId $userId): User
    {
        return self::root(aggregateId: $userId);
    }

    public function userId(): UserId|AggregateId
    {
        return $this->userId;
    }

    public function username(): StringLiteral
    {
        return $this->username;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function emailAddress(): EmailAddress
    {
        return $this->emailAddress;
    }

    public function password(): StringLiteral
    {
        return $this->password;
    }

    /**
     * Gets the confirmation token.
     *
     * @return UserToken|null
     */
    /*public function confirmationToken(): ?UserToken
    {
        return $this->confirmationToken instanceof UserToken
        && null === $this->confirmationToken->token()
            ? null
            : $this->confirmationToken;
    }*/

    /**
     * @throws Exception
     */
    public function changeEmailAddress(EmailAddress $emailAddress): void
    {
        if ($emailAddress->isEmpty()) {
            throw new Exception(message: 'Email address cannot be null.');
        }
        if ($emailAddress->__toString() === $this->emailAddress->__toString()) {
            return;
        }
        $this->recordApplyAndPublishThat(
            event: EmailAddressWasChanged::withData(userId: $this->userId, emailAddress: $emailAddress)
        );
    }

    /**
     * @throws Exception
     */
    public function changeName(Name $name): void
    {
        if (empty($name->getFirstName()) && empty($name->getLastName())) {
            throw new Exception(message: 'Name cannot be null.');
        }
        if ($name->__toString() === $this->name->__toString()) {
            return;
        }
        $this->recordApplyAndPublishThat(
            event: NameWasChanged::withData(userId: $this->userId, name: $name)
        );
    }

    public function changePassword(StringLiteral $password): void
    {
        if ($password->isEmpty()) {
            throw new Exception(message: 'Password cannot be null.');
        }
        if (Password::hash($password->__toString()) === Password::hash($this->password->__toString())) {
            return;
        }
        $this->recordApplyAndPublishThat(
            event: PasswordWasChanged::withData(userId: $this->userId, password: $password)
        );
    }

    /**
     * @throws TypeException
     */
    public function whenUserWasCreated(UserWasCreated $event): void
    {
        $this->userId = $event->userId();
        $this->name = $event->name();
        $this->emailAddress = $event->emailAddress();
        $this->password = $event->password();
    }

    /**
     * @throws TypeException
     */
    public function whenEmailAddressWasChanged(EmailAddressWasChanged $event): void
    {
        $this->userId = $event->userId();
        $this->emailAddress = $event->emailAddress();
    }

    /**
     * @throws TypeException
     */
    public function whenNameWasChanged(NameWasChanged $event): void
    {
        $this->userId = $event->userId();
        $this->name = $event->name();
    }


    public function whenPasswordWasChanged(PasswordWasChanged $event): void
    {
        $this->userId = $event->userId();
        $this->password = $event->password();
    }
}
