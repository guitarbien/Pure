<?php

declare(strict_types=1);

namespace App\Submission\Presentation;

use App\Framework\Csrf\StoredTokenValidator;
use App\Framework\Rendering\TemplateRenderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SubmissionController
 * @package App\Submission\Presentation
 */
final class SubmissionController
{
    /** @var TemplateRenderer */
    private $templateRenderer;

    /** @var StoredTokenValidator */
    private $storedTokenValidator;

    /**
     * SubmissionController constructor.
     * @param TemplateRenderer $templateRenderer
     * @param StoredTokenValidator $storedTokenValidator
     */
    public function __construct(TemplateRenderer $templateRenderer, StoredTokenValidator $storedTokenValidator)
    {
        $this->templateRenderer     = $templateRenderer;
        $this->storedTokenValidator = $storedTokenValidator;
    }

    /**
     * @return Response
     */
    public function show(): Response
    {
        $content = $this->templateRenderer->render('Submission.html.twig');

        return new Response($content);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function submit(Request $request): Response
    {
        $content = $request->get('title') . ' - ' . $request->get('url');

        return new Response($content);
    }
}
