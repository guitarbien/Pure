<?php

declare(strict_types=1);

namespace App\User\Presentation;

use App\Framework\Csrf\StoredTokenValidator;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RegisterUserFormFactory
 * @package App\User\Presentation
 */
final class RegisterUserFormFactory
{
    /** @var StoredTokenValidator */
    private $storedTokenValidator;

    /**
     * RegisterUserFormFactory constructor.
     * @param StoredTokenValidator $storedTokenValidator
     */
    public function __construct(StoredTokenValidator $storedTokenValidator)
    {
        $this->storedTokenValidator = $storedTokenValidator;
    }

    /**
     * @param Request $request
     * @return RegisterUserForm
     */
    public function createFormRequest(Request $request): RegisterUserForm
    {
        return new RegisterUserForm(
            $this->storedTokenValidator,
            $request->get('token'),
            $request->get('email'),
            $request->get('password')
        );
    }
}
