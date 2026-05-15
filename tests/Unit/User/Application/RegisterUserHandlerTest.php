<?php

declare(strict_types=1);

namespace Tests\Unit\User\Application;

use App\User\Application\RegisterUser;
use App\User\Application\RegisterUserHandler;
use App\User\Domain\User;
use App\User\Domain\UserRepository;
use PHPUnit\Framework\TestCase;

class RegisterUserHandlerTest extends TestCase
{
    public function test_handle_creates_and_adds_user(): void
    {
        $repo = $this->createMock(UserRepository::class);
        $repo->expects($this->once())
            ->method('add')
            ->with($this->isInstanceOf(User::class));

        $handler = new RegisterUserHandler($repo);
        $handler->handle(new RegisterUser('new@example.com', 'password123'));
    }

    public function test_handle_stores_user_with_correct_email(): void
    {
        $capturedUser = null;
        $repo = $this->createMock(UserRepository::class);
        $repo->method('add')->willReturnCallback(function (User $u) use (&$capturedUser) {
            $capturedUser = $u;
        });

        $handler = new RegisterUserHandler($repo);
        $handler->handle(new RegisterUser('test@example.com', 'mypassword'));

        $this->assertSame('test@example.com', $capturedUser->getEmail());
    }

    public function test_handle_hashes_password_before_storing(): void
    {
        $capturedUser = null;
        $repo = $this->createMock(UserRepository::class);
        $repo->method('add')->willReturnCallback(function (User $u) use (&$capturedUser) {
            $capturedUser = $u;
        });

        $handler = new RegisterUserHandler($repo);
        $handler->handle(new RegisterUser('user@example.com', 'plaintext'));

        $this->assertTrue(password_verify('plaintext', $capturedUser->getPasswordHash()));
        $this->assertNotSame('plaintext', $capturedUser->getPasswordHash());
    }
}
