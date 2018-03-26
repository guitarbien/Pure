<?php

declare(strict_types=1);

namespace App\User\Domain;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class User
 * @package App\User\Domain
 */
final class User
{
    /** @var UuidInterface */
    private $id;

    /** @var string */
    private $email;

    /** @var string */
    private $passwordHash;

    /** @var DateTimeImmutable */
    private $creationDate;

    /** @var int */
    private $failedLoginAttempts;

    /** @var DateTimeImmutable */
    private $lastFailedLoginAttempt;

    /** @var array */
    private $recordedEvents = [];

    /**
     * User constructor.
     * @param UuidInterface $id
     * @param string $email
     * @param string $passwordHash
     * @param DateTimeImmutable $creationDate
     * @param int $failedLoginAttempts
     * @param DateTimeImmutable|null $lastFailedLoginAttempt
     */
    public function __construct(
        UuidInterface $id,
        string $email,
        string $passwordHash,
        DateTimeImmutable $creationDate,
        int $failedLoginAttempts,
        ?DateTimeImmutable $lastFailedLoginAttempt
    ) {
        $this->id                     = $id;
        $this->email                  = $email;
        $this->passwordHash           = $passwordHash;
        $this->creationDate           = $creationDate;
        $this->failedLoginAttempts    = $failedLoginAttempts;
        $this->lastFailedLoginAttempt = $lastFailedLoginAttempt;
    }

    /**
     * @param string $email
     * @param string $password
     * @return User
     */
    public static function register(string $email, string $password): User
    {
        return new User(
            Uuid::uuid4(),
            $email,
            password_hash($password, PASSWORD_DEFAULT),
            new DateTimeImmutable(),
            0,
            null
        );
    }

    /**
     * @param string $password
     */
    public function logIn(string $password): void
    {
        if (!password_verify($password, $this->getPasswordHash())) {
            $this->lastFailedLoginAttempt = new DateTimeImmutable();
            $this->failedLoginAttempts++;
            return;
        }

        $this->recordedEvents[] = new UserWasLoggedIn();

        $this->failedLoginAttempts = 0;
        $this->lastFailedLoginAttempt = null;
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreationDate(): DateTimeImmutable
    {
        return $this->creationDate;
    }

    /**
     * @return int
     */
    public function getFailedLoginAttempts(): int
    {
        return $this->failedLoginAttempts;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getLastFailedLoginAttempt(): ?DateTimeImmutable
    {
        return $this->lastFailedLoginAttempt;
    }

    /**
     * @return array
     */
    public function getRecordedEvents(): array
    {
        return $this->recordedEvents;
    }

    public function clearRecordedEvents(): void
    {
        $this->recordedEvents = [];
    }
}
