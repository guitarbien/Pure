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

    /**
     * Submission constructor.
     * @param string $url
     * @param string $title
     */
    public function __construct(string $url, string $title)
    {
        $this->url   = $url;
        $this->title = $title;
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
