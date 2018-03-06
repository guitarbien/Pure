<?php

declare(strict_types=1);

namespace App\Submission\Domain;

use DateTimeImmutable;
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

    /** @var string */
    private $title;

    /** @var string */
    private $url;

    /** @var DateTimeImmutable */
    private $creationDate;

    /**
     * Submission constructor.
     * @param UuidInterface $id
     * @param string $title
     * @param string $url
     * @param DateTimeImmutable $creationDate
     */
    private function __construct(UuidInterface $id, string $title, string $url, DateTimeImmutable $creationDate)
    {
        $this->id           = $id;
        $this->title        = $title;
        $this->url          = $url;
        $this->creationDate = $creationDate;
    }

    /**
     * @param string $url
     * @param string $title
     * @return Submission
     */
    public static function submit(string $url, string $title): self
    {
        return new Submission(Uuid::uuid4(), $url, $title, new DateTimeImmutable());
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
