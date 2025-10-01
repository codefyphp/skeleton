<?php

declare(strict_types=1);

namespace App\Domain\User\Query;

use Codefy\QueryBus\Query;
use Codefy\QueryBus\QueryHandler;
use Qubus\Expressive\Database;

final readonly class FindUserQueryHandler implements QueryHandler
{
    public function __construct(private Database $db)
    {
    }

    public function handle(FindUserQuery|Query $query): Database|bool
    {
        $this->db->setStructure(primaryKeyName: 'user_id');

        return $this->db->table('users')
            ->where(condition: 'email = ?', parameters: $query->email)
            ->findOne();
    }
}
