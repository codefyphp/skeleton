<?php

declare(strict_types=1);

namespace Domain\User\Service;

use Codefy\CommandBus\Exceptions\CommandCouldNotBeHandledException;
use Codefy\CommandBus\Exceptions\CommandPropertyNotFoundException;
use Codefy\CommandBus\Exceptions\UnresolvableCommandHandlerException;
use Codefy\Framework\Factory\FileLoggerFactory;
use Codefy\Framework\Http\Request\DataTransformerRequest;
use Codefy\Framework\Proxy\Codefy;
use Codefy\QueryBus\UnresolvableQueryHandlerException;
use Domain\User\Command\CreateUserCommand;
use Domain\User\Command\DeleteUserCommand;
use Domain\User\Command\UpdateUserCommand;
use Domain\User\Command\UpdateUserPasswordCommand;
use Domain\User\Query\FindUsersQuery;
use Exception;
use Qubus\Exception\Data\TypeException;
use ReflectionException;

use function Codefy\Framework\Helpers\ask;
use function Codefy\Framework\Helpers\command;
use function Codefy\Framework\Helpers\trans;

final readonly class UserService
{
    /**
     * @throws ReflectionException
     * @throws UnresolvableQueryHandlerException
     */
    public function findAll(): mixed
    {
        return ask(new FindUsersQuery());
    }

    /**
     * @throws ReflectionException
     * @throws TypeException
     * @throws Exception
     */
    public function createUser(DataTransformerRequest $request): void
    {
        try {
            command(
                command: new CreateUserCommand(
                    data: $request->toDtoArray()
                )
            );

            Codefy::$PHP->flash->success(
                message: trans('User added successfully.'),
            );
        } catch (CommandPropertyNotFoundException|
                ReflectionException|
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
     * @throws ReflectionException
     * @throws Exception
     */
    public function updateUser(DataTransformerRequest $request): void
    {
        try {
            command(
                command: new UpdateUserCommand(
                    data: $request->toDtoArray()
                )
            );

            Codefy::$PHP->flash->success(
                message: trans('Profile was updated successfully.'),
            );
        } catch (CommandPropertyNotFoundException|
                ReflectionException|
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
     * @throws ReflectionException
     * @throws TypeException
     * @throws Exception
     */
    public function updatePassword(DataTransformerRequest $request): void
    {
        try {
            command(
                command: new UpdateUserPasswordCommand(
                    data: $request->toDtoArray()
                )
            );

            Codefy::$PHP->flash->success(
                message: trans('Password was updated successfully.'),
            );
        } catch (CommandPropertyNotFoundException|
                ReflectionException|
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
     * @throws ReflectionException
     * @throws TypeException
     * @throws Exception
     */
    public function deleteUser(DataTransformerRequest $request): void
    {
        try {
            command(
                command: new DeleteUserCommand(
                    data: $request->toDtoArray()
                )
            );

            Codefy::$PHP->flash->success(
                message: trans('User was deleted.'),
            );
        } catch (CommandPropertyNotFoundException|
                ReflectionException|
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
     * @throws ReflectionException
     * @throws TypeException
     * @throws Exception
     */
    public function createAccount(DataTransformerRequest $request): bool
    {
        try {
            command(
                command: new CreateUserCommand(
                    data: $request->toDtoArray()
                )
            );

            Codefy::$PHP->flash->success(
                message: trans('Account created successfully.'),
            );

            return true;
        } catch (CommandPropertyNotFoundException|
                ReflectionException|
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
