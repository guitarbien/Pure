<?php

declare(strict_types=1);

namespace Tests\Unit\Framework\Rendering;

use App\Framework\Csrf\StoredTokenReader;
use App\Framework\Csrf\Token;
use App\Framework\MessageContainer\FlashMessenger;
use App\Framework\Rendering\TemplateDirectory;
use App\Framework\Rendering\TemplateRenderer;
use App\Framework\Rendering\TwigTemplateRenderer;
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

    public function test_create_get_token_function_returns_token_string(): void
    {
        $token = new Token('test-token-value');

        $storedTokenReader = $this->createMock(StoredTokenReader::class);
        $storedTokenReader->expects($this->once())
            ->method('read')
            ->with('csrf_key')
            ->willReturn($token);

        $templateDir = $this->createMock(TemplateDirectory::class);
        $templateDir->method('toString')->willReturn(__DIR__);

        $flashMessenger = $this->createMock(FlashMessenger::class);

        $factory = new TwigTemplateRendererFactory($storedTokenReader, $templateDir, $flashMessenger);
        $renderer = $factory->create();

        $twigEnv = $this->getTwigEnvironment($renderer);
        $getToken = $twigEnv->getFunction('get_token');
        $callable = $getToken->getCallable();

        $result = $callable('csrf_key');
        $this->assertSame('test-token-value', $result);
    }

    public function test_create_get_flash_bag_function_returns_flash_messenger(): void
    {
        $storedTokenReader = $this->createMock(StoredTokenReader::class);
        $templateDir = $this->createMock(TemplateDirectory::class);
        $templateDir->method('toString')->willReturn(__DIR__);
        $flashMessenger = $this->createMock(FlashMessenger::class);

        $factory = new TwigTemplateRendererFactory($storedTokenReader, $templateDir, $flashMessenger);
        $renderer = $factory->create();

        $twigEnv = $this->getTwigEnvironment($renderer);
        $getFlashBag = $twigEnv->getFunction('get_flash_bag');
        $callable = $getFlashBag->getCallable();

        $result = $callable();
        $this->assertSame($flashMessenger, $result);
    }

    private function getTwigEnvironment(TwigTemplateRenderer $renderer): \Twig\Environment
    {
        $reflection = new \ReflectionClass($renderer);
        $prop = $reflection->getProperty('twigEnvironment');
        $prop->setAccessible(true);
        return $prop->getValue($renderer);
    }
}
