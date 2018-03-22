<?php

declare(strict_types=1);

define('ROOT_DIR', dirname(__DIR__));

require ROOT_DIR . '/vendor/autoload.php';

$injector   = include(ROOT_DIR . '/src/Dependencies.php');
$connection = $injector->make('Doctrine\DBAL\Connection');

$migrations = get_available_migrations();
$selected   = select_migration($migrations);

foreach ($migrations as $key => $migration) {
    if ($selected !== 0 && $selected !== $key + 1) {
        continue;
    }

    $class = "Migrations\\$migration";
    (new $class($connection))->migrate();

    echo "Running $migration..." . PHP_EOL;
}

echo 'Finished running migrations' . PHP_EOL;

/**
 * @return array
 */
function get_available_migrations(): array
{
    $migrations = [];

    foreach (new FilesystemIterator(ROOT_DIR . '/migrations') as $file) {
        $migrations[] = $file->getBasename('.php');
    }

    return array_reverse($migrations);
}

/**
 * @param array $migrations
 * @return int
 */
function select_migration(array $migrations): int
{
    echo "[0] All" . PHP_EOL;

    foreach ($migrations as $key => $name) {
        $index = $key + 1;
        echo "[$index] $name" . PHP_EOL;
    }

    $selected    = readline('Select the migration that you want tot run: ');
    $selectedKey = $selected - 1;

    if ($selected !== '0' && !array_key_exists($selectedKey, $migrations)) {
        exit('Invalid selection' . PHP_EOL);
    }

    return (int)$selected;
}
