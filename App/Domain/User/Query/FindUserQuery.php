<?php

declare(strict_types=1);

namespace App\Domain\User\Query;

use Codefy\CommandBus\PropertyCommand;
use Codefy\QueryBus\Query;

final class FindUserQuery extends PropertyCommand implements Query
{
    public ?string $email;
}
