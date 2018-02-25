<?php

declare(strict_types=1);

namespace App\FrontPage\Presentation;

use App\Framework\Rendering\TemplateRenderer;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class FrontPageController
 * @package App\src\FrontPage\Presentation
 */
final class FrontPageController
{
    /** @var TemplateRenderer  */
    private $templateRenderer;

    /**
     * FrontPageController constructor.
     * @param $templateRenderer
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
        $content = $this->templateRenderer->render('FrontPage.html.twig');

        return new Response($content);
    }
}
