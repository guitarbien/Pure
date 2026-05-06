<?php

declare(strict_types=1);

namespace App\Submission\Infrastructure;

use App\Submission\Domain\Submission;
use App\Submission\Domain\SubmissionRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;

final class DbalSubmissionRepository implements SubmissionRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function add(Submission $submission): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->insert('submissions');
        $queryBuilder->values([
            'id'             => $queryBuilder->createNamedParameter($submission->getId()->toString()),
            'author_user_id' => $queryBuilder->createNamedParameter($submission->getAuthorId()->toString()),
            'title'          => $queryBuilder->createNamedParameter($submission->getTitle()),
            'url'            => $queryBuilder->createNamedParameter($submission->getUrl()),
            'creation_date'  => $queryBuilder->createNamedParameter($submission->getCreationDate(), Types::DATETIME_MUTABLE),
        ]);

        $queryBuilder->executeStatement();
    }
}
