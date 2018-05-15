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
     * @throws \Exception
     */
    public function handle(LogIn $command): void
    {
        $user = $this->userRepository->findByEmail($command->getEmail());

        if ($user === null) {
            return;
        }

        // authenticate
        $user->logIn($command->getPassword());

        $this->userRepository->save($user);
    }
}
