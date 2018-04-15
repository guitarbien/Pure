<?php

declare(strict_types=1);

namespace App\Submission\Domain;

use DateTimeImmutable;
use Exception;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class Submission
 * @package App\Submission\Domain
 */
final class Submission
{
    /** @var UuidInterface */
    private $id;

    /** @var AuthorId */
    private $authorId;

    /** @var string */
    private $url;

    /** @var string */
    private $title;

    /** @var DateTimeImmutable */
    private $creationDate;

    /**
     * Submission constructor.
     * @param UuidInterface $id
     * @param AuthorId $authorId
     * @param string $url
     * @param string $title
     * @param DateTimeImmutable $creationDate
     */
    public function __construct(
        UuidInterface $id,
        AuthorId $authorId,
        string $url,
        string $title,
        DateTimeImmutable $creationDate
    ) {
        $this->id           = $id;
        $this->authorId     = $authorId;
        $this->url          = $url;
        $this->title        = $title;
        $this->creationDate = $creationDate;
    }

    /**
     * @param UuidInterface $authorId
     * @param string $url
     * @param string $title
     * @return Submission
     * @throws Exception
     */
    public static function submit(UuidInterface $authorId, string $url, string $title): self
    {
        return new Submission(Uuid::uuid4(), AuthorId::fromUuid($authorId), $url, $title, new DateTimeImmutable());
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return AuthorId
     */
    public function getAuthorId(): AuthorId
    {
        return $this->authorId;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreationDate(): DateTimeImmutable
    {
        return $this->creationDate;
    }
}
