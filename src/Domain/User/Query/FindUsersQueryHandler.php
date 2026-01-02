<?php

declare(strict_types=1);

namespace Domain\User\Query;

use Codefy\QueryBus\Query;
use Codefy\QueryBus\QueryHandler;
use Qubus\Expressive\Database;

final readonly class FindUsersQueryHandler implements QueryHandler
{
    public function __construct(private Database $db)
    {
    }

    public function handle(FindUsersQuery|Query $query): Database|bool|array
    {
        return $this->db->table('users')
            ->select([
                'user_id',
                'username',
                'first_name',
                'middle_name',
                'last_name',
                'email',
                'role'
            ])
            ->find(function ($data) {
                $array = [];
                foreach ($data as $d) {
                    $array[] = $d;
                }

                return $array;
            });
    }
}
