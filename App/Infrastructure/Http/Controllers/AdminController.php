<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Domain\User\Command\CreateUserCommand;
use App\Domain\User\Command\UpdateUserCommand;
use App\Domain\User\ValueObject\UserId;
use App\Domain\User\ValueObject\Username;
use App\Domain\User\ValueObject\UserToken;
use App\Infrastructure\Services\UserAuth;
use Codefy\CommandBus\Busses\SynchronousCommandBus;
use Codefy\CommandBus\Containers\ContainerFactory;
use Codefy\CommandBus\Exceptions\CommandCouldNotBeHandledException;
use Codefy\CommandBus\Exceptions\CommandPropertyNotFoundException;
use Codefy\CommandBus\Exceptions\UnresolvableCommandHandlerException;
use Codefy\CommandBus\Odin;
use Codefy\CommandBus\Resolvers\NativeCommandHandlerResolver;
use Codefy\Framework\Codefy;
use Codefy\Framework\Factory\FileLoggerFactory;
use Codefy\Framework\Http\BaseController;
use Codefy\QueryBus\UnresolvableQueryHandlerException;
use Psr\Http\Message\ResponseInterface;
use Qubus\Exception\Data\TypeException;
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

use function Codefy\Framework\Helpers\config;

final class AdminController extends BaseController
{
    public function __construct(
        SessionService $sessionService,
        Router $router,
        protected UserAuth $user,
        ?Renderer $view = null
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
                return $this->redirect($this->router->url(name: 'admin.login'));
            } catch (NamedRouteNotFoundException|RouteParamFailedConstraintException $e) {
                FileLoggerFactory::getLogger()->notice($e->getMessage(), ['AdminController' => 'auth:route']);
            }

            exit();
        }

        return $this->redirect($this->router->url(name: 'admin.home'));
    }

    /**
     * @throws RouteParamFailedConstraintException
     * @throws UnresolvableQueryHandlerException
     * @throws NamedRouteNotFoundException
     * @throws CommandPropertyNotFoundException
     * @throws ReflectionException
     * @throws SessionException
     * @throws TypeException
     */
    public function index(ServerRequest $request): ResponseInterface|string
    {
        if (false === $this->user->can(permissionName: 'admin:dashboard', request:  $request)) {
            Codefy::$PHP->flash->error(
                message: 'You must be logged in to access the admin area.'
            );
            return $this->redirect($this->router->url(name: 'admin.login'));
        }

        return $this->view->render(template: 'framework::backend/index', data: ['title' => 'Dashboard']);
    }

    /**
     * @throws RouteParamFailedConstraintException
     * @throws UnresolvableQueryHandlerException
     * @throws NamedRouteNotFoundException
     * @throws CommandPropertyNotFoundException
     * @throws ReflectionException
     * @throws SessionException
     * @throws TypeException
     */
    public function profile(ServerRequest $request): ResponseInterface|string
    {
        if (false === $this->user->can(permissionName: 'admin:profile', request: $request)) {
            Codefy::$PHP->flash->error(
                message: 'You must be logged in to access the admin area.'
            );

            return $this->redirect($this->router->url(name: 'admin.login'));
        }

        return $this->view->render(
            template: 'framework::backend/profile',
            data: [
                'title' => 'User Profile',
                'user' => $this->user->findUserByToken(),
                'roles' => config(key: 'rbac.roles'),
                'url' => $this->router->url(name: 'admin.update'),
            ]
        );
    }

    /**
     * @throws RouteParamFailedConstraintException
     * @throws UnresolvableQueryHandlerException
     * @throws ReflectionException
     * @throws TypeException
     * @throws NamedRouteNotFoundException
     * @throws CommandPropertyNotFoundException
     */
    public function login(ServerRequest $request): ResponseInterface|string
    {
        if (true === $this->user->can(permissionName: 'admin:dashboard', request: $request)) {
            return $this->redirect($this->router->url(name: 'admin.home'));
        }

        return $this->view->render(
            template: 'framework::backend/login',
            data: [
                'title' => 'Login',
                'url' => $this->router->url(name: 'admin.auth'),
            ]
        );
    }

    /**
     * @throws RouteParamFailedConstraintException
     * @throws UnresolvableQueryHandlerException
     * @throws NamedRouteNotFoundException
     * @throws CommandPropertyNotFoundException
     * @throws ReflectionException
     * @throws TypeException
     * @throws SessionException
     */
    public function logout(ServerRequest $request): ResponseInterface
    {
        if (false === $this->user->can(permissionName: 'admin:dashboard', request: $request)) {
            Codefy::$PHP->flash->error(
                message: 'You are already logged out.'
            );
            return $this->redirect($this->router->url(name: 'admin.login'));
        }

        return $this->redirect($this->router->url(name: 'admin.login'));
    }

    /**
     * @throws RouteParamFailedConstraintException
     * @throws UnresolvableQueryHandlerException
     * @throws ReflectionException
     * @throws TypeException
     * @throws NamedRouteNotFoundException
     * @throws CommandPropertyNotFoundException
     */
    public function register(ServerRequest $request): ResponseInterface|string
    {
        if (true === $this->user->can(permissionName: 'admin:dashboard', request: $request)) {
            return $this->redirect($this->router->url(name: 'admin.home'));
        }

        return $this->view->render(
            template: 'framework::backend/register',
            data: [
                'title' => 'Register',
                'url' => $this->router->url(name: 'admin.create'),
            ]
        );
    }

    /**
     * @throws RouteParamFailedConstraintException
     * @throws NamedRouteNotFoundException
     * @throws CommandPropertyNotFoundException
     * @throws TypeException
     * @throws SessionException
     */
    public function create(ServerRequest $request): ResponseInterface
    {
        /*if(false === $this->user->can(permissionName: 'admin:dashboard')) {
            Codefy::$PHP->flash->error(
                message: 'You must be logged in to perform that action.'
            );

            return $this->redirect($this->router->url(name: 'admin.login'));
            exit();
        }*/

        $resolver = new NativeCommandHandlerResolver(
            container: ContainerFactory::make(config: config('commandbus.container'))
        );
        $odin = new Odin(bus: new SynchronousCommandBus($resolver));

        $command = new CreateUserCommand(data: [
            'username' => new Username(value: $request->get('username')),
            'token' => new UserToken(),
            'firstName' => new StringLiteral(value: $request->get('first_name')),
            'lastName' => new StringLiteral(value: $request->get('last_name')),
            'email' => new EmailAddress(value: $request->get('email')),
            'role' => new StringLiteral(value: $request->get('role')),
            'password' => new StringLiteral(value: $request->get('password')),
        ]);

        try {
            $odin->execute($command);
            Codefy::$PHP->flash->success(
                message: 'User added successfully.',
            );

            return $this->redirect($this->router->url(name: 'admin.login'));
        } catch (CommandCouldNotBeHandledException|UnresolvableCommandHandlerException|ReflectionException $e) {
            Codefy::$PHP->flash->error(
                message: 'Could not execute create user command.',
            );

            return $this->redirect($this->router->url(name: 'admin.create'));
        }
    }

    /**
     * @throws RouteParamFailedConstraintException
     * @throws UnresolvableQueryHandlerException
     * @throws CommandPropertyNotFoundException
     * @throws NamedRouteNotFoundException
     * @throws ReflectionException
     * @throws TypeException
     * @throws SessionException
     */
    public function update(ServerRequest $request): ResponseInterface
    {
        if (false === $this->user->can(permissionName: 'admin:profile', request: $request)) {
            Codefy::$PHP->flash->error(
                message: 'You must be logged in to perform that action.'
            );

            return $this->redirect($this->router->url(name: 'admin.login'));
        }

        $resolver = new NativeCommandHandlerResolver(
            container: ContainerFactory::make(config: config('commandbus.container'))
        );
        $odin = new Odin(bus: new SynchronousCommandBus($resolver));

        $command = new UpdateUserCommand(data: [
            'userId' => UserId::fromString($request->get('user_id')),
            'firstName' => new StringLiteral(value: $request->get('first_name')),
            'middleName' => empty($request->get('middle_name')) ?
                new StringLiteral(value: '') :
                new StringLiteral(value: $request->get('middle_name')),
            'lastName' => new StringLiteral(value: $request->get('last_name')),
            'email' => new EmailAddress(value: $request->get('email')),
            'role' => new StringLiteral(value: $request->get('role')),
            'password' => new StringLiteral(value: $request->get('password')),
            'token' => new UserToken(),
        ]);

        try {
            $odin->execute($command);
            Codefy::$PHP->flash->success(
                message: 'Profile was updated successfully.',
            );

            return $this->redirect($this->router->url(name: 'admin.profile'));
        } catch (CommandCouldNotBeHandledException|UnresolvableCommandHandlerException|ReflectionException $e) {
            Codefy::$PHP->flash->error(
                message: 'Could not update the profile.',
            );

            return $this->redirect($this->router->url(name: 'admin.profile'));
        }
    }
}
