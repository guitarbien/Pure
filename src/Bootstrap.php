<?php

declare(strict_types=1);

use App\FrontPage\Presentation\FrontPageController;
use App\Submission\Presentation\SubmissionController;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use Symfony\Component\HttpFoundation\Response;

define('ROOT_DIR', dirname(__DIR__));

require ROOT_DIR . '/vendor/autoload.php';

// use Debugger
\Tracy\Debugger::enable();

// use Symfony HttpFoundation to handle Request
$request = Symfony\Component\HttpFoundation\Request::createFromGlobals();

// keep the Request handle logic in FrontPageController
// Bootstrap is just responsible for showing the response
$dispatcher = simpleDispatcher(function(RouteCollector $r) {
    $r->addRoute('GET', '/', FrontPageController::class . '#show');
    $r->addRoute('GET', '/submit', SubmissionController::class . '#show');
});

if (!$response instanceof Response) {
    throw new \Exception('Controller methods must return a Response object');
}

$response->prepare($request);
$response->send();
