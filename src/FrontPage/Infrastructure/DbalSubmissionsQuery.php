<?php

declare(strict_types=1);

namespace App\FrontPage\Infrastructure;

use App\FrontPage\Application\Submission;
use App\FrontPage\Application\SubmissionsQuery;
use Doctrine\DBAL\Connection;

/**
 * Class DbalSubmissionsQuery
 * @package App\FrontPage\Infrastructure
 */
final class DbalSubmissionsQuery implements SubmissionsQuery
{
    /** @var Connection */
    private $connection;

    /**
     * DbalSubmissionsQuery constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return Submission[]
     */
    public function execute(): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->addSelect('submissions.title');
        $queryBuilder->addSelect('submissions.url');
        $queryBuilder->addSelect('authors.email');
        $queryBuilder->from('submissions');
        $queryBuilder->join('submissions', 'users', 'authors', 'submissions.author_user_id = authors.id');
        $queryBuilder->orderBy('submissions.creation_date', 'DESC');

        $stmt = $queryBuilder->execute();
        $rows = $stmt->fetchAll();

        /** @var Submission[] $submissions */
        $submissions = [];
        foreach ($rows as $row) {
            $submissions[] = new Submission($row['url'], $row['title'],  $row['email']);
        }

        return $submissions;
    }
}
