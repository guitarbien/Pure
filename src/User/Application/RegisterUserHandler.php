<?php

declare(strict_types=1);

namespace App\User\Application;

use App\User\Domain\User;
use App\User\Domain\UserRepository;

/**
 * Class RegisterUserHandler
 * @package App\User\Application
 */
final class RegisterUserHandler
{
    /** @var UserRepository */
    private $userRepository;

    /**
     * RegisterUserHandler constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param RegisterUser $command
     * @throws \Exception
     */
    public function handle(RegisterUser $command): void
    {
        $user = User::register($command->getEmail(), $command->getPassword());
        $this->userRepository->add($user);
    }
}
