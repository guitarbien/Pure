<?php

declare(strict_types=1);

use App\Framework\Csrf\SymfonySessionTokenStorage;
use App\Framework\Csrf\TokenStorage;
use App\Framework\Dbal\ConnectionFactory;
use App\Framework\Dbal\DatabaseUrl;
use App\Framework\Rendering\TemplateDirectory;
use App\Framework\Rendering\TemplateRenderer;
use App\Framework\Rendering\TwigTemplateRendererFactory;
use App\FrontPage\Infrastructure\DbalSubmissionsQuery;
use App\FrontPage\Presentation\SubmissionsQuery;
use Auryn\Injector;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

$injector = new Injector();

//----------
// Template
//----------
$injector->define(TemplateDirectory::class, [':rootDirectory' => ROOT_DIR]);

$injector->delegate(TemplateRenderer::class, function() use($injector): TemplateRenderer {
    $factory = $injector->make(TwigTemplateRendererFactory::class);
    return $factory->create();
});

//------------------
// SubmissionsQuery
//------------------
$injector->alias(SubmissionsQuery::class, DbalSubmissionsQuery::class);

// Use share() prevent the injector creating a new instance whenever an object is injected
// The same instance of the object is reused for all classes that use this dependency.
$injector->share(SubmissionsQuery::class);

//-----------------------
// Database Access Layer
//-----------------------
$injector->define(DatabaseUrl::class, [':url' => 'sqlite:///' . ROOT_DIR . '/storage/db.sqlite3']);

$injector->delegate(Connection::class, function() use($injector): Connection {
    $factory = $injector->make(ConnectionFactory::class);
    return $factory->create();
});

$injector->share(Connection::class);

//------------
// CSRF Token
//------------
$injector->alias(TokenStorage::class, SymfonySessionTokenStorage::class);
$injector->alias(SessionInterface::class, Session::class);

return $injector;
