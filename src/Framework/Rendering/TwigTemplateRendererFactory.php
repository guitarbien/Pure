<?php

declare(strict_types=1);

namespace App\Framework\Rendering;

use App\Framework\Csrf\StoredTokenReader;
use Twig_Environment;
use Twig_Function;
use Twig_Loader_Filesystem;

/**
 * Class TwigTemplateRendererFactory
 * @package App\Framework\Rendering
 */
final class TwigTemplateRendererFactory
{
    /** @var StoredTokenReader */
    private $storedTokenReader;

    /** @var TemplateDirectory */
    private $templateDirectory;

    /**
     * TwigTemplateRendererFactory constructor.
     * @param StoredTokenReader $storedTokenReader
     * @param TemplateDirectory $templateDirectory
     */
    public function __construct(StoredTokenReader $storedTokenReader, TemplateDirectory $templateDirectory)
    {
        $this->storedTokenReader = $storedTokenReader;
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

        $twigEnvironment->addFunction(
            new Twig_Function('get_token', function(string $key): string {
                $token = $this->storedTokenReader->read($key);
                return $token->toString();
            })
        );

        return new TwigTemplateRenderer($twigEnvironment);
    }
}
