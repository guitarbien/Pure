<?php

declare(strict_types=1);

namespace App\Framework\Dbal;

/**
 * Class DatabaseUrl
 * @package App\Framework\Dbal
 */
final class DatabaseUrl
{
    /** @var string */
    private $url;

    /**
     * DatabaseUrl constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->url;
    }
}
