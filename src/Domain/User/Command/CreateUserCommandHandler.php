<?php

declare(strict_types=1);

namespace Domain\User\Command;

use Codefy\CommandBus\Command;
use Codefy\CommandBus\CommandHandler;
use Domain\User\Repository\UserAggregateRepository;
use Domain\User\User;
use Domain\User\ValueObject\UserId;
use Qubus\Support\DateTime\QubusDateTimeImmutable;
use Qubus\ValueObjects\Person\Name;

final readonly class CreateUserCommandHandler implements CommandHandler
{
    public function __construct(public UserAggregateRepository $aggregateRepository)
    {
    }

    public function handle(CreateUserCommand|Command $command): void
    {
        /** @var CreateUserCommand $command */
        $user = User::createUser(
            userId: new UserId(),
            username: $command->username,
            token: $command->token,
            name: new Name(
                firstName: $command->firstName,
                middleName: $command->middleName,
                lastName: $command->lastName
            ),
            emailAddress: $command->email,
            role: $command->role,
            password: $command->password,
            createdOn: QubusDateTimeImmutable::now()
        );

        $this->aggregateRepository->saveAggregateRoot(aggregate: $user);
    }
}
