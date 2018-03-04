<?php

declare(strict_types=1);

namespace App\Framework\Csrf;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class SymfonySessionTokenStorage
 * @package App\Framework\Csrf
 */
final class SymfonySessionTokenStorage implements TokenStorage
{
    /** @var SessionInterface */
    private $session;

    /**
     * SymfonySessionTokenStorage constructor.
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @param string $key
     * @param Token $token
     */
    public function store(string $key, Token $token): void
    {
        $this->session->set($key, $token->toString());
    }

    /**
     * @param string $key
     * @return Token|null
     */
    public function retrieve(string $key): ?Token
    {
        $tokenValue = $this->session->get($key);

        if ($tokenValue === null) {
            return null;
        }

        return new Token($tokenValue);
    }
}
