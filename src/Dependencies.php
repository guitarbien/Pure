<?php

declare(strict_types=1);

use App\Framework\Rendering\TemplateDirectory;
use App\Framework\Rendering\TemplateRenderer;
use App\Framework\Rendering\TwigTemplateRendererFactory;
use App\FrontPage\Infrastructure\MockSubmissionQuery;
use App\FrontPage\Presentation\SubmissionsQuery;
use Auryn\Injector;

$injector = new Injector();

$injector->define(TemplateDirectory::class, [':rootDirectory' => ROOT_DIR]);

$injector->delegate(
    TemplateRenderer::class,
    function() use($injector): TemplateRenderer {
        $factory = $injector->make(TwigTemplateRendererFactory::class);
        return $factory->create();
    }
);

$injector->alias(SubmissionsQuery::class, MockSubmissionQuery::class);

// Use share() prevent the injector creating a new instance whenever an object is injected
// The same instance of the object is reused for all classes that use this dependency.
$injector->share(SubmissionsQuery::class);

return $injector;
