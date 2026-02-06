<?php

declare(strict_types=1);

namespace Domain\User\Command;

use Codefy\CommandBus\PropertyCommand;
use Domain\User\ValueObject\Username;
use Domain\User\ValueObject\UserRole;
use Domain\User\ValueObject\UserToken;
use Qubus\ValueObjects\StringLiteral\StringLiteral;
use Qubus\ValueObjects\Web\EmailAddress;

final class CreateUserCommand extends PropertyCommand
{
    public Username $username;

    public UserToken $token;

    public StringLiteral $firstName;

    public StringLiteral $middleName;

    public StringLiteral $lastName;

    public EmailAddress $email;

    public UserRole $role;

    public StringLiteral $password;
}
