<?php

declare(strict_types=1);

namespace App\Framework\Rendering;

/**
 * Class TemplateDirectory
 * @package App\Framework\Rendering
 */
final class TemplateDirectory
{
    private $templateDirectory;

    /**
     * TemplateDirectory constructor.
     * @param $rootDirectory
     */
    public function __construct(string $rootDirectory)
    {
        $this->templateDirectory = $rootDirectory . '/templates';
    }

    public function toString(): string
    {
        return $this->templateDirectory;
    }
}
