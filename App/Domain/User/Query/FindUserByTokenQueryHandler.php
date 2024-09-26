<?php

declare(strict_types=1);

namespace App\Domain\User\Query;

use Codefy\QueryBus\Query;
use Codefy\QueryBus\QueryHandler;
use Qubus\Exception\Exception;

use function Codefy\Framework\Helpers\orm;

final class FindUserByTokenQueryHandler implements QueryHandler
{
    /**
     * @throws Exception
     */
    public function handle(Query $query): mixed
    {
        $orm = orm();
        return $orm->table('users')
                ->select(['user_id','username','first_name','middle_name','last_name','email','role'])
                ->where('token = ?', $query->token)
                ->findOne();
    }
}
