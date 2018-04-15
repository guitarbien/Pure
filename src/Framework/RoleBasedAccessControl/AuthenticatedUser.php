<?php

declare(strict_types=1);

namespace App\Framework\RoleBasedAccessControl;

use Ramsey\Uuid\UuidInterface;

/**
 * Class AuthenticatedUser
 * @package App\Framework\RoleBasedAccessControl
 */
final class AuthenticatedUser implements User
{
    /** @var UuidInterface */
    private $id;

    /** @var Role[] */
    private $roles = [];

    /**
     * AuthenticatedUser constructor.
     * @param UuidInterface $id
     * @param array $roles
     */
    public function __construct(UuidInterface $id, array $roles)
    {
        $this->id    = $id;
        $this->roles = $roles;
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @param Permission $permission
     * @return bool
     */
    public function hasPermission(Permission $permission): bool
    {
        foreach ($this->roles as $role) {
            if ($role->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }
}
