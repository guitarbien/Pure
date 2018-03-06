<?php

declare(strict_types=1);

namespace App\Submission\Presentation;

use App\Framework\MessageContainer\FlashMessenger;
use App\Framework\Rendering\TemplateRenderer;
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

    /** @var SubmissionFormFactory */
    private $submissionFormFactory;

    /** @var FlashMessenger */
    private $flashMessenger;

    /** @var SubmitLinkHandler */
    private $submitLinkHandler;

    /**
     * SubmissionController constructor.
     * @param TemplateRenderer $templateRenderer
     * @param SubmissionFormFactory $submissionFormFactory
     * @param FlashMessenger $flashMessenger
     * @param SubmitLinkHandler $submitLinkHandler
     */
    public function __construct(
        TemplateRenderer $templateRenderer,
        SubmissionFormFactory $submissionFormFactory,
        FlashMessenger $flashMessenger,
        SubmitLinkHandler $submitLinkHandler
    ) {
        $this->templateRenderer = $templateRenderer;
        $this->submissionFormFactory = $submissionFormFactory;
        $this->flashMessenger = $flashMessenger;
        $this->submitLinkHandler = $submitLinkHandler;
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

        $form = $this->submissionFormFactory->createFromRequest($request);

        if ($form->hasValidationErrors()) {
            foreach ($form->getValidationErrors() as $errorMessage) {
                $this->flashMessenger->add('errors', $errorMessage);
            }

            return $response;
        }

        $this->submitLinkHandler->handle($form->toCommand());

        $this->flashMessenger->add('success', 'Your URL was added successfully');

        return $response;
    }
}
