<?php

declare(strict_types=1);

namespace App\User\Infrastructure;

use App\User\Application\EmailTakenQuery;
use Doctrine\DBAL\Connection;

/**
 * Class DbalEmailTakenQuery
 * @package App\User\Infrastructure
 */
final class DbalEmailTakenQuery implements EmailTakenQuery
{
    /** @var Connection */
    private $connection;

    /**
     * DbalEmailTakenQuery constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $email
     * @return bool
     */
    public function execute(string $email): bool
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->select('count(*)');
        $queryBuilder->from('users');
        $queryBuilder->where("email = {$queryBuilder->createNamedParameter($email)}");

        $stmt = $queryBuilder->execute();

        return (bool)$stmt->fetchColumn();
    }
}
