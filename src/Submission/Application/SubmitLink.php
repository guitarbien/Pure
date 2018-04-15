<?php

declare(strict_types=1);

namespace App\Submission\Application;

use Ramsey\Uuid\UuidInterface;

/**
 * Class SubmitLink
 * @package App\Submission\Application
 */
final class SubmitLink
{
    /** @var UuidInterface */
    private $authorId;

    /** @var string */
    private $url;

    /** @var string */
    private $title;

    /**
     * SubmitLink constructor.
     * @param UuidInterface $authorId
     * @param string $url
     * @param string $title
     */
    public function __construct(UuidInterface $authorId, string $url, string $title)
    {
        $this->authorId = $authorId;
        $this->url      = $url;
        $this->title    = $title;
    }

    /**
     * @return UuidInterface
     */
    public function getAuthorId(): UuidInterface
    {
        return $this->authorId;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
}
