<?php

declare(strict_types=1);

namespace App\Domain\User\Command;

use App\Domain\User\ValueObject\UserId;
use Codefy\CommandBus\PropertyCommand;
use Qubus\ValueObjects\StringLiteral\StringLiteral;
use Qubus\ValueObjects\Web\EmailAddress;

final class UpdateUserCommand extends PropertyCommand
{
    public UserId $userId;

    public StringLiteral $firstName;

    public StringLiteral $middleName;

    public StringLiteral $lastName;

    public EmailAddress $email;

    public StringLiteral $password;
}
