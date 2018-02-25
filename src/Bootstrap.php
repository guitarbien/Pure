<?php

declare(strict_types=1);

use App\Framework\Rendering\TwigTemplateRendererFactory;
use Auryn\Injector;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Symfony\Component\HttpFoundation\Response;
use function FastRoute\simpleDispatcher;

define('ROOT_DIR', dirname(__DIR__));

require ROOT_DIR . '/vendor/autoload.php';

// use Debugger
\Tracy\Debugger::enable();

// use Symfony HttpFoundation to handle Request
$request = Symfony\Component\HttpFoundation\Request::createFromGlobals();

// use FastRoute to handle the route
// Bootstrap is just responsible for showing the response
$dispatcher = simpleDispatcher(function(RouteCollector $r) {
    $routes = include(ROOT_DIR . '/src/Routes.php');
    foreach ($routes as $route) {
        $r->addRoute(...$route);
    }
});

$routeInfo = $dispatcher->dispatch(
    $request->getMethod(),
    $request->getPathInfo()
);

switch ($routeInfo[0]) {
    case Dispatcher::NOT_FOUND:
        $response = new Response('Not Found', 404);
        break;
    case Dispatcher::METHOD_NOT_ALLOWED:
        $response = new Response('Method not allowed', 405);
        break;
    case Dispatcher::FOUND:
        [$controllerName, $method] = explode('#', $routeInfo[1]);
        $vars = $routeInfo[2];

        /** @var Injector $injector */
        $injector = include(ROOT_DIR . '/src/Dependencies.php');
        $controller = $injector->make($controllerName);

        $response = $controller->$method($request, $vars);
        break;
}

if (!$response instanceof Response) {
    throw new \Exception('Controller methods must return a Response object');
}

$response->prepare($request);
$response->send();
