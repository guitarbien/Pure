<?php

declare(strict_types=1);

namespace App\Framework\RoleBasedAccessControl;

/**
 * Class Permission
 * @package App\Framework\RoleBasedAccessControl
 */
abstract class Permission
{
    /**
     * @param Permission $permission
     * @return bool
     */
    public function equals(Permission $permission): bool
    {
        return get_class() === get_class($permission);
    }
}
