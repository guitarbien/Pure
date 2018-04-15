<?php

declare(strict_types=1);

namespace App\Framework\RoleBasedAccessControl;

/**
 * Interface CurrentUserFactory
 * @package App\Framework\RoleBasedAccessControl
 */
interface CurrentUserFactory
{
    /**
     * @return User
     */
    public function create(): User;
}
