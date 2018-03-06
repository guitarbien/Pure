<?php

declare(strict_types=1);

namespace App\Submission\Application;

/**
 * Class SubmitLink
 * @package App\Submission\Application
 */
final class SubmitLink
{
    /** @var string */
    private $url;

    /** @var string */
    private $title;

    /**
     * SubmitLink constructor.
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
