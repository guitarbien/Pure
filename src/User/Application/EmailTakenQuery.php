<?php

declare(strict_types=1);

namespace App\User\Application;

/**
 * Interface EmailTakenQuery
 * @package App\User\Application
 */
interface EmailTakenQuery
{
    /**
     * @param string $email
     * @return bool
     */
    public function execute(string $email): bool;
}
