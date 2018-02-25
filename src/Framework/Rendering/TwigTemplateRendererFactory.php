<?php

declare(strict_types=1);

namespace App\Framework\Rendering;

use Twig_Environment;
use Twig_Loader_Filesystem;

/**
 * Class TwigTemplateRendererFactory
 * @package App\Framework\Rendering
 */
final class TwigTemplateRendererFactory
{
    /** @var TemplateDirectory */
    private $templateDirectory;

    /**
     * TwigTemplateRendererFactory constructor.
     * @param TemplateDirectory $templateDirectory
     */
    public function __construct(TemplateDirectory $templateDirectory)
    {
        $this->templateDirectory = $templateDirectory;
    }

    /**
     * @return TwigTemplateRenderer
     */
    public function create(): TwigTemplateRenderer
    {
        $templateDirectory = $this->templateDirectory->toString();
        $loader = new Twig_Loader_Filesystem([$templateDirectory]);
        $twigEnvironment = new Twig_Environment($loader);

        return new TwigTemplateRenderer($twigEnvironment);
    }
}
