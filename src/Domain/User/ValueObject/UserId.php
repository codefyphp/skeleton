<?php

declare(strict_types=1);

namespace Domain\User\ValueObject;

use Codefy\Domain\Aggregate\AggregateId;
use Domain\User\User;
use Qubus\Exception\Data\TypeException;
use Qubus\ValueObjects\Identity\Ulid;

class UserId extends Ulid implements AggregateId
{

    public function aggregateClassName(): string
    {
        return User::className();
    }

    /**
     * @throws TypeException
     */
    public static function fromString(string $userId): UserId
    {
        return new self(value: $userId);
    }
}
