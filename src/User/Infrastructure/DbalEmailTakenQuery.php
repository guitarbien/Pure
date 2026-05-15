<?php

declare(strict_types=1);

namespace App\User\Infrastructure;

use App\User\Application\EmailTakenQuery;
use Doctrine\DBAL\Connection;

final class DbalEmailTakenQuery implements EmailTakenQuery
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function execute(string $email): bool
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->select('count(*)');
        $queryBuilder->from('users');
        $queryBuilder->where("email = {$queryBuilder->createNamedParameter($email)}");

        return (bool) $queryBuilder->executeQuery()->fetchOne();
    }
}
