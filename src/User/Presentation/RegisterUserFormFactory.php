<?php

declare(strict_types=1);

namespace App\User\Presentation;

use App\Framework\Csrf\StoredTokenValidator;
use App\User\Application\EmailTakenQuery;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RegisterUserFormFactory
 * @package App\User\Presentation
 */
final class RegisterUserFormFactory
{
    /** @var StoredTokenValidator */
    private $storedTokenValidator;

    /** @var EmailTakenQuery */
    private $emailTakenQuery;

    /**
     * RegisterUserFormFactory constructor.
     * @param StoredTokenValidator $storedTokenValidator
     * @param EmailTakenQuery $emailTakenQuery
     */
    public function __construct(StoredTokenValidator $storedTokenValidator, EmailTakenQuery $emailTakenQuery)
    {
        $this->storedTokenValidator = $storedTokenValidator;
        $this->emailTakenQuery      = $emailTakenQuery;
    }

    /**
     * @param Request $request
     * @return RegisterUserForm
     */
    public function createFormRequest(Request $request): RegisterUserForm
    {
        return new RegisterUserForm(
            $this->storedTokenValidator,
            $this->emailTakenQuery,
            $request->get('token'),
            $request->get('email'),
            $request->get('password')
        );
    }
}
