<?php

declare(strict_types=1);

namespace Domain\User\Query;

use Codefy\CommandBus\PropertyCommand;
use Codefy\QueryBus\Query;
use Domain\User\ValueObject\UserToken;

final class FindUserByTokenQuery extends PropertyCommand implements Query
{
    public UserToken $token;
}
