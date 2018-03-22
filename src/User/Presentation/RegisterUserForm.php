<?php

declare(strict_types=1);

namespace App\User\Presentation;

use App\Framework\Csrf\StoredTokenValidator;
use App\Framework\Csrf\Token;
use App\User\Application\EmailTakenQuery;
use App\User\Application\RegisterUser;

/**
 * Class RegisterUserForm
 * @package App\User\Presentation
 */
final class RegisterUserForm
{
    /** @var StoredTokenValidator */
    private $storedTokenValidator;

    /** @var EmailTakenQuery */
    private $emailTakenQuery;

    /** @var string */
    private $token;

    /** @var string */
    private $email;

    /** @var string */
    private $password;

    /**
     * RegisterUserForm constructor.
     * @param StoredTokenValidator $storedTokenValidator
     * @param EmailTakenQuery $emailTakenQuery
     * @param string $token
     * @param string $email
     * @param string $password
     */
    public function __construct(
        StoredTokenValidator $storedTokenValidator,
        EmailTakenQuery $emailTakenQuery,
        string $token,
        string $email,
        string $password
    ) {
        $this->storedTokenValidator = $storedTokenValidator;
        $this->emailTakenQuery      = $emailTakenQuery;
        $this->token                = $token;
        $this->email                = $email;
        $this->password             = $password;
    }

    /**
     * @return bool
     */
    public function hasValidationErrors(): bool
    {
        return count($this->getValidationErrors()) > 0;
    }

    /**
     * @return array
     */
    public function getValidationErrors(): array
    {
        $errors = [];

        if (!$this->storedTokenValidator->validate('registration', new Token($this->token))) {
            $errors[] = 'Invalid token';
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Incorrect email format';
        }

        if (strlen($this->password) < 8) {
            $errors[] = 'Password must be at least 8 characters';
        }

        if ($this->emailTakenQuery->execute($this->email)) {
            $errors[] = 'This email is already being used';
        }

        return $errors;
    }

    /**
     * @return RegisterUser
     */
    public function toCommand(): RegisterUser
    {
        return new RegisterUser($this->email, $this->password);
    }
}
