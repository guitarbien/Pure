<?php

declare(strict_types=1);

namespace App\Submission\Presentation;

use App\Framework\Csrf\StoredTokenValidator;
use App\Framework\Csrf\Token;
use App\Framework\Rendering\TemplateRenderer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

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

    /** @var Session */
    private $session;

    /**
     * SubmissionController constructor.
     * @param TemplateRenderer $templateRenderer
     * @param StoredTokenValidator $storedTokenValidator
     * @param Session $session
     */
    public function __construct(
        TemplateRenderer $templateRenderer,
        StoredTokenValidator $storedTokenValidator,
        Session $session
    ) {
        $this->templateRenderer     = $templateRenderer;
        $this->storedTokenValidator = $storedTokenValidator;
        $this->session              = $session;
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
            $this->session->getFlashBag()->add('errors', 'Invalid token');

            return $response;
        }

        // save the submission

        $this->session->getFlashBag()->add('success', 'Your URL was added successfully');

        return $response;
    }
}
