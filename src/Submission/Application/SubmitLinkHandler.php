<?php

declare(strict_types=1);

namespace App\Submission\Application;

use App\Submission\Domain\Submission;
use App\Submission\Domain\SubmissionRepository;
use Exception;

/**
 * Class SubmitLinkHandler
 * @package App\Submission\Application
 */
final class SubmitLinkHandler
{
    /** @var SubmissionRepository */
    private $submissionRepository;

    /**
     * SubmitLinkHandler constructor.
     * @param SubmissionRepository $submissionRepository
     */
    public function __construct(SubmissionRepository $submissionRepository)
    {
        $this->submissionRepository = $submissionRepository;
    }

    /**
     * @param SubmitLink $command
     * @throws Exception
     */
    public function handle(SubmitLink $command): void
    {
        $submission = Submission::submit($command->getAuthorId(), $command->getUrl(), $command->getTitle());
        $this->submissionRepository->add($submission);
    }
}
