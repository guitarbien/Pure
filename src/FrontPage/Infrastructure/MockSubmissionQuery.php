<?php

declare(strict_types=1);

namespace App\FrontPage\Infrastructure;

use App\FrontPage\Application\Submission;
use App\FrontPage\Application\SubmissionsQuery;

/**
 * Class MockSubmissionQuery
 * @package App\FrontPage\Infrastructure
 */
final class MockSubmissionQuery implements SubmissionsQuery
{
    /** @var Submission[] */
    private $submissions;

    /**
     * MockSubmissionQuery constructor.
     */
    public function __construct()
    {
        $this->submissions = [
            new Submission('https://duckduckgo.com', 'DuckDuckGo'),
            new Submission('https://google.com', 'Google'),
            new Submission('https://bing.com', 'Bing'),
        ];
    }

    /**
     * @return array
     */
    public function execute(): array
    {
        return $this->submissions;
    }
}
