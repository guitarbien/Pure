<?php

declare(strict_types=1);

namespace App\User\Presentation;

use App\Framework\Csrf\StoredTokenValidator;
use App\Framework\Csrf\Token;
use App\Framework\MessageContainer\FlashMessenger;
use App\Framework\Rendering\TemplateRenderer;
use App\User\Application\LogIn;
use App\User\Application\LogInHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class LoginController
 * @package App\User\Presentation
 */
final class LoginController
{
    /** @var TemplateRenderer */
    private $templateRenderer;

    /** @var StoredTokenValidator */
    private $storedTokenValidator;

    /** @var FlashMessenger */
    private $flashMessenger;

    /** @var LogInHandler */
    private $logInHandler;

    /** @var Session */
    private $session;

    /**
     * LoginController constructor.
     * @param TemplateRenderer $templateRenderer
     * @param StoredTokenValidator $storedTokenValidator
     * @param FlashMessenger $flashMessenger
     * @param LogInHandler $logInHandler
     * @param Session $session
     */
    public function __construct(
        TemplateRenderer $templateRenderer,
        StoredTokenValidator $storedTokenValidator,
        FlashMessenger $flashMessenger,
        LogInHandler $logInHandler,
        Session $session
    ) {
        $this->templateRenderer = $templateRenderer;
        $this->storedTokenValidator = $storedTokenValidator;
        $this->flashMessenger = $flashMessenger;
        $this->logInHandler = $logInHandler;
        $this->session = $session;
    }

    /**
     * @return Response
     */
    public function show(): Response
    {
        $content = $this->templateRenderer->render('Login.html.twig');

        return new Response($content);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function logIn(Request $request): Response
    {
        $this->session->remove('userId');

        if (!$this->storedTokenValidator->validate('login', new Token((string)$request->get('token')))) {
            $this->flashMessenger->add('errors', 'Invalid token');

            return new RedirectResponse('/login');
        }

        $this->logInHandler->handle(new LogIn(
            (string)$request->get('email'),
            (string)$request->get('password')
        ));

        // validate that the user was logged in
        if ($this->session->get('userId') === null) {
            $this->flashMessenger->add('errors', 'Invalid username or password');
            return new RedirectResponse('/login');
        }

        $this->flashMessenger->add('success', 'You were logged in.');
        return new RedirectResponse('/');
    }
}
