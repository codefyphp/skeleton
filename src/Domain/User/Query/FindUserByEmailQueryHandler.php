<?php

declare(strict_types=1);

namespace Domain\User\Query;

use Codefy\QueryBus\Query;
use Codefy\QueryBus\QueryHandler;
use Qubus\Expressive\Database;

final readonly class FindUserByEmailQueryHandler implements QueryHandler
{
    public function __construct(private Database $db)
    {
    }

    public function handle(FindUserByEmailQuery|Query $query): Database|bool
    {
        $this->db->setStructure(primaryKeyName: 'user_id');

        return $this->db->table('users')
            ->where(condition: 'email = ?', parameters: $query->email)
            ->findOne();
    }
}
