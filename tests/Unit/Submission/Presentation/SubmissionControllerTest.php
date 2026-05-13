<?php

declare(strict_types=1);

namespace Tests\Unit\Submission\Presentation;

use App\Framework\MessageContainer\FlashMessenger;
use App\Framework\Rendering\TemplateRenderer;
use App\Framework\RoleBasedAccessControl\AuthenticatedUser;
use App\Framework\RoleBasedAccessControl\Guest;
use App\Framework\RoleBasedAccessControl\Permission\SubmitLink;
use App\Submission\Application\SubmitLinkHandler;
use App\Submission\Presentation\SubmissionController;
use App\Submission\Presentation\SubmissionForm;
use App\Submission\Presentation\SubmissionFormFactory;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class SubmissionControllerTest extends TestCase
{
    private $renderer;
    private $factory;
    private $messenger;
    private $handler;

    protected function setUp(): void
    {
        $this->renderer = $this->createMock(TemplateRenderer::class);
        $this->factory = $this->createMock(SubmissionFormFactory::class);
        $this->messenger = $this->createMock(FlashMessenger::class);
        $this->handler = $this->createMock(SubmitLinkHandler::class);
    }

    public function test_show_renders_form_when_user_has_permission(): void
    {
        $user = $this->createMock(AuthenticatedUser::class);
        $user->method('hasPermission')->willReturn(true);

        $this->renderer->expects($this->once())
            ->method('render')
            ->with('Submission.html.twig')
            ->willReturn('<form></form>');

        $controller = new SubmissionController($this->renderer, $this->factory, $this->messenger, $this->handler, $user);
        $response = $controller->show();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('<form></form>', $response->getContent());
    }

    public function test_show_redirects_to_login_when_user_not_authenticated(): void
    {
        $user = new Guest();

        $this->messenger->expects($this->once())
            ->method('add')
            ->with('errors', 'You have to log in before you can submit a link.');

        $controller = new SubmissionController($this->renderer, $this->factory, $this->messenger, $this->handler, $user);
        $response = $controller->show();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame('/login', $response->getTargetUrl());
    }

    public function test_submit_redirects_when_user_not_authenticated(): void
    {
        $user = new Guest();
        $request = new Request();

        $this->messenger->expects($this->once())
            ->method('add')
            ->with('errors', 'You have to log in before you can submit a link.');

        $controller = new SubmissionController($this->renderer, $this->factory, $this->messenger, $this->handler, $user);
        $response = $controller->submit($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame('/login', $response->getTargetUrl());
    }

    public function test_submit_returns_redirect_with_validation_errors(): void
    {
        $user = $this->createMock(AuthenticatedUser::class);
        $user->method('hasPermission')->willReturn(true);

        $form = $this->createMock(SubmissionForm::class);
        $form->method('getValidationErrors')->willReturn(['Invalid token', 'Title too short']);

        $this->factory->method('createFromRequest')->willReturn($form);

        $this->messenger->expects($this->exactly(2))
            ->method('add')
            ->with('errors', $this->anything());

        $request = new Request();
        $controller = new SubmissionController($this->renderer, $this->factory, $this->messenger, $this->handler, $user);
        $response = $controller->submit($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame('/submit', $response->getTargetUrl());
    }

    public function test_submit_processes_valid_form(): void
    {
        $authorId = Uuid::uuid4();
        $user = $this->createMock(AuthenticatedUser::class);
        $user->method('hasPermission')->willReturn(true);
        $user->method('getId')->willReturn($authorId);

        $form = $this->createMock(SubmissionForm::class);
        $form->method('getValidationErrors')->willReturn([]);

        $submitLink = null;
        $form->expects($this->once())
            ->method('toCommand')
            ->with($user)
            ->willReturnCallback(function ($u) {
                return new \App\Submission\Application\SubmitLink(Uuid::uuid4(), 'https://test.com', 'Test');
            });

        $this->factory->method('createFromRequest')->willReturn($form);
        $this->handler->expects($this->once())->method('handle');
        $this->messenger->expects($this->once())
            ->method('add')
            ->with('success', 'Your URL was added successfully');

        $request = new Request();
        $controller = new SubmissionController($this->renderer, $this->factory, $this->messenger, $this->handler, $user);
        $response = $controller->submit($request);

        $this->assertSame('/submit', $response->getTargetUrl());
    }
}
