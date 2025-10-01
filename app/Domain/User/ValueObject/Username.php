<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use Qubus\Support\Assertion;
use Qubus\ValueObjects\StringLiteral\StringLiteral;

class Username extends StringLiteral
{
    public function __construct(string $value)
    {
        Assertion::regex(
            value: $value,
            pattern: '/^[a-z_-]{3,20}$/',
            message: 'Username must be alpha only string that may include "_" and "–", 
            having a length of 3 to 20 characters'
        );

        $this->value = $value;
    }
}
