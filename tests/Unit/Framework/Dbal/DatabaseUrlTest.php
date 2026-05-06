<?php

declare(strict_types=1);

namespace Tests\Unit\Framework\Dbal;

use App\Framework\Dbal\DatabaseUrl;
use PHPUnit\Framework\TestCase;

class DatabaseUrlTest extends TestCase
{
    public function test_toString_returns_provided_url(): void
    {
        $url = new DatabaseUrl('sqlite:///tmp/test.db');
        $this->assertSame('sqlite:///tmp/test.db', $url->toString());
    }

    public function test_toString_preserves_mysql_url(): void
    {
        $url = new DatabaseUrl('mysql://user:pass@localhost:3306/mydb');
        $this->assertSame('mysql://user:pass@localhost:3306/mydb', $url->toString());
    }
}
