<?php

declare(strict_types=1);

namespace App\User\Infrastructure;

use App\User\Domain\User;
use App\User\Domain\UserRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;

/**
 * Class DbalUserRepository
 * @package App\User\Infrastructure
 */
final class DbalUserRepository implements UserRepository
{
    /** @var Connection */
    private $connection;

    /**
     * DbalUserRepository constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param User $user
     */
    public function add(User $user): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->insert('users');
        $queryBuilder->values([
            'id' => $queryBuilder->createNamedParameter($user->getId()->toString()),
            'email' => $queryBuilder->createNamedParameter($user->getEmail()),
            'password_hash' => $queryBuilder->createNamedParameter($user->getPasswordHash()),
            'creation_date' => $queryBuilder->createNamedParameter($user->getCreationDate(), Type::DATETIME),
        ]);

        $queryBuilder->execute();
    }
}
