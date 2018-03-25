<?php

declare(strict_types=1);

namespace App\User\Application;

use App\User\Domain\UserRepository;

/**
 * Class LogInHandler
 * @package App\User\Application
 */
final class LogInHandler
{
    /** @var UserRepository */
    private $userRepository;

    /**
     * LogInHandler constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param LogIn $command
     */
    public function handle(LogIn $command): void
    {

    }
}
