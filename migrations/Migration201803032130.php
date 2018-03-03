<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Connection;

/**
 * Class Migration201803032130
 * @package Migrations
 */
final class Migration201803032130
{
    /** @var Connection */
    private $connection;

    /**
     * Migration201803032130 constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function migrate(): void
    {

    }
}
