<?php

declare(strict_types=1);

namespace App\Framework\Csrf;

/**
 * Class StoredTokenValidator
 * @package App\Framework\Csrf
 */
final class StoredTokenValidator
{
    /** @var TokenStorage */
    private $tokenStorage;

    /**
     * StoredTokenValidator constructor.
     * @param TokenStorage $tokenStorage
     */
    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param string $key
     * @param Token $token
     * @return bool
     */
    public function validate(string $key, Token $token): bool
    {
        return $this->tokenStorage->retrieve($key)->equals($token);
    }
}
