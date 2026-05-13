<?php

declare(strict_types=1);

namespace Tests\Unit\User\Presentation;

use App\Framework\MessageContainer\FlashMessenger;
use App\Framework\Rendering\TemplateRenderer;
use App\User\Application\RegisterUserHandler;
use App\User\Presentation\RegisterUserForm;
use App\User\Presentation\RegisterUserFormFactory;
use App\User\Presentation\RegistrationController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class RegistrationControllerTest extends TestCase
{
    private $renderer;
    private $factory;
    private $messenger;
    private $handler;

    protected function setUp(): void
    {
        $this->renderer = $this->createMock(TemplateRenderer::class);
        $this->factory = $this->createMock(RegisterUserFormFactory::class);
        $this->messenger = $this->createMock(FlashMessenger::class);
        $this->handler = $this->createMock(RegisterUserHandler::class);
    }

    public function test_show_renders_registration_form(): void
    {
        $this->renderer->expects($this->once())
            ->method('render')
            ->with('Registration.html.twig')
            ->willReturn('<form></form>');

        $controller = new RegistrationController($this->renderer, $this->factory, $this->messenger, $this->handler);
        $response = $controller->show();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('<form></form>', $response->getContent());
    }

    public function test_register_returns_redirect_with_validation_errors(): void
    {
        $form = $this->createMock(RegisterUserForm::class);
        $form->method('getValidationErrors')->willReturn(['Invalid email', 'Password too short']);

        $this->factory->method('createFormRequest')->willReturn($form);

        $this->messenger->expects($this->exactly(2))
            ->method('add')
            ->with('errors', $this->anything());

        $request = new Request();
        $controller = new RegistrationController($this->renderer, $this->factory, $this->messenger, $this->handler);
        $response = $controller->register($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame('/register', $response->getTargetUrl());
    }

    public function test_register_processes_valid_form(): void
    {
        $form = $this->createMock(RegisterUserForm::class);
        $form->method('getValidationErrors')->willReturn([]);

        $registerUserCmd = new \App\User\Application\RegisterUser('user@example.com', 'password123');
        $form->expects($this->once())
            ->method('toCommand')
            ->willReturn($registerUserCmd);

        $this->factory->method('createFormRequest')->willReturn($form);

        $this->handler->expects($this->once())
            ->method('handle')
            ->with($registerUserCmd);

        $this->messenger->expects($this->once())
            ->method('add')
            ->with('success', 'Your account was created. You can now log in.');

        $request = new Request();
        $controller = new RegistrationController($this->renderer, $this->factory, $this->messenger, $this->handler);
        $response = $controller->register($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame('/register', $response->getTargetUrl());
    }

    public function test_register_with_no_errors_succeeds(): void
    {
        $form = $this->createMock(RegisterUserForm::class);
        $form->method('getValidationErrors')->willReturn([]);
        $form->method('toCommand')->willReturn(new \App\User\Application\RegisterUser('new@example.com', 'pass12345'));

        $this->factory->method('createFormRequest')->willReturn($form);
        $this->handler->expects($this->once())->method('handle');

        $request = new Request();
        $controller = new RegistrationController($this->renderer, $this->factory, $this->messenger, $this->handler);
        $response = $controller->register($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
    }
}
