<?php

declare(strict_types=1);

namespace Domain\User\Command;

use Codefy\CommandBus\PropertyCommand;
use Domain\User\ValueObject\UserId;
use Domain\User\ValueObject\UserRole;
use Qubus\ValueObjects\StringLiteral\StringLiteral;
use Qubus\ValueObjects\Web\EmailAddress;

final class UpdateUserCommand extends PropertyCommand
{
    public UserId $userId;

    public StringLiteral $firstName;

    public StringLiteral $middleName;

    public StringLiteral $lastName;

    public EmailAddress $email;

    public UserRole $role;
}
