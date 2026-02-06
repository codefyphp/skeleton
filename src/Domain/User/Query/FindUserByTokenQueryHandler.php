<?php

declare(strict_types=1);

namespace Domain\User\Query;

use Codefy\QueryBus\Query;
use Codefy\QueryBus\QueryHandler;
use Qubus\Expressive\Database;

final readonly class FindUserByTokenQueryHandler implements QueryHandler
{
    public function __construct(private Database $db)
    {
    }

    public function handle(FindUserByTokenQuery|Query $query): Database|bool
    {
        /** @var FindUserByTokenQuery $query */

        $this->db->setStructure(primaryKeyName: 'user_id');

        return $this->db->table(tableName: 'users')
            ->select(columns: ['user_id','username','first_name','middle_name','last_name','email','role'])
            ->where(condition: 'token = ?', parameters: $query->token->toNative())
            ->findOne();
    }
}
