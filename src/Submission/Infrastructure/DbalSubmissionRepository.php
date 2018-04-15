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
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->insert('submissions');
        $queryBuilder->values([
            'id'             => $queryBuilder->createNamedParameter($submission->getId()->toString()),
            'author_user_id' => $queryBuilder->createNamedParameter($submission->getAuthorId()->toString()),
            'title'          => $queryBuilder->createNamedParameter($submission->getTitle()),
            'url'            => $queryBuilder->createNamedParameter($submission->getUrl()),
            'creation_date'  => $queryBuilder->createNamedParameter($submission->getCreationDate(), 'datetime'),
        ]);

        $queryBuilder->execute();
    }
}
