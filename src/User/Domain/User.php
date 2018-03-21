<?php

declare(strict_types=1);

namespace App\User\Domain;

use DateTimeImmutable;
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

    /**
     * User constructor.
     * @param UuidInterface $id
     * @param string $email
     * @param string $passwordHash
     * @param DateTimeImmutable $creationDate
     */
    public function __construct(UuidInterface $id, string $email, string $passwordHash, DateTimeImmutable $creationDate)
    {
        $this->id           = $id;
        $this->email        = $email;
        $this->passwordHash = $passwordHash;
        $this->creationDate = $creationDate;
    }
}
