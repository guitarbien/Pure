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
use App\Framework\RoleBasedAccessControl\SymfonySessionCurrentUserFactory;
use App\Framework\RoleBasedAccessControl\User;
use App\FrontPage\Application\SubmissionsQuery;
use App\FrontPage\Infrastructure\DbalSubmissionsQuery;
use App\Submission\Domain\SubmissionRepository;
use App\Submission\Infrastructure\DbalSubmissionRepository;
use App\User\Application\EmailTakenQuery;
use App\User\Domain\UserRepository;
use App\User\Infrastructure\DbalEmailTakenQuery;
use App\User\Infrastructure\DbalUserRepository;
use DI\Container;
use DI\ContainerBuilder;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use function DI\create;
use function DI\factory;
use function DI\get;

$builder = new ContainerBuilder();

$builder->addDefinitions([
    //----------
    // Template
    //----------
    TemplateDirectory::class => create()->constructor(ROOT_DIR),

    TemplateRenderer::class => factory(function (Container $c): TemplateRenderer {
        $factory = $c->get(TwigTemplateRendererFactory::class);

        return $factory->create();
    }),

    //------------------
    // SubmissionsQuery
    //------------------
    SubmissionsQuery::class => get(DbalSubmissionsQuery::class),

    //----------------------
    // SubmissionRepository
    //----------------------
    SubmissionRepository::class => get(DbalSubmissionRepository::class),

    //-----------------------
    // Database Access Layer
    //-----------------------
    DatabaseUrl::class => create()->constructor('sqlite:///' . ROOT_DIR . '/storage/db.sqlite3'),

    Connection::class => factory(function (Container $c): Connection {
        $factory = $c->get(ConnectionFactory::class);

        return $factory->create();
    }),

    //------------
    // CSRF Token
    //------------
    TokenStorage::class    => get(SymfonySessionTokenStorage::class),
    SessionInterface::class => get(Session::class),

    //---------------
    // Flash Message
    //---------------
    FlashMessenger::class => get(SymfonySessionFlashBag::class),

    //---------------
    // UserRepository
    //---------------
    UserRepository::class  => get(DbalUserRepository::class),
    EmailTakenQuery::class => get(DbalEmailTakenQuery::class),

    //---------------
    // User Permission
    //---------------
    User::class => factory(function (Container $c): User {
        $factory = $c->get(SymfonySessionCurrentUserFactory::class);

        return $factory->create();
    }),
]);

return $builder->build();
