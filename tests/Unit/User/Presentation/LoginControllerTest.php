<?php

declare(strict_types=1);

namespace Tests\Unit\User\Presentation;

use App\Framework\Csrf\StoredTokenValidator;
use App\Framework\MessageContainer\FlashMessenger;
use App\Framework\Rendering\TemplateRenderer;
use App\User\Application\LogInHandler;
use App\User\Presentation\LoginController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class LoginControllerTest extends TestCase
{
    private $renderer;
    private $validator;
    private $messenger;
    private $handler;

    protected function setUp(): void
    {
        $this->renderer = $this->createMock(TemplateRenderer::class);
        $this->validator = $this->createMock(StoredTokenValidator::class);
        $this->messenger = $this->createMock(FlashMessenger::class);
        $this->handler = $this->createMock(LogInHandler::class);
    }

    public function test_show_renders_login_form(): void
    {
        $this->renderer->expects($this->once())
            ->method('render')
            ->with('Login.html.twig')
            ->willReturn('<form></form>');

        $session = new Session(new MockArraySessionStorage());
        $controller = new LoginController($this->renderer, $this->validator, $this->messenger, $this->handler, $session);
        $response = $controller->show();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('<form></form>', $response->getContent());
    }

    public function test_login_redirects_with_invalid_token(): void
    {
        $this->validator->method('validate')->willReturn(false);

        $this->messenger->expects($this->once())
            ->method('add')
            ->with('errors', 'Invalid token');

        $session = new Session(new MockArraySessionStorage());
        $request = new Request(['token' => 'bad-token', 'email' => 'user@example.com', 'password' => 'pass']);

        $controller = new LoginController($this->renderer, $this->validator, $this->messenger, $this->handler, $session);
        $response = $controller->logIn($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame('/login', $response->getTargetUrl());
    }

    public function test_login_clears_session_user_id(): void
    {
        $this->validator->method('validate')->willReturn(true);

        $session = new Session(new MockArraySessionStorage());
        $session->set('userId', 'some-old-value');
        $request = new Request(['token' => 'valid', 'email' => 'user@example.com', 'password' => 'pass']);

        $controller = new LoginController($this->renderer, $this->validator, $this->messenger, $this->handler, $session);
        $controller->logIn($request);

        $this->assertNull($session->get('userId'));
    }

    public function test_login_redirects_when_handler_does_not_set_user_id(): void
    {
        $this->validator->method('validate')->willReturn(true);
        $this->handler->method('handle')->will($this->returnCallback(function () {
            // handler does not set userId in session
        }));

        $this->messenger->expects($this->once())
            ->method('add')
            ->with('errors', 'Invalid username or password');

        $session = new Session(new MockArraySessionStorage());
        $request = new Request(['token' => 'valid', 'email' => 'wrong@example.com', 'password' => 'wrong']);

        $controller = new LoginController($this->renderer, $this->validator, $this->messenger, $this->handler, $session);
        $response = $controller->logIn($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame('/login', $response->getTargetUrl());
    }

    public function test_login_succeeds_and_redirects_to_home(): void
    {
        $this->validator->method('validate')->willReturn(true);
        $this->handler->method('handle')->will($this->returnCallback(function () use (&$session) {
            // handler sets userId
        }));

        $this->messenger->expects($this->once())
            ->method('add')
            ->with('success', 'You were logged in.');

        $session = new Session(new MockArraySessionStorage());
        $session->start();

        $request = new Request(['token' => 'valid', 'email' => 'user@example.com', 'password' => 'correct']);

        $controller = new LoginController($this->renderer, $this->validator, $this->messenger, $this->handler, $session);

        // simulate handler setting the userId
        $this->handler->expects($this->once())->method('handle')->will($this->returnCallback(function () use ($session) {
            $session->set('userId', 'user-123');
        }));

        $response = $controller->logIn($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame('/', $response->getTargetUrl());
    }

    // --- 邊界條件：空值 ---

    public function test_login_with_empty_email_fails_gracefully(): void
    {
        $this->validator->method('validate')->willReturn(true);
        $this->handler->expects($this->once())->method('handle');

        $session = new Session(new MockArraySessionStorage());
        $request = new Request(['token' => 'valid', 'email' => '', 'password' => 'pass']);

        $controller = new LoginController($this->renderer, $this->validator, $this->messenger, $this->handler, $session);
        $response = $controller->logIn($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame('/login', $response->getTargetUrl());
    }

    public function test_login_with_empty_password_fails_gracefully(): void
    {
        $this->validator->method('validate')->willReturn(true);
        $this->handler->expects($this->once())->method('handle');

        $session = new Session(new MockArraySessionStorage());
        $request = new Request(['token' => 'valid', 'email' => 'user@example.com', 'password' => '']);

        $controller = new LoginController($this->renderer, $this->validator, $this->messenger, $this->handler, $session);
        $response = $controller->logIn($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame('/login', $response->getTargetUrl());
    }

    // --- 邊界條件：特殊輸入 ---

    public function test_login_with_emoji_password(): void
    {
        $this->validator->method('validate')->willReturn(true);
        $this->handler->expects($this->once())->method('handle');

        $session = new Session(new MockArraySessionStorage());
        $request = new Request(['token' => 'valid', 'email' => 'user@example.com', 'password' => '🔑🔒🔥']);

        $controller = new LoginController($this->renderer, $this->validator, $this->messenger, $this->handler, $session);
        $response = $controller->logIn($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_login_with_very_long_email(): void
    {
        $this->validator->method('validate')->willReturn(true);
        $this->handler->expects($this->once())->method('handle');

        $longEmail = str_repeat('a', 500) . '@example.com';
        $session = new Session(new MockArraySessionStorage());
        $request = new Request(['token' => 'valid', 'email' => $longEmail, 'password' => 'pass']);

        $controller = new LoginController($this->renderer, $this->validator, $this->messenger, $this->handler, $session);
        $response = $controller->logIn($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    // --- 邊界條件：missing token ---

    public function test_login_with_missing_token(): void
    {
        $this->validator->method('validate')->willReturn(false);
        $this->messenger->expects($this->once())->method('add')->with('errors', 'Invalid token');

        $session = new Session(new MockArraySessionStorage());
        $request = new Request(['email' => 'user@example.com', 'password' => 'pass']);

        $controller = new LoginController($this->renderer, $this->validator, $this->messenger, $this->handler, $session);
        $response = $controller->logIn($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame('/login', $response->getTargetUrl());
    }
}
