<?php

declare(strict_types=1);

namespace App\Framework\Csrf;

/**
 * Class StoredTokenReader
 * @package App\Framework\Csrf
 */
final class StoredTokenReader
{
    /** @var TokenStorage */
    private $tokenStorage;

    /**
     * StoredTokenReader constructor.
     * @param TokenStorage $tokenStorage
     */
    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param string $key
     * @return Token
     * @throws \Exception
     */
    public function read(string $key): Token
    {
        $token = $this->tokenStorage->retrieve($key);

        if ($token !== null) {
            return $token;
        }

        $token = Token::generate();
        $this->tokenStorage->store($key, $token);

        return $token;
    }
}
