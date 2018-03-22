<?php

declare(strict_types=1);

namespace App\User\Presentation;

use App\Framework\MessageContainer\FlashMessenger;
use App\Framework\Rendering\TemplateRenderer;
use App\User\Application\RegisterUserHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RegistrationController
 * @package App\User\Presentation
 */
final class RegistrationController
{
    /** @var TemplateRenderer */
    private $templateRenderer;

    /** @var RegisterUserFormFactory */
    private $registerUserFormFactory;

    /** @var FlashMessenger */
    private $flashMessenger;

    /** @var RegisterUserHandler */
    private $registerUserHandler;

    /**
     * RegistrationController constructor.
     * @param TemplateRenderer $templateRenderer
     * @param RegisterUserFormFactory $registerUserFormFactory
     * @param FlashMessenger $flashMessenger
     * @param RegisterUserHandler $registerUserHandler
     */
    public function __construct(
        TemplateRenderer $templateRenderer,
        RegisterUserFormFactory $registerUserFormFactory,
        FlashMessenger $flashMessenger,
        RegisterUserHandler $registerUserHandler
    ) {
        $this->templateRenderer = $templateRenderer;
        $this->registerUserFormFactory = $registerUserFormFactory;
        $this->flashMessenger = $flashMessenger;
        $this->registerUserHandler = $registerUserHandler;
    }

    /**
     * @return Response
     */
    public function show(): Response
    {
        $content = $this->templateRenderer->render('Registration.html.twig');

        return new Response($content);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function register(Request $request): Response
    {
        $response = new RedirectResponse('/register');
        $form = $this->registerUserFormFactory->createFormRequest($request);

        if ($form->hasValidationErrors()) {
            foreach ($form->getValidationErrors() as $errorMessage) {
                $this->flashMessenger->add('errors', $errorMessage);
            }

            return $response;
        }

        // register the user
        $this->registerUserHandler->handle($form->toCommand());

        $this->flashMessenger->add('success', 'Your account was created. You can now log in.');

        return $response;
    }
}
