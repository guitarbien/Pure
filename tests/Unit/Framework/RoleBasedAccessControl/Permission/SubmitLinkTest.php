<?php

declare(strict_types=1);

namespace Tests\Unit\Framework\RoleBasedAccessControl\Permission;

use App\Framework\RoleBasedAccessControl\Permission\SubmitLink;
use PHPUnit\Framework\TestCase;

class SubmitLinkTest extends TestCase
{
    public function test_submit_link_permission_can_be_created(): void
    {
        $permission = new SubmitLink();

        $this->assertInstanceOf(SubmitLink::class, $permission);
    }

    public function test_submit_link_permissions_are_equal(): void
    {
        $perm1 = new SubmitLink();
        $perm2 = new SubmitLink();

        $this->assertTrue($perm1->equals($perm2));
    }
}
