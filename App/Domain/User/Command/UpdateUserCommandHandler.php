<?php

declare(strict_types=1);

namespace App\Domain\User\Command;

use App\Domain\User\User;
use Codefy\CommandBus\Command;
use Codefy\CommandBus\CommandHandler;
use Codefy\Domain\Aggregate\AggregateRepository;
use Qubus\ValueObjects\Person\Name;

final readonly class UpdateUserCommandHandler implements CommandHandler
{
    public function __construct(public AggregateRepository $aggregateRepository)
    {
    }

    public function handle(Command $command): void
    {
        /** @var User $user */
        $user = $this->aggregateRepository->loadAggregateRoot(aggregateId: $command->userId);

        $user->changeEmailAddress(emailAddress: $command->email);

        $user->changeName(name: new Name(firstName: $command->firstName, middleName: $command->middleName, lastName: $command->lastName));

        if(!empty($command->password->__toString())) {
            $user->changePassword(password: $command->password);
        }

        $this->aggregateRepository->saveAggregateRoot(aggregate: $user);
    }
}
