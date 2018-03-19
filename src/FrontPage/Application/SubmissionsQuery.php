<?php

declare(strict_types=1);

namespace App\FrontPage\Application;

/**
 * Interface SubmissionsQuery
 * @package App\FrontPage\Application
 */
interface SubmissionsQuery
{
    /**
     * @return array
     */
    public function execute(): array;
}
