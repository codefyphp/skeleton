<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Domain\User\Command\CreateUserCommand;
use App\Domain\User\Command\UpdateUserCommand;
use App\Domain\User\Services\UserAuth;
use App\Domain\User\Services\UserSession;
use App\Domain\User\ValueObject\UserId;
use Codefy\CommandBus\Busses\SynchronousCommandBus;
use Codefy\CommandBus\Containers\ContainerFactory;
use Codefy\CommandBus\Exceptions\CommandCouldNotBeHandledException;
use Codefy\CommandBus\Exceptions\CommandPropertyNotFoundException;
use Codefy\CommandBus\Exceptions\UnresolvableCommandHandlerException;
use Codefy\CommandBus\Odin;
use Codefy\CommandBus\Resolvers\NativeCommandHandlerResolver;
use Codefy\Framework\Codefy;
use Codefy\Framework\Http\BaseController;
use Psr\Http\Message\ResponseInterface;
use Qubus\Exception\Data\TypeException;
use Qubus\Http\ServerRequest;
use Qubus\Http\Session\SessionException;
use Qubus\Http\Session\SessionId;
use Qubus\Http\Session\SessionService;
use Qubus\Routing\Router;
use Qubus\ValueObjects\StringLiteral\StringLiteral;
use Qubus\ValueObjects\Web\EmailAddress;
use Qubus\View\Native\Exception\InvalidTemplateNameException;
use Qubus\View\Native\Exception\ViewException;
use Qubus\View\Renderer;
use ReflectionException;

use function Codefy\Framework\Helpers\config;
use function Qubus\Support\Helpers\is_false__;

final class AdminController extends BaseController
{
    public function __construct(
        SessionService $sessionService,
        Router $router,
        ?Renderer $view = null
    ) {
        parent::__construct($sessionService, $router, $view);
    }

    /**
     * @throws ViewException
     * @throws InvalidTemplateNameException
     */
    public function auth(): ResponseInterface
    {
        return $this->redirect((string) config('auth.admin_url'));
    }

    /**
     * @throws ViewException
     * @throws InvalidTemplateNameException
     */
    public function index(ServerRequest $request): ResponseInterface|string
    {
        $user = UserAuth::isAuthenticated(sessionService: $this->sessionService, request: $request);

        if(is_false__($user)) {
            Codefy::$PHP->flash->error(
                message: 'You must be logged in to access the admin area.'
            );
            return $this->redirect((string) config('auth.login_url'));

            exit();
        }

        return $this->view->render(template: 'framework::backend/index', data: ['title' => 'Dashboard']);
    }

    /**
     * @throws ViewException
     * @throws InvalidTemplateNameException
     */
    public function profile(ServerRequest $request): ResponseInterface|string
    {
        $user = UserAuth::isAuthenticated(sessionService: $this->sessionService, request: $request);

        if(is_false__($user)) {
            Codefy::$PHP->flash->error(
                message: 'You must be logged in to access the admin area.'
            );

            return $this->redirect((string) config('auth.login_url'));
            exit();
        }

        return $this->view->render(
            template: 'framework::backend/profile',
            data: [
                'title' => 'User Profile',
                'user' => UserAuth::findUserById(),
            ]
        );
    }

    /**
     * @throws ViewException
     * @throws InvalidTemplateNameException
     */
    public function login(): ResponseInterface|string
    {
        return $this->view->render(template: 'framework::backend/login', data: ['title' => 'Login']);
    }

    /**
     * @throws ViewException
     * @throws InvalidTemplateNameException
     */
    public function logout(ServerRequest $request): ResponseInterface
    {
        $sessionId = SessionId::create($_COOKIE['USERSESSID']);

        $this->sessionService::$options = [
            'cookie-name' => 'USERSESSID'
        ];
        $session = $this->sessionService->makeSession($request);

        /** @var UserSession $userSession */
        $userSession = $session->get(UserSession::class);

        if($this->sessionService->sessionStorage->destroy($sessionId)) {
            $userSession->clear();
            Codefy::$PHP->flash->success(
                message: 'You are logged out.'
            );
        }

        return $this->redirect(config('auth.admin_url') . 'login/');
    }

    /**
     * @throws ViewException
     * @throws InvalidTemplateNameException
     */
    public function register(): ResponseInterface|string
    {
        return $this->view->render(template: 'framework::backend/register', data: ['title' => 'Register']);
    }

    /**
     * @throws TypeException
     * @throws CommandPropertyNotFoundException|SessionException
     */
    public function create(ServerRequest $request): ResponseInterface
    {
        /*$user = UserAuth::isAuthenticated(sessionService: $this->sessionService, request: $request);

        if(is_false__($user)) {
            Codefy::$PHP->flash->error(
                message: 'You must be logged in to perform that action.'
            );

            return $this->redirect((string) config('auth.login_url'));
            exit();
        }*/

        $resolver = new NativeCommandHandlerResolver(container: ContainerFactory::make(config: config('commandbus.container')));
        $odin = new Odin(bus: new SynchronousCommandBus($resolver));

        $command = new CreateUserCommand(data: [
            'username' => new StringLiteral(value: $request->get('username')),
            'firstName' => new StringLiteral(value: $request->get('first_name')),
            'lastName' => new StringLiteral(value: $request->get('last_name')),
            'email' => new EmailAddress(value: $request->get('email')),
            'password' => new StringLiteral(value: $request->get('password')),
        ]);

        try {
            $odin->execute($command);
            Codefy::$PHP->flash->success(
                message: 'User added successfully.',
            );

            return $this->redirect((string) config('auth.login_url'));
        } catch (CommandCouldNotBeHandledException|UnresolvableCommandHandlerException|ReflectionException $e) {
            Codefy::$PHP->flash->error(
                message: 'Could not execute create user command.',
            );

            return $this->redirect(config('auth.admin_url') . 'create/');
        }
    }

    /**
     * @throws TypeException
     * @throws CommandPropertyNotFoundException|SessionException
     */
    public function update(ServerRequest $request): ResponseInterface
    {
        $user = UserAuth::isAuthenticated(sessionService: $this->sessionService, request: $request);

        if(is_false__($user)) {
            Codefy::$PHP->flash->error(
                message: 'You must be logged in to perform that action.'
            );

            return $this->redirect((string) config('auth.login_url'));
            exit();
        }

        $resolver = new NativeCommandHandlerResolver(container: ContainerFactory::make(config: config('commandbus.container')));
        $odin = new Odin(bus: new SynchronousCommandBus($resolver));

        $command = new UpdateUserCommand(data: [
            'userId' => UserId::fromString(UserAuth::currentUserId()),
            'firstName' => new StringLiteral(value: $request->get('first_name')),
            'middleName' => empty($request->get('middle_name')) ?
                new StringLiteral(value: '') :
                new StringLiteral(value: $request->get('middle_name')),
            'lastName' => new StringLiteral(value: $request->get('last_name')),
            'email' => new EmailAddress(value: $request->get('email')),
            'password' => new StringLiteral(value: $request->get('password')),
        ]);

        try {
            $odin->execute($command);
            Codefy::$PHP->flash->success(
                message: 'Profile was updated successfully.',
            );

            return $this->redirect(config('auth.admin_url') . 'profile/');
        } catch (CommandCouldNotBeHandledException|UnresolvableCommandHandlerException|ReflectionException $e) {
            Codefy::$PHP->flash->error(
                message: 'Could not update the profile.',
            );

            return $this->redirect(config('auth.admin_url') . 'profile/');
        }
    }
}
