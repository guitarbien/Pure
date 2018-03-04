<?php

declare(strict_types=1);

namespace App\Framework\Csrf;

/**
 * Interface TokenStorage
 * @package App\Framework\Csrf
 */
interface TokenStorage
{
    /**
     * @param string $key
     * @param Token $token
     */
    public function store(string $key, Token $token): void;

    /**
     * @param string $key
     * @return Token|null
     */
    public function retrieve(string $key): ?Token;
}
