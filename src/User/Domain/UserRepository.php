<?php

declare(strict_types=1);

namespace App\User\Domain;

/**
 * Interface UserRepository
 * @package App\User\Domain
 */
interface UserRepository
{
    /**
     * @param User $user
     */
    public function add(User $user): void;

    /**
     * @param User $user
     */
    public function save(User $user): void;

    /**
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;
}
