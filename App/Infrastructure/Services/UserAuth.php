<?php

declare(strict_types=1);

namespace App\Infrastructure\Services;

use App\Domain\User\Query\FindUserByTokenQuery;
use Codefy\CommandBus\Exceptions\CommandPropertyNotFoundException;
use Codefy\Framework\Auth\Rbac\Rbac;
use Codefy\Framework\Auth\UserSession;
use Codefy\Framework\Factory\FileLoggerFactory;
use Codefy\Framework\Http\Middleware\Auth\UserAuthorizationMiddleware;
use Codefy\QueryBus\UnresolvableQueryHandlerException;
use Exception;
use Psr\Http\Message\ServerRequestInterface;
use Qubus\Config\ConfigContainer;
use Qubus\Exception\Data\TypeException;
use Qubus\Expressive\Database;
use Qubus\Http\Session\SessionService;
use ReflectionException;

use function Codefy\Framework\Helpers\ask;

final class UserAuth
{
    private ?string $token = null;

    public function __construct(
        protected Rbac $rbac,
        protected SessionService $sessionService,
        protected ServerRequestInterface $request,
        protected ConfigContainer $configContainer
    ) {
    }

    /**
     * @throws UnresolvableQueryHandlerException
     * @throws ReflectionException
     * @throws TypeException
     * @throws CommandPropertyNotFoundException
     * @throws \Qubus\Exception\Exception
     */
    public function can(string $permissionName, ServerRequestInterface $request, array $ruleParams = []): bool
    {
        $this->setRequest($request);
        $cookieName = $this->configContainer->getConfigKey(key: 'auth.cookie_name', default: 'USERSESSID');

        /** This is only checked by routes which have the `user.authorization` middleware enabled. */
        if ($this->request->getHeaderLine(UserAuthorizationMiddleware::HEADER_HTTP_STATUS_CODE) === 'not_authorized') {
            return false;
        }

        if (!isset($this->request->getCookieParams()[$cookieName])
                || empty($this->request->getCookieParams()[$cookieName])) {
            return false;
        }

        $roles = $this->getRoles();
        return array_any($roles, fn($role) => $role->checkAccess($permissionName, $ruleParams));
    }

    /**
     * @throws TypeException
     * @throws Exception
     */
    public function current(): Database|bool
    {
        $this->sessionService::$options = [
            'cookie-name' => $this->configContainer->getConfigKey(key: 'auth.cookie_name', default: 'USERSESSID'),
        ];
        $session = $this->sessionService->makeSession($this->request);

        /** @var UserSession $user */
        $user = $session->get(type: UserSession::class);
        if ($user->isEmpty()) {
            return false;
        }

        $this->token = $user->token;

        try {
            return $this->findUserByToken();
        } catch (CommandPropertyNotFoundException|UnresolvableQueryHandlerException|ReflectionException $e) {
            FileLoggerFactory::getLogger()->error($e->getMessage());
        }

        return false;
    }

    /**
     * @throws ReflectionException
     * @throws CommandPropertyNotFoundException
     * @throws UnresolvableQueryHandlerException
     */
    private function findUserByToken(): Database|bool
    {
        $query = new FindUserByTokenQuery(data: [
            'token' => $this->token,
        ]);

        return ask($query);
    }

    /**
     * @throws TypeException
     */
    private function getRoles(): array
    {
        $user = $this->current();
        $result = [];
        foreach ((array)$user->role as $roleName) {
            if ($role = $this->rbac->getRole($roleName)) {
                $result[$roleName] = $role;
            }
        }
        return $result;
    }

    private function setRequest(ServerRequestInterface $request): void
    {
        $this->request = $request;
    }
}
