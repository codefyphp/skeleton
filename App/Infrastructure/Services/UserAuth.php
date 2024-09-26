<?php

declare(strict_types=1);

namespace App\Infrastructure\Services;

use App\Domain\User\Query\FindUserByTokenQuery;
use App\Infrastructure\Http\Middleware\UserAuthorizationMiddleware;
use Codefy\CommandBus\Containers\ContainerFactory;
use Codefy\CommandBus\Exceptions\CommandPropertyNotFoundException;
use Codefy\Framework\Auth\Rbac\Rbac;
use Codefy\Framework\Auth\UserSession;
use Codefy\Framework\Factory\FileLoggerFactory;
use Codefy\QueryBus\Busses\SynchronousQueryBus;
use Codefy\QueryBus\Enquire;
use Codefy\QueryBus\Resolvers\NativeQueryHandlerResolver;
use Codefy\QueryBus\UnresolvableQueryHandlerException;
use Exception;
use Psr\Http\Message\ServerRequestInterface;
use Qubus\Exception\Data\TypeException;
use Qubus\Expressive\OrmBuilder;
use Qubus\Http\Session\SessionService;
use ReflectionException;

final class UserAuth
{
    private ?string $token = null;
    public function __construct(
        protected Rbac $rbac,
        protected SessionService $sessionService,
        protected ServerRequestInterface $request
    ) {
    }

    /**
     * @throws UnresolvableQueryHandlerException
     * @throws ReflectionException
     * @throws TypeException
     * @throws CommandPropertyNotFoundException
     */
    public function can(string $permissionName, ServerRequestInterface $request, array $ruleParams = []): bool
    {
        $this->setRequest($request);

        /** This is only checked by routes which have the `user.authorization` middleware enabled. */
        if ($this->request->getHeaderLine(UserAuthorizationMiddleware::HEADER_HTTP_STATUS_CODE) === 'not_authorized') {
            return false;
        }

        if (!isset($this->request->getCookieParams()['USERSESSID'])
                || empty($this->request->getCookieParams()['USERSESSID'])) {
            return false;
        }

        $roles = $this->getRoles();
        foreach ($roles as $role) {
            if ($role->checkAccess($permissionName, $ruleParams)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @throws TypeException
     * @throws Exception
     */
    public function current(): ?OrmBuilder
    {
        $this->sessionService::$options = [
            'cookie-name' => 'USERSESSID',
        ];
        $session = $this->sessionService->makeSession($this->request);

        /** @var UserSession $user */
        $user = $session->get(type: UserSession::class);
        if ($user->isEmpty()) {
            return null;
        }

        $this->token = $user->token;

        try {
            return $this->findUserByToken();
        } catch (CommandPropertyNotFoundException|UnresolvableQueryHandlerException|ReflectionException $e) {
            FileLoggerFactory::getLogger()->error($e->getMessage());
        }

        return null;
    }

    /**
     * @throws ReflectionException
     * @throws CommandPropertyNotFoundException
     * @throws UnresolvableQueryHandlerException
     */
    public function findUserByToken(): ?OrmBuilder
    {
        $resolver = new NativeQueryHandlerResolver(container: ContainerFactory::make(config: []));
        $enquirer = new Enquire(bus: new SynchronousQueryBus($resolver));

        $query = new FindUserByTokenQuery(data: [
            'token' => $this->token,
        ]);

        return $enquirer->execute($query);
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
