<?php

declare(strict_types=1);

namespace App\Framework\MessageContainer;

/**
 * Interface FlashMessenger
 * @package App\Framework\MessageContainer
 */
interface FlashMessenger
{
    /**
     * @param string $key
     * @param string $value
     */
    public function add(string $key, string $value): void;

    /**
     * @param string $key
     * @return string[]
     */
    public function get(string $key): array;
}
