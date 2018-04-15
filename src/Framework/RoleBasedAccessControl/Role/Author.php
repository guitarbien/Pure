<?php

declare(strict_types=1);

namespace App\Framework\RoleBasedAccessControl\Role;

use App\Framework\RoleBasedAccessControl\Permission;
use App\Framework\RoleBasedAccessControl\Permission\SubmitLink;
use App\Framework\RoleBasedAccessControl\Role;

/**
 * Class Author
 * @package App\Framework\RoleBasedAccessControl\Role
 */
final class Author extends Role
{
    /**
     * @return Permission[]
     */
    protected function getPermissions(): array
    {
        return [new SubmitLink()];
    }
}
