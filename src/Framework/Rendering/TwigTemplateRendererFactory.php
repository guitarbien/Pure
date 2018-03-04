<?php

declare(strict_types=1);

namespace App\Framework\Rendering;

use App\Framework\Csrf\StoredTokenReader;
use App\Framework\MessageContainer\FlashMessenger;
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

    /** @var FlashMessenger */
    private $flashMessenger;

    /**
     * TwigTemplateRendererFactory constructor.
     * @param StoredTokenReader $storedTokenReader
     * @param TemplateDirectory $templateDirectory
     * @param FlashMessenger $flashMessenger
     */
    public function __construct(
        StoredTokenReader $storedTokenReader,
        TemplateDirectory $templateDirectory,
        FlashMessenger $flashMessenger
    ) {
        $this->storedTokenReader = $storedTokenReader;
        $this->templateDirectory = $templateDirectory;
        $this->flashMessenger    = $flashMessenger;
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

        $twigEnvironment->addFunction(
            new Twig_Function('get_flash_bag', function(): FlashMessenger {
                return $this->flashMessenger;
            })
        );

        return new TwigTemplateRenderer($twigEnvironment);
    }
}
