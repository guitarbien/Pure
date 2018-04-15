<?php

declare(strict_types=1);

namespace App\Framework\RoleBasedAccessControl;

/**
 * Class Guest
 * @package App\Framework\RoleBasedAccessControl
 */
final class Guest implements User
{
    /**
     * @param Permission $permission
     * @return bool
     */
    public function hasPermission(Permission $permission): bool
    {
        return false;
    }
}
