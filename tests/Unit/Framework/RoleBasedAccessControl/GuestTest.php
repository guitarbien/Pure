<?php

declare(strict_types=1);

namespace Tests\Unit\Framework\RoleBasedAccessControl;

use App\Framework\RoleBasedAccessControl\Guest;
use App\Framework\RoleBasedAccessControl\Permission\SubmitLink;
use PHPUnit\Framework\TestCase;

class GuestTest extends TestCase
{
    public function test_hasPermission_always_returns_false(): void
    {
        $guest = new Guest();
        $this->assertFalse($guest->hasPermission(new SubmitLink()));
    }
}
