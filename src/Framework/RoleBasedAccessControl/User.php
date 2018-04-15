<?php

declare(strict_types=1);

namespace App\Framework\RoleBasedAccessControl;

/**
 * Interface User
 * @package App\Framework\RoleBasedAccessControl
 */
interface User
{
    /**
     * @param Permission $permission
     * @return bool
     */
    public function hasPermission(Permission $permission): bool;
}
