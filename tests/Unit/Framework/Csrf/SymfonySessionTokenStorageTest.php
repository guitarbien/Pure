<?php

declare(strict_types=1);

namespace Tests\Unit\Framework\Csrf;

use App\Framework\Csrf\SymfonySessionTokenStorage;
use App\Framework\Csrf\Token;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class SymfonySessionTokenStorageTest extends TestCase
{
    private Session $session;
    private SymfonySessionTokenStorage $storage;

    protected function setUp(): void
    {
        $this->session = new Session(new MockArraySessionStorage());
        $this->session->start();
        $this->storage = new SymfonySessionTokenStorage($this->session);
    }

    public function test_store_saves_token_value_in_session(): void
    {
        $token = new Token('my-csrf-token');
        $this->storage->store('csrf-key', $token);

        $this->assertSame('my-csrf-token', $this->session->get('csrf-key'));
    }

    public function test_retrieve_returns_token_when_stored(): void
    {
        $this->storage->store('csrf', new Token('abc123'));
        $retrieved = $this->storage->retrieve('csrf');

        $this->assertInstanceOf(Token::class, $retrieved);
        $this->assertSame('abc123', $retrieved->toString());
    }

    public function test_retrieve_returns_null_when_key_not_found(): void
    {
        $result = $this->storage->retrieve('nonexistent');

        $this->assertNull($result);
    }

    public function test_store_overwrites_previous_token(): void
    {
        $this->storage->store('key', new Token('old'));
        $this->storage->store('key', new Token('new'));

        $retrieved = $this->storage->retrieve('key');
        $this->assertSame('new', $retrieved->toString());
    }

    public function test_different_keys_are_independent(): void
    {
        $this->storage->store('key-a', new Token('token-a'));
        $this->storage->store('key-b', new Token('token-b'));

        $this->assertSame('token-a', $this->storage->retrieve('key-a')->toString());
        $this->assertSame('token-b', $this->storage->retrieve('key-b')->toString());
    }
}
