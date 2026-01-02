<?php

declare(strict_types=1);

namespace Domain\User\Command;

use Codefy\CommandBus\PropertyCommand;
use Domain\User\ValueObject\UserId;
use Domain\User\ValueObject\UserToken;
use Qubus\ValueObjects\StringLiteral\StringLiteral;

final class UpdateUserPasswordCommand extends PropertyCommand
{
    public ?UserId $userId = null;

    public ?StringLiteral $password = null;

    public ?UserToken $token = null;
}
