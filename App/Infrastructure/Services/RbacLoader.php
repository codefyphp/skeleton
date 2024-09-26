<?php

declare(strict_types=1);

namespace App\Infrastructure\Services;

use Codefy\Framework\Auth\Rbac\Entity\Permission;
use Codefy\Framework\Auth\Rbac\Entity\Role;
use Codefy\Framework\Auth\Rbac\Exception\SentinelException;
use Codefy\Framework\Auth\Rbac\Rbac;
use Qubus\Config\ConfigContainer;
use Qubus\Exception\Exception;
use RuntimeException;

use function Qubus\Support\Helpers\is_null__;
use function sprintf;

final readonly class RbacLoader
{
    public function __construct(private Rbac $rbac, private ConfigContainer $configContainer)
    {
    }

    /**
     * @throws Exception
     */
    public function initRbacRoles(): void
    {
        if (!is_null__($this->configContainer->getConfigKey(key: 'rbac.roles'))) {
            $rolesConfig = (array) $this->configContainer->getConfigKey(key: 'rbac.roles', default: []);
            $this->addRoles($rolesConfig);
        }
    }

    /**
     * @throws Exception
     */
    public function initRbacPermissions(): void
    {
        if (!is_null__($this->configContainer->getConfigKey(key: 'rbac.permissions'))) {
            $permissionsConfig = (array) $this->configContainer->getConfigKey(key: 'rbac.permissions', default: []);
            $this->addPermissions($permissionsConfig);
        }
    }

    private function addRoles(array $rolesConfig, ?Role $parent = null): void
    {
        foreach ($rolesConfig as $name => $config) {
            $role = $this->rbac->addRole(name: $name, description: $config['description'] ?? '');
            if (!empty($config['permissions'])) {
                foreach ((array) $config['permissions'] as $permissionName) {
                    if ($permission = $this->rbac->getPermission(name: $permissionName)) {
                        $role->addPermission($permission);
                    } else {
                        throw new RuntimeException(message: sprintf('Permission not found: %s', $permissionName));
                    }
                }
            }
        }

        $parent?->addChild($role);

        if (!empty($config['roles'])) {
            $this->addRoles(rolesConfig: $config['roles'], parent: $role);
        }
    }

    /**
     * @throws SentinelException
     */
    private function addPermissions(array $permissionsConfig, ?Permission $parent = null): void
    {
        foreach ($permissionsConfig as $name => $config) {
            $permission = $this->rbac->addPermission(name: $name, description: $config['description'] ?? '');

            $parent?->addChild($permission);

            if (!empty($config['permissions'])) {
                $this->addPermissions(permissionsConfig: $config['permissions'], parent: $permission);
            }
        }
    }
}
