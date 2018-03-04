<?php

declare(strict_types=1);

namespace App\Framework\MessageContainer;

use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class SymfonySessionFlashBag
 * @package App\Framework\MessageContainer
 */
final class SymfonySessionFlashBag implements FlashMessenger
{
    /** @var Session */
    private $session;

    /**
     * SymfonySessionFlashBag constructor.
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function add(string $key, string $value): void
    {
        $this->session->getFlashBag()->add($key, $value);
    }
}
