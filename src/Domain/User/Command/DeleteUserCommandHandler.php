<?php

declare(strict_types=1);

namespace Domain\User\Command;

use Codefy\CommandBus\Command;
use Codefy\CommandBus\CommandHandler;
use Codefy\Domain\Aggregate\AggregateNotFoundException;
use Domain\User\Repository\UserAggregateRepository;
use Domain\User\User;

final readonly class DeleteUserCommandHandler implements CommandHandler
{
    public function __construct(public UserAggregateRepository $aggregateRepository)
    {
    }

    /**
     * @throws AggregateNotFoundException
     * @throws \Exception
     */
    public function handle(DeleteUserCommand|Command $command): void
    {
        /** @var DeleteUserCommand $command */

        /** @var User $user */
        $user = $this->aggregateRepository->loadAggregateRoot(aggregateId: $command->userId);

        $user->deleteUser($command->userId);

        $this->aggregateRepository->saveAggregateRoot(aggregate: $user);
    }
}
