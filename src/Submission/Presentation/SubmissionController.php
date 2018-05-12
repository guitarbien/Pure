<?php

declare(strict_types=1);

namespace App\Submission\Presentation;

use App\Framework\MessageContainer\FlashMessenger;
use App\Framework\Rendering\TemplateRenderer;
use App\Framework\RoleBasedAccessControl\AuthenticatedUser;
use App\Framework\RoleBasedAccessControl\Permission\SubmitLink;
use App\Framework\RoleBasedAccessControl\User;
use App\Submission\Application\SubmitLinkHandler;
use Exception;
use LogicException;
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

    /** @var User */
    private $user;

    /** @var string */
    const ERROR_FLASH_KEY = 'errors';

    /**
     * SubmissionController constructor.
     * @param TemplateRenderer $templateRenderer
     * @param SubmissionFormFactory $submissionFormFactory
     * @param FlashMessenger $flashMessenger
     * @param SubmitLinkHandler $submitLinkHandler
     * @param User $user
     */
    public function __construct(
        TemplateRenderer $templateRenderer,
        SubmissionFormFactory $submissionFormFactory,
        FlashMessenger $flashMessenger,
        SubmitLinkHandler $submitLinkHandler,
        User $user
    ) {
        $this->templateRenderer = $templateRenderer;
        $this->submissionFormFactory = $submissionFormFactory;
        $this->flashMessenger = $flashMessenger;
        $this->submitLinkHandler = $submitLinkHandler;
        $this->user = $user;
    }

    /**
     * @return Response
     */
    public function show(): Response
    {
        if (!$this->user->hasPermission(new SubmitLink())) {
            $this->flashMessenger->add(
                self::ERROR_FLASH_KEY,
                'You have to log in before you can submit a link.'
            );

            return new RedirectResponse('/login');
        }

        $content = $this->templateRenderer->render('Submission.html.twig');

        return new Response($content);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function submit(Request $request): Response
    {
        if (!$this->user->hasPermission(new SubmitLink())) {
            $this->flashMessenger->add(
                self::ERROR_FLASH_KEY,
                'You have to log in before you can submit a link.'
            );

            return new RedirectResponse('/login');
        }

        $response = new RedirectResponse('/submit');

        $form = $this->submissionFormFactory->createFromRequest($request);

        $errors = $form->getValidationErrors();
        if (count($errors) > 0) {
            foreach ($errors as $errorMessage) {
                $this->flashMessenger->add(self::ERROR_FLASH_KEY, $errorMessage);
            }

            return $response;
        }

        if (!$this->user instanceof AuthenticatedUser) {
            throw new LogicException('Only authenticated users can submit links');
        }

        $this->submitLinkHandler->handle($form->toCommand($this->user));

        $this->flashMessenger->add('success', 'Your URL was added successfully');

        return $response;
    }
}
