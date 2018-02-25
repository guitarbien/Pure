<?php

declare(strict_types=1);

namespace App\FrontPage\Presentation;

use App\Framework\Rendering\TemplateRenderer;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request $request
     * @return Response
     */
    public function show(Request $request): Response
    {
        $content = 'Hello ' . $request->get('name', 'default');

        return new Response($content);
    }
}
