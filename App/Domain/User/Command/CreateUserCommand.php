<?php

declare(strict_types=1);

namespace App\Domain\User\Command;

use App\Domain\User\ValueObject\Username;
use App\Domain\User\ValueObject\UserToken;
use Codefy\CommandBus\PropertyCommand;
use Qubus\ValueObjects\StringLiteral\StringLiteral;
use Qubus\ValueObjects\Web\EmailAddress;

final class CreateUserCommand extends PropertyCommand
{
    public ?Username $username = null;

    public ?UserToken $token = null;

    public ?StringLiteral $firstName = null;

    public ?StringLiteral $middleName = null;

    public ?StringLiteral $lastName = null;

    public ?EmailAddress $email = null;

    public ?StringLiteral $role = null;

    public ?StringLiteral $password = null;
}
