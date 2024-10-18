<?php

namespace App\Domain\User\Event;

use App\Domain\User\ValueObject\UserId;
use App\Domain\User\ValueObject\UserToken;
use Codefy\Domain\Aggregate\AggregateId;
use Codefy\Domain\EventSourcing\AggregateChanged;
use Codefy\Domain\EventSourcing\DomainEvent;
use Codefy\Domain\Metadata;
use Codefy\Framework\Support\Password;
use Qubus\Exception\Data\TypeException;
use Qubus\ValueObjects\StringLiteral\StringLiteral;

use function Qubus\Support\Helpers\is_null__;

class PasswordWasChanged extends AggregateChanged
{
    private ?UserId $userId = null;

    private ?StringLiteral $password = null;

    private ?UserToken $token = null;

    public static function withData(
        UserId $userId,
        StringLiteral $password,
        UserToken $token
    ): PasswordWasChanged|DomainEvent|AggregateChanged {
        $event = self::occur(
            aggregateId: $userId,
            payload: [
                'password' => Password::hash($password->toNative()),
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
    public function userId(): UserId|AggregateId
    {
        if (is_null__(var: $this->userId)) {
            $this->userId = UserId::fromString(userId: $this->aggregateId()->__toString());
        }

        return $this->userId;
    }

    /**
     * @throws TypeException
     */
    public function password(): StringLiteral
    {
        if (is_null__(var: $this->password)) {
            $this->password = StringLiteral::fromNative($this->payload()['password']);
        }

        return $this->password;
    }

    /**
     * @throws TypeException
     */
    public function token(): UserToken
    {
        if (is_null__(var: $this->token)) {
            $this->token = UserToken::fromString($this->payload()['token']);
        }
        return $this->token;
    }
}
