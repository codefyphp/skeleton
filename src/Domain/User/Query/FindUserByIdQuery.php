<?php

declare(strict_types=1);

namespace Domain\User\Query;

use Codefy\CommandBus\PropertyCommand;
use Codefy\QueryBus\Query;
use Domain\User\ValueObject\UserId;

final class FindUserByIdQuery extends PropertyCommand implements Query
{
    public UserId $userId;
}
