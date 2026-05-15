<?php

declare(strict_types=1);

namespace Tests\Unit\Framework\RoleBasedAccessControl;

use App\Framework\RoleBasedAccessControl\AuthenticatedUser;
use App\Framework\RoleBasedAccessControl\Permission\SubmitLink;
use App\Framework\RoleBasedAccessControl\Role\Author;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class AuthenticatedUserTest extends TestCase
{
    public function test_getId_returns_assigned_uuid(): void
    {
        $uuid = Uuid::uuid4();
        $user = new AuthenticatedUser($uuid, []);

        $this->assertTrue($uuid->equals($user->getId()));
    }

    public function test_hasPermission_returns_false_when_no_roles(): void
    {
        $user = new AuthenticatedUser(Uuid::uuid4(), []);
        $this->assertFalse($user->hasPermission(new SubmitLink()));
    }

    public function test_hasPermission_returns_true_when_role_grants_it(): void
    {
        $user = new AuthenticatedUser(Uuid::uuid4(), [new Author()]);
        $this->assertTrue($user->hasPermission(new SubmitLink()));
    }

    public function test_hasPermission_returns_true_when_any_role_grants_it(): void
    {
        // Author grants SubmitLink; even if mixed with an empty role, result is true
        $emptyRole = new class extends \App\Framework\RoleBasedAccessControl\Role {
            protected function getPermissions(): array { return []; }
        };

        $user = new AuthenticatedUser(Uuid::uuid4(), [$emptyRole, new Author()]);
        $this->assertTrue($user->hasPermission(new SubmitLink()));
    }
}
