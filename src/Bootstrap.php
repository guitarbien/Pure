<?php

declare(strict_types=1);

define('ROOT_DIR', dirname(__DIR__));

require ROOT_DIR . '/vendor/autoload.php';

\Tracy\Debugger::enable();

$request = Symfony\Component\HttpFoundation\Request::createFromGlobals();

$content = 'Hello ' . $request->get('name', 'default');

$response = new Symfony\Component\HttpFoundation\Response($content);
$response->prepare($request);
$response->send();
