<?php

declare(strict_types=1);

namespace App\Domain\User\Command;

use App\Domain\User\ValueObject\UserId;
use App\Domain\User\ValueObject\UserToken;
use Codefy\CommandBus\PropertyCommand;
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

    public ?StringLiteral $password = null;

    public ?UserToken $token = null;
}
