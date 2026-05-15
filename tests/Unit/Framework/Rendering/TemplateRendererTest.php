<?php

declare(strict_types=1);

namespace Tests\Unit\Framework\Rendering;

use App\Framework\Csrf\StoredTokenReader;
use App\Framework\MessageContainer\FlashMessenger;
use App\Framework\Rendering\TemplateDirectory;
use App\Framework\Rendering\TemplateRenderer;
use App\Framework\Rendering\TwigTemplateRendererFactory;
use PHPUnit\Framework\TestCase;

class TemplateRendererTest extends TestCase
{
    public function test_render_uses_template_directory(): void
    {
        $templateDir = $this->createMock(TemplateDirectory::class);
        $templateDir->expects($this->once())
            ->method('toString')
            ->willReturn(__DIR__);

        $storedTokenReader = $this->createMock(StoredTokenReader::class);
        $flashMessenger = $this->createMock(FlashMessenger::class);

        $factory = new TwigTemplateRendererFactory($storedTokenReader, $templateDir, $flashMessenger);
        $renderer = $factory->create();

        $this->assertInstanceOf(TemplateRenderer::class, $renderer);
    }
}
