<?php

declare(strict_types=1);

namespace App\Submission\Infrastructure;

use App\Submission\Domain\Submission;
use App\Submission\Domain\SubmissionRepository;
use Doctrine\DBAL\Connection;

/**
 * Class DbalSubmissionRepository
 * @package App\Submission\Infrastructure
 */
final class DbalSubmissionRepository implements SubmissionRepository
{
    /** @var Connection */
    private $connection;

    /**
     * DbalSubmissionRepository constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param Submission $submission
     */
    public function add(Submission $submission): void
    {
        $this->connection->exec("
            INSERT INTO
            submissions (id, title, url, creation_date) VALUES (
                '{$submission->getId()->toString()}', 
                '{$submission->getTitle()}',
                '{$submission->getUrl()}', 
                '{$submission->getCreationDate()->format('Y-m-d H:i:s')}'
            );
        ");
    }
}
