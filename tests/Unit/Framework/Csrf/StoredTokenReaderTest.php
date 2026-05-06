<?php

declare(strict_types=1);

namespace Tests\Unit\Framework\Csrf;

use App\Framework\Csrf\StoredTokenReader;
use App\Framework\Csrf\Token;
use App\Framework\Csrf\TokenStorage;
use PHPUnit\Framework\TestCase;

class StoredTokenReaderTest extends TestCase
{
    public function test_read_returns_existing_token_when_found(): void
    {
        $existing = new Token('existing-token');
        $storage = $this->createMock(TokenStorage::class);
        $storage->method('retrieve')->with('key')->willReturn($existing);
        $storage->expects($this->never())->method('store');

        $reader = new StoredTokenReader($storage);
        $result = $reader->read('key');

        $this->assertSame('existing-token', $result->toString());
    }

    public function test_read_generates_and_stores_new_token_when_not_found(): void
    {
        $storage = $this->createMock(TokenStorage::class);
        $storage->method('retrieve')->with('key')->willReturn(null);
        $storage->expects($this->once())
            ->method('store')
            ->with($this->equalTo('key'), $this->isInstanceOf(Token::class));

        $reader = new StoredTokenReader($storage);
        $result = $reader->read('key');

        $this->assertInstanceOf(Token::class, $result);
        $this->assertNotEmpty($result->toString());
    }

    public function test_read_returns_newly_generated_token_when_storage_empty(): void
    {
        $capturedToken = null;
        $storage = $this->createMock(TokenStorage::class);
        $storage->method('retrieve')->willReturn(null);
        $storage->method('store')->willReturnCallback(function (string $key, Token $token) use (&$capturedToken) {
            $capturedToken = $token;
        });

        $reader = new StoredTokenReader($storage);
        $result = $reader->read('key');

        $this->assertSame($capturedToken->toString(), $result->toString());
    }
}
