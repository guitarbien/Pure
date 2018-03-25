<?php

declare(strict_types=1);

namespace App\User\Presentation;

use App\Framework\Rendering\TemplateRenderer;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LoginController
 * @package App\User\Presentation
 */
final class LoginController
{
    /** @var TemplateRenderer */
    private $templateRenderer;

    /**
     * LoginController constructor.
     * @param TemplateRenderer $templateRenderer
     */
    public function __construct(TemplateRenderer $templateRenderer)
    {
        $this->templateRenderer = $templateRenderer;
    }

    /**
     * @return Response
     */
    public function show(): Response
    {
        $content = $this->templateRenderer->render('Login.html.twig');

        return new Response($content);
    }
}
