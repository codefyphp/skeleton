<?php

declare(strict_types=1);

namespace Domain\User\Query;

use Codefy\CommandBus\PropertyCommand;
use Codefy\QueryBus\Query;

final class FindUserByEmailQuery extends PropertyCommand implements Query
{
    public ?string $email;
}
