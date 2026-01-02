<?php

declare(strict_types=1);

namespace Application\Http\Controllers;

use Codefy\CommandBus\Exceptions\CommandCouldNotBeHandledException;
use Codefy\CommandBus\Exceptions\CommandPropertyNotFoundException;
use Codefy\CommandBus\Exceptions\UnresolvableCommandHandlerException;
use Codefy\Framework\Auth\Gate;
use Codefy\Framework\Factory\FileLoggerFactory;
use Codefy\Framework\Http\BaseController;
use Codefy\Framework\Proxy\Codefy;
use Codefy\Framework\Support\Password;
use Codefy\QueryBus\UnresolvableQueryHandlerException;
use Domain\User\Command\CreateUserCommand;
use Domain\User\Command\UpdateUserCommand;
use Domain\User\Query\FindUsersQuery;
use Domain\User\ValueObject\UserId;
use Domain\User\ValueObject\Username;
use Domain\User\ValueObject\UserToken;
use Psr\Http\Message\ResponseInterface;
use Qubus\Exception\Data\TypeException;
use Qubus\Exception\Exception;
use Qubus\Http\Factories\HtmlResponseFactory;
use Qubus\Http\ServerRequest;
use Qubus\Http\Session\SessionException;
use Qubus\Http\Session\SessionService;
use Qubus\Routing\Exceptions\NamedRouteNotFoundException;
use Qubus\Routing\Exceptions\RouteParamFailedConstraintException;
use Qubus\Routing\Router;
use Qubus\ValueObjects\StringLiteral\StringLiteral;
use Qubus\ValueObjects\Web\EmailAddress;
use Qubus\View\Renderer;
use ReflectionException;

use function array_values;
use function Codefy\Framework\Helpers\ask;
use function Codefy\Framework\Helpers\command;
use function Codefy\Framework\Helpers\config;
use function Codefy\Framework\Helpers\site_url;
use function Codefy\Framework\Helpers\trans;
use function json_encode;

final class AdminController extends BaseController
{
    public function __construct(
        protected SessionService $sessionService,
        protected Router $router,
        protected Gate $user,
        protected Renderer $view
    ) {
        parent::__construct($sessionService, $router, $view);
    }

    /**
     * @throws RouteParamFailedConstraintException
     * @throws UnresolvableQueryHandlerException
     * @throws ReflectionException
     * @throws TypeException
     * @throws NamedRouteNotFoundException
     * @throws CommandPropertyNotFoundException
     * @throws Exception
     */
    public function auth(ServerRequest $request): ResponseInterface
    {
        if (false === $this->user->can(permissionName: 'admin:dashboard')) {
            try {
                return $this->redirect(url: site_url(path: $this->router->url(name: 'admin.login')));
            } catch (NamedRouteNotFoundException|RouteParamFailedConstraintException $e) {
                FileLoggerFactory::getLogger()->error(
                    message: $e->getMessage(),
                    context: ['AdminController' => 'auth']
                );
            }

            return $this->redirect(url: $request->getHeaderLine('HTTP_REFERER'));
        }

        return $this->redirect(url: site_url(path: $this->router->url(name: 'admin.home')));
    }

    /**
     * @throws RouteParamFailedConstraintException
     * @throws UnresolvableQueryHandlerException
     * @throws NamedRouteNotFoundException
     * @throws CommandPropertyNotFoundException
     * @throws ReflectionException
     * @throws SessionException
     * @throws TypeException
     * @throws \Exception
     */
    public function index(ServerRequest $request): ResponseInterface
    {
        if (false === $this->user->can(permissionName: 'admin:dashboard')) {
            Codefy::$PHP->flash->error(
                message: trans('You must be logged in to access the admin area.')
            );
            return $this->redirect(url: site_url(path: $this->router->url(name: 'home')));
        }

        return HtmlResponseFactory::create(
            $this->view->render(template: 'framework::backend/index', data: ['title' => 'Dashboard'])
        );
    }

    /**
     * @throws RouteParamFailedConstraintException
     * @throws UnresolvableQueryHandlerException
     * @throws NamedRouteNotFoundException
     * @throws CommandPropertyNotFoundException
     * @throws ReflectionException
     * @throws SessionException
     * @throws TypeException
     * @throws \Exception
     */
    public function profile(ServerRequest $request): ResponseInterface
    {
        if (false === $this->user->can(permissionName: 'admin:profile')) {
            Codefy::$PHP->flash->error(
                message: trans('You must be logged in to access the admin area.')
            );

            return $this->redirect(url: site_url(path: $this->router->url(name: 'admin.login')));
        }

        return HtmlResponseFactory::create(
            $this->view->render(
                template: 'framework::backend/profile',
                data: [
                    'title' => trans('User Profile'),
                    'user' => $this->user->current(),
                    'roles' => config(key: 'rbac.roles'),
                    'url' => $this->router->url(name: 'admin.update'),
                ]
            )
        );
    }

    /**
     * @throws RouteParamFailedConstraintException
     * @throws UnresolvableQueryHandlerException
     * @throws ReflectionException
     * @throws TypeException
     * @throws NamedRouteNotFoundException
     * @throws CommandPropertyNotFoundException
     * @throws \Exception
     */
    public function login(ServerRequest $request): ResponseInterface
    {
        if (true === $this->user->can(permissionName: 'admin:dashboard')) {
            return $this->redirect($this->router->url(name: 'admin.home'));
        }

        return HtmlResponseFactory::create(
            $this->view->render(
                template: 'framework::backend/login',
                data: [
                    'title' => trans('Login'),
                    'url' => $this->router->url(name: 'admin.auth'),
                ]
            )
        );
    }

    /**
     * @throws RouteParamFailedConstraintException
     * @throws UnresolvableQueryHandlerException
     * @throws NamedRouteNotFoundException
     * @throws CommandPropertyNotFoundException
     * @throws ReflectionException
     * @throws TypeException
     * @throws Exception
     */
    public function logout(ServerRequest $request): ResponseInterface
    {
        if (false === $this->user->can(permissionName: 'admin:dashboard')) {
            Codefy::$PHP->flash->error(
                message: trans('You are already logged out.')
            );
        }

        return $this->redirect(url: site_url(path: $this->router->url(name: 'admin.login')));
    }

    /**
     * @throws RouteParamFailedConstraintException
     * @throws UnresolvableQueryHandlerException
     * @throws ReflectionException
     * @throws TypeException
     * @throws NamedRouteNotFoundException
     * @throws CommandPropertyNotFoundException
     * @throws \Exception
     */
    public function register(ServerRequest $request): ResponseInterface
    {
        if (true === $this->user->can(permissionName: 'admin:dashboard')) {
            return $this->redirect(url: site_url(path: $this->router->url(name: 'admin.home')));
        }

        return HtmlResponseFactory::create(
            $this->view->render(
                template: 'framework::backend/register',
                data: [
                    'title' => trans('Register'),
                    'url' => $this->router->url(name: 'admin.create'),
                ]
            )
        );
    }

    /**
     * @throws RouteParamFailedConstraintException
     * @throws UnresolvableQueryHandlerException
     * @throws NamedRouteNotFoundException
     * @throws CommandPropertyNotFoundException
     * @throws Exception
     * @throws ReflectionException
     * @throws TypeException
     * @throws \Exception
     */
    public function users(ServerRequest $request): ResponseInterface
    {
        if (false === $this->user->can(permissionName: 'admin:dashboard')) {
            Codefy::$PHP->flash->error(
                message: trans('You must be logged in to access the admin area.')
            );

            return $this->redirect(url: site_url(path: $this->router->url(name: 'admin.login')));
        }

        $users = ask(new FindUsersQuery());

        return HtmlResponseFactory::create(
            $this->view->render(
                template: 'framework::backend/users',
                data: [
                    'title' => trans('User Management'),
                    'users' => json_encode(array_values($users)),
                    'roles' => config(key: 'rbac.roles'),
                ]
            )
        );
    }

    /**
     * @throws RouteParamFailedConstraintException
     * @throws NamedRouteNotFoundException
     * @throws CommandPropertyNotFoundException
     * @throws TypeException
     * @throws Exception
     * @throws ReflectionException
     */
    public function create(ServerRequest $request): ResponseInterface
    {
        /*if(false === $this->user->can(permissionName: 'admin:dashboard')) {
            Codefy::$PHP->flash->error(
                message: 'You must be logged in to perform that action.'
            );

            return $this->redirect(site_url(path: $this->router->url(name: 'admin.login')));
            exit();
        }*/

        $command = new CreateUserCommand(data: [
            'username' => new Username(value: $request->get('username')),
            'token' => new UserToken(),
            'firstName' => new StringLiteral(value: $request->get('first_name')),
            'middleName' => new StringLiteral(value: ''),
            'lastName' => new StringLiteral(value: $request->get('last_name')),
            'email' => new EmailAddress(value: $request->get('email')),
            'role' => new StringLiteral(value: $request->get('role')),
            'password' => new StringLiteral(value: Password::hash($request->get('password'))),
        ]);

        try {
            command(command: $command);

            Codefy::$PHP->flash->success(
                message: trans('User added successfully.'),
            );

            return $this->redirect(url: site_url(path: $this->router->url(name: 'admin.login')));
        } catch (CommandCouldNotBeHandledException|UnresolvableCommandHandlerException|ReflectionException $e) {
            Codefy::$PHP->flash->error(
                message: trans('Could not execute create user command.'),
            );

            FileLoggerFactory::getLogger()->error(message: $e->getMessage(), context: ['AdminController' => 'create']);

            return $this->redirect(url: site_url(path: $this->router->url(name: 'admin.create')));
        }
    }

    /**
     * @throws RouteParamFailedConstraintException
     * @throws UnresolvableQueryHandlerException
     * @throws CommandPropertyNotFoundException
     * @throws NamedRouteNotFoundException
     * @throws ReflectionException
     * @throws TypeException
     * @throws Exception
     */
    public function update(ServerRequest $request): ResponseInterface
    {
        if (false === $this->user->can(permissionName: 'admin:profile')) {
            Codefy::$PHP->flash->error(
                message: trans('You must be logged in to perform that action.')
            );

            return $this->redirect(url: site_url(path: $this->router->url(name: 'admin.login')));
        }

        if (empty($request->get('password'))) {
            $password = '';
        } else {
            $password = Password::hash($request->get('password'));
        }

        $command = new UpdateUserCommand(data: [
            'userId' => UserId::fromString($request->get('user_id')),
            'firstName' => new StringLiteral(value: $request->get('first_name')),
            'middleName' => empty($request->get('middle_name')) ?
                new StringLiteral(value: '') :
                new StringLiteral(value: $request->get('middle_name')),
            'lastName' => new StringLiteral(value: $request->get('last_name')),
            'email' => new EmailAddress(value: $request->get('email')),
            'role' => new StringLiteral(value: $request->get('role')),
            'password' => new StringLiteral(value: $password),
            'token' => new UserToken(),
        ]);

        try {
            command(command: $command);

            Codefy::$PHP->flash->success(
                message: trans('Profile was updated successfully.'),
            );

            return $this->redirect(url: $this->router->url(name: 'admin.profile'));
        } catch (CommandCouldNotBeHandledException|UnresolvableCommandHandlerException|ReflectionException $e) {
            Codefy::$PHP->flash->error(
                message: trans('Could not update the profile.'),
            );

            FileLoggerFactory::getLogger()->error(message: $e->getMessage(), context: ['AdminController' => 'update']);

            return $this->redirect(url: site_url(path: $this->router->url(name: 'admin.profile')));
        }
    }

    /**
     * @throws RouteParamFailedConstraintException
     * @throws UnresolvableQueryHandlerException
     * @throws CommandPropertyNotFoundException
     * @throws NamedRouteNotFoundException
     * @throws ReflectionException
     * @throws TypeException
     * @throws Exception
     */
    public function edit(ServerRequest $request): ResponseInterface
    {
        if (false === $this->user->can(permissionName: 'admin:edit:user')) {
            Codefy::$PHP->flash->error(
                message: trans('You are not allowed to perform that action.')
            );

            return $this->redirect(url: site_url(path: $this->router->url(name: 'admin.login')));
        }

        $command = new UpdateUserCommand(data: [
            'userId' => UserId::fromString($request->get('user_id')),
            'firstName' => new StringLiteral(value: $request->get('first_name')),
            'middleName' => empty($request->get('middle_name')) ?
                    new StringLiteral(value: '') :
                    new StringLiteral(value: $request->get('middle_name')),
            'lastName' => new StringLiteral(value: $request->get('last_name')),
            'email' => new EmailAddress(value: $request->get('email')),
            'role' => new StringLiteral(value: $request->get('role')),
            'token' => new UserToken(),
        ]);

        try {
            command(command: $command);

            Codefy::$PHP->flash->success(
                message: trans('User was updated successfully.'),
            );

            return $this->redirect(url: $this->router->url(name: 'admin.users'));
        } catch (CommandCouldNotBeHandledException|UnresolvableCommandHandlerException|ReflectionException $e) {
            Codefy::$PHP->flash->error(
                message: trans('Could not update the user.'),
            );

            FileLoggerFactory::getLogger()->error(message: $e->getMessage(), context: ['AdminController' => 'edit']);

            return $this->redirect(url: site_url(path: $this->router->url(name: 'admin.users')));
        }
    }
}
