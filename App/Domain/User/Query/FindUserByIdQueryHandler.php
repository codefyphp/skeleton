<?php

declare(strict_types=1);

namespace App\Domain\User\Query;

use Codefy\QueryBus\Query;
use Codefy\QueryBus\QueryHandler;

use Qubus\Exception\Exception;

use function Codefy\Framework\Helpers\orm;

final class FindUserByIdQueryHandler implements QueryHandler
{
    /**
     * @throws Exception
     */
    public function handle(FindUserByIdQuery|Query $query): \Qubus\Expressive\OrmBuilder|bool
    {
        $orm = orm()->setStructure('user_id');
        return $orm->table('users')->where('user_id = ?', $query->userId)->findOne();
    }
}
