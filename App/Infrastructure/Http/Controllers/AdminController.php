<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Domain\User\Command\CreateUserCommand;
use App\Domain\User\Command\UpdateUserCommand;
use App\Domain\User\ValueObject\UserId;
use App\Domain\User\ValueObject\Username;
use App\Domain\User\ValueObject\UserToken;
use App\Infrastructure\Services\UserAuth;
use Codefy\CommandBus\Exceptions\CommandCouldNotBeHandledException;
use Codefy\CommandBus\Exceptions\CommandPropertyNotFoundException;
use Codefy\CommandBus\Exceptions\UnresolvableCommandHandlerException;
use Codefy\Framework\Factory\FileLoggerFactory;
use Codefy\Framework\Http\BaseController;
use Codefy\Framework\Proxy\Codefy;
use Codefy\Framework\Support\Password;
use Codefy\QueryBus\UnresolvableQueryHandlerException;
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

use function Codefy\Framework\Helpers\command;
use function Codefy\Framework\Helpers\config;
use function Qubus\Security\Helpers\t__;

final class AdminController extends BaseController
{
    public function __construct(
        protected SessionService $sessionService,
        protected Router $router,
        protected UserAuth $user,
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
     */
    public function auth(ServerRequest $request): ResponseInterface
    {
        if (false === $this->user->can(permissionName: 'admin:dashboard', request: $request)) {
            try {
                return $this->redirect(url: $this->router->url(name: 'admin.login'));
            } catch (NamedRouteNotFoundException|RouteParamFailedConstraintException $e) {
                FileLoggerFactory::getLogger()->error(
                    message: $e->getMessage(),
                    context: ['AdminController' => 'auth']
                );
            }

            return $this->redirect(url: $request->getHeaderLine('HTTP_REFERER'));
        }

        return $this->redirect(url: $this->router->url(name: 'admin.home'));
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
    public function index(ServerRequest $request): ?ResponseInterface
    {
        if (false === $this->user->can(permissionName: 'admin:dashboard', request:  $request)) {
            Codefy::$PHP->flash->error(
                message: t__(msgid: 'You must be logged in to access the admin area.', domain: 'codefy')
            );
            return $this->redirect(url: $this->router->url(name: 'admin.login'));
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
    public function profile(ServerRequest $request): ?ResponseInterface
    {
        if (false === $this->user->can(permissionName: 'admin:profile', request: $request)) {
            Codefy::$PHP->flash->error(
                message: t__(msgid: 'You must be logged in to access the admin area.', domain: 'codefy')
            );

            return $this->redirect(url: $this->router->url(name: 'admin.login'));
        }

        return HtmlResponseFactory::create(
            $this->view->render(
                template: 'framework::backend/profile',
                data: [
                    'title' => 'User Profile',
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
    public function login(ServerRequest $request): ?ResponseInterface
    {
        /*if (true === $this->user->can(permissionName: 'admin:dashboard', request: $request)) {
            return $this->redirect($this->router->url(name: 'admin.home'));
        }*/

        return HtmlResponseFactory::create(
            $this->view->render(
                template: 'framework::backend/login',
                data: [
                    'title' => t__(msgid: 'Login', domain: 'codefy'),
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
     */
    public function logout(ServerRequest $request): ?ResponseInterface
    {
        if (false === $this->user->can(permissionName: 'admin:dashboard', request: $request)) {
            Codefy::$PHP->flash->error(
                message: t__(msgid: 'You are already logged out.', domain: 'codefy')
            );
            return $this->redirect(url: $this->router->url(name: 'admin.login'));
        }

        return $this->redirect(url: $this->router->url(name: 'admin.login'));
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
    public function register(ServerRequest $request): ?ResponseInterface
    {
        if (true === $this->user->can(permissionName: 'admin:dashboard', request: $request)) {
            return $this->redirect(url: $this->router->url(name: 'admin.home'));
        }

        return HtmlResponseFactory::create(
            $this->view->render(
                template: 'framework::backend/register',
                data: [
                    'title' => t__(msgid: 'Register', domain: 'codefy'),
                    'url' => $this->router->url(name: 'admin.create'),
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
    public function create(ServerRequest $request): ?ResponseInterface
    {
        /*if(false === $this->user->can(permissionName: 'admin:dashboard')) {
            Codefy::$PHP->flash->error(
                message: 'You must be logged in to perform that action.'
            );

            return $this->redirect($this->router->url(name: 'admin.login'));
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
                message: t__(msgid: 'User added successfully.', domain: 'codefy'),
            );

            return $this->redirect(url: $this->router->url(name: 'admin.login'));
        } catch (CommandCouldNotBeHandledException|UnresolvableCommandHandlerException|ReflectionException $e) {
            Codefy::$PHP->flash->error(
                message: t__('Could not execute create user command.', domain: 'codefy'),
            );

            FileLoggerFactory::getLogger()->error(message: $e->getMessage(), context: ['AdminController' => 'create']);

            return $this->redirect(url: $this->router->url(name: 'admin.create'));
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
    public function update(ServerRequest $request): ?ResponseInterface
    {
        if (false === $this->user->can(permissionName: 'admin:profile', request: $request)) {
            Codefy::$PHP->flash->error(
                message: 'You must be logged in to perform that action.'
            );

            return $this->redirect(url: $this->router->url(name: 'admin.login'));
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
            'password' => new StringLiteral(value: Password::hash($request->get('password'))),
            'token' => new UserToken(),
        ]);

        try {
            command(command: $command);

            Codefy::$PHP->flash->success(
                message: 'Profile was updated successfully.',
            );

            return $this->redirect(url: $this->router->url(name: 'admin.profile'));
        } catch (CommandCouldNotBeHandledException|UnresolvableCommandHandlerException|ReflectionException $e) {
            Codefy::$PHP->flash->error(
                message: 'Could not update the profile.',
            );

            FileLoggerFactory::getLogger()->error(message: $e->getMessage(), context: ['AdminController' => 'update']);

            return $this->redirect(url: $this->router->url(name: 'admin.profile'));
        }
    }
}
