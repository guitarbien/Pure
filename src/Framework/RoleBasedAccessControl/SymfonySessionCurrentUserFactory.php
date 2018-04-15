<?php

declare(strict_types=1);

namespace App\Framework\RoleBasedAccessControl;

use App\Framework\RoleBasedAccessControl\Role\Author;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class SymfonySessionCurrentUserFactory
 * @package App\Framework\RoleBasedAccessControl
 */
final class SymfonySessionCurrentUserFactory implements CurrentUserFactory
{
    /** @var Session */
    private $session;

    /**
     * SymfonySessionCurrentUserFactory constructor.
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @return User
     */
    public function create(): User
    {
        if (!$this->session->has('userId')) {
            return new Guest();
        }

        return new AuthenticatedUser(
            Uuid::fromString($this->session->get('userId')),
            [new Author()]
        );
    }
}
