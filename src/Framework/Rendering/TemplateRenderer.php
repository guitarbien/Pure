<?php

declare(strict_types=1);

namespace App\Framework\Rendering;

/**
 * Interface TemplateRenderer
 * @package App\Framework\Rendering
 */
interface TemplateRenderer
{
    /**
     * @param string $template
     * @param array $data
     * @return string
     */
    public function render(string $template, array $data = []): string;
}
