<?php

declare(strict_types=1);

use App\Framework\Rendering\TemplateRenderer;
use App\Framework\Rendering\TwigTemplateRendererFactory;
use Auryn\Injector;

$injector = new Injector();

$injector->delegate(
    TemplateRenderer::class,
    function() use($injector): TemplateRenderer {
        $factory = $injector->make(TwigTemplateRendererFactory::class);
        return $factory->create();
    }
);

return $injector;
