<?php

declare(strict_types=1);

namespace App\Framework\RoleBasedAccessControl;

/**
 * Class Role
 * @package App\Framework\RoleBasedAccessControl
 */
abstract class Role
{
    /**
     * @param Permission $permission
     * @return bool
     */
    public function hasPermission(Permission $permission): bool
    {
        return in_array($permission, $this->getPermissions());
    }

    /**
     * @return Permission[]
     */
    abstract protected function getPermissions(): array;
}
