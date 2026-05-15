<?php

declare(strict_types=1);

namespace Tests\Unit\User\Application;

use App\User\Application\LogIn;
use PHPUnit\Framework\TestCase;

class LogInTest extends TestCase
{
    public function test_constructor_stores_email(): void
    {
        $command = new LogIn('user@example.com', 'password');

        $this->assertSame('user@example.com', $command->getEmail());
    }

    public function test_constructor_stores_password(): void
    {
        $command = new LogIn('user@example.com', 'secret123');

        $this->assertSame('secret123', $command->getPassword());
    }
}
