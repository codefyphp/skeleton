<?php

declare(strict_types=1);

namespace Domain\User\Event;

use Codefy\Domain\EventSourcing\AggregateChanged;
use Codefy\Domain\EventSourcing\DomainEvent;
use Codefy\Domain\Metadata;
use Domain\User\ValueObject\UserId;
use Domain\User\ValueObject\UserToken;
use Qubus\Exception\Data\TypeException;
use Qubus\ValueObjects\StringLiteral\StringLiteral;

class PasswordWasChanged extends AggregateChanged
{
    private UserId $userId;

    private StringLiteral $password;

    private UserToken $token;

    public static function withData(
        UserId $userId,
        StringLiteral $password,
        UserToken $token
    ): PasswordWasChanged|DomainEvent|AggregateChanged {
        $event = self::occur(
            aggregateId: $userId,
            payload: [
                'password' => $password->toNative(),
                'token' => $token->toNative()
            ],
            metadata: [
                Metadata::AGGREGATE_TYPE => 'user'
            ]
        );

        $event->userId = $userId;
        $event->password = $password;
        $event->token = $token;

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

    public function password(): StringLiteral
    {
        /** @var string $password */
        $password = $this->payload()['password'];

        if (!isset($this->password)) {
            $this->password = StringLiteral::fromNative($password);
        }

        return $this->password;
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
}
