<?php

declare(strict_types=1);

use App\Framework\Csrf\SymfonySessionTokenStorage;
use App\Framework\Csrf\TokenStorage;
use App\Framework\Dbal\ConnectionFactory;
use App\Framework\Dbal\DatabaseUrl;
use App\Framework\MessageContainer\FlashMessenger;
use App\Framework\MessageContainer\SymfonySessionFlashBag;
use App\Framework\Rendering\TemplateDirectory;
use App\Framework\Rendering\TemplateRenderer;
use App\Framework\Rendering\TwigTemplateRendererFactory;
use App\FrontPage\Infrastructure\DbalSubmissionsQuery;
use App\FrontPage\Application\SubmissionsQuery;
use App\Submission\Domain\SubmissionRepository;
use App\Submission\Infrastructure\DbalSubmissionRepository;
use App\User\Domain\UserRepository;
use App\User\Infrastructure\DbalUserRepository;
use Auryn\Injector;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

$injector = new Injector();

//----------
// Template
//----------
$injector->define(TemplateDirectory::class, [':rootDirectory' => ROOT_DIR]);

$injector->delegate(TemplateRenderer::class, function () use ($injector): TemplateRenderer {
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

//----------------------
// SubmissionRepository
//----------------------
$injector->alias(SubmissionRepository::class, DbalSubmissionRepository::class);

//-----------------------
// Database Access Layer
//-----------------------
$injector->define(DatabaseUrl::class, [':url' => 'sqlite:///' . ROOT_DIR . '/storage/db.sqlite3']);

$injector->delegate(Connection::class, function () use ($injector): Connection {
    $factory = $injector->make(ConnectionFactory::class);

    return $factory->create();
});

$injector->share(Connection::class);

//------------
// CSRF Token
//------------
$injector->alias(TokenStorage::class, SymfonySessionTokenStorage::class);
$injector->alias(SessionInterface::class, Session::class);


//---------------
// Flash Message
//---------------
$injector->alias(FlashMessenger::class, SymfonySessionFlashBag::class);
$injector->share(FlashMessenger::class);

//---------------
// UserRepository
//---------------
$injector->alias(UserRepository::class, DbalUserRepository::class);

return $injector;
