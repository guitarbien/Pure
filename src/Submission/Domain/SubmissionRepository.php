<?php

declare(strict_types=1);

namespace App\Submission\Domain;

/**
 * Interface SubmissionRepository
 * @package App\Submission\Domain
 */
interface SubmissionRepository
{
    /**
     * @param Submission $submission
     */
    public function add(Submission $submission): void;
}
