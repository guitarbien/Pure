<?php

declare(strict_types=1);

namespace App\Framework\Rendering;

use App\Framework\Csrf\StoredTokenReader;
use App\Framework\MessageContainer\FlashMessenger;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

final class TwigTemplateRendererFactory
{
    private StoredTokenReader $storedTokenReader;
    private TemplateDirectory $templateDirectory;
    private FlashMessenger $flashMessenger;

    public function __construct(
        StoredTokenReader $storedTokenReader,
        TemplateDirectory $templateDirectory,
        FlashMessenger $flashMessenger
    ) {
        $this->storedTokenReader = $storedTokenReader;
        $this->templateDirectory = $templateDirectory;
        $this->flashMessenger    = $flashMessenger;
    }

    public function create(): TwigTemplateRenderer
    {
        $loader          = new FilesystemLoader([$this->templateDirectory->toString()]);
        $twigEnvironment = new Environment($loader);

        $twigEnvironment->addFunction(
            new TwigFunction('get_token', function (string $key): string {
                $token = $this->storedTokenReader->read($key);

                return $token->toString();
            })
        );

        $twigEnvironment->addFunction(
            new TwigFunction('get_flash_bag', function (): FlashMessenger {
                return $this->flashMessenger;
            })
        );

        return new TwigTemplateRenderer($twigEnvironment);
    }
}
