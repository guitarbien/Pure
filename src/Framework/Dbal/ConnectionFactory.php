<?php

declare(strict_types=1);

namespace App\Framework\Dbal;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

/**
 * Class ConnectionFactory
 * @package App\Framework\Dbal
 */
final class ConnectionFactory
{
    /** @var DatabaseUrl */
    private $databaseUrl;

    /**
     * ConnectionFactory constructor.
     * @param DatabaseUrl $databaseUrl
     */
    public function __construct(DatabaseUrl $databaseUrl)
    {
        $this->databaseUrl = $databaseUrl;
    }

    /**
     * @return Connection
     * @throws \Doctrine\DBAL\DBALException
     */
    public function create(): Connection
    {
        return DriverManager::getConnection(
            ['url' => $this->databaseUrl->toString()],
            new Configuration()
        );
    }
}
