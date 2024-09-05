<?php

declare(strict_types=1);

namespace App\Domain\User\Query;

use Codefy\QueryBus\Query;
use Codefy\QueryBus\QueryHandler;

use function Codefy\Framework\Helpers\orm;

final class FindUserQueryHandler implements QueryHandler
{
    public function handle(Query $query): mixed
    {
        $orm = orm()->setStructure('user_id');
        return $orm->table('users')
            ->where('email = ?', $query->email)
            ->findOne();
    }
}
