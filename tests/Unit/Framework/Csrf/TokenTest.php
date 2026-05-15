<?php

declare(strict_types=1);

namespace Tests\Unit\Framework\Csrf;

use App\Framework\Csrf\Token;
use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase
{
    public function test_toString_returns_stored_value(): void
    {
        $token = new Token('abc123');
        $this->assertSame('abc123', $token->toString());
    }

    public function test_equals_returns_true_for_same_value(): void
    {
        $token = new Token('abc123');
        $other = new Token('abc123');
        $this->assertTrue($token->equals($other));
    }

    public function test_equals_returns_false_for_different_value(): void
    {
        $token = new Token('abc123');
        $other = new Token('xyz789');
        $this->assertFalse($token->equals($other));
    }

    public function test_generate_returns_token_instance(): void
    {
        $token = Token::generate();
        $this->assertInstanceOf(Token::class, $token);
    }

    public function test_generate_produces_non_empty_string(): void
    {
        $token = Token::generate();
        $this->assertNotEmpty($token->toString());
    }

    public function test_generate_produces_512_hex_chars(): void
    {
        // bin2hex(random_bytes(256)) → 512 chars
        $token = Token::generate();
        $this->assertSame(512, strlen($token->toString()));
        $this->assertMatchesRegularExpression('/^[0-9a-f]+$/', $token->toString());
    }

    public function test_generate_produces_unique_tokens(): void
    {
        $a = Token::generate();
        $b = Token::generate();
        $this->assertFalse($a->equals($b));
    }
}
