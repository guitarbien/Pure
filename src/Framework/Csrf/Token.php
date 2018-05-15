<?php

declare(strict_types=1);

namespace App\Framework\Csrf;

/**
 * Class Token
 * @package App\Framework\Csrf
 */
final class Token
{
    /** @var string */
    private $token;

    /**
     * Token constructor.
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * @return Token
     * @throws \Exception
     */
    public static function generate(): self
    {
        $token = bin2hex(random_bytes(256));

        return new self($token);
    }

    /**
     * @param Token $token
     * @return bool
     */
    public function equals(Token $token): bool
    {
        return $this->token === $token->toString();
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->token;
    }
}
