<?php

declare(strict_types=1);

namespace Tests\Unit\Framework\Rendering;

use App\Framework\Rendering\TwigTemplateRenderer;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

class TwigTemplateRendererTest extends TestCase
{
    public function test_render_delegates_to_twig_environment(): void
    {
        $twig = $this->createMock(Environment::class);
        $twig->expects($this->once())
            ->method('render')
            ->with('template.html.twig', ['key' => 'value'])
            ->willReturn('<html>rendered</html>');

        $renderer = new TwigTemplateRenderer($twig);
        $result = $renderer->render('template.html.twig', ['key' => 'value']);

        $this->assertSame('<html>rendered</html>', $result);
    }

    public function test_render_without_data_passes_empty_array(): void
    {
        $twig = $this->createMock(Environment::class);
        $twig->expects($this->once())
            ->method('render')
            ->with('Page.html.twig', [])
            ->willReturn('page content');

        $renderer = new TwigTemplateRenderer($twig);
        $result = $renderer->render('Page.html.twig');

        $this->assertSame('page content', $result);
    }
}
