<?php

declare(strict_types=1);

namespace Domain\User\ValueObject;

use Qubus\Support\Assertion;
use Qubus\ValueObjects\StringLiteral\StringLiteral;

class UserRole extends StringLiteral
{
    public function __construct(string $value)
    {
        Assertion::inArray(
            value: $value,
            values: \Domain\User\Enum\UserRole::values(),
            message: sprintf(
                'User role must be one of the following: %s',
                implode(', ', \Domain\User\Enum\UserRole::values())
            )
        );

        parent::__construct($value);
    }
}
