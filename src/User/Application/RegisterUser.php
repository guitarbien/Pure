<?php

declare(strict_types=1);

namespace App\User\Application;

/**
 * Class RegisterUser
 * @package App\User\Application
 */
final class RegisterUser
{
    /** @var string */
    private $email;

    /** @var string */
    private $password;

    /**
     * RegisterUser constructor.
     * @param string $email
     * @param string $password
     */
    public function __construct(string $email, string $password)
    {
        $this->email    = $email;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
