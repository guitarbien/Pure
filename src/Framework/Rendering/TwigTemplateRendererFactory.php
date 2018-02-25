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
    /**
     * @return TwigTemplateRenderer
     */
    public function create(): TwigTemplateRenderer
    {
        $loader = new Twig_Loader_Filesystem([]);
        $twigEnvironment = new Twig_Environment($loader);

        return new TwigTemplateRenderer($twigEnvironment);
    }
}
