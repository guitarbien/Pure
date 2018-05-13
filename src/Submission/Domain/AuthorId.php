<?php

declare(strict_types=1);

namespace App\Submission\Domain;

use Ramsey\Uuid\UuidInterface;

/**
 * Class AuthorId
 * @package App\Submission\Domain
 */
final class AuthorId
{
    /** @var string */
    private $id;

    /**
     * AuthorId constructor.
     * @param string $id
     */
    private function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @param UuidInterface $uuid
     * @return AuthorId
     */
    public static function fromUuid(UuidInterface $uuid): AuthorId
    {
        return new AuthorId($uuid->toString());
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->id;
    }
}
