<?php

declare(strict_types=1);

namespace App\Submission\Presentation;

use App\Framework\Csrf\StoredTokenValidator;
use App\Framework\Csrf\Token;
use App\Framework\MessageContainer\FlashMessenger;
use App\Framework\Rendering\TemplateRenderer;
use App\Submission\Application\SubmitLink;
use App\Submission\Application\SubmitLinkHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

    /** @var FlashMessenger */
    private $flashMessenger;

    /** @var SubmitLinkHandler */
    private $submitLinkHandler;

    /**
     * SubmissionController constructor.
     * @param TemplateRenderer $templateRenderer
     * @param StoredTokenValidator $storedTokenValidator
     * @param FlashMessenger $flashMessenger
     * @param SubmitLinkHandler $submitLinkHandler
     */
    public function __construct(
        TemplateRenderer $templateRenderer,
        StoredTokenValidator $storedTokenValidator,
        FlashMessenger $flashMessenger,
        SubmitLinkHandler $submitLinkHandler
    ) {
        $this->templateRenderer     = $templateRenderer;
        $this->storedTokenValidator = $storedTokenValidator;
        $this->flashMessenger       = $flashMessenger;
        $this->submitLinkHandler    = $submitLinkHandler;
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
        $response = new RedirectResponse('/submit');

        if (!$this->storedTokenValidator->validate('submission', new Token($request->get('token')))) {
            $this->flashMessenger->add('errors', 'Invalid token');

            return $response;
        }

        $this->submitLinkHandler->handle(new SubmitLink(
            $request->get('url'),
            $request->get('title')
        ));

        $this->flashMessenger->add('success', 'Your URL was added successfully');

        return $response;
    }
}
