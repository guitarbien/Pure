<?php

declare(strict_types=1);

namespace Tests\Unit\Framework\Csrf;

use App\Framework\Csrf\StoredTokenValidator;
use App\Framework\Csrf\Token;
use App\Framework\Csrf\TokenStorage;
use PHPUnit\Framework\TestCase;

class StoredTokenValidatorTest extends TestCase
{
    public function test_validate_returns_true_when_tokens_match(): void
    {
        $stored = new Token('secret-token');
        $storage = $this->createMock(TokenStorage::class);
        $storage->method('retrieve')->with('form')->willReturn($stored);

        $validator = new StoredTokenValidator($storage);

        $this->assertTrue($validator->validate('form', new Token('secret-token')));
    }

    public function test_validate_returns_false_when_tokens_differ(): void
    {
        $stored = new Token('secret-token');
        $storage = $this->createMock(TokenStorage::class);
        $storage->method('retrieve')->with('form')->willReturn($stored);

        $validator = new StoredTokenValidator($storage);

        $this->assertFalse($validator->validate('form', new Token('wrong-token')));
    }

    public function test_validate_uses_correct_key(): void
    {
        $stored = new Token('abc');
        $storage = $this->createMock(TokenStorage::class);
        $storage->expects($this->once())
            ->method('retrieve')
            ->with('my-key')
            ->willReturn($stored);

        $validator = new StoredTokenValidator($storage);
        $validator->validate('my-key', new Token('abc'));
    }
}
