<?php

declare(strict_types=1);

namespace Tests\Unit\User\Application;

use App\User\Application\RegisterUser;
use PHPUnit\Framework\TestCase;

class RegisterUserTest extends TestCase
{
    public function test_constructor_stores_email(): void
    {
        $command = new RegisterUser('user@example.com', 'password123');

        $this->assertSame('user@example.com', $command->getEmail());
    }

    public function test_constructor_stores_password(): void
    {
        $command = new RegisterUser('user@example.com', 'secret123');

        $this->assertSame('secret123', $command->getPassword());
    }
}
