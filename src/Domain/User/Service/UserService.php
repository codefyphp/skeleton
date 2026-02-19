<?php

declare(strict_types=1);

namespace Domain\User\Service;

use Codefy\CommandBus\Exceptions\CommandCouldNotBeHandledException;
use Codefy\CommandBus\Exceptions\CommandPropertyNotFoundException;
use Codefy\CommandBus\Exceptions\UnresolvableCommandHandlerException;
use Codefy\Framework\Factory\FileLoggerFactory;
use Codefy\Framework\Proxy\Codefy;
use Codefy\QueryBus\UnresolvableQueryHandlerException;
use Domain\User\Command\CreateUserCommand;
use Domain\User\Command\DeleteUserCommand;
use Domain\User\Command\UpdateUserCommand;
use Domain\User\Command\UpdateUserPasswordCommand;
use Domain\User\Query\FindUsersQuery;
use Domain\User\Validator\DestroyUserValidator;
use Domain\User\Validator\StoreUserValidator;
use Domain\User\Validator\UpdateUserPasswordValidator;
use Domain\User\Validator\UpdateUserValidator;

use function Codefy\Framework\Helpers\ask;
use function Codefy\Framework\Helpers\command;
use function Codefy\Framework\Helpers\trans;

final readonly class UserService
{
    /**
     * @return array<array{
     *      'user_id':string,
     *      'username':string,
     *      'first_name':string,
     *      'middle_name':string,
     *      'last_name':string,
     *      'email':string,
     *      'role':string
     *  }> | array<mixed>
     * @throws \ReflectionException
     * @throws UnresolvableQueryHandlerException
     */
    public function findAll(): array
    {
        $results = ask(new FindUsersQuery());
        if (is_array($results)) {
            return $results;
        }
        return [];
    }

    /**
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function createUser(StoreUserValidator $data): void
    {
        try {
            command(
                command: new CreateUserCommand(
                    data: $data->toDtoArray()
                )
            );

            Codefy::$PHP->flash->success(
                message: trans('User added successfully.'),
            );
        } catch (CommandPropertyNotFoundException|
                \ReflectionException|
                UnresolvableCommandHandlerException|
                CommandCouldNotBeHandledException $e
        ) {
            Codefy::$PHP->flash->error(
                message: trans('Could not create user. Please try again later.'),
            );
            FileLoggerFactory::getLogger()->error(message: $e->getMessage(), context: ['AdminController' => 'create']);
        }
    }

    /**
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function updateUser(UpdateUserValidator $data): void
    {
        try {
            command(
                command: new UpdateUserCommand(
                    data: $data->toDtoArray()
                )
            );

            Codefy::$PHP->flash->success(
                message: trans('Profile was updated successfully.'),
            );
        } catch (CommandPropertyNotFoundException|
                \ReflectionException|
                UnresolvableCommandHandlerException|
                CommandCouldNotBeHandledException $e
        ) {
            Codefy::$PHP->flash->error(
                message: trans('Could not update the profile.'),
            );
            FileLoggerFactory::getLogger()->error(
                message: $e->getMessage(),
                context: ['UserService' => 'updateUser()']
            );
        }
    }

    /**
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function updatePassword(UpdateUserPasswordValidator $data): void
    {
        try {
            command(
                command: new UpdateUserPasswordCommand(
                    data: $data->toDtoArray()
                )
            );

            Codefy::$PHP->flash->success(
                message: trans('Password was updated successfully.'),
            );
        } catch (CommandPropertyNotFoundException|
                \ReflectionException|
                UnresolvableCommandHandlerException|
                CommandCouldNotBeHandledException $e
        ) {
            Codefy::$PHP->flash->error(
                message: trans('Password could not be updated. Please try again later.'),
            );
            FileLoggerFactory::getLogger()->error(
                message: $e->getMessage(),
                context: ['UserService' => 'updatePassword()']
            );
        }
    }

    /**
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function deleteUser(DestroyUserValidator $data): void
    {
        try {
            command(
                command: new DeleteUserCommand(
                    data: $data->toDtoArray()
                )
            );

            Codefy::$PHP->flash->success(
                message: trans('User was deleted.'),
            );
        } catch (CommandPropertyNotFoundException|
                \ReflectionException|
                UnresolvableCommandHandlerException|
                CommandCouldNotBeHandledException $e
        ) {
            Codefy::$PHP->flash->error(
                message: trans('User could not be deleted. Please try again later.'),
            );
            FileLoggerFactory::getLogger()->error(
                message: $e->getMessage(),
                context: ['UserService' => 'deleteUser()']
            );
        }
    }

    /**
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function createAccount(StoreUserValidator $data): bool
    {
        try {
            command(
                command: new CreateUserCommand(
                    data: $data->toDtoArray()
                )
            );

            Codefy::$PHP->flash->success(
                message: trans('Account created successfully.'),
            );

            return true;
        } catch (CommandPropertyNotFoundException|
                \ReflectionException|
                UnresolvableCommandHandlerException|
                CommandCouldNotBeHandledException $e
        ) {
            Codefy::$PHP->flash->error(
                message: trans('Could not create account. Please try again later.'),
            );
            FileLoggerFactory::getLogger()->error(
                message: $e->getMessage(),
                context: ['UserService' => 'createAccount()']
            );
        }

        return false;
    }
}
