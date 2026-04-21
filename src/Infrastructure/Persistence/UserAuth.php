<?php

declare(strict_types=1);

namespace Infrastructure\Persistence;

use Codefy\Framework\Auth\Gate;
use Codefy\Framework\Auth\Rbac\Entity\Role;
use Codefy\Framework\Auth\Rbac\Rbac;
use Codefy\Framework\Auth\Repository\AuthUserRepository;
use Codefy\Framework\Factory\FileLoggerFactory;
use Codefy\Framework\Http\Middleware\Auth\UserAuthorizationMiddleware;
use Codefy\Framework\Http\Middleware\Auth\UserCookieDecryptMiddleware;
use Codefy\Framework\Http\RequestContext;
use Psr\Http\Message\ServerRequestInterface;

final class UserAuth implements Gate
{
    public function __construct(protected Rbac $rbac, protected AuthUserRepository $user)
    {
    }

    /**
     * @inheritDoc
     */
    public function can(string $permissionName, array $ruleParams = []): bool
    {
        if (
                $this->getRequest()->getHeaderLine(
                        UserAuthorizationMiddleware::HEADER_HTTP_STATUS_CODE
                ) === 'not_authorized'
        ) {
            return false;
        }

        if (!$this->hasAuthenticatedUser()) {
            return false;
        }

        $roles = $this->getRoles();

        return array_any($roles, fn($role) => $role->checkAccess($permissionName, $ruleParams));
    }

    /**
     * @inheritDoc
     */
    public function current(): object|bool|null
    {
        $token = $this->getToken();

        if ($token === null) {
            return false;
        }

        return $this->resolveUserByToken($token);
    }

    /**
     * Whether user is authenticated.
     *
     * @return bool
     */
    private function hasAuthenticatedUser(): bool
    {
        return $this->getToken() !== null;
    }

    /**
     * @throws \ReflectionException
     */
    private function resolveUserByToken(string $token): object|bool|null
    {
        try {
            return $this->user->find($token);
        } catch (\Throwable $e) {
            FileLoggerFactory::getLogger()->error($e->getMessage());
            return false;
        }
    }

    /**
     * @return array<string, Role>
     * @throws \ReflectionException
     */
    private function getRoles(): array
    {
        $user = $this->current();

        if ($user === false) {
            return [];
        }

        $roles = [];
        // @phpstan-ignore property.nonObject
        foreach ((array)$user->role as $roleName) {
            if ($role = $this->rbac->getRole($roleName)) {
                $roles[$roleName] = $role;
            }
        }

        return $roles;
    }

    /**
     * @inheritDoc
     */
    public function guest(): bool
    {
        return $this->getToken() === null;
    }

    /**
     * @inheritDoc
     */
    public function isLoggedIn(): bool
    {
        return $this->getToken() !== null;
    }

    /**
     * Fetch decrypted token from request context.
     */
    private function getToken(): ?string
    {
        $request = $this->getRequest();

        return $request->getAttribute(UserCookieDecryptMiddleware::USER_COOKIE);
    }

    /**
     * Return request object.
     *
     * @return ServerRequestInterface
     */
    private function getRequest(): ServerRequestInterface
    {
        return RequestContext::get();
    }
}
