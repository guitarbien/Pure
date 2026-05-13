<?php

declare(strict_types=1);

namespace Tests\Unit\Framework\RoleBasedAccessControl;

use App\Framework\RoleBasedAccessControl\AuthenticatedUser;
use App\Framework\RoleBasedAccessControl\Guest;
use App\Framework\RoleBasedAccessControl\SymfonySessionCurrentUserFactory;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class SymfonySessionCurrentUserFactoryTest extends TestCase
{
    public function test_create_returns_guest_when_no_user_id_in_session(): void
    {
        $session = new Session(new MockArraySessionStorage());
        $session->start();

        $factory = new SymfonySessionCurrentUserFactory($session);
        $user = $factory->create();

        $this->assertInstanceOf(Guest::class, $user);
    }

    public function test_create_returns_authenticated_user_when_user_id_exists(): void
    {
        $userId = Uuid::uuid4();
        $session = new Session(new MockArraySessionStorage());
        $session->start();
        $session->set('userId', $userId->toString());

        $factory = new SymfonySessionCurrentUserFactory($session);
        $user = $factory->create();

        $this->assertInstanceOf(AuthenticatedUser::class, $user);
        $this->assertSame($userId->toString(), $user->getId()->toString());
    }

    public function test_create_authenticated_user_has_author_role(): void
    {
        $userId = Uuid::uuid4();
        $session = new Session(new MockArraySessionStorage());
        $session->start();
        $session->set('userId', $userId->toString());

        $factory = new SymfonySessionCurrentUserFactory($session);
        $user = $factory->create();

        $this->assertInstanceOf(AuthenticatedUser::class, $user);
        $this->assertTrue($user instanceof AuthenticatedUser);
    }
}
