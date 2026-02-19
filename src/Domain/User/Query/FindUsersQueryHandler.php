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

    /**
     * @param FindUsersQuery|Query $query
     * @return mixed
     */
    public function handle(FindUsersQuery|Query $query): mixed
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
                /** @var array<array{
                 *     'user_id':string,
                 *     'username':string,
                 *     'first_name':string,
                 *     'middle_name':string,
                 *     'last_name':string,
                 *     'email':string,
                 *     'role':string
                 * }> $data
                 */
                foreach ($data as $d) {
                    $array[] = $d;
                }

                return $array;
            });
    }
}
