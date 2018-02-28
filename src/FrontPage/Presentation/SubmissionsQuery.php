<?php

declare(strict_types=1);

namespace App\FrontPage\Presentation;

/**
 * Interface SubmissionsQuery
 * @package App\FrontPage\Presentation
 */
interface SubmissionsQuery
{
    /**
     * @return array
     */
    public function execute(): array;
}
