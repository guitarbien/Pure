<?php

declare(strict_types=1);

namespace Tests\Unit\User\Application;

use App\User\Application\LogIn;
use App\User\Application\LogInHandler;
use App\User\Domain\User;
use App\User\Domain\UserRepository;
use PHPUnit\Framework\TestCase;

class LogInHandlerTest extends TestCase
{
    public function test_handle_does_nothing_when_user_not_found(): void
    {
        $repo = $this->createMock(UserRepository::class);
        $repo->method('findByEmail')->willReturn(null);
        $repo->expects($this->never())->method('save');

        $handler = new LogInHandler($repo);
        $handler->handle(new LogIn('ghost@example.com', 'pass'));
        // no exception expected
        $this->assertTrue(true);
    }

    public function test_handle_calls_logIn_and_saves_user(): void
    {
        $user = User::register('user@example.com', 'correct');

        $repo = $this->createMock(UserRepository::class);
        $repo->method('findByEmail')->with('user@example.com')->willReturn($user);
        $repo->expects($this->once())->method('save')->with($user);

        $handler = new LogInHandler($repo);
        $handler->handle(new LogIn('user@example.com', 'correct'));
    }

    public function test_handle_with_wrong_password_still_saves_user_to_record_failed_attempt(): void
    {
        // LogInHandler::handle() calls $user->logIn() then $repo->save() unconditionally
        $user = User::register('user@example.com', 'correct');

        $repo = $this->createMock(UserRepository::class);
        $repo->method('findByEmail')->willReturn($user);
        $repo->expects($this->once())->method('save');

        $handler = new LogInHandler($repo);
        $handler->handle(new LogIn('user@example.com', 'wrong'));

        $this->assertSame(1, $user->getFailedLoginAttempts());
    }
}
