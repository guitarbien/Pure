<?php

declare(strict_types=1);

namespace App\User\Infrastructure;

use App\User\Domain\User;
use App\User\Domain\UserRepository;
use App\User\Domain\UserWasLoggedIn;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use LogicException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class DbalUserRepository
 * @package App\User\Infrastructure
 */
final class DbalUserRepository implements UserRepository
{
    /** @var Connection */
    private $connection;

    /** @var Session */
    private $session;

    /**
     * DbalUserRepository constructor.
     * @param Connection $connection
     * @param Session $session
     */
    public function __construct(Connection $connection, Session $session)
    {
        $this->connection = $connection;
        $this->session    = $session;
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

    /**
     * @param User $user
     */
    public function save(User $user): void
    {
        foreach ($user->getRecordedEvents() as $event) {
            if ($event instanceof UserWasLoggedIn) {
                $this->session->set('userId', $user->getId()->toString());
                continue;
            }

            throw new LogicException(get_class($event) . ' was not handled');
        }

        $user->clearRecordedEvents();

        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->update('users');
        $queryBuilder->set('email', $queryBuilder->createNamedParameter($user->getEmail()));
        $queryBuilder->set('password_hash', $queryBuilder->createNamedParameter($user->getPasswordHash()));
        $queryBuilder->set('failed_login_attempts', $queryBuilder->createNamedParameter($user->getFailedLoginAttempts()));
        $queryBuilder->set('last_failed_login_attempt', $queryBuilder->createNamedParameter($user->getLastFailedLoginAttempt(), Type::DATETIME));

        $queryBuilder->execute();
    }

    /**
     * @param string $email
     * @return User|null
     * @throws \Exception
     */
    public function findByEmail(string $email): ?User
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->addSelect('id');
        $queryBuilder->addSelect('email');
        $queryBuilder->addSelect('password_hash');
        $queryBuilder->addSelect('creation_date');
        $queryBuilder->addSelect('failed_login_attempts');
        $queryBuilder->addSelect('last_failed_login_attempt');
        $queryBuilder->from('users');
        $queryBuilder->where("email = {$queryBuilder->createNamedParameter($email)}");

        $stmt = $queryBuilder->execute();
        $row = $stmt->fetch();

        if (empty($row)) {
            return null;
        }

        return $this->createUserFromRow($row);
    }

    /**
     * @param array $row
     * @return User
     * @throws \Exception
     */
    private function createUserFromRow(array $row): User
    {
        $lastFailedLoginAttempt = null;
        if ($row['last_failed_login_attempt']) {
            $lastFailedLoginAttempt = new DateTimeImmutable($row['last_failed_login_attempt']);
        }

        return new User(
            Uuid::fromString($row['id']),
            $row['email'],
            $row['password_hash'],
            new DateTimeImmutable($row['creation_date']),
            (int)$row['failed_login_attempts'],
            $lastFailedLoginAttempt
        );
    }
}
