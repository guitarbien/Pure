<?php

declare(strict_types=1);

namespace App\FrontPage\Application;

/**
 * Class Submission
 * @package App\FrontPage\Application
 */
final class Submission
{
    /** @var string */
    private $url;

    /** @var string */
    private $title;

    /** @var string */
    private $author;

    /**
     * Submission constructor.
     * @param string $url
     * @param string $title
     * @param string $author
     */
    public function __construct(string $url, string $title, string $author)
    {
        $this->url    = $url;
        $this->title  = $title;
        $this->author = $author;
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

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }
}
