<?php

declare(strict_types=1);

namespace App\FrontPage\Presentation;

use App\Framework\Rendering\TemplateRenderer;
use App\FrontPage\Application\SubmissionsQuery;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class FrontPageController
 * @package App\src\FrontPage\Presentation
 */
final class FrontPageController
{
    /** @var TemplateRenderer */
    private $templateRenderer;

    /** @var SubmissionsQuery */
    private $submissionsQuery;

    /**
     * FrontPageController constructor.
     * @param TemplateRenderer $templateRenderer
     * @param SubmissionsQuery $submissionQuery
     */
    public function __construct(TemplateRenderer $templateRenderer, SubmissionsQuery $submissionQuery)
    {
        $this->templateRenderer = $templateRenderer;
        $this->submissionsQuery = $submissionQuery;
    }

    /**
     * @return Response
     */
    public function show(): Response
    {
        $content = $this->templateRenderer->render('FrontPage.html.twig', [
            'submissions' => $this->submissionsQuery->execute(),
        ]);

        return new Response($content);
    }
}
