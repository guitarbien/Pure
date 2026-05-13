<?php

declare(strict_types=1);

namespace Tests\Unit\FrontPage\Presentation;

use App\Framework\Rendering\TemplateRenderer;
use App\FrontPage\Application\Submission;
use App\FrontPage\Application\SubmissionsQuery;
use App\FrontPage\Presentation\FrontPageController;
use PHPUnit\Framework\TestCase;

class FrontPageControllerTest extends TestCase
{
    public function test_show_returns_response_with_rendered_content(): void
    {
        $renderer = $this->createMock(TemplateRenderer::class);
        $renderer->expects($this->once())
            ->method('render')
            ->with('FrontPage.html.twig', $this->isType('array'))
            ->willReturn('<html>content</html>');

        $query = $this->createMock(SubmissionsQuery::class);
        $query->expects($this->once())
            ->method('execute')
            ->willReturn([]);

        $controller = new FrontPageController($renderer, $query);
        $response = $controller->show();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('<html>content</html>', $response->getContent());
    }

    public function test_show_passes_submissions_to_template(): void
    {
        $submissions = [
            new Submission('https://example.com', 'Example', 'Alice'),
            new Submission('https://test.com', 'Test', 'Bob'),
        ];

        $renderer = $this->createMock(TemplateRenderer::class);
        $renderer->expects($this->once())
            ->method('render')
            ->with('FrontPage.html.twig', ['submissions' => $submissions])
            ->willReturn('<html></html>');

        $query = $this->createMock(SubmissionsQuery::class);
        $query->expects($this->once())
            ->method('execute')
            ->willReturn($submissions);

        $controller = new FrontPageController($renderer, $query);
        $controller->show();
    }

    public function test_show_renders_with_empty_submissions(): void
    {
        $renderer = $this->createMock(TemplateRenderer::class);
        $renderer->expects($this->once())
            ->method('render')
            ->with('FrontPage.html.twig', ['submissions' => []])
            ->willReturn('<html>No submissions</html>');

        $query = $this->createMock(SubmissionsQuery::class);
        $query->expects($this->once())
            ->method('execute')
            ->willReturn([]);

        $controller = new FrontPageController($renderer, $query);
        $response = $controller->show();

        $this->assertSame('<html>No submissions</html>', $response->getContent());
    }
}
