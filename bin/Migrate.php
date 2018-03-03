<?php

declare(strict_types=1);

use Auryn\Injector;
use Doctrine\DBAL\Connection;
use Migrations\Migration201803032130;

define('ROOT_DIR', dirname(__DIR__));

require ROOT_DIR . '/vendor/autoload.php';

/** @var Injector $injector */
$injector = include(ROOT_DIR . '/src/Dependencies.php');
$connection = $injector->make(Connection::class);

$migration = new Migration201803032130($connection);
$migration->migrate();

echo 'Finished running migrations' . PHP_EOL;
