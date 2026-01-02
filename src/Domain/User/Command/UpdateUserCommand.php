<?php

declare(strict_types=1);

namespace Domain\User\Command;

use Codefy\CommandBus\PropertyCommand;
use Domain\User\ValueObject\UserId;
use Qubus\ValueObjects\StringLiteral\StringLiteral;
use Qubus\ValueObjects\Web\EmailAddress;

final class UpdateUserCommand extends PropertyCommand
{
    public ?UserId $userId = null;

    public ?StringLiteral $firstName = null;

    public ?StringLiteral $middleName = null;

    public ?StringLiteral $lastName = null;

    public ?EmailAddress $email = null;

    public ?StringLiteral $role = null;
}
