<?php

declare(strict_types=1);

namespace App\Framework\Rendering;

use Twig_Environment;

/**
 * Class TwigTemplateRenderer
 * @package App\Framework\Rendering
 */
final class TwigTemplateRenderer implements TemplateRenderer
{
    /** @var Twig_Environment  */
    private $twigEnvironment;

    /**
     * TwigTemplateRenderer constructor.
     * @param $twigEnvironment
     */
    public function __construct(Twig_Environment $twigEnvironment)
    {
        $this->twigEnvironment = $twigEnvironment;
    }

    /**
     * @param string $template
     * @param array $data
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render(string $template, array $data = []): string
    {
        return $this->twigEnvironment->render($template, $data);
    }
}
