<?php

declare(strict_types=1);

namespace Tests\Unit\Framework\MessageContainer;

use App\Framework\MessageContainer\SymfonySessionFlashBag;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class SymfonySessionFlashBagTest extends TestCase
{
    public function test_add_stores_message_in_session(): void
    {
        $session = new Session(new MockArraySessionStorage());
        $session->start();

        $flashBag = new SymfonySessionFlashBag($session);
        $flashBag->add('info', 'Test message');

        $messages = $flashBag->get('info');
        $this->assertContains('Test message', $messages);
    }

    public function test_get_returns_empty_array_when_no_messages(): void
    {
        $session = new Session(new MockArraySessionStorage());
        $session->start();

        $flashBag = new SymfonySessionFlashBag($session);
        $messages = $flashBag->get('nonexistent');

        $this->assertEmpty($messages);
    }

    public function test_add_multiple_messages(): void
    {
        $session = new Session(new MockArraySessionStorage());
        $session->start();

        $flashBag = new SymfonySessionFlashBag($session);
        $flashBag->add('errors', 'Error 1');
        $flashBag->add('errors', 'Error 2');

        $messages = $flashBag->get('errors');
        $this->assertCount(2, $messages);
        $this->assertContains('Error 1', $messages);
        $this->assertContains('Error 2', $messages);
    }
}
