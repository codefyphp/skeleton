<?php

declare(strict_types=1);

namespace Domain\User\Command;

use Codefy\CommandBus\PropertyCommand;
use Domain\User\ValueObject\UserId;

final class DeleteUserCommand extends PropertyCommand
{
    public UserId $userId;
}
