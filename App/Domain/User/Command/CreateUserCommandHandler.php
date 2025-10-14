<?php

declare(strict_types=1);

namespace App\Domain\User\Command;

use App\Domain\User\User;
use App\Domain\User\ValueObject\UserId;
use Codefy\CommandBus\Command;
use Codefy\CommandBus\CommandHandler;
use Codefy\Domain\Aggregate\AggregateRepository;
use Qubus\Exception\Exception;
use Qubus\Support\DateTime\QubusDateTimeImmutable;
use Qubus\ValueObjects\Person\Name;

final readonly class CreateUserCommandHandler implements CommandHandler
{
    public function __construct(public AggregateRepository $aggregateRepository)
    {
    }

    public function handle(CreateUserCommand|Command $command): void
    {
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
